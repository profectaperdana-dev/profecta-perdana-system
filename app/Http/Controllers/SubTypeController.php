<?php

namespace App\Http\Controllers;

use App\Models\SubMaterialModel;
use App\Models\SubTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
        $all_sub_materials = SubMaterialModel::oldest('nama_sub_material')->get();

        $data = [
            'title' => 'Product Material Sub Type',
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
                    ->oldest('type_name')
                    ->get();
            } else {
                $sub_types = SubTypeModel::where('sub_material_id', $sub_material_id)->oldest('type_name')
                    ->get();
            }
            return response()->json($sub_types);
        } catch (\Throwable $th) {
            print($th);
        }
    }

    public function selectAll()
    {
        try {
            $sub_types = [];

            if (request()->has('q')) {
                $search = request()->q;
                $sub_types = SubTypeModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->join('product_materials', 'product_materials.id', '=', 'product_sub_materials.material_id')
                    ->select("product_sub_types.*", "product_sub_materials.nama_sub_material", "product_materials.nama_material")
                    ->where('type_name', 'LIKE', "%$search%")
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->get();
            } else {
                $sub_types = SubTypeModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->join('product_materials', 'product_materials.id', '=', 'product_sub_materials.material_id')
                    ->select("product_sub_types.*", "product_sub_materials.nama_sub_material", "product_materials.nama_material")
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->get();
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
            // 'code_sub_type' => 'required|max:5'

        ]);
        // $checked = SubTypeModel::where('code_sub_type', $request->get('code_sub_type'))->count();
        // if ($checked > 0) {
        //     return redirect('/product_sub_types')->with('error', 'You Have Entered Duplicate Code');
        // }
        try {
            DB::beginTransaction();
            $model = new SubTypeModel();
            $model->sub_material_id = $request->get('sub_material_id');
            $model->type_name = $request->get('type_name');
            // $model->code_sub_type = $request->get('code_sub_type');
            $model->code_sub_type = '-';

            $model->created_by = Auth::user()->id;
            $model->save();

            DB::commit();
            return redirect('/product_sub_types')->with('success', 'Create data sub product type ' . $model->type_name . ' is success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/product_sub_types')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        $validated_data = $request->validate([
            'sub_material_id_edit' => 'required|numeric',
            'type_name_edit' => 'required',
            // 'code_sub_type_edit' => 'required|max:5',
        ]);

        try {
            DB::beginTransaction();
            $current_type = SubTypeModel::where('id', $id)->firstOrFail();
            // $old = $current_type->code_sub_type;
            // $current_type->code_sub_type = '';
            // $current_type->save();
            // $checked = SubTypeModel::where('code_sub_type', $request->get('code_sub_type_edit'))->count();
            // if ($checked > 0) {
            //     $current_type->code_sub_type = $old;
            //     $current_type->save();
            //     return redirect('/product_sub_types')->with('error', 'You Have Entered Duplicate Code');
            // }
            $current_type->sub_material_id = $validated_data['sub_material_id_edit'];
            $current_type->type_name = $validated_data['type_name_edit'];
            // $current_type->code_sub_type = $validated_data['code_sub_type_edit'];
            $current_type->save();

            DB::commit();
            return redirect('/product_sub_types')->with('info', 'Create data sub product type ' . $current_type->type_name . ' is success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/product_sub_types')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
            $current_type = SubTypeModel::find($id);
            $current_type->delete();

            DB::commit();
            return redirect('/product_sub_types')->with('error', 'Create data sub product type ' . $current_type->type_name . ' is success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/product_sub_types')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
