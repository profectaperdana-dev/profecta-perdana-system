<?php

namespace App\Http\Controllers;

use App\Models\UomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Data Unit of Measurement';
        $data = UomModel::latest()->get();

        return view('uoms.index', compact('title', 'data'));
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
            'satuan' => 'required',

        ]);
        $model = new UomModel();
        $model->satuan = $request->get('satuan');
        $model->created_by = Auth::user()->id;
        $model->save();

        return redirect('/product_uoms')->with('success', 'Add Data Unit of Measurement Success');
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
            'editSatuan' => 'required',

        ]);
        $model = UomModel::find($id);
        $model->satuan = $request->get('editSatuan');
        $model->created_by = Auth::user()->id;
        $model->save();

        return redirect('/product_uoms')->with('info', 'Changes Data Unit of Measurement Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = UomModel::find($id);
        $model->delete();
        return redirect('/product_uoms')->with('error', 'Delete Data Unit of Measurement Success');
    }
}
