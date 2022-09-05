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
            $data = SalesOrderModel::where('pdf_invoice', 'LIKE', '%' . $keyword . '%')->latest()->get();
        }
        // $data = DataTables::of(SalesOrderModel::query())->make(true);
        return view('files.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getDO()
    {
        $title = 'All Delivery Order File PDF';
        // $data = DataTables::of(SalesOrderModel::query())->make(true);
        $data = SalesOrderModel::latest()->get();
        return view('files.do', compact('title', 'data'));
    }
    public function getFilePo()
    {
        $title = 'All Purchase Order File PDF';
        // $data = DataTables::of(SalesOrderModel::query())->make(true);
        $data = PurchaseOrderModel::latest()->get();
        return view('files.po', compact('title', 'data'));
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
