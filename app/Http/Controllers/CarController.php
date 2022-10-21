<?php

namespace App\Http\Controllers;

use App\Models\CarBrandModel;
use App\Models\CarTypeModel;
use Illuminate\Http\Request;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Cars Brand';
        $data = CarBrandModel::latest()->get();
        return view('cars.index', compact('title', 'data'));
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
            'car_brand' => 'required',
        ]);
        // save data
        $data = new CarBrandModel();
        $data->car_brand = strtoupper($request->car_brand);
        $saved = $data->save();
        if ($saved) {
            return redirect('cars')->with('success', 'Data has been saved');
        } else {
            return redirect('cars')->with('error', 'Data failed to save');
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
            'edit_car_brand' => 'required',
        ]);

        // update data
        $data = CarBrandModel::find($id);
        $data->car_brand = strtoupper($request->edit_car_brand);
        $saved = $data->save();
        if ($saved) {
            return redirect('cars')->with('success', 'Data has been updated');
        } else {
            return redirect('cars')->with('error', 'Data failed to update');
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
        $data = CarBrandModel::find($id);
        $saved = $data->delete();
        if ($saved) {
            return redirect('cars')->with('success', 'Data has been deleted');
        } else {
            return redirect('cars')->with('error', 'Data failed to delete');
        }
    }

    public function index_type()
    {
        $title = 'Cars Type';
        $brand = CarBrandModel::latest()->get();
        $data = CarTypeModel::latest()->get();
        return view('cars.index_type', compact('title', 'data', 'brand'));
    }

    public function store_type(Request $request)
    {
        $request->validate([
            "id_car_brand" => "required|numeric",
            "typeFields.*.car_type" => "required",
            "typeFields.*.accu_type" => "required",
        ]);

        $message_duplicate = "";
        $issaved = false;
        foreach ($request->typeFields as $key => $value) {
            $model = new CarTypeModel();
            $model->id_car_brand = $request->get('id_car_brand');
            $model->car_type = strtoupper($value['car_type']);
            $model->accu_type = strtoupper($value['accu_type']);
            $cek = CarTypeModel::where('car_type', strtoupper($value['car_type']))
                ->where('id_car_brand', $request->get('id_car_brand'))
                ->count();

            if ($cek > 0) {
                $message_duplicate = "You enter duplication of type. Please recheck the type you enter.";
                continue;
            } else {
                $issaved = $model->save();
            }
        }

        if (empty($message_duplicate) && $issaved == true) {
            return redirect('/cars_type')->with('success', 'Create type Success');
        } elseif (!empty($message_duplicate) && $issaved == true) {
            return redirect('/cars_type')->with('success', 'Some of type add maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/cars_type')->with('error', 'Create type Fail! Please make sure you have filled all the input');
        }
    }

    public function update_type(Request $request, $id)
    {
        $request->validate([
            "edit_brands_id" => "required|numeric",
            "edit_car_type" => "required",
            "edit_accu_type" => "required"
        ]);

        $message_duplicate = "";
        $issaved = false;
        $model = CarTypeModel::find($id);


        $model->id_car_brand = $request->get('edit_brands_id');
        $model->car_type = strtoupper($request->get('edit_car_type'));
        $model->accu_type = strtoupper($request->get('edit_accu_type'));
        $cek = CarTypeModel::where('car_type', strtoupper($request->get('edit_car_type')))
            ->where('id_car_brand',  $request->get('edit_brands_id'))
            ->count();

        if ($cek > 0) {
            $message_duplicate = "You enter duplication of type. Please recheck the type you enter.";
        } else {
            $issaved = $model->save();
        }


        if (empty($message_duplicate) && $issaved == true) {
            return redirect('/cars_type')->with('success', 'Update type Success');
        } elseif (!empty($message_duplicate) && $issaved == true) {
            return redirect('/cars_type')->with('success', 'Some of type update maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/cars_type')->with('error', 'Update type Fail! Please make sure you have filled all the input');
        }
    }

    public function delete_type($id)
    {
        $data = CarTypeModel::find($id);
        $saved = $data->delete();
        if ($saved) {
            return redirect('cars_type')->with('success', 'Data has been deleted');
        } else {
            return redirect('cars_type')->with('error', 'Data failed to delete');
        }
    }
}
