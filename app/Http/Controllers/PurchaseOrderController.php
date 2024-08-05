<?php

namespace App\Http\Controllers;

use App\Events\PoMessage;
use App\Models\Finance\Coa;
use App\Models\Finance\Journal;
use App\Models\Finance\JournalDetail;
use App\Models\JurnalDetailModel;
use App\Models\JurnalModel;
use App\Models\NotificationsModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderCodeModel;
use App\Models\PurchaseOrderCreditModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\PurchaseOrderModel;
use App\Models\ReturnPurchaseDetailModel;
use App\Models\ReturnPurchaseModel;
use App\Models\StockModel;
use App\Models\SuppliersModel;
use App\Models\TyreDotModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use PDF;
use Illuminate\Support\Facades\DB;
use function App\Helpers\createJournal;
use function App\Helpers\createJournalDetail;
use function App\Helpers\changeSaldoTambah;
use function App\Helpers\changeSaldoKurang;
use App\Models\Finance\CoaSaldo;
use stdClass;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $all_purchases = PurchaseOrderModel::where('isapprove', 0)
            ->whereIn('warehouse_id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
            ->latest()
            ->get();
        $all_suppliers = SuppliersModel::oldest('nama_supplier')->get();
        $all_warehouses = Auth::user()->userWarehouseBy;
        $ppn = ValueAddedTaxModel::first()->ppn / 100;

        $data = [
            "title" => "PO Approval",
            "purchases" => $all_purchases,
            "suppliers" => $all_suppliers,
            "warehouses" => $all_warehouses,
            'ppn' => $ppn
        ];

        return view('purchase_orders.index', $data);
    }
    public function deleteInvoice($id)
    {
        try {
            DB::beginTransaction();

            $sales_order = PurchaseOrderModel::find($id);

            // restore stock
            $sales_order_detail = PurchaseOrderDetailModel::where('purchase_order_id', $id)->get();

            foreach ($sales_order_detail as $key => $value) {
                if($sales_order->isvalidated == 1){
                    $stock = StockModel::where('products_id', $value->product_id)->where('warehouses_id', $sales_order->warehouse_id)->first();
                    $stock->stock = $stock->stock - $value->qty;
                    $stock->save();
                }
                
                $value->delete();
            }

            $sales_order->delete();
            // $sales_order::deleteee();
            DB::commit();

            return redirect()->back()->with('success', 'Purchase Order ' . $sales_order->order_number . ' has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function preview()
    {

        $all_purchases = PurchaseOrderModel::where('isapprove', 0)
            ->whereIn('warehouse_id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
            ->latest()
            ->get();
        $all_suppliers = SuppliersModel::oldest('nama_supplier')->get();
        $all_warehouses = Auth::user()->userWarehouseBy;
        $ppn = ValueAddedTaxModel::first()->ppn / 100;

        $data = [
            "title" => "PO Preview",
            "purchases" => $all_purchases,
            "suppliers" => $all_suppliers,
            "warehouses" => $all_warehouses,
            'ppn' => $ppn
        ];

        return view('purchase_orders.preview', $data);
    }
    public function getPO(Request $request)
    {
        if ($request->ajax()) {

            if (!empty($request->from_date)) {
                $purchase = PurchaseOrderModel::with('supplierBy', 'createdPurchaseOrder', 'warehouseBy')
                    ->where('isapprove', 1)
                    // ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->whereIn('warehouse_id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $purchase = PurchaseOrderModel::with('supplierBy', 'createdPurchaseOrder', 'warehouseBy')
                    ->where('isapprove', 1)
                    ->whereMonth('order_date', now()->month)
                    ->whereYear('order_date', now()->year)
                    ->latest()
                    ->get();
            }
            return datatables()->of($purchase)
                ->editColumn('isvalidated', function ($data) {
                    if ($data->isvalidated == 0) {
                        return 'Not Received';
                    } else {
                        return 'Received';
                    }
                })
                ->editColumn('order_date', function ($data) {
                    return date('d F Y', strtotime($data->order_date));
                })
                ->editColumn('remark', function ($data) {
                    return $data->remark;
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('supplier_id', fn ($purchase) => $purchase->supplierBy->nama_supplier)
                ->editColumn('warehouse_id', function ($data) {
                    return $data->warehouseBy->warehouses;
                })
                ->editColumn('isPaid', function ($data) {
                    if ($data->isPaid == 1) {
                        return '<b class="text-success fw-bold">Paid</b>';
                    } else return '<b class="text-danger fw-bold">Unpaid</b>';
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($purchase) {
                    $suppliers = SuppliersModel::latest()->get();
                    $warehouses = WarehouseModel::with('typeBy')->whereHas('typeBy', function ($query) {
                        $query->where('id', 5);
                    })->latest()->get();
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('purchase_orders._option', compact('purchase', 'suppliers', 'warehouses', 'ppn'))->render();
                })
                ->rawColumns(['isPaid'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            "title" => "Purchase Order",
            // 'order_number' =>
        ];

        return view('purchase_orders.po', $data);
    }
    public function printPO($id)
    {
        $data = PurchaseOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();

        $pdf = FacadePdf::loadView('purchase_orders.print_po', compact('data', 'warehouse'))->setPaper('A5', 'landscape');
        return $pdf->stream($data->pdf_po);
    }

    public function receivingPO()
    {

        $all_purchases = PurchaseOrderModel::where('isapprove', 1)
            ->where('isvalidated', 0)
            ->whereIn('warehouse_id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
            ->latest()
            ->get();

        $return_arr = [];
        foreach ($all_purchases as $value) {
            foreach ($value->purchaseOrderDetailsBy as $detail) {
                $return_amount = 0;
                $selected_return = ReturnPurchaseDetailModel::whereHas('returnBy', function ($query) use ($value) {
                    $query->where('purchase_order_id', $value->id);
                })
                    ->where('product_id', $detail->product_id)
                    ->get();
                foreach ($selected_return as $return) {
                    $return_amount += $return->qty;
                }
                array_push($return_arr, $return_amount);
            }
        }


        $data = [
            "title" => "PO Receiving",
            "purchases" => $all_purchases,
            "return_amount" => $return_arr
        ];

        return view('purchase_orders.receiving', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_suppliers = SuppliersModel::oldest('nama_supplier')->get();
        $all_warehouses = Auth::user()->userWarehouseBy->sortBy('warehouseBy.warehouses');

        $data = [
            "title" => "Create PO",
            "suppliers" => $all_suppliers,
            "warehouses" => $all_warehouses,
        ];

        return view('purchase_orders.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd('wait');
        // validator
        $request->validate([
            "supplier_id" => "required|numeric",
            "warehouse_id" => "required|numeric",
            "payment_method" => "required",
            "poFieldss.*.product_id" => "required|numeric",
            "poFieldss.*.qty" => "required|numeric"
        ]);

        try {
            DB::beginTransaction();


            // save purchase orders
            $model = new PurchaseOrderModel();
            $model->order_number = '-';
            $model->order_date = Carbon::now()->format('Y-m-d');
            $model->due_date = Carbon::now()->format('Y-m-d');
            $model->supplier_id = $request->get('supplier_id');
            $model->payment_method = $request->get('payment_method');
            $model->warehouse_id = $request->get('warehouse_id');
            $model->remark = '-';
            $model->created_by = Auth::user()->id;
            $model->isvalidated = 0;
            $model->isapprove = 0;
            if ($request->get('payment_method') == 'cash') {
                $model->isPaid = 1;
            } else $model->isPaid = 0;
            
            //Create Order Number
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->get('warehouse_id'))
                ->first();
            $lastRecord = PurchaseOrderModel::where('warehouse_id', $request->warehouse_id)->latest()->first();
            if ($lastRecord != null) {
                $lastRecordMonth = Carbon::parse($lastRecord->order_date)->format('m');
                $currentMonth = Carbon::now()->format('m');

                if ($lastRecordMonth != $currentMonth) {
                    // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $model->id_sort = $cust_number_id;
                    // dd($model->id_sort);
                } else {
                    // Jika masih dalam bulan yang sama, increment $cust_number_id
                    $cust_number_id = intval($lastRecord->id_sort) + 1;
                    $model->id_sort = $cust_number_id;
                }
            } else {
                // Jika belum ada record sebelumnya, set $cust_number_id menjadi 1
                $cust_number_id = 1;
                $model->id_sort = $cust_number_id;
            }

            $length = 3;
            // $id2 = intval(PurchaseOrderModel::where('warehouse_id', $request->get('warehouse_id'))->max('id')) + 1;
            $po_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $order_number = 'POPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $po_number_id;
           
            $model->order_number = $order_number;
            $model->pdf_po = $model->order_number . '.pdf';

            $saved = $model->save();

            // save purchase order details
            $total = 0;
            $message_duplicate = '';
            if ($saved) {
                // dd($request->poFieldss);
                foreach ($request->poFieldss as $value) {
                    $data = new PurchaseOrderDetailModel();
                    $data->product_id = $value['product_id'];
                    $data->qty = $value['qty'];
                    $data->purchase_order_id = $model->id;
                    $check_duplicate = PurchaseOrderDetailModel::where('purchase_order_id', $data->purchase_order_id)
                        ->where('product_id', $data->product_id)
                        ->count();
                    if ($check_duplicate > 0) {
                        $message_duplicate = "You enter duplication of products. Please recheck the PO you set.";
                        continue;
                    } else {
                        $harga = ProductModel::where('id', $data->product_id)->first();
                        $harga_double = Crypt::decryptString($harga->harga_beli);
                        $data->price = $harga_double;
                        $total = $total + ((float)$harga_double  * $data->qty);
                        
                        $data->save();
                    }
                    unset($value);
                }

                // dd(PurchaseOrderDetailModel::where('purchase_order_id', $model->id)->get());
            }
            $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $total;
            $model->total = $total + $ppn;
            $saved = $model->save();


            if (empty($message_duplicate) && $saved) {
                $message = 'There is a purchase that must be checked!';
                event(new PoMessage('From:' . Auth::user()->name, $message));
                $notif = new NotificationsModel();
                $notif->message = $message;
                $notif->status = 0;
                $notif->job_id = 39;
                $notif->save();
                DB::commit();
                return redirect('/purchase_orders/create')->with('success', 'Create purchase order ' . $model->order_number . ' success');
            } elseif (!empty($message_duplicate) && $saved) {
                $message = 'There is a purchase that must be checked!';
                event(new PoMessage('From:' . Auth::user()->name, $message));
                $notif = new NotificationsModel();
                $notif->message = $message;
                $notif->status = 0;
                $notif->job_id = 39;
                $notif->save();
                DB::commit();
                return redirect('/purchase_orders/create')->with('info', 'Purchase Order add Success! ' . $message_duplicate);
            } else {
                DB::rollback();
                return redirect('/purchase_orders/create')->with('error', 'Add Purchase Order Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/purchase_orders/create')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function reject($id)
    {
        try {
            DB::beginTransaction();
            $purchase = PurchaseOrderModel::where('id', $id)->first();
            $detail = $purchase->purchaseOrderDetailsBy()->delete();

            $purchase->delete();

            DB::commit();

            return redirect('/purchase_orders')->with('error', 'Purchase reject success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/purchase_orders')->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function manage(Request $request, $id)
    {
        // dd($request->all());
        // validator
        $request->validate([
            "supplier_id" => "required|numeric",
            "warehouse_id" => "required|numeric",
            "order_date" => "required",
            "payment_method" => "required",
            "remark" => "required",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.qty" => "required|numeric"
        ]);

        try {
            DB::beginTransaction();
            //Check Duplicate
            $products_arr = [];
            foreach ($request->poFields as $check) {
                array_push($products_arr, $check['product_id']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return redirect('/purchase_orders')->with('error', "You enter duplicate products! Please check again!");
            }
            //assign object
            $model = PurchaseOrderModel::where('id', $id)->first();
            $model->order_date = date('Y-m-d', strtotime($request->get('order_date')));
            $model->top = $request->get('top');
            $dt = new DateTimeImmutable(date('Y-m-d', strtotime($model->order_date)), new DateTimeZone('Asia/Jakarta'));
            // dd($dt);
            $dt = $dt->modify("+" . $model->top . " days");
            $model->due_date = $dt;
            $model->payment_method = $request->get('payment_method');
            $model->supplier_id = $request->get('supplier_id');
            $model->warehouse_id = $request->get('warehouse_id');
            $model->remark = $request->get('remark');
            $old_isapprove = $model->isapprove;
            $model->isapprove = 1;
            $model->created_by = Auth::user()->id;
            $model->isPaid = 0;

            $saved = $model->save();

            //Save POD Input and Total
            $total = 0;
            foreach ($request->poFields as $product) {
                $product_exist = PurchaseOrderDetailModel::where('purchase_order_id', $id)
                    ->where('product_id', $product['product_id'])->first();
                $harga = ProductModel::where('id', $product['product_id'])->first();
                // dd($product_exist);
                if ($product_exist != null) {
                    $harga_double = $product_exist->price;
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();
                } else {
                    $new_product = new PurchaseOrderDetailModel();
                    $new_product->purchase_order_id = $id;
                    $new_product->product_id = $product['product_id'];
                    $new_product->price = Crypt::decryptString($harga->harga_beli);
                    $harga_double = $new_product->price;
                    $new_product->qty = $product['qty'];
                    $new_product->save();
                }
                if ($harga_double == null || $harga_double == 0) {
                    $harga_double = Crypt::decryptString($harga->harga_beli);
                }
                $total = $total + ($harga_double * $product['qty']);
                unset($product);
            }

            //Delete product that not in POD Input
            $del = PurchaseOrderDetailModel::where('purchase_order_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            //Save total
            $ppn = (ValueAddedTaxModel::first()->ppn / 100)  * $total;
            $model->total = $total + $ppn;

            $saved_model = $model->save();


            // $model::creats();
            if ($saved_model == true) {
                $data = PurchaseOrderModel::where('order_number', $model->order_number)->first();
                $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
                $pdf = FacadePdf::loadView('purchase_orders.print_po', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $model->order_number . '.pdf');

                DB::commit();
                return redirect()->back()->with('success', "Purchase Order Update Success");
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', "Purchase Order Update Fail! Please check again!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function validation(Request $request, $id)
    {
        // dd('Sedang maintenance');
        // validator
        $request->validate([
            "remark" => "required",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.qty" => "required|numeric"
        ]);

        try {
            DB::beginTransaction();
            //Check Duplicate
            $products_arr = [];
            foreach ($request->poFields as $check) {
                array_push($products_arr, $check['product_id']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return redirect('/purchase_orders')->with('error', "You enter duplicate products! Please check again!");
            }

            //assign object
            $model = PurchaseOrderModel::where('id', $id)->first();
            $model->remark = $request->get('remark');
            $saved = $model->save();

            //Save POD Input and Total
            $total = 0;
            foreach ($request->poFields as $product) {
                $product_exist = PurchaseOrderDetailModel::where('purchase_order_id', $id)
                    ->where('product_id', $product['product_id'])->first();
                $harga = ProductModel::where('id', $product['product_id'])->first();

                if ($product_exist != null) {
                    $product_exist->qty = $product['qty'];
                    $harga_double = $product_exist->price;
                    $product_exist->save();
                } else {
                    $new_product = new PurchaseOrderDetailModel();
                    $new_product->purchase_order_id = $id;
                    $new_product->product_id = $product['product_id'];
                    $new_product->price = Crypt::decryptString($harga->harga_beli);
                    $harga_double = $new_product->price;
                    $new_product->qty = $product['qty'];
                    $new_product->discount = 0;
                    $new_product->save();
                    $product_exist = $new_product;
                }


                //save DOT
                $keys = array_keys($product);
                $lastKey = end($keys);
                if (is_numeric($lastKey)) {
                    for ($i = 0; $i <= $lastKey; $i++) {
                        if (isset($product[$i]['qtyDot'])) {
                            $code = new PurchaseOrderCodeModel();
                            $code->purchase_detail_id = $product_exist->id;
                            $code->dot = $product[$i]['week'] . '/' . $product[$i]['year'];
                            $code->qty = $product[$i]['qtyDot'];
                            $code->save();
                        }
                    }

                    //Change DOT Stock
                    for ($i = 0; $i <= $lastKey; $i++) {
                        if (isset($product[$i]['qtyDot'])) {
                            $getDot = TyreDotModel::where('id_product', $product['product_id'])
                                ->where('id_warehouse', $model->warehouse_id)
                                ->where('dot',  $product[$i]['week'] . '/' . $product[$i]['year'])
                                ->first();
                            if ($getDot == null) {
                                $newDot = new TyreDotModel();
                                $newDot->id_product = $product['product_id'];
                                $newDot->id_warehouse =  $model->warehouse_id;
                                $newDot->dot = $product[$i]['week'] . '/' . $product[$i]['year'];
                                $newDot->qty = $product[$i]['qtyDot'];
                                $newDot->save();
                            } else {
                                $getDot->qty = $getDot->qty + $product[$i]['qtyDot'];
                                $getDot->save();
                            }
                        }
                    }
                }

                if ($harga_double == null || $harga_double == 0) {
                    $harga_double = Crypt::decryptString($harga->harga_beli);
                }
                
                $harga_diskon = $harga_double - ($harga_double * ($product_exist->discount / 100));
                $total += $harga_diskon * $product['qty'];
                
                /* Rumusnya
                HPP Baru = (Total Pembelian Baru + Total Pembelian Lama) / (Total Qty Baru + Total Qty Lama)
                */

                //** Hitung HPP/Barang Pembelian */
                $total_pemebelian_baru = $product['qty'] * $harga_diskon; //**total pembelian baru
                $qty_pembelian_baru = $product['qty']; //**total qty pembelian baru

                //** Hitung HPP/Barang Lama */
                $hpp_sebelumnya = ProductModel::where('id', $product['product_id'])->first(); //**hpp sebelumnya
                $hpp_sebelumnya->old_hpp = $hpp_sebelumnya->hpp;
                $hpp_sebelumnya->save(); //** simpan HPP lama ke kolom old_hpp */
                $qty_sebelumnya = StockModel::where('products_id', $product['product_id'])
                    ->whereIn('warehouses_id', [1, 8])
                    ->sum('stock'); //**qty sebelumnya
                $total_pembelian_lama = $hpp_sebelumnya->hpp * $qty_sebelumnya; //**total pembelian lama

                //** Hitung HPP Baru */
                $total_pembelian = $total_pemebelian_baru + $total_pembelian_lama; //**total pembelian baru + lama
                $total_qty = $qty_pembelian_baru + $qty_sebelumnya; //**total qty baru + lama
                $hpp_baru  = $total_pembelian / $total_qty; //**hpp baru

                //** update hpp yang baru */
                $update_hpp = ProductModel::where('id', $product['product_id'])->first();
                $update_hpp->hpp = $hpp_baru;
                $update_hpp->save();
            }

            //Delete product that not in POD Input
            $del = PurchaseOrderDetailModel::where('purchase_order_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            //Change Stock
            $selected_pod = PurchaseOrderDetailModel::where('purchase_order_id', $id)->get();
            foreach ($selected_pod as $pod) {
                $stock = StockModel::where('warehouses_id', $model->warehouse_id)
                    ->where('products_id', $pod->product_id)->first();
                if ($stock == null) {
                    $new_stock = new StockModel();
                    $new_stock->products_id = $pod->product_id;
                    $new_stock->warehouses_id = $model->warehouse_id;
                    $new_stock->stock = $pod->qty;
                    $new_stock->save();
                } else {
                    $stock->stock = $stock->stock + $pod->qty;
                    $stock->save();
                }
            }

            //Save total
            $model->isvalidated = 1;
            $ppn = (ValueAddedTaxModel::first()->ppn / 100)  * $total;
            $model->total = $total + $ppn;
            // dd($model);
            $saved_model = $model->save();
            if ($saved_model == true) {
                // ** Jurnal Pembelian Barang Dagang ** //
                $journal = createJournal(
                    Carbon::now()->format('Y-m-d'),
                    'Pembelian Barang Dagang No.' . $model->order_number,
                    $model->warehouse_id
                );


                // ** Perubahan Saldo Pajak Masukan ** //
                $get_coa_p_masukan =  Coa::where('coa_code', '1-600')->first()->id;
                changeSaldoTambah($get_coa_p_masukan, $model->warehouse_id, $ppn);

                // ** Perubahan Saldo Persediaan Barang Dagang ** //
                $get_coa_persediaan =  Coa::where('coa_code', '1-401')->first()->id;
                changeSaldoTambah($get_coa_persediaan, $model->warehouse_id, $total);

                // ** Perubahan Saldo Hutang Dagang ** //
                $get_coa_hutang_dagang =  Coa::where('coa_code', '2-101')->first()->id;
                changeSaldoTambah($get_coa_hutang_dagang, $model->warehouse_id, $model->total);


                // ** Jika Jurnal Berhasil Disimpan ** //
                if ($journal != "" && $journal != null && $journal != false) {

                    // ** COA Persediaan Barang Dagang ** //
                    createJournalDetail(
                        $journal,
                        '1-401',
                        $model->order_number,
                        $total,
                        0
                    );


                    // ** COA Pajak Masukan ** //
                    createJournalDetail(
                        $journal,
                        '1-600',
                        $model->order_number,
                        $ppn,
                        0
                    );



                    // ** COA Hutang Dagang ** //
                    createJournalDetail(
                        $journal,
                        '2-101',
                        $model->order_number,
                        0,
                        $model->total
                    );
                }
                $model->journal_id = $journal;
                $model->save();
                
                DB::commit();
                return redirect('/purchase_orders/receiving')->with('success', "Purchase Order Validation Success");
            } else {
                DB::rollBack();
                return redirect('/purchase_orders/receiving')->with('error', "Purchase Order Validation Fail! Please check again!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/purchase_orders/receiving')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    public function updatePO(Request $request, $id)
    {
        
        try {
            DB::beginTransaction();
            // validator
            $request->validate([
                "supplier_id" => "required|numeric",
                "warehouse_id" => "required|numeric",
                "payment_method" => "required",
                "order_date" => "required",
                "remark" => "required",
                "poFields.*.product_id" => "required|numeric",
                "poFields.*.qty" => "required|numeric",
                'poFields.*.discount' => 'required'
            ]);
            //Check Duplicate
            $products_arr = [];
            foreach ($request->poFields as $check) {
                array_push($products_arr, $check['product_id']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return redirect('/purchase_orders')->with('error', "You enter duplicate products! Please check again!");
            }
            //assign object
            $model = PurchaseOrderModel::where('id', $id)->first();
            $model->order_date =date('Y-m-d', strtotime($request->get('order_date')));
            $model->top = $request->get('top');
            $dt = new DateTimeImmutable(date('Y-m-d', strtotime($model->order_date)), new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
            $model->due_date = $dt;
            $model->supplier_id = $request->get('supplier_id');
            $model->warehouse_id = $request->get('warehouse_id');
            $model->remark = $request->get('remark');
            $model->payment_method = $request->get('payment_method');
            // dd($model);
            // if ($request->get('payment_method') == 'cash') {
            //     $model->isPaid = 1;
            // } else $model->isPaid = 0;
            $saved = $model->save();

            if ($model->isvalidated == 1) {
                //Restore data to before changed
                $po_restore = PurchaseOrderDetailModel::where('purchase_order_id', $id)->get();
                foreach ($po_restore as $restore) {
                    $stock = StockModel::where('warehouses_id', $model->warehouse_id)
                        ->where('products_id', $restore->product_id)->first();
                    $stock->stock = $stock->stock - $restore->qty;
                    $stock->save();
                }
            }


            //Save POD Input and Total and Change Stock
            $total = 0;
            foreach ($request->poFields as $product) {
                $product_exist = PurchaseOrderDetailModel::where('purchase_order_id', $id)
                    ->where('product_id', $product['product_id'])->first();
                $harga = ProductModel::where('id', $product['product_id'])->first();


                if ($product_exist != null) {
                    $old_qty = $product_exist->qty;
                    $product_exist->qty = $product['qty'];
                    $harga_double = $product_exist->price;
                    $product_exist->discount = $product['discount'];
                    $product_exist->save();
                } else {
                    $new_product = new PurchaseOrderDetailModel();
                    $new_product->purchase_order_id = $id;
                    $new_product->product_id = $product['product_id'];
                    $new_product->price = Crypt::decryptString($harga->harga_beli);
                    $harga_double = $new_product->price;
                    $new_product->qty = $product['qty'];
                    $new_product->discount = $product['discount'];
                    $new_product->save();
                }
                if ($harga_double == null || $harga_double == 0) {
                    $harga_double = Crypt::decryptString($harga->harga_beli);
                }
                $harga_diskon = $harga_double - ($harga_double * ($product['discount'] / 100));
                $total += $harga_diskon * $product['qty'];

                if ($model->journal_id != null && $model->isvalidated == 1) {
                    /* Rumusnya
                        HPP Baru = (Total Pembelian Baru + Total Pembelian Lama) / (Total Qty Baru + Total Qty Lama)
                        */
                    //** Hitung HPP/Barang Pembelian */
                    $total_pemebelian_baru = $product['qty'] * $harga_diskon; //**total pembelian baru
                    $qty_pembelian_baru = $product['qty']; //**total qty pembelian baru

                    //** Hitung HPP/Barang Lama */
                    $hpp_sebelumnya = ProductModel::where('id', $product['product_id'])->first(); //**hpp sebelumnya
                    // $hpp_sebelumnya->old_hpp = $hpp_sebelumnya->hpp;
                    // $hpp_sebelumnya->save(); //** simpan HPP lama ke kolom old_hpp */
                    $qty_sebelumnya = StockModel::where('products_id', $product['product_id'])
                        ->whereIn('warehouses_id', [1, 8])
                        ->sum('stock'); //**qty sebelumnya
                    $total_pembelian_lama = $hpp_sebelumnya->old_hpp * $qty_sebelumnya; //**total pembelian lama

                    //** Hitung HPP Baru */
                    $total_pembelian = $total_pemebelian_baru + $total_pembelian_lama; //**total pembelian baru + lama
                    $total_qty = $qty_pembelian_baru + $qty_sebelumnya; //**total qty baru + lama
                    $hpp_baru  = $total_pembelian / $total_qty; //**hpp baru

                    //** update hpp yang baru */
                    $update_hpp = ProductModel::where('id', $product['product_id'])->first();
                    $update_hpp->hpp = $hpp_baru;
                    $update_hpp->save();
                }

                
            }
            
            //Save total
            $ppn = (ValueAddedTaxModel::first()->ppn / 100)  * $total;
            $model->total = $total + $ppn;
            // dd($model->total);

            //Delete product that not in POD Input
            $del = PurchaseOrderDetailModel::where('purchase_order_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            if ($model->isvalidated == 1) {
                //Change Stock
                $selected_pod = PurchaseOrderDetailModel::where('purchase_order_id', $id)->get();
                foreach ($selected_pod as $pod) {
                    $stock = StockModel::where('warehouses_id', $model->warehouse_id)
                        ->where('products_id', $pod->product_id)->first();
                    if ($stock == null) {
                        $new_stock = new StockModel();
                        $new_stock->products_id = $pod->product_id;
                        $new_stock->warehouses_id = $model->warehouse_id;
                        $new_stock->stock = $pod->qty;
                        $new_stock->save();
                    } else {
                        $stock->stock = $stock->stock + $pod->qty;
                        $stock->save();
                    }
                }
            }
            

            $saved_model = $model->save();
            if ($saved_model == true) {
                
                // * update jurnal
                if ($model->journal_id != null && $model->isvalidated == 1) {
                    $journal_detail = JournalDetail::where('journal_id', $model->journal_id)->get();
                    if ($journal_detail->count() > 0) {
                        $journal_detail->where('coa_code', '1-401')->first()->update(['debit' => $total]);
                        $journal_detail->where('coa_code', '1-600')->first()->update(['debit' => $ppn]);
                        $journal_detail->where('coa_code', '2-101')->first()->update(['credit' => $model->total]);
                    }
                }
                
                $data = PurchaseOrderModel::where('order_number', $model->order_number)->first();
                $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
                $pdf = FacadePdf::loadView('purchase_orders.print_po', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->order_number . '_edited.pdf');
                // dd($model);

                DB::commit();
                return redirect('/all_purchase_orders')->with('success', "Purchase Order Update Success");
            } else {

                DB::rollBack();
                return redirect('/purchase_orders')->with('error', "Purchase Order Update Fail! Please check again!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/purchase_orders/receiving')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!Gate::allows('level1')) {
            abort(403);
        }
        $modelPurchaseOrder = PurchaseOrderModel::where('id', $id)->first();
        $modelPurchaseOrder->purchaseOrderDetailsBy()->delete();
        $modelPurchaseOrder->delete();
        return redirect('/purchase_orders')->with('error', 'Delete Data Purchase Order Success');
    }

    public function getDot()
    {
        $retail_id = request()->r;
        $product_id = request()->p;

        $getdetail = PurchaseOrderDetailModel::where('purchase_order_id', $retail_id)->where('product_id', $product_id)->first();
        $dots = [];
        foreach ($getdetail->purchaseOrderCodeBy as $value) {
            $gettingDot = TyreDotModel::where('id_product', $product_id)->where('id_warehouse', $getdetail->purchaseOrderBy->warehouse_id)
                ->where('dot', $value->dot)->first();
            if ($value->dot != null && !in_array($value->dot, array_column($dots, 'dot'))) {
                $gettingDot = TyreDotModel::where('id_product', $product_id)->where('id_warehouse', $getdetail->purchaseOrderBy->warehouse_id)
                    ->where('dot', $value->dot)->first();
                $dot_obj = new stdClass();
                $dot_obj->id = $value->dot;
                $dot_obj->dot = $value->dot;

                array_push($dots, $dot_obj);
            }
        }



        $data = [
            'dots' => $dots,
        ];
        return response()->json($data);
    }

    public function paidManagement(Request $request)
    {
        // $purchase = PurchaseOrderModel::with('supplierBy', 'purchaseOrderDetailsBy', 'createdPurchaseOrder', 'warehouseBy')
        //     ->where('payment_method', 'credit')
        //     ->where('isapprove', 1)
        //     ->where('isPaid', 0)
        //     ->latest()
        //     ->get();
        // dd($purchase);

        // $purchase = PurchaseOrderModel::with(
        //     'supplierBy',
        //     'purchaseOrderDetailsBy',
        //     'createdPurchaseOrder',
        //     'warehouseBy',
        //     'purchaseOrderDetailsBy.productBy',
        //     'purchaseOrderDetailsBy.productBy.sub_types',
        //     'purchaseOrderDetailsBy.productBy.sub_materials'
        // )
        //     ->where('payment_method', 'credit')
        //     ->where('isapprove', 1)
        //     ->where('isPaid', 0)
        //     ->get()
        //     ->groupBy('supplier_id')
        //     ->sortBy(function ($item) {
        //         return $item->first()->supplierBy->nama_supplier;
        //     });

        // dd($purchase);

        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $purchase = PurchaseOrderModel::with(
                    'supplierBy',
                    'purchaseOrderDetailsBy',
                    'createdPurchaseOrder',
                    'warehouseBy',
                    'purchaseOrderDetailsBy.productBy',
                    'purchaseOrderDetailsBy.productBy.sub_types',
                    'purchaseOrderDetailsBy.productBy.sub_materials'
                )
                    // ->where('payment_method', 'credit')
                    ->where('isapprove', 1)
                    ->where('isPaid', 0)
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $purchase = PurchaseOrderModel::with(
                    'supplierBy',
                    'purchaseOrderDetailsBy',
                    'createdPurchaseOrder',
                    'warehouseBy',
                    'purchaseOrderDetailsBy.productBy',
                    'purchaseOrderDetailsBy.productBy.sub_types',
                    'purchaseOrderDetailsBy.productBy.sub_materials'
                )
                    // ->where('payment_method', 'credit')
                    ->where('isapprove', 1)
                    ->where('isPaid', 0)
                    ->get()
                    ->groupBy('supplier_id')
                    ->sortBy(function ($item) {
                        return $item->first()->supplierBy->nama_supplier;
                    });
            }
            return datatables()->of($purchase)
                ->editColumn('total', function ($data) {
                    $total_return = 0;
                    $total_sale = 0;
                    $total_credit = 0;

                    foreach ($data as $value) {
                        $total_return += ReturnPurchaseModel::where('purchase_order_id', $value->id)->sum('total');
                        $total_credit += PurchaseOrderCreditModel::where('purchase_order_id', $value->id)->sum('amount');
                        $total_sale += $value->total;
                    }

                    return number_format(($total_sale - $total_credit) - $total_return);
                })
                // ->editColumn('order_date', function ($data) {
                //     return date('d F Y', strtotime($data->order_date));
                // })
                // ->editColumn('due_date', function ($data) {
                //     return date('d F Y', strtotime($data->due_date));
                // })
                // ->editColumn('warehouse_id', function (PurchaseOrderModel $purchaseOrderModel) {
                //     return $purchaseOrderModel->warehouseBy->warehouses;
                // })
                // ->editColumn('created_by', function (PurchaseOrderModel $purchaseOrderModel) {
                //     return $purchaseOrderModel->createdPurchaseOrder->name;
                // })
                // ->editColumn('supplier_id', function (PurchaseOrderModel $purchaseOrderModel) {
                //     return $purchaseOrderModel->supplierBy->nama_supplier;
                // })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($purchase) {

                    $total_return = 0;
                    $total_purchase = 0;
                    $total_credit = 0;
                    $id_vendor = '';
                    $name_vendor = '';

                    foreach ($purchase as $value) {
                        $total_return += ReturnPurchaseModel::where('purchase_order_id', $value->id)->sum('total');
                        $total_credit += PurchaseOrderCreditModel::where('purchase_order_id', $value->id)->sum('amount');
                        $total_purchase += $value->total;
                    }
                    $id_vendor = $purchase->first()->supplierBy->id;
                    $name_vendor = $purchase->first()->supplierBy->nama_supplier;

                    return view('purchase_orders._option_paid_management', compact(
                        'purchase',
                        'total_purchase',
                        'total_return',
                        'total_credit',
                        'id_vendor',
                        'name_vendor'
                    ))->render();

                    // $total_return = ReturnPurchaseModel::where('purchase_order_id', $purchase->id)->sum('total');
                    // return view('purchase_orders._option_paid_management', compact('purchase', 'total_return'))->render();
                })
                ->rawColumns(['action'], ['customerBy'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "PO Settlement",
            // 'order_number' =>
        ];
        return view('purchase_orders.paid_management', $data);
    }

    public function updatePaid(Request $request, $id)
    {
        // dd('sedang di-fix');
        try {
            DB::beginTransaction();

            // $request->validate([
            //     "amount_method" => "required",
            //     "payment_date" => 'required'
            // ]);

            $selected_po = PurchaseOrderModel::where('id', $id)->first();

            //Save Sales Order Credit
            $array_date = [];
             $total_current_amount = 0;
            $id_current = [];
            foreach ($request->pay as $item) {

                $poc = new PurchaseOrderCreditModel();
                $poc->purchase_order_id = $selected_po->id;
                $poc->payment_date = date('Y-m-d', strtotime($item['payment_date']));
                if ($item['amount_method'] == 'full') {
                    $poc->amount = $selected_po->total - $selected_po->purchaseOrderReturnBy->sum('total') - $selected_po->purchaseOrderCreditsBy->sum('amount');
                } else {
                    $poc->amount = $item['amount'];
                }
                $poc->payment_method = $item['payment_method'];
                $poc->updated_by = Auth::user()->id;
                $poc->save();
                array_push($array_date, $poc->payment_date);
                $total_current_amount += $poc->amount;
                array_push($id_current, $poc->id);
            }
            sort($array_date);
            $last_date = end($array_date);
            //Count total amount instalment
            $all_poc = PurchaseOrderCreditModel::where('purchase_order_id', $id)->get();
            $total_amount = 0;
            $total_return = 0;
            $total_return = ReturnPurchaseModel::where('purchase_order_id', $id)->sum('total');
            foreach ($all_poc as $value) {
                $total_amount = $total_amount + $value->amount;
            }
            
            $ppn = $total_current_amount / 1.11 * (ValueAddedTaxModel::first()->ppn / 100);
            $total_ppn = $total_current_amount / 1.11;
            $journal = createJournal(
                Carbon::now()->format('Y-m-d'),
                'Pembayaran Hutang Dagang No.' . $selected_po->order_number,
                $selected_po->warehouse_id
            );

            // ** Perubahan Saldo Hutang Usaha ** //
            $get_coa_p_masukan =  Coa::where('coa_code', '2-101')->first()->id;
            changeSaldoKurang($get_coa_p_masukan, $selected_po->warehouse_id, $total_current_amount);

            // ** Perubahan Saldo Kas dan Bank ** //
            $get_coa_persediaan =  Coa::where('coa_code',  $request->coa_code)->first()->id;
            changeSaldoKurang($get_coa_persediaan, $selected_po->warehouse_id, $total_current_amount);

            // ** Perubahan Saldo PPN Masukan ** //
            // $get_coa_hutang_dagang =  Coa::where('coa_code', '1-600')->first()->id;
            // changeSaldoKurang($get_coa_hutang_dagang, $selected_po->warehouse_id,  $ppn);


            // ** Jika Jurnal Berhasil Disimpan ** //
            if ($journal != "" && $journal != null && $journal != false) {
                // ** COA Hutang Barang Dagang ** //
                createJournalDetail(
                    $journal,
                    '2-101',
                    $selected_po->order_number,
                    $total_current_amount,
                    0
                );
                // ** COA Cash & Bank ** //
                createJournalDetail(
                    $journal,
                    $request->coa_code,
                    $selected_po->order_number,
                    0,
                    $total_current_amount
                );
                // ** COA PPn Masukan ** //
                // createJournalDetail(
                //     $journal,
                //     '1-600',
                //     $selected_po->order_number,
                //     0,
                //     $ppn
                // );
            }
            foreach ($id_current as $id) {
                $poc = PurchaseOrderCreditModel::where('id', $id)->first();
                $poc->journal_id = $journal;
                $poc->save();
            }
            
            if (round($total_amount) >= (round($selected_po->total) - round($total_return))) {
                $selected_po->isPaid = 1;
                $selected_po->paid_date = date('Y-m-d', strtotime($last_date));
                $selected_po->save();

                DB::commit();
                return redirect('/purchase_orders/manage_payment')->with('success', "Order number " . $selected_po->order_number . " already paid!");
            } else {

                DB::commit();
                return redirect('/purchase_orders/manage_payment')->with('success', "Update Payment of Order number " . $selected_po->order_number . " Success!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/purchase_orders/manage_payment')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function cancelPaid(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            
            $total_return = ReturnPurchaseModel::where('purchase_order_id', $id)->sum('total');
            if ($total_return > 0) {
                // ** jika terjadi return ** //
                $tot_purchase = PurchaseOrderModel::where('id', $id)->first();
                $tot_credit = PurchaseOrderCreditModel::where('purchase_order_id', $id)->get();
                $data_kas = '';
                foreach ($tot_credit as $value) {
                    $journal = Journal::where('id', $value->journal_id)->first();
                    $journal_detail = JournalDetail::where('journal_id', $value->journal_id)
                        ->where('credit', '!=', 0)
                        ->orderBy('credit', 'desc') // Menyusun data berdasarkan 'credit' secara descending
                        ->first(); // Mengambil entri pertama dengan 'credit' terbesar
                    // dd($journal_detail);
                    $data_kas = $journal_detail->coa_code;
                }
                // ** Jika sudah terjadi pelunasan
                if (round($tot_purchase->total) - $tot_credit->sum('amount') == 0) {
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Persediaan Pembelian.' . $tot_purchase->order_number,
                        $tot_purchase->warehouse_id
                    );

                    // ** Perubahan Kas dan Bank ** //
                    $get_coa_p_masukan =  Coa::where('coa_code', $data_kas)->first()->id;
                    changeSaldoTambah($get_coa_p_masukan, $tot_purchase->warehouse_id, $tot_credit->sum('amount'));

                    // ** Perubahan Saldo Retur Pembelian ** //
                    $get_coa_persediaan =  Coa::where('coa_code',  '5-103')->first()->id;
                    changeSaldoTambah($get_coa_persediaan, $tot_purchase->warehouse_id, $tot_credit->sum('amount'));

                    // ** Perubahan Saldo PPN Masukan ** //
                    // $get_coa_hutang_dagang =  Coa::where('coa_code', '1-600')->first()->id;
                    // changeSaldoKurang($get_coa_hutang_dagang, $tot_purchase->warehouse_id, $tot_credit->sum('amount') / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));

                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA KAS ** //
                        createJournalDetail(
                            $journal,
                            $data_kas,
                            $tot_purchase->order_number,
                            $tot_credit->sum('amount'),
                            0
                        );
                        // ** COA Return Pembelian ** //
                        createJournalDetail(
                            $journal,
                            '5-103',
                            $tot_purchase->order_number,
                            0,
                            $tot_credit->sum('amount')
                        );
                        // ** COA PPn Masukan ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '1-600',
                        //     $tot_purchase->order_number,
                        //     0,
                        //     $tot_credit->sum('amount') / 1.11 * (ValueAddedTaxModel::first()->ppn / 100)
                        // );
                    }
                } else if ($tot_credit->count() > 0) {
                    // dd($tot_credit->count() > 0);
                    // ** ini jika sudah bayar setengah
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Persediaan Pembelian Tunai.' . $tot_purchase->order_number,
                        $tot_purchase->warehouse_id
                    );

                    // ** Perubahan Kas dan Bank ** //
                    $get_coa_p_masukan =  Coa::where('coa_code', $data_kas)->first()->id;
                    changeSaldoTambah($get_coa_p_masukan, $tot_purchase->warehouse_id, $tot_credit->sum('amount'));

                    // ** Perubahan Saldo Retur Pembelian ** //
                    $get_coa_persediaan =  Coa::where('coa_code',  '5-103')->first()->id;
                    changeSaldoTambah($get_coa_persediaan, $tot_purchase->warehouse_id, $tot_credit->sum('amount'));

                    // // ** Perubahan Saldo PPN Masukan ** //
                    // $get_coa_hutang_dagang =  Coa::where('coa_code', '1-600')->first()->id;
                    // changeSaldoKurang($get_coa_hutang_dagang, $tot_purchase->warehouse_id, $tot_credit->sum('amount') / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));

                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA KAS ** //
                        createJournalDetail(
                            $journal,
                            $data_kas,
                            $tot_purchase->order_number,
                            $tot_credit->sum('amount'),
                            0
                        );
                        // ** COA Return Pembelian ** //
                        createJournalDetail(
                            $journal,
                            '5-103',
                            $tot_purchase->order_number,
                            0,
                            $tot_credit->sum('amount')
                        );
                        // ** COA PPn Masukan ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '1-600',
                        //     $tot_purchase->order_number,
                        //     0,
                        //     $tot_credit->sum('amount') / 1.11 * (ValueAddedTaxModel::first()->ppn / 100)
                        // );
                    }

                    // ** ini sisa yang belum bayar
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Persediaan Pembelian Kredit.' . $tot_purchase->order_number,
                        $tot_purchase->warehouse_id
                    );

                    // ** Perubahan Hutang Usaha ** //
                    $get_coa_p_masukan =  Coa::where('coa_code', '2-101')->first()->id;
                    changeSaldoKurang($get_coa_p_masukan, $tot_purchase->warehouse_id, round($tot_purchase->total) - $tot_credit->sum('amount'));

                    // ** Perubahan Saldo Retur Pembelian ** //
                    $get_coa_persediaan =  Coa::where('coa_code',  '5-103')->first()->id;
                    changeSaldoTambah($get_coa_persediaan, $tot_purchase->warehouse_id, (round($tot_purchase->total) - $tot_credit->sum('amount')) / 1.11);

                    // ** Perubahan Saldo PPN Masukan ** //
                    // $get_coa_hutang_dagang =  Coa::where('coa_code', '1-600')->first()->id;
                    // changeSaldoKurang($get_coa_hutang_dagang, $tot_purchase->warehouse_id, (round($tot_purchase->total) - $tot_credit->sum('amount')) / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));

                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA Hutang Dagang ** //
                        createJournalDetail(
                            $journal,
                            '2-101',
                            $tot_purchase->order_number,
                            round($tot_purchase->total) - $tot_credit->sum('amount'),
                            0
                        );
                        // ** COA Return Pembelian ** //
                        createJournalDetail(
                            $journal,
                            '5-103',
                            $tot_purchase->order_number,
                            0,
                            (round($tot_purchase->total) - $tot_credit->sum('amount'))
                        );
                        // ** COA PPn Masukan ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '1-600',
                        //     $tot_purchase->order_number,
                        //     0,
                        //     (round($tot_purchase->total) - $tot_credit->sum('amount')) / 1.11 * (ValueAddedTaxModel::first()->ppn / 100)
                        // );
                    }
                } else {
                    // ** ini sisa yang belum bayar
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Persediaan Pembelian Kredit.' . $tot_purchase->order_number,
                        $tot_purchase->warehouse_id
                    );

                    // ** Perubahan Hutang Usaha ** //
                    $get_coa_p_masukan =  Coa::where('coa_code', '2-101')->first()->id;
                    changeSaldoKurang($get_coa_p_masukan, $tot_purchase->warehouse_id, round($tot_purchase->total));

                    // ** Perubahan Saldo Retur Pembelian ** //
                    $get_coa_persediaan =  Coa::where('coa_code',  '5-103')->first()->id;
                    changeSaldoTambah($get_coa_persediaan, $tot_purchase->warehouse_id,   round($tot_purchase->total)  / 1.11);

                    // ** Perubahan Saldo PPN Masukan ** //
                    // $get_coa_hutang_dagang =  Coa::where('coa_code', '1-600')->first()->id;
                    // changeSaldoKurang($get_coa_hutang_dagang, $tot_purchase->warehouse_id, round($tot_purchase->total)  / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));
                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA Hutang Dagang ** //
                        createJournalDetail(
                            $journal,
                            '2-101',
                            $tot_purchase->order_number,
                            round($tot_purchase->total),
                            0
                        );
                        // ** COA Return Pembelian ** //
                        createJournalDetail(
                            $journal,
                            '5-103',
                            $tot_purchase->order_number,
                            0,
                            round($tot_purchase->total)
                        );
                        // ** COA PPn Masukan ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '1-600',
                        //     $tot_purchase->order_number,
                        //     0,
                        //     round($tot_purchase->total)  / 1.11 * (ValueAddedTaxModel::first()->ppn / 100)
                        // );
                    }
                }
            } else {
                // ** jika hanya cancel payment saja ** //
                $poc = PurchaseOrderCreditModel::where('purchase_order_id', $id)->get();
                foreach ($poc as $value) {
                    $journal = Journal::where('id', $value->journal_id)->first();
                    $journal->jurnal_detail()->delete();
                    $journal->delete();
                }
            }
            
            foreach ($request->cancel as $value) {
                $credits = PurchaseOrderCreditModel::where('id', $value['credit_id'])->first();
                $credits->amount = $credits->amount - $value['amount'];
                if ($credits->amount <= 0) {
                    $credits->delete();
                } else {
                    $credits->save();
                }
            }
            // $total_return = ReturnPurchaseModel::where('purchase_order_id', $id)->sum('total');
            $total_credit = PurchaseOrderCreditModel::where('purchase_order_id', $id)->sum('amount');
            $purchase = PurchaseOrderModel::where('id', $id)->first();
            if (round($purchase->total) - $total_return == $total_credit) {
                $purchase->isPaid = 1;
                $purchase->paid_date = date('Y-m-d');
                $purchase->save();
            }


            DB::commit();
            return redirect('/purchase_orders/manage_payment')->with('error', 'Cancel payment success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function getTotalInstalment($id)
    {
        $poc = PurchaseOrderCreditModel::where('purchase_order_id', $id)->get();

        $total_amount = 0;
        foreach ($poc as $value) {
            $total_amount = $total_amount + $value->amount;
        }
        return response()->json($total_amount);
    }

    public function getAllDetail()
    {
        $po_id = request()->p;

        $getqty = PurchaseOrderDetailModel::where('purchase_order_id', $po_id)->get();
        return response()->json($getqty);
    }
    public function getQtyDetail()
    {
        $po_id = request()->p;
        $product_id = request()->pr;

        $getqty = PurchaseOrderDetailModel::where('purchase_order_id', $po_id)->where('product_id', $product_id)->first();
        $_qty = $getqty->qty;
        $selected_return = ReturnPurchaseModel::with('returnDetailsBy')->where('purchase_order_id', $po_id)->get();

        $return = 0;
        if ($selected_return != null) {
            foreach ($selected_return as $value) {
                $selected_detail = ReturnPurchaseDetailModel::where('return_id', $value->id)->where('product_id', $product_id)->first();
                $return = $return + $selected_detail->qty;
            }
        }
        $data = [
            'qty' => $_qty,
            'return' => $return
        ];
        return response()->json($data);
    }

    public function selectReturn()
    {
        try {
            $po_id = request()->p;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = PurchaseOrderDetailModel::join('products', 'products.id', '=', 'purchase_order_details.product_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('products.nama_barang', 'LIKE', "%$search%")
                    ->where('purchase_order_id', $po_id)
                    ->orWhere('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->where('purchase_order_id', $po_id)
                    ->orWhere('product_sub_materials.nama_sub_material', 'LIKE', "%$search%")
                    ->where('purchase_order_id', $po_id)
                    ->get();
            } else {
                $product = PurchaseOrderDetailModel::join('products', 'products.id', '=', 'purchase_order_details.product_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('purchase_order_id', $po_id)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
