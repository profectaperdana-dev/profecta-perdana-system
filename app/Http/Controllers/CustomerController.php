<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use App\Models\CustomerCategoriesModel;
use App\Models\CustomerModel;
use Customers;
use Illuminate\Http\Request;

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
            'title' => 'Add Customers',
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
