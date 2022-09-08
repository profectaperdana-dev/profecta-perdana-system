<?php

namespace App\Http\Controllers;

use App\Models\NotificationsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getAll()
    {
        $data = NotificationsModel::where('job_id', Auth::user()->job_id)->where('status', 0)->latest()->first();
        return response()->json($data);
    }
    public function readMessage($id)
    {
        $model = NotificationsModel::find($id);
        $model->status = 1;
        $model->save();
        return redirect()->back();
    }
    public function readAll($id)
    {
        // dd($id);
        $model = NotificationsModel::where('job_id', $id)->get();

        foreach ($model as $value) {
            $value->status = 1;
            $value->save();
        }

        return redirect()->back();
    }
}
