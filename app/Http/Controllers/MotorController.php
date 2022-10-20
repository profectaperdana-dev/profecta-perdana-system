<?php

namespace App\Http\Controllers;

use App\Models\MotorBrandModel;
use App\Models\MotorTypeModel;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Motorcycle';
        $data = MotorBrandModel::latest()->get();
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
        // save data
        $data = new MotorBrandModel();
        $data->name_brand = $request->name_brands;
        $saved = $data->save();
        if ($saved) {
            return redirect('motorcycle')->with('success', 'Data has been saved');
        } else {
            return redirect('motorcycle')->with('error', 'Data failed to save');
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

        // update data
        $data = MotorBrandModel::find($id);
        $data->name_brand = $request->edit_brands;
        $saved = $data->save();
        if ($saved) {
            return redirect('motorcycle')->with('success', 'Data has been updated');
        } else {
            return redirect('motorcycle')->with('error', 'Data failed to update');
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
        // delete data
        $data = MotorBrandModel::find($id);
        $saved = $data->delete();
        if ($saved) {
            return redirect('motorcycle')->with('success', 'Data has been deleted');
        } else {
            return redirect('motorcycle')->with('error', 'Data failed to delete');
        }
    }

    public function motorcycleType()
    {
        $title = 'Motorcycle Type';
        $brand = MotorBrandModel::latest()->get();
        $data = MotorTypeModel::latest()->get();
        return view('motor.motorcycle_type', compact('title', 'data', 'brand'));
    }

    public function storeMotorcycleType(Request $request)
    {
        $request->validate([
            "brand_id" => "required|numeric",
            "typeFields.*.type" => "required"
        ]);

        $message_duplicate = "";
        $issaved = false;
        foreach ($request->typeFields as $key => $value) {
            $model = new MotorTypeModel();
            $model->id_motor_brand = $request->get('brand_id');
            $model->name_type = $value['type'];
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
            return redirect('/motorcycle_type')->with('success', 'Create type Success');
        } elseif (!empty($message_duplicate) && $issaved == true) {
            return redirect('/motorcycle_type')->with('success', 'Some of type add maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/motorcycle_type')->with('error', 'Create type Fail! Please make sure you have filled all the input');
        }
    }

    public function updateMotorcycleType(Request $request, $id)
    {
        $request->validate([
            "brands_id" => "required|numeric",
            "types" => "required"
        ]);

        $message_duplicate = "";
        $issaved = false;
        $model = MotorTypeModel::find($id);
        $model->id_motor_brand = $request->get('brands_id');
        $model->name_type =  $request->get('types');
        $cek = MotorTypeModel::where('name_type',  $request->get('types'))
            ->where('id_motor_brand',  $request->get('brands_id'))
            ->count();

        if ($cek > 0) {
            $message_duplicate = "You enter duplication of type. Please recheck the type you enter.";
        } else {
            $issaved = $model->save();
        }


        if (empty($message_duplicate) && $issaved == true) {
            return redirect('/motorcycle_type')->with('success', 'Update type Success');
        } elseif (!empty($message_duplicate) && $issaved == true) {
            return redirect('/motorcycle_type')->with('success', 'Some of type update maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/motorcycle_type')->with('error', 'Update type Fail! Please make sure you have filled all the input');
        }
    }

    // delete
    public function deleteMotorcycleType($id)
    {
        $data = MotorTypeModel::find($id);
        $saved = $data->delete();
        if ($saved) {
            return redirect('motorcycle_type')->with('success', 'Data has been deleted');
        } else {
            return redirect('motorcycle_type')->with('error', 'Data failed to delete');
        }
    }
}
