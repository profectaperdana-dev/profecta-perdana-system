<?php

namespace App\Http\Controllers;

use App\Models\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_roles = RoleModel::oldest()->get();
        $data = [
            'title' => "Data Accounts Role",
            'roles' => $all_roles
        ];

        return view('roles.index', $data);
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
        ]);
        $validated_data['created_by'] = Auth::user()->id;
        $validated_data['guard_name'] = 'web';

        RoleModel::create($validated_data);

        return redirect('/roles')->with('success', 'Role Add Success');
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
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        $validated_data = $request->validate([
            'name_edit' => 'required',
        ]);

        $role = RoleModel::where('id', $id)->firstOrFail();
        $role->name = $validated_data['name_edit'];
        $role->save();

        return redirect('/roles')->with('success', 'Role Edit Success');
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
        RoleModel::where('id', $id)->delete();

        return redirect('/roles')->with('error', 'Role Delete Success');
    }
}
