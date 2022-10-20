<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\MaterialModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderModel;
use App\Models\SalesOrderModel;
use App\Models\SubMaterialModel;
use App\Models\SubTypeModel;
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



        // ALL PRODUCT
        $record_product = SalesOrderModel::Join('sales_order_details', 'sales_order_details.sales_orders_id', '=', 'sales_orders.id')
            ->Join('products', 'products.id', '=', 'sales_order_details.products_id')
            ->select(
                DB::raw('SUM(sales_order_details.qty) as total'),
                DB::raw('products.nama_barang as name')
            )
            ->where('order_number', 'like', '%IVPP%')
            ->whereMonth('order_date', date('m'))
            ->where('isapprove', 'approve')->where('isverified', 1)
            ->groupBy('products.nama_barang')
            ->orderBy('total', 'DESC')
            ->get();
        $data_product = [];
        foreach ($record_product as $val) {
            $data_product['label'][] = $val->name;
            $data_product['data'][] = $val->total;
        }
        $data_product['chart_data'] = json_encode($data_product);

        $materials = MaterialModel::all();
        $sub_materials = SubMaterialModel::all();
        $sub_types = SubTypeModel::all();
        $sales = User::where('job_id', 4)->get();
        //END PRODUCT
        return view('analysis.index', compact('sales', 'materials', 'sub_materials', 'sub_types', 'total_income', 'data', 'title', 'data_product'));
    }

    public function dataBySales(Request $request)
    {
        if (request()->ajax()) {

            // BY SALES
            $recordBySales = SalesOrderModel::Join('users', 'users.id', '=', 'sales_orders.created_by')
                ->select(
                    DB::raw('SUM(sales_orders.total_after_ppn) as total'),
                    DB::raw('order_date as day_name'),
                    DB::raw("DAY(order_date) as day"),
                    DB::raw("users.name as name")
                )
                ->where('users.job_id', '=', 4)
                ->when($request->sales, function ($query) use ($request) {
                    return $query->where('created_by', $request->sales);
                })
                ->when(!empty($request->fd) && !empty($request->td), function ($query) use ($request) {
                    return $query->whereBetween('order_date', array($request->fd, $request->td));
                })
                // ->where('created_by', '=', $request->sales)
                ->where('order_number', 'like', '%IVPP%')
                ->whereMonth('order_date', date('m'))
                ->where('isapprove', 'approve')->where('isverified', 1)
                ->groupBy('day', 'order_date')
                ->orderBy('order_date', 'ASC')
                ->get();
            $dataBySales = [];
            foreach ($recordBySales as $row) {
                $dataBySales['label'][] = date('d, M', strtotime($row->day_name));
                $dataBySales['data'][] = $row->total;
                $dataBySales['nama'] = $row->name;
            }
            $dataBySales['chart_dataBySales'] = $dataBySales;
            // dd($dataBySales);
            return response()->json($dataBySales['chart_dataBySales']);
        }
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

    public function productChart()
    {
        if (request()->ajax()) {
            $from_date = request()->fd;
            $to_date = request()->td;
            $material = request()->m;
            $sub_material = request()->sm;
            $sub_type = request()->st;

            $record_product = SalesOrderModel::Join('sales_order_details', 'sales_order_details.sales_orders_id', '=', 'sales_orders.id')
                ->Join('products', 'products.id', '=', 'sales_order_details.products_id')
                ->select(
                    DB::raw('SUM(sales_order_details.qty) as total'),
                    DB::raw('products.nama_barang as name')
                )
                ->where('order_number', 'like', '%IVPP%')
                ->when(!empty($from_date) && !empty($to_date), function ($query) use ($from_date, $to_date) {
                    return $query->whereBetween('order_date', array($from_date, $to_date));
                })
                ->when(!empty($material), function ($query) use ($material) {
                    return $query->where('products.id_material', $material);
                })
                ->when(!empty($sub_material), function ($query) use ($sub_material) {
                    return $query->where('products.id_sub_material', $sub_material);
                })
                ->when(!empty($sub_type), function ($query) use ($sub_type) {
                    return $query->where('products.id_sub_type', $sub_type);
                })
                ->where('isapprove', 'approve')->where('isverified', 1)
                ->groupBy('products.nama_barang')
                ->orderBy('total', 'DESC')
                ->get();
            $data_product = [];
            foreach ($record_product as $val) {
                $data_product['label'][] = $val->name;
                $data_product['data'][] = $val->total;
            }
            $data_product['chart_data'] = $data_product;
            return response()->json($data_product['chart_data']);
        }
    }

    // make report profit and loss by sales
}
