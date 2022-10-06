<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Mail\NotifyMail;
use App\Mail\PoMail;
use App\Mail\ReturnMail;
use App\Models\PurchaseOrderModel;
use App\Models\ReturnModel;
use App\Models\SalesOrderModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
// use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use PDF;

class SendEmailController extends Controller
{
    public function index($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isSales') && !Gate::allows('isVerificator')
            && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $data = SalesOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = PDF::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->order_number . '.pdf');

        $name = $data->customerBy->email_cust;
        if (!filter_var($name, FILTER_VALIDATE_EMAIL)) {
            return redirect('invoice')->with('error', ' Invalid email format');
        } else {
            Mail::to($data->customerBy->email_cust)->queue(new InvoiceMail($warehouse, $data));
        }

        if (Mail::failures()) {
            return redirect('/invoice')->with('error', 'Send Invoice By Email Failed !');
        } else {
            return redirect('/invoice')->with('success', 'Send Invoice ' . $data->order_number . ' to ' . $data->customerBy->name_cust . ' by Email Success !');
        }
    }
    public function sendPo($id)
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
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
    public function send_return($id)
    {
        if (
            !Gate::allows('isSuperAdmin') &&  !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $data = ReturnModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = FacadePdf::loadView('returns.print_return', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->return_number . '.pdf');

        $name = $data->salesOrderBy->customerBy->email_cust;
        if (!filter_var($name, FILTER_VALIDATE_EMAIL)) {
            return redirect('/return')->with('error', ' Invalid email format');
        } else {
            Mail::to($data->salesOrderBy->customerBy->email_cust)->queue(new ReturnMail($warehouse, $data));
        }

        if (Mail::failures()) {
            return redirect('/return')->with('error', 'Send Return Invoice By Email Failed !');
        } else {
            return redirect('/return')->with('success', 'Send Return Invoice ' . $data->return_number . ' to ' . $data->salesOrderBy->customerBy->name_cust . ' by Email Success !');
        }
    }
}
