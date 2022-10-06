<?php

namespace App\Http\Controllers;

use App\Models\AccuClaimDetailModel;
use App\Models\AccuClaimModel;
use App\Models\CarBrandModel;
use App\Models\CarTypeModel;
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
        $data = AccuClaimModel::where('status', 0)->latest()->get();
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
        $data = AccuClaimModel::latest()->get();
        $brand = CarBrandModel::all();
        return view('claim.create', compact('title', 'product', 'customer', 'data', 'brand'));
    }
    public function select($id)
    {
        $sub_materials = [];
        $material_id = $id;

        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = CarTypeModel::select("id", "car_type", "id_car_brand")
                ->where('car_type', 'LIKE', "%$search%")
                ->where('id_car_brand', $material_id)
                ->get();
        } else {
            $sub_materials = CarTypeModel::where('id_car_brand', $material_id)->get();
        }
        return response()->json($sub_materials);
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
            // Customer
            'customer_id' => 'required',
            'sub_name' => 'required',
            'sub_phone' => 'required',

            // Product
            'product_id' => 'required',

            // Car
            'car_type_id' => 'required',
            'plate_number' => 'required',

            // Accu
            'e_voltage' => 'required',
            'e_cca' => 'required',
            'e_starting' => 'required',
            'e_charging' => 'required',
            'file' => 'required',
        ]);
        if ($request->parent_material == 'Battery') {

            // get claim number
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            $length = 3;
            $id = intval(AccuClaimModel::where('claim_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
            $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $order_number = 'CLPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

            // save claim
            $model = new AccuClaimModel();
            $model->claim_number = $order_number;
            $model->claim_date = Carbon::now();

            // Customer
            $model->customer_id = $request->customer_id;
            $model->sub_name = $request->sub_name;
            $model->sub_phone = $request->sub_phone;
            $model->plate_number = $request->plate_number;


            // Product
            $model->product_id = $request->product_id;
            $model->material = $request->material;
            $model->type_material = $request->type_material;

            // Car
            $model->car_type_id = $request->car_type_id;
            $model->car_brand_id = $request->car_brand_id;

            // Accu
            $model->e_voltage = $request->e_voltage;
            $model->e_cca = $request->e_cca;
            $model->e_starting = $request->e_starting;
            $model->e_charging = $request->e_charging;

            // submit and receive by
            $model->e_submittedBy = Auth::user()->id;

            // Evidence
            $file = $request->file;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("file_evidence/", $nama_file);
            $model->e_foto = $nama_file;

            // Signature
            $folderPath = public_path('file_signature/');
            $image_parts = explode(";base64,", $request->signed);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $image_base64);
            $model->e_receivedBy = $fileName;
            $saved = $model->save();

            if ($saved) {
                // diagnostic
                $sundays = $request->input('diagnosa');
                $sundaysArray = array();
                foreach ($sundays as $sunday) {
                    $sundaysArray[] = $sunday;

                    $data = new AccuClaimDetailModel();
                    $data->id_accu_claim = $model->id;
                    $data->diagnosa = $sunday;
                    $data->save();
                }
                if ($request->other_diagnosa != null) {
                    $data->diagnosa = $request->other_diagnosa;
                }
                $data->save();

                return redirect()->route('claim.index')->with('success', 'Claim ' . $model->claim_number . ' has been created');
            } else {
                return redirect()->route('claim.index')->with('error', 'Claim failed to create');
            }
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
        $value = AccuClaimModel::find($id);
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
        $model = AccuClaimModel::find($id);
        $model->f_voltage = $request->f_voltage;
        $model->f_cca = $request->f_cca;
        $model->f_starting = $request->f_starting;
        $model->f_charging = $request->f_charging;
        $model->result = $request->result;
        $model->cost = $request->cost;
        $model->date_replaced = Carbon::now();

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
            $data = AccuClaimModel::where('status', 1)->latest()->get();
        } else {
            $data = AccuClaimModel::where('e_submittedBy', Auth::user()->id)->where('status', 1)->get();
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
        $model = AccuClaimModel::find($id);
        unlink('receivedBy/' . $model->e_receivedBy);
        $deleted = $model->delete();
        if ($deleted) {
            return redirect()->route('claim.index')->with('success', 'Claim has been deleted');
        } else {
            return redirect()->route('claim.index')->with('error', 'Claim failed to delete');
        }
    }
}
