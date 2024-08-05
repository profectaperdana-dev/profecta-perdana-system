<?php

namespace App\Http\Controllers;

use App\Models\MotorBrandModel;
use App\Models\MotorTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MotorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Create Motorcycle Brand';
        $data = MotorBrandModel::oldest('name_brand')->get();
        return view('motor.index', compact('title', 'data'));
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
        // validate
        $request->validate([
            'name_brands' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // save data
            $data = new MotorBrandModel();
            $data->name_brand = $request->name_brands;
            $saved = $data->save();
            if ($saved) {

                DB::commit();
                return redirect('motorcycle')->with('success', 'Data has been saved');
            } else {

                DB::rollback();
                return redirect('motorcycle')->with('error', 'Data failed to save');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('motorcycle')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        // validate
        $request->validate([
            'edit_brands' => 'required',
        ]);
        try {
            DB::beginTransaction();
            // update data
            $data = MotorBrandModel::find($id);
            $data->name_brand = $request->edit_brands;
            $saved = $data->save();
            if ($saved) {

                DB::commit();
                return redirect('motorcycle')->with('success', 'Data has been updated');
            } else {

                DB::rollback();
                return redirect('motorcycle')->with('error', 'Data failed to update');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('motorcycle')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        try {
            DB::beginTransaction();
            // delete data
            $data = MotorBrandModel::find($id);
            $saved = $data->delete();
            if ($saved) {

                DB::commit();
                return redirect('motorcycle')->with('success', 'Data has been deleted');
            } else {

                DB::rollback();
                return redirect('motorcycle')->with('error', 'Data failed to delete');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('motorcycle')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function motorcycleType()
    {
        $title = 'Create Motorcycle Type';
        $brand = MotorBrandModel::latest()->get();
        $data = MotorTypeModel::with('brandBy')->whereHas('brandBy', function ($query) {
            $query->oldest('name_brand');
        })->get();
        return view('motor.motorcycle_type', compact('title', 'data', 'brand'));
    }

    public function storeMotorcycleType(Request $request)
    {
        $request->validate([
            "brand_id" => "required|numeric",
            "typeFields.*.type" => "required",
            "typeFields.*.accu_type" => "required",
        ]);

        try {
            DB::beginTransaction();
            $message_duplicate = "";
            $issaved = true;
            foreach ($request->typeFields as $key => $value) {
                $model = new MotorTypeModel();
                $model->id_motor_brand = $request->get('brand_id');
                $model->name_type = $value['type'];
                $model->accu_type = $value['accu_type'];
                $cek = MotorTypeModel::where('name_type', $value['type'])
                    ->where('id_motor_brand', $request->get('brand_id'))
                    ->count();

                if ($cek > 0) {
                    $message_duplicate = "You enter duplication of type. Please recheck the type you enter.";
                    continue;
                } else {
                    $issaved = $model->save();
                }
            }

            if (empty($message_duplicate) && $issaved == true) {

                DB::commit();
                return redirect('/motorcycle_type')->with('success', 'Create type Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {

                DB::commit();
                return redirect('/motorcycle_type')->with('success', 'Some of type add maybe Success! ' . $message_duplicate);
            } else {

                DB::rollback();
                return redirect('/motorcycle_type')->with('error', 'Create type Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/motorcycle_type')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function updateMotorcycleType(Request $request, $id)
    {
        $request->validate([
            "brands_id" => "required|numeric",
            "types" => "required",
            "accu_types" => "required"
        ]);

        try {
            DB::beginTransaction();
            $message_duplicate = "";
            $issaved = false;
            $model = MotorTypeModel::find($id);
            $model->id_motor_brand = $request->get('brands_id');
            $model->name_type =  $request->get('types');
            $model->accu_type =  $request->get('accu_types');
            $issaved = $model->save();



            if (empty($message_duplicate) && $issaved == true) {

                DB::commit();
                return redirect('/motorcycle_type')->with('success', 'Update type Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {

                DB::commit();
                return redirect('/motorcycle_type')->with('success', 'Some of type update maybe Success! ' . $message_duplicate);
            } else {

                DB::rollback();
                return redirect('/motorcycle_type')->with('error', 'Update type Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/motorcycle_type')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    // delete
    public function deleteMotorcycleType($id)
    {
        try {
            DB::beginTransaction();
            $data = MotorTypeModel::find($id);
            $saved = $data->delete();
            if ($saved) {

                DB::commit();
                return redirect('motorcycle_type')->with('success', 'Data has been deleted');
            } else {

                DB::rollback();
                return redirect('motorcycle_type')->with('error', 'Data failed to delete');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/motorcycle_type')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function select($id)
    {
        $sub_materials = [];
        $material_id = $id;

        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = MotorTypeModel::select("id", "name_type", "id_motor_brand")
                ->where('name_type', 'LIKE', "%$search%")
                ->where('id_motor_brand', $material_id)
                ->get();
        } else {
            $sub_materials = MotorTypeModel::where('id_motor_brand', $material_id)->get();
        }
        return response()->json($sub_materials);
    }
}
