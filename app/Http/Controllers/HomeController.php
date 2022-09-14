<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderModel;
use App\Models\SalesOrderModel;
use App\Models\SuppliersModel;
use App\Models\User;
use Cron\MonthField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    //broadcast message with api whatsapp


    public function index()
    {
        $title = 'Dashboard';

        // SALES
        $so_day = SalesOrderModel::where('order_number', 'like', '%IVPP%')->whereDay('order_date', date('d'))->where('created_by', Auth::user()->id)->sum('total_after_ppn');
        $so_total = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('created_by', Auth::user()->id)->sum('total_after_ppn');
        $so_by = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('created_by', Auth::user()->id)->count();

        // VERIFICATOR
        $so_verify = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('isverified', 1)->whereDay('order_date', date('d'))->count();
        $so_no_verif = SalesOrderModel::where('order_number', 'like', '%SOPP%')->where('isverified', 0)->whereDay('order_date', date('d'))->count();
        $so_today = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('isapprove', 'approve')->where('isverified', 1)->whereDay('order_date', date('d'))->sum('total_after_ppn');
        // dd($so_today);

        // finance
        $approve_today = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('isapprove', 'approve')->where('isverified', 1)->whereDay('order_date', date('d'))->count();
        $over_due = CustomerModel::where('isOverDue', 1)->count();

        // superadmin
        $supplier = SuppliersModel::count();
        $customer = CustomerModel::count();
        $user = User::count();
        $produk = ProductModel::count();
        $month = SalesOrderModel::where('order_number', 'like', '%IVPP%')
            ->where('isapprove', 'approve')->where('isverified', 1)
            ->whereMonth('order_date', date('m'))
            ->sum('total_after_ppn');
        $year = SalesOrderModel::where('order_number', 'like', '%IVPP%')
            ->where('isapprove', 'approve')->where('isverified', 1)
            ->whereYear('order_date', date('Y'))
            ->sum('total_after_ppn');

        // gudang
        $po_val = PurchaseOrderModel::where('isvalidated', 0)->count();
        $po = PurchaseOrderModel::where('isvalidated', 1)->count();

        // chart
        $record = SalesOrderModel::select(DB::raw('SUM(total_after_ppn) as total'), DB::raw('DAYNAME(order_date) as day_name'), DB::raw("DAY(order_date) as day"))
            ->where('order_number', 'like', '%IVPP%')
            ->where('isapprove', 'approve')->where('isverified', 1)
            ->groupBy('day', 'order_date')
            ->limit(7)
            ->orderBy('order_date', 'ASC')
            ->get();
        $record = SalesOrderModel::select(DB::raw('SUM(total_after_ppn) as total'), DB::raw('DAYNAME(order_date) as day_name'), DB::raw("DAY(order_date) as day"))
            ->where('order_number', 'like', '%IVPP%')
            ->where('isapprove', 'approve')->where('isverified', 1)
            ->groupBy('day', 'order_date')
            ->get();

        //Purchase Order Record
        $po_record = PurchaseOrderModel::select(DB::raw('SUM(total) as total'), DB::raw('MONTHNAME(order_date) as month_name'), DB::raw("MONTH(order_date) as month"))
            ->where('order_number', 'like', '%POPP%')
            ->where('isapprove', 1)
            ->where('isvalidated', 1)
            ->where(DB::raw('YEAR(order_date)'), date('Y'))
            ->groupBy('month', 'order_date')
            ->get();
        $data = [];
        $data_po = [];

        $total_income = 0;
        foreach ($record as $row) {
            $data['label'][] = $row->day_name;
            $data['data'][] = $row->total;
            $total_income += $row->total;
        }
        $data['chart_data'] = json_encode($data);

        foreach ($po_record as $row) {
            $data_po['label_po'][] = $row->month_name;
            $data_po['data_po'][] = $row->total;
        }
        $data_po['chart_po'] = json_encode($data_po);

        return view('home', compact('total_income', 'data', 'data_po', 'po_val', 'po', 'so_day', 'supplier', 'produk', 'customer', 'year', 'user', 'month', 'title', 'so_total', 'so_by', 'so_verify', 'so_today', 'approve_today', 'so_no_verif', 'over_due'));
    }
}
