<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\SalesOrderDetailModel;
use App\Models\SalesOrderModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isSales') && !Gate::allows('isVerificator')
            && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $temp_1 = '';
        $temp_2 = '';
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
                    $invoice = SalesOrderDetailModel::join('sales_orders', 'sales_orders.id', '=', 'sales_order_details.sales_orders_id')
                        ->join('products', 'products.id', '=', 'sales_order_details.products_id')
                        ->join('customers', 'customers.id', '=', 'sales_orders.customers_id')
                        ->join('users', 'users.id', '=', 'sales_orders.created_by')
                        ->select('sales_orders.*', 'sales_order_details.*', 'products.*', 'customers.*', 'users.*')
                        ->where('isapprove', 'approve')
                        ->where('isverified', 1)
                        // ->groupBy('sales_orders.order_number')
                        ->get();
                } else {
                    $invoice = SalesOrderDetailModel::with('customerBy', 'createdSalesOrder', 'salesOrderDetailsBy')
                        ->where('isapprove', 'approve')
                        ->where('isverified', 1)
                        ->where('order_number', 'like', "%$kode_area->area_code%")
                        ->latest()
                        ->get();
                }
            }

            return datatables()->of($invoice)

                // ->editColumn('payment_method', function ($data) {
                //     if ($data->payment_method == 1) {
                //         return 'COD';
                //     } elseif ($data->payment_method == 2) {
                //         return 'CBD';
                //     } else {
                //         return 'Credit';
                //     }
                // })

                // ->editColumn('order_number', function ($data) use ($temp_1, $temp_2) {
                // $temp_1 = $data->order_number;
                // if ($temp_1 != $temp_2) {
                //     $temp_2 = $temp_1;
                //     return $temp_1 . ',' . $temp_2;
                // } else {
                //     return '';
                // }

                // return $data->order_number;
                // })

                ->editColumn('total_after_ppn', function ($data) {
                    return number_format($data->total_after_ppn, 0, ',', '.');
                })
                // ->editColumn('total_after_ppn', function ($data) {
                //     return number_format($data->total_after_ppn, 0, ',', '.');
                // })
                // ->editColumn('total', function ($data) {
                //     return number_format($data->total, 0, ',', '.');
                // })
                // ->editColumn('isPaid', function ($data) {
                //     if ($data->isPaid == 0) {
                //         return 'Unpaid';
                //     } else {
                //         return 'Paid';
                //     }
                // })
                ->editColumn('material', function (SalesOrderDetailModel $SalesOrderDetailModel) {
                    return $SalesOrderDetailModel->productSales->sub_materials->nama_sub_material;
                })
                ->editColumn('sub_type', function (SalesOrderDetailModel $SalesOrderDetailModel) {
                    return '<a href=""> ' . $SalesOrderDetailModel->productSales->sub_types->type_name . '</a>';
                })
                ->rawColumns(['sub_type'])
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All data invoice in profecta perdana : " . Auth::user()->warehouseBy->warehouses,
        ];

        return view('report.index', $data);
    }
}
