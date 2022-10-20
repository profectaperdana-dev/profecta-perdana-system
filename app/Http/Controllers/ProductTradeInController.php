<?php

namespace App\Http\Controllers;

use App\Models\ProductTradeInModel;
use Illuminate\Http\Request;

class ProductTradeInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //view data
        $title = 'Product Trade In';
        $data = ProductTradeInModel::latest()->get();
        return view('product_trade_in.index', compact('title', 'data'));
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
        //validate
        $request->validate([
            'name_product_trade_in' => 'required',
            'price_product_trade_in' => 'required|numeric',
        ]);

        //insert data
        $model = new ProductTradeInModel();
        $model->name_product_trade_in = $request->name_product_trade_in;
        $model->price_product_trade_in = $request->price_product_trade_in;
        $saved = $model->save();
        if ($saved) {
            return redirect('trade_in')->with('success', 'Data has been saved');
        } else {
            return redirect('trade_in')->with('error', 'Data fail saved');
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
