<?php

namespace App\Http\Controllers;

use App\Events\PoMessage;
use App\Models\NotificationsModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\PurchaseOrderModel;
use App\Models\ReturnPurchaseDetailModel;
use App\Models\ReturnPurchaseModel;
use App\Models\StockModel;
use App\Models\SuppliersModel;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use PDF;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('isSuperAdmin')) {
            abort(403);
        }
        $all_purchases = PurchaseOrderModel::where('isapprove', 0)->latest()->get();
        $all_suppliers = SuppliersModel::latest()->get();
        $all_warehouses = WarehouseModel::latest()->get();

        $data = [
            "title" => "Purchase Orders",
            "purchases" => $all_purchases,
            "suppliers" => $all_suppliers,
            "warehouses" => $all_warehouses
        ];

        return view('purchase_orders.index', $data);
    }
    public function getPO(Request $request)
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            if (!empty($request->from_date)) {
                if (Gate::allows('isSuperAdmin')) {
                    $purchase = PurchaseOrderModel::with('supplierBy', 'createdPurchaseOrder', 'warehouseBy')
                        ->where('isapprove', 1)
                        ->whereBetween('order_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                } else {
                    $purchase = PurchaseOrderModel::with('supplierBy', 'createdPurchaseOrder', 'warehouseBy')
                        ->where('isapprove', 1)
                        ->where('order_number', 'like', "%$kode_area->area_code%")
                        ->whereBetween('order_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                }
            } else {
                if (Gate::allows('isSuperAdmin')) {
                    $purchase = PurchaseOrderModel::with('supplierBy', 'createdPurchaseOrder', 'warehouseBy')
                        ->where('isapprove', 1)
                        ->latest()
                        ->get();
                } else {
                    $purchase = PurchaseOrderModel::with('supplierBy', 'createdPurchaseOrder', 'warehouseBy')
                        ->where('isapprove', 1)
                        ->where('order_number', 'like', "%$kode_area->area_code%")
                        ->latest()
                        ->get();
                }
            }
            return datatables()->of($purchase)
                ->editColumn('isvalidated', function ($data) {
                    if ($data->isvalidated == 0) {
                        return 'Not Received';
                    } else {
                        return 'Received';
                    }
                })
                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data->order_date));
                })
                ->editColumn('top', function ($data) {
                    if (Gate::allows('isSuperAdmin')) {
                        return $data->top;
                    } else return 'Restricted';
                })
                ->editColumn('due_date', function ($data) {
                    if (Gate::allows('isSuperAdmin')) {
                        return date('d-M-Y', strtotime($data->due_date));
                    } else return 'Restricted';
                })
                ->editColumn('remark', function ($data) {
                    if (Gate::allows('isSuperAdmin')) {
                        return $data->remark;
                    } else return 'Restricted';
                })
                ->editColumn('total', function ($data) {
                    if (Gate::allows('isSuperAdmin')) {
                        return number_format($data->total, 0, ',', '.');
                    } else return 'Restricted';
                })
                ->editColumn('supplier_id', function (PurchaseOrderModel $PurchaseOrderModel) {
                    return $PurchaseOrderModel->supplierBy->nama_supplier;
                })
                ->editColumn('created_by', function (PurchaseOrderModel $PurchaseOrderModel) {
                    return $PurchaseOrderModel->createdPurchaseOrder->name;
                })
                ->editColumn('warehouse_id', function (PurchaseOrderModel $PurchaseOrderModel) {
                    return $PurchaseOrderModel->warehouseBy->warehouses;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($purchase) {
                    $suppliers = SuppliersModel::latest()->get();
                    $warehouses = WarehouseModel::latest()->get();
                    return view('purchase_orders._option', compact('purchase', 'suppliers', 'warehouses'))->render();
                })
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            "title" => "All Data Purchase Orders",
            // 'order_number' =>
        ];

        return view('purchase_orders.po', $data);
    }
    public function printPO($id)
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
        $data = PurchaseOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();

        $pdf = PDF::loadView('purchase_orders.print_po', compact('data', 'warehouse'))->setPaper('A5', 'landscape');
        return $pdf->download($data->order_number . '.pdf');
    }

    public function receivingPO()
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
        if (Gate::allows('isSuperAdmin')) {
            $all_purchases = PurchaseOrderModel::where('isapprove', 1)->where('isvalidated', 0)->latest()->get();
        } else {
            $all_purchases = PurchaseOrderModel::where('isapprove', 1)
                ->where('isvalidated', 0)
                ->where('warehouse_id', Auth::user()->warehouse_id)
                ->latest()
                ->get();
        }

        $return_arr = [];
        foreach ($all_purchases as $value) {
            foreach ($value->purchaseOrderDetailsBy as $detail) {
                $return_amount = 0;
                $selected_return = ReturnPurchaseDetailModel::whereHas('returnBy', function ($query) use ($value) {
                    $query->where('purchase_order_id', $value->id);
                })
                    ->where('product_id', $detail->product_id)
                    ->get();
                foreach ($selected_return as $return) {
                    $return_amount += $return->qty;
                }
                array_push($return_arr, $return_amount);
            }
        }


        $data = [
            "title" => "Receiving Purchase Order",
            "purchases" => $all_purchases,
            "return_amount" => $return_arr
        ];

        return view('purchase_orders.receiving', $data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
        $all_suppliers = SuppliersModel::latest()->get();
        $all_warehouses = WarehouseModel::latest()->get();

        $data = [
            "title" => "Create Purchase Orders",
            "suppliers" => $all_suppliers,
            "warehouses" => $all_warehouses,
        ];

        return view('purchase_orders.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
        // validator
        $request->validate([
            "supplier_id" => "required|numeric",
            "warehouse_id" => "required|numeric",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.qty" => "required|numeric"
        ]);



        // save purchase orders
        $model = new PurchaseOrderModel();
        $model->order_number = '-';
        $model->order_date = Carbon::now()->format('Y-m-d');
        $model->due_date = Carbon::now()->format('Y-m-d');
        $model->supplier_id = $request->get('supplier_id');
        $model->warehouse_id = $request->get('warehouse_id');
        $model->remark = '-';
        $model->created_by = Auth::user()->id;
        $model->isvalidated = 0;
        $model->isapprove = 0;

        $saved = $model->save();

        // save purchase order details
        $total = 0;
        $message_duplicate = '';
        if ($saved) {
            foreach ($request->poFields as $value) {
                $data = new PurchaseOrderDetailModel();
                $data->product_id = $value['product_id'];
                $data->qty = $value['qty'];
                $data->purchase_order_id = $model->id;
                $check_duplicate = PurchaseOrderDetailModel::where('purchase_order_id', $data->purchase_order_id)
                    ->where('product_id', $data->product_id)
                    ->count();
                if ($check_duplicate > 0) {
                    $message_duplicate = "You enter duplication of products. Please recheck the PO you set.";
                    continue;
                } else {
                    $harga = ProductModel::where('id', $data->product_id)->first();
                    $total = $total + ($harga->harga_beli * $data->qty);
                    $data->save();
                }
            }
        }
        $model->total = $total;
        $saved = $model->save();

        if (empty($message_duplicate) && $saved) {
            $message = 'hey there is a purchase that must be checked  ';
            event(new PoMessage('From:' . Auth::user()->name, $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->job_id = 3;
            $notif->save();
            return redirect('/purchase_orders/create')->with('success', 'Create purchase order ' . $model->order_number . ' success');
        } elseif (!empty($message_duplicate) && $saved) {
            $message = 'hey there is a purchase that must be checked ';
            event(new PoMessage('From:' . Auth::user()->name, $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->job_id = 3;
            $notif->save();
            return redirect('/purchase_orders/create')->with('info', 'Purchase Order add Success! ' . $message_duplicate);
        } else {
            return redirect('/purchase_orders/create')->with('error', 'Add Purchase Order Fail! Please make sure you have filled all the input');
        }
    }

    public function manage(Request $request, $id)
    {
        if (!Gate::allows('isSuperAdmin')) {
            abort(403);
        }
        // validator
        $request->validate([
            "supplier_id" => "required|numeric",
            "warehouse_id" => "required|numeric",
            "order_date" => "required",
            "remark" => "required",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.qty" => "required|numeric"
        ]);
        //Check Duplicate
        $products_arr = [];
        foreach ($request->poFields as $check) {
            array_push($products_arr, $check['product_id']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return redirect('/purchase_orders')->with('error', "You enter duplicate products! Please check again!");
        }
        //assign object
        $model = PurchaseOrderModel::where('id', $id)->first();
        $model->order_date = $request->get('order_date');
        $model->top = $request->get('top');
        $dt = new DateTimeImmutable(date('Y-m-d', strtotime($model->order_date)), new DateTimeZone('Asia/Jakarta'));
        $dt = $dt->modify("+" . $model->top . " days");
        $model->due_date = $dt;
        $model->supplier_id = $request->get('supplier_id');
        $model->warehouse_id = $request->get('warehouse_id');
        $model->remark = $request->get('remark');
        $old_isapprove = $model->isapprove;
        $model->isapprove = 1;
        $model->created_by = Auth::user()->id;

        //Create Order Number
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', $request->get('warehouse_id'))
            ->first();
        $length = 3;
        $id = intval(PurchaseOrderModel::where('order_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $po_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $order_number = 'POPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $po_number_id;
        $model->order_number = $order_number;
        $model->pdf_po = $model->order_number . '.pdf';

        $saved = $model->save();

        //Save POD Input and Total
        $total = 0;
        foreach ($request->poFields as $product) {
            $product_exist = PurchaseOrderDetailModel::where('purchase_order_id', $id)
                ->where('product_id', $product['product_id'])->first();
            // dd($product_exist);
            if ($product_exist != null) {
                $product_exist->qty = $product['qty'];
                $product_exist->save();
            } else {
                $new_product = new PurchaseOrderDetailModel();
                $new_product->purchase_order_id = $id;
                $new_product->product_id = $product['product_id'];
                $new_product->qty = $product['qty'];
                $new_product->save();
            }
            $harga = ProductModel::where('id', $product['product_id'])->first();
            $total = $total + ($harga->harga_beli * $product['qty']);
        }

        //Delete product that not in POD Input
        $del = PurchaseOrderDetailModel::where('purchase_order_id', $id)
            ->whereNotIn('product_id', $products_arr)->delete();

        //Save total
        $model->total = $total;

        $saved_model = $model->save();
        if ($saved_model == true) {
            $data = PurchaseOrderModel::where('order_number', $model->order_number)->first();
            $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
            $pdf = PDF::loadView('purchase_orders.print_po', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->order_number . '.pdf');

            return redirect('/all_purchase_orders')->with('success', "Purchase Order Update Success");
        } else {
            return redirect('/purchase_orders')->with('error', "Purchase Order Update Fail! Please check again!");
        }
    }

    public function validation(Request $request, $id)
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
        // validator
        $request->validate([
            "remark" => "required",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.qty" => "required|numeric"
        ]);

        //Check Duplicate
        $products_arr = [];
        foreach ($request->poFields as $check) {
            array_push($products_arr, $check['product_id']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return redirect('/purchase_orders')->with('error', "You enter duplicate products! Please check again!");
        }

        //assign object
        $model = PurchaseOrderModel::where('id', $id)->first();
        $model->remark = $request->get('remark');
        $saved = $model->save();

        //Save POD Input and Total
        $total = 0;
        foreach ($request->poFields as $product) {
            $product_exist = PurchaseOrderDetailModel::where('purchase_order_id', $id)
                ->where('product_id', $product['product_id'])->first();
            if ($product_exist != null) {
                $product_exist->qty = $product['qty'];
                $product_exist->save();
            } else {
                $new_product = new PurchaseOrderDetailModel();
                $new_product->purchase_order_id = $id;
                $new_product->product_id = $product['product_id'];
                $new_product->qty = $product['qty'];
                $new_product->save();
            }
            $harga = ProductModel::where('id', $product['product_id'])->first();
            $total = $total + ($harga->harga_beli * $product['qty']);
        }

        //Delete product that not in POD Input
        $del = PurchaseOrderDetailModel::where('purchase_order_id', $id)
            ->whereNotIn('product_id', $products_arr)->delete();

        //Change Stock
        $selected_pod = PurchaseOrderDetailModel::where('purchase_order_id', $id)->get();
        foreach ($selected_pod as $pod) {
            $stock = StockModel::where('warehouses_id', $model->warehouse_id)
                ->where('products_id', $pod->product_id)->first();
            if ($stock == null) {
                $new_stock = new StockModel();
                $new_stock->products_id = $pod->product_id;
                $new_stock->warehouses_id = $model->warehouse_id;
                $new_stock->stock = $pod->qty;
                $new_stock->save();
            } else {
                $stock->stock = $stock->stock + $pod->qty;
                $stock->save();
            }
        }

        //Save total
        $model->isvalidated = 1;
        $model->total = $total;

        $saved_model = $model->save();
        if ($saved_model == true) {
            return redirect('/purchase_orders/receiving')->with('success', "Purchase Order Validation Success");
        } else {
            return redirect('/purchase_orders/receiving')->with('error', "Purchase Order Validation Fail! Please check again!");
        }
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

    public function updatePO(Request $request, $id)
    {

        if (!Gate::allows('isSuperAdmin')) {
            abort(403);
        }
        // validator
        $request->validate([
            "supplier_id" => "required|numeric",
            "warehouse_id" => "required|numeric",
            "order_date" => "required",
            "remark" => "required",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.qty" => "required|numeric"
        ]);
        //Check Duplicate
        $products_arr = [];
        foreach ($request->poFields as $check) {
            array_push($products_arr, $check['product_id']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return redirect('/purchase_orders')->with('error', "You enter duplicate products! Please check again!");
        }
        //assign object
        $model = PurchaseOrderModel::where('id', $id)->first();
        $model->order_date = $request->get('order_date');
        $model->top = $request->get('top');
        $dt = new DateTimeImmutable(date('Y-m-d', strtotime($model->order_date)), new DateTimeZone('Asia/Jakarta'));
        $dt = $dt->modify("+" . $model->top . " days");
        $model->due_date = $dt;
        $model->supplier_id = $request->get('supplier_id');
        $model->warehouse_id = $request->get('warehouse_id');
        $model->remark = $request->get('remark');
        $model->created_by = Auth::user()->id;

        $saved = $model->save();

        if ($model->isvalidated == 1) {
            //Restore data to before changed
            $po_restore = PurchaseOrderDetailModel::where('purchase_order_id', $id)->get();
            foreach ($po_restore as $restore) {
                $stock = StockModel::where('warehouses_id', $model->warehouse_id)
                    ->where('products_id', $restore->product_id)->first();
                $stock->stock = $stock->stock - $restore->qty;
                $stock->save();
            }
        }


        //Save POD Input and Total and Change Stock
        $total = 0;
        foreach ($request->poFields as $product) {
            $product_exist = PurchaseOrderDetailModel::where('purchase_order_id', $id)
                ->where('product_id', $product['product_id'])->first();

            if ($product_exist != null) {
                $old_qty = $product_exist->qty;
                $product_exist->qty = $product['qty'];
                $product_exist->save();
            } else {
                $new_product = new PurchaseOrderDetailModel();
                $new_product->purchase_order_id = $id;
                $new_product->product_id = $product['product_id'];
                $new_product->qty = $product['qty'];
                $new_product->save();
            }
            $harga = ProductModel::where('id', $product['product_id'])->first();
            $total = $total + ($harga->harga_beli * $product['qty']);
        }

        //Delete product that not in POD Input
        $del = PurchaseOrderDetailModel::where('purchase_order_id', $id)
            ->whereNotIn('product_id', $products_arr)->delete();

        if ($model->isvalidated == 1) {
            //Change Stock
            $selected_pod = PurchaseOrderDetailModel::where('purchase_order_id', $id)->get();
            foreach ($selected_pod as $pod) {
                $stock = StockModel::where('warehouses_id', $model->warehouse_id)
                    ->where('products_id', $pod->product_id)->first();
                if ($stock == null) {
                    $new_stock = new StockModel();
                    $new_stock->products_id = $pod->product_id;
                    $new_stock->warehouses_id = $model->warehouse_id;
                    $new_stock->stock = $pod->qty;
                    $new_stock->save();
                } else {
                    $stock->stock = $stock->stock + $pod->qty;
                    $stock->save();
                }
            }
        }


        //Save total
        $model->total = $total;

        $saved_model = $model->save();
        if ($saved_model == true) {
            $data = PurchaseOrderModel::where('order_number', $model->order_number)->first();
            $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
            $pdf = PDF::loadView('purchase_orders.print_po', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->order_number . '_edited.pdf');

            return redirect('/all_purchase_orders')->with('success', "Purchase Order Update Success");
        } else {
            return redirect('/purchase_orders')->with('error', "Purchase Order Update Fail! Please check again!");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if (!Gate::allows('level1') || !Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
        $modelPurchaseOrder = PurchaseOrderModel::where('id', $id)->first();
        $modelPurchaseOrder->purchaseOrderDetailsBy()->delete();
        $modelPurchaseOrder->delete();
        return redirect('/purchase_orders')->with('error', 'Delete Data Purchase Order Success');
    }

    public function getAllDetail()
    {
        $po_id = request()->p;

        $getqty = PurchaseOrderDetailModel::where('purchase_order_id', $po_id)->get();
        return response()->json($getqty);
    }
    public function getQtyDetail()
    {
        $po_id = request()->p;
        $product_id = request()->pr;

        $getqty = PurchaseOrderDetailModel::where('purchase_order_id', $po_id)->where('product_id', $product_id)->first();
        $_qty = $getqty->qty;
        $selected_return = ReturnPurchaseModel::with('returnDetailsBy')->where('purchase_order_id', $po_id)->get();

        $return = 0;
        if ($selected_return != null) {
            foreach ($selected_return as $value) {
                $selected_detail = ReturnPurchaseDetailModel::where('return_id', $value->id)->where('product_id', $product_id)->first();
                $return = $return + $selected_detail->qty;
            }
        }
        $data = [
            'qty' => $_qty,
            'return' => $return
        ];
        return response()->json($data);
    }

    public function selectReturn()
    {
        try {
            $po_id = request()->p;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = PurchaseOrderDetailModel::join('products', 'products.id', '=', 'purchase_order_details.product_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('products.nama_barang', 'LIKE', "%$search%")
                    ->where('purchase_order_id', $po_id)
                    ->orWhere('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->where('purchase_order_id', $po_id)
                    ->orWhere('product_sub_materials.nama_sub_material', 'LIKE', "%$search%")
                    ->where('purchase_order_id', $po_id)
                    ->get();
            } else {
                $product = PurchaseOrderDetailModel::join('products', 'products.id', '=', 'purchase_order_details.product_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('purchase_order_id', $po_id)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
