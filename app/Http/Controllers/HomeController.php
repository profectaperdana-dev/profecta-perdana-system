<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\SalesOrderModel;
use App\Models\SuppliersModel;
use App\Models\User;
use Cron\MonthField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        $title = 'Dashboard';

        // SALES
        $so_total = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('created_by', Auth::user()->id)->sum('total_after_ppn');
        $so_by = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('created_by', Auth::user()->id)->count();

        // VERIFICATOR
        $so_verify = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('isverified', 1)->whereBetween('created_at', array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')))->count();
        $so_no_verif = SalesOrderModel::where('order_number', 'like', '%SOPP%')->where('isverified', 0)->whereBetween('created_at', array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')))->count();
        $so_today = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('isapprove', 'approve')->where('isverified', 1)->whereBetween('created_at', array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')))->sum('total_after_ppn');
        // dd($so_today);

        // finance
        $approve_today = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('isapprove', 'approve')->where('isverified', 1)->whereBetween('created_at', array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')))->count();
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

        return view('home', compact('supplier', 'produk', 'customer', 'year', 'user', 'month', 'title', 'so_total', 'so_by', 'so_verify', 'so_today', 'approve_today', 'so_no_verif', 'over_due'));
    }
}
