<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Mail\NotifyMail;
use App\Mail\PoMail;
use App\Models\PurchaseOrderModel;
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
        $pdf = PDF::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->order_number . '.pdf');

        Mail::to($data->customerBy->email_cust)->queue(new InvoiceMail($warehouse, $data));

        if (Mail::failures()) {
            return redirect('/invoice')->with('error', 'Send Invoice By Email Failed !');
        } else {
            return redirect('/invoice')->with('success', 'Send Invoice ' . $data->order_number . ' to ' . $data->customerBy->name_cust . ' by Email Success !');
        }
    }
    public function sendPo($id)
    {
        $data = PurchaseOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = PDF::loadView('purchase_orders.print_po', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->order_number . '.pdf');

        Mail::to($data->supplierBy->email)->queue(new PoMail($warehouse, $data));

        if (Mail::failures()) {
            return redirect('/all_purchase_orders')->with('error', 'Send Purchase Order By Email Failed !');
        } else {
            return redirect('/all_purchase_orders')->with('success', 'Send Purchase Order ' . $data->order_number . ' to ' . $data->supplierBy->nama_supplier . ' by Email Success !');
        }
    }
}
