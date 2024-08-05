<?php

namespace App\Http\Controllers;

use App\Models\CarBrandModel;
use App\Models\CarTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Create Car Brand';
        $data = CarBrandModel::oldest('car_brand')->get();
        return view('cars.index', compact('title', 'data'));
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
        // validate
        $request->validate([
            'car_brand' => 'required',
        ]);

        try {
            DB::beginTransaction();
            // save data
            $data = new CarBrandModel();
            $data->car_brand = strtoupper($request->car_brand);
            $saved = $data->save();
            if ($saved) {

                DB::commit();
                return redirect('cars')->with('success', 'Data has been saved');
            } else {

                DB::rollback();
                return redirect('cars')->with('error', 'Data failed to save');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('cars')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        // validate
        $request->validate([
            'edit_car_brand' => 'required',
        ]);
        try {
            DB::beginTransaction();
            // update data
            $data = CarBrandModel::find($id);
            $data->car_brand = strtoupper($request->edit_car_brand);
            $saved = $data->save();
            if ($saved) {

                DB::commit();
                return redirect('cars')->with('success', 'Data has been updated');
            } else {

                DB::rollback();
                return redirect('cars')->with('error', 'Data failed to update');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('cars')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
            $data = CarBrandModel::find($id);
            $saved = $data->delete();
            if ($saved) {

                DB::commit();
                return redirect('cars')->with('success', 'Data has been deleted');
            } else {

                DB::rollback();
                return redirect('cars')->with('error', 'Data failed to delete');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('cars')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function index_type()
    {
        $title = 'Create Car Type';
        $brand = CarBrandModel::oldest('car_brand')->get();
        $data = CarTypeModel::with('brandBy')->whereHas('brandBy', function ($query) {
            $query->oldest('car_brand');
        })->get();
        // $data2 = CarTypeModel::where('accu_type', 'LIKE', '%D66%')->get();
        // foreach ($data2 as $value) {
        //     $hasil = str_replace('D66', 'LN2', $value->accu_type);
        //     $value->accu_type = $hasil;
        //     $value->save();
        // }
        return view('cars.index_type', compact('title', 'data', 'brand'));
    }

    public function store_type(Request $request)
    {
        $request->validate([
            "id_car_brand" => "required|numeric",
            "typeFields.*.car_type" => "required",
            "typeFields.*.accu_type" => "required",
            "typeFields.*.second_accu_type" => "required",
            "typeFields.*.tire_type" => "required",
        ]);

        try {
            DB::beginTransaction();
            $message_duplicate = "";
            $issaved = false;
            foreach ($request->typeFields as $key => $value) {
                $model = new CarTypeModel();
                $model->id_car_brand = $request->get('id_car_brand');
                //$model->car_type = strtoupper($value['car_type']);
                if (ctype_digit($value['car_type'])) {
                    $model->car_type = $value['car_type'] . ' SERIES'; 
                } else {
                    $model->car_type = $value['car_type'];
                }
                $model->accu_type = strtoupper($value['accu_type']);
                $model->second_accu_type = strtoupper($value['second_accu_type']);
                $model->tire_type = strtoupper($value['tire_type']);
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

                DB::commit();
                return redirect('/cars_type')->with('success', 'Create type Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {

                DB::commit();
                return redirect('/cars_type')->with('success', 'Some of type add maybe Success! ' . $message_duplicate);
            } else {

                DB::rollback();
                return redirect('/cars_type')->with('error', 'Create type Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/cars_type')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function update_type(Request $request, $id)
    {
        $request->validate([
            "edit_brands_id" => "required|numeric",
            "edit_car_type" => "required",
            "edit_accu_type" => "required",
            "edit_second_accu_type" => "required",
            "edit_tire_type" => "required",
        ]);

        try {
            DB::beginTransaction();
            $message_duplicate = "";
            $issaved = true;
            $model = CarTypeModel::find($id);


            $model->id_car_brand = $request->get('edit_brands_id');
            $model->car_type = strtoupper($request->get('edit_car_type'));
            $model->accu_type = strtoupper($request->get('edit_accu_type'));
            $model->tire_type = strtoupper($request->get('edit_tire_type'));
            $model->second_accu_type = strtoupper($request->get('edit_second_accu_type'));
            $issaved = $model->save();
            
            if ($issaved == true) {

                DB::commit();
                return redirect('/cars_type')->with('success', 'Update type Success');
            } else {

                DB::rollback();
                return redirect('/cars_type')->with('error', 'Update type Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/cars_type')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function delete_type($id)
    {
        try {
            DB::beginTransaction();
            $data = CarTypeModel::find($id);
            $saved = $data->delete();
            if ($saved) {

                DB::commit();
                return redirect('cars_type')->with('success', 'Data has been deleted');
            } else {

                DB::rollback();
                return redirect('cars_type')->with('error', 'Data failed to delete');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/cars_type')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
