<?php

namespace App\Http\Controllers;

use App\Models\SuppliersModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // abort(403, 'Unauthorized action.');
        $title = 'Data Suppliers';
        $warehouse = WarehouseModel::join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
            ->select('warehouses.*', 'warehouse_types.name')
            ->where('warehouse_types.name', 'SUPPLIER')->get();
        $data = SuppliersModel::latest()->get();

        return view('suppliers.index', compact('title', 'data', 'warehouse'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
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
            'nama_supplier' => 'required',
            'alamat_supplier' => 'required',
            'no_telepon_supplier' => 'required|numeric',
            'npwp_supplier' => 'required',
            'pic_supplier' => 'required',
            'email' => 'required',

        ]);
        $model = new SuppliersModel();
        $model->nama_supplier = $request->get('nama_supplier');
        $model->alamat_supplier = $request->get('alamat_supplier');
        $model->id_warehouse = $request->get('id_warehouse');
        $model->no_telepon_supplier = $request->get('no_telepon_supplier');
        $model->npwp_supplier = $request->get('npwp_supplier');
        $model->pic_supplier = $request->get('pic_supplier');
        $model->email = $request->get('email');
        $model->status_supplier = 1;
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect('/supliers')->with('success', 'Add Data Suppliers ' . $model->nama_supplier . ' Success');
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
            'nama_supplier_' => 'required',
            'alamat_supplier_' => 'required',
            'no_telepon_supplier_' => 'required|numeric',
            'npwp_supplier_' => 'required',
            'pic_supplier_' => 'required',
            'email_' => 'required',

        ]);

        $model = SuppliersModel::find($id);
        $model->nama_supplier = $request->get('nama_supplier_');
        $model->alamat_supplier = $request->get('alamat_supplier_');
        $model->id_warehouse = $request->get('id_warehouse_');
        $model->no_telepon_supplier = $request->get('no_telepon_supplier_');
        $model->npwp_supplier = $request->get('npwp_supplier_');
        $model->pic_supplier = $request->get('pic_supplier_');
        $model->status_supplier = $request->get('status_supplier');
        $model->email = $request->get('email_');

        $model->created_by = Auth::user()->id;
        $model->save();

        return redirect('/supliers')->with('info', 'Edit Data Suppliers ' . $model->nama_supplier . ' Success');
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
        $model = SuppliersModel::find($id);
        $model->delete();
        return redirect('/supliers')->with('error', 'Delete Data Suppliers ' . $model->nama_supplier . ' Success');
    }
}
