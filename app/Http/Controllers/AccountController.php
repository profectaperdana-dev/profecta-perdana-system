<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')) {
            abort(403);
        }
        $title = 'Account';
        $data = AccountModel::orderBy('code', 'asc')->get();
        return view('account.index', compact('title', 'data'));
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

        //* Validate
        $request->validate([
            'code' => 'required',
            'name' => 'required',
            // 'biaya' => 'required',
        ]);
        //* Save data
        $model = new AccountModel();
        $model->code = $request->code;
        $model->name = $request->name;
        $model->biaya = $request->biaya;
        $saved = $model->save();
        if ($saved) {
            return redirect('account')->with('success', 'Data Has Been Saved');
        } else {
            return redirect('account')->with('error', 'Data Failed To Save');
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
        //* Validate
        $request->validate([
            'codes' => 'required',
            'names' => 'required',
            // 'descriptions' => 'required',
        ]);

        //* Update data
        $model = AccountModel::find($id);
        $model->code = $request->codes;
        $model->name = $request->names;
        $model->biaya = $request->biayas;
        $saved = $model->save();
        if ($saved) {
            return redirect('account')->with('success', 'Data Has Been Updated');
        } else {
            return redirect('account')->with('error', 'Data Failed To Update');
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
        //* Delete data
        $model = AccountModel::find($id);
        $deleted = $model->delete();
        if ($deleted) {
            return redirect('account')->with('success', 'Data Has Been Deleted');
        } else {
            return redirect('account')->with('error', 'Data Failed To Delete');
        }
    }
}
