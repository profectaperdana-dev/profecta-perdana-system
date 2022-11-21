<?php

namespace App\Http\Controllers;

use App\Models\AccuClaimDetailModel;
use App\Models\AccuClaimModel;
use App\Models\CarBrandModel;
use App\Models\CarTypeModel;
use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\StockMutationDetailModel;
use App\Models\StockMutationModel;
use App\Models\SuppliersModel;
use App\Models\TyreClaimModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Clockwork\Web\Web;
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

    //? CLAIM ACCU
    public function index()
    {
        $title = 'Battery Claim';
        $product = ProductModel::all();
        $customer = CustomerModel::all();
        $data = AccuClaimModel::where('status', 0)->latest()->get();

        return view('claim.index', compact('title', 'product', 'customer', 'data'));
    }

    public function pdfClaimAccu($id)
    {
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $data = AccuClaimModel::find($id);
        $pdf = FacadePdf::loadView('claim.pdf_accu_claims', compact('warehouse', 'data'));
        return $pdf->stream();
    }

    public function pdfClaimAccuFinish($id)
    {
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $data = AccuClaimModel::find($id);
        $pdf = FacadePdf::loadView('claim.pdf_accu_claims_finish', compact('warehouse', 'data'))->setPaper('legal', 'potrait');
        return $pdf->stream("", array("Attachment" => false));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $title = 'Early Checking';
        $product = ProductModel::where('id_material', 4)->get();
        $customer = CustomerModel::all();
        $data = AccuClaimModel::latest()->get();
        $brand = CarBrandModel::all();
        $stock = StockModel::join('warehouses', 'warehouses.id', '=', 'stocks.warehouses_id')
            ->join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
            ->select('stocks.*', 'warehouses.type', 'warehouse_types.name')
            ->where('warehouse_types.name', 'C01')
            ->get();
        return view('claim.create', compact('title', 'product', 'customer', 'data', 'brand', 'stock'));
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


        //* GET CLAIM NUMBER
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
        $order_number = 'BATTERY-CLPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

        //* INSERT CLAIM
        $model = new AccuClaimModel();
        $model->claim_number = $order_number;
        $model->claim_date = Carbon::now();

        //* CUSTOMER
        $model->customer_id = $request->customer_id;
        $model->sub_name = $request->sub_name;
        $model->sub_phone = $request->sub_phone;
        $model->plate_number = strtoupper($request->plate_number);
        $model->email = $request->sub_email;

        //* PRODUCT
        $model->product_id = $request->product_id;
        $model->material = $request->material;
        $model->type_material = $request->type_material;

        //* CAR
        $model->car_type_id = $request->car_type_id;
        $model->car_brand_id = $request->car_brand_id;

        //* ACCU
        $model->e_voltage = $request->e_voltage;
        $model->e_cca = $request->e_cca;
        $model->e_starting = $request->e_starting;
        $model->e_charging = $request->e_charging;

        //* SUBMIT CLAIM
        $model->e_submittedBy = Auth::user()->id;

        //* EVIDENCE
        $file = $request->file;
        $nama_file = time() . '.' . $file->getClientOriginalExtension();
        $file->move("file_evidence/", $nama_file);
        $model->e_foto = $nama_file;

        //* SIGNATURE
        $folderPath = public_path('file_signature/');
        $image_parts = explode(";base64,", $request->signed);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';
        $file = $folderPath . $fileName;
        file_put_contents($file, $image_base64);
        $model->e_receivedBy = $fileName;
        $model->loan_product_id = $request->loan_product_id;
        $saved = $model->save();

        if ($saved) {
            //* UPDATE STOCK
            $stock = StockModel::join('warehouses', 'warehouses.id', '=', 'stocks.warehouses_id')
                ->join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
                ->select('stocks.*', 'warehouses.type', 'warehouse_types.name')
                ->where('warehouse_types.name', 'C01')
                ->where('stocks.products_id', $request->loan_product_id)
                ->first();
            $stock->stock = $stock->stock - 1;
            $stock->save();

            //* INSERT CLAIM DIAGNOSIS
            $sundays = $request->input('diagnosa');
            if ($sundays != null) {
                $sundaysArray = [];
                foreach ($sundays as $sunday) {
                    array_push($sundaysArray, $sunday);
                    $data = new AccuClaimDetailModel();
                    $data->id_accu_claim = $model->id;
                    $data->diagnosa = $sunday;
                    $data->save();
                }
                if ($request->other_diagnosa != null) {
                    $data = new AccuClaimDetailModel();
                    $data->id_accu_claim = $model->id;
                    $data->diagnosa = $request->other_diagnosa;
                    $data->save();
                }
            } else {
                $data = new AccuClaimDetailModel();
                $data->id_accu_claim = $model->id;
                $data->diagnosa = $request->other_diagnosa;
                $data->save();
            }



            return redirect()->route('claim.index')->with('success', 'Claim ' . $model->claim_number . ' has been created');
        } else {
            return redirect()->route('claim.index')->with('error', 'Claim failed to create');
        }

        //* END CLAIM ACCU
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
        $title = 'Finish Claim';
        // $suppliers = SuppliersModel::all();
        $value = AccuClaimModel::find($id);
        // $warehouse = WarehouseModel::all();
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
        // * FINISH CLAIM ACCU
        // $request->validate([
        //     // Accu
        //     'f_voltage' => 'required',
        //     'f_cca' => 'required',
        //     'f_starting' => 'required',
        //     'f_charging' => 'required',
        //     'file' => 'required',
        // ]);

        //* FINISH CLAIM ACCU

        //* CLAIM DATA
        // dd($id);
        $model = AccuClaimModel::where('id', $id)->first();
        $model->f_voltage = $request->f_voltage;
        $model->f_cca = $request->f_cca;
        $model->f_starting = $request->f_starting;
        $model->f_charging = $request->f_charging;

        //* UPDATE CLAIM DIAGNOSIS
        $diagnosa = $request->input('diagnosa');
        $diagnosaArray = [];
        foreach ($diagnosa as $value) {
            array_push($diagnosaArray, $value);
        }
        if ($request->other_diagnosa != null) {
            array_push($diagnosaArray, $request->other_diagnosa);
            $data = new  AccuClaimDetailModel();
            $data->id_accu_claim = $model->id;
            $data->diagnosa = $request->other_diagnosa;
            $data->save();
        }
        $data =  AccuClaimDetailModel::where('id_accu_claim', $id)->get();
        foreach ($data as $value) {
            if (!in_array($value->diagnosa, $diagnosaArray)) {
                $value->delete();
            }
        }



        // * EVIDENCE RECEIVED
        $file = $request->file;
        $nama_file = time() . '.' . $file->getClientOriginalExtension();
        $file->move("file_evidence/", $nama_file);
        $model->f_foto = $nama_file;

        //* SIGNATURE RECEIVED
        $folderPath = public_path('file_signature/');
        $image_parts = explode(";base64,", $request->signed);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';
        $file = $folderPath . $fileName;
        file_put_contents($file, $image_base64);
        $model->f_receivedBy = $fileName;

        // * DATE REPLACEMENT & RESULT
        $model->result = $request->result;
        $model->cost = $request->cost;
        $model->date_replaced = Carbon::now();
        $model->status = 1;

        //* UPDATE STOCK LOAN
        $stock_loan = StockModel::join('warehouses', 'warehouses.id', '=', 'stocks.warehouses_id')
            ->join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
            ->select('stocks.*', 'warehouses.type', 'warehouse_types.name')
            ->where('warehouse_types.name', 'C01')
            ->where('stocks.products_id', $model->loan_product_id)
            ->first();
        $stock_loan->stock = $stock_loan->stock + 1;
        $stock_loan->save();


        //* UPDATE CLAIM ACCU
        $saved = $model->save();
        if ($saved) {
            return redirect()->route('claim.index')->with('success', 'Claim has been created');
        } else {
            return redirect()->route('claim.index')->with('error', 'Claim failed to create');
        }
    }
    public function historyClaim()
    {
        $title = 'History Claim';
        $suppliers = SuppliersModel::all();
        $warehouse = WarehouseModel::join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
            ->select('warehouses.*', 'warehouse_types.name')
            ->where('warehouse_types.name', 'C02')
            ->orWhere('warehouse_types.name', 'C03')
            ->get();
        if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isTeknisi')) {
            $data = AccuClaimModel::where('status', 1)->latest()->get();
        } else {
            $data = AccuClaimModel::where('e_submittedBy', Auth::user()->id)->where('status', 1)->latest()->get();
        }
        return view('claim.history_claim', compact('title', 'data', 'suppliers', 'warehouse'));
    }
    public function mutasiClaim(Request $request, $id)
    {
        $model = AccuClaimModel::where('id', $id)->first();
        if ($request->result == 'CP03 - Waranty Accepted') {
            $mutasi = new StockMutationModel();
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            $length = 3;
            $id = intval(StockMutationModel::where('mutation_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
            $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $mutation_number = 'SMPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
            $mutasi->mutation_number = $mutation_number;
            $mutasi->mutation_date = Carbon::now()->format('Y-m-d');
            $mutasi->from = Auth::user()->warehouse_id;
            $mutasi->to = $request->to;
            $mutasi->remark = 'CP03 - Waranty Accepted ' . $mutasi->fromWarehouse->warehouses . ' to ' . $mutasi->toWarehouse->warehouses;
            $mutasi->created_by = Auth::user()->id;
            $mutasi->save();

            $mutasi_detail = new StockMutationDetailModel();
            $mutasi_detail->mutation_id  = $mutasi->id;
            $mutasi_detail->product_id = $model->product_id;
            $mutasi_detail->qty = 1;
            $mutasi_detail->save();
            // dd($model->product_id);
            // dd(Auth::user()->warehouse_id);
            //* UPDATE STOCK


            $stock = StockModel::where('warehouses_id', Auth::user()->warehouse_id)->where('products_id', $model->product_id)->first();
            // dd($stock);
            if ($stock != null) {
                $stock->stock = $stock->stock - 1;
                $stock->save();
            } else {
                return redirect()->route('claim.index')->with('error', 'Stock not found');
            }

            // * UPDATE STOCK MUTATION
            $stock_mutasi = StockModel::where('warehouses_id', $mutasi->to)->where('products_id', $model->product_id)->first();
            if ($stock_mutasi == null) {
                $stock_mutasi = new StockModel();
                $stock_mutasi->warehouses_id = $mutasi->to;
                $stock_mutasi->products_id = $model->product_id;
                $stock_mutasi->stock = 1;
                $stock_mutasi->save();
            } else {
                $stock_mutasi->stock = $stock_mutasi->stock + 1;
                $stock_mutasi->save();
            }
        } elseif ($request->result == 'CP04 - Good Will') {
            $mutasi = new StockMutationModel();
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            $length = 3;
            $id = intval(StockMutationModel::where('mutation_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
            $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $mutation_number = 'SMPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
            $mutasi->mutation_number = $mutation_number;
            $mutasi->mutation_date = Carbon::now()->format('Y-m-d');
            $mutasi->from = Auth::user()->warehouse_id;
            $mutasi->to = (int) $request->to_warehouse;
            // dd($mutasi->from);
            // dd($mutasi->to);
            $mutasi->remark = 'CP04 - Good Will ' . $mutasi->fromWarehouse->warehouses . ' to ' . $mutasi->toWarehouse->warehouses;
            $mutasi->created_by = Auth::user()->id;
            $mutasi->save();

            $mutasi_detail = new StockMutationDetailModel();
            $mutasi_detail->mutation_id  = $mutasi->id;
            $mutasi_detail->product_id = $model->product_id;
            $mutasi_detail->qty = 1;
            $mutasi_detail->save();
            // dd($model->product_id);
            // dd(Auth::user()->warehouse_id);
            //* UPDATE STOCK
            $stock = StockModel::where('warehouses_id', Auth::user()->warehouse_id)->where('products_id', $model->product_id)->first();
            // dd($stock);
            $stock->stock = $stock->stock - 1;
            $stock->save();

            // * UPDATE STOCK MUTATION
            $stock_mutasi = StockModel::where('warehouses_id', $mutasi->to)->where('products_id', $model->product_id)->first();
            if ($stock_mutasi == null) {
                $stock_mutasi = new StockModel();
                $stock_mutasi->warehouses_id = $mutasi->to;
                $stock_mutasi->products_id = $model->product_id;
                $stock_mutasi->stock = 1;
                $stock_mutasi->save();
            } else {
                $stock_mutasi->stock = $stock_mutasi->stock + 1;
                $stock_mutasi->save();
            }
        }
        $model->result = $request->result;
        $saved = $model->save();
        if ($saved) {
            return redirect('history_claim')->with('success', 'Claim has been updated');
        } else {
            return redirect('history_claim')->with('error', 'Claim failed to update');
        }
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
        unlink('file_evidence/' . $model->e_foto);
        unlink('file_evidence/' . $model->e_receivedBy);
        unlink('file_signature/' . $model->f_foto);
        unlink('file_signature/' . $model->f_receivedBy);
        $deleted = $model->delete();
        if ($deleted) {
            return redirect()->route('claim.index')->with('success', 'Claim has been deleted');
        } else {
            return redirect()->route('claim.index')->with('error', 'Claim failed to delete');
        }
    }

    //? CLAIMS TYRE

    public function indexTyre()
    {
        $title = 'Tyre Claim';
        if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isTeknisi')) {
            $data = TyreClaimModel::where('status', 0)->latest()->get();
        } else {
            $data = TyreClaimModel::where('e_submittedBy', Auth::user()->id)->where('status', 0)->latest()->get();
        }
        return view('claim.index_tyre', compact('title', 'data'));
    }

    public function createTyre()
    {
        $title = 'Create Tyre Claim';
        $customer = CustomerModel::all();
        $brand = CarBrandModel::all();
        $product = ProductModel::where('id_material', 18)->get();
        // $warehouse = WarehouseModel::where('type', 2)->get();
        return view('claim.create_tyre', compact('title', 'product', 'customer', 'brand'));
    }

    public function storeTyre(Request $request)
    {
        $model = new TyreClaimModel();

        //* GET CLAIM NUMBER
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
        $order_number = 'TYRE-CLPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

        //* insert data
        $model->claim_number = $order_number;
        $model->claim_date = Carbon::now();
        $model->customer_id = $request->customer_id;
        $model->sub_name = $request->sub_name;
        $model->sub_phone = $request->sub_phone;
        $model->email = $request->sub_email;
        $model->product_id = $request->product_id;
        $model->material = $request->material;
        $model->type_material = $request->type_material;
        $model->plate_number = strtoupper($request->plate_number);
        //* CAR
        $model->car_type_id = $request->car_type_id;
        $model->car_brand_id = $request->car_brand_id;

        //* Tyre Claim
        $model->application = $request->application;
        $model->dot = $request->dot;
        $model->serial_number = $request->serial_number;
        $model->rtd1 = $request->rtd1;
        $model->rtd2 = $request->rtd2;
        $model->rtd3 = $request->rtd3;
        $model->complaint_area = $request->complaint_area;
        $model->reason = $request->reason;


        // * EVIDENCE SUBMITTED
        $file = $request->file;
        $nama_file = time() . '.' . $file->getClientOriginalExtension();
        $file->move("file_evidence/", $nama_file);
        $model->e_foto = $nama_file;

        // * SIGNATURE SUBMITTED
        $folderPath = public_path('file_signature/');
        $image_parts = explode(";base64,", $request->signed);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.png';
        $file = $folderPath . $fileName;
        file_put_contents($file, $image_base64);
        $model->e_signature = $fileName;
        $model->created_by = Auth::user()->id;
        $saved = $model->save();
        if ($saved) {
            return redirect('claim_tyre')->with('success', 'Claim has been created');
        } else {
            return redirect('claim_tyre')->with('error', 'Claim failed to create');
        }
    }

    public function delTyre($id)
    {
        $model = TyreClaimModel::find($id);
        unlink('file_evidence/' . $model->e_foto);
        unlink('file_signature/' . $model->e_signature);
        $deleted = $model->delete();
        if ($deleted) {
            return redirect('claim_tyre')->with('success', 'Claim has been deleted');
        } else {
            return redirect('claim_tyre')->with('error', 'Claim failed to delete');
        }
    }
}
