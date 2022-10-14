<?php

namespace App\Http\Controllers;

use App\Events\ApprovalMessage;
use DateTimeZone;
use Carbon\Carbon;
use DateTimeImmutable;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Events\SOMessage;
use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Models\NotificationsModel;
use App\Models\ReturnDetailModel;
use App\Models\ReturnModel;
use App\Models\SalesOrderCreditModel;
use App\Models\SalesOrderModel;
use Illuminate\Support\Facades\Auth;
use App\Models\SalesOrderDetailModel;
use App\Models\StockModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Dompdf\Options;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Gate;
// use Barryvdh\DomPDF\PDF;
use PDF;
use Illuminate\Support\Facades\Redirect;

use function App\Helpers\checkOverDue;
use function App\Helpers\checkOverDueByCustomer;
use function App\Helpers\checkOverPlafone;

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
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isSales') && !Gate::allows('isVerificator')
            && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $title = 'Create Sales Order';
        $product = ProductModel::latest()->get();
        $customer = CustomerModel::where('status', 1)->latest()->get();

        return view('sales_orders.index', compact('title', 'product', 'customer'));
    }
    public function editSuperadmin(Request $request, $id)
    {
        if (
            !Gate::allows('isSuperAdmin')
        ) {
            abort(403);
        }
        // Validate Input
        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",
            "editProduct.*.products_id" => "required|numeric",
            "editProduct.*.qty" => "required|numeric",
            "editProduct.*.discount" => "required|numeric",
            "remark" => "required"
        ]);

        //Assign Object Model and Save SO Input
        $model = SalesOrderModel::where('id', $id)->firstOrFail();
        $customer_id = $request->get('customer_id');
        $model->customers_id = $customer_id;
        $model->payment_method = $request->get('payment_method');
        $model->remark = $request->get('remark');
        if ($request->get('payment_method') == 3) {
            $top = CustomerModel::where('id', $model->customers_id)->first();
            $model->top = $top->due_date;
            $dt = new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
            $model->duedate = $dt;
        } else {
            $model->top = NULL;
            $model->duedate = NULL;
        }
        $saved_temp = $model->save();

        //Check Duplicate
        $products_arr = [];
        foreach ($request->editProduct as $check) {
            array_push($products_arr, $check['products_id']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return redirect('/invoice')->with('error', "You enter duplicate products! Please check again!");
        }

        if ($model->isapprove == 'approve') {
            //Restore data to before changed
            $po_restore = SalesOrderDetailModel::where('sales_orders_id', $id)->get();
            foreach ($po_restore as $restore) {
                $stock = StockModel::where('warehouses_id', $model->customerBy->warehouseBy->id)
                    ->where('products_id', $restore->products_id)->first();
                $stock->stock = $stock->stock + $restore->qty;
                $stock->save();
            }
        }
        //Save SOD Input and Count total
        $total = 0;

        foreach ($request->editProduct as $product) {
            $product_exist = SalesOrderDetailModel::where('sales_orders_id', $id)
                ->where('products_id', $product['products_id'])->first();
            if ($product_exist != null) {
                $product_exist->qty = $product['qty'];
                $product_exist->discount = $product['discount'];
                $product_exist->save();
            } else {
                $new_product = new SalesOrderDetailModel();
                $new_product->sales_orders_id = $id;
                $new_product->products_id = $product['products_id'];
                $new_product->qty = $product['qty'];
                $new_product->discount = $product['discount'];
                $new_product->created_by = Auth::user()->id;
                $new_product->save();
            }
            $harga = ProductModel::where('id', $product['products_id'])->first();
            $diskon =  $product['discount'] / 100;
            $hargaDiskon = $harga->harga_jual_nonretail * $diskon;
            $hargaAfterDiskon = $harga->harga_jual_nonretail -  $hargaDiskon;
            $total = $total + ($hargaAfterDiskon * $product['qty']);

            $harga_awal = $harga->harga_beli * $product['qty'];
        }

        //Delete product that not in SOD Input
        $del = SalesOrderDetailModel::where('sales_orders_id', $id)
            ->whereNotIn('products_id', $products_arr)->delete();

        //Count PPN and Total
        $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $total;
        $model->ppn = $ppn;
        $model->total = $total;
        $model->total_after_ppn = $total + $ppn;
        $model->profit = $model->total_after_ppn - $harga_awal;

        //Verify


        //Potong Stock
        $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $id)->get();
        foreach ($selected_sod as $value) {
            if (Gate::allows('isSuperAdmin') || Gate::allows('isVerificator') || Gate::allows('isFinance')) {
                $getStock = StockModel::where('products_id', $value->products_id)
                    ->where('warehouses_id', $model->customerBy->warehouseBy->id)
                    ->first();
            } else {
                $getStock = StockModel::where('products_id', $value->products_id)
                    ->where('warehouses_id', Auth::user()->warehouse_id)
                    ->first();
            }
            $old_stock = $getStock->stock;
            $getStock->stock = $old_stock - $value->qty;
            if ($getStock->stock < 0) {
                return Redirect::back()->with('error', 'Verification Fail! Not enough stock. Please re-confirm to the customer.');
            } else {
                $getStock->save();
            }



            $checkoverplafone = checkOverPlafone($model->customers_id);
            $checkoverdue = checkOverDueByCustomer($model->customers_id);
        }

        $saved_model = $model->save();
        if ($saved_model == true) {
            $data = SalesOrderModel::where('order_number', $model->order_number)->first();
            $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
            if ($model->pdf_do != '') {
                $pdf = PDF::loadView('invoice.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_do);
            }
            if ($model->pdf_invoice != '') {
                $pdf = PDF::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_invoice);
            }
            return redirect('/invoice')->with('Info', "Invoice success update !");
        } else {
            return redirect('/invoice')->with('error', "Invoice update Fail! Please check again!");
        }
    }
    // print history payment
    public function printHistoryPayment($id)
    {
        $sales_order = SalesOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();

        $sales_order_credit = SalesOrderCreditModel::where('sales_order_id', $sales_order)->get();
        $pdf = PDF::loadView('invoice.print_history_payment', compact('warehouse', 'sales_order', 'sales_order_credit'))->setPaper('A5', 'landscape');
        return $pdf->stream('history_payment.pdf');
    }

    // print invoice dengan PPN
    public function printInoiceWithPpn($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isSales') && !Gate::allows('isVerificator')
            && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $data = SalesOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $data->pdf_invoice = $data->order_number . '.pdf';
        $data->save();

        $pdf = PDF::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->order_number . '.pdf');



        return $pdf->download($data->pdf_invoice);
    }
    //print delivery order
    public function deliveryOrder($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isSales') && !Gate::allows('isVerificator')
            && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $data = SalesOrderModel::find($id);
        $so_number = str_replace('IVPP', 'DOPP', $data->order_number);
        $data->pdf_do = $so_number . '.pdf';
        $data->save();
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = PDF::loadView('invoice.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $so_number . '.pdf');
        return $pdf->download($data->pdf_do);
    }

    // getRecentData() : READ DATA RECENT SALES ORDERS ADMIN & SALES ADMIN
    public function getRecentData()
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isVerificator')) {
            abort(403);
        }
        $title = 'Recent Sales Order';
        // get kode area
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', Auth::user()->warehouse_id)
            ->first();
        // get sales no debt
        $dataSalesOrder = SalesOrderModel::with([
            'customerBy',
            'salesOrderDetailsBy.productSales.sub_types',
            'salesOrderDetailsBy.productSales.sub_materials'
        ])
            ->whereIn('payment_method', [1, 2])
            ->where('isverified', 0)
            ->where('isapprove', 'progress')
            ->latest()
            ->get();

        // get sales with
        $dataSalesOrderDebt = SalesOrderModel::with([
            'customerBy',
            'salesOrderDetailsBy.productSales.sub_types',
            'salesOrderDetailsBy.productSales.sub_materials'
        ])
            ->where('payment_method', 3)
            ->where('isverified', 0)
            ->where('isapprove', 'progress')
            ->latest()
            ->get();

        $dataSalesOrderReject = SalesOrderModel::with([
            'customerBy',
            'salesOrderDetailsBy.productSales.sub_types',
            'salesOrderDetailsBy.productSales.sub_materials'
        ])
            ->where('isapprove', 'reject')
            ->latest()
            ->get();

        checkOverDue();
        $customer = CustomerModel::where('status', 1)->latest()->get();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        return view('recent_sales_order.index', compact('title', 'dataSalesOrder', 'ppn', 'dataSalesOrderDebt', 'customer', 'dataSalesOrderReject'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
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
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isSales') && !Gate::allows('isVerificator')
            && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        // validasi sebelum save
        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",
            "remark" => "required",
            "soFields.*.product_id" => "required|numeric",
            "soFields.*.qty" => "required|numeric"
        ]);

        //Check Stock
        $customer = CustomerModel::where('id', $request->get('customer_id'))->first();

        // dd($customer->name_cust);
        foreach ($request->soFields as $qty) {
            if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                $getStock = StockModel::where('products_id', $qty['product_id'])
                    ->where('warehouses_id', $customer->warehouseBy->id)
                    ->first();
            } else {
                $getStock = StockModel::where('products_id', $qty['product_id'])
                    ->where('warehouses_id', Auth::user()->warehouse_id)
                    ->first();
            }

            if ($qty['qty'] > $getStock->stock) {
                return redirect('/sales_order')->with('error', 'Add Sales Order Fail! The number of items exceeds the stock');
            }
        }

        // query cek kode warehouse/area sales orders
        if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $customer->warehouseBy->id)
                ->first();
        } else {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
        }

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

        $model->customers_id = $customer->id;
        $model->remark = $request->get('remark');
        $model->created_by = Auth::user()->id;
        $model->payment_method = $request->get('payment_method');

        // metode bayar
        if ($model->payment_method == 3) {
            $top = CustomerModel::where('id', $model->customers_id)->first();
            $model->top = $top->due_date;
            $dt = new DateTimeImmutable(Carbon::now()->format('Y-m-d'), new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
            $model->duedate = $dt;
        } else {
            $model->top = NULL;
            $model->duedate = NULL;
        }
        $model->isapprove = 'progress';
        $model->isverified = 0;

        $saved = $model->save();

        // save sales order details
        $total = 0;
        $message_duplicate = '';
        if ($saved) {
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
        $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $total;
        $model->ppn = $ppn;
        $model->total = $total;
        $model->total_after_ppn = $total + $ppn;
        $saved = $model->save();

        if (empty($message_duplicate) && $saved) {
            $message = $model->order_number . ' Sales Order has been created! Please check';
            event(new SOMessage('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->job_id = 1;
            $notif->save();
            return redirect('/sales_order')->with('success', 'Create sales orders ' . $model->order_number . ' success');
        } elseif (!empty($message_duplicate) && $saved) {
            $message = $model->order_number . ' Sales Order has been created! Please check';
            event(new SOMessage('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->job_id = 1;
            $notif->save();
            return redirect('/sales_order')->with('info', 'Some of SO add maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/sales_order')->with('error', 'Add Sales Order Fail! Please make sure you have filled all the input');
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
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isFinance') || !Gate::allows('level1')) {
            abort(403);
        }
        $modelSalesOrder = SalesOrderModel::where('id', $id)->first();
        $modelSalesOrder->salesOrderDetailsBy()->delete();
        $modelSalesOrder->delete();
        return redirect('/recent_sales_order')->with('error', 'Delete Data Sales Order Success');
    }
    public function soNeedApproval()
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $title = 'Sales Order Need Approval By Finance';

        $dataInvoice = SalesOrderModel::where('isapprove', 'progress')->where('isverified', 1)->latest('created_at')->get();

        return view('need_approval.index', compact('title', 'dataInvoice'));
    }
    public function verify(Request $request, $id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isVerificator')
        ) {
            abort(403);
        }
        // Validate Input
        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",
            "editProduct.*.products_id" => "required|numeric",
            "editProduct.*.qty" => "required|numeric",
            "editProduct.*.discount" => "required|numeric",
            "remark" => "required"
        ]);

        //Assign Object Model and Save SO Input
        $model = SalesOrderModel::where('id', $id)->firstOrFail();
        $customer_id = $request->get('customer_id');
        $model->customers_id = $customer_id;
        $model->payment_method = $request->get('payment_method');
        $model->remark = $request->get('remark');
        if ($request->get('payment_method') == 3) {
            $top = CustomerModel::where('id', $model->customers_id)->first();
            $model->top = $top->due_date;
            $dt = new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
            $model->duedate = $dt;
        } else {
            $model->top = NULL;
            $model->duedate = NULL;
        }
        $saved_temp = $model->save();

        //Check Duplicate
        $products_arr = [];
        foreach ($request->editProduct as $check) {
            array_push($products_arr, $check['products_id']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return redirect('/recent_sales_order')->with('error', "You enter duplicate products! Please check again!");
        }

        //Save SOD Input and Count total
        $total = 0;

        foreach ($request->editProduct as $product) {
            $product_exist = SalesOrderDetailModel::where('sales_orders_id', $id)
                ->where('products_id', $product['products_id'])->first();
            if ($product_exist != null) {
                $product_exist->qty = $product['qty'];
                $product_exist->discount = $product['discount'];
                $product_exist->discount_rp = $product['discount_rp'];
                $product_exist->save();
            } else {
                $new_product = new SalesOrderDetailModel();
                $new_product->sales_orders_id = $id;
                $new_product->products_id = $product['products_id'];
                $new_product->qty = $product['qty'];
                $new_product->discount = $product['discount'];
                $new_product->discount_rp = $product['discount_rp'];
                $new_product->created_by = Auth::user()->id;
                $new_product->save();
            }
            $harga = ProductModel::where('id', $product['products_id'])->first();
            $diskon =  $product['discount'] / 100;
            $hargaDiskon = $harga->harga_jual_nonretail * $diskon;
            $hargaAfterDiskon = ($harga->harga_jual_nonretail -  $hargaDiskon) - $product['discount_rp'];
            $total = $total + ($hargaAfterDiskon * $product['qty']);

            $harga_awal = $harga->harga_beli * $product['qty'];
        }

        //Delete product that not in SOD Input
        $del = SalesOrderDetailModel::where('sales_orders_id', $id)
            ->whereNotIn('products_id', $products_arr)->delete();

        //Count PPN and Total
        $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $total;
        $model->ppn = $ppn;
        $model->total = $total;
        $model->total_after_ppn = $total + $ppn;
        $model->profit = $model->total_after_ppn - $harga_awal;

        //Verify
        $getCredential = CustomerModel::where('id', $model->customers_id)->firstOrFail();
        $model->isverified = 1;
        $model->verifiedBy = Auth::user()->id;
        if ($model->isapprove == 'reject') {
            $old_revision = $model->revision;
            $model->revision = $old_revision + 1;
            $model->isapprove = 'progress';
            $message = 'Sales Order ' . $model->order_number . ' has revised. Please check immediately!';
            event(new ApprovalMessage('From:' . Auth::user()->name, $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->job_id = 2;
            $notif->save();
        } else {
            if ($model->payment_method != 3) {
                $model->isapprove = 'approve';
                $model->isPaid = 1;
                $model->paid_date = $model->order_date;
                $so_number = $model->order_number;
                $iv_number = str_replace('SOPP', 'IVPP', $so_number);
                $do = str_replace('SOPP', 'DOPP', $so_number);
                // dd($do);
                $model->pdf_do = $do . '.pdf';
                $model->pdf_invoice = $iv_number . '.pdf';
                $model->order_number = $iv_number;
                // profit

                //Potong Stock
                $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $id)->get();
                foreach ($selected_sod as $value) {
                    if (Gate::allows('isSuperAdmin') || Gate::allows('isVerificator') || Gate::allows('isFinance')) {
                        $getStock = StockModel::where('products_id', $value->products_id)
                            ->where('warehouses_id', $model->customerBy->warehouseBy->id)
                            ->first();
                    } else {
                        $getStock = StockModel::where('products_id', $value->products_id)
                            ->where('warehouses_id', Auth::user()->warehouse_id)
                            ->first();
                    }
                    $old_stock = $getStock->stock;
                    $getStock->stock = $old_stock - $value->qty;
                    if ($getStock->stock < 0) {
                        return Redirect::back()->with('error', 'Verification Fail! Not enough stock. Please re-confirm to the customer.');
                    } else {
                        $getStock->save();
                    }
                }
                //Update Last Transaction Customer
                $selected_customer = CustomerModel::where('id', $model->customers_id)->first();
                $selected_customer->last_transaction = $model->order_date;
                $selected_customer->save();
            } else {
                $checkoverplafone = checkOverPlafone($model->customers_id);
                $checkoverdue = checkOverDueByCustomer($model->customers_id);
                // dd("Overdue: " . $checkoverdue . ", " . "Overplafone: " . $checkoverplafone);
                if ($checkoverdue == false & $checkoverplafone == false & $getCredential->label != 'Bad Customer') {
                    $model->isapprove = 'approve';
                    $so_number = $model->order_number;
                    $so_number = str_replace('SOPP', 'IVPP', $so_number);
                    $model->order_number = $so_number;
                    //Potong Stock
                    $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $id)->get();
                    foreach ($selected_sod as $value) {
                        if (Gate::allows('isSuperAdmin') || Gate::allows('isVerificator') || Gate::allows('isFinance')) {
                            $getStock = StockModel::where('products_id', $value->products_id)
                                ->where('warehouses_id', $model->customerBy->warehouseBy->id)
                                ->first();
                        } else {
                            $getStock = StockModel::where('products_id', $value->products_id)
                                ->where('warehouses_id', Auth::user()->warehouse_id)
                                ->first();
                        }

                        $old_stock = $getStock->stock;
                        $getStock->stock = $old_stock - $value->qty;
                        if ($getStock->stock < 0) {
                            return Redirect::back()->with('error', 'Verification Fail! Not enough stock. Please re-confirm to the customer.');
                        } else {
                            $getStock->save();
                        }
                    }

                    //Update Last Transaction Customer
                    $selected_customer = CustomerModel::where('id', $model->customers_id)->first();
                    $selected_customer->last_transaction = $model->order_date;
                    $selected_customer->save();
                } else {
                    $message = 'Sales Order indicated overdue or overceiling. Please check immediately!';
                    event(new ApprovalMessage('From:' . Auth::user()->name, $message));
                    $notif = new NotificationsModel();
                    $notif->message = $message;
                    $notif->status = 0;
                    $notif->job_id = 2;
                    $notif->save();
                }
            }
        }

        $saved_model = $model->save();
        if ($saved_model == true) {
            $data = SalesOrderModel::where('order_number', $model->order_number)->first();
            $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
            if ($model->pdf_do != '') {
                $pdf = PDF::loadView('invoice.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_do);
            }
            if ($model->pdf_invoice != '') {
                $pdf = PDF::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_invoice);
            }
            return redirect('/recent_sales_order')->with('success', "Sales Order Verification Success");
        } else {
            return redirect('/recent_sales_order')->with('error', "Sales Order Verification Fail! Please check again!");
        }
    }

    // getInvoiceData() : Tampilkan data invoice dengan yajra
    public function getInvoiceData(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isSales') && !Gate::allows('isVerificator')
            && !Gate::allows('isFinance')
        ) {
            abort(403);
        }

        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            if (!empty($request->from_date)) {
                if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                    $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                        ->where('isapprove', 'approve')
                        ->where('isverified', 1)
                        ->whereBetween('order_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                } else {
                    $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                        ->where('isapprove', 'approve')
                        ->where('isverified', 1)
                        ->where('order_number', 'like', "%$kode_area->area_code%")
                        ->whereBetween('order_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                }
            } else {
                if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                    $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                        ->where('isapprove', 'approve')
                        ->where('isverified', 1)
                        ->latest()
                        ->get();
                } else {
                    $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                        ->where('isapprove', 'approve')
                        ->where('isverified', 1)
                        ->where('order_number', 'like', "%$kode_area->area_code%")
                        ->latest()
                        ->get();
                }
            }
            return datatables()->of($invoice)
                ->editColumn('payment_method', function ($data) {
                    if ($data->payment_method == 1) {
                        return 'COD';
                    } elseif ($data->payment_method == 2) {
                        return 'CBD';
                    } else {
                        return 'Credit';
                    }
                })
                ->editColumn('ppn', function ($data) {
                    return number_format($data->ppn, 0, ',', '.');
                })
                ->editColumn('total_after_ppn', function ($data) {
                    return number_format($data->total_after_ppn, 0, ',', '.');
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, ',', '.');
                })
                ->editColumn('isPaid', function ($data) {
                    if ($data->isPaid == 0) {
                        return 'Unpaid';
                    } else {
                        return 'Paid';
                    }
                })
                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data->order_date));
                })
                ->editColumn('duedate', function ($data) {
                    if ($data->duedate != null) {
                        return date('d-M-Y', strtotime($data->duedate));
                    } else {
                        return "-";
                    }
                })
                ->editColumn('customers_id', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->customerBy->name_cust;
                })
                ->editColumn('created_by', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->createdSalesOrder->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) {
                    $customer = CustomerModel::latest()->get();
                    $warehouses = WarehouseModel::latest()->get();
                    return view('invoice._option', compact('invoice', 'customer', 'warehouses'))->render();
                })
                ->rawColumns(['action'], ['customerBy'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = [
            'title' => "All data invoice in profecta perdana : " . Auth::user()->warehouseBy->warehouses,
            'ppn' => $ppn
        ];

        return view('invoice.index', $data);
    }
    public function reject($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $selected_so = SalesOrderModel::where('id', $id)->firstOrFail();
        $selected_so->isapprove = 'reject';
        $selected_so->isverified = 0;
        $selected_so->isPaid = 0;
        $selected_so->save();
        return redirect('/need_approval')->with('info', "Sales Order " . $selected_so->order_number . " Reject ");
    }
    public function approve($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $selected_so = SalesOrderModel::where('id', $id)->firstOrFail();
        //Potong Stock
        $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $selected_so->id)->get();
        foreach ($selected_sod as $value) {
            if (Gate::allows('isSuperAdmin') || Gate::allows('isVerificator') || Gate::allows('isFinance')) {
                $getStock = StockModel::where('products_id', $value->products_id)
                    ->where('warehouses_id', $selected_so->customerBy->warehouseBy->id)
                    ->first();
            } else {
                $getStock = StockModel::where('products_id', $value->products_id)
                    ->where('warehouses_id', Auth::user()->warehouse_id)
                    ->first();
            }
            $old_stock = $getStock->stock;
            $getStock->stock = $old_stock - $value->qty;
            if ($getStock->stock < 0) {
                return Redirect::back()->with('error', 'Approval Fail! Not enough stock. Please re-confirm to the customer.');
            } else {
                $getStock->save();
            }
        }

        //Update Last Transaction Customer
        $selected_customer = CustomerModel::where('id', $selected_so->customers_id)->first();
        $selected_customer->last_transaction = $selected_so->order_date;
        $selected_customer->save();

        $so_number = $selected_so->order_number;
        $so_number = str_replace('SOPP', 'IVPP', $so_number);
        $do = str_replace('SOPP', 'DOPP', $selected_so->order_number);
        $selected_so->pdf_invoice = $so_number . '.pdf';
        $selected_so->pdf_do = $do . '.pdf';
        $selected_so->order_number = $so_number;
        $selected_so->isapprove = 'approve';
        $selected_so->approvedBy = Auth::user()->id;
        $selected_so->isPaid = 0;
        $selected_so->save();
        $data = SalesOrderModel::where('order_number', $selected_so->order_number)->first();
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        if ($selected_so->pdf_do != '') {
            $pdf = PDF::loadView('invoice.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $selected_so->pdf_do);
        }
        if ($selected_so->pdf_invoice != '') {
            $pdf = PDF::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $selected_so->pdf_invoice);
        }
        return redirect('/invoice')->with('success', "Sales Order Approval Success");
    }

    public function updatePaid(Request $request, $id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }

        $request->validate([
            "amount" => "required|numeric",
        ]);

        $selected_so = SalesOrderModel::where('id', $id)->first();

        //Save Sales Order Credit
        $soc = new SalesOrderCreditModel();
        $soc->sales_order_id = $selected_so->id;
        $soc->payment_date = Carbon::now()->format('Y-m-d H:i:s');
        $soc->amount = $request->get('amount');
        $soc->updated_by = Auth::user()->id;
        $soc->save();

        //Count total amount instalment
        $all_soc = SalesOrderCreditModel::where('sales_order_id', $id)->get();
        $total_amount = 0;
        $total_return = 0;
        $total_return = ReturnModel::where('sales_order_id', $id)->sum('total');
        foreach ($all_soc as $value) {
            $total_amount = $total_amount + $value->amount;
        }
        if ($total_amount >= ($selected_so->total_after_ppn - $total_return)) {
            $selected_so->isPaid = 1;
            $selected_so->paid_date = Carbon::now()->format('Y-m-d H:i:s');
            $selected_so->save();

            //update overplafone and overdue
            $checkoverplafone = checkOverPlafone($selected_so->customers_id);
            $checkoverdue = checkOverDueByCustomer($selected_so->customers_id);
            return redirect('/invoice')->with('success', "Order number " . $selected_so->order_number . " already paid!");
        } else {
            return redirect('/invoice/manage_payment')->with('success', "Update Payment of Order number " . $selected_so->order_number . " Success!");
        }
    }

    public function paidManagement(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                    ->where('isapprove', 'approve')
                    ->where('isverified', 1)
                    ->where('isPaid', 0)
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                    ->where('isapprove', 'approve')
                    ->where('isverified', 1)
                    ->where('isPaid', 0)
                    ->latest()
                    ->get();
            }
            return datatables()->of($invoice)
                ->editColumn('payment_method', function ($data) {
                    if ($data->payment_method == 1) {
                        return 'COD';
                    } elseif ($data->payment_method == 2) {
                        return 'CBD';
                    } else {
                        return 'Credit';
                    }
                })
                ->editColumn('isPaid', function ($data) {
                    if ($data->isPaid == 0) {
                        return 'Unpaid';
                    } else {
                        return 'Paid';
                    }
                })
                ->editColumn('total_after_ppn', function ($data) {
                    return number_format($data->total_after_ppn, 0, ',', '.');
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, ',', '.');
                })
                ->editColumn('ppn', function ($data) {
                    return number_format($data->ppn, 0, ',', '.');
                })
                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data->order_date));
                })
                ->editColumn('duedate', function ($data) {
                    return date('d-M-Y', strtotime($data->duedate));
                })
                ->editColumn('customers_id', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->customerBy->name_cust;
                })
                ->editColumn('created_by', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->createdSalesOrder->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) {
                    $total_return = ReturnModel::where('sales_order_id', $invoice->id)->sum('total');
                    return view('invoice._option_paid_management', compact('invoice', 'total_return'))->render();
                })
                ->rawColumns(['action'], ['customerBy'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All data unpaid invoice in Profecta Perdana : " . Auth::user()->warehouseBy->warehouses,
            // 'order_number' =>
        ];
        return view('invoice.paid_management', $data);
    }

    public function getTotalInstalment($id)
    {
        $soc = SalesOrderCreditModel::where('sales_order_id', $id)->get();

        $total_amount = 0;
        foreach ($soc as $value) {
            $total_amount = $total_amount + $value->amount;
        }
        return response()->json($total_amount);
    }

    public function getQtyDetail()
    {
        $so_id = request()->s;
        $product_id = request()->p;

        $getqty = SalesOrderDetailModel::where('sales_orders_id', $so_id)->where('products_id', $product_id)->first();
        $_qty = $getqty->qty;
        $selected_return = ReturnModel::with('returnDetailsBy')->where('sales_order_id', $so_id)->get();

        $return = 0;
        if ($selected_return != null) {
            foreach ($selected_return as $value) {
                $selected_detail = ReturnDetailModel::where('return_id', $value->id)->where('product_id', $product_id)->first();
                $return = $return + $selected_detail->qty;
            }
        }
        $data = [
            'qty' => $_qty,
            'return' => $return
        ];
        return response()->json($data);
    }

    public function getAllDetail()
    {
        $so_id = request()->s;

        $getqty = SalesOrderDetailModel::where('sales_orders_id', $so_id)->get();
        return response()->json($getqty);
    }

    public function selectReturn()
    {
        try {
            $so_id = request()->s;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = SalesOrderDetailModel::join('products', 'products.id', '=', 'sales_order_details.products_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('products.nama_barang', 'LIKE', "%$search%")
                    ->where('sales_orders_id', $so_id)
                    ->orWhere('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->where('sales_orders_id', $so_id)
                    ->orWhere('product_sub_materials.nama_sub_material', 'LIKE', "%$search%")
                    ->where('sales_orders_id', $so_id)
                    ->get();
            } else {
                $product = SalesOrderDetailModel::join('products', 'products.id', '=', 'sales_order_details.products_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('sales_orders_id', $so_id)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
