<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use App\Models\CustomerCategoriesModel;
use App\Models\CustomerModel;
use Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_customer = CustomerModel::join('customer_categories', 'customers.category_cust_id', '=', 'customer_categories.id')
            ->join('customer_areas', 'customers.area_cust_id', '=', 'customer_areas.id')
            ->select('customers.*', 'customer_areas.area_name', 'customer_categories.category_name')
            ->get();

        $data = [
            "title" => 'Customers',
            "customers" => $all_customer
        ];

        return view('customers.index', $data);
    }

    public function create()
    {
        $all_customer_categories = CustomerCategoriesModel::all();
        $all_customer_areas = CustomerAreaModel::all();
        $data = [
            'title' => 'Create Customers',
            'customer_categories' => $all_customer_categories,
            'customer_areas' => $all_customer_areas

        ];
        return view('customers.create', $data);
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
            'name_cust' => 'required',
            'phone_cust' => 'required',
            'address_cust' => 'required',
            'email_cust' => 'required|email:dns',
            'category_cust_id' => 'required|numeric',
            'area_cust_id' => 'required|numeric',
            'credit_limit' => 'required|numeric',
            'coordinate' => 'required',
            'status' => 'required|numeric',
            'reference_image' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        //create create by
        $validated_data['created_by'] = Auth::user()->id;

        //Create Customer Code
        $area_code = CustomerAreaModel::where('id', $validated_data['area_cust_id'])->firstOrFail()->area_code;
        $length = 4;
        $id = CustomerModel::max('id');
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $validated_data['code_cust'] = $area_code . $cust_number_id;

        //Process Image
        $file = $request->file('reference_image');
        $name_file = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/customers'), $name_file);
        $validated_data['reference_image'] = $name_file;


        CustomerModel::create($validated_data);

        return redirect('/customers')->with('success', 'Customer Add Success');
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
