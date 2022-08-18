<?php

namespace App\Http\Controllers;

use App\Events\RealTimeMessage;
use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_discounts = DiscountModel::with(['customerBy', 'productBy'])->latest()->get();
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
    public function select($customer_id, $product_id)
    {
        try {
            $discount = [];
            $discount = DiscountModel::select("id", "discount")
                ->where('customer_id', $customer_id)
                ->where('product_id', $product_id)
                ->first();

            return response()->json($discount);
        } catch (\Throwable $th) {
            dd($th);
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
        $validate_data = $request->validate([
            "customer_id" => "required|numeric",
            "discountFields.*.product_id" => "required|numeric",
            "discountFields.*.discount" => "required|numeric"
        ]);

        $message_duplicate = "";
        foreach ($request->discountFields as $key => $value) {
            $model = new DiscountModel();
            $model->customer_id = $request->get('customer_id');
            $model->product_id = $value['product_id'];
            $model->discount = $value['discount'];
            $model->created_by = Auth::user()->id;

            $check_duplicate = DiscountModel::where('customer_id', $model->customer_id)
                ->where('product_id', $model->product_id)
                ->count();

            if ($check_duplicate > 0) {
                $message_duplicate = "You enter duplication of products. Please recheck the discount you set.";
                continue;
            } else {
                $model->save();
            }
        }
        if (empty($message_duplicate)) {
            return redirect('/discounts')->with('success', 'Add Discount Success');
        } elseif (!empty($message_duplicate)) {
            return redirect('/discounts')->with('success', 'Some of Discounts add maybe Success! ' . $message_duplicate);
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
