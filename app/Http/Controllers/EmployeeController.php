<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_employees = EmployeeModel::oldest('name')->get();
        $data = [
            'title' => 'Employees Data',
            'employees' => $all_employees
        ];

        return view('employees.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empty_employee = new EmployeeModel();

        $data = [
            'title' => 'Employee',
            'employee' => $empty_employee
        ];

        return view('employees.create', $data);
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
            'name' => 'required',
            'gender' => 'required',
            'birth_place' => 'required',
            'birth_date' => 'required',
            'phone' => 'required',
            'emergency_phone' => 'required',
            'email' => 'required',
            'province' => 'required',
            'district' => 'required',
            'sub_district' => 'required',
            'address' => 'required',
            'last_education_first' => 'required',
            'school_name_first' => 'required',
            'from_first' => 'required',
            'to_first' => 'required',
            'last_education_sec' => 'required',
            'school_name_sec' => 'required',
            'from_sec' => 'required',
            'to_sec' => 'required',
            'mom_name' => 'required',
            'mom_phone' => 'required',
            'father_name' => 'required',
            'father_phone' => 'required',
            'salary' => 'required',
            'work_date' => 'required',
            'photo' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $model = new EmployeeModel();
        $model->name = $request->name;
        $model->gender = $request->gender;
        $model->birth_place = $request->birth_place;
        $model->birth_date = $request->birth_date;
        $model->phone = $request->phone;
        $model->emergency_phone = $request->emergency_phone;
        $model->email = $request->email;

        //Get Province, City, District, Village
        $province_name = $this->getNameProvince($request->province);
        $model->province = ucwords(strtolower($province_name));
        $district_name = $this->getNameCity($request->district);
        $model->district = ucwords(strtolower($district_name));
        $sub_district = $this->getNameDistrict($request->sub_district);
        $model->sub_district = ucwords(strtolower($sub_district));

        $model->address = $request->address;
        $model->last_education_first = $request->last_education_first;
        $model->school_name_first = $request->school_name_first;
        $model->from_first = $request->from_first;
        $model->to_first = $request->to_first;
        $model->last_education_sec = $request->last_education_sec;
        $model->school_name_sec = $request->school_name_sec;
        $model->from_sec = $request->from_sec;
        $model->to_sec = $request->to_sec;
        $model->mom_name = $request->mom_name;
        $model->mom_phone = $request->mom_phone;
        $model->father_name = $request->father_name;
        $model->father_phone = $request->father_phone;
        $model->work_date = $request->work_date;
        $model->salary = $request->salary;

        //Process Image
        $file = $request->file('photo');
        if ($file) {
            $name_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/employees'), $name_file);
        } else {
            $name_file = "blank";
        }
        $model->photo = $name_file;

        $saved = $model->save();

        return redirect('/employee')->with('success', 'Employee Add Success');
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
        //
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
}
