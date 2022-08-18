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
            $data = StockModel::with('warehouseStockBy', 'productBy')->latest()->get();
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

        $validate_data = $request->validate([
            "warehouses_id" => "required|numeric",
            "stockFields.*.product_id" => "required|numeric",
            "stockFields.*.stock" => "required|numeric"
        ]);

        $message_duplicate = "";
        $issaved = false;
        foreach ($request->stockFields as $key => $value) {
            $model = new StockModel();
            $model->warehouses_id = $request->get('warehouses_id');
            $model->products_id = $value['product_id'];
            $model->stock = $value['stock'];
            $model->created_by = Auth::user()->id;
            $cek = StockModel::where('products_id', $value['product_id'])
                ->where('warehouses_id', $request->get('warehouses_id'))
                ->count();

            if ($cek > 0) {
                $message_duplicate = "You enter duplication of products. Please recheck the Stock you set.";
                continue;
            } else {
                $issaved = $model->save();
            }
        }

        if (empty($message_duplicate) && $issaved == true) {
            return redirect('/stocks')->with('success', 'Create Stocks Success');
        } elseif (!empty($message_duplicate) && $issaved == true) {
            return redirect('/stocks')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/stocks')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
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
        $validate_data = $request->validate([
            "stock_" => "required|numeric",

        ]);

        $model = StockModel::where('id', $id)->firstOrFail();
        $model->stock = $validate_data['stock_'];
        $model->save();
        return redirect('/stocks')->with('success', 'Stocks Edit Success');
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
