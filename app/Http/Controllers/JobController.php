<?php

namespace App\Http\Controllers;

use App\Models\JobModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_jobs = JobModel::latest()->get();

        $data = [
            'title' => "Data Account Jobs",
            'jobs' => $all_jobs
        ];

        return view('jobs.index', $data);
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
        $validated_data = $request->validate([
            'job_name' => 'required',
        ]);
        $validated_data['created_by'] = Auth::user()->id;

        JobModel::create($validated_data);

        return redirect('/jobs')->with('success', 'Job Add Success');
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
        $validated_data = $request->validate([
            'job_name_edit' => 'required',
        ]);

        $job = JobModel::where('id', $id)->firstOrFail();
        $job->job_name = $validated_data['job_name_edit'];
        $job->save();

        return redirect('/jobs')->with('success', 'Job Edit Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        JobModel::where('id', $id)->delete();

        return redirect('/jobs')->with('error', 'Job Delete Success');
    }
}
