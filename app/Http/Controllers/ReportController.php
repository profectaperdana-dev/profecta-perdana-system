<?php

namespace App\Http\Controllers;

use App\Models\ClaimModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderDetailModel;
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
                    $invoice = SalesOrderDetailModel::join('sales_orders', 'sales_orders.id', '=', 'sales_order_details.sales_orders_id')
                        ->join('products', 'products.id', '=', 'sales_order_details.products_id')
                        ->join('customers', 'customers.id', '=', 'sales_orders.customers_id')
                        ->join('users', 'users.id', '=', 'sales_orders.created_by')
                        ->select('sales_orders.*', 'sales_order_details.*', 'products.*', 'customers.*', 'users.*')
                        ->whereBetween('order_date', array($request->from_date, $request->to_date))
                        ->where('isapprove', 'approve')
                        ->where('isverified', 1)
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
    public function report_po(Request $request)
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
                    $purchase = PurchaseOrderDetailModel::with('purchaseOrderBy', 'productBy')
                        ->whereHas('purchaseOrderBy', function ($query) {
                            $query->where('isvalidated', 1);
                        })
                        ->whereBetween('order_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                } else {
                    $purchase = PurchaseOrderDetailModel::with('purchaseOrderBy', 'productBy')
                        ->whereHas('purchaseOrderBy', function ($query) {
                            $query->where('isvalidated', 1);
                        })
                        ->whereHas('purchaseOrderBy', function ($query) use ($kode_area) {
                            $query->where('order_number', 'like', "%$kode_area->area_code%");
                        })
                        ->whereHas('purchaseOrderBy', function ($query) use ($request) {
                            $query->whereBetween('order_date', array($request->from_date, $request->to_date));
                        })
                        ->latest()
                        ->get();
                }
            } else {
                if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                    $purchase = PurchaseOrderDetailModel::with('purchaseOrderBy', 'productBy')
                        ->whereHas('purchaseOrderBy', function ($query) {
                            $query->where('isvalidated', 1);
                        })
                        ->latest()
                        ->get();
                } else {
                    $purchase = PurchaseOrderDetailModel::with('purchaseOrderBy', 'productBy')
                        ->whereHas('purchaseOrderBy', function ($query) {
                            $query->where('isvalidated', 1);
                        })
                        ->whereHas('purchaseOrderBy', function ($query) use ($kode_area) {
                            $query->where('order_number', 'like', "%$kode_area->area_code%");
                        })
                        ->latest()
                        ->get();
                }
            }

            return datatables()->of($purchase)
                ->editColumn('isvalidated', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    if ($purchaseOrderDetailModel->purchaseOrderBy->isvalidated == 0) {
                        return 'Not Received';
                    } else {
                        return 'Received';
                    }
                })
                ->editColumn('order_number', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->order_number;
                })
                ->editColumn('order_date', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return date('d-M-Y', strtotime($purchaseOrderDetailModel->purchaseOrderBy->order_date));
                })
                ->editColumn('top', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    if (Gate::allows('isSuperAdmin')) {
                        return $purchaseOrderDetailModel->purchaseOrderBy->top;
                    } else return 'Restricted';
                })
                ->editColumn('due_date', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    if (Gate::allows('isSuperAdmin')) {
                        return date('d-M-Y', strtotime($purchaseOrderDetailModel->purchaseOrderBy->due_date));
                    } else return 'Restricted';
                })
                ->editColumn('remark', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    if (Gate::allows('isSuperAdmin')) {
                        return $purchaseOrderDetailModel->purchaseOrderBy->remark;
                    } else return 'Restricted';
                })
                ->editColumn('total', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    if (Gate::allows('isSuperAdmin')) {
                        return number_format($purchaseOrderDetailModel->purchaseOrderBy->total, 0, ',', '.');
                    } else return 'Restricted';
                })
                ->editColumn('supplier_id', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->supplierBy->nama_supplier;
                })
                ->editColumn('created_by', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->createdPurchaseOrder->name;
                })
                ->editColumn('warehouse_id', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->warehouseBy->warehouses;
                })
                ->editColumn('product', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->productBy->nama_barang;
                })
                ->editColumn('sub_material', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->productBy->sub_materials->nama_sub_material;
                })
                ->editColumn('sub_type', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->productBy->sub_types->type_name;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All Data Purchase Orders in Profecta Perdana : " . Auth::user()->warehouseBy->warehouses,
        ];
        return view('report.po_report', $data);
    }
    public function reportClaim(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isSales') && !Gate::allows('isVerificator')
            && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            if (!empty($request->from_date)) {
                if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                    $invoice = ClaimModel::with('productSales')
                        ->where('status', 1)
                        ->whereBetween('claim_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                } else {
                    $invoice = ClaimModel::with('productSales')
                        ->where('status', 1)
                        ->where('claim_number', 'like', "%$kode_area->area_code%")
                        ->whereBetween('claim_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                }
            } else {
                if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                    $invoice = ClaimModel::join('products', 'products.id', '=', 'claims.product_id')
                        ->join('users', 'users.id', '=', 'claims.e_submittedBy')
                        ->select('claims.*', 'products.*', 'users.*')
                        ->where('claims.status', 1)
                        ->get();
                } else {
                    $invoice = ClaimModel::with('productSales')
                        ->where('status', 1)
                        ->where('claim_number', 'like', "%$kode_area->area_code%")
                        ->latest()
                        ->get();
                }
            }
            return datatables()->of($invoice)
                ->editColumn('product_id', function (ClaimModel $ClaimModel) {
                    return $ClaimModel->productSales->nama_barang;
                })
                ->editColumn('plate_number', function (ClaimModel $ClaimModel) {
                    return '<div class="text-uppercase"> ' . $ClaimModel->plate_number . '</div>';
                })
                ->editColumn('material', function (ClaimModel $ClaimModel) {
                    return $ClaimModel->productSales->sub_materials->nama_sub_material;
                })
                ->editColumn('sub_type', function (ClaimModel $ClaimModel) {
                    return '<a href=""> ' . $ClaimModel->productSales->sub_types->type_name . '</a>';
                })
                ->rawColumns(['sub_type', 'product_id', 'plate_number'])
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All data claim in profecta perdana : " . Auth::user()->warehouseBy->warehouses,
        ];

        return view('report.claim_report', $data);
    }
}
