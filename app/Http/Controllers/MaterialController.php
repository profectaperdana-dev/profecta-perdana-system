<?php

namespace App\Http\Controllers;

use App\Models\MaterialModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Data Product Material';
        $data = MaterialModel::latest()->get();

        return view('materials.index', compact('title', 'data'));
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
            'nama_material' => 'required',

        ]);
        $model = new MaterialModel();
        $model->nama_material = $request->get('nama_material');
        $model->created_by = Auth::user()->id;
        $model->save();

        return redirect('/product_materials')->with('success', 'Add Data Product Material Success');
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
            'editnama_material' => 'required',

        ]);
        $model = MaterialModel::find($id);
        $model->nama_material = $request->get('editnama_material');
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect('/product_materials')->with('info', 'Changes Data Product Material Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = MaterialModel::find($id);
        $model->delete();
        return redirect('/product_materials')->with('error', 'Delete Data Product Material Success');
    }
}