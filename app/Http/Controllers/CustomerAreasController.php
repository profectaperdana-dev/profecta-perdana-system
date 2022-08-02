<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAreasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_customer_areas = CustomerAreaModel::all();
        $data=[
            'title' => 'Customer Area',
            'customer_areas' => $all_customer_areas
        ];

        return view('customer_areas.index', $data);
        
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
        $validated_data = $request->validate([
            'area_name' => 'required'
        ]);
        $validated_data['created_by'] = Auth::user()->id;

        CustomerAreaModel::create($validated_data);

        return redirect('/customer_areas')->with('success', 'Customer Area Add Success');
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
            'area_name' => 'required'
        ]);

        $customer_area = CustomerAreaModel::where('id', $id)->firstOrFail();
        $customer_area->area_name = $validateData['area_name'];
        $customer_area->save();

        return redirect('/customer_areas')->with('success', 'Customer Areas Edit Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CustomerAreaModel::where('id', $id)->delete();

        return redirect('/customer_areas')->with('success', 'Customer Areas Delete Success');
    }
}
