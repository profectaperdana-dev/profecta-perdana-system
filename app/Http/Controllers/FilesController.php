<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderModel;
use App\Models\SalesOrderModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class FilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');

        $title = 'All Invoice Order File PDF';
        if ($keyword == NULL) {
            $data = SalesOrderModel::latest()->get();
        } else {
            $data = SalesOrderModel::leftJoin('customers', 'customers.id', '=', 'sales_orders.customers_id')
                ->select('sales_orders.*', 'customers.name_cust', 'customers.code_cust')
                ->where('sales_orders.pdf_invoice', 'LIKE', '%' . $keyword . '%')
                ->orWhere('customers.name_cust', 'LIKE', '%' . $keyword . '%')
                ->orWhere('customers.code_cust', 'LIKE', '%' . $keyword . '%')
                ->latest()
                ->get();
        }
        // $data = DataTables::of(SalesOrderModel::query())->make(true);
        return view('files.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getDO(Request $request)
    {
        $title = 'All Delivery Order File PDF';
        $keyword = $request->get('search');
        if ($keyword == NULL) {
            $data = SalesOrderModel::latest()->get();
        } else {
            $data = SalesOrderModel::where('pdf_invoice', 'LIKE', '%' . $keyword . '%')->latest()->get();
        }
        return view('files.do', compact('title', 'data'));
    }
    public function getFilePo(Request $request)
    {
        $title = 'All Purchase Order File PDF';
        $keyword = $request->get('search');
        if ($keyword == NULL) {
            $data = PurchaseOrderModel::latest()->get();
        } else {
            $data = PurchaseOrderModel::where('pdf_invoice', 'LIKE', '%' . $keyword . '%')->latest()->get();
        }
        return view('files.po', compact('title', 'data'));
    }
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
