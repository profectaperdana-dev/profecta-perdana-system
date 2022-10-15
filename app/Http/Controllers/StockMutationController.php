<?php

namespace App\Http\Controllers;

use App\Models\StockModel;
use App\Models\StockMutationDetailModel;
use App\Models\StockMutationModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class StockMutationController extends Controller
{
    public function index(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')
        ) {
            abort(403);
        }

        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $mutation = StockMutationModel::with('stockMutationDetailBy', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->whereBetween('mutation_date', array($request->from_date, $request->to_date))
                    ->where('isapprove', 1)
                    ->latest()
                    ->get();
            } else {
                $mutation = StockMutationModel::with('stockMutationDetailBy', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->where('isapprove', 1)
                    ->latest()
                    ->get();
            }
            return datatables()->of($mutation)
                ->editColumn('mutation_date', function ($data) {
                    return date('d-M-Y', strtotime($data->mutation_date));
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

        $data = [
            'title' => 'All Stock Mutations in Profecta Perdana'
        ];
        return view('stock_mutations.index', $data);
    }

    public function create()
    {
        $all_warehouses = WarehouseModel::all();

        $data = [
            'title' => 'Create Stock Mutation ',
            'warehouses' => $all_warehouses
        ];

        return view('stock_mutations.create', $data);
    }

    public function approval()
    {
        $unapprove_mutation = StockMutationModel::where('isapprove', 0)->latest()->get();
        $all_warehouses = WarehouseModel::all();

        $data = [
            'title' => 'Stock Mutation Need Approve',
            'warehouses' => $all_warehouses,
            'mutations' => $unapprove_mutation
        ];

        return view('stock_mutations.approval', $data);
    }

    public function approve_mutation(Request $request, $id)
    {
        // Validate Input
        $request->validate([
            "from" => "required|numeric",
            "to" => "required|numeric",
            "remark" => "required",
            "mutationFields.*.product_id" => "required|numeric",
            "mutationFields.*.qty" => "required|numeric"
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

        $selected_mutation = StockMutationModel::where('id', $id)->first();
        $selected_mutation->from = $request->get('from');
        $selected_mutation->to = $request->get('to');
        $selected_mutation->remark = $request->get('remark');
        $selected_mutation->isapprove = 1;
        $selected_mutation->save();

        foreach ($request->mutationFields as $item) {
            $selected_detail = StockMutationDetailModel::where('mutation_id', $id)->where('product_id', $item['product_id'])->first();
            if ($selected_detail == null) {
                $detail = new StockMutationDetailModel();
                $detail->mutation_id = $id;
                $detail->product_id = $item['product_id'];
                $detail->qty = $item['qty'];
                $detail->save();
            } else {
                $selected_detail->qty = $item['qty'];
                $selected_detail->save();
            }


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
        }

        return redirect('/stock_mutation/approval')->with('success', 'Approve Stock Mutation Success!');
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
                    ->get();
            } else {
                $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('stocks.warehouses_id', $from_warehouse)
                    ->latest()->get();
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

    public function store(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')
        ) {
            abort(403);
        }
        // Validate Input
        $request->validate([
            "from" => "required|numeric",
            "to" => "required|numeric",
            "remark" => "required",
            "mutationFields.*.product_id" => "required|numeric",
            "mutationFields.*.qty" => "required|numeric"
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
        $length = 3;
        $id = intval(StockMutationModel::where('mutation_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $mutation_number = 'SMPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

        $model->mutation_number = $mutation_number;
        $model->mutation_date = Carbon::now()->format('Y-m-d');
        $model->from = $request->get('from');
        $model->to = $request->get('to');
        $model->remark = $request->get('remark');
        $model->created_by = Auth::user()->id;
        $model->save();

        foreach ($request->mutationFields as $item) {
            $detail = new StockMutationDetailModel();
            $detail->mutation_id = $model->id;
            $detail->product_id = $item['product_id'];
            $detail->qty = $item['qty'];
            $detail->save();

            // //Change Stock Warehouse From
            // $getstockfrom = StockModel::where('products_id', $detail->product_id)->where('warehouses_id', $model->from)->first();
            // $old_stock = $getstockfrom->stock;
            // $getstockfrom->stock = $old_stock - $detail->qty;
            // $getstockfrom->save();

            // //Change Stock Warehouse To
            // $getstockto = StockModel::where('products_id', $detail->product_id)->where('warehouses_id', $model->to)->first();
            // if ($getstockto == null) {
            //     $newstock = new StockModel();
            //     $newstock->products_id = $detail->product_id;
            //     $newstock->warehouses_id = $model->to;
            //     $newstock->stock = $detail->qty;
            //     $newstock->save();
            // } else {
            //     $old_stock = $getstockto->stock;
            //     $getstockto->stock = $old_stock + $detail->qty;
            //     $getstockto->save();
            // }
        }

        return redirect('/stock_mutation/create')->with('success', 'Create Stock Mutation Success!');
    }

    public function update_mutation(Request $request, $id)
    {
        if (
            !Gate::allows('isSuperAdmin')
        ) {
            abort(403);
        }
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

        //Check Duplicate
        $products_arr = [];

        foreach ($request->mutationFields as $check) {
            array_push($products_arr, $check['product_id']);
            $getstock = StockModel::where('products_id', $check['product_id'])->where('warehouses_id', $selected_mutation->from)->first();
            if ($getstock != null) {
                if ($check['qty'] > $getstock->stock) {
                    return Redirect::back()->with('error', 'Mutation Edit Fail! The number of items exceeds the stock.');
                }
            }
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return Redirect::back()->with('error', 'Stock Mutation Edit Fail! You enter duplicate product.');
        }



        //Restore stock before changed
        $mutation_restore = StockMutationDetailModel::where('mutation_id', $id)->get();
        foreach ($mutation_restore as $restore) {
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
        }

        foreach ($request->mutationFields as $item) {
            $selected_detail = StockMutationDetailModel::where('mutation_id', $id)->where('product_id', $item['product_id'])->first();
            if ($selected_detail == null) {
                $detail = new StockMutationDetailModel();
                $detail->mutation_id = $id;
                $detail->product_id = $item['product_id'];
                $detail->qty = $item['qty'];
                $detail->save();
            } else {
                $selected_detail->qty = $item['qty'];
                $selected_detail->save();
            }


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
        }

        return redirect('/stock_mutation')->with('success', 'Edit Stock Mutation Success!');
    }

    public function print_do($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')
        ) {
            abort(403);
        }
        $data = StockMutationModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $do_number = str_replace('SMPP', 'SMDOPP', $data->mutation_number);
        $data->pdf_do = $do_number . '.pdf';
        $data->save();

        $pdf = Pdf::loadView('stock_mutations.print_do', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->mutation_number . '.pdf');

        return $pdf->download($data->pdf_do);
    }
}
