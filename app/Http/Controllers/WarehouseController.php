<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Data Warehouses';

        $data = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->latest('warehouses.id')
            ->get(['warehouses.*', 'customer_areas.area_name']);

        $areas = CustomerAreaModel::latest()->get();
        return view('warehouses.index', compact('title', 'data', 'areas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'warehouses' => 'required',
            'alamat' => 'required',
            'id_area' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        $model = new WarehouseModel();
        $model->warehouses = $request->get('warehouses');
        $model->alamat = $request->get('alamat');
        $model->id_area = $request->get('id_area');
        $model->latitude = $request->get('latitude');
        $model->longitude = $request->get('longitude');
        $model->status = 1;
        $model->created_by = Auth::user()->id;
        $model->save();

        return redirect('/warehouses')->with('success', 'Create Data Warehouses Success');
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
        $request->validate([
            'warehouses_' => 'required',
            'alamat_' => 'required',
            'id_area_' => 'required',
            'latitude_' => 'required',
            'longitude_' => 'required',
        ]);
        $model =  WarehouseModel::find($id);
        $model->warehouses = $request->get('warehouses_');
        $model->alamat = $request->get('alamat_');
        $model->id_area = $request->get('id_area_');
        $model->latitude = $request->get('latitude_');
        $model->longitude = $request->get('longitude_');
        $model->status = $request->get('status');
        $model->created_by = Auth::user()->id;
        $model->save();

        return redirect('/warehouses')->with('info', 'Change Data Warehouses Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $model =  WarehouseModel::find($id);
        $model->delete();
        return redirect('/warehouses')->with('error', 'Delete Data Warehouses Success');
    }
}
