<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\PurchaseOrderModel;
use App\Models\StockModel;
use App\Models\SuppliersModel;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_purchases = PurchaseOrderModel::latest()->get();
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        // validator
        $request->validate([
            "supplier_id" => "required|numeric",
            "warehouse_id" => "required|numeric",
            "due_date" => "required",
            "remark" => "required",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.qty" => "required|numeric"
        ]);

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

        // save purchase orders
        $model = new PurchaseOrderModel();
        $model->order_number = $order_number;
        $model->order_date = Carbon::now()->format('Y-m-d');
        $model->due_date = $request->get('due_date');
        $model->supplier_id = $request->get('supplier_id');
        $model->warehouse_id = $request->get('warehouse_id');
        $model->remark = $request->get('remark');
        $model->created_by = Auth::user()->id;
        $model->isvalidated = 0;
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
            return redirect('/purchase_orders')->with('success', 'Create purchase order ' . $model->order_number . ' success');
        } elseif (!empty($message_duplicate) && $saved) {
            return redirect('/purchase_orders')->with('info', 'Purchase Order add Success! ' . $message_duplicate);
        } else {
            return redirect('/purchase_orders')->with('error', 'Add Purchase Order Fail! Please make sure you have filled all the input');
        }
    }

    public function manage(Request $request, $id)
    {
        // validator
        $request->validate([
            "supplier_id" => "required|numeric",
            "warehouse_id" => "required|numeric",
            "due_date" => "required",
            "remark" => "required",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.qty" => "required|numeric"
        ]);

        //assign object
        $model = PurchaseOrderModel::where('id', $id)->first();
        $model->due_date = $request->get('due_date');
        $model->supplier_id = $request->get('supplier_id');
        $model->warehouse_id = $request->get('warehouse_id');
        $model->remark = $request->get('remark');
        $model->created_by = Auth::user()->id;
        $saved = $model->save();

        //Check Duplicate
        $products_arr = [];
        foreach ($request->get('poFields') as $check) {
            array_push($products_arr, $check['product_id']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return redirect('/purchase_orders')->with('error', "You enter duplicate products! Please check again!");
        }

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

        //Save total    
        $model->total = $total;

        $saved_model = $model->save();
        if ($saved_model == true) {
            return redirect('/purchase_orders')->with('success', "Purchase Order Update Success");
        } else {
            return redirect('/purchase_orders')->with('error', "Purchase Order Update Fail! Please check again!");
        }
    }

    public function validation(Request $request, $id)
    {
        // validator
        $request->validate([
            "remark" => "required",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.qty" => "required|numeric"
        ]);

        //Check Duplicate
        $products_arr = [];
        foreach ($request->get('poFields') as $check) {
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
            } else {
                $stock->stock = $stock->stock + $pod->qty;
            }
        }

        //Save total
        $model->isvalidate = 1;
        $model->total = $total;

        $saved_model = $model->save();
        if ($saved_model == true) {
            return redirect('/purchase_orders')->with('success', "Purchase Order Validation Success");
        } else {
            return redirect('/purchase_orders')->with('error', "Purchase Order Validation Fail! Please check again!");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelPurchaseOrder = PurchaseOrderModel::where('id', $id)->first();
        $modelPurchaseOrder->purchaseOrderDetailsBy()->delete();
        $modelPurchaseOrder->delete();
        return redirect('/purchase_orders')->with('error', 'Delete Data Purchase Order Success');
    }
}
