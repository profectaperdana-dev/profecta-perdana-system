<?php

namespace App\Http\Controllers;

use App\Models\NotificationsModel;
use App\Models\UserAuthorizationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getAll()
    {
        $cek_user_verificator = UserAuthorizationModel::with('userBy')->where('auth_id', 43)->where('user_id', auth()->user()->id)->first();
        $cek_user_approve_so = UserAuthorizationModel::with('userBy')->where('auth_id', 44)->where('user_id', auth()->user()->id)->first();
        $cek_user_approve_po = UserAuthorizationModel::with('userBy')->where('auth_id', 39)->where('user_id', auth()->user()->id)->first();
        $cek_user_approve_leave = UserAuthorizationModel::with('userBy')->where('auth_id', 1006)->where('user_id', auth()->user()->id)->first();
        
            $receiver_return = UserAuthorizationModel::with('userBy')->where('auth_id', 1070)->where('user_id', @auth()->user()->id)->count();

            $receive_return_direct = UserAuthorizationModel::with('userBy')->where('auth_id', 1071)->where('user_id', @auth()->user()->id)->count();
            $receive_return_indirect = UserAuthorizationModel::with('userBy')->where('auth_id', 1070)->where('user_id', @auth()->user()->id)->count();
            $receive_return_ = UserAuthorizationModel::with('userBy')->where('auth_id', 1068)->where('user_id', @auth()->user()->id)->count();
            $receive_return = UserAuthorizationModel::with('userBy')->where('auth_id', 1069)->where('user_id', @auth()->user()->id)->count();
        // dd($cek_user_approve_po);
        $data = NotificationsModel::whereIn('job_id', [$receive_return->auth_id,$receive_return_->auth_id,$receive_return_indirect->auth_id,$receive_return_direct->auth_id,$receiver_return->auth_id,$cek_user_approve_leave->auth_id,$cek_user_verificator->auth_id, $cek_user_approve_so->auth_id, $cek_user_approve_po->auth_id])->where('status', 0)->latest()->first();
        return response()->json($data);
    }
    public function readMessage($id)
    {
        $model = NotificationsModel::find($id);
        $model->status = 1;
        $model->save();
        return redirect()->back();
    }
    public function readAll($param)
    {


        $ids = explode(',', $param);
        // return response()->json([
        //         'data' =>$ids,
        //         'message' => 'Validation failed. Please check your input.'
        //     ], 422);
        $model = NotificationsModel::whereIn('job_id', $ids)->get();

        foreach ($model as $value) {
            $value->status = 1;
            $value->save();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'All notification has been removed.'
        ]);
    }
}
