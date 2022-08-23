<?php

namespace App\Http\Controllers;

use App\Events\RealTimeMessage;
use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Models\SubTypeModel;
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

        $data = [
            'title' => 'Discount',
            'discounts' => $all_discounts,
            'customers' => $all_customers
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
        $issaved = false;
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
                $issaved = $model->save();
            }
        }
        if (empty($message_duplicate) && $issaved == true) {
            return redirect('/discounts')->with('success', 'Add Discount Success');
        } elseif (!empty($message_duplicate) && $issaved == true) {
            return redirect('/discounts')->with('success', 'Some of Discounts add maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/discounts')->with('error', 'Add Discount Fail! Maybe the discount already set or check your input again');
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
            "product_id_edit" => "required|numeric",
            "discount_edit" => "required|numeric"
        ]);

        $current_discount = DiscountModel::where('id', $id)->firstOrFail();
        $temp_product = $current_discount->product_id;
        $temp_discount = $current_discount->discount;
        $current_discount->product_id = $validate_data['product_id_edit'];
        $current_discount->discount = $validate_data['discount_edit'];
        $current_discount->save();

        $check = DiscountModel::where('customer_id', $current_discount->customer_id)
            ->where('product_id', $current_discount->product_id)
            ->count();
        if ($check > 1) {
            $current_discount->product_id = $temp_product;
            $current_discount->discount = $temp_discount;
            $current_discount->save();
            return redirect('/discounts')->with('error', 'The Product already exist!');
        }
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
