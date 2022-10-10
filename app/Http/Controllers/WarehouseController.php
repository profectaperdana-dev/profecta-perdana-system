<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use App\Models\WarehouseModel;
use App\Models\WarehouseTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $warehouse_types = WarehouseTypeModel::all();
        $areas = CustomerAreaModel::latest()->get();
        return view('warehouses.index', compact('title', 'data', 'areas', 'warehouse_types'));
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

        ]);
        $model = new WarehouseModel();
        $model->warehouses = $request->get('warehouses');
        $model->type = $request->get('type');
        $model->alamat = $request->get('alamat');
        $model->id_area = $request->get('id_area');
        $model->latitude = '-';
        $model->longitude = '-';
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
        $request->validate([
            'warehouses_' => 'required',
            'alamat_' => 'required',
            'id_area_' => 'required',

        ]);
        $model =  WarehouseModel::find($id);
        $model->warehouses = $request->get('warehouses_');
        $model->type = $request->get('type_');
        $model->alamat = $request->get('alamat_');
        $model->id_area = $request->get('id_area_');

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
        if (!Gate::allows('level1')) {
            abort(403);
        }
        $model =  WarehouseModel::find($id);
        $model->delete();
        return redirect('/warehouses')->with('error', 'Delete Data Warehouses Success');
    }
}
