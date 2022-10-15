<?php

namespace App\Http\Controllers;

use App\Models\AccuAccuClaimModel;
use App\Models\AccuClaimDetailModel;
use App\Models\AccuClaimModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\ReturnDetailModel;
use App\Models\ReturnPurchaseDetailModel;
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
                ->editColumn('total', function (SalesOrderDetailModel $SalesOrderDetailModel) {

                    $diskon = $SalesOrderDetailModel->discount / 100;
                    $diskon_rp = $SalesOrderDetailModel->discount_rp;
                    // foreach ($getdiskon as $dis) {
                    //     if ($dis->products_id == $SalesOrderDetailModel->product_id) {
                    //         $diskon = $dis->discount / 100;
                    //         $diskon_rp = $dis->discount_rp;
                    //     }
                    // }
                    $hargaDiskon = $SalesOrderDetailModel->productSales->harga_jual_nonretail * $diskon;
                    $hargaAfterDiskon = $SalesOrderDetailModel->productSales->harga_jual_nonretail - $hargaDiskon - $diskon_rp;
                    $sub_total = $hargaAfterDiskon * $SalesOrderDetailModel->qty;
                    $ppn = 0.11 * $sub_total;
                    $total = $sub_total + $ppn;
                    return number_format($total, 0, ',', '.');
                })

                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data->order_date));
                })
                ->editColumn('paid_date', function ($data) {
                    if ($data->paid_date == null) {
                        return "-";
                    } else return date('d-M-Y', strtotime($data->paid_date));
                })
                ->editColumn('total_after_ppn', function ($data) {
                    return number_format($data->total_after_ppn, 0, ',', '.');
                })
                ->editColumn('discount_rp', function ($data) {
                    return number_format($data->discount_rp, 0, ',', '.');
                })
                ->editColumn('total', function ($data) {
                    if (Gate::allows('isSuperAdmin')) {
                        $diskon_persen = $data->discount / 100;
                        $produk_diskon = $data->productSales->harga_jual_nonretail * $diskon_persen;
                        $harga_setelah_diskon = $data->productSales->harga_jual_nonretail - $produk_diskon - $data->discount_rp;
                        $total = $harga_setelah_diskon * $data->qty;
                        return number_format($total, 0, ',', '.');
                    } else return 'Restricted';
                })
                ->editColumn('ppn', function ($data) {
                    if (Gate::allows('isSuperAdmin')) {
                        $diskon_persen = $data->discount / 100;
                        $produk_diskon = $data->productSales->harga_jual_nonretail * $diskon_persen;
                        $harga_setelah_diskon = $data->productSales->harga_jual_nonretail - $produk_diskon - $data->discount_rp;
                        $total = $harga_setelah_diskon * $data->qty;
                        $ppn = 0.11 * $total;
                        return number_format($ppn, 0, ',', '.');
                    } else return 'Restricted';
                })
                ->editColumn('total_ppn', function ($data) {
                    if (Gate::allows('isSuperAdmin')) {
                        $diskon_persen = $data->discount / 100;
                        $produk_diskon = $data->productSales->harga_jual_nonretail * $diskon_persen;
                        $harga_setelah_diskon = $data->productSales->harga_jual_nonretail - $produk_diskon - $data->discount_rp;
                        $total = $harga_setelah_diskon * $data->qty;
                        $ppn = 0.11 * $total;
                        $total_ppn = $total + $ppn;
                        return number_format($total_ppn, 0, ',', '.');
                    } else return 'Restricted';
                })

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
                        $total = $purchaseOrderDetailModel->productBy->harga_beli * $purchaseOrderDetailModel->qty;

                        return number_format($total, 0, ',', '.');
                    } else return 'Restricted';
                })
                ->editColumn('ppn', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    if (Gate::allows('isSuperAdmin')) {
                        $total = $purchaseOrderDetailModel->productBy->harga_beli * $purchaseOrderDetailModel->qty;
                        $ppn = 0.11 * $total;

                        return number_format($ppn, 0, ',', '.');
                    } else return 'Restricted';
                })
                ->editColumn('total_ppn', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    if (Gate::allows('isSuperAdmin')) {
                        $total = $purchaseOrderDetailModel->productBy->harga_beli * $purchaseOrderDetailModel->qty;
                        $ppn = 0.11 * $total;
                        $total_ppn = $total + $ppn;
                        return number_format($total_ppn, 0, ',', '.');
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
        // $invoice = AccuClaimModel::join('users', 'users.id', '=', 'accu_claims.e_submittedBy')
        //     ->select('accu_claims.*', 'users.name')
        //     ->where('accu_claims.status', 1)
        //     ->first();
        // // dd($invoice->id);
        // $detail = AccuClaimDetailModel::where('id_accu_claim', $invoice->id)->get();

        // $diagnosa = '';
        // foreach ($detail as $value) {
        //     $diagnosa .= $value->diagnosa . ', ';
        // }
        // dd($diagnosa);
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
                    $invoice = AccuClaimModel::where('status', 1)
                        ->whereBetween('claim_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                } else {
                    $invoice = AccuClaimModel::where('status', 1)
                        ->where('claim_number', 'like', "%$kode_area->area_code%")
                        ->whereBetween('claim_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                }
            } else {
                if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                    $invoice = AccuClaimModel::join('users', 'users.id', '=', 'accu_claims.e_submittedBy')
                        ->select('accu_claims.*', 'users.name')
                        ->where('accu_claims.status', 1)
                        ->get();
                } else {
                    $invoice = AccuClaimModel::join('users', 'users.id', '=', 'accu_claims.e_submittedBy')
                        ->select('accu_claims.*', 'users.*')
                        ->where('accu_claims.status', 1)
                        ->get();
                }
            }
            return datatables()->of($invoice)
                ->editColumn('car_brand_id', function (AccuClaimModel $AccuClaimModel) {
                    return $AccuClaimModel->carBrandBy->car_brand;
                })
                ->editColumn('car_type_id', function (AccuClaimModel $AccuClaimModel) {
                    return $AccuClaimModel->carTypeBy->car_type;
                })
                ->editColumn('product_id', function (AccuClaimModel $AccuClaimModel) {
                    return $AccuClaimModel->productSales->nama_barang;
                })
                ->editColumn('diagnosa', function (AccuClaimModel $AccuClaimModel) {
                    $detail = AccuClaimDetailModel::where('id_accu_claim', $AccuClaimModel->id)->get();

                    $diagnosa = '';
                    foreach ($detail as $key => $value) {
                        $diagnosa .= $value->diagnosa . ',<br>';
                    }
                    return $diagnosa;

                    // return $valDiagnosa;
                })
                ->editColumn('plate_number', function (AccuClaimModel $AccuClaimModel) {
                    return '<div class="text-uppercase"> ' . $AccuClaimModel->plate_number . '</div>';
                })
                ->editColumn('cost', function (AccuClaimModel $AccuClaimModel) {
                    return number_format($AccuClaimModel->cost);
                })
                ->editColumn('e_submittedBy', function (AccuClaimModel $AccuClaimModel) {
                    return $AccuClaimModel->createdBy->name;
                })
                ->rawColumns(['plate_number', 'diagnosa', 'cost', 'e_submittedBy'])
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All data claim in profecta perdana : " . Auth::user()->warehouseBy->warehouses,
        ];
        return view('report.claim_report', $data);
    }

    public function report_return(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }


        // get kode area
        // dd($request->all());
        if ($request->ajax()) {

            if (!empty($request->from_date)) {

                $return = ReturnDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) use ($request) {
                        $query->whereBetween('return_date', array($request->from_date, $request->to_date));
                    })
                    ->latest()
                    ->get();
            } else {
                $return = ReturnDetailModel::with('returnBy', 'productBy')
                    ->latest()
                    ->get();
            }

            return datatables()->of($return)
                ->editColumn('return_number', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->return_number;
                })
                ->editColumn('sales_order_id', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->salesOrderBy->order_number;
                })
                ->editColumn('return_date', function (ReturnDetailModel $returnDetailModel) {
                    return date('d-M-Y', strtotime($returnDetailModel->returnBy->return_date));
                })
                ->editColumn('total', function (ReturnDetailModel $returnDetailModel) {
                    $diskon = 0;
                    $diskon_rp = 0;
                    $getdiskon = $returnDetailModel->returnBy->salesOrderBy->salesOrderDetailsBy;
                    foreach ($getdiskon as $dis) {
                        if ($dis->products_id == $returnDetailModel->product_id) {
                            $diskon = $dis->discount / 100;
                            $diskon_rp = $dis->discount_rp;
                        }
                    }
                    $hargaDiskon = $returnDetailModel->productBy->harga_jual_nonretail * $diskon;
                    $hargaAfterDiskon = $returnDetailModel->productBy->harga_jual_nonretail - $hargaDiskon - $diskon_rp;
                    $sub_total = $hargaAfterDiskon * $returnDetailModel->qty;
                    $ppn = 0.11 * $sub_total;
                    $total = $sub_total + $ppn;
                    return number_format($total, 0, ',', '.');
                })
                ->editColumn('return_reason', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->return_reason;
                })
                ->editColumn('created_by', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->createdBy->name;
                })
                ->editColumn('product', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->productBy->nama_barang;
                })
                ->editColumn('sub_material', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->productBy->sub_materials->nama_sub_material;
                })
                ->editColumn('sub_type', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->productBy->sub_types->type_name;
                })
                ->editColumn('discount', function (ReturnDetailModel $returnDetailModel) {
                    $diskon = 0;
                    $getdiskon = $returnDetailModel->returnBy->salesOrderBy->salesOrderDetailsBy;
                    foreach ($getdiskon as $dis) {
                        if ($dis->products_id == $returnDetailModel->product_id) {
                            $diskon = $dis->discount;
                        }
                    }
                    return $diskon;
                })
                ->editColumn('discount_rp', function (ReturnDetailModel $returnDetailModel) {
                    $diskon = 0;
                    $getdiskon = $returnDetailModel->returnBy->salesOrderBy->salesOrderDetailsBy;
                    foreach ($getdiskon as $dis) {
                        if ($dis->products_id == $returnDetailModel->product_id) {
                            $diskon = $dis->discount_rp;
                        }
                    }
                    return $diskon;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All Report Return Invoice in Profecta Perdana : " . Auth::user()->warehouseBy->warehouses,
        ];
        return view('report.return', $data);
    }

    public function report_return_purchase(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }


        // get kode area
        // dd($request->all());
        if ($request->ajax()) {

            if (!empty($request->from_date)) {

                $return = ReturnPurchaseDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) use ($request) {
                        $query->whereBetween('return_date', array($request->from_date, $request->to_date));
                    })
                    ->latest()
                    ->get();
            } else {
                $return = ReturnPurchaseDetailModel::with('returnBy', 'productBy')
                    ->latest()
                    ->get();
            }

            return datatables()->of($return)
                ->editColumn('return_number', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->returnBy->return_number;
                })
                ->editColumn('purchase_order_id', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->returnBy->purchaseOrderBy->order_number;
                })
                ->editColumn('return_date', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return date('d-M-Y', strtotime($returnPurchaseDetailModel->returnBy->return_date));
                })
                ->editColumn('total', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    $total = $returnPurchaseDetailModel->productBy->harga_beli * $returnPurchaseDetailModel->qty;

                    return number_format($total, 0, ',', '.');
                })
                ->editColumn('return_reason', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->returnBy->return_reason;
                })
                ->editColumn('created_by', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->returnBy->createdBy->name;
                })
                ->editColumn('product', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->productBy->nama_barang;
                })
                ->editColumn('sub_material', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->productBy->sub_materials->nama_sub_material;
                })
                ->editColumn('sub_type', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->productBy->sub_types->type_name;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All Report Return Purchases in Profecta Perdana : " . Auth::user()->warehouseBy->warehouses,
        ];
        return view('report.return_purchase', $data);
    }
}
