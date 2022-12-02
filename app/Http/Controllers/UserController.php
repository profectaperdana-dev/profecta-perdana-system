<?php

namespace App\Http\Controllers;

use App\Models\EmployeeModel;
use App\Models\JobModel;
use App\Models\RoleModel;
use App\Models\User;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_users = User::join('roles', 'users.role_id', '=', 'roles.id')
            ->join('jobs', 'users.job_id', '=', 'jobs.id')
            ->join('warehouses', 'users.warehouse_id', '=', 'warehouses.id')
            ->select('users.*', 'roles.name AS role_name', 'jobs.job_name AS job_name', 'warehouses.warehouses AS warehouse_name')
            ->latest()
            ->get();
        $all_roles = RoleModel::latest()->get();
        $all_jobs = JobModel::latest()->get();
        $all_warehouses = WarehouseModel::where('type', 5)->get();
        $all_employees = EmployeeModel::oldest('name')->get();

        $data = [
            'title' => 'User Account',
            'users' => $all_users,
            'roles' => $all_roles,
            'jobs' => $all_jobs,
            'warehouses' => $all_warehouses,
            'employees' => $all_employees
        ];

        return view('users.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'employee_id' => 'required',
            'job_id' => 'required|numeric',
            'role_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric'

        ]);

        $selected_employee = EmployeeModel::where('id', $validated_data['employee_id'])->first();
        $validated_data['name'] = $selected_employee->name;

        //Create username
        $lowercase_name = strtolower($selected_employee->name);
        $split_name = explode(" ", $lowercase_name);
        $remove_nonalphabet_name = array_map(function ($val) {
            return preg_replace('/[^a-z0-9]/i', '', $val);
        }, $split_name);
        $get_three_char = array_map(function ($val) {
            return substr($val, 0, 3);
        }, $remove_nonalphabet_name);
        $implode_name = implode("", $get_three_char);
        $get_birth = date('md', strtotime($selected_employee->birth_date));
        $username = $implode_name . $get_birth;
        $validated_data['username'] = $username;

        $validated_data['password'] = bcrypt('profecta123');

        $saved = User::create($validated_data);
        if ($saved) {
            return redirect('/users')->with('success', 'User Account Add Success');
        } else return redirect('/users')->with('fail', 'The employee already has an account!');
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
            'employee_id_edit' => 'required',
            'role_id_edit' => 'required|numeric',
            'job_id_edit' => 'required|numeric',
            'warehouse_id_edit' => 'required|numeric'
        ]);

        $current_user = User::where('id', $id)->firstOrFail();
        $old_employee = $current_user->employee_id;

        if ($old_employee != $validated_data['employee_id_edit']) {
            $selected_employee = EmployeeModel::where('id', $validated_data['employee_id_edit'])->first();

            //Create username
            $lowercase_name = strtolower($selected_employee->name);
            $split_name = explode(" ", $lowercase_name);
            $remove_nonalphabet_name = array_map(function ($val) {
                return preg_replace('/[^a-z0-9]/i', '', $val);
            }, $split_name);
            $get_three_char = array_map(function ($val) {
                return substr($val, 0, 3);
            }, $remove_nonalphabet_name);
            $implode_name = implode("", $get_three_char);
            $get_birth = date('md', strtotime($selected_employee->birth_date));
            $username = $implode_name . $get_birth;
            $current_user->username = $username;
            $current_user->name = $selected_employee->name;
        }

        $current_user->employee_id = $validated_data['employee_id_edit'];
        $current_user->role_id = $validated_data['role_id_edit'];
        $current_user->job_id = $validated_data['job_id_edit'];
        $current_user->warehouse_id = $validated_data['warehouse_id_edit'];
        $current_user->save();

        return redirect('/users')->with('success', 'User Account Edit Success');
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
        User::where('id', $id)->delete();

        return redirect('/users')->with('error', 'User Account Delete Success');
    }
}
