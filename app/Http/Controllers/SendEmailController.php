<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Mail\NotifyMail;
use App\Models\SalesOrderModel;
use App\Models\WarehouseModel;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;

class SendEmailController extends Controller
{
    public function index($id)
    {

        $data = SalesOrderModel::find($id);

        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = PDF::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf_invoice/' . $data->order_number . '.pdf');

        Mail::to($data->customerBy->email_cust)->queue(new InvoiceMail($warehouse, $data));

        if (Mail::failures()) {
            return redirect('/invoice')->with('error', 'Send Invoice By Email Failed !');
        } else {
            return redirect('/invoice')->with('success', 'Send Invoice ' . $data->order_number . ' to ' . $data->customerBy->name_cust . '  Email Success !');
        }
    }
}
