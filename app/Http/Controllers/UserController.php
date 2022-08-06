<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use App\Models\User;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;

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
            ->join('warehouses', 'users.warehouse_id', '=', 'warehouses.id')
            ->select('users.*', 'roles.name AS role_name', 'warehouses.warehouses AS warehouse_name')
            ->latest()
            ->get();
        $all_roles = RoleModel::latest()->get();
        $all_warehouses = WarehouseModel::latest()->get();

        $data = [
            'title' => 'User Account',
            'users' => $all_users,
            'roles' => $all_roles,
            'warehouses' => $all_warehouses
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
            'name' => 'required',
            'email' => 'required|email:dns|unique:users,email',
            'role_id' => 'required|numeric',
            'warehouse_id' => 'required|numeric'
        ]);

        $validated_data['password'] = bcrypt('profecta123');

        User::create($validated_data);

        return redirect('/users')->with('success', 'User Account Add Success');
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
        $validated_data = $request->validate([
            'name_edit' => 'required',
            'role_id_edit' => 'required|numeric',
            'warehouse_id_edit' => 'required|numeric'
        ]);

        $current_user = User::where('id', $id)->firstOrFail();
        $current_user->name = $validated_data['name_edit'];
        $current_user->role_id = $validated_data['role_id_edit'];
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
        User::where('id', $id)->delete();

        return redirect('/users')->with('success', 'User Account Delete Success');
    }
}
