<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\AccountSubModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccountSubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('isSuperadmin') && !Gate::allows('isFinance')) {
            abort(403);
        }
        //* view data
        $title = 'Account Sub';
        $data = AccountSubModel::orderBy('code', 'asc')->get();
        $account = AccountModel::all();
        return view('account_sub.index', compact('title', 'data', 'account'));
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
        //* validate
        $request->validate([
            "account_id" => "required",
            "subFields.*.code" => "required",
            "subFields.*.name" => "required",
            // "subFields.*.description" => "required",
        ]);

        $message_duplicate = "";
        $issaved = false;
        foreach ($request->subFields as $key => $value) {
            $model = new AccountSubModel();
            $getCode = AccountModel::where('id', $request->account_id)->first();
            $model->account_id = $request->get('account_id');
            $model->code = $value['code'];
            $model->name = $value['name'];
            $model->biaya = $value['biaya'];
            $cek = AccountSubModel::where('code', $value['code'])
                ->where('account_id', $request->get('account_id'))
                ->count();

            if ($cek > 0) {
                $message_duplicate = "You enter duplication of sub account. Please recheck the type you enter.";
                continue;
            } else {
                $issaved = $model->save();
            }
        }

        if (empty($message_duplicate) && $issaved == true) {
            return redirect('/account_sub')->with('success', 'Create account sub Success');
        } elseif (!empty($message_duplicate) && $issaved == true) {
            return redirect('/account_sub')->with('success', 'Some of account sub add maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/account_sub')->with('error', 'Create account sub Fail! Please make sure you have filled all the input');
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
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        //* validate
        $request->validate([
            "account_ids" => "required",
            "codes" => "required",
            "names" => "required",
            // "description" => "required",
        ]);

        //* update
        $model = AccountSubModel::find($id);
        $model->account_id = $request->get('account_ids');
        $model->code = $request->get('codes');
        $model->name = $request->get('names');
        $model->biaya = $request->get('biayas');
        $saved = $model->save();
        if ($saved) {
            return redirect('/account_sub')->with('success', 'Update account sub Success');
        } else {
            return redirect('/account_sub')->with('error', 'Update account sub Fail');
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
        //* delete
        $data = AccountSubModel::find($id);
        $delete = $data->delete();
        if ($delete) {
            return redirect('/account_sub')->with('success', 'Delete account sub Success');
        } else {
            return redirect('/account_sub')->with('error', 'Delete account sub Fail');
        }
    }
}
