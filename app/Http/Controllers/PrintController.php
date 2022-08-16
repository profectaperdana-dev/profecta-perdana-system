<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;
use PDF;

class PrintController extends Controller
{
    public function cetakInvoice()
    {

        $data = ProductModel::all();
        $pdf = PDF::loadview('print.invoice', compact('data'))->setPaper('A5', 'landscape');
        return $pdf->download('laporan-pegawai.pdf');
    }
}
