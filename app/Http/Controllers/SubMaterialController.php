<?php

namespace App\Http\Controllers;

use App\Models\SubMaterialModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Data Product Sub Material';
        $data = SubMaterialModel::latest()->get();

        return view('submaterials.index', compact('title', 'data'));
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
        $request->validate([
            'nama_sub_material' => 'required',

        ]);
        $model = new SubMaterialModel();
        $model->nama_sub_material = $request->get('nama_sub_material');
        $model->created_by = Auth::user()->id;
        $model->save();

        return redirect('/product_sub_materials')->with('success', 'Add Data Product Sub Material Success');
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
            'editnama_submaterial' => 'required',

        ]);
        $model = SubMaterialModel::find($id);
        $model->nama_sub_material = $request->get('editnama_submaterial');
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect('/product_sub_materials')->with('info', 'Changes Data Product Sub Material Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = SubMaterialModel::find($id);
        $model->delete();
        return redirect('/product_sub_materials')->with('error', 'Delete Data Product Sub Material Success');
    }
}
