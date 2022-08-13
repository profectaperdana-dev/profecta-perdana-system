<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_discounts = DiscountModel::latest()->get();
        $all_customers = CustomerModel::latest()->get();
        $all_products = ProductModel::latest()->get();

        $data = [
            'title' => 'Discount',
            'discounts' => $all_discounts,
            'customers' => $all_customers,
            'products' => $all_products
        ];

        return view('discounts.index', $data);
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
            "customer_id" => "required|numeric",
            "discountFields.*.product_id" => "required|numeric",
            "discountFields.*.discount" => "required|numeric"
        ]);

        foreach ($request->discountFields as $key => $value) {
            $model = new DiscountModel();
            $model->customer_id = $request->get('customer_id');
            $model->product_id = $value['product_id'];
            $model->discount = $value['discount'];
            $model->save();
        }

        if ($model->save()) {
            return redirect('/discounts')->with('success', 'Add Discount Success');
        } else {
            return redirect('/discounts')->with('error', 'Add Discount Fail! Please make sure you have filled all the input');
        }
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
            "customer_id_edit" => "required|numeric",
            "product_id_edit" => "required|numeric",
            "discount_edit" => "required|numeric"
        ]);

        $current_discount = DiscountModel::where('id', $id)->firstOrFail();
        $current_discount->customer_id = $validate_data['customer_id_edit'];
        $current_discount->product_id = $validate_data['product_id_edit'];
        $current_discount->discount = $validate_data['discount_edit'];
        $current_discount->save();

        return redirect('/discounts')->with('success', 'Discount Edit Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DiscountModel::where('id', $id)->delete();

        return redirect('/discounts')->with('success', 'Discount Delete Success');
    }
}
