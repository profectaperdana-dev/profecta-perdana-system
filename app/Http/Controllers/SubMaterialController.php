<?php

namespace App\Http\Controllers;

use App\Models\MaterialModel;
use App\Models\SubMaterialModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SubMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = ' Product Sub Material';
        $data = SubMaterialModel::join('product_materials', 'product_sub_materials.material_id', '=', 'product_materials.id')
            ->select('product_sub_materials.*', 'product_materials.nama_material')
            ->latest()
            ->get();
        $materials = MaterialModel::oldest('nama_material')->get();

        return view('submaterials.index', compact('title', 'data', 'materials'));
    }

    public function create()
    {
        abort(404);
    }
    public function select($id)
    {
        $sub_materials = [];
        $material_id = $id;

        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = SubMaterialModel::select("id", "nama_sub_material")
                ->where('nama_sub_material', 'LIKE', '%', $search, '%')
                ->where('material_id', $id)
                ->get();
        } else {
            $sub_materials = SubMaterialModel::where('material_id', $material_id)->get();
        }
        return response()->json($sub_materials);
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
            'material_id' => 'required|numeric',
            // 'code_sub_material' => 'required|max:4'


        ]);
        // $checked = SubMaterialModel::where('code_sub_material', $request->get('code_sub_material'))->count();
        // if ($checked > 0) {
        //     return redirect('/product_sub_materials')->with('error', 'You Have Entered Duplicate Code');
        // }
        try {
            DB::beginTransaction();
            $model = new SubMaterialModel();
            $model->nama_sub_material = $request->get('nama_sub_material');
            $model->material_id = $request->get('material_id');
            // $model->code_sub_material = $request->get('code_sub_material');
            $model->code_sub_material = '-';

            $model->created_by = Auth::user()->id;
            $model->save();

            DB::commit();
            return redirect('/product_sub_materials')->with('success', 'Create data sub product material ' . $model->nama_sub_material . ' is success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/product_sub_materials')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
            'editnama_submaterial' => 'required',
            'material_id_edit' => 'required|numeric',
            // 'editcode_sub_material' => 'required|unique:product_sub_materials'

        ]);
        try {
            DB::beginTransaction();
            $model = SubMaterialModel::find($id);
            // $old = $model->code_sub_material;
            // $model->code_sub_material = '';
            // $model->save();
            // $checked = SubMaterialModel::where('code_sub_material', $request->get('editcode_sub_material'))->count();
            // if ($checked > 0) {
            //     $model->code_sub_material = $old;
            //     $model->save();
            //     return redirect('/product_sub_materials')->with('error', 'You Have Entered Duplicate Code');
            // }

            $model->nama_sub_material = $request->get('editnama_submaterial');
            $model->material_id = $request->get('material_id_edit');
            // $model->code_sub_material = $request->get('editcode_sub_material');
            $model->created_by = Auth::user()->id;
            $model->save();

            DB::commit();
            return redirect('/product_sub_materials')->with('info', 'Edit data sub product material ' . $model->nama_sub_material . ' is success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/product_sub_materials')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
            $model = SubMaterialModel::find($id);
            $model->delete();

            DB::commit();
            return redirect('/product_sub_materials')->with('error', 'Delete data sub product material ' . $model->nama_sub_material . ' is success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/product_sub_materials')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
