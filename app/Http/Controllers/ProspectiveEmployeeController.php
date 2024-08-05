<?php

namespace App\Http\Controllers;

use App\Models\ProspectiveEmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

use PharIo\Manifest\Url;

class ProspectiveEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //* index
        $title = 'Candidate Employees';
        $data = ProspectiveEmployeeModel::latest()->get();
        return view('prospective_employee.index', compact('title', 'data'));
    }
    public function print_data($any)
    {
        $data = ProspectiveEmployeeModel::where('code', $any)->first();
        $pdf = FacadePdf::loadView('prospective_employee.print_data', compact('data'))->setPaper('A4', 'potrait');
        return $pdf->stream();
    }
    public function createCode()
    {
        $code = Str::random(10);
        $model = new ProspectiveEmployeeModel;
        $model->code = $code;
        $model->link = url('/prospective_employees/fill_form/' . $code);
        $saved = $model->save();

        if ($saved) {
            return redirect()->back()->with('success', 'Link form has been created!');
        } else {
            return redirect()->back()->with('error', 'Link form has been failed to create!');
        }
    }

    public function store_form(Request $request)
    {
        //* store data pribadi 

        // dd($request->all());
        $model = ProspectiveEmployeeModel::where('link', $request->link)->first();

        if ($model->status == 1) {
            return abort(419);
        }

        $model->name = $request->name;
        $model->gender = $request->gender;
        $model->place_of_birth  = $request->place_of_birth;
        $model->date_of_birth = $request->date_of_birth;
        $model->height = $request->height;
        $model->weight = $request->weight;
        $model->marital_status = $request->status_marital;
        $model->card_id = $request->card_id;
        $model->sim_a = $request->sim_a;
        $model->sim_b = $request->sim_b;
        $model->sim_c = $request->sim_c;
        $model->address = $request->address;
        $model->address1 = $request->address1;

        if ($request->residential_status == 'other') {
            $model->residential = $request->other_residential;
        } else {
            $model->residential = $request->residential_status;
        }

        $model->own_vehicle = $request->vehicle;
        $model->vehicle = $request->kendaraan;
        $model->phone_number = $request->phone_number;
        $model->phone_number1 = $request->phone_number1;
        $model->email = $request->email;
        $model->e_contact = $request->e_contact;
        $model->relation = $request->relation;
        $model->status_tab_0 = 1;
        $saved = $model->save();
        $data = [
            'success' => 'Data has been saved!',
            'current_tab' => $request->tabNum,
        ];


        if ($saved) {
            return redirect()->back()->with($data);
        } else {
            return redirect()->back()->with('error', 'Data has been failed to save!');
        }
    }
    public function form_filled_successfully()
    {
        $title = 'Form Filled Successfully';
        return view('prospective_employee.form_filled_successfully', compact('title'));
    }
    public function getNameProvince($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/province/' . $id . '.json');
        $getProvinces = $getAPI->json();
        // dd($getProvinces['name']);
        return $getProvinces['name'];
        // dd($getProvinces['name']);
    }
    public function getNameCity($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/regency/' . $id . '.json');
        $getCities = $getAPI->json();
        return $getCities['name'];
    }
    public function getNameDistrict($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/district/' . $id . '.json');
        $getDistricts = $getAPI->json();
        return $getDistricts['name'];
    }

    /** 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $title = 'Form Prospective Employee';
        // return view('prospective_employee.create', compact('title'));
    }


    public function fill_form($any)
    {
        $title = 'Form Prospective Employee';
        $data = ProspectiveEmployeeModel::where('code', $any)->where('status', 0)->first();
        if ($data) {
            return view('prospective_employee.create', compact('title'));
        } else {
            return abort(404);
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
        //*save data

        $model = new ProspectiveEmployeeModel;
        $model->name = $request->name;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //* delete data
        $model = ProspectiveEmployeeModel::find($id);
        $model->delete();
        return redirect()->back()->with('success', 'Link form has been deleted!');
    }
}
