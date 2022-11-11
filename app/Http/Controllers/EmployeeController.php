<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
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
            'last_edu_first' => 'required',
            'school_name_first' => 'required',
            'from_first' => 'required',
            'to_first' => 'required',
            'last_edu_sec' => 'required',
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

        $selected_employee = new EmployeeModel();
        $selected_employee->name = $request->name;
        $selected_employee->gender = $request->gender;
        $selected_employee->birth_place = $request->birth_place;
        $selected_employee->birth_date = $request->birth_date;

        //Create Employee ID
        $birth_date = $request->birth_date;
        $month = date('m', strtotime($birth_date));
        $day = date('d', strtotime($birth_date));
        $count_employee = EmployeeModel::max('id') + 1;
        $padding = str_pad($count_employee, 4, '0', STR_PAD_LEFT);
        $employee_id = $month . $day . $padding;
        $selected_employee->employee_id = $employee_id;

        $selected_employee->phone = $request->phone;
        $selected_employee->emergency_phone = $request->emergency_phone;
        $selected_employee->email = $request->email;

        //Get Province, City, District, Village
        $province_name = $this->getNameProvince($request->province);
        $selected_employee->province = ucwords(strtolower($province_name));
        $district_name = $this->getNameCity($request->district);
        $selected_employee->district = ucwords(strtolower($district_name));
        $sub_district = $this->getNameDistrict($request->sub_district);
        $selected_employee->sub_district = ucwords(strtolower($sub_district));

        $selected_employee->address = $request->address;
        $selected_employee->last_edu_first = $request->last_edu_first;
        $selected_employee->school_name_first = $request->school_name_first;
        $selected_employee->from_first = $request->from_first;
        $selected_employee->to_first = $request->to_first;
        $selected_employee->last_edu_sec = $request->last_edu_sec;
        $selected_employee->school_name_sec = $request->school_name_sec;
        $selected_employee->from_sec = $request->from_sec;
        $selected_employee->to_sec = $request->to_sec;
        $selected_employee->mom_name = $request->mom_name;
        $selected_employee->mom_phone = $request->mom_phone;
        $selected_employee->father_name = $request->father_name;
        $selected_employee->father_phone = $request->father_phone;
        $selected_employee->work_date = $request->work_date;
        $selected_employee->salary = $request->salary;
        $selected_employee->job = $request->job;

        //Process Image
        $file = $request->file('photo');
        if ($file) {
            $name_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/employees'), $name_file);
        } else {
            $name_file = "blank";
        }
        $selected_employee->photo = $name_file;

        $saved = $selected_employee->save();

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
        $selected_employee = EmployeeModel::where('id', $id)->first();

        $data = [
            'title' => 'Employee',
            'employee' => $selected_employee
        ];

        return view('employees.edit', $data);
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
            'last_edu_first' => 'required',
            'school_name_first' => 'required',
            'from_first' => 'required',
            'to_first' => 'required',
            'last_edu_sec' => 'required',
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

        $selected_employee = EmployeeModel::where('id', $id)->first();
        $selected_employee->name = $request->name;
        $selected_employee->gender = $request->gender;
        $selected_employee->birth_place = $request->birth_place;
        $selected_employee->birth_date = $request->birth_date;
        $selected_employee->phone = $request->phone;
        $selected_employee->emergency_phone = $request->emergency_phone;
        $selected_employee->email = $request->email;

        //Get Province, City, District, Village
        if ($selected_employee->province != $request->province) {
            $province_name = $this->getNameProvince($request->province);
            $selected_employee->province = ucwords(strtolower($province_name));
            $district_name = $this->getNameCity($request->district);
            $selected_employee->district = ucwords(strtolower($district_name));
            $sub_district = $this->getNameDistrict($request->sub_district);
            $selected_employee->sub_district = ucwords(strtolower($sub_district));
        }

        $selected_employee->address = $request->address;
        $selected_employee->last_edu_first = $request->last_edu_first;
        $selected_employee->school_name_first = $request->school_name_first;
        $selected_employee->from_first = $request->from_first;
        $selected_employee->to_first = $request->to_first;
        $selected_employee->last_edu_sec = $request->last_edu_sec;
        $selected_employee->school_name_sec = $request->school_name_sec;
        $selected_employee->from_sec = $request->from_sec;
        $selected_employee->to_sec = $request->to_sec;
        $selected_employee->mom_name = $request->mom_name;
        $selected_employee->mom_phone = $request->mom_phone;
        $selected_employee->father_name = $request->father_name;
        $selected_employee->father_phone = $request->father_phone;
        $selected_employee->work_date = $request->work_date;
        $selected_employee->salary = $request->salary;
        $selected_employee->job = $request->job;

        //Process Image
        $file = $request->file('photo');
        if ($file) {
            $name_file = time() . '_' . $file->getClientOriginalName();
            $path = public_path('images/employees/') . $selected_employee->photo;
            if (File::exists($path)) {
                File::delete($path);
            }
            $file->move(public_path('images/employees'), $name_file);
            $selected_employee->photo = $name_file;
        }
        $saved = $selected_employee->save();

        return redirect('/employee')->with('success', 'Employee Add Success');
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
        $employee_current = EmployeeModel::where('id', $id)->firstOrFail();
        $path = public_path('images/employees/') . $employee_current->photo;
        if (File::exists($path)) {
            File::delete($path);
        }
        $employee_current->delete();
        return redirect('/employee')->with('error', 'Employee Delete Success');
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
