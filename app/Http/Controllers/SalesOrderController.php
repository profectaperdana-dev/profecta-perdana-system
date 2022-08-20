<?php

namespace App\Http\Controllers;

use DateTime;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SalesOrderDetailModel;

use function PHPUnit\Framework\isEmpty;
use function Symfony\Component\VarDumper\Dumper\esc;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Create Sales Order';
        $product = ProductModel::latest()->get();
        $customer = CustomerModel::where('status', 1)->latest()->get();

        return view('sales_orders.index', compact('title', 'product', 'customer'));
    }
    public function getRecentData()
    {
        $title = 'Recent Sales Order';
        $product = ProductModel::latest()->get();
        $customer = CustomerModel::where('status', 1)->latest()->get();
        $dataSalesOrder = SalesOrderModel::where('payment_method', 1)->latest('created_at')->get();
        $dataSalesOrderDebt = SalesOrderModel::where('payment_method', 2)->latest('created_at')->get();
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
    public function cekJam()
    {
        $dt = new DateTimeImmutable("2022-08-16 00:00:00", new DateTimeZone('Asia/Jakarta'));
        $dt = $dt->modify("+1 days");
        dd($dt);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",
            "remark" => "required",
            "soFields.*.product_id" => "required|numeric",
            "soFields.*.qty" => "required|numeric"
        ]);


        $length = 4;
        $id = intval(SalesOrderModel::max('id')) + 1;
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $order_number = 'SOPP/' . $year . '/' . $month . '/' . $cust_number_id . '';
        $model = new SalesOrderModel();
        $model->order_number = $order_number;
        $model->order_date = Carbon::now()->format('Y-m-d');
        $model->customers_id = $request->get('customer_id');
        $model->remark = $request->get('remark');
        $model->created_by = Auth::user()->id;
        $model->top = $request->get('top');
        $model->payment = $request->get('payment');
        $model->payment_method = $request->get('payment_method');
        $model->payment_type = $request->get('payment_type');
        $model->isapprove = 0;
        $model->isverified = 0;

        if ($model->top != NULL) {
            $dt = new DateTimeImmutable(Carbon::now()->format('Y-m-d'), new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
        } else {
            $dt = NULL;
        }
        $model->due_date = $dt;
        $model->save();

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
            return redirect('/sales_order')->with('success', 'Create SO  Success');
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


    public function editSo($id)
    {
        $title = 'Edit Data Sales Order';
        $value = SalesOrderModel::find($id);
        $customer = CustomerModel::where('status', 1)->latest()->get();
        return view('recent_sales_order.edit', compact('title', 'value', 'customer'));
    }
    public function updateSo(Request $request, $id)
    {
        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",

        ]);
        $model = SalesOrderModel::find($id);
        if ($request->get('customer_id') != $model->customers_id) {

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
        }

        $model->customers_id = $request->get('customer_id');
        $model->remark = $request->get('remark');
        $model->payment_method = $request->get('payment_method');
        if ($request->get('payment_method') == 1) {
            $model->top = '';
            $model->payment = $request->get('payment');
            $model->payment_type = $request->get('payment_type');
            $model->duedate = '';
        } else {
            $model->top = $request->get('top');
            $model->payment = '';
            $model->payment_type = '';
            $dt = new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
            $model->duedate = $dt;
        }
        $model->save();

        if ($model->save()) {

            return redirect('/recent_sales_order')->with('success', 'Add Discount Success');
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
        //
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
            event(new SOMessage('From:' . Auth::user()->name, 'Sales Order indicated overdue or overceiling. Please check immediately!'));
        }
        $selected_so->save();

        return redirect('/sales_orders')->with('Success', "Sales Order Verification Success");
    }
}
