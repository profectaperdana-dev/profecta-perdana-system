<?php

namespace App\Http\Controllers;

use App\Models\AuthorizationModel;
use App\Models\EmployeeModel;
use App\Models\JobModel;
use App\Models\RoleModel;
use App\Models\User;
use App\Models\UserAuthorizationModel;
use App\Models\UserWarehouseModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $all_users = User::with('userAuthBy')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.*', 'roles.name AS role_name')
            ->oldest('name')
            ->get();
        $all_roles = RoleModel::latest()->get();
        $all_jobs = JobModel::latest()->get();
        $all_warehouses = WarehouseModel::where('type', 5)->get();
        $all_employees = EmployeeModel::oldest('name')->get();
        $all_auth = AuthorizationModel::oldest('section')->get();
        $all_section = AuthorizationModel::select('section', 'master_section')->distinct()->get();
        $all_master_section = AuthorizationModel::select('master_section')->distinct()->get();
        // dd($all_section);
        $data = [
            'title' => 'Create User',
            'users' => $all_users,
            'roles' => $all_roles,
            'jobs' => $all_jobs,
            'warehouses' => $all_warehouses,
            'employees' => $all_employees,
            'auth' => $all_auth,
            'section' => $all_section,
            'master_section' => $all_master_section
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

        // dd($request->all());
        $validated_data = $request->validate([
            'employee_id' => 'required',
            'role_id' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            if ($request->userFields == null) {
                return redirect('/users')->with('error', "The user don't have warehouse area! Please set the warehouse");
            }

            $selected_employee = EmployeeModel::where('id', $validated_data['employee_id'])->first();
            $validated_data['name'] = $selected_employee->name;

            //Create username
            $lowercase_name = strtolower($selected_employee->name);
            $split_name = explode(" ", $lowercase_name);
            $split_name = array_splice($split_name, 0, 3);
            $remove_nonalphabet_name = array_map(function ($val) {
                return preg_replace('/[^a-z0-9]/i', '', $val);
            }, $split_name);
            if (sizeof($remove_nonalphabet_name) == 1) {
                $double_name = array_map(function ($val) {
                    return $val . $val;
                }, $remove_nonalphabet_name);
                $implode_name = implode("", $double_name);
                $implode_name = substr($implode_name, 0, 8);
            } else if (sizeof($remove_nonalphabet_name) == 2) {
                $get_three_char = array_map(function ($val) {
                    return substr($val, 0, 4);
                }, $remove_nonalphabet_name);
                $implode_name = implode("", $get_three_char);
            } else {
                $get_three_char = array_map(function ($val, $key) {
                    if ($key == 0) {
                        return substr($val, 0, 1);
                    } else if ($key == 1) {
                        return substr($val, 0, 4);
                    } else {
                        return substr($val, 0, 3);
                    }
                }, $remove_nonalphabet_name, array_keys($remove_nonalphabet_name));
                $implode_name = implode("", $get_three_char);
            }
            $get_birth = date('md', strtotime($selected_employee->birth_date));
            $username = $implode_name . $get_birth;
            $validated_data['username'] = $username;

            $validated_data['password'] = bcrypt('profecta123');

            $saved = User::create($validated_data);

            foreach ($request->userFields as $value) {
                $new_warehouse = new UserWarehouseModel();
                $new_warehouse->user_id = $saved->id;
                $new_warehouse->warehouse_id = $value['warehouse_id'];
                $new_warehouse->save();
            }

            if ($saved) {

                DB::commit();
                return redirect('/users')->with('success', 'User Account Add Success');
            } else {

                DB::rollback();
                return redirect('/users')->with('fail', 'The employee already has an account!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/users')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
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
            'status' => 'required'
        ]);

        try {
            DB::beginTransaction();
            if ($request->userEditFields == null) {
                return redirect('/users')->with('error', "The user don't have warehouse area! Please set the warehouse");
            }

            //Restore Warehouse
            $unselected_warehouse = UserWarehouseModel::where('user_id', $id)->whereNotIn('warehouse_id', $request->userEditFields)->get();
            foreach ($unselected_warehouse as $sw) {
                $sw->delete();
            }

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
            $current_user->status = $validated_data['status'];
            $current_user->save();

            foreach ($request->userEditFields as $value) {
                $search_warehouse = UserWarehouseModel::where('user_id', $id)->where('warehouse_id', $value['edit_warehouse_id'])->first();
                if ($search_warehouse == null) {
                    $new_warehouse = new UserWarehouseModel();
                    $new_warehouse->user_id = $id;
                    $new_warehouse->warehouse_id = $value['edit_warehouse_id'];
                    $new_warehouse->save();
                }
            }

            DB::commit();
            return redirect('/users')->with('success', 'User Account Edit Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/users')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
            User::where('id', $id)->delete();

            DB::commit();
            return redirect('/users')->with('error', 'User Account Delete Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/users')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function authorization(Request $request)
    {

        if ($request->ajax()) {
            $data = AuthorizationModel::orderBy('master_section', 'ASC')->get();


            return datatables()->of($data)
                ->addIndexColumn()
                // ->addColumn('action', function ($row) {
                //     $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editAuthorization">Edit</a>';
                //     $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteAuthorization">Delete</a>';
                //     return $btn;
                // })
                // ->rawColumns(['action'])
                ->make(true);
        }

        $all_auth = AuthorizationModel::oldest('menu_name')->get();
        // $icon = AuthorizationModel::select('section', 'icon')->distinct()->get();
        $data = [
            'title' => 'Master Authorization',
            'auth' => $all_auth,
            // 'icon' => $icon,
        ];
        return view('users.authorization', $data);
    }

    public function reset_password($id)
    {
        $selected_user = User::where('id', $id)->first();
        $selected_user->password = bcrypt('profecta123');
        $selected_user->save();
        return redirect('/users')->with('success', 'Reset Password Success');
    }

    public function edit_icon_authorization(Request $request, $id)
    {
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        $request->validate([
            'edit_menu_icon' => 'required',
        ]);
        $id_ = AuthorizationModel::first($id);
        $model = AuthorizationModel::where('section', $id_->section)->get();
        $model->icon = $request->get('edit_menu_icon');
        $model->save();

        return redirect('/authorization')->with('info', 'Change Icon Succes!');
    }
    public function edit_authorization(Request $request, $id)
    {
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        $request->validate([
            'edit_menu_name' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $model = AuthorizationModel::find($id);
            $model->menu_name = $request->get('edit_menu_name');
            $model->save();

            DB::commit();
            return redirect('/authorization')->with('info', 'Change Feature Name Succes!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/authorization')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function change_authorization(Request $request, $id)
    {
        // dd($request->authFields);
        //Delete Old Data
        try {
            DB::beginTransaction();
            $selected_auth = UserAuthorizationModel::where('user_id', $id)->get();
            if ($selected_auth != null) {
                UserAuthorizationModel::where('user_id', $id)->each(function ($value, $key) {
                    $value->delete();
                });
            }
            foreach ($request->authFields as $auth) {
                $model = new UserAuthorizationModel();
                $model->user_id = $id;
                $model->auth_id = $auth['auth_id'];
                $model->save();
            }

            DB::commit();
            return redirect('/users')->with('success', 'Change Authorization Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/users')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function user_authorization()
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
}
