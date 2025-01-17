<?php

namespace App\Http\Controllers;

use App\Models\WarehouseTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //* Get all warehouse types
        $title = 'Warehouse Type';
        $data = WarehouseTypeModel::all();
        return view('warehouse_type.index', compact('data', 'title'));
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
            'name' => 'required',
            'detail' => 'required',
        ]);
        try {
            DB::beginTransaction();
            // * Insert data
            $model = new WarehouseTypeModel();
            $model->name = $request->name;
            $model->detail = $request->detail;
            $saved = $model->save();
            if ($saved) {

                DB::commit();
                return redirect()->route('warehouse_types.index')->with('success', 'Warehouse Type created successfully.');
            } else {

                DB::rollback();
                return redirect()->route('warehouse_types.index')->with('error', 'Warehouse Type created failed.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('warehouse_types.index')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        $request->validate([
            'name_' => 'required',
            'detail_' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // * Update data
            $model = WarehouseTypeModel::find($id);
            $model->name = $request->name_;
            $model->detail = $request->detail_;
            $saved = $model->save();
            if ($saved) {

                DB::commit();
                return redirect()->route('warehouse_types.index')->with('success', 'Warehouse Type updated successfully.');
            } else {

                DB::rollback();
                return redirect()->route('warehouse_types.index')->with('error', 'Warehouse Type updated failed.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('warehouse_types.index')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            // * Delete data
            $model = WarehouseTypeModel::find($id);
            $deleted = $model->delete();
            if ($deleted) {

                DB::commit();
                return redirect()->route('warehouse_types.index')->with('success', 'Warehouse Type deleted successfully.');
            } else {

                DB::rollback();
                return redirect()->route('warehouse_types.index')->with('error', 'Warehouse Type deleted failed.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('warehouse_types.index')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
