<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use App\Models\CustomerModel;
use App\Models\DirectSalesModel;
use App\Models\Finance\Coa;
use App\Models\ItemPromotionCostModel;
use App\Models\ItemPromotionModel;
use App\Models\ItemPromotionCategoryModel;
use App\Models\ItemPromotionMutationDetailModel;
use App\Models\ItemPromotionMutationModel;
use App\Models\ItemPromotionPurchaseDetailModel;
use App\Models\ItemPromotionPurchaseModel;
use App\Models\ItemPromotionStockModel;
use App\Models\ItemPromotionSupplier;
use App\Models\ItemPromotionSupplierModel;
use App\Models\ItemPromotionTransactionDetailModel;
use App\Models\ItemPromotionTransactionModel;
use App\Models\ReturnItemPromotionDetailModel;
use App\Models\ReturnItemPromotionModel;
use App\Models\ReturnItemPromotionPurchaseDetailModel;
use App\Models\ReturnItemPromotionPurchaseModel;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class ItemPromotionController extends Controller
{
    public function getCategory()
    {
        $prefix = '5-4';
        $data = Coa::where('coa_code', 'like', $prefix . '%')->get();
        return response()->json($data);
    }
    public function index(Request $request)
    {
        $all_main_warehouse = WarehouseModel::select("id", "type", 'warehouses')
            ->where('type', 5)
            ->get();
            
        // $categories = ItemPromotionCategoryModel::orderBy('category_name', 'asc')->get();

        if ($request->ajax()) {
            $data = ItemPromotionModel::orderBy('name', 'asc')->get();
            return datatables()->of($data)
                ->editColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-danger">Inactive</span>';
                    }
                })
                ->editColumn('category', function ($data) {
                    return $data->categoryBy?->name;
                })
                ->addColumn('name', function ($data) use ($all_main_warehouse) {
                    return view('item_promotion._option', ['data' => $data, 'warehouse' => $all_main_warehouse])->render();
                })
                ->rawColumns(['name', 'status'])
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'Create Material Promotion',
            'warehouse' => $all_main_warehouse,
            // 'categories' => $categories
        ];

        return view('item_promotion.index', $datas);
    }

    public function index_stock(Request $request)
    {
        $data = DB::table('item_promotion_stocks')
            ->join('item_promotions', 'item_promotion_stocks.id_item', '=', 'item_promotions.id')
            ->join('warehouses', 'item_promotion_stocks.id_warehouse', '=', 'warehouses.id')
            ->select('*', 'item_promotion_stocks.id as stock_id')
            ->orderBy('item_promotions.name', 'asc')
            ->orderBy('warehouses.warehouses', 'asc')
            ->get();
        // dd($data);
        if ($request->ajax()) {

            return datatables()->of($data)
                ->editColumn('warehouse', function ($data) {
                    return $data->warehouses;
                })
                ->editColumn('qty', function ($data) {
                    return number_format($data->qty);
                })
                ->addColumn('name', fn ($data) => view('item_promotion._option_stock', ['data' => $data])->render())
                ->rawColumns(['name'])
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'Material Promotion Stock',
            'warehouse' => WarehouseModel::select("id", "type", 'warehouses')
                ->where('type', 5)
                ->get(),
        ];

        return view('item_promotion.index_stock', $datas);
    }

    public function index_transaction(Request $request)
    {
        if ($request->ajax()) {
            // Ambil daftar gudang yang diizinkan oleh pengguna saat ini
            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            // Filter invoice berdasarkan gudang dan tanggal yang diminta
            $invoice = ItemPromotionTransactionModel::with('customerBy', 'warehouseBy', 'transactionDetailBy')
                ->whereIn('id_warehouse', $userWarehouseIds)
                ->where('isapproved', 1)
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    return $query->whereBetween('order_date', [$fromDate, $request->to_date]);
                }, function ($query) {
                    // Add this condition to use today's date as default
                    $today = date('Y-m-d');
                    return empty($query->from_date) ? $query->whereDate('order_date', $today) : $query;
                })
                ->latest()
                ->get();


            return datatables()->of($invoice)
                ->editColumn('order_number', fn ($invoice) => '<strong>' . $invoice->order_number . '</strong>')
                ->editColumn('total', fn ($invoice) => number_format($invoice->total))
                ->editColumn('order_date', fn ($invoice) => date('d F Y', strtotime($invoice->order_date)))
                ->editColumn('id_customer', function ($data) {
                    if (is_numeric($data->id_customer)) {
                        return $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust;
                    } else {
                        return $data->id_customer;
                    }
                })
                ->editColumn('created_by', fn ($invoice) => $invoice->createdBy->name)
                ->addIndexColumn()
                ->addColumn('action', fn ($invoice) => view('item_promotion._option_transaction', ['invoice' => $invoice, 'customer' => CustomerModel::latest()->get(), 'warehouses' => WarehouseModel::where('type', 5)->oldest('warehouses')->get()])->render())
                ->rawColumns(['order_number', 'action'])
                ->make(true);
        }

        // dd($warehouses);
        $data = [
            'title' => "Invoicing Material Promotion",
        ];

        return view('item_promotion.index_transaction', $data);
    }

    public function index_purchase(Request $request)
    {
        if ($request->ajax()) {
            // Ambil daftar gudang yang diizinkan oleh pengguna saat ini
            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            // Filter invoice berdasarkan gudang dan tanggal yang diminta
            $invoice = ItemPromotionPurchaseModel::with('supplierBy', 'warehouseBy', 'purchaseDetailBy')
                ->whereIn('warehouse_id', $userWarehouseIds)
                ->where('isapproved', 1)
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    return $query->whereBetween('order_date', [$request->from_date, $request->to_date]);
                }, function ($query) {
                    // Add this condition to use today's date as default
                    $today = date('Y-m-d');
                    return empty($query->from_date) ? $query->whereDate('order_date', $today) : $query;
                })
                ->latest()
                ->get();


            return datatables()->of($invoice)
                ->editColumn('order_number', fn ($invoice) => '<strong>' . $invoice->order_number . '</strong>')
                ->editColumn('total', fn ($invoice) => number_format($invoice->total))
                ->editColumn('order_date', fn ($invoice) => date('d F Y', strtotime($invoice->order_date)))
                ->editColumn('supplier_id', function ($data) {
                    return $data->supplierBy->name;
                })
                ->editColumn('created_by', fn ($invoice) => $invoice->createdBy->name)
                ->addIndexColumn()
                ->addColumn('action', fn ($invoice) => view('item_promotion._option_purchase', ['invoice' => $invoice, 'supplier' => ItemPromotionSupplierModel::latest()->get(), 'warehouses' => WarehouseModel::where('type', 5)->oldest('warehouses')->get()])->render())
                ->rawColumns(['order_number', 'action'])
                ->make(true);
        }

        // dd($warehouses);
        $data = [
            'title' => "Material Promotion Purchase",
        ];

        return view('item_promotion.index_purchase', $data);
    }

    public function index_supplier(Request $request)
    {
        $all_main_warehouse = WarehouseModel::select("id", "type", 'warehouses')
            ->where('type', 5)
            ->get();

        if ($request->ajax()) {
            $data = ItemPromotionSupplierModel::oldest('name')->get();
            return datatables()->of($data)

                ->editColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-danger">Inactive</span>';
                    }
                })
                ->addColumn('name', function ($data) use ($all_main_warehouse) {
                    return view('item_promotion._option_supplier', ['data' => $data])->render();
                })
                ->rawColumns(['name', 'status'])
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'Create Material Promotion Vendor'
        ];

        return view('item_promotion.index_supplier', $datas);
    }

    public function index_mutation(Request $request)
    {
        $area_user = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $all_warehouses = WarehouseModel::whereIn('id_area', array_column($area_user->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();

        if ($request->ajax()) {

            if (!empty($request->from_date)) {
                $mutation = ItemPromotionMutationModel::with('stockMutationDetailBy', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->whereBetween('mutation_date', array($request->from_date, $request->to_date))
                    ->whereIn('from', array_column($all_warehouses->toArray(), 'id'))
                    ->where('isapproved', 1)
                    ->when($request->area, function ($query) use ($request) {
                        $getWarehouse = WarehouseModel::where('id_area', $request->area)->get();
                        return $query->whereIn('from', array_column($getWarehouse->toArray(), 'id'));
                    })
                    ->latest()
                    ->get();
            } else {
                $mutation = ItemPromotionMutationModel::with('stockMutationDetailBy', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->whereIn('from', array_column($all_warehouses->toArray(), 'id'))
                    ->where('mutation_date', date('Y-m-d'))
                    ->where('isapproved', 1)
                    ->latest()
                    ->get();
            }
            return datatables()->of($mutation)
                ->editColumn('mutation_date', function ($data) {
                    return date('d F Y', strtotime($data->mutation_date));
                })
                ->editColumn('from', function ($data) {
                    return $data->fromWarehouse->warehouses;
                })
                ->editColumn('to', function ($data) {
                    return $data->toWarehouse->warehouses;
                })
                ->editColumn('created_by', function ($data) {
                    return $data->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($mutation) {
                    return view('item_promotion._option_mutation', compact('mutation'))->render();
                })
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $area = CustomerAreaModel::oldest('area_name')->get();
        $data = [
            'title' => 'All Material Promotions',
            'area' => $area,
            'area_user' => $area_user
        ];
        return view('item_promotion.index_mutation', $data);
    }

    public function approval_purchase()
    {
        $all_purchases = ItemPromotionPurchaseModel::where('isapproved', 0)
            ->whereIn('warehouse_id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
            ->latest()
            ->get();
        $all_suppliers = ItemPromotionSupplierModel::oldest('name')->get();
        $all_warehouses = Auth::user()->userWarehouseBy;

        $data = [
            "title" => "Material Promotion Purchase Approval",
            "purchases" => $all_purchases,
            "suppliers" => $all_suppliers,
            "warehouses" => $all_warehouses,
        ];

        return view('item_promotion.approval_purchase', $data);
    }

    public function approval_transaction()
    {
        $all_transactions = ItemPromotionTransactionModel::where('isapproved', 0)
            ->latest()
            ->get();
        $all_customers = CustomerModel::oldest('name_cust')->get();
        $all_warehouses = Auth::user()->userWarehouseBy;

        $data = [
            "title" => "Material Promotion Transaction Approval",
            "transactions" => $all_transactions,
            "customers" => $all_customers,
            "warehouses" => $all_warehouses,
        ];

        return view('item_promotion.approval_transaction', $data);
    }

    public function approval_mutation()
    {
        $area_user = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $all_warehouses = WarehouseModel::whereIn('id_area', array_column($area_user->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();

        $unapprove_mutation = ItemPromotionMutationModel::where('isapproved', 0)
            ->whereIn('from', array_column($all_warehouses->toArray(), 'id'))
            ->latest()
            ->get();

        $data = [
            'title' => 'Material Promotion Mutation Approval',
            'warehouses' => $all_warehouses,
            'mutations' => $unapprove_mutation
        ];

        return view('item_promotion.approval_mutation', $data);
    }

    public function approve_transaction(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            // validasi sebelum save
            $request->validate([
                "id_customer" => "required",
                "id_warehouse" => "required",
                "remark" => "required",
                "poFields.*.product_id" => "required",
                "poFields.*.qty" => "required"
            ]);

            $products_arr = [];
            foreach ($request->poFields as $check) {
                array_push($products_arr, $check['product_id']);
            }

            // dd($customer->name_cust);
            //Check Exceed Quantity
            $productQuantities = [];

            foreach ($request->poFields as $qty) {
                $productId = $qty['product_id'];
                $quantity = $qty['qty'];

                // Add or update the quantity for the product ID
                if (isset($productQuantities[$productId])) {
                    $productQuantities[$productId] += $quantity;
                } else {
                    $productQuantities[$productId] = $quantity;
                }
            }

            foreach ($productQuantities as $productId => $quantity) {
                $getStock = ItemPromotionStockModel::where('id_item', $productId)
                    ->where('id_warehouse', $request->id_warehouse)
                    ->first();

                if ($quantity > $getStock->qty) {
                    return redirect('/material-promotion/transaction/create')
                        ->with('error', 'Add Material Promotion Transaction Fail! The total quantity for products exceeds the stock');
                }
            }
            $model = ItemPromotionTransactionModel::where('id', $id)->first();

            $model->order_date = date('Y-m-d', strtotime($request->get('order_date')));;
            if (!is_numeric($request->id_customer)) {
                $model->id_customer = $request->id_customer;
                $model->address = $request->address_cust;
            } else {
                $cust = CustomerModel::where('id', $request->id_customer)->first();
                $model->id_customer = $request->id_customer;
                $model->address = $cust->address_cust . ', ' . $cust->district . ', ' . $cust->city . ', ' . $cust->province;
            }
            $model->id_warehouse = $request->id_warehouse;
            $model->remark = $request->get('remark');
            $model->isapproved = 1;
            $model->approved_by = Auth::user()->id;

            $saved = $model->save();
            // $model::creates();
            // save sales order details
            $total = 0;
            $message_duplicate = '';
            if ($saved) {
                foreach ($request->poFields as $key => $value) {
                    $product_exist = ItemPromotionTransactionDetailModel::where('id_transaction', $id)
                        ->where('id_item', $value['product_id'])->where('price', $value['price'])->first();
                    // dd($product_exist);
                    if ($product_exist != null) {
                        $harga_double = $product_exist->price;
                        $product_exist->price = $value['price'];
                        $product_exist->qty = $value['qty'];
                        $product_exist->save();
                    } else {
                        $new_product = new ItemPromotionTransactionDetailModel();
                        $new_product->id_transaction = $id;
                        $new_product->id_item = $value['product_id'];
                        $new_product->price = $value['price'];
                        $new_product->qty = $value['qty'];
                        $new_product->save();
                    }
                    $total = $total + ($value['price'] * $value['qty']);

                    //Cut Purchase Cost Stock
                    $getCostModel = ItemPromotionCostModel::where('item_id', $value['product_id'])
                        ->where('warehouse_id', $model->id_warehouse)
                        ->where('cost', $value['price'])
                        ->first();
                    if ($getCostModel != null) {
                        $getCostModel->qty = $getCostModel->qty - $value['qty'];
                        if ($getCostModel->qty >= 0) {
                            $getCostModel->save();
                        } else {
                            DB::rollBack();
                            return redirect('/material-promotion/transaction/approval')->with('error', 'Add Material Promotion Transaction Fail! The quantity of product exceed the stock of purchase price');
                        }
                    } else {
                        DB::rollBack();
                        return redirect('/material-promotion/transaction/approval')->with('error', 'Add Material Promotion Transaction Fail! We cannot found the cost by purchase');
                    }


                    //Cut Stock
                    $item_stock = ItemPromotionStockModel::where('id_item', $value['product_id'])
                        ->where('id_warehouse', $request->id_warehouse)->first();
                    $item_stock->qty = $item_stock->qty - $value['qty'];
                    $item_stock->save();
                }
            }
            $del = ItemPromotionTransactionDetailModel::where('id_transaction', $id)
                ->whereNotIn('id_item', $products_arr)->delete();

            $model->total = $total;

            //Save PDF
            $data = ItemPromotionTransactionModel::where('id', $model->id)->first();
            $warehouse = WarehouseModel::where('id', $model->id_warehouse)->first();

            if ($model->pdf_do != '') {
                $pdf = FacadePdf::loadView('item_promotion.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_do . '_' . $data->customerBy->name_cust);
            }
            $saved = $model->save();

            if (empty($message_duplicate) && $saved) {
                DB::commit();

                return redirect('material-promotion/transaction/approval')->with('success', 'Approve Material Promotion Transaction ' . $model->order_number . ' success');
            } elseif (!empty($message_duplicate) && $saved) {
                DB::commit();

                return redirect('material-promotion/transaction/approval')->with('info', 'Some of transaction add maybe Success! ' . $message_duplicate);
            } else {
                DB::commit();

                return redirect('material-promotion/transaction/approval')->with('error', 'Approve Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            
        }
    }

    public function reject_transaction($id)
    {
        try {
            DB::beginTransaction();
            $mp = ItemPromotionTransactionModel::where('id', $id)->first();
            $mp_detail = ItemPromotionTransactionDetailModel::where('id_transaction', $id)->get();
            foreach ($mp_detail as $value) {
                $value->delete();
            }
            $mp->delete();

            DB::commit();
            return redirect('/material-promotion/transaction/approval')->with('error', "Transaction Rejected");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/material-promotion/transaction/approval')->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function approve_purchase(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            "supplier_id" => "required|numeric",
            "warehouse_id" => "required|numeric",
            "order_date" => "required",
            "remark" => "required",
            "poFields.*.product_id" => "required|numeric",
            "poFields.*.price" => "required",
            "poFields.*.qty" => "required|numeric"
        ]);

        try {
            DB::beginTransaction();
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
            $model = ItemPromotionPurchaseModel::where('id', $id)->first();
            $model->order_date = date('Y-m-d', strtotime($request->get('order_date')));
            $model->supplier_id = $request->get('supplier_id');
            $model->warehouse_id = $request->get('warehouse_id');
            $model->remark = $request->get('remark');
            $model->isapproved = 1;
            $model->created_by = Auth::user()->id;
            $saved = $model->save();

            //Save POD Input and Total
            $total = 0;
            foreach ($request->poFields as $product) {
                $product_exist = ItemPromotionPurchaseDetailModel::where('purchase_id', $id)
                    ->where('item_id', $product['product_id'])->first();
                // dd($product_exist);
                if ($product_exist != null) {
                    $harga_double = $product_exist->price;
                    $product_exist->price = $product['price'];
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();
                } else {
                    $new_product = new ItemPromotionPurchaseDetailModel();
                    $new_product->purchase_id = $id;
                    $new_product->item_id = $product['product_id'];
                    $new_product->price = $product['price'];
                    $new_product->qty = $product['qty'];
                    $new_product->save();
                }
                $total = $total + ($product['price'] * $product['qty']);

                //Save to Item Promotion Cost
                $getCostModel = ItemPromotionCostModel::where('item_id', $product['product_id'])
                    ->where('warehouse_id', $model->warehouse_id)
                    ->where('cost', $product['price'])
                    ->first();
                if ($getCostModel == null) {
                    $new_cost = new ItemPromotionCostModel();
                    $new_cost->item_id = $product['product_id'];
                    $new_cost->cost = $product['price'];
                    $new_cost->qty = $product['qty'];
                    $new_cost->warehouse_id = $model->warehouse_id;
                    $new_cost->save();
                } else {
                    $getCostModel->qty = $getCostModel->qty + $product['qty'];
                    $getCostModel->save();
                }

                //Add Stock
                $item_stock = ItemPromotionStockModel::where('id_item', $product['product_id'])
                    ->where('id_warehouse', $request->warehouse_id)->first();
                $item_stock->qty = $item_stock->qty + $product['qty'];
                $item_stock->save();

                unset($product);
            }

            //Delete product that not in POD Input
            $del = ItemPromotionPurchaseDetailModel::where('purchase_id', $id)
                ->whereNotIn('item_id', $products_arr)->delete();

            //Save total
            $model->total = $total;
            $saved_model = $model->save();

            // $model::creats();
            if ($saved_model == true) {
                // $data = PurchaseOrderModel::where('order_number', $model->order_number)->first();
                // $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
                // $pdf = FacadePdf::loadView('purchase_orders.print_po', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $model->order_number . '.pdf');

                DB::commit();
                return redirect('/material-promotion/purchase/approval')->with('success', "Purchase Order Update Success");
            } else {
                DB::rollBack();
                return redirect('/material-promotion/purchase/approval')->with('error', "Purchase Order Update Fail! Please check again!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/material-promotion/purchase/approval')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function reject_purchase($id)
    {
        try {
            DB::beginTransaction();
            $mp = ItemPromotionPurchaseModel::where('id', $id)->first();
            $mp_detail = ItemPromotionPurchaseDetailModel::where('purchase_id', $id)->get();
            foreach ($mp_detail as $value) {
                $value->delete();
            }
            $mp->delete();

            DB::commit();
            return redirect('/material-promotion/purchase/approval')->with('error', "Purchase Order Rejected");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/material-promotion/purchase/approval')->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function approve_mutation(Request $request, $id)
    {

        try {
            DB::beginTransaction();
            // Validate Input
            $request->validate([
                "from" => "required|numeric",
                "to" => "required|numeric",
                "remark" => "required",
                "mutationFields.*.product_id" => "required|numeric",
                "mutationFields.*.qty" => "required|numeric"
            ]);

            $selected_mutation = ItemPromotionMutationModel::where('id', $id)->first();


            if ($request->mutationFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }

            //Check Duplicate and exceeds stock
            $products_arr = [];


            foreach ($request->mutationFields as $check) {
                array_push($products_arr, $check['product_id']);
            }

            //Check Exceed Quantity
            $productQuantities = [];

            foreach ($request->mutationFields as $qty) {
                $productId = $qty['product_id'];
                $quantity = $qty['qty'];

                // Add or update the quantity for the product ID
                if (isset($productQuantities[$productId])) {
                    $productQuantities[$productId] += $quantity;
                } else {
                    $productQuantities[$productId] = $quantity;
                }
            }

            foreach ($productQuantities as $productId => $quantity) {
                $getStock = ItemPromotionStockModel::where('id_item', $productId)
                    ->where('id_warehouse', $request->from)
                    ->first();

                if ($quantity > $getStock->qty) {
                    return redirect('/material-promotion/transaction/create')
                        ->with('error', 'Add Material Promotion Transaction Fail! The total quantity for products exceeds the stock');
                }
            }

            $selected_mutation->from = $request->get('from');
            $selected_mutation->to = $request->get('to');
            $selected_mutation->remark = $request->get('remark');
            $selected_mutation->isapproved = 1;
            $selected_mutation->save();

            foreach ($request->mutationFields as $item) {
                $selected_detail = ItemPromotionMutationDetailModel::where('mutation_id', $id)
                    ->where('item_id', $item['product_id'])
                    ->where('price', $item['price'])
                    ->first();

                if ($selected_detail == null) {
                    $selected_detail = new ItemPromotionMutationDetailModel();
                    $selected_detail->mutation_id = $id;
                    $selected_detail->item_id = $item['product_id'];
                    $selected_detail->price = $item['price'];
                    $selected_detail->qty = $item['qty'];
                    $selected_detail->save();
                } else {
                    $selected_detail->qty = $item['qty'];
                    $selected_detail->save();
                }

                //Change Cost Stock Warehouse From
                $getcostfrom = ItemPromotionCostModel::where('item_id', $item['product_id'])
                    ->where('cost', $item['price'])
                    ->where('warehouse_id', $selected_mutation->from)
                    ->first();

                if ($getcostfrom != null) {
                    $old_stock = $getcostfrom->qty;
                    $getcostfrom->qty = $getcostfrom->qty - $item['qty'];
                    if ($getcostfrom->qty >= 0) {
                        $getcostfrom->save();
                    } else {
                        DB::rollBack();
                        return redirect('/material-promotion/mutation/approval')->with('error', 'Add Material Promotion Mutation Fail! The quantity of product exceed the stock of purchase price');
                    }
                } else {
                    DB::rollBack();
                    return redirect('/material-promotion/mutation/approval')->with('error', 'Add Material Promotion Mutation Fail! We cannot found the cost by purchase');
                }

                //Change Cost Stock Warehouse To
                $getcostto = ItemPromotionCostModel::where('item_id', $item['product_id'])
                    ->where('cost', $item['price'])
                    ->where('warehouse_id', $selected_mutation->to)
                    ->first();

                if ($getcostto != null) {
                    $old_stock = $getcostto->qty;
                    $getcostto->qty = $getcostto->qty + $item['qty'];
                    $getcostto->save();
                } else {
                    $getcostto = new ItemPromotionCostModel();
                    $getcostto->item_id = $item['product_id'];
                    $getcostto->cost = $item['price'];
                    $getcostto->qty = $item['qty'];
                    $getcostto->warehouse_id = $selected_mutation->to;
                    $getcostto->save();
                }

                //Change Stock Warehouse From
                $getstockfrom = ItemPromotionStockModel::where('id_item', $item['product_id'])
                    ->where('id_warehouse', $selected_mutation->from)->first();
                $old_stock = $getstockfrom->qty;
                $getstockfrom->qty = $old_stock - $item['qty'];
                $getstockfrom->save();

                //Change Stock Warehouse To
                $getstockto = ItemPromotionStockModel::where('id_item', $item['product_id'])
                    ->where('id_warehouse', $selected_mutation->to)->first();
                if ($getstockto == null) {
                    $newstock = new ItemPromotionStockModel();
                    $newstock->id_item = $selected_detail->item_id;
                    $newstock->id_warehouse = $selected_mutation->to;
                    $newstock->qty = $item['qty'];
                    $newstock->save();
                } else {
                    $old_stock = $getstockto->qty;
                    $getstockto->qty = $old_stock + $item['qty'];
                    $getstockto->save();
                }
            }

            $del = ItemPromotionMutationDetailModel::where('mutation_id', $id)
                ->whereNotIn('item_id', $products_arr)->delete();
            DB::commit();

            return redirect('/material-promotion/mutation/approval')->with('success', 'Approve Mutation Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/material-promotion/mutation/approval')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function reject_mutation($id)
    {
        try {
            DB::beginTransaction();
            $mp = ItemPromotionMutationModel::where('id', $id)->first();
            $mp_detail = ItemPromotionMutationDetailModel::where('mutation_id', $id)->get();
            foreach ($mp_detail as $value) {
                $value->delete();
            }
            $mp->delete();

            DB::commit();
            return redirect('/material-promotion/mutation/approval')->with('error', "Mutation Rejected");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/material-promotion/mutation/approval')->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function create_transaction($id = null)
    {
        $title = 'Create Material Promotion Transaction';
        $ref = $id ?? ''; // memberikan nilai default jika $any kosong
        if ($id != null) {
            $direct_sales = DirectSalesModel::where('id', $id)->first();
        } else {
            $direct_sales = null;
        }
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->oldest('warehouses')->get();
        if ($user_warehouse->count() > 1) {
            $customer = CustomerModel::where('status', 1)->oldest('name_cust')->get();
        } else {
            $customer = CustomerModel::where('status', 1)->whereIn('area_cust_id', array_column($user_warehouse->toArray(), 'id_area'))->oldest('name_cust')->get();
        }
        $data = compact('title', 'customer', 'user_warehouse', 'direct_sales');
        return view('item_promotion.create_transaction', $data);
    }

    public function create_purchase()
    {
        $title = 'Create Material Promotion Purchase';
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->oldest('warehouses')->get();
        $suppliers = ItemPromotionSupplierModel::where('status', 1)->oldest('name')->get();
        $data = compact('title', 'suppliers', 'user_warehouse');
        return view('item_promotion.create_purchase', $data);
    }

    public function create_mutation()
    {
        $area_user = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $from_warehouses = WarehouseModel::where('type', '=', 5)
            ->whereIn('id_area', array_column($area_user->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();

        $to_warehouses = WarehouseModel::where('type', '=', 5)
            ->oldest('warehouses')
            ->get();

        $data = [
            'title' => 'Create Material Promotion Mutation ',
            'from_warehouse' => $from_warehouses,
            'to_warehouse' => $to_warehouses
        ];

        return view('item_promotion.create_mutation', $data);
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = new ItemPromotionModel();
        $data->name = $request->input('name');
        $data->category_id = $request->input('category');
        $data->description = $request->input('description');
        $file = $request->file('img');
        if ($file) {
            $name_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/material_promotion'), $name_file);
        } else {
            $name_file = "blank";
        }
        $data->img_ref = $name_file;
        $data->status = 1;
        if ($data->save()) {
            //Create Stock
            $selected_warehouses = $request->input('cek_warehouse');
            foreach ($selected_warehouses as $value) {
                $stock = new ItemPromotionStockModel();
                $stock->id_item = $data->id;
                $stock->id_warehouse = $value;
                $stock->qty = 0;
                $stock->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data has been saved.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal disimpan.'
            ]);
        }
    }

    public function store_transaction(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            // validasi sebelum save
            $request->validate([
                "customer_id" => "required|numeric",
                "warehouse_id" => "required|numeric",
                "remark" => "required",
                "promFields.*.product_id" => "required|numeric",
                "promFields.*.qty" => "required|numeric"
            ]);

            // dd($customer->name_cust);
            //Check Exceed Quantity
            $productQuantities = [];

            foreach ($request->promFields as $qty) {
                $productId = $qty['product_id'];
                $quantity = $qty['qty'];

                // Add or update the quantity for the product ID
                if (isset($productQuantities[$productId])) {
                    $productQuantities[$productId] += $quantity;
                } else {
                    $productQuantities[$productId] = $quantity;
                }
            }

            foreach ($productQuantities as $productId => $quantity) {
                $getStock = ItemPromotionStockModel::where('id_item', $productId)
                    ->where('id_warehouse', $request->warehouse_id)
                    ->first();

                if ($quantity > $getStock->qty) {
                    return redirect('/material-promotion/transaction/create')
                        ->with('error', 'Add Material Promotion Transaction Fail! The total quantity for products exceeds the stock');
                }
            }
            $model = new ItemPromotionTransactionModel();

            // query cek kode warehouse/area sales orders
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->warehouse_id)
                ->first();

            $lastRecord = ItemPromotionTransactionModel::where('id_warehouse', $request->warehouse_id)->latest()->first();

            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->order_date)->format('m');
                $currentMonth = Carbon::now()->format('m');

                if ($lastRecordMonth != $currentMonth) {
                    // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $model->id_sort = $cust_number_id;
                } else {
                    // Jika masih dalam bulan yang sama, increment $cust_number_id
                    $cust_number_id = intval($lastRecord->id_sort) + 1;
                    $model->id_sort = $cust_number_id;
                }
            } else {
                // Jika belum ada record sebelumnya, set $cust_number_id menjadi 1
                $cust_number_id = 1;
                $model->id_sort = $cust_number_id;
            }
            $length = 3;
            // $id = intval(SalesOrderModel::where('warehouse_id', $request->warehouse_id)->max('id_sort')) + 1;
            $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $order_number = 'IPPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
            //

            // save sales orders
            $model->order_number = $order_number;
            $model->order_date = Carbon::now()->format('Y-m-d');
            if ($request->customer_id == '-1') {
                $model->id_customer = $request->name_cust;
                $model->address = $request->address_cust;
            } else {
                $cust = CustomerModel::where('id', $request->customer_id)->first();
                $model->id_customer = $request->customer_id;
                $model->address = $cust->address_cust . ', ' . $cust->district . ', ' . $cust->city . ', ' . $cust->province;
            }
            $model->id_warehouse = $request->warehouse_id;
            $model->remark = $request->get('remark');
            $model->created_by = Auth::user()->id;
            if ($request->ds_number != '-') {
                $model->isapproved = 1;
            } else {
                $model->isapproved = 0;
            }
            $model->direct_number = $request->ds_number;

            $saved = $model->save();
            // $model::creates();
            // save sales order details
            $total = 0;
            $message_duplicate = '';
            if ($saved) {
                
                foreach ($request->promFields as $key => $value) {
                    $data = new ItemPromotionTransactionDetailModel();
                    $data->id_item = $value['product_id'];
                    $data->qty = $value['qty'];
                    $data->id_transaction = $model->id;

                    $harga = $value['price'];
                    $total = $total + ($harga * $value['qty']);

                    $data->price = $harga;
                    $data->save();

                    // Cut Purchase Cost Stock
                    if($request->ds_number != '-'){
                         $getCostModel = ItemPromotionCostModel::where('item_id', $value['product_id'])
                        ->where('warehouse_id', $model->id_warehouse)
                        ->where('cost', $value['price'])
                        ->first();
                        if ($getCostModel != null) {
                            $getCostModel->qty = $getCostModel->qty - $value['qty'];
                            if ($getCostModel->qty >= 0) {
                                $getCostModel->save();
                            } else {
                                DB::rollBack();
                                return redirect('/material-promotion/transaction/create')->with('error', 'Add Material Promotion Transaction Fail! The quantity of product exceed the stock of purchase price');
                            }
                        } else {
                            DB::rollBack();
                            return redirect('/material-promotion/transaction/create')->with('error', 'Add Material Promotion Transaction Fail! We cannot found the cost by purchase');
                        }
                    }
                   

                    if($request->ds_number != '-'){
                        //Cut Stock
                        $item_stock = ItemPromotionStockModel::where('id_item', $value['product_id'])
                            ->where('id_warehouse', $request->warehouse_id)->first();
                        $item_stock->qty = $item_stock->qty - $value['qty'];
                        $item_stock->save();
                    }
                    
                }
            }

            $model->total = $total;

            //Save PDF
            // $data = ItemPromotionTransactionModel::where('id', $model->id)->first();
            // $warehouse = WarehouseModel::where('id', $model->id_warehouse)->first();

            // if ($model->pdf_do != '') {
            //     $pdf = FacadePdf::loadView('item_promotion.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_do . '_' . $data->customerBy->name_cust);
            // }
            $saved = $model->save();

            if (empty($message_duplicate) && $saved) {
                DB::commit();
                if ($request->ds_number != '-') {
                    return redirect('material-promotion/transaction')->with('success', 'Create Material Promotion Transaction ' . $model->order_number . ' success');
                } else {
                    return redirect('material-promotion/transaction/create')->with('success', 'Create Material Promotion Transaction ' . $model->order_number . ' success');
                }
            } elseif (!empty($message_duplicate) && $saved) {
                DB::commit();

                return redirect('material-promotion/transaction/create')->with('info', 'Some of transaction add maybe Success! ' . $message_duplicate);
            } else {
                DB::commit();

                return redirect('material-promotion/transaction/create')->with('error', 'Add Transaction Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store_purchase(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            // validasi sebelum save
            $request->validate([
                "supplier_id" => "required|numeric",
                "warehouse_id" => "required|numeric",
                "remark" => "required",
                "promFields.*.product_id" => "required|numeric",
                "promFields.*.price" => "required",
                "promFields.*.qty" => "required|numeric"
            ]);

            $model = new ItemPromotionPurchaseModel();

            // query cek kode warehouse/area sales orders
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->warehouse_id)
                ->first();

            $lastRecord = ItemPromotionPurchaseModel::where('warehouse_id', $request->warehouse_id)->latest()->first();

            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->order_date)->format('m');
                $currentMonth = Carbon::now()->format('m');

                if ($lastRecordMonth != $currentMonth) {
                    // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $model->id_sort = $cust_number_id;
                } else {
                    // Jika masih dalam bulan yang sama, increment $cust_number_id
                    $cust_number_id = intval($lastRecord->id_sort) + 1;
                    $model->id_sort = $cust_number_id;
                }
            } else {
                // Jika belum ada record sebelumnya, set $cust_number_id menjadi 1
                $cust_number_id = 1;
                $model->id_sort = $cust_number_id;
            }
            $length = 3;
            // $id = intval(SalesOrderModel::where('warehouse_id', $request->warehouse_id)->max('id_sort')) + 1;
            $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $order_number = 'PMPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
            //

            // save sales orders
            $model->order_number = $order_number;
            $model->order_date = Carbon::now()->format('Y-m-d');
            $model->supplier_id = $request->supplier_id;
            $model->warehouse_id = $request->warehouse_id;
            $model->remark = $request->get('remark');
            $model->created_by = Auth::user()->id;
            $model->isapproved = 0;

            $saved = $model->save();
            // $model::creates();
            // save sales order details
            $total = 0;
            $message_duplicate = '';
            if ($saved) {
                foreach ($request->promFields as $key => $value) {
                    $data = new ItemPromotionPurchaseDetailModel();
                    $data->item_id = $value['product_id'];
                    $data->qty = $value['qty'];
                    $data->price = $value['price'];
                    $data->purchase_id = $model->id;
                    $check_duplicate = ItemPromotionPurchaseDetailModel::where('purchase_id', $data->purchase_id)
                        ->where('item_id', $data->item_id)
                        ->count();
                    if ($check_duplicate > 0) {
                        $message_duplicate = "You enter duplication of products. Please recheck the order you set.";
                        continue;
                    } else {
                        $total = $total + ($value['price'] * $value['qty']);
                        $data->save();
                    }
                }
            }

            $model->total = $total;

            //Save PDF
            // $data = ItemPromotionPurchaseModel::where('id', $model->id)->first();
            // $warehouse = WarehouseModel::where('id', $model->id_warehouse)->first();

            // if ($model->pdf_do != '') {
            //     $pdf = FacadePdf::loadView('item_promotion.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_do . '_' . $data->customerBy->name_cust);
            // }
            $saved = $model->save();

            if (empty($message_duplicate) && $saved) {
                DB::commit();

                return redirect('material-promotion/purchase/create')->with('success', 'Create Material Promotion Purchase ' . $model->order_number . ' success');
            } elseif (!empty($message_duplicate) && $saved) {
                DB::commit();

                return redirect('material-promotion/purchase/create')->with('info', 'Some of transaction add maybe Success! ' . $message_duplicate);
            } else {
                DB::commit();

                return redirect('material-promotion/purchase/create')->with('error', 'Add Purchase Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
            dd($e);
        }
    }

    public function store_supplier(Request $request)
    {
        // dd($request);
        $data = new ItemPromotionSupplierModel();
        $data->name = $request->input('name');
        $data->phone_number = $request->input('phone_number');
        $data->email = $request->input('email');
        $data->npwp = $request->input('npwp');
        $data->address = $request->input('address');
        $data->pic = $request->input('pic');
        $data->bank = $request->input('bank');
        $data->no_rek = $request->input('no_rek');
        $data->status = 1;

        if ($data->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been saved.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal disimpan.'
            ]);
        }
    }

    public function store_mutation(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            // Validate Input
            $request->validate([
                "from" => "required|numeric",
                "to" => "required|numeric",
                "remark" => "required",
                "mutationFields.*.product_id" => "required|numeric",
                "mutationFields.*.price" => "numeric",
                "mutationFields.*.qty" => "required|numeric"
            ]);

            if ($request->mutationFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }

            //Check Duplicate and exceeds stock
            $products_arr = [];

            foreach ($request->mutationFields as $check) {
                array_push($products_arr, $check['product_id']);
            }
            $productQuantities = [];

            foreach ($request->mutationFields as $qty) {
                $productId = $qty['product_id'];
                $quantity = $qty['qty'];

                // Add or update the quantity for the product ID
                if (isset($productQuantities[$productId])) {
                    $productQuantities[$productId] += $quantity;
                } else {
                    $productQuantities[$productId] = $quantity;
                }
            }

            foreach ($productQuantities as $productId => $quantity) {
                $getStock = ItemPromotionStockModel::where('id_item', $productId)
                    ->where('id_warehouse', $request->from)
                    ->first();

                if ($quantity > $getStock->qty) {
                    return redirect('/material-promotion/mutation/create')
                        ->with('error', 'Add Material Promotion Transaction Fail! The total quantity for products exceeds the stock');
                }
            }

            $model = new ItemPromotionMutationModel();

            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->get('from'))
                ->first();
            $lastRecord = ItemPromotionMutationModel::where('from', $request->from)->latest()->first();

            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->order_date)->format('m');
                $currentMonth = Carbon::now()->format('m');

                if ($lastRecordMonth != $currentMonth) {
                    // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $model->id_sort = $cust_number_id;
                } else {
                    // Jika masih dalam bulan yang sama, increment $cust_number_id
                    $cust_number_id = intval($lastRecord->id_sort) + 1;
                    $model->id_sort = $cust_number_id;
                }
            } else {
                // Jika belum ada record sebelumnya, set $cust_number_id menjadi 1
                $cust_number_id = 1;
                $model->id_sort = $cust_number_id;
            }
            $length = 3;
            // $id = intval(StockMutationModel::where('from', $request->from)->max('id')) + 1;
            $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $mutation_number = 'MMPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

            // dd($mutation_number);
            $model->mutation_number = $mutation_number;
            $model->mutation_date = Carbon::now()->format('Y-m-d');
            $model->from = $request->get('from');
            $model->to = $request->get('to');
            $model->remark = $request->get('remark');
            $model->created_by = Auth::user()->id;
            $model->isapproved = 0;
            $model->save();

            foreach ($request->mutationFields as $item) {
                $detail = new ItemPromotionMutationDetailModel();
                $detail->mutation_id = $model->id;
                $detail->item_id = $item['product_id'];
                $detail->price = $item['price'];
                $detail->qty = $item['qty'];
                $detail->save();
            }
            DB::commit();
            return redirect('/material-promotion/mutation/create')->with('success', 'Create Material Promotion Mutation Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/material-promotion/mutation/create')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = ItemPromotionModel::find($id);
        $data->name = $request->input('name');
        $data->category_id = $request->input('category');
        $data->description = $request->input('description');
        $data->cost = $request->input('cost');
        $data->status = $request->input('status');
        //process image
        $file = $request->file('img');
        if ($file) {
            $name_file = time() . '_' . $file->getClientOriginalName();
            $path = public_path('images/material_promotion/') . $data->img_ref;
            if (File::exists($path)) {
                File::delete($path);
            }
            $file->move(public_path('images/material_promotion/'), $name_file);
            $data->img_ref = $name_file;
        }
        if ($data->save()) {
            //Edit Stock
            $selected_warehouses = $request->input('cek_warehouse');
            foreach ($selected_warehouses as $value) {
                $check_stock = ItemPromotionStockModel::where('id_item', $id)->where('id_warehouse', $value)->first();
                if ($check_stock == null) {
                    $stock = new ItemPromotionStockModel();
                    $stock->id_item = $data->id;
                    $stock->id_warehouse = $value;
                    $stock->qty = 0;
                    $stock->save();
                }
            }

            $unselected_warehouses = ItemPromotionStockModel::where('id_item', $id)
                ->whereNotIn('id_warehouse', $selected_warehouses)->get();
            foreach ($unselected_warehouses as $value) {
                $value->delete();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data has been saved.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal disimpan.'
            ]);
        }
    }

    public function update_supplier(Request $request, $id)
    {
        $data = ItemPromotionSupplierModel::find($id);
        $data->name = $request->input('name');
        $data->phone_number = $request->input('phone_number');
        $data->email = $request->input('email');
        $data->npwp = $request->input('npwp');
        $data->address = $request->input('address');
        $data->pic = $request->input('pic');
        $data->bank = $request->input('bank');
        $data->no_rek = $request->input('no_rek');
        $data->status = $request->input('status');

        if ($data->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been saved.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal disimpan.'
            ]);
        }
    }

    public function update_transaction(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $model = ItemPromotionTransactionModel::where('id', $id)->firstOrFail();
            $customer_id = $request->get('customer_id');
            if (!is_numeric($customer_id)) {
                $model->id_customer = $customer_id;
                $model->address = $request->get('address');
            } else {
                $model->id_customer = $customer_id;
                $cust = CustomerModel::where('id', $customer_id)->first();
                $model->address = $cust->address_cust;
            }
            $model->remark = $request->get('remark');
            $model->id_warehouse = $request->get('warehouse_id');

            $saved_temp = $model->save();

            //Check Duplicate
            $products_arr = [];
            foreach ($request->editProduct as $check) {
                array_push($products_arr, $check['products_id']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return redirect('/material-promotion/transaction')->with('error', "You enter duplicate products! Please check again!");
            }

            //Restore data to before changed
            $po_restore = ItemPromotionTransactionDetailModel::where('id_transaction', $id)->get();

            foreach ($po_restore as $restore) {
                $stock = ItemPromotionStockModel::where('id_warehouse', $model->id_warehouse)
                    ->where('id_item', $restore->id_item)->first();
                $stock->qty = $stock->qty + $restore->qty;
                $stock->save();
            }

            //Save SOD Input and Count total
            $total = 0;
            $hpp = 0;
            foreach ($request->editProduct as $product) {
                $product_exist = ItemPromotionTransactionDetailModel::where('id_transaction', $id)
                    ->where('id_item', $product['products_id'])->first();

                $harga = ItemPromotionModel::where('id', $product['products_id'])->first();

                if ($product_exist != null) {
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();
                } else {
                    $new_product = new ItemPromotionTransactionDetailModel();
                    $new_product->id_transaction = $id;
                    $new_product->id_item = $product['products_id'];
                    $new_product->price = $harga->cost;
                    $new_product->qty = $product['qty'];
                    $new_product->save();
                }

                $total += (float) $total + ($harga->cost * $product['qty']);
            }

            //Delete product that not in SOD Input
            $del = ItemPromotionTransactionDetailModel::where('id_transaction', $id)
                ->whereNotIn('id_item', $products_arr)->delete();

            //Count PPN and Total
            $model->total = $total;

            //Verify

            //Potong Stock
            $selected_sod = ItemPromotionTransactionDetailModel::where('id_transaction', $id)->get();
            foreach ($selected_sod as $value) {

                $getStock = ItemPromotionStockModel::where('id_item', $value->id_item)
                    ->where('id_warehouse', $model->id_warehouse)
                    ->first();

                $old_stock = $getStock->qty;
                $getStock->qty = $old_stock - $value->qty;
                if ($getStock->stock < 0) {
                    DB::rollback();
                    return Redirect::back()->with('error', 'Verification Fail! Not enough stock. Please re-confirm to the customer.');
                } else {
                    $getStock->save();
                }
            }

            $saved_model = $model->save();
            if ($saved_model == true) {
                DB::commit();
                return redirect('/material-promotion/transaction')->with('info', "Invoice success update !");
            } else {
                DB::rollback();
                return redirect('/material-promotion/transaction')->with('error', "Invoice update Fail! Please check again!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update_stock(Request $request, $id)
    {
        $data = ItemPromotionStockModel::find($id);
        $data->qty = $request->input('qty');

        if ($data->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been saved.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal disimpan.'
            ]);
        }
    }

    public function delete($id)
    {
        $data = ItemPromotionModel::find($id);
        $path = public_path('images/material_promotion/') . $data->img_ref;
        if (File::exists($path)) {
            File::delete($path);
        }
        if ($data->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been deleted.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal dihapus.'
            ]);
        }
    }

    public function delete_supplier($id)
    {
        $data = ItemPromotionSupplierModel::find($id);
        if ($data->delete()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been deleted.'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal dihapus.'
            ]);
        }
    }

    public function printDeliveryOrder($id)
    {
        $data = ItemPromotionTransactionModel::find($id);
        $so_number = str_replace('IPPP', 'DIPP', $data->order_number);
        
        if(!is_numeric($data->id_customer)){
            $name = $data->id_customer;
        }else{
            $name = $data->customerBy->name_cust;
        }
        
        $data->pdf_do = $so_number . '_' . $name . '.pdf';
        
        $warehouse = WarehouseModel::where('id', $data->id_warehouse)->first();
        $pdf = FacadePdf::loadView('item_promotion.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $so_number . '_' . $name . '.pdf');
        return $pdf->stream($data->pdf_do);
    }
    
    public function printDeliveryOrderStruk($id)
    {
        $data = ItemPromotionTransactionModel::find($id);
        $so_number = str_replace('IPPP', 'DIPP', $data->order_number);
        if (is_numeric($data->id_customer)) {
            $name = $data->customerBy->name_cust;
        } else {
            $name = $data->id_customer;
        }

        $data->pdf_do = $so_number . '_' . $name . '.pdf';
        $warehouse = WarehouseModel::where('id', $data->id_warehouse)->first();
        $pdf = FacadePdf::loadView('item_promotion.delivery_order_struk', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $so_number . '_' . $name . '.pdf');
        return $pdf->stream($data->pdf_do);
    }

    public function print_mutation($id)
    {
        $data = ItemPromotionMutationModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->from)->first();
        $do_number = str_replace('MMPP', 'MMDOPP', $data->mutation_number);
        $data->pdf_do = $do_number . '.pdf';
        $data->save();

        $pdf = FacadePdf::loadView('item_promotion.do_mutation', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->mutation_number . '.pdf');

        return $pdf->stream($data->pdf_do);
    }

    public function print_purchase($id)
    {
        $data = ItemPromotionPurchaseModel::find($id);
        $so_number = str_replace('PMPP', 'DMPP', $data->order_number);
        $data->pdf_inv = $so_number . '_' . $data->supplierBy->name . '.pdf';
        $data->save();
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
        $pdf = FacadePdf::loadView('item_promotion.print_purchase', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $so_number . '_' . $data->supplierBy->name . '.pdf');
        return $pdf->stream($data->pdf_inv);
    }
    
     public function preview_transaction(Request $request)
    {
        if ($request->ajax()) {
            // Ambil daftar gudang yang diizinkan oleh pengguna saat ini
            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            // Filter invoice berdasarkan gudang dan tanggal yang diminta
            $invoice = ItemPromotionTransactionModel::with('customerBy', 'warehouseBy', 'transactionDetailBy')
                ->whereIn('id_warehouse', $userWarehouseIds)
                ->where('isapproved', 0)
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    return $query->whereBetween('order_date', [$fromDate, $request->to_date]);
                }, function ($query) {
                    // Add this condition to use today's date as default
                    $today = date('Y-m-d');
                    return empty($query->from_date) ? $query->whereDate('order_date', $today) : $query;
                })
                ->latest()
                ->get();


            return datatables()->of($invoice)
                ->editColumn('order_number', fn ($invoice) => '<strong>' . $invoice->order_number . '</strong>')
                ->editColumn('total', fn ($invoice) => number_format($invoice->total))
                ->editColumn('order_date', fn ($invoice) => date('d F Y', strtotime($invoice->order_date)))
                ->editColumn('id_customer', function ($data) {
                    if (is_numeric($data->id_customer)) {
                        return $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust;
                    } else {
                        return $data->id_customer;
                    }
                })
                ->editColumn('created_by', fn ($invoice) => $invoice->createdBy->name)
                ->addIndexColumn()
                ->addColumn('action', fn ($invoice) => view('item_promotion._option_transaction_preview', ['invoice' => $invoice, 'customer' => CustomerModel::latest()->get(), 'warehouses' => WarehouseModel::where('type', 5)->oldest('warehouses')->get()])->render())
                ->rawColumns(['order_number', 'action'])
                ->make(true);
        }

        // dd($warehouses);
        $data = [
            'title' => "Material Promotion Preview",
        ];

        return view('item_promotion.preview_transaction', $data);
    }

    public function preview_purchase(Request $request)
    {
        if ($request->ajax()) {
            // Ambil daftar gudang yang diizinkan oleh pengguna saat ini
            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            // Filter invoice berdasarkan gudang dan tanggal yang diminta
            $invoice = ItemPromotionPurchaseModel::with('supplierBy', 'warehouseBy', 'purchaseDetailBy')
                ->whereIn('warehouse_id', $userWarehouseIds)
                ->where('isapproved', 0)
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    return $query->whereBetween('order_date', [$request->from_date, $request->to_date]);
                }, function ($query) {
                    // Add this condition to use today's date as default
                    $today = date('Y-m-d');
                    return empty($query->from_date) ? $query->whereDate('order_date', $today) : $query;
                })
                ->latest()
                ->get();


            return datatables()->of($invoice)
                ->editColumn('order_number', fn ($invoice) => '<strong>' . $invoice->order_number . '</strong>')
                ->editColumn('total', fn ($invoice) => number_format($invoice->total))
                ->editColumn('order_date', fn ($invoice) => date('d F Y', strtotime($invoice->order_date)))
                ->editColumn('supplier_id', function ($data) {
                    return $data->supplierBy->name;
                })
                ->editColumn('created_by', fn ($invoice) => $invoice->createdBy->name)
                ->addIndexColumn()
                ->addColumn('action', fn ($invoice) => view('item_promotion._option_purchase_preview', ['invoice' => $invoice, 'supplier' => ItemPromotionSupplierModel::latest()->get(), 'warehouses' => WarehouseModel::where('type', 5)->oldest('warehouses')->get()])->render())
                ->rawColumns(['order_number', 'action'])
                ->make(true);
        }

        // dd($warehouses);
        $data = [
            'title' => "Material Promotion Purchase Preview",
        ];

        return view('item_promotion.preview_purchase', $data);
    }

    public function selectItem(Request $request)
    {
        try {
            $warehouse_id = request()->w;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = ItemPromotionStockModel::join('item_promotions', 'item_promotions.id', '=', 'item_promotion_stocks.id_item')
                    ->select('item_promotion_stocks.*', 'item_promotions.name AS nama_barang', 'item_promotions.id AS id_item', 'item_promotion_stocks.id AS id_stock')
                    ->Where('item_promotions.name', 'LIKE', "%$search%")
                    ->where('item_promotion_stocks.id_warehouse', $warehouse_id)
                    ->oldest('item_promotions.name')
                    ->get();
            } else {
                $product = ItemPromotionStockModel::join('item_promotions', 'item_promotions.id', '=', 'item_promotion_stocks.id_item')
                    ->select('item_promotion_stocks.*', 'item_promotions.name AS nama_barang', 'item_promotions.id AS id_item', 'item_promotion_stocks.id AS id_stock')
                    ->where('item_promotion_stocks.id_warehouse', $warehouse_id)
                    ->oldest('item_promotions.name')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function selectPrice(Request $request)
    {
        try {
            $warehouse_id = request()->w;
            $product_id = request()->p;
            $isReturn = request()->isreturn;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                if ($isReturn) {
                    $product = ItemPromotionCostModel::Where('cost', 'LIKE', "%$search%")
                        ->where('warehouse_id', $warehouse_id)
                        ->where('item_id', $product_id)
                        ->oldest('created_at')
                        ->get();
                } else {
                    $product = ItemPromotionCostModel::Where('cost', 'LIKE', "%$search%")
                        ->where('warehouse_id', $warehouse_id)
                        ->where('item_id', $product_id)
                        ->where('qty', '>', 0)
                        ->oldest('created_at')
                        ->get();
                }
            } else {
                if ($isReturn) {
                    $product = ItemPromotionCostModel::where('warehouse_id', $warehouse_id)
                        ->where('item_id', $product_id)
                        ->oldest('created_at')
                        ->get();
                } else {
                    $product = ItemPromotionCostModel::where('warehouse_id', $warehouse_id)
                        ->where('item_id', $product_id)
                        ->where('qty', '>', 0)
                        ->oldest('created_at')
                        ->get();
                }
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }



    public function selectByItem(Request $request)
    {
        try {
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = ItemPromotionModel::select('*', 'name AS nama_barang', 'id AS id_item')
                    ->Where('name', 'LIKE', "%$search%")
                    ->oldest('name')
                    ->get();
            } else {
                $product = ItemPromotionModel::select('*', 'name AS nama_barang', 'id AS id_item')
                    ->oldest('name')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function cekQty($product_id)
    {
        $warehouse_id = request()->w;

        $qty = ItemPromotionStockModel::where('id_warehouse', $warehouse_id)
            ->where('id_item', $product_id)
            ->first();

        return response()->json($qty);
    }

    public function SelectItemReturn(Request $request)
    {
        try {
            $so_id = request()->s;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = ItemPromotionTransactionDetailModel::join('item_promotions', 'item_promotions.id', '=', 'item_promotion_transaction_details.id_item')
                    ->select('item_promotions.name AS nama_barang', 'item_promotions.id AS id_item', 'item_promotion_transaction_details.*')
                    ->where('item_promotions.name', 'LIKE', "%$search%")
                    ->where('id_transaction', $so_id)
                    ->get();
            } else {
                $product = ItemPromotionTransactionDetailModel::join('item_promotions', 'item_promotions.id', '=', 'item_promotion_transaction_details.id_item')
                    ->select('item_promotions.name AS nama_barang', 'item_promotions.id AS id_item', 'item_promotion_transaction_details.*')
                    ->where('id_transaction', $so_id)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function SelectPurchaseItemReturn(Request $request)
    {
        try {
            $so_id = request()->s;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = ItemPromotionPurchaseDetailModel::join('item_promotions', 'item_promotions.id', '=', 'item_promotion_purchase_details.item_id')
                    ->select('item_promotions.name AS nama_barang', 'item_promotions.id AS id_item', 'item_promotion_purchase_details.*')
                    ->where('item_promotions.name', 'LIKE', "%$search%")
                    ->where('purchase_id', $so_id)
                    ->get();
            } else {
                $product = ItemPromotionPurchaseDetailModel::join('item_promotions', 'item_promotions.id', '=', 'item_promotion_purchase_details.item_id')
                    ->select('item_promotions.name AS nama_barang', 'item_promotions.id AS id_item', 'item_promotion_purchase_details.*')
                    ->where('purchase_id', $so_id)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function SelectQtyReturn(Request $request)
    {
        $so_id = request()->s;
        $product_id = request()->p;
        $price = request()->c;

        $getqty = ItemPromotionTransactionDetailModel::where('id_transaction', $so_id)->where('id_item', $product_id)->where('price', $price)->first();
        $_qty = $getqty->qty;
        $selected_return = ReturnItemPromotionModel::with('returnDetailsBy')->where('id_transaction', $so_id)->get();

        $return = 0;
        if ($selected_return != null) {
            foreach ($selected_return as $value) {
                $selected_detail = ReturnItemPromotionDetailModel::where('return_id', $value->id)->where('id_item', $product_id)->where('price', $price)->first();
                $return = $return + $selected_detail->qty;
            }
        }
        $data = [
            'qty' => $_qty,
            'return' => $return
        ];
        return response()->json($data);
    }

    public function SelectPurchaseQtyReturn(Request $request)
    {
        $so_id = request()->s;
        $product_id = request()->p;

        $getqty = ItemPromotionPurchaseDetailModel::where('purchase_id', $so_id)->where('item_id', $product_id)->first();
        $_qty = $getqty->qty;
        $selected_return = ReturnItemPromotionPurchaseModel::with('returnDetailsBy')->where('purchase_id', $so_id)->get();

        $return = 0;
        if ($selected_return != null) {
            foreach ($selected_return as $value) {
                $selected_detail = ReturnItemPromotionPurchaseDetailModel::where('return_id', $value->id)->where('item_id', $product_id)->first();
                $return = $return + $selected_detail->qty;
            }
        }
        $data = [
            'qty' => $_qty,
            'return' => $return
        ];
        return response()->json($data);
    }

    public function delete_transaction($id)
    {
        try {
            DB::beginTransaction();

            $sales_order = ItemPromotionTransactionModel::find($id);

            // restore stock
            $sales_order_detail = ItemPromotionTransactionDetailModel::where('id_transaction', $id)->get();

            foreach ($sales_order_detail as $key => $value) {
                $stock = ItemPromotionStockModel::where('id_item', $value->id_item)->where('id_warehouse', $sales_order->id_warehouse)->first();
                $stock->qty = $stock->qty + $value->qty;
                $stock->save();

                //Restore Purchase Cost Qty
                $getCostModel = ItemPromotionCostModel::where('item_id', $value->id_item)
                    ->where('warehouse_id', $sales_order->id_warehouse)
                    ->where('cost', $value->price)
                    ->first();
                $getCostModel->qty = $getCostModel->qty + $value->qty;
                $getCostModel->save();

                $value->delete();
            }

            $sales_order->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Material Promotion Transaction ' . $sales_order->order_number . ' has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/invoice')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
