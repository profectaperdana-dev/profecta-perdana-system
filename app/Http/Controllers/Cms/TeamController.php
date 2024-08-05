<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\TeamModel;
use App\Models\EmployeeModel;
use Illuminate\Http\Request;
use Mockery\Undefined;

class TeamController extends Controller
{
    public function index()
    {
        $get_employee = EmployeeModel::with(['teamBy'])->where('status', 1)->oldest('name')->get();

        $data = [
            'title' => "Manage Team Content",
            'all_employees' => $get_employee
        ];

        return view('cms.teams.index', $data);
    }

    public function save(Request $request)
    {
        $team = TeamModel::where('employee_id', $request->employee_id)->first();
        if (!$team) {
            $team = new TeamModel();
        }

        $team->employee_id = $request->employee_id;
        $team->sort_number = $request->sort_number;
        $team->save();

        return response()->json([
            'status' => 200,
            'message' => 'Changing team data success!',
            'data' => $team
        ]);
    }

    public function api_getteam()
    {
        $team = TeamModel::with(['employeeBy'])
            ->where('sort_number', '>', 0)
            ->oldest('sort_number')->get();

        return response()->json([
            'status' => 200,
            'data' => $team
        ]);
    }
}
