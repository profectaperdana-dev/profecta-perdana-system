<?php

namespace App\Http\Controllers;

use App\Models\NationalDayModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NationalDayController extends Controller
{
    public function index()
    {
        $this_year = date('Y');
        $all_national_days = NationalDayModel::oldest('date')->get();

        //Automatic Update Year

        $one_national_day = NationalDayModel::oldest('date')->first();

        if ($one_national_day != null) {
            $old_year = date('Y', strtotime($one_national_day->date));
            if ($old_year != $this_year) {
                foreach ($all_national_days as $value) {
                    $value->date = str_replace($old_year, $this_year, $value->date);
                    $value->save();
                }
            }
        }

        $data = [
            'title' => "Create National Day",
            'days' => $all_national_days
        ];
        return view('national_days.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nationalFields.*.date' => 'required',
            'nationalFields.*.remark' => 'required'
        ]);
        try {
            DB::beginTransaction();
            foreach ($request->nationalFields as $value) {
                $model = new NationalDayModel();
                $model->date = $value['date'];
                $model->remark = $value['remark'];
                $model->created_by = Auth::user()->id;
                $model->save();
            }

            DB::commit();
            return redirect('/national_day')->with('success', 'Add national day success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/national_day')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'remark' => 'required'
        ]);
        try {
            DB::beginTransaction();
            $selected_national_day = NationalDayModel::where('id', $id)->first();
            $day = $request->day;
            $month = $request->month;
            $year = $request->year;
            $full_date = $year . '-' . $month . '-' . $day;
            $selected_national_day->date = $full_date;
            $selected_national_day->remark = $request->remark;
            $selected_national_day->save();

            DB::commit();
            return redirect('/national_day')->with('success', 'Edit national day success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/national_day')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $selected_national_day = NationalDayModel::where('id', $id)->first();
            $selected_national_day->delete();

            DB::commit();
            return redirect('/national_day')->with('error', 'Delete national day success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/national_day')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
