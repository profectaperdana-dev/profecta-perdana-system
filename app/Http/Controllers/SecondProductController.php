<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\SecondProductModel;
use App\Models\StockModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use phpDocumentor\Reflection\Types\Null_;

class SecondProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (Gate::allows('isWarehouseKeeper')) {
        //     $id = Auth::user()->warehouseBy->id;
        //     $title = 'Data Second Product ' . Auth::user()->warehouseBy->warehouses;
        //     $data = StockModel::where('warehouses_id', $id)->get();
        //     $product = ProductModel::latest()->get();
        //     $warehouse = WarehouseModel::latest()->get();

        //     return view('second_product.index', compact('title', 'data', 'product', 'warehouse'));
        // } else {
        $data = SecondProductModel::with(['warehouseStockBy'], ['productTradeBy'])->latest()->get();
        $product = ProductModel::latest()->get();
        $warehouse_user = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();
        $warehouse = WarehouseModel::where('type', 7)->whereIn('id_area', array_column($warehouse_user->toArray(), 'id_area'))->get();

        $title = 'Stock Second Product';

        return view('second_product.index', compact('title', 'data', 'product', 'warehouse'));
        // }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $validate_data = $request->validate([
            "warehouses_id" => "required|numeric",
            "stockFields.*.product_id" => "required|numeric",
            "stockFields.*.stock" => "required|numeric"
        ]);

        $message_duplicate = "";
        $issaved = false;
        foreach ($request->stockFields as $key => $value) {
            $model = new SecondProductModel();
            $model->warehouses_id = $request->get('warehouses_id');
            $model->products_id = $value['product_id'];
            $model->qty = $value['stock'];
            $model->created_by = Auth::user()->id;
            $cek = SecondProductModel::where('products_id', $value['product_id'])
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
            return redirect('/second_product')->with('success', 'Create Second Product Success');
        } elseif (!empty($message_duplicate) && $issaved == true) {
            return redirect('/second_product')->with('success', 'Some of Second Product add maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/second_product')->with('error', 'Create Second Product Fail! Please make sure you have filled all the input');
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
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        $validate_data = $request->validate([
            "stock_" => "required|numeric",

        ]);

        $model = SecondProductModel::where('id', $id)->firstOrFail();
        $model->qty = $validate_data['stock_'];
        $model->save();
        return redirect('/second_product')->with('success', 'Stocks Edit Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        $model = SecondProductModel::find($id);
        $model->delete();
        return redirect('/second_product')->with('error', 'Delete Data Stocks Success');
    }
}
