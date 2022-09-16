<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderModel;
use App\Models\SalesOrderModel;
use App\Models\SuppliersModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalysisController extends Controller
{
    public function index()
    {
        $title = 'Analysis Report';
        // ALL SALES
        $record_sales = SalesOrderModel::Join('users', 'users.id', '=', 'sales_orders.created_by')
            ->select(
                DB::raw('SUM(sales_orders.total_after_ppn) as total'),
                DB::raw('DAYNAME(sales_orders.order_date) as day_name'),
                DB::raw("sales_orders.created_by as day"),
                DB::raw("users.name as name")
            )
            ->where('users.job_id', '=', 4)
            ->where('order_number', 'like', '%IVPP%')
            ->whereMonth('order_date', date('m'))
            ->where('isapprove', 'approve')->where('isverified', 1)
            ->groupBy('day', 'created_by')
            ->orderBy('order_date', 'ASC')
            ->get();
        $data = [];
        $total_income = 0;
        foreach ($record_sales as $row) {
            $data['label'][] = $row->name;
            $data['data'][] = $row->total;
            $total_income += $row->total;
        }
        $data['chart_data'] = json_encode($data);

        $sales = User::where('job_id', '=', 4)->get();
        return view('analysis.index', compact('total_income', 'data', 'title', 'sales'));
    }

    public function salesmanChart(Request $request)
    {
        if (request()->ajax()) {
            $record_sales = SalesOrderModel::Join('users', 'users.id', '=', 'sales_orders.created_by')
                ->select(
                    DB::raw('SUM(sales_orders.total_after_ppn) as total'),
                    DB::raw('DAYNAME(sales_orders.order_date) as day_name'),
                    DB::raw("sales_orders.created_by as day"),
                    DB::raw("users.name as name")
                )
                ->where('users.job_id', '=', 4)

                ->where('order_number', 'like', '%IVPP%')
                ->whereBetween('order_date', array($request->from_date, $request->to_date))
                ->where('isapprove', 'approve')->where('isverified', 1)
                ->groupBy('day', 'created_by')
                ->orderBy('order_date', 'ASC')
                ->get();
            $data = [];
            $total_income = 0;
            foreach ($record_sales as $row) {
                $data['label'][] = $row->name;
                $data['data'][] = $row->total;
                $total_income += $row->total;
            }
            $data['chart_data'] = $data;
            return response()->json($data['chart_data']);
        }
    }
}
