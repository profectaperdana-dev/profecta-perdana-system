<?php

namespace App\Http\Controllers;

use App\Models\CustomerCategoriesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_customer_categories=CustomerCategoriesModel::all();
        $data=[
            'title' => "Customer Categories",
            'customer_categories' => $all_customer_categories
        ];

        return view('customer_categories.index', $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'category_name' => 'required'
        ]);
        $validated_data['created_by'] = Auth::user()->id;

        CustomerCategoriesModel::create($validated_data);

        return redirect('/customer_categories')->with('success', 'Customer Category Add Success');
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
        $validateData = $request->validate([
            'category_name' => 'required'
        ]);

        $customer_category = CustomerCategoriesModel::where('id', $id)->firstOrFail();
        $customer_category->category_name = $validateData['category_name'];
        $customer_category->save();

        return redirect('/customer_categories')->with('success', 'Customer Categories Edit Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CustomerCategoriesModel::where('id', $id)->delete();

        return redirect('/customer_categories')->with('success', 'Customer Categories Delete Success');
    }
}
