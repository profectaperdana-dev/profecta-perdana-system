<?php

namespace App\Http\Controllers;

use App\Models\DailyActivityModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyActivityController extends Controller
{
    public function index(Request $request)
    {
        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $activity = DailyActivityModel::with('userBy')
                    ->where('user_id', Auth::user()->id)
                    ->whereBetween('date', array($request->from_date, $request->to_date))
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->date;
                    });
            } else {
                $activity = DailyActivityModel::with('userBy')
                    ->where('user_id', Auth::user()->id)
                    ->where('date', date('Y-m-d'))
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->date;
                    });
            }
            return datatables()->of($activity)
                ->editColumn('date', function ($data) {
                    return date('d/F/Y', strtotime($data->date));
                })
                ->make(true);
        }

        $data = [
            'title' => 'Daily Activity',
        ];
        return view('daily_activities.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity' => 'required',
            'result' => 'required',
            'duration' => 'required',
            'next' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $model = new DailyActivityModel();
            $model->activity = $request->activity;
            $model->result = $request->result;
            $model->duration = $request->duration;
            $model->next = $request->next;
            $model->user_id = Auth::user()->id;
            $model->date = date('Y-m-d');
            $model->save();

            DB::commit();
            return redirect('/daily_activity')->with('success', 'Add Activity Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/daily_activity')->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
