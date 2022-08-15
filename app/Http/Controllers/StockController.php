<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use phpDocumentor\Reflection\Types\Null_;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Gate::allows('isWarehouseKeeper')) {
            $id = Auth::user()->warehouseBy->id;
            $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
            $data = StockModel::where('warehouses_id', $id)->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::latest()->get();

            return view('stocks.index', compact('title', 'data', 'product', 'warehouse'));
        } else {
            $title = 'Data Stocks Product All Warehouse';
            $data = StockModel::latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::latest()->get();

            return view('stocks.index', compact('title', 'data', 'product', 'warehouse'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $id_product = $request->get('products_id');
        $id_warehouse = $request->get('warehouses_id');
        $cek = StockModel::where('products_id', $id_product)
            ->where('warehouses_id', $id_warehouse)
            ->count();
        if ($cek != Null) {
            return redirect('/stocks')->with('error', 'This product already in warehouse');
        } else {
            // dd($cek);
            // dd($request->all());
            $request->validate([
                'warehouses_id' => 'required',
                'products_id' => 'required',
                'stock' => 'required',

            ]);
            $model = new StockModel();
            $model->warehouses_id = $request->get('warehouses_id');
            $model->products_id = $request->get('products_id');
            $model->stock = $request->get('stock');
            $model->created_by = Auth::user()->id;
            $model->save();

            return redirect('/stocks')->with('success', 'Create Data Stocks Success');
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
        $model = StockModel::find($id);
        $model->delete();
        return redirect('/stocks')->with('error', 'Delete Data Stocks Success');
    }
}
