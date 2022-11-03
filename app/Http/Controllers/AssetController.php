<?php

namespace App\Http\Controllers;

use App\Models\AssetModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_assets = AssetModel::with('createdBy')->latest()->get();
        $data = [
            'title' => 'Asset Data',
            'assets' => $all_assets
        ];

        return view('assets.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Create Asset',
        ];
        return view('assets.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //* validate
        $request->validate([
            "asset_code" => "required|unique:assets",
            "asset_name" => "required",
            "amount" => "required|numeric",
            "lifetime" => "required|numeric",
            "acquisition_year" => "required",
            "acquisition_cost" => "required|numeric"
        ]);

        $model = new AssetModel();
        $model->asset_code = $request->asset_code;
        $model->asset_name = $request->asset_name;
        $model->amount = $request->amount;
        $model->lifetime = $request->lifetime;
        $model->acquisition_year = $request->acquisition_year;
        $model->acquisition_cost = $request->acquisition_cost;
        $model->created_by = Auth::user()->id;
        $saved = $model->save();

        if ($saved) {
            return redirect('/asset')->with('success', 'Add Asset Success!');
        } else {
            return redirect('/asset')->with('error', 'Add Asset Fail!');
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
        //* validate
        $request->validate([
            "asset_name" => "required",
            "amount" => "required|numeric",
            "lifetime" => "required|numeric",
            "acquisition_year" => "required",
            "acquisition_cost" => "required|numeric"
        ]);

        $model = AssetModel::where('id', $id)->first();
        $model->asset_name = $request->asset_name;
        $model->amount = $request->amount;
        $model->lifetime = $request->lifetime;
        $model->acquisition_year = $request->acquisition_year;
        $model->acquisition_cost = $request->acquisition_cost;
        $saved = $model->save();

        if ($saved) {
            return redirect('/asset')->with('success', 'Update Asset Success!');
        } else {
            return redirect('/asset')->with('error', 'Update Asset Fail!');
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
        $data = AssetModel::find($id);
        $saved = $data->delete();
        if ($saved) {
            return redirect('/asset')->with('success', 'Data has been deleted');
        } else {
            return redirect('/asset')->with('error', 'Data failed to delete');
        }
    }
}
