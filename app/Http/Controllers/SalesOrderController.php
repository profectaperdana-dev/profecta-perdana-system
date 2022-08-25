<?php

namespace App\Http\Controllers;

use DateTimeZone;
use Carbon\Carbon;
use DateTimeImmutable;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Events\SOMessage;
use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Models\NotificationsModel;
use App\Models\SalesOrderModel;
use Illuminate\Support\Facades\Auth;
use App\Models\SalesOrderDetailModel;
use App\Models\StockModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
// use Barryvdh\DomPDF\PDF;
use PDF;

use function PHPUnit\Framework\isEmpty;

class SalesOrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // index() : CREATE SALES ORDERS
    public function index()

    {
        $title = 'Create Sales Order';
        $product = ProductModel::latest()->get();
        $customer = CustomerModel::where('status', 1)->latest()->get();

        return view('sales_orders.index', compact('title', 'product', 'customer'));
    }


    // print invoice dengan PPN
    public function printInoiceWithPpn($id)
    {
        $data = SalesOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = PDF::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape');
        return $pdf->download('ahsha.pdf');
    }


    // getRecentData() : READ DATA RECENT SALES ORDERS ADMIN & SALES ADMIN
    public function getRecentData()
    {
        $title = 'Recent Sales Order';
        $product = ProductModel::latest()->get();
        $customer = CustomerModel::where('status', 1)->latest()->get();
        // get kode area
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', Auth::user()->warehouse_id)
            ->first();
        // get sales no debt
        $dataSalesOrder = SalesOrderModel::whereIn('payment_method', [1, 2])->where('order_number', 'like', "%$kode_area->area_code%")->latest()->get();

        // get sales with
        $dataSalesOrderDebt = SalesOrderModel::where('payment_method', 3)->where('order_number', 'like', "%$kode_area->area_code%")->latest()->get();


        return view('recent_sales_order.index', compact('title', 'dataSalesOrder', 'dataSalesOrderDebt', 'product', 'customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  store() : SIMPAN DATA CREATE SALES ORDERS
    public function store(Request $request)
    {
        // validasi sebelum save
        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",
            "remark" => "required",
            "soFields.*.product_id" => "required|numeric",
            "soFields.*.qty" => "required|numeric"
        ]);

        // query cek kode warehouse/area sales orders
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', Auth::user()->warehouse_id)
            ->first();
        $length = 3;
        $id = intval(SalesOrderModel::where('order_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $order_number = 'SOPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
        //

        // save sales orders
        $model = new SalesOrderModel();
        $model->order_number = $order_number;
        $model->order_date = Carbon::now()->format('Y-m-d');
        $model->customers_id = $request->get('customer_id');
        $model->remark = $request->get('remark');
        $model->created_by = Auth::user()->id;
        $model->payment_method = $request->get('payment_method');

        // metode bayar
        if ($model->payment_method == 3) {
            $model->top = $request->get('top');
            $dt = new DateTimeImmutable(Carbon::now()->format('Y-m-d'), new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
            $model->duedate = $dt;
        } else {
            $model->top = NULL;
            $model->duedate = NULL;
        }
        $model->isapprove = 0;
        $model->isverified = 0;

        $model->save();

        // save sales order details
        $total = 0;
        $message_duplicate = '';
        if ($model->save()) {
            foreach ($request->soFields as $key => $value) {
                $data = new SalesOrderDetailModel();
                $data->products_id = $value['product_id'];
                $data->qty = $value['qty'];
                if ($value['discount'] == NULL) {
                    $data->discount = 0;
                } else {
                    $data->discount = $value['discount'];
                }
                $data->sales_orders_id = $model->id;
                $data->created_by = Auth::user()->id;
                $check_duplicate = SalesOrderDetailModel::where('sales_orders_id', $data->sales_orders_id)
                    ->where('products_id', $data->products_id)
                    ->count();
                if ($check_duplicate > 0) {
                    $message_duplicate = "You enter duplication of products. Please recheck the SO you set.";
                    continue;
                } else {
                    $harga = ProductModel::where('id', $data->products_id)->first();
                    $diskon =  $value['discount'] / 100;
                    $hargaDiskon = $harga->harga_jual_nonretail * $diskon;
                    $hargaAfterDiskon = $harga->harga_jual_nonretail -  $hargaDiskon;
                    $total = $total + ($hargaAfterDiskon * $data->qty);
                    $data->save();
                }
            }
        }
        $ppn = 0.11 * $total;
        $model->ppn = $ppn;
        $model->total = $total;
        $model->total_after_ppn = $total + $ppn;
        $model->save();

        if (isEmpty($message_duplicate)) {
            $message = $model->order_number . ' Sales Order has been created! Please check';
            event(new SOMessage('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->role_id = 5;
            $notif->save();
            return redirect('/sales_order')->with('success', 'Create sales orders ' . $model->order_number . ' success');
        } elseif (!empty($message_duplicate)) {
            $message = $model->order_number . ' Sales Order has been created! Please check';
            event(new SOMessage('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->role_id = 5;
            $notif->save();
            return redirect('/sales_order')->with('success', 'Some of SO add maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/sales_order')->with('error', 'Add Sales Order Fail! Please make sure you have filled all the input');
        }
    }

    // editSo() :TAMPILAN EDIT SALES ORDER TANPA PRODUCT
    public function editSo($id)
    {
        $title = 'Edit Data Sales Order';
        $value = SalesOrderModel::find($id);
        $customer = CustomerModel::where('status', 1)->latest()->get();
        return view('recent_sales_order.edit', compact('title', 'value', 'customer'));
    }
    // updateSo() : PROSES UPDATE SALES ORDER TANPA PRODUCT
    public function updateSo(Request $request, $id)
    {
        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",
        ]);
        $model = SalesOrderModel::find($id);
        // if ($request->get('customer_id') != $model->customers_id) {

        $arraySod = [];
        $arrayDiscount = [];
        $sod = SalesOrderDetailModel::where('sales_orders_id', $id)->get();
        foreach ($sod as $key => $value) {
            array_push($arraySod, $value->products_id);
            array_push($arrayDiscount, $value->discount);
        }
        $customer_id = $request->get('customer_id');
        $total = 0;

        foreach ($sod as $key => $item) {
            $discount = DiscountModel::where('customer_id', $customer_id)
                ->where('product_id', $item->products_id)->first();
            $discountValue = 0;
            if (!isset($discount)) {
                $discountValue = 0;
            } else {
                $discountValue = $discount->discount;
            }
            $produkDiscount = SalesOrderDetailModel::where('products_id', $item->products_id)->where('sales_orders_id', $id)->first();
            $produkDiscount->discount = $discountValue;
            $dataHarga = ProductModel::select('harga_jual_nonretail')->where('id', $item->products_id)->first();
            $diskon =   $produkDiscount->discount / 100;
            $hargaDiskon = $dataHarga->harga_jual_nonretail * $diskon;
            $hargaAfterDiskon = $dataHarga->harga_jual_nonretail -  $hargaDiskon;
            $total = $total + ($hargaAfterDiskon * $produkDiscount->qty);
            $produkDiscount->save();
        }

        $ppn = 0.11 * $total;
        $model->ppn = $ppn;
        $model->total = $total;
        $model->total_after_ppn = $total + $ppn;
        // dd($arrayDiscount);
        // }
        $model->customers_id = $request->get('customer_id');
        $model->remark = $request->get('remark');
        $model->payment_method = $request->get('payment_method');
        if ($request->get('payment_method') == 3) {
            $model->top = $request->get('top');
            $dt = new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
            $model->duedate = $dt;
        } else {
            $model->top = NULL;
            $model->duedate = NULL;
        }
        $model->save();
        if ($model->save()) {
            return redirect('/recent_sales_order')->with('info', 'Edit sales orders ' . $model->order_number . ' success');
        }
    }


    // editProduuctP() :TAMPILAN EDIT PRODUCT
    public function editProduct($id)
    {
        $title = 'Edit Data Product in Sales Order :';
        $value = SalesOrderModel::find($id);
        $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')
            ->select('products.*', 'stocks.warehouses_id')
            ->where('stocks.warehouses_id', Auth::user()->warehouse_id)
            ->get();
        $customer = CustomerModel::where('status', 1)->latest()->get();
        return view('recent_sales_order.edit_product', compact('title', 'value', 'customer', 'product'));
    }

    // updateProduct()
    public function updateProduct(Request $request, $id)
    {
        // dd($request->all());
        $model = SalesOrderModel::find($id);
        // dd($model);
        $total = 0;

        foreach ($request->editProduct as $key => $value) {
            $sod = SalesOrderDetailModel::where('products_id', $value['products_id'])->where('sales_orders_id', $id)->first();
            $dataHarga = ProductModel::select('harga_jual_nonretail')->where('id', $value['products_id'])->first();
            $temp_product = $sod->products_id;
            $temp_discount = $sod->discount;
            $temp_qty = $sod->qty;
            $sod->products_id = $value['products_id'];
            $sod->qty = $value['qty'];
            $sod->discount = $value['discount'];
            $sod->save();
            $check_duplicate = SalesOrderDetailModel::where('sales_orders_id', $sod->sales_orders_id)
                ->where('products_id', $sod->products_id)
                ->count();
            if ($check_duplicate > 1) {
                $sod->products_id = $temp_product;
                $sod->discount = $temp_discount;
                $sod->qty = $temp_qty;
                $sod->save();
            } else {
                $sod->products_id = $value['products_id'];
                $sod->qty = $value['qty'];
                $sod->discount = $value['discount'];
                $diskon =   $sod->discount / 100;
                $hargaDiskon = $dataHarga->harga_jual_nonretail * $diskon;
                $hargaAfterDiskon = $dataHarga->harga_jual_nonretail -  $hargaDiskon;
                $total = $total + ($hargaAfterDiskon * $sod->qty);
                $cekSod = $sod->save();
            }
        }
        if ($cekSod) {
            $ppn = 0.11 * $total;
            $model->ppn = $ppn;
            $model->total = $total;
            $model->total_after_ppn = $total + $ppn;
            $model->save();
            return redirect('/recent_sales_order')->with('success', 'Update product in sales orders ' . $model->order_number . 'success');
        } else {
            return redirect('/recent_sales_order')->with('error', 'Update product in sales orders ' . $model->order_number . 'fail');
        }
    }
    // deleteProduct() : HAPUS DATA PRODUCT PADA SO DAN UPDATE JUMLAH HARGA SERTA TOTAL
    public function deleteProduct($id_so, $id_sod)
    {
        $cekDetail = SalesOrderDetailModel::where('sales_orders_id', $id_so)->count();

        if ($cekDetail > 1) {
            // dd($model);
            $cekProduct = SalesOrderDetailModel::find($id_sod);
            $hapus =  $cekProduct->delete();
            $total = 0;
            if ($hapus) {
                $productDetail = SalesOrderDetailModel::where('sales_orders_id', $id_so)->get();
                // dd($productDetail);

                foreach ($productDetail as $produk) {
                    $harga = ProductModel::where('id', $produk->products_id)->first();
                    $diskon =  $produk['discount'] / 100;
                    $hargaDiskon = $harga->harga_jual_nonretail * $diskon;
                    $hargaAfterDiskon = $harga->harga_jual_nonretail -  $hargaDiskon;
                    $total = $total + ($hargaAfterDiskon * $produk->qty);
                    // dd($produk);
                }
            }
            $model = SalesOrderModel::find($id_so);
            $ppn = 0.11 * $total;
            $model->ppn = $ppn;
            $model->total = $total;
            $model->total_after_ppn = $total + $ppn;
            $model->save();
            return redirect('/recent_sales_order')->with('error', 'Delete product in sales orders success');
        } else {
            return redirect('/recent_sales_order')->with('error', 'Delete product in sales orders fail because the product in the sales order cannot be empty');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelSalesOrder = SalesOrderModel::where('id', $id)->first();
        $modelSalesOrder->salesOrderDetailsBy()->delete();
        $modelSalesOrder->delete();
        return redirect('/recent_sales_order')->with('success', 'Delete Data Sales Order Success');
    }
    public function soNeedApproval()
    {
        $title = 'Sales Order Need Approval By Admin';

        $dataInvoice = SalesOrderModel::where('isapprove', 0)->where('isverified', 1)->latest('created_at')->get();

        return view('need_approval.index', compact('title', 'dataInvoice'));
    }
    public function verificate($id)
    {
        $selected_so = SalesOrderModel::where('id', $id)->firstOrFail();
        $getCredential = CustomerModel::select('isOverDue', 'isOverPlafoned')->where('id', $selected_so->customers_id)->firstOrFail();
        $selected_so->isverified = 1;
        if ($getCredential->isOverDue != 1 && $getCredential->isOverPlafoned != 1) {
            $selected_so->isapprove = 1;
            $so_number = $selected_so->order_number;
            $so_number = str_replace('SOPP', 'IVPP', $so_number);
            $selected_so->order_number = $so_number;
        } else {
            $message = 'Sales Order indicated overdue or overceiling. Please check immediately!';
            event(new SOMessage('From:' . Auth::user()->name, $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->role_id = 1;
            $notif->save();
        }
        $selected_so->save();

        return redirect('/sales_orders')->with('Success', "Sales Order Verification Success");
    }

    // getInvoiceData() : Tampilkan data invoice dengan yajra
    public function getInvoiceData(Request $request)
    {
        // get kode area

        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            $invoice = SalesOrderModel::with('customerBy')
                ->with('createdSalesOrder')
                ->where('isapprove', 1)
                ->where('isverified', 1)
                ->where('order_number', 'like', "%$kode_area->area_code%")
                ->latest()
                ->get();

            return datatables()->of($invoice)
                ->editColumn('payment_method', function ($data) {
                    if ($data->payment_method == 1) {
                        return 'COD';
                    } elseif ($data->payment_method == 1) {
                        return 'CBD';
                    } else {
                        return 'Credit';
                    }
                })

                ->addColumn('customerBy', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->customerBy->name_cust;
                })
                ->addColumn('createdSalesOrder', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->createdSalesOrder->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', 'invoice._option')
                ->rawColumns(['action'], ['customerBy'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All data invoice in profecta perdana : " . Auth::user()->warehouseBy->warehouses,
            // 'order_number' =>
        ];

        return view('invoice.index', $data);
    }
}
