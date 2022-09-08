<?php

namespace App\Http\Controllers;

use App\Models\SalesOrderModel;
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
        $so_total = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('created_by', Auth::user()->id)->sum('total_after_ppn');
        $so_by = SalesOrderModel::where('order_number', 'like', '%IVPP%')->where('created_by', Auth::user()->id)->count();

        return view('home', compact('title', 'so_total', 'so_by'));
    }
}
