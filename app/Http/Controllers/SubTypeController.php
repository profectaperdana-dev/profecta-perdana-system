<?php

namespace App\Http\Controllers;

use App\Models\SubMaterialModel;
use App\Models\SubTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_sub_types = SubTypeModel::join('product_sub_materials', 'product_sub_types.sub_material_id', '=', 'product_sub_materials.id')
            ->select('product_sub_types.*', 'product_sub_materials.nama_sub_material')
            ->latest()
            ->get();
        $all_sub_materials = SubMaterialModel::latest()->get();

        $data = [
            'title' => 'Material Sub Types',
            'sub_types' => $all_sub_types,
            'sub_materials' => $all_sub_materials
        ];

        return view('subtypes.index', $data);
    }

    public function select($id)
    {
        try {
            $sub_types = [];
            $sub_material_id = $id;

            if (request()->has('q')) {
                $search = request()->q;
                $sub_types = SubTypeModel::select("id", "type_name")
                    ->where('type_name', 'LIKE', "%$search%")
                    ->where('sub_material_id', $id)
                    ->get();
            } else {
                $sub_types = SubTypeModel::where('sub_material_id', $sub_material_id)->get();
            }
            return response()->json($sub_types);
        } catch (\Throwable $th) {
            print($th);
        }
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
            'sub_material_id' => 'required|numeric',
            'type_name' => 'required',
            'code_sub_type' => 'required|max:3|min:2'

        ]);
        $model = new SubTypeModel();
        $model->sub_material_id = $request->get('sub_material_id');
        $model->type_name = $request->get('type_name');
        $model->code_sub_type = $request->get('code_sub_type');
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect('/product_sub_types')->with('success', 'Create data sub product type ' . $model->type_name . ' is success');
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
        $validated_data = $request->validate([
            'sub_material_id_edit' => 'required|numeric',
            'type_name_edit' => 'required',
            'code_sub_type_edit' => 'required|max:3|min:2',
        ]);

        $current_type = SubTypeModel::where('id', $id)->firstOrFail();
        $current_type->sub_material_id = $validated_data['sub_material_id_edit'];
        $current_type->type_name = $validated_data['type_name_edit'];
        $current_type->code_sub_type = $validated_data['code_sub_type_edit'];
        $current_type->save();

        return redirect('/product_sub_types')->with('info', 'Create data sub product type ' . $current_type->type_name . ' is success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $current_type = SubTypeModel::find($id);
        $current_type->delete();

        return redirect('/product_sub_types')->with('error', 'Create data sub product type ' . $current_type->type_name . ' is success');
    }
}
