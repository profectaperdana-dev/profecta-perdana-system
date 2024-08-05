<?php

namespace App\Http\Controllers;

use App\Models\AssetCategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AssetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_asset_categories = AssetCategoryModel::latest()->get();
        $data = [
            'title' => 'Master Asset Category',
            'asset_categories' => $all_asset_categories
        ];

        return view('asset_categories.index', $data);
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
            'name' => 'required',
            'code' => 'required|unique:asset_categories|max:3'
        ]);
        try {
            DB::beginTransaction();
            $model = new AssetCategoryModel();
            $model->name = $request->name;
            $model->code = $request->code;
            $model->created_by = Auth::user()->id;
            $model->save();

            DB::commit();
            return redirect('/asset_category')->with('success', 'Asset Category Add Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/asset_category')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        $request->validate([
            'name_edit' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $selected_category = AssetCategoryModel::where('id', $id)->firstOrFail();
            $selected_category->name = $request->name_edit;
            $selected_category->save();

            DB::commit();
            return redirect('/asset_category')->with('success', 'Asset Category Edit Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/asset_category')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        if (!Gate::allows('level1')) {
            abort(403);
        }

        try {
            DB::beginTransaction();
            AssetCategoryModel::where('id', $id)->delete();

            DB::commit();
            return redirect('/asset_category')->with('error', 'Asset Category Delete Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/asset_category')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
