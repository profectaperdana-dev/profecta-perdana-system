<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Mail\NotifyMail;
use App\Models\SalesOrderModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendEmailController extends Controller
{
    public function index($id)
    {

        $data = SalesOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        Mail::to('koleksibkk@gmail.com')->queue(new InvoiceMail($warehouse, $data));

        if (Mail::failures()) {
            return redirect('/invoice')->with('error', 'Send Invoice By Email Failed !');
        } else {
            return redirect('/invoice')->with('success', 'Send Invoice By Email Success !');
        }
    }
}
