<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use App\Models\Finance\Coa;
use App\Models\Finance\Journal;
use App\Models\Finance\JournalDetail;
use App\Models\ProductModel;
use App\Models\SecondProductModel;
use App\Models\StockModel;
use App\Models\StockMutationDetailModel;
use App\Models\StockMutationDotModel;
use App\Models\StockMutationModel;
use App\Models\TyreDotModel;
use App\Models\UserWarehouseModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use function App\Helpers\changeSaldoTambah;
use function App\Helpers\changeSaldoKurang;
use function App\Helpers\createJournal;
use function App\Helpers\createJournalDetail;

class StockMutationController extends Controller
{
    public function index(Request $request)
    {
        // get kode area
        // dd($request->all());
        $area_user = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $all_warehouses = WarehouseModel::whereIn('id_area', array_column($area_user->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();

        if ($request->ajax()) {

            if (!empty($request->from_date)) {
                $mutation = StockMutationModel::with('stockMutationDetailBy', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->whereBetween('mutation_date', array($request->from_date, $request->to_date))
                    ->where(function ($query) use ($all_warehouses) {
                        $query->whereIn('from', array_column($all_warehouses->toArray(), 'id'))
                              ->orWhereIn('to', array_column($all_warehouses->toArray(), 'id'));
                    })
                    ->where('isapprove', 1)
                    ->when($request->area, function ($query) use ($request) {
                        $getWarehouse = WarehouseModel::where('id_area', $request->area)->get();
                        return $query->whereIn('from', array_column($getWarehouse->toArray(), 'id'));
                    })
                    ->latest()
                    ->get();
            } else {
                $mutation = StockMutationModel::with('stockMutationDetailBy', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->where(function ($query) use ($all_warehouses) {
                        $query->whereIn('from', array_column($all_warehouses->toArray(), 'id'))
                              ->orWhereIn('to', array_column($all_warehouses->toArray(), 'id'));
                    })
                    ->where('mutation_date', date('Y-m-d'))
                    ->where('isapprove', 1)
                    ->latest()
                    ->get();
            }
            return datatables()->of($mutation)
                ->editColumn('mutation_date', function ($data) {
                    return date('d F Y', strtotime($data->mutation_date));
                })
                ->editColumn('from', function (StockMutationModel $stockMutationModel) {
                    return $stockMutationModel->fromWarehouse->warehouses;
                })
                ->editColumn('to', function (StockMutationModel $stockMutationModel) {
                    return $stockMutationModel->toWarehouse->warehouses;
                })
                ->editColumn('created_by', function (StockMutationModel $stockMutationModel) {
                    return $stockMutationModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($mutation) {
                    return view('stock_mutations._option', compact('mutation'))->render();
                })
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $area = CustomerAreaModel::oldest('area_name')->get();
        $data = [
            'title' => 'All Stock Mutations',
            'area' => $area,
            'area_user' => $area_user
        ];
        return view('stock_mutations.index', $data);
    }

    public function create()
    {
        $area_user = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $from_warehouses = WarehouseModel::where('type', '!=', 7)
            ->whereIn('id_area', array_column($area_user->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();

        $to_warehouses = WarehouseModel::where('type', '!=', 7)
            ->oldest('warehouses')
            ->get();

        $data = [
            'title' => 'Create Stock Mutation ',
            'from_warehouse' => $from_warehouses,
            'to_warehouse' => $to_warehouses
        ];

        return view('stock_mutations.create', $data);
    }

    public function second_create()
    {
        $area_user = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $from_warehouses = WarehouseModel::where('type',  7)
            ->whereIn('id_area', array_column($area_user->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();

        $to_warehouses = WarehouseModel::where('type', 7)
            ->oldest('warehouses')
            ->get();


        $data = [
            'title' => 'Create Second Product Stock Mutation ',
            'from_warehouse' => $from_warehouses,
            'to_warehouse' => $to_warehouses
        ];

        return view('stock_mutations.create_second', $data);
    }

    public function approval()
    {

        $area_user = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $all_warehouses = WarehouseModel::whereIn('id_area', array_column($area_user->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();

        $unapprove_mutation = StockMutationModel::where('isapprove', 0)
            ->whereIn('from', array_column($all_warehouses->toArray(), 'id'))
            ->latest()
            ->get();

        $data = [
            'title' => 'Stock Mutation Approval',
            'warehouses' => $all_warehouses,
            'mutations' => $unapprove_mutation
        ];

        return view('stock_mutations.approval', $data);
    }

    public function reject_mutation(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $mutation = StockMutationModel::where('id', $id)->first();
            foreach ($mutation->stockMutationDetailBy as $key => $value) {
                 // Change Stock Warehouse From
                if($mutation->isapprove == 1){
                    $getstockfrom = StockModel::where('products_id', $value->product_id)->where('warehouses_id', $mutation->from)->first();
                    $old_stock = $getstockfrom->stock;
                    $getstockfrom->stock = $old_stock + $value->qty;
                    $getstockfrom->save();
                } 
                
                $dot = $value->mutationDotBy()->delete();
                $value->delete();
            }
            // $detail = $mutation->stockMutationDetailBy()->delete();

            $mutation->delete();

            DB::commit();
            return redirect('/stock_mutation/approval')->with('error', 'Mutation reject success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/stock_mutation/approval')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function approve_mutation(Request $request, $id)
    {
        // dd('Under Maintenance');

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

            $selected_mutation = StockMutationModel::where('id', $id)->first();


            if ($request->mutationFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }

            //Check Duplicate and exceeds stock
            $products_arr = [];
            $dots_arr = [];

            if ($selected_mutation->product_type == 'Common') {
                foreach ($request->mutationFields as $check) {
                    array_push($products_arr, $check['product_id']);
                    
                    for ($i = 0; $i < sizeof($check) - 3; $i++) {
                        if(isset($check[$i]['Dot'])){
                            array_push($dots_arr, $check[$i]['Dot']);
                        }
                    }
                    $getstock = StockModel::where('products_id', $check['product_id'])->where('warehouses_id', $request->get('from'))->first();
                    if ($check['qty'] > $getstock->stock) {
                        return Redirect::back()->with('error', 'Mutation Fail! The number of items exceeds the stock.');
                    }
                }
            } else {
                foreach ($request->mutationFields as $check) {
                    array_push($products_arr, $check['product_id']);
                    $getstock = SecondProductModel::where('products_id', $check['product_id'])->where('warehouses_id', $request->get('from'))->first();
                    if ($check['qty'] > $getstock->qty) {
                        return Redirect::back()->with('error', 'Mutation Fail! The number of items exceeds the stock.');
                    }
                }
            }

            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return Redirect::back()->with('error', 'Mutation Fail! You enter duplicate product.');
            }

            $selected_mutation->from = $request->get('from');
            $selected_mutation->to = $request->get('to');
            $selected_mutation->remark = $request->get('remark');
            $selected_mutation->isreceived = 0;
            $selected_mutation->isapprove = 1;
            $selected_mutation->save();

            foreach ($request->mutationFields as $item) {
                $selected_detail = StockMutationDetailModel::where('mutation_id', $id)->where('product_id', $item['product_id'])->first();

                if ($selected_detail == null) {
                    $selected_detail = new StockMutationDetailModel();
                    $selected_detail->mutation_id = $id;
                    $selected_detail->product_id = $item['product_id'];
                    $selected_detail->qty = $item['qty'];
                    $selected_detail->note = $item['note'];
                    $selected_detail->save();
                } else {
                    $selected_detail->qty = $item['qty'];
                    $selected_detail->note = $item['note'];
                    $selected_detail->save();
                }


                if ($selected_mutation->product_type == 'Common') {

                    //Save DOT
                    $getProduct = ProductModel::where('id', $item['product_id'])->first();
                    if ($getProduct->materials->nama_material == "Tyre") {
                        for ($i = 0; $i < sizeof($item) - 3; $i++) {
                            $getDot = StockMutationDotModel::where('mutation_detail_id', $selected_detail->id)->where('dot', $item[$i]['Dot'])->first();
                            if ($getDot == null) {
                                $code = new StockMutationDotModel();
                                $code->mutation_detail_id = $selected_detail->id;
                                $code->dot = $item[$i]['Dot'];
                                $code->qty = $item[$i]['qtyDot'];
                                $code->save();
                            } else {
                                $getDot->qty = $item[$i]['qtyDot'];
                                $getDot->save();
                            }

                            //Change Stock DOT
                            // $dotfrom = TyreDotModel::where('id', $item[$i]['Dot'])->first();
                            // $dotfrom->qty = $dotfrom->qty - $item[$i]['qtyDot'];
                            // $dotfrom->save();

                            // $dotto = TyreDotModel::where('id_product', $item['product_id'])
                            //     ->where('id_warehouse', $selected_mutation->to)
                            //     ->where('dot', $dotfrom->dot)->first();
                            // if ($dotto == null) {
                            //     $new_dot = new TyreDotModel();
                            //     $new_dot->id_product = $item['product_id'];
                            //     $new_dot->id_warehouse = $selected_mutation->to;
                            //     $new_dot->dot = $dotfrom->dot;
                            //     $new_dot->qty = $item[$i]['qtyDot'];
                            //     $new_dot->save();
                            // } else {
                            //     $dotto->qty = $dotto->qty + $item[$i]['qtyDot'];
                            //     $dotto->save();
                            // }
                        }
                        $del = StockMutationDotModel::where('mutation_detail_id', $selected_detail->id)
                            ->whereNotIn('dot', $dots_arr)->delete();
                    }


                    // Change Stock Warehouse From
                    $getstockfrom = StockModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->from)->first();
                    $old_stock = $getstockfrom->stock;
                    $getstockfrom->stock = $old_stock - $item['qty'];
                    $getstockfrom->save();

                    // //Change Stock Warehouse To
                    // $getstockto = StockModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->to)->first();
                    // if ($getstockto == null) {
                    //     $newstock = new StockModel();
                    //     $newstock->products_id = $selected_detail->product_id;
                    //     $newstock->warehouses_id = $selected_mutation->to;
                    //     $newstock->stock = $item['qty'];
                    //     $newstock->save();
                    // } else {
                    //     $old_stock = $getstockto->stock;
                    //     $getstockto->stock = $old_stock + $item['qty'];
                    //     $getstockto->save();
                    // }
                } else {
                    //Change Stock Warehouse From
                    $getstockfrom = SecondProductModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->from)->first();
                    $old_stock = $getstockfrom->qty;
                    $getstockfrom->qty = $old_stock - $item['qty'];
                    $getstockfrom->save();

                    // //Change Stock Warehouse To
                    // $getstockto = SecondProductModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->to)->first();
                    // if ($getstockto == null) {
                    //     $newstock = new SecondProductModel();
                    //     $newstock->products_id = $selected_detail->product_id;
                    //     $newstock->warehouses_id = $selected_mutation->to;
                    //     $newstock->qty = $item['qty'];
                    //     $newstock->save();
                    // } else {
                    //     $old_stock = $getstockto->qty;
                    //     $getstockto->qty = $old_stock + $item['qty'];
                    //     $getstockto->save();
                    // }
                }
            }
            $del = StockMutationDetailModel::where('mutation_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();
            DB::commit();

            return redirect('/stock_mutation/approval')->with('success', 'Approve Stock Mutation Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/stock_mutation/approval')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function select()
    {
        try {
            $from_warehouse = request()->fw;
            $warehouse = WarehouseModel::where('id', $from_warehouse)->first();
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->where('stocks.warehouses_id', $from_warehouse)
                    ->orWhere('products.nama_barang', 'LIKE', "%$search%")
                    ->where('stocks.warehouses_id', $from_warehouse)
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->oldest('product_sub_types.type_name')
                    ->oldest('products.nama_barang')
                    ->get();
            } else {
                $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('stocks.warehouses_id', $from_warehouse)
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->oldest('product_sub_types.type_name')
                    ->oldest('products.nama_barang')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function checkMaterial()
    {
        try {
            $product = ProductModel::where('id', request()->p)->first();
            if ($product->materials->nama_material == "Tyre") {
                return response()->json(true);
            } else return response()->json(false);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function selectSecond()
    {
        try {
            $from_warehouse = request()->fw;
            $warehouse = WarehouseModel::where('id', $from_warehouse)->first();
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = SecondProductModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'second_products.products_id')
                    ->select('second_products.*', 'product_trade_ins.name_product_trade_in AS nama_barang', 'product_trade_ins.id AS id')
                    ->where('product_trade_ins.name_product_trade_in', 'LIKE', "%$search%")
                    ->where('second_products.warehouses_id', $from_warehouse)
                    ->oldest('product_trade_ins.name_product_trade_in')
                    ->get();
            } else {
                $product = SecondProductModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'second_products.products_id')
                    ->select('second_products.*', 'product_trade_ins.name_product_trade_in AS nama_barang', 'product_trade_ins.id AS id')
                    ->where('second_products.warehouses_id', $from_warehouse)
                    ->oldest('product_trade_ins.name_product_trade_in')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }


    public function getQtyDetail()
    {
        $from_warehouse = request()->fw;
        $product_id = request()->p;

        $getqty = StockModel::where('products_id', $product_id)->where('warehouses_id', $from_warehouse)->first();
        $_qty = $getqty->stock;

        return response()->json($_qty);
    }

    public function getSecondProductQty()
    {
        $from_warehouse = request()->fw;
        $product_id = request()->p;

        $getqty = SecondProductModel::where('products_id', $product_id)->where('warehouses_id', $from_warehouse)->first();
        $_qty = $getqty->qty;

        return response()->json($_qty);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            // Validate Input
            $request->validate([
                "from" => "required",
                "to" => "required",
                "remark" => "required",
                "mutationFields.*.product_id" => "required",
                "mutationFields.*.note" => "required",
                "mutationFields.*.qty" => "required"
            ]);

            if ($request->mutationFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }

            //Check Duplicate and exceeds stock
            $products_arr = [];

            foreach ($request->mutationFields as $check) {
                array_push($products_arr, $check['product_id']);
                $getstock = StockModel::where('products_id', $check['product_id'])->where('warehouses_id', $request->get('from'))->first();
                if ($check['qty'] > $getstock->stock) {
                    return Redirect::back()->with('error', 'Mutation Fail! The number of items exceeds the stock.');
                }
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return Redirect::back()->with('error', 'Mutation Fail! You enter duplicate product.');
            }

            $model = new StockMutationModel();

            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->get('from'))
                ->first();
            $lastRecord = StockMutationModel::where('from', $request->from)->latest()->first();

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
            $mutation_number = 'SMPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

            // dd($mutation_number);
            $model->mutation_number = $mutation_number;
            $model->mutation_date = Carbon::now()->format('Y-m-d');
            $model->from = $request->get('from');
            $model->to = $request->get('to');
            $model->remark = $request->get('remark');
            $model->created_by = Auth::user()->id;
            $model->product_type = 'Common';
            $model->isreceived = 0;
            $model->save();

            foreach ($request->mutationFields as $item) {
                $detail = new StockMutationDetailModel();
                $detail->mutation_id = $model->id;
                $detail->product_id = $item['product_id'];
                $detail->note = $item['note'];
                $detail->qty = $item['qty'];
                $detail->save();

                //Save DOT
                $getProduct = ProductModel::where('id', $item['product_id'])->first();
                if ($getProduct->materials->nama_material == "Tyre") {
                    for ($i = 0; $i < sizeof($item) - 3; $i++) {
                        $code = new StockMutationDotModel();
                        $code->mutation_detail_id = $detail->id;
                        $code->dot = $item[$i]['Dot'];
                        $code->qty = $item[$i]['qtyDot'];
                        $code->save();
                    }
                }
            }
            
            // dd('under maintenance');
            DB::commit();
            return redirect('/stock_mutation/create')->with('success', 'Create Stock Mutation Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/stock_mutation/create')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function receiving()
    {

        $area_user = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $user_warehouses = WarehouseModel::whereIn('id_area', array_column($area_user->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();
        
        $all_warehouses = WarehouseModel::oldest('warehouses')->get(); 

        $unapprove_mutation = StockMutationModel::where('isapprove', 1)
            ->where('isreceived', 0)
            ->whereIn('to', array_column($user_warehouses->toArray(), 'id'))
            ->latest()
            ->get();

        $data = [
            'title' => 'Stock Mutation Receiving',
            'warehouses' => $all_warehouses,
            'mutations' => $unapprove_mutation
        ];

        return view('stock_mutations.receiving', $data);
    }

    public function second_store(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            // Validate Input
            $request->validate([
                "from" => "required|numeric",
                "to" => "required|numeric",
                "remark" => "required",
                "mutationFieldss.*.product_id" => "required|numeric",
                "mutationFieldss.*.qty" => "required|numeric"
            ]);

            if ($request->mutationFieldss == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }

            //Check Duplicate and exceeds stock
            $products_arr = [];

            foreach ($request->mutationFieldss as $check) {
                array_push($products_arr, $check['product_id']);
                $getstock = SecondProductModel::where('products_id', $check['product_id'])->where('warehouses_id', $request->get('from'))->first();
                if ($check['qty'] > $getstock->qty) {
                    return Redirect::back()->with('error', 'Mutation Fail! The number of items exceeds the stock.');
                }
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return Redirect::back()->with('error', 'Mutation Fail! You enter duplicate product.');
            }

            $model = new StockMutationModel();

            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->get('from'))
                ->first();
            $length = 3;
            $id = intval(StockMutationModel::where('from', $request->from)->max('id')) + 1;
            $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $mutation_number = 'TMPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

            $model->mutation_number = $mutation_number;
            $model->mutation_date = Carbon::now()->format('Y-m-d');
            $model->from = $request->get('from');
            $model->to = $request->get('to');
            $model->remark = $request->get('remark');
            $model->created_by = Auth::user()->id;
            $model->product_type = 'Second';
            $model->save();

            foreach ($request->mutationFieldss as $item) {
                $detail = new StockMutationDetailModel();
                $detail->mutation_id = $model->id;
                $detail->product_id = $item['product_id'];
                $detail->qty = $item['qty'];
                $detail->save();
            }
            DB::commit();
            return redirect('/stock_mutation/second_create')->with('success', 'Create Stock Mutation Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/stock_mutation/second_create')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function receive_mutation(Request $request, $id)
    {
        // dd('Under Maintenance');

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

            $selected_mutation = StockMutationModel::where('id', $id)->first();


            if ($request->mutationFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }

            //Check Duplicate and exceeds stock
            $products_arr = [];
            $dots_arr = [];

            if ($selected_mutation->product_type == 'Common') {
                foreach ($request->mutationFields as $check) {
                    array_push($products_arr, $check['product_id']);

                    for ($i = 0; $i < sizeof($check) - 3; $i++) {
                        if (isset($check[$i]['Dot'])) {
                            array_push($dots_arr, $check[$i]['Dot']);
                        }
                    }
                    // $getstock = StockModel::where('products_id', $check['product_id'])->where('warehouses_id', $request->get('from'))->first();
                    // if ($check['qty'] > $getstock->stock) {
                    //     return Redirect::back()->with('error', 'Mutation Fail! The number of items exceeds the stock.');
                    // }
                }
            } else {
                foreach ($request->mutationFields as $check) {
                    array_push($products_arr, $check['product_id']);
                    // $getstock = SecondProductModel::where('products_id', $check['product_id'])->where('warehouses_id', $request->get('from'))->first();
                    // if ($check['qty'] > $getstock->qty) {
                    //     return Redirect::back()->with('error', 'Mutation Fail! The number of items exceeds the stock.');
                    // }
                }
            }

            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return Redirect::back()->with('error', 'Mutation Fail! You enter duplicate product.');
            }

            $selected_mutation->from = $request->get('from');
            $selected_mutation->to = $request->get('to');
            $selected_mutation->remark = $request->get('remark');
            $selected_mutation->isapprove = 1;
            $selected_mutation->isreceived = 1;
            $selected_mutation->save();

            foreach ($request->mutationFields as $item) {
                $selected_detail = StockMutationDetailModel::where('mutation_id', $id)->where('product_id', $item['product_id'])->first();

                if ($selected_detail == null) {
                    $selected_detail = new StockMutationDetailModel();
                    $selected_detail->mutation_id = $id;
                    $selected_detail->product_id = $item['product_id'];
                    $selected_detail->qty = $item['qty'];
                    $selected_detail->note = $item['note'];
                    $selected_detail->save();
                } else {
                    $selected_detail->qty = $item['qty'];
                    $selected_detail->note = $item['note'];
                    $selected_detail->save();
                }

                // $get_product_price = ProductModel::where('id', $selected_detail->product_id)->first();
                // $product_price = Crypt::decryptString($get_product_price->harga_beli);
                // $total = $product_price * $selected_detail->qty;


                if ($selected_mutation->product_type == 'Common') {

                    //Save DOT
                    $getProduct = ProductModel::where('id', $item['product_id'])->first();
                    if ($getProduct->materials->nama_material == "Tyre") {
                        for ($i = 0; $i < sizeof($item) - 3; $i++) {
                            $getDot = StockMutationDotModel::where('mutation_detail_id', $selected_detail->id)->where('dot', $item[$i]['Dot'])->first();
                            if ($getDot == null) {
                                $code = new StockMutationDotModel();
                                $code->mutation_detail_id = $selected_detail->id;
                                $code->dot = $item[$i]['Dot'];
                                $code->qty = $item[$i]['qtyDot'];
                                $code->save();
                            } else {
                                $getDot->qty = $item[$i]['qtyDot'];
                                $getDot->save();
                            }

                            //Change Stock DOT
                            $dotfrom = TyreDotModel::where('id', $item[$i]['Dot'])->first();
                            $dotfrom->qty = $dotfrom->qty - $item[$i]['qtyDot'];
                            $dotfrom->save();

                            $dotto = TyreDotModel::where('id_product', $item['product_id'])
                                ->where('id_warehouse', $selected_mutation->to)
                                ->where('dot', $dotfrom->dot)->first();
                            if ($dotto == null) {
                                $new_dot = new TyreDotModel();
                                $new_dot->id_product = $item['product_id'];
                                $new_dot->id_warehouse = $selected_mutation->to;
                                $new_dot->dot = $dotfrom->dot;
                                $new_dot->qty = $item[$i]['qtyDot'];
                                $new_dot->save();
                            } else {
                                $dotto->qty = $dotto->qty + $item[$i]['qtyDot'];
                                $dotto->save();
                            }
                        }
                        $del = StockMutationDotModel::where('mutation_detail_id', $selected_detail->id)
                            ->whereNotIn('dot', $dots_arr)->delete();
                    }


                    //Change Stock Warehouse From
                    // $getstockfrom = StockModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->from)->first();
                    // $old_stock = $getstockfrom->stock;
                    // $getstockfrom->stock = $old_stock - $item['qty'];
                    // $getstockfrom->save();

                    //Change Stock Warehouse To
                    $getstockto = StockModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->to)->first();
                    if ($getstockto == null) {
                        $newstock = new StockModel();
                        $newstock->products_id = $selected_detail->product_id;
                        $newstock->warehouses_id = $selected_mutation->to;
                        $newstock->stock = $item['qty'];
                        $newstock->save();
                    } else {
                        $old_stock = $getstockto->stock;
                        $getstockto->stock = $old_stock + $item['qty'];
                        $getstockto->save();
                    }
                } else {
                    //Change Stock Warehouse From
                    // $getstockfrom = SecondProductModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->from)->first();
                    // $old_stock = $getstockfrom->qty;
                    // $getstockfrom->qty = $old_stock - $item['qty'];
                    // $getstockfrom->save();

                    //Change Stock Warehouse To
                    $getstockto = SecondProductModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->to)->first();
                    if ($getstockto == null) {
                        $newstock = new SecondProductModel();
                        $newstock->products_id = $selected_detail->product_id;
                        $newstock->warehouses_id = $selected_mutation->to;
                        $newstock->qty = $item['qty'];
                        $newstock->save();
                    } else {
                        $old_stock = $getstockto->qty;
                        $getstockto->qty = $old_stock + $item['qty'];
                        $getstockto->save();
                    }
                }
            }
            $del = StockMutationDetailModel::where('mutation_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            // ** Mutation Journal ** //
            if ($selected_mutation->toWarehouse->type == 5 && $selected_mutation->fromWarehouse->type == 5) {

                $hpp_excl = 0;
                foreach ($request->mutationFields as $hpp_c) {
                    $getProduct = ProductModel::where('id', $hpp_c['product_id'])->first();
                    $hpp_excl = $hpp_excl + ($getProduct->hpp * $hpp_c['qty']);
                }

                // $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
                // $hpp_ppn = $hpp_excl * $current_ppn;
                // $hpp_incl = $hpp_excl + $hpp_ppn;

                // Pembelian Stok dari warehouse to
                $journal_to = createJournal(
                    Carbon::now()->format('Y-m-d'),
                    'Pembelian stok Ke ' . $selected_mutation->toWarehouse->warehouses . ' No.' . $selected_mutation->mutation_number,
                    $selected_mutation->to
                );

                // ** Jika Jurnal Berhasil Disimpan ** //
                if ($journal_to != "" && $journal_to != null && $journal_to != false) {

                    // ** COA Persediaan Barang Dagang ** //
                    createJournalDetail(
                        $journal_to,
                        '1-401',
                        $selected_mutation->mutation_number,
                        $hpp_excl,
                        0
                    );

                    // ** Cash Bank BCA ** //
                    createJournalDetail(
                        $journal_to,
                        '1-102',
                        $selected_mutation->mutation_number,
                        0,
                        $hpp_excl
                    );
                }

                //Penjualan Stok dari Warehouse from
                $journal_from = createJournal(
                    Carbon::now()->format('Y-m-d'),
                    'Penjualan stok dari ' . $selected_mutation->fromWarehouse->warehouses . ' No.' . $selected_mutation->mutation_number,
                    $selected_mutation->from
                );

                // ** Jika Jurnal Berhasil Disimpan ** //
                if ($journal_from != "" && $journal_from != null && $journal_from != false) {

                    // ** Cash Bank BCA ** //
                    createJournalDetail(
                        $journal_from,
                        '1-102',
                        $selected_mutation->mutation_number,
                        $hpp_excl,
                        0
                    );

                    // ** COA Pendapatan Penjualan ** //
                    createJournalDetail(
                        $journal_from,
                        '4-100',
                        $selected_mutation->mutation_number,
                        0,
                        $hpp_excl
                    );
                }

                //Persediaan berkurang untuk warehouse from
                $hpp_id = createJournal(
                    Carbon::now()->format('Y-m-d'),
                    'Persediaan berkurang No.' . $selected_mutation->mutation_number,
                    $selected_mutation->from
                );

                // ** Jika Jurnal Berhasil Disimpan ** //
                if ($hpp_id != "" && $hpp_id != null && $hpp_id != false) {

                    // ** HPP ** //
                    createJournalDetail(
                        $hpp_id,
                        '6-000',
                        $selected_mutation->mutation_number,
                        $hpp_excl,
                        0
                    );

                    // ** Persediaan barang ** //
                    createJournalDetail(
                        $hpp_id,
                        '1-401',
                        $selected_mutation->mutation_number,
                        0,
                        $hpp_excl
                    );
                }
                // ** Perubahan Saldo HPP From ** //
                $get_coa_p_masukan =  Coa::where('coa_code', '6-000')->first()->id;
                changeSaldoTambah($get_coa_p_masukan, $selected_mutation->from, $hpp_excl);

                // ** Perubahan Saldo Persediaan From ** //
                $get_coa_persediaan =  Coa::where('coa_code', '1-401')->first()->id;
                changeSaldoKurang($get_coa_persediaan,  $selected_mutation->from, $hpp_excl);

                // ** Perubahan Saldo Kas From ** //
                $get_coa_hutang_dagang =  Coa::where('coa_code', '1-102')->first()->id;
                changeSaldoTambah($get_coa_hutang_dagang, $selected_mutation->from, $hpp_excl);

                // ** Perubahan Saldo Pendapatan Penjualan ** //
                $get_coa_hutang_dagang =  Coa::where('coa_code', '4-100')->first()->id;
                changeSaldoKurang($get_coa_hutang_dagang, $selected_mutation->from, $hpp_excl);

                // ** Perubahan Saldo Persediaan  ** //
                $get_coa_hutang_dagang =  Coa::where('coa_code', '1-401')->first()->id;
                changeSaldoTambah($get_coa_hutang_dagang, $selected_mutation->to, $hpp_excl);

                // ** Perubahan Saldo Kas ** //
                $get_coa_hutang_dagang =  Coa::where('coa_code', '1-102')->first()->id;
                changeSaldoKurang($get_coa_hutang_dagang, $selected_mutation->to, $hpp_excl);

                $selected_mutation->journal_from = $journal_from;
                $selected_mutation->journal_to = $journal_to;
                $selected_mutation->hpp_id = $hpp_id;
                $selected_mutation->save();
            }

            DB::commit();

            return redirect('/stock_mutation/receiving')->with('success', 'Receive Stock Mutation Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/stock_mutation/receiving')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function update_mutation(Request $request, $id)
    {
        // dd($request->all());

        try {
            DB::beginTransaction();
            // Validate Input

            $request->validate([
                "mutationFields.*.product_id" => "required|numeric",
                "mutationFields.*.qty" => "required|numeric",
                "remark" => "required"
            ]);
            $selected_mutation = StockMutationModel::where('id', $id)->first();

            //Check Number of product
            if ($request->mutationFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }
            //Restore stock before changed
            $mutation_restore = StockMutationDetailModel::where('mutation_id', $id)->get();
            foreach ($mutation_restore as $restore) {
                if ($selected_mutation->product_type == 'Common') {
                    //From Warehouse
                    $stock_from = StockModel::where('warehouses_id', $selected_mutation->from)
                        ->where('products_id', $restore->product_id)->first();
                    $stock_from->stock = $stock_from->stock + $restore->qty;
                    $stock_from->save();

                    //To Warehouse
                    $stock_to = StockModel::where('warehouses_id', $selected_mutation->to)
                        ->where('products_id', $restore->product_id)->first();
                    $stock_to->stock = $stock_to->stock - $restore->qty;
                    $stock_to->save();
                } else {
                    //From Warehouse
                    $stock_from = SecondProductModel::where('warehouses_id', $selected_mutation->from)
                        ->where('products_id', $restore->product_id)->first();
                    $stock_from->qty = $stock_from->qty + $restore->qty;
                    $stock_from->save();

                    //To Warehouse
                    $stock_to = SecondProductModel::where('warehouses_id', $selected_mutation->to)
                        ->where('products_id', $restore->product_id)->first();
                    $stock_to->qty = $stock_to->qty - $restore->qty;
                    $stock_to->save();
                }
            }
            //Check Duplicate
            $products_arr = [];



            if ($selected_mutation->product_type == 'Common') {
                foreach ($request->mutationFields as $check) {
                    array_push($products_arr, $check['product_id']);
                    $getstock = StockModel::where('products_id', $check['product_id'])->where('warehouses_id', $selected_mutation->from)->first();
                    if ($getstock != null) {
                        if ($check['qty'] > $getstock->stock) {
                            return Redirect::back()->with('error', 'Mutation Edit Fail! The number of items exceeds the stock.');
                        }
                    }
                }
            } else {
                foreach ($request->mutationFields as $check) {
                    array_push($products_arr, $check['product_id']);
                    $getstock = SecondProductModel::where('products_id', $check['product_id'])->where('warehouses_id', $selected_mutation->from)->first();
                    if ($check['qty'] > $getstock->qty) {
                        return Redirect::back()->with('error', 'Mutation Fail! The number of items exceeds the stock.');
                    }
                }
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return Redirect::back()->with('error', 'Stock Mutation Edit Fail! You enter duplicate product.');
            }

            foreach ($request->mutationFields as $item) {
                $selected_detail = StockMutationDetailModel::where('mutation_id', $id)->where('product_id', $item['product_id'])->first();
                if ($selected_detail == null) {
                    $detail = new StockMutationDetailModel();
                    $detail->mutation_id = $id;
                    $detail->product_id = $item['product_id'];
                    $detail->qty = $item['qty'];
                    $detail->note = $item['note'];
                    $detail->save();
                } else {
                    $selected_detail->qty = $item['qty'];
                    $selected_detail->note = $item['note'];
                    $selected_detail->save();
                }
                //Delete product that not in SOD Input
                $del = StockMutationDetailModel::where('mutation_id', $id)
                    ->whereNotIn('product_id', $products_arr)->delete();

                if ($selected_mutation->product_type == 'Common') {
                    //Change Stock Warehouse From
                    $getstockfrom = StockModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->from)->first();
                    $old_stock = $getstockfrom->stock;
                    $getstockfrom->stock = $old_stock - $item['qty'];
                    $getstockfrom->save();

                    //Change Stock Warehouse To
                    $getstockto = StockModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->to)->first();
                    if ($getstockto == null) {
                        $newstock = new StockModel();
                        $newstock->products_id = $detail->product_id;
                        $newstock->warehouses_id = $selected_mutation->to;
                        $newstock->stock = $item['qty'];
                        $newstock->save();
                    } else {
                        $old_stock = $getstockto->stock;
                        $getstockto->stock = $old_stock + $item['qty'];
                        $getstockto->save();
                    }
                } else {
                    //Change Stock Warehouse From
                    $getstockfrom = SecondProductModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->from)->first();
                    $old_stock = $getstockfrom->qty;
                    $getstockfrom->qty = $old_stock - $item['qty'];
                    $getstockfrom->save();

                    //Change Stock Warehouse To
                    $getstockto = SecondProductModel::where('products_id', $item['product_id'])->where('warehouses_id', $selected_mutation->to)->first();
                    if ($getstockto == null) {
                        $newstock = new SecondProductModel();
                        $newstock->products_id = $detail->product_id;
                        $newstock->warehouses_id = $selected_mutation->to;
                        $newstock->qty = $item['qty'];
                        $newstock->save();
                    } else {
                        $old_stock = $getstockto->qty;
                        $getstockto->qty = $old_stock + $item['qty'];
                        $getstockto->save();
                    }
                }
            }
            
            if ($selected_mutation->toWarehouse->type == 5 && $selected_mutation->fromWarehouse->type == 5) {

                $hpp_excl = 0;
                foreach ($request->mutationFields as $hpp_c) {
                    $getProduct = ProductModel::where('id', $hpp_c['product_id'])->first();
                    $hpp_excl = $hpp_excl + ($getProduct->hpp * $hpp_c['qty']);
                }

                $journal_from = Journal::where('id', $selected_mutation->journal_from)->first();
                if ($journal_from) {

                    // Cash Bank BCA
                    $cash_from = JournalDetail::where('journal_id', $journal_from->id)->where('coa_code', '1-102')->first();
                    $cash_from->debit = $hpp_excl;
                    $cash_from->credit = 0;
                    $cash_from->save();

                    // akun pendapatan penjualan
                    $pendapatan_from = JournalDetail::where('journal_id', $journal_from->id)->where('coa_code', '4-100')->first();
                    $pendapatan_from->debit = 0;
                    $pendapatan_from->credit =  $hpp_excl;
                    $pendapatan_from->save();
                }

                $journal_to = Journal::where('id', $selected_mutation->journal_to)->first();
                if ($journal_to) {

                    // Persediaan Barang
                    $persediaan_to = JournalDetail::where('journal_id', $journal_to->id)->where('coa_code', '1-401')->first();
                    $persediaan_to->debit = $hpp_excl;
                    $persediaan_to->credit = 0;
                    $persediaan_to->save();

                    // Cash bank bca
                    $cash_to = JournalDetail::where('journal_id', $journal_to->id)->where('coa_code', '1-102')->first();
                    $cash_to->debit = 0;
                    $cash_to->credit =  $hpp_excl;
                    $cash_to->save();
                }

                $hpp_id = Journal::where('id', $selected_mutation->hpp_id)->first();
                if ($hpp_id) {

                    // HPP
                    $hpp_from = JournalDetail::where('journal_id', $hpp_id->id)->where('coa_code', '6-000')->first();
                    $hpp_from->debit = $hpp_excl;
                    $hpp_from->credit = 0;
                    $hpp_from->save();

                    // Persediaan Barang
                    $persediaan_from = JournalDetail::where('journal_id', $hpp_id->id)->where('coa_code', '1-401')->first();
                    $persediaan_from->debit = 0;
                    $persediaan_from->credit =  $hpp_excl;
                    $persediaan_from->save();
                }
            }
            DB::commit();
            return redirect('/stock_mutation')->with('success', 'Edit Stock Mutation Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/stock_mutation')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function print_do($id)
    {
        $data = StockMutationModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->from)->first();
        $do_number = str_replace('SMPP', 'SMDOPP', $data->mutation_number);
        $data->pdf_do = $do_number . '.pdf';
        $data->save();

        $pdf = Pdf::loadView('stock_mutations.print_do', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->mutation_number . '.pdf');

        return $pdf->stream($data->pdf_do);
    }

    public function delete_mutation($id)
    {
        try {
            DB::beginTransaction();
            $selected_mutation = StockMutationModel::where('id', $id)->first();
            
            if ($selected_mutation->toWarehouse->type == 5 && $selected_mutation->fromWarehouse->type == 5) {
                //Delete Journal
                $journal_from = Journal::where('id', $selected_mutation->journal_from)->first();
                if ($journal_from) {
                    $journal_from->jurnal_detail()->delete();
                    $journal_from->delete();
                }

                $journal_to = Journal::where('id', $selected_mutation->journal_to)->first();
                if ($journal_to) {
                    $journal_to->jurnal_detail()->delete();
                    $journal_to->delete();
                }

                $hpp_id = Journal::where('id', $selected_mutation->hpp_id)->first();
                if ($hpp_id) {
                    $hpp_id->jurnal_detail()->delete();
                    $hpp_id->delete();
                }
            }
            foreach ($selected_mutation->stockMutationDetailBy as $key => $value) {
                //Restore Stock
                if ($selected_mutation->product_type == 'Common') {

                    //Save DOT
                    $getProduct = ProductModel::where('id', $value->product_id)->first();
                    if ($getProduct->materials->nama_material == "Tyre") {
                        foreach ($value->mutationDotBy as $key => $code) {
                            $dotfrom = TyreDotModel::where('id', $code->dot)->first();
                            $dotfrom->qty = $dotfrom->qty + $code->qty;
                            $dotfrom->save();

                            $dotto = TyreDotModel::where('id_product', $value->product_id)
                                ->where('id_warehouse', $selected_mutation->to)
                                ->where('dot', $dotfrom->dot)->first();

                            $dotto->qty = $dotto->qty - $code->qty;
                            $dotto->save();

                            $code->delete();
                        }
                    }

                    if($selected_mutation->isreceived == 1){
                        //Change Stock Warehouse From
                        $getstockfrom = StockModel::where('products_id', $value->product_id)
                            ->where('warehouses_id', $selected_mutation->from)->first();
                        $old_stock = $getstockfrom->stock;
                        $getstockfrom->stock = $old_stock + $value->qty;
                        $getstockfrom->save();
    
                        //Change Stock Warehouse To
                        $getstockto = StockModel::where('products_id', $value->product_id)
                            ->where('warehouses_id', $selected_mutation->to)->first();
    
                        $old_stock = $getstockto->stock;
                        $getstockto->stock = $old_stock - $value->qty;
                        $getstockto->save();
                    }
                } else {
                    //Change Stock Warehouse From
                    $getstockfrom = SecondProductModel::where('products_id', $value->product_id)
                        ->where('warehouses_id', $selected_mutation->from)->first();
                    $old_stock = $getstockfrom->qty;
                    $getstockfrom->qty = $old_stock + $value->qty;
                    $getstockfrom->save();

                    //Change Stock Warehouse To
                    $getstockto = SecondProductModel::where('products_id', $value->product_id)
                        ->where('warehouses_id', $selected_mutation->to)->first();

                    $old_stock = $getstockto->qty;
                    $getstockto->qty = $old_stock - $value->qty;
                    $getstockto->save();
                }
                $value->delete();
            }
            $selected_mutation->delete();
            DB::commit();
            return redirect('/stock_mutation')->with('error', 'Delete Mutation Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/stock_mutation')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
