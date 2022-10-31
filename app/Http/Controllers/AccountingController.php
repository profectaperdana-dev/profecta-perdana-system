<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\AccountSubModel;
use App\Models\AccountSubTypeModel;
use App\Models\JurnalModel;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //* view data from database
        $data = JurnalModel::latest()->get();
        $title = 'Jurnal';
        return view('accounting.index', compact('data'));
    }
    public function jurnal()
    {
        //* view data from database
        $data = JurnalModel::latest()->get();
        $title = 'Journal';
        return view('accounting.jurnal', compact('data', 'title'));
    }
    public function createExpenses()
    {
        $title = 'Create Expenses';
        $account = AccountModel::latest()->get();
        return view('accounting.create_expanse', compact('title', 'account'));
    }

    public function storeExpenses(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'account_sub_id' => 'required',
            'account_sub_type_id' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);
        $data = [
            'account_id' => $request->account_id,
            'account_sub_id' => $request->account_sub_id,
            'account_sub_type_id' => $request->account_sub_type_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ];
        JurnalModel::create($data);
        return redirect()->route('accounting.jurnal')->with('success', 'Data has been added');
    }

    public function select($id)
    {
        $sub_materials = [];
        $material_id = $id;

        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = AccountSubModel::select("id", "account_id", "code", "name")
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('code', 'LIKE', "%$search%")
                ->where('account_id', $material_id)
                ->get();
        } else {
            $sub_materials = AccountSubModel::where('account_id', $material_id)->get();
        }
        return response()->json($sub_materials);
    }
    public function select_type($id)
    {
        $sub_materials = [];
        $material_id = $id;

        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = AccountSubTypeModel::select("id", "account_sub_id", "code", "name")
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('code', 'LIKE', "%$search%")
                ->where('account_sub_id', $material_id)
                ->get();
        } else {
            $sub_materials = AccountSubTypeModel::where('account_sub_id', $material_id)->get();
        }
        return response()->json($sub_materials);
    }
    /** 
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //* 
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
}
