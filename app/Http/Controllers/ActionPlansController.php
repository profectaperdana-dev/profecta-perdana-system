<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ActionPlansModel;
use Nette\Utils\DateTime;
use Carbon\Carbon;
use App\Models\EmployeeModel;
use App\Models\User;

class ActionPlansController extends Controller
{
    public function approve_plans(Request $request)
    {
        // Muat relasi 'userBy' agar bisa digunakan nanti
        $plans = ActionPlansModel::with('userBy')->where('is_approved', 'progress')->latest()->get();
        
               

        if ($request->ajax()) {
            return datatables()
                ->of($plans)
                ->editColumn('created_by', function ($data) {
                    // Mengambil nama dari relasi 'userBy'
                    $createdByLink = '<a href="#" class="fw-bold text-nowrap text-success modal-btn2" data-id="' . $data->id . '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">' . $data->created_by . '</a>';
                    $userByName = $data->userBy ? '<a href="#" class="fw-bold text-nowrap text-success modal-btn2" data-id="' . $data->id . '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">' . $data->userBy->name . '</a>' : 'N/A';

                    return $userByName;
                })

                ->editColumn('date', function ($data) {
                    return Carbon::parse($data->date)->format('d M Y');
                })
                ->editColumn('status', function ($data) {
                    // Mengubah nilai status
                    return $data->status == 0 ? 'OnGoing' : ($data->status == 1 ? 'Done' : 'Unknown');
                })
                ->addIndexColumn()
                ->rawColumns(['created_by'])
                ->make(true);
        }

        $data = [
            'title' => 'Approval Action Plans',
            'data' => $plans,
            
        ];

        return view('action_plans.approval', $data);
    }

    public function approve(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            // Insert data into DB leave_addition_details
            $approved = ActionPlansModel::where('id', $id)->first();
            $approved->is_approved = 'approved';
            $saved = $approved->save();

            if ($saved) {
                DB::commit();
                return redirect()->back()->with('success', 'Plans Update Success');
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

    public function reject($id)
    {
        // dd($id->all());
        try {
            DB::beginTransaction();

            // Mengambil data pengajuan cuti tambahan berdasarkan ID
            $reject = ActionPlansModel::where('id', $id)->first();
            if (!$reject) {
                return redirect()->back()->with('error', 'Request not found');
            }

            // Update status to rejected
            $reject->is_approved = 'rejected';
            $saved = $reject->save();

            if ($saved) {
                DB::commit();
                return redirect()->back()->with('success', 'Action Plans request has been rejected');
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

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $query = ActionPlansModel::where('is_approved', 'approved');

            if ($request->from_date && $request->to_date) {
                $fromDate = Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
                $toDate = Carbon::createFromFormat('Y-m-d', $request->to_date)->endOfDay();
                $query->whereBetween('date', [$fromDate, $toDate]);
            }

            $history = $query->latest()->get();

            return datatables()
                ->of($history)
                ->editColumn('date', function ($data) {
                    return Carbon::parse($data->date)->format('d M Y');
                })
                ->editColumn('created_by', function ($data) {
                    $userByName = $data->userBy ? '<a href="#" class="fw-bold text-nowrap text-success modal-btn2" data-id="' . $data->id . '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">' . $data->userBy->name . '</a>' : 'N/A';
                    return $userByName;
                })
                ->editColumn('status', function ($data) {
                    return $data->status == 0 ? 'OnGoing' : ($data->status == 1 ? 'Done' : 'Unknown');
                })
                ->addIndexColumn()
                ->rawColumns(['created_by'])
                ->make(true);
        }

        return view('action_plans.history', [
            'title' => 'History Action Plans',
            'data' => ActionPlansModel::where('is_approved', 'approved')->latest()->get(),
        ]);
    }
}
