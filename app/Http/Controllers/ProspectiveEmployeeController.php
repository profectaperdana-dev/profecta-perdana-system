<?php

namespace App\Http\Controllers;

use App\Models\ProspectiveEmployeeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    public function createCode()
    {
        $code = Str::random(6);
        $model = new ProspectiveEmployeeModel;
        $model->code = $code;
        $saved = $model->save();

        if ($saved) {
            return redirect()->back()->with('success', 'Data has been saved!');
        } else {
            return redirect()->back()->with('error', 'Data failed to save!');
        }
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


    public function fill_form()
    {
        $title = 'Form Prospective Employee';
        return view('prospective_employee.create', compact('title'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
