<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\PurchaseOrderModel;
use App\Models\SalesOrderModel;
use App\Models\SuppliersModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;


class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');

        $title = 'All Invoice Order File PDF';
        $keyword = $request->get('search');
        $warehouse_id = $request->warehouse_id;
        $selected_customer = $request->val_cus;
        $from_date = $request->date;
        $to_date = $request->date2;
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
            ->oldest('warehouses')->get();
        $all_warehouse = WarehouseModel::where('type', 5)->oldest('warehouses')->get();

        if ($user_warehouse->count() > 1) {
            $data = SalesOrderModel::leftJoin('customers', 'customers.id', '=', 'sales_orders.customers_id')
                ->select('sales_orders.*', 'customers.name_cust', 'customers.code_cust')
                ->when($request->warehouse_id != null, function ($q) use ($request) {
                    return $q->where('warehouse_id', $request->warehouse_id);
                })
                ->when($request->val_cus != null, function ($q) use ($request) {
                    return $q->where('customers_id', $request->val_cus);
                })
                ->when($request->date != null && $request->date2 != null, function ($q) use ($request) {
                    return $q->whereBetween('order_date', array($request->date, $request->date2));
                })
                ->when($request->search != null, function ($q) use ($request) {
                    return $q->where('sales_orders.pdf_do', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('customers.name_cust', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('customers.code_cust', 'LIKE', '%' . $request->search . '%');
                })
                ->oldest('order_date')
                ->get();
        } else {
            $data = SalesOrderModel::leftJoin('customers', 'customers.id', '=', 'sales_orders.customers_id')
                ->select('sales_orders.*', 'customers.name_cust', 'customers.code_cust')
                ->when($request->val_cus != null, function ($q) use ($request) {
                    return $q->where('customers_id', $request->val_cus);
                })
                ->when($request->date != null && $request->date2 != null, function ($q) use ($request) {
                    return $q->whereBetween('order_date', array($request->date, $request->date2));
                })
                ->when($request->search != null, function ($q) use ($request) {
                    return $q->where('sales_orders.pdf_do', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('customers.name_cust', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('customers.code_cust', 'LIKE', '%' . $request->search . '%');
                })
                ->whereIn('warehouse_id', array_column($user_warehouse->toArray(), 'id'))
                ->oldest('order_date')
                ->get();
        }
        $customer = CustomerModel::oldest('name_cust')->get();
        return view('files.index', compact(
            'title',
            'data',
            'customer',
            'all_warehouse',
            'user_warehouse',
            'keyword',
            'warehouse_id',
            'from_date',
            'to_date',
            'selected_customer'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getDO(Request $request)
    {
        $title = 'All Delivery Order File PDF';
        $keyword = $request->get('search');
        $warehouse_id = $request->warehouse_id;
        $selected_customer = $request->val_cus;
        $from_date = $request->date;
        $to_date = $request->date2;
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
            ->oldest('warehouses')->get();
        $all_warehouse = WarehouseModel::where('type', 5)->oldest('warehouses')->get();

        if ($user_warehouse->count() > 1) {
            $data = SalesOrderModel::leftJoin('customers', 'customers.id', '=', 'sales_orders.customers_id')
                ->select('sales_orders.*', 'customers.name_cust', 'customers.code_cust')
                ->when($request->warehouse_id != null, function ($q) use ($request) {
                    return $q->where('warehouse_id', $request->warehouse_id);
                })
                ->when($request->val_cus != null, function ($q) use ($request) {
                    return $q->where('customers_id', $request->val_cus);
                })
                ->when($request->date != null && $request->date2 != null, function ($q) use ($request) {
                    return $q->whereBetween('order_date', array($request->date, $request->date2));
                })
                ->when($request->date == null, function ($q) {
                    return $q->whereDate('order_date', Carbon::today());
                })
                ->when($request->search != null, function ($q) use ($request) {
                    return $q->where('sales_orders.pdf_do', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('customers.name_cust', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('customers.code_cust', 'LIKE', '%' . $request->search . '%');
                })
                ->oldest('order_date')
                ->get();
        } else {
            $data = SalesOrderModel::leftJoin('customers', 'customers.id', '=', 'sales_orders.customers_id')
                ->select('sales_orders.*', 'customers.name_cust', 'customers.code_cust')
                ->when($request->val_cus != null, function ($q) use ($request) {
                    return $q->where('customers_id', $request->val_cus);
                })
                ->when($request->date != null && $request->date2 != null, function ($q) use ($request) {
                    return $q->whereBetween('order_date', array($request->date, $request->date2));
                })
                ->when($request->date == null, function ($q) {
                    return $q->whereDate('order_date', Carbon::today());
                })
                ->when($request->search != null, function ($q) use ($request) {
                    return $q->where('sales_orders.pdf_do', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('customers.name_cust', 'LIKE', '%' . $request->search . '%')
                        ->orWhere('customers.code_cust', 'LIKE', '%' . $request->search . '%');
                })
                ->whereIn('warehouse_id', array_column($user_warehouse->toArray(), 'id'))
                ->oldest('order_date')
                ->get();
        }

        $customer = CustomerModel::oldest('name_cust')->get();

        return view('files.do', compact(
            'title',
            'data',
            'customer',
            'all_warehouse',
            'user_warehouse',
            'keyword',
            'warehouse_id',
            'selected_customer',
            'from_date',
            'to_date'
        ));
    }
    public function getFilePo(Request $request)
    {
        $title = 'All Purchase Order File PDF';
        $keyword = $request->get('search');
        $warehouse_id = $request->warehouse_id;
        $selected_supplier = $request->val_cus;
        $from_date = $request->date;
        $to_date = $request->date2;
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
            ->oldest('warehouses')->get();
        $all_warehouse = WarehouseModel::where('type', 5)->oldest('warehouses')->get();

        if ($user_warehouse->count() > 1) {
            $data = PurchaseOrderModel::leftJoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
                ->select('purchase_orders.*', 'suppliers.nama_supplier')
                ->when($request->warehouse_id != null, function ($q) use ($request) {
                    return $q->where('warehouse_id', $request->warehouse_id);
                })
                ->when($request->val_cus != null, function ($q) use ($request) {
                    return $q->where('supplier_id', $request->val_cus);
                })
                ->when($request->date != null && $request->date2 != null, function ($q) use ($request) {
                    return $q->whereBetween('order_date', array($request->date, $request->date2));
                })
                ->when($request->search != null, function ($q) use ($request) {
                    return $q->where('purchase_orders.pdf_po', 'LIKE', '%' . $request->val_cus . '%')
                        ->orWhere('suppliers.nama_supplier', 'LIKE', '%' . $request->val_cus . '%');
                })
                ->oldest('order_date')
                ->get();
        } else {
            $data = PurchaseOrderModel::leftJoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
                ->select('purchase_orders.*', 'suppliers.nama_supplier')
                ->when($request->val_cus != null, function ($q) use ($request) {
                    return $q->where('supplier_id', $request->val_cus);
                })
                ->when($request->date != null && $request->date2 != null, function ($q) use ($request) {
                    return $q->whereBetween('order_date', array($request->date, $request->date2));
                })
                ->when($request->search != null, function ($q) use ($request) {
                    return $q->where('purchase_orders.pdf_po', 'LIKE', '%' . $request->val_cus . '%')
                        ->orWhere('suppliers.nama_supplier', 'LIKE', '%' . $request->val_cus . '%');
                })
                ->whereIn('warehouse_id', array_column($user_warehouse->toArray(), 'id'))
                ->oldest('order_date')
                ->get();
        }

        // if ($request->get('val_cus') == '' && $request->get('search') != '' && $request->get('date') == '' && $request->get('date2') == '') {
        //     $data = PurchaseOrderModel::leftJoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
        //         ->select('purchase_orders.*', 'suppliers.nama_supplier')
        //         ->where('purchase_orders.pdf_po', 'LIKE', '%' . $keyword . '%')
        //         ->orWhere('suppliers.nama_supplier', 'LIKE', '%' . $keyword . '%')
        //         ->latest()
        //         ->get();
        // } else if ($request->get('val_cus') != '' && $request->get('search') == '' && $request->get('date') == '' && $request->get('date2') == '') {
        //     $data = PurchaseOrderModel::leftJoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
        //         ->select('purchase_orders.*', 'suppliers.id')
        //         ->where('purchase_orders.supplier_id', $request->get('val_cus'))
        //         ->latest()
        //         ->get();
        // } else if ($request->get('val_cus') == '' && $request->get('search') == '' && $request->get('date') != '' && $request->get('date2') != '') {
        //     $data = PurchaseOrderModel::leftJoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
        //         ->select('purchase_orders.*', 'suppliers.nama_supplier')
        //         ->whereBetween('order_date', array($request->date, $request->date2))
        //         ->latest()
        //         ->get();
        // } else if ($request->get('val_cus') != '' && $request->get('search') == '' && $request->get('date') != '' && $request->get('date2') != '') {
        //     $data = PurchaseOrderModel::leftJoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
        //         ->select('purchase_orders.*', 'suppliers.nama_supplier')
        //         ->whereBetween('order_date', array($request->date, $request->date2))
        //         ->Where('purchase_orders.supplier_id', $request->get('val_cus'))
        //         ->latest()
        //         ->get();
        // } else {
        //     $data = PurchaseOrderModel::leftJoin('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
        //         ->select('purchase_orders.*', 'suppliers.nama_supplier')
        //         ->latest()
        //         ->get();
        // }
        $customer = SuppliersModel::oldest('nama_supplier')->get();

        return view('files.po', compact(
            'title',
            'data',
            'customer',
            'all_warehouse',
            'user_warehouse',
            'keyword',
            'warehouse_id',
            'selected_supplier',
            'from_date',
            'to_date'
        ));
    }
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
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
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
