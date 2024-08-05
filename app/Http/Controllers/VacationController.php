<?php

namespace App\Http\Controllers;

use App\Events\ApprovalMessage;
use App\Models\EmployeeModel;
use App\Models\NationalDayModel;
use App\Models\VacationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Utils\DateTime;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Events\LeaveApproval;
use App\Models\NotificationsModel;
use App\Models\UserAuthorizationModel;
use App\Models\AdditionLeaveDetailsModel;
use App\Models\AdditionLeaveModel;


class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //** use yajra to get all data */
        if ($request->ajax()) {

            $vacation = VacationModel::with('userBy', 'employeeBy')
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    return $query->whereBetween('submission', [$fromDate, $request->to_date]);
                }, function ($query) {
                    // Use start and end of the current month as the default date range
                    $startDate = date('Y-m-01');
                    $endDate = date('Y-m-t');
                    return $query->whereBetween('submission', [$startDate, $endDate]);
                })
                ->when($request->employee, function ($query, $user_id) {
                    return $query->where('user_id', $user_id);
                })
                ->when($request->status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->latest()
                ->get();

            return datatables()->of($vacation)
                ->addIndexColumn()
                ->editColumn('user_id', function (VacationModel $VacationModel) {
                    return $VacationModel->employeeBy->name;
                })
                ->editColumn('date_range', function (VacationModel $VacationModel) {
                    if ($VacationModel->remark == null) {
                        return date('d F Y', strtotime($VacationModel->start_date)) . ' - ' . date('d F Y', strtotime($VacationModel->end_date));
                    } else {
                        return date('d F Y', strtotime($VacationModel->start_date)) . ' - ' . date('d F Y', strtotime($VacationModel->end_date)) . ' (' . $VacationModel->remark . ')';
                    }
                })
               ->editColumn('remaining',function(VacationModel $VacationModel){
                    if ($VacationModel->employeeBy->vacation >= 1){
                    return '<span class="badge badge-success">' .$VacationModel->employeeBy->vacation.'  </span>';
                    }else{
                                            return '<span class="badge badge-danger">' .$VacationModel->employeeBy->vacation.'  </span>';

                    }
               })
                ->editColumn('submission', function (VacationModel $VacationModel) {
                    return date('d F Y', strtotime($VacationModel->submission));
                })
                ->editColumn('status', function (VacationModel $VacationModel) {
                    if ($VacationModel->status == 'pending') {
                        return '<span class="badge badge-warning">Pending</span>';
                    } else if ($VacationModel->status == 'approved') {
                        return '<span class="badge badge-success">Approved</span>';
                    } else {
                        return '<span class="badge badge-danger">Rejected</span>';
                    }
                })
                //* action button */
                ->addColumn('action', function ($vacation) {

                    return view('vacation._option', compact('vacation'))->render();
                })
                ->rawColumns(['action', 'status', 'count_days','remaining'])
                ->make(true);
        }
        $data = [
            'user' => VacationModel::select('vacations.user_id')
                ->join('employees', 'employees.id', '=', 'vacations.user_id')
                ->orderBy('employees.name', 'ASC')
                ->groupBy('vacations.user_id')
                ->get(),
            'title' => 'Data Vacation',
        ];
        return view('vacation.index', $data);
    }

public function history(Request $request)
    {
        $user = Auth::user()->employeeBy->id;
        //** use yajra to get all data */
        if ($request->ajax()) {
            
            $vacation = VacationModel::with('userBy', 'employeeBy')
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    return $query->whereBetween('submission', [$fromDate, $request->to_date]);
                }, function ($query) {
                    // Use start and end of the current month as the default date range
                    $startDate = date('Y-01-01');
    $endDate = date('Y-12-31');
    return $query->whereBetween('submission', [$startDate, $endDate]);
                })
                
                ->when($request->status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->where('user_id',$user)
                ->latest()
                ->get();

            return datatables()->of($vacation)
                ->addIndexColumn()
                ->editColumn('user_id', function (VacationModel $VacationModel) {
                    return $VacationModel->employeeBy->name;
                })
                ->editColumn('date_range', function (VacationModel $VacationModel) {
                    if ($VacationModel->remark == null) {
                        return date('d F Y', strtotime($VacationModel->start_date)) . ' - ' . date('d F Y', strtotime($VacationModel->end_date));
                    } else {
                        return date('d F Y', strtotime($VacationModel->start_date)) . ' - ' . date('d F Y', strtotime($VacationModel->end_date)) . ' (' . $VacationModel->remark . ')';
                    }
                })
               
                ->editColumn('submission', function (VacationModel $VacationModel) {
                    return date('d F Y', strtotime($VacationModel->submission));
                })
                ->editColumn('status', function (VacationModel $VacationModel) {
                    if ($VacationModel->status == 'pending') {
                        return '<span class="badge badge-warning">Pending</span>';
                    } else if ($VacationModel->status == 'approved') {
                        return '<span class="badge badge-success">Approved</span>';
                    } else {
                        return '<span class="badge badge-danger">Rejected</span>';
                    }
                })
                //* action button */
                ->addColumn('action', function ($vacation) {

                    return view('vacation._option_history', compact('vacation'))->render();
                })
                ->rawColumns(['action', 'status', 'count_days'])
                ->make(true);
        }
        $data = [
            'user' => VacationModel::select('vacations.user_id')
                ->join('employees', 'employees.id', '=', 'vacations.user_id')
                ->orderBy('employees.name', 'ASC')
                ->groupBy('vacations.user_id')
                ->get(),
            'title' => 'Leave History',
            'remaining'=>Auth::user()->employeeBy->vacation,
        ];
        return view('vacation.history', $data);
    }
