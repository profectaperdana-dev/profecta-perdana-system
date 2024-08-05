<?php

namespace App\Http\Controllers;

use App\Models\ProductCostSecondModel;
use App\Models\ProductTradeInModel;
use App\Models\ReturnTradeSaleDetailModel;
use App\Models\ReturnTradeSaleModel;
use App\Models\SecondProductModel;
use App\Models\SecondSaleDetailModel;
use App\Models\SecondSaleModel;
use App\Models\UserWarehouseModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SecondSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();
            $warehouse_second = WarehouseModel::where('type', 7)->whereIn('id_area', array_column($warehouse->toArray(), 'id_area'))->get();
            if (!empty($request->from_date)) {

                $invoice = SecondSaleModel::with('secondSaleBy')
                    ->whereIn('warehouse_id', array_column($warehouse_second->toArray(), 'id'))
                    ->whereBetween('second_sale_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {

                $invoice = SecondSaleModel::with('secondSaleBy')
                    ->whereIn('warehouse_id', array_column($warehouse_second->toArray(), 'id'))
                    ->where('second_sale_date', date('Y-m-d'))
                    ->latest()
                    ->get();
            }
            return datatables()->of($invoice)
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, '.', ',');
                })
                ->editColumn('second_sale_date', function ($data) {
                    return date('d F Y', strtotime($data->second_sale_date));
                })
                ->editColumn('secondSaleBy', function (SecondSaleModel $SecondSaleModel) {
                    return $SecondSaleModel->secondSaleBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) use ($warehouse_second) {
                    $user_warehouse = $warehouse_second;
                    return view('second_sale._option', compact('invoice', 'user_warehouse'))->render();
                })
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "Trade-In Sale Invoicing",
        ];
        return view('second_sale.index', $data);
    }
    public function printStruk($id)
    {

        $data = SecondSaleModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();

        $pdf = Pdf::loadView('second_sale.print_struk', compact('warehouse', 'data'));

        return $pdf->stream($data->second_sale_number . '.pdf');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Trade-In Sale';
        $warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->oldest('warehouses')->get();
        $user_warehouse = WarehouseModel::where('type', 7)->whereIn('id_area', array_column($warehouse->toArray(), 'id_area'))->oldest('warehouses')->get();
        return view('second_sale.create', compact('title', 'user_warehouse'));
    }
    public function select()
    {
        try {
            $product = [];
            $warehouse_id = request()->w;
            $warehouse = WarehouseModel::where('id', $warehouse_id)->first();

            if (request()->has('q')) {
                $search = request()->q;
                $product = SecondProductModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'second_products.products_id')
                    ->select('second_products.*', 'product_trade_ins.*, warehouses.*')
                    ->join('warehouses', 'warehouses.id', '=', 'second_products.warehouses_id')
                    ->select('second_products.*', 'product_trade_ins.*')
                    ->where('product_trade_ins.name_product_trade_in', 'LIKE', "%$search%")
                    ->where('warehouses.id', $warehouse->id)
                    ->where('second_products.qty', '>', 0)
                    ->oldest('product_trade_ins.name_product_trade_in')
                    ->get();
            } else {
                $product = SecondProductModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'second_products.products_id')
                    ->select('second_products.*', 'product_trade_ins.*, warehouses.*')
                    ->join('warehouses', 'warehouses.id', '=', 'second_products.warehouses_id')
                    ->select('second_products.*', 'product_trade_ins.*')
                    ->where('warehouses.id', $warehouse->id)
                    ->where('second_products.qty', '>', 0)
                    ->oldest('product_trade_ins.name_product_trade_in')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function cekQty($id_product)
    {
        $warehouse_id = request()->w;
        $warehouse = WarehouseModel::where('id', $warehouse_id)->first();

        $qty = SecondProductModel::join('warehouses', 'warehouses.id', '=', 'second_products.warehouses_id')
            ->select('second_products.*', 'warehouses.*')
            ->where('warehouses.id', $warehouse->id)
            ->where('second_products.products_id', $id_product)
            ->first();


        return response()->json($qty);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $model = new SecondSaleModel();
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->warehouse_id)
                ->first();
            $length = 3;
            $lastRecord = SecondSaleModel::where('warehouse_id', $request->warehouse_id)->latest()->first();
            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->second_sale_date)->format('m');
                $currentMonth = Carbon::now()->format('m');
                if ($lastRecordMonth != $currentMonth) {
                    // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $model->id_sort = $cust_number_id;
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
            $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $order_number = 'SSPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
            $model->second_sale_number = $order_number;
            $model->second_sale_date = Carbon::now()->format('Y-m-d');
            $model->created_by = Auth::user()->id;
            $model->warehouse_id = $request->warehouse_id;

            //* customer
            $model->customer_name = $request->customer;
            $model->customer_phone = $request->customer_phone;
            if ($request->customer_nik == null || $request->customer_email == '') {
                $model->customer_nik = '-';
                $model->customer_email = '-';
            } else {
                $model->customer_nik = $request->customer_nik;
                $model->customer_email = $request->customer_email;
            }
            $saved = $model->save();
            $total = 0;

            if ($saved) {
                $message_duplicate = '';
                foreach ($request->tradeFields as $key => $value) {
                    $model_detail = new SecondSaleDetailModel();
                    $model_detail->second_sale_id = $model->id;
                    $model_detail->product_second_id = $value['product_trade_in'];
                    $model_detail->qty = $value['qty'];
                    if ($value['disc_percent'] == null) {
                        $model_detail->discount = 0;
                    } else {
                        $model_detail->discount = $value['disc_percent'];
                    }
                    if ($value['disc_rp'] == null) {
                        $model_detail->discount_rp = 0;
                    } else {
                        $model_detail->discount_rp = $value['disc_rp'];
                    }
                    $check_duplicate = SecondSaleDetailModel::where('second_sale_id', $model_detail->second_sale_id)
                        ->where('product_second_id', $model_detail->product_second_id)
                        ->count();
                    if ($check_duplicate > 0) {
                        $message_duplicate = "You enter duplication of products. Please recheck the order you set.";
                        continue;
                    } else {
                        $get_harga = ProductCostSecondModel::where('id_product_trade_in', $model_detail->product_second_id)
                            ->where('id_warehouse', $model->warehouse_id)
                            ->first();
                        $diskon =  $value['disc_percent'] / 100;
                        $harga_diskon = $get_harga->price_sale * $diskon;
                        $diskon_rupiah = $value['disc_rp'];
                        $harga_akhir = $get_harga->price_sale - ($harga_diskon  + $diskon_rupiah);
                        $model_detail->price = $get_harga->price_sale;
                        $total += $harga_akhir * $value['qty'];
                        $detail_saved = $model_detail->save();

                        if ($detail_saved) {
                            $model_product = SecondProductModel::join('warehouses', 'warehouses.id', '=', 'second_products.warehouses_id')
                                ->select('second_products.*', 'warehouses.id_area')
                                ->where('warehouses.id', $request->warehouse_id)
                                ->where('second_products.products_id', $value['product_trade_in'])
                                ->first();
                            $model_product->qty = $model_product->qty - $value['qty'];
                            $model_product->save();
                        }
                    }
                }
            }
            $model->total = $total;
            $cek_save = $model->save();
            if (empty($message_duplicate && $cek_save)) {
                DB::commit();
                return redirect()->back()->with('success', 'Create Order Has Been Success');
            } else if (!empty($message_duplicate) && $cek_save) {
                DB::commit();
                return redirect()->back()->with('success', 'Create Order Has Been Success and ' . $message_duplicate);
            } else {
                DB::rollback();
                return redirect()->back()->with('error', 'Create Order Has Been Failed');
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('create/trade_in')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function getQtyDetail()
    {
        $so_id = request()->s;
        $product_id = request()->p;

        $getqty = SecondSaleDetailModel::where('second_sale_id', $so_id)->where('product_second_id', $product_id)->first();
        $_qty = $getqty->qty;
        $selected_return = ReturnTradeSaleModel::with('returnDetailsBy')->where('second_sale_id', $so_id)->get();

        $return = 0;
        if ($selected_return != null) {
            foreach ($selected_return as $value) {
                $selected_detail = ReturnTradeSaleDetailModel::where('return_id', $value->id)->where('product_id', $product_id)->first();
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
            $so_id = request()->p;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = SecondSaleDetailModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'second_sale_details.product_second_id')
                    ->select('product_trade_ins.name_product_trade_in', 'product_trade_ins.id AS id')
                    ->where('second_sale_details.second_sale_id', $so_id)
                    ->where('product_trade_ins.name_product_trade_in', 'LIKE', "%$search%")
                    ->oldest('product_trade_ins.name_product_trade_in')
                    ->get();
            } else {
                $product = SecondSaleDetailModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'second_sale_details.product_second_id')
                    ->select('product_trade_ins.name_product_trade_in', 'product_trade_ins.id AS id')
                    ->where('second_sale_details.second_sale_id', $so_id)
                    ->oldest('product_trade_ins.name_product_trade_in')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }
    public function editSuperadmin(Request $request, $id)
    {
        $model = SecondSaleModel::find($id);
        //* get customer
        $model->customer_name = $request->customer_name;
        $model->customer_phone = $request->customer_phone;
        $old_warehouse = $model->warehouse_id;
        $model->warehouse_id = $request->warehouse_id;

        if ($request->customer_email) {
            $model->customer_email = $request->customer_email;
        } else {
            $model->customer_email = '-';
        }
        if ($request->customer_nik) {
            $model->customer_nik = $request->customer_nik;
        } else {
            $model->customer_nik = '-';
        }
        //* created by
        $model->created_by = Auth::user()->id;
        $saved = $model->save();
        // save purchase order details
        $total = 0;

        $products_arr = [];
        foreach ($request->tradeFields as $check) {
            array_push($products_arr, $check['product_trade_in']);
        }
        // dd($products_arr);
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return redirect()->back()->with('error', "You enter duplicate products! Please check again!");
        }

        $po_restore = SecondSaleDetailModel::where('second_sale_id', $id)->get();
        foreach ($po_restore as $restore) {
            $stock = SecondProductModel::where('warehouses_id', $old_warehouse)
                ->where('products_id', $restore->product_second_id)->first();

            $stock->qty = $stock->qty + $restore->qty;
            $stock->save();
        }


        $total = 0;

        if ($saved) {
            foreach ($request->tradeFields as $value) {
                $data = SecondSaleDetailModel::where('second_sale_id', $id)
                    ->where('product_second_id', $value['product_trade_in'])
                    ->first();
                if ($data != null) {
                    $data->product_second_id = $value['product_trade_in'];
                    $data->discount = $value['disc_percent'];
                    $data->discount_rp = $value['disc_rp'];
                    $data->qty = $value['qty'];
                    $data->save();
                } else {
                    $detail = new SecondSaleDetailModel();
                    $detail->second_sale_id = $id;
                    $detail->product_second_id = $value['product_trade_in'];
                    $detail->discount = $value['disc_percent'];
                    $detail->discount_rp = $value['disc_rp'];
                    $detail->qty = $value['qty'];
                    $detail->save();
                }

                //? GET HARGA
                $get_harga = ProductCostSecondModel::where('id_product_trade_in', $value['product_trade_in'])
                    ->where('id_warehouse', $model->warehouse_id)
                    ->first();
                $diskon =  $value['disc_percent'] / 100;
                $harga_diskon = $get_harga->price_sale * $diskon;
                $diskon_rupiah = $value['disc_rp'];
                $harga_akhir = $get_harga->price_sale - ($harga_diskon  + $diskon_rupiah);
                $total += $harga_akhir * $value['qty'];

                $second_stock = SecondProductModel::where('warehouses_id', $request->warehouse_id)->where('products_id', $value['product_trade_in'])->first();

                // dd($second_stock);
                if ($second_stock == null) {
                    $second_stock = new SecondProductModel();
                    $second_stock->warehouses_id = $request->warehouse_id;
                    $second_stock->products_id = $value['product_trade_in'];
                    $second_stock->qty = $value['qty'];
                    $second_stock->save();
                } else {
                    $second_stock->qty = $second_stock->qty -  $value['qty'];
                    $second_stock->save();
                }
            }
            //Delete product that not in SOD Input
            $del = SecondSaleDetailModel::where('second_sale_id', $id)
                ->whereNotIn('product_second_id', $products_arr)->delete();
            $model->total = $total;
            $saved = $model->save();
            if (empty($message_duplicate) && $saved) {
                return redirect()->back()->with('success', 'Create Trade-In order ' . $model->second_sale_number . ' success');
            } elseif (!empty($message_duplicate) && $saved) {

                return redirect()->back()->with('info', 'Trade-In Order add Success! ' . $message_duplicate);
            } else {
                return redirect()->back()->with('error', 'Add Trade-In Order Fail! Please make sure you have filled all the input');
            }
        }
    }

    public function deleteTradeSale($id)
    {
        try {
            DB::beginTransaction();
            $return  = SecondSaleModel::where('id', $id)->first();
            // dd($return);
            $return_detail = SecondSaleDetailModel::where('second_sale_id', $id)->get();
            // dd($return->salesOrderBy->warehouse_id);
            // dd($return_detail);

            // dd($return_detail);
            foreach ($return_detail as $value) {
                $stock = SecondProductModel::where('warehouses_id', $return->warehouse_id)
                    ->where('products_id', $value->product_second_id)->first();
                $stock->qty = $stock->qty + $value->qty;
                $stock->save();
                $value->delete();
            }
            // dd($return);
            $return->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Second Sale Invoice has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', '. Please call your Most Valuable IT Team.');
        }
    }
}
