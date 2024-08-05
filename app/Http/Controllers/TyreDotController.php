<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\TyreDotModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;

class TyreDotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Stock DOT';
        $data = StockModel::with('productBy', 'warehouseBy')
            // ->join('tyre_dots', function ($join) {
            //     $join->on('tyre_dots.id_product', '=', 'stocks.products_id')
            //         ->on('tyre_dots.id_warehouse', '=', 'stocks.warehouses_id');
            // })
            ->whereHas('productBy', function ($q) {
                $q->where('id_material', 18);
                $q->oldest('nama_barang');
            })
            ->groupBy('products_id', 'warehouses_id')
            ->get();

        $qty = [];
        $datas =  TyreDotModel::with('tyreBy', 'warehouseBy')

            ->join('stocks', function ($join) {
                $join->on('tyre_dots.id_product', '=', 'stocks.products_id')
                    ->on('tyre_dots.id_warehouse', '=', 'stocks.warehouses_id');
            })
            ->select('tyre_dots.id AS DOT', 'tyre_dots.id_product', 'tyre_dots.id_warehouse', 'tyre_dots.dot', 'tyre_dots.qty', 'stocks.stock')

            ->whereHas('tyreBy', function ($q) {
                $q->oldest('nama_barang');
            })
            ->get();
        // Now you can access the `qty` column from the `StockModel` table in the `$data` variable like this:
        foreach ($data as $item) {
            array_push($qty, $item->stock);
            // $qty = $item->stock;
            // dd($qty);
            // Do something with the $qty value
        }
        // dd($data);

        $product = ProductModel::with('sub_materials', 'sub_types')->whereHas('sub_materials', function ($query) {
            $query->oldest('nama_sub_material');
        })

            ->get();
        $warehouse = WarehouseModel::whereIn('type', [5])->latest()->get();
        $user_warehouse = WarehouseModel::where('type', 7)->whereIn('id_area', array_column($warehouse->toArray(), 'id_area'))->oldest('warehouses')->get();

        $datas = [
            'title' => $title,
            'data' => $data,
            'product' => $product,
            'warehouse' => $warehouse,
            'user_warehouse' => $user_warehouse,
            'qty' => $qty,
            'datas' => $datas,
        ];

        return view('tyre_dot.index', $datas);
    }
    public function deleteData($id, request $request)
    {

        // $rowId = $request->input('id');
        $row = TyreDotModel::find($id);

        if ($row) {
            $row->delete();

            return response()->json([
                'success' => true,
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }
    public function updateWeek(Request $request, $id)
    {
        $data_request = request()->week;
        $data = TyreDotModel::where('id', $id)->first();
        $data_dot = explode('/', $data->dot);
        $data->dot = $data_request . '/' . $data_dot[1];
        $data->save();
        return response()->json(true);
    }

    public function updateYear(Request $request, $id)
    {
        $data_request = request()->year;
        $data = TyreDotModel::where('id', $id)->first();
        $data_dot = explode('/', $data->dot);
        $data->dot = $data_dot[0] . '/' . $data_request;
        $data->save();
        return response()->json(true);
    }

    public function updateQty(Request $request, $id)
    {
        $data_request = request()->qty;
        $data = TyreDotModel::where('id', $id)->first();
        $data->qty = $data_request;
        $data->save();
        return response()->json(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveData(Request $request)
    {
        // dd($request->all());
        foreach ($request->field as $key => $value) {
            $data = new TyreDotModel();
            $data->id_product = $request->id_product;
            $data->id_warehouse = $request->id_warehouse;
            $data->qty = $value['qty'];
            $data->dot = $value['week'] . '/' . $value['year'];
            $data->save();
        }


        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function selectDot()
    {
        $product_id = request()->p;
        $warehouse_id = request()->w;

        $getDot = TyreDotModel::where('id_product', $product_id)->where('id_warehouse', $warehouse_id)->get();
        return response()->json($getDot);
    }

    public function checkExceed()
    {
        $dot_id = request()->d;
        $total_dot_picked = request()->qt;

        $getDOT = TyreDotModel::where('id', $dot_id)->first();
        if ($total_dot_picked > $getDOT->qty) {
            return response()->json(false);
        } else return response()->json(true);
    }


    public function select()
    {
        try {
            $warehouse_id = request()->w;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')
                    ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->select('stocks.*', 'product_materials.id', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->where('stocks.warehouses_id', $warehouse_id)
                    ->whereIn('products.shown', ['non-retail', 'all'])
                    ->where('product_materials.id', 18)
                    ->orWhere('products.nama_barang', 'LIKE', "%$search%")
                    ->where('stocks.warehouses_id', $warehouse_id)
                    ->whereIn('products.shown', ['non-retail', 'all'])
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->oldest('product_sub_types.type_name')
                    ->oldest('products.nama_barang')
                    ->get();
            } else {
                $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id')
                    ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->select('stocks.*', 'products.nama_barang AS nama_barang', 'product_materials.id', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('stocks.warehouses_id', $warehouse_id)
                    ->where('product_materials.id', 18)
                    ->whereIn('products.shown', ['non-retail', 'all'])
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

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // cek duplikat
        // $cek = TyreDotModel::where('id_product', $request->id_product)->where('id_warehouse', $request->id_warehouse)->first();
        // if ($cek) {
        //     return redirect()->back()->with('error', 'DOT for this item already exists, please go to the edit menu to change it');
        // }
        $message = '';
        foreach ($request->stockFields as $value) {
            $data  = new TyreDotModel();
            $data->id_warehouse = $request->id_warehouse;
            $data->id_product = $request->id_product;
            $week = $value['week'];
            $year = $value['year'];
            $data->dot = $week . '/' . $year;
            $data->qty = $value['qty'];

            // Check for duplicates
            $existingRecord = TyreDotModel::where('id_warehouse', $request->id_warehouse)
                ->where('id_product', $request->id_product)
                ->where('dot', $data->dot)
                ->first();
            if ($existingRecord) {
                $message = 'DOT for this item already exists, you entered the same DOT';
                continue; // skip this iteration and move to the next value
            }

            $data->save();
        }
        if ($message != '') {
            return redirect()->back()->with('info', $message);
        } else {
            return redirect()->back()->with('success', 'Create DOT Stock Has Been Success');
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
        //
    }
}