public function getEmployee(Request $request)
    {
        $user = Auth::user()->employee_id;
        $data = EmployeeModel::where('status',1)->orderBy('name', 'asc')->get();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Form Leave';
        // ! get data vacation days
        $vacation = EmployeeModel::where('id', Auth::user()->employee_id)->first();
        $days = NationalDayModel::latest()->get();
        $arr_days = array_column($days->toArray(), 'date');
        $cek_ga = UserAuthorizationModel::with('userBy')->where('auth_id', 1006)->where('user_id', @auth()->user()->id)->count();

        $data = [
            'title' => $title,
            'vacation' => $vacation,
            'arr_days' => json_encode($arr_days),
            'cek_ga' =>$cek_ga ?? 0
        ];
        return view('vacation.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        //** kondisikan pilihan cuti khusus atau tahunan */
        try {
            // dd($id);
            // dd($request->all());
            DB::beginTransaction();
        $vacation = new VacationModel();
        if ($request->select_necess == 'Annual Leave') {
            // ! pecah tanggal menjadi array
            $tanggal = explode(',', $request->datepicker);
            // ! ubah format tanggal menjadi Y-m-d
            $array_tanggal = array_map(function ($tanggal) {
                return date('Y-m-d', strtotime($tanggal));
            }, $tanggal);
            // ! ambil tanggal terbesar
            $max_tanggal = max($array_tanggal);
            // dd($max_tanggal);
            // ! ambil tanggal terkecil
            $min_tanggal = min($array_tanggal);
            // ! save data
            $vacation->reason = $request->select_necess . ' - ' . $request->other_reason;
            $vacation->start_date = $min_tanggal;
            $vacation->end_date = $max_tanggal;
            $saved = $vacation->save();
            if ($saved) {
                //** kurangi hak cuti */
                $vacation_ = EmployeeModel::where('id', $request->employee_id)->first();
                $vacation_->vacation = $vacation_->vacation - $request->vacation_get;
                $saved_ = $vacation_->save();
            }
        } else {
            $vacation->remark = $request->remark;
            $vacation->reason = $request->select_necess . ' - ' . $request->reason;
            $vacation->start_date = $request->start_date;
            $vacation->end_date = $request->end_date;
        }
        $vacation->user_id =$request->employee_id;
        $vacation->submission = date('Y-m-d');
        $vacation->count_days = $request->vacation_get;
        $isSaved = $vacation->save();
        if ($isSaved) {
            $message = 'Leave Request From: ' . Auth::user()->name . ' - '  .  date('d-m-Y', strtotime($vacation->start_date)) . ' until ' . date('d-m-Y', strtotime($vacation->end_date)) . ' ( ' . $request->vacation_get . ' days )';
            event(new LeaveApproval('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->job_id = 44;
            $notif->save();
                            DB::commit();

            return redirect()->back()->with('success', 'Data has been saved');
        } else {
            return redirect()->back()->with('error', 'Data failed to save');
        }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function reject($id, Request $request)
    {
        try {
            // dd($id);
            // dd($request->all());
            DB::beginTransaction();
            $vacation = VacationModel::where('id', $id)->first();
            $vacation->status = 'rejected';
            $vacation->reason = $vacation->reason . ' - ' . $request->reason;
            // dd($vacation->user_id);
            //** tambahkan hak cuti */
            $vacation_ = EmployeeModel::where('id', $vacation->user_id)->first();
            // dd($vacation_);
            $vacation_->vacation = $vacation_->vacation + $vacation->count_days;
            $saved_ = $vacation_->save();


            $isSaved = $vacation->save();
            if ($isSaved && $saved_) {
                DB::commit();
                return redirect()->back()->with('success', 'Data has been saved');
            } else {
                DB::rollback();
                return redirect()->back()->with('error', 'Data failed to save');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function deleteVacation($id, Request $request)
    {
        try {

            DB::beginTransaction();
            $vacation = VacationModel::where('id', $id)->first();
            $vacation->status = 'rejected';
            $vacation->reason = $vacation->reason . ' - ' . $request->reason;


            //** tambahkan hak cuti */
            $vacation_ = EmployeeModel::where('id', $vacation->user_id)->first();
            $vacation_->vacation = $vacation_->vacation + $vacation->count_days;

            $saved_ = $vacation_->save();
            $isSaved = $vacation->delete();
            if ($isSaved && $saved_) {
                DB::commit();
                return redirect()->back()->with('success', 'Data has been deleted');
            } else {
                DB::rollback();
                return redirect()->back()->with('error', 'Data failed to delete');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function approve($id)
    {
        $vacation = VacationModel::where('id', $id)->first();
        $vacation->status = 'approved';
        $isSaved = $vacation->save();
        if ($isSaved) {
                           return redirect()->back()->with('success', 'Data has been updates');

        } 
    }

    //Additonal Vacation section

    public function getEmployee_2(Request $request)
    {
        $user = Auth::user()->employee_id;
        $data = EmployeeModel::where('status', 1)->orderBy('name', 'asc')
            ->get();
        return response()->json($data);
    }

    public function additional_index(Request $request)
    {
        $additional = AdditionalLeaveModel::with(['userBy', 'employeeBy'])->first();
        $data = [
            'title' => 'Additional Leave',
            'additional' => $additional,
        ];

        return view('additional_vacation.create', $data);
    }

    public function create_additional()
    {
        $additional = AdditionLeaveModel::with(['userBy', 'detailBy'])->first();

        $title = 'Form Additional Vacation';

        // Get the user data
        $user = EmployeeModel::all();

        // ! get data vacation days
        // $additional_vacation = EmployeeModel::where('id', Auth::user()->employee_id)->first();

        $addition = AdditionLeaveModel::get('addition');
        // $arr_days = array_column($days->toArray(), 'date');
        $remark = AdditionLeaveModel::get('remark');

        $data = [
            'title' => $title,
            'user' => $user, // Menambahkan data pengguna ke dalam data yang dikirimkan ke tampilan
            'addition' => $addition,
            // 'arr_days' => json_encode($arr_days),
            'remark' => $remark,
            'additional' => $additional,
        ];
        return view('additional_vacation.create', $data);
    }

    public function store_additional(Request $request)
    {
        // dd($request->all());
        //** kondisikan pilihan cuti khusus atau tahunan */
        try {
            DB::beginTransaction();
            // $additional_leave = AdditionLeaveModel::first();

            // if ($additional_leave == null) {
            $additional_leave = new AdditionLeaveModel();
            // }

            $additional_leave->addition = $request->vacation_get;
            $additional_leave->date = date('Y-m-d', strtotime($request->from_date));
            $additional_leave->remark = $request->remark;
            $additional_leave->status = 'progress';
            $additional_leave->created_by = Auth::user()->id;
            $isSaved = $additional_leave->save();

            // $additional_leave->employees()->delete();

            // if ($employee) {
            //     $employee = EmployeeModel::where('user_id', $additional_leave->id)->delete();
            // }

            foreach ($request->formEmployee as $item) {
                $employee = new AdditionLeaveDetailsModel();
                $employee->leave_addition_id = $additional_leave->id;
                $employee->employee_id = $item['employee'];
                $employee->save();
            }

            if ($isSaved) {
                $message = 'Addition Leave Request From: ' . Auth::user()->name;
                event(new LeaveApproval('From: ' . Auth::user()->name, $message));
                $notif = new NotificationsModel();
                $notif->message = $message;
                $notif->status = 0;
                $notif->job_id = 44;
                $notif->save();
                DB::commit();

                return redirect()->back()->with('success', 'Data has been saved');
            } else {
                return redirect()->back()->with('error', 'Data failed to save');
            }
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            return redirect()
                ->back()
                ->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function approve_additional(Request $request)
    {
        $addition = AdditionLeaveModel::where('status', 'progress')->latest()->get();
        $employees = EmployeeModel::all();
        if ($request->ajax()) {
            return datatables()
                ->of($addition)
                ->editColumn('remark', function ($data) {
                    return '<a href="#" class="fw-bold text-nowrap text-success modal-btn2" data-id="' . $data->id . '"  data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">' . $data->remark . '</a>';
                })
                ->editColumn('date', function ($data) {
                    return Carbon::parse($data->date)->format('d M Y');
                })

                ->addIndexColumn()
                ->rawColumns(['remark'])
                ->make(true);
        }

        $data = [
            'title' => 'Approval Leave Addition',
            'data' => $addition,
        ];

        return view('additional_vacation.approval', $data);
    }

    public function approveAdditional(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            // Check duplicate employee IDs
            $addition_arr = $request->employee_id;
            $duplicates = array_unique(array_diff_assoc($addition_arr, array_unique($addition_arr)));

            // If there are duplicates
            if (!empty($duplicates)) {
                return redirect('/approve_additional_vacation')->with('error', 'You entered duplicate employee IDs! Please check again!');
            }

            // Insert data into DB leave_addition_details
            $addition = AdditionLeaveModel::where('id', $id)->first();
            $addition->date = date('Y-m-d', strtotime($request->from_date));
            $addition->addition = $request->addition;
            $addition->remark = $request->remark;
            $addition->status = 'approved';
            $saved = $addition->save();

            // Delete employees that are not in the input
            $del = AdditionLeaveDetailsModel::where('leave_addition_id', $id)->whereNotIn('employee_id', $addition_arr)->delete();

            // Add or update employees
            foreach ($request->employee_id as $employee_id) {
                $employee_detail = AdditionLeaveDetailsModel::where('leave_addition_id', $id)->where('employee_id', $employee_id)->first();
                if ($employee_detail) {
                    // Update existing record if needed
                    // No specific update logic here, just ensure the record exists
                    $employee_detail->save();
                } else {
                    // Create new record
                    $employee_detail = new AdditionLeaveDetailsModel();
                    $employee_detail->leave_addition_id = $id;
                    $employee_detail->employee_id = $employee_id;
                    $employee_detail->save();
                }
                // Dapatkan nilai addition dari AdditionLeaveModel
                $addition_leave = AdditionLeaveModel::find($id);
                $addition = $addition_leave ? $addition_leave->addition : 0;

                // Dapatkan nilai vacation dari EmployeeModel
                $employee = EmployeeModel::find($employee_id);
                $vacation = $employee ? $employee->vacation : 0;

                // Hitung nilai leave_total
                $leave_total = $addition + $vacation;

                if ($employee) {
                    $employee->vacation = $leave_total;
                    $employee->save();
                }
            }

            if ($saved) {
                DB::commit();
                return redirect()->back()->with('success', 'Leave Addition Approved');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Update Failed! Please check again!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()
                ->back()
                ->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function reject_additional($id)
    {
        // dd($id->all());
        try {
            DB::beginTransaction();

            // Mengambil data pengajuan cuti tambahan berdasarkan ID
            $addition = AdditionLeaveModel::where('id', $id)->first();
            if (!$addition) {
                return redirect()->back()->with('error', 'Request not found');
            }

            // Update status to rejected
            $addition->status = 'rejected';
            $saved = $addition->save();

            // Menghapus semua detail pengajuan cuti tambahan
            $del = AdditionLeaveDetailsModel::where('leave_addition_id', $id)->delete();

            if ($saved && $del) {
                DB::commit();
                return redirect()->back()->with('success', 'Leave addition request has been rejected');
            } else {
                DB::rollBack();
                return redirect()->back()->with('error', 'Rejection failed! Please check again!');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function history_additional(Request $request)
    {
        // Ambil data yang memiliki status 'approved'
        if ($request->ajax()) {
            $addition = AdditionLeaveModel::whereIn('status', ['approved', 'rejected'])
            // ->orWhere('status', 'rejected')
            ->when($request->from_date, function ($query) use ($request) {
                return $query->whereBetween('date', [$request->from_date, $request->to_date]);
            }, function ($query) {
                // Use start and end of the current month as the default date range
                
                return $query->where('date', date('Y-m-d'));
            })
            ->latest()->get();
            return datatables()
                ->of($addition)
                ->editColumn('remark', function ($data) {
                    return view('additional_vacation._option_history', compact('data'))->render();
                    // return '<a href="#" class="fw-bold text-nowrap text-success modal-btn2" data-id="' . $data->id . '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">' . $data->remark . '</a>';
                })
                ->editColumn('date', function ($data) {
                    return Carbon::parse($data->date)->format('d M Y');
                })
                ->addIndexColumn()
                ->rawColumns(['remark'])
                ->make(true);
        }

        $data = [
            'title' => 'History Approval Leave Addition',
            // 'data' => $addition,
        ];

        return view('additional_vacation.history', $data);
    }


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
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
