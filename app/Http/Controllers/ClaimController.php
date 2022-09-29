<?php

namespace App\Http\Controllers;

use App\Models\ClaimModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'List Claim';
        $product = ProductModel::all();
        $customer = CustomerModel::all();
        $data = ClaimModel::where('status', 0)->latest()->get();
        return view('claim.index', compact('title', 'product', 'customer', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Early Checking';
        $product = ProductModel::all();
        $customer = CustomerModel::all();
        $data = ClaimModel::latest()->get();
        return view('claim.create', compact('title', 'product', 'customer', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'product_id' => 'required',
            'car_type' => 'required',
            'plate_number' => 'required',
            'e_voltage' => 'required',
            'e_cca' => 'required',
            'e_starting' => 'required',
            'e_charging' => 'required',
            'diagnosa' => 'required',
        ]);


        // get claim number
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', Auth::user()->warehouse_id)
            ->first();
        $length = 3;
        $id = intval(ClaimModel::where('claim_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $order_number = 'CLPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

        // save claim
        $model = new ClaimModel();
        $model->claim_number = $order_number;
        $model->claim_date = Carbon::now();
        if ($request->customer_id == 'other') {
            $model->customer_id = $request->other;
        } else {
            $model->customer_id = $request->customer_id;
        }
        $model->product_id = $request->product_id;
        $model->car_type = $request->car_type;
        $model->plate_number = $request->plate_number;
        $model->e_voltage = $request->e_voltage;
        $model->e_cca = $request->e_cca;
        $model->e_starting = $request->e_starting;
        $model->e_charging = $request->e_charging;
        $model->diagnosa = $request->diagnosa;

        // submit and receive by
        $model->e_submittedBy = Auth::user()->id;

        // file or signature

        if ($request->get('receipt_method') == 'file') {
            $file = $request->file;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("receivedBy/", $nama_file);
            $model->e_receivedBy = $nama_file;
        } else {
            $folderPath = public_path('receivedBy/');
            $image_parts = explode(";base64,", $request->signed);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $image_base64);
            $model->e_receivedBy = $fileName;
        }
        $saved = $model->save();
        $id = $model->id;
        if ($saved) {
            return redirect()->route('claim.index')->with('success', 'Claim has been created');
        } else {
            return redirect()->route('claim.index')->with('error', 'Claim failed to create');
        }
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
        $title = 'Finish Claim';
        $value = ClaimModel::find($id);
        return view('claim.edit', compact('title',  'value'));
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
        $model = ClaimModel::find($id);
        $model->f_voltage = $request->f_voltage;
        $model->f_cca = $request->f_cca;
        $model->f_starting = $request->f_starting;
        $model->f_charging = $request->f_charging;
        $model->result = $request->result;

        // submit and receive by
        $model->f_submittedBy = Auth::user()->id;
        if ($request->get('receipt_method') == 'file') {
            $file = $request->file;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("receivedBy/", $nama_file);
            $model->f_receivedBy = $nama_file;
        } else {
            $folderPath = public_path('receivedBy/');
            $image_parts = explode(";base64,", $request->signed);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $image_base64);
            $model->f_receivedBy = $fileName;
        }
        $model->status = 1;
        $saved = $model->save();
        $id = $model->id;
        if ($saved) {
            return redirect()->route('claim.index')->with('success', 'Claim has been created');
        } else {
            return redirect()->route('claim.index')->with('error', 'Claim failed to create');
        }
    }
    public function historyClaim()
    {
        $title = 'History Claim';
        if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance')) {
            $data = ClaimModel::where('status', 1)->latest()->get();
        } else {
            $data = ClaimModel::where('e_submittedBy', Auth::user()->id)->where('status', 1)->get();
        }


        return view('claim.history_claim', compact('title', 'data'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = ClaimModel::find($id);
        unlink('receivedBy/' . $model->e_receivedBy);
        $deleted = $model->delete();
        if ($deleted) {
            return redirect()->route('claim.index')->with('success', 'Claim has been deleted');
        } else {
            return redirect()->route('claim.index')->with('error', 'Claim failed to delete');
        }
    }
}
