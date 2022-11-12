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
        $title = 'Prospective Employees';
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

        $model = ProspectiveEmployeeModel::where('link', $request->link)->first();

        if ($model->status == 1) {
            return abort(419);
        }

        $model->name = $request->name;
        $model->email = $request->email;
        $model->gender = $request->gender;
        $model->place_of_birth  = $request->place_of_birth;
        $model->date_of_birth = $request->date_of_birth;

        //Get Province, City, District, Village
        $province_name = $this->getNameProvince($request->province);
        $request->province = ucwords(strtolower($province_name));
        $city_name = $this->getNameCity($request->city);
        $request->city = ucwords(strtolower($city_name));
        $district_name = $this->getNameDistrict($request->district);
        $request->district = ucwords(strtolower($district_name));

        $model->address = $request->province . ', ' . $request->city . ', ' . $request->district . ', ' . $request->address;
        $model->phone_number = $request->phone_number;
        $model->house_phone_number = $request->house_phone_number;
        $model->birth_order = $request->birth_order;
        $model->from_order = $request->from_order;
        $model->formal_education_1 = $request->formal_education_1;
        $model->formal_education_from_1 = $request->formal_education_from_1;
        $model->formal_education_to_1 = $request->formal_education_to_1;
        $model->formal_education_2 = $request->formal_education_2;
        $model->formal_education_from_2 = $request->formal_education_from_2;
        $model->formal_education_to_2 = $request->formal_education_to_2;

        //* store data keluarga
        $model->marital_status = $request->marital_status;
        $model->couple_name = $request->couple_name;
        $model->couple_education = $request->couple_education;
        $model->couple_occupation = $request->couple_occupation;
        $model->number_of_children = $request->number_of_children;
        $model->child_1_age = $request->child_1_age;
        $model->child_2_age = $request->child_2_age;
        $model->child_3_age = $request->child_3_age;
        $model->child_4_age = $request->child_4_age;
        $model->father_name = $request->father_name;
        $model->father_occupation = $request->father_occupation;
        $model->father_address = $request->father_address;
        $model->mother_name = $request->mother_name;
        $model->mother_occupation = $request->mother_occupation;
        $model->mother_address = $request->mother_address;
        $model->related_name_1 = $request->related_name_1;
        $model->related_number_phone_1 = $request->related_number_phone_1;
        $model->related_name_2 = $request->related_name_2;
        $model->related_number_phone_2 = $request->related_number_phone_2;

        //* store data pekerjaan
        $model->company_name_1 = $request->company_name_1;
        $model->position_1 = $request->position_1;
        $model->length_of_work_1 = $request->length_of_work_1;
        $model->last_salary_1 = $request->last_salary_1;
        $model->reason_stop_1 = $request->reason_stop_1;

        $model->company_name_2 = $request->company_name_2;
        $model->position_2 = $request->position_2;
        $model->length_of_work_2 = $request->length_of_work_2;
        $model->last_salary_2 = $request->last_salary_2;
        $model->reason_stop_2 = $request->reason_stop_2;

        $model->language_skill_1 = $request->language_skill_1;
        $model->language_skill_2 = $request->language_skill_2;
        $model->language_skill_3 = $request->language_skill_3;

        $model->computer_skill = $request->computer_skill;
        $model->placement = $request->placement;
        $model->salary_expected = $request->salary_expected;

        $model->status = '1';
        $saved = $model->save();

        if ($saved) {
            return redirect('/form_filled_successfully');
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
