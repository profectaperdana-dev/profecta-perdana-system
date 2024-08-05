<?php

namespace App\Http\Controllers;

use App\Models\AttendancesModel;
use App\Models\User;
use App\Models\EmployeeModel;
use Carbon\Carbon;
use Nette\Utils\DateTime;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AttendancesController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = date('Y-m-d 00:00:00');
        $toDate = date('Y-m-d 23:59:59');

        if ($request->ajax()) {
            $query = AttendancesModel::with('userBy'); // Ensure userBy relationship is loaded

            if ($request->from_date && $request->to_date) {
                $fromDate = date('Y-m-d 00:00:00', strtotime($request->from_date));
                $toDate = date('Y-m-d 23:59:59', strtotime($request->to_date));
            } else {
                // Set default to today's date if no date range is provided
                $fromDate = date('Y-m-d 00:00:00');
                $toDate = date('Y-m-d 23:59:59');
            }

            $query->whereBetween('clock_time', [$fromDate, $toDate]);

            if ($request->user_id) {
                $user = User::where('employee_id', $request->user_id)->first();
                $query->where('user_id', $user->id);
            }

            $attendances = $query->latest()->get();

            return response()->json([
                'data' => $attendances,
            ]);
        }

        // Set default date filter for initial page load
        $attendances = AttendancesModel::with('userBy')
            ->whereBetween('clock_time', [$fromDate, $toDate])
            ->latest()
            ->get();

        return view('attendances.index', [
            'title' => 'Attendances',
            'attendances' => $attendances,
        ]);
    }

    public function getuser(Request $request)
    {
        $user = Auth::user()->employee_id;
        $data = User::where('status', '1')->orderBy('name', 'asc')->get();
        return response()->json($data);
    }
}
