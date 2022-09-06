<?php

namespace App\Http\Controllers;

use App\Models\MaterialModel;
use App\Models\SubMaterialModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $title = 'Data Product Sub Material';
        $data = SubMaterialModel::join('product_materials', 'product_sub_materials.material_id', '=', 'product_materials.id')
            ->select('product_sub_materials.*', 'product_materials.nama_material')
            ->latest()
            ->get();
        $materials = MaterialModel::latest()->get();

        return view('submaterials.index', compact('title', 'data', 'materials'));
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
            'code_sub_material' => 'required|max:3|min:2'

        ]);
        $model = new SubMaterialModel();
        $model->nama_sub_material = $request->get('nama_sub_material');
        $model->material_id = $request->get('material_id');
        $model->code_sub_material = $request->get('code_sub_material');
        $model->created_by = Auth::user()->id;
        $model->save();

        return redirect('/product_sub_materials')->with('success', 'Create data sub product material ' . $model->nama_sub_material . ' is success');
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
            'material_id_edit' => 'required|numeric'

        ]);
        $model = SubMaterialModel::find($id);
        $model->nama_sub_material = $request->get('editnama_submaterial');
        $model->material_id = $request->get('material_id_edit');
        $model->code_sub_material = $request->get('editcode_sub_material');
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect('/product_sub_materials')->with('info', 'Edit data sub product material ' . $model->nama_sub_material . ' is success');
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
        $model = SubMaterialModel::find($id);
        $model->delete();
        return redirect('/product_sub_materials')->with('error', 'Delete data sub product material ' . $model->nama_sub_material . ' is success');
    }
}
