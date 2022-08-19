<?php

namespace App\Http\Controllers;

use App\Events\SOMessage;
use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Models\ProductModel;
use App\Models\SalesOrderDetailModel;
use App\Models\SalesOrderModel;
use Carbon\Carbon;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        event(new SOMessage('From:' . Auth::user()->name, 'Halloooo'));
        return view('sales_orders.index', compact('title', 'product', 'customer'));
    }
    public function getRecentData()
    {
        $title = 'Recent Sales Order';
        $product = ProductModel::latest()->get();
        $customer = CustomerModel::where('status', 1)->latest()->get();
        $dataSalesOrder = SalesOrderDetailModel::select('sales_orders.*', 'sales_order_details.*')
            ->leftJoin('sales_orders', 'sales_orders.id', '=', 'sales_order_details.sales_orders_id')
            ->where('payment_method', 1)
            ->groupBy('sales_order_details.sales_orders_id')
            ->get();
        $dataSalesOrderDebt = SalesOrderDetailModel::select('sales_orders.*', 'sales_order_details.*')
            ->leftJoin('sales_orders', 'sales_orders.id', '=', 'sales_order_details.sales_orders_id')
            ->where('payment_method', 2)
            ->groupBy('sales_order_details.sales_orders_id')
            ->get();
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
        // dd($request->all());


        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",
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
        $model->isapprove = 0;
        $model->isverified = 0;

        if ($model->top != NULL) {
            $dt = new DateTimeImmutable(Carbon::now()->format('Y-m-d'), new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
        } else {
            $dt = NULL;
        }
        $model->isoverdue = $dt;
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
                    $message_duplicate = "You enter duplication of products. Please recheck the discount you set.";
                    continue;
                } else {
                    $harga = ProductModel::find($data->products_id);
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
            return redirect('/sales_order')->with('success', 'Add Discount Success');
        } else {
            return redirect('/sales_order')->with('error', 'Add Discount Fail! Please make sure you have filled all the input');
        }
    }
    public function updateSo(Request $request, $id)
    {
        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",

        ]);

        $model = SalesOrderDetailModel::find($id);
        $model->customer_id = $request->get('customer_id');

        $dataProduk =  DiscountModel::where('customer_id', $request->get('customer_id'))
            ->where('product_id', $model->products_id)
            ->get();
        dd($dataProduk);
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

    public function verificate(SalesOrderModel $salesorder)
    {
        $selected_so = SalesOrderModel::where('order_number', $salesorder->order_number)->firstOrFail();
        $selected_so->isverified = 1;
        $selected_so->save();

        return redirect('/sales_orders')->with('Success', "Sales Order Verifate Success");
    }
}
