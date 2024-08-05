<?php

namespace App\Http\Controllers;

use App\Models\AccuClaimDetailModel;
use App\Models\AccuClaimModel;
use App\Models\CarBrandModel;
use App\Models\CarTypeModel;
use App\Models\ClaimCreditModel;
use App\Models\CustomerModel;
use App\Models\MotorBrandModel;
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
use Illuminate\Support\Facades\DB;


class ClaimController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $customer;
    private $car_brand;
    private $motor_brand;
    public function __construct($customer = null, $car_brand = null, $motor_brand = null)
    {
        $customer = CustomerModel::select('id', 'name_cust', 'code_cust')
            ->orderBy('code_cust', 'ASC')
            ->orderBy('name_cust', 'ASC')
            ->get();
        $car_brand = CarBrandModel::all();
        $motor_brand = MotorBrandModel::all();
        $this->car_brand = $car_brand;
        $this->motor_brand = $motor_brand;
        $this->customer = $customer;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AccuClaimModel::select('id', 'claim_number', 'claim_date', 'customer_id', 'sub_name', 'sub_phone', 'email', 'cost', 'status', 'alamat')
                ->where('status', 0)
                ->where('e_receivedBy', null)
                ->latest()
                ->get();
            return datatables()->of($data)
                ->editColumn('claim_date', function ($data) {
                    return date('d-m-Y', strtotime($data->claim_date));
                })
                ->editColumn('customer_id', function ($data) {
                    if (is_numeric($data->customer_id)) {
                        return $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust;
                    } else {
                        return $data->customer_id;
                    }
                })
                ->addColumn('claim_number', fn ($data) => view(
                    'claim._option_prior',
                    ['data' => $data, 'customer' => $this->customer, 'car_brand' => $this->car_brand, 'motor_brand' => $this->motor_brand],

                )->render())
                ->rawColumns(['claim_number'])
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'Prior Checking',


        ];
        return view('claim.index', $datas);
    }

    public function createInitialClaim()
    {
        $data = [
            'customer' => $this->customer,
            'title' => 'Create Initial Claim',
        ];
        return view('claim.create_claim', $data);
    }

    public function storeInitialClaim(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = new AccuClaimModel();
            $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $user_warehouse[0]->id)
                ->first();
            $lastRecord = AccuClaimModel::where('warehouse_id', $user_warehouse[0]->id)->latest()->first();
            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->claim_date)->format('m');
                $currentMonth = Carbon::now()->format('m');
                if ($lastRecordMonth != $currentMonth) {
                    $cust_number_id = 1;
                    $data->id_sort = $cust_number_id;
                } else {
                    $cust_number_id = intval($lastRecord->id_sort) + 1;
                    $data->id_sort = $cust_number_id;
                }
            } else {
                $cust_number_id = 1;
                $data->id_sort = $cust_number_id;
            }
            $length = 3;
            $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y');
            $month = Carbon::now()->format('m');
            $tahun = substr($year, -2);
            $order_number = 'CLPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

            $data->claim_number = $order_number;
            $data->claim_date = date('Y-m-d');
            $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();
            $data->warehouse_id = $user_warehouse[0]->id;;
            $data->customer_id = $request->input('customer_id');
            $data->sub_name = ucwords($request->input('sub_name'));
            $data->sub_phone = $request->input('sub_phone');
            $data->email = $request->input('email');
            $data->alamat = $request->input('alamat');

            $data->status = 0;
            if ($data->save()) {

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ]);
        }
    }

    public function updateInitialClaim(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = AccuClaimModel::findOrFail($id);
            $data->customer_id = $request->input('customer_id');
            $data->sub_name = ucwords($request->input('sub_name'));
            $data->sub_phone = $request->input('sub_phone');
            $data->email = $request->input('email');
            $data->car_brand_id = $request->input('car_brand_id');
            $data->car_type_id = $request->input('car_type_id');
            $data->motor_brand_id = $request->input('motor_brand_id');
            $data->motor_type_id = $request->input('motor_type_id');
            $data->other_machine = $request->input('other_machine');
            $data->plate_number = strtoupper($request->input('plate_number'));
            if ($data->save()) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ]);
        }
    }
    public function create($id)
    {
        $warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $datas = [
            'title' => 'Prior Checking',
            'product' => ProductModel::where('id_material', 4)->get(),
            'customer' => CustomerModel::all(),
            'data' => AccuClaimModel::find($id),
            'car_brands' => CarBrandModel::all(),
            'motor_brands' => MotorBrandModel::all(),
            'user_warehouse' => WarehouseModel::where('type', 1)->whereIn('id_area', array_column($warehouse->toArray(), 'id_area'))->get(),
        ];
        return view('claim.create', $datas);
    }
    public function storeClaimPrior(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = AccuClaimModel::findOrFail($id);
            $data->product_id = $request->product_id;
            $data->product_code = strtoupper($request->product_code);
            $data->cost = $request->input('cost');
            $data->e_voltage = $request->e_voltage;
            $data->e_cca = $request->e_cca;
            $data->e_starting = $request->e_starting;
            $data->e_charging = $request->e_charging;

            $sundays = $request->diagnosa;
            if ($sundays != null) {
                $sundaysArray = [];
                foreach ($sundays as $sunday) {
                    array_push($sundaysArray, $sunday);
                    $detail = new AccuClaimDetailModel();
                    $detail->id_accu_claim = $data->id;
                    $detail->diagnosa = $sunday;
                    $detail->save();
                }
                if ($request->other_diagnosa != null) {
                    $detail = new AccuClaimDetailModel();
                    $detail->id_accu_claim = $data->id;
                    $detail->diagnosa = $request->other_diagnosa;
                    $detail->save();
                }
            } else {
                $detail = new AccuClaimDetailModel();
                $detail->id_accu_claim = $data->id;
                $detail->diagnosa = $request->other_diagnosa;
                $detail->save();
            }
            $data->loan_warehouses = $request->loan_warehouses;
            $data->loan_product_id = $request->loan_product_id;

            //* EVIDENCE
            $file = $request->gambar;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("file_evidence/", $nama_file);
            $data->e_foto = $nama_file;

            $folderPath = 'file_signature/';
            $image_parts = explode(";base64,", $request->signed);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $image_base64);
            $data->e_receivedBy = $fileName;
            $data->e_submittedBy =  Auth::user()->id;

            if ($data->save()) {
                if ($data->loan_product_id != '') {
                    $stock = StockModel::join('warehouses', 'warehouses.id', '=', 'stocks.warehouses_id')
                        ->join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
                        ->select('stocks.*', 'warehouses.type', 'warehouse_types.name')
                        ->where('warehouse_types.name', 'C01')
                        ->where('warehouses.id', $data->loan_warehouses)
                        ->where('stocks.products_id', $data->loan_product_id)
                        ->first();
                    $stock->stock = $stock->stock - 1;
                    if($stock->stock < 0){
                         DB::rollback();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Stock not enough!'
                        ]);
                    }
                    $stock->save();
                }
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'success',
                'message' => 'Data has been failed to saved.'
            ]);
        }
    }
    public function checkFinalClaim(Request $request)
    {
        if ($request->ajax()) {
            $data = AccuClaimModel::where('status', 0)
                ->where('e_receivedBy', '!=', null)
                ->latest()
                ->get();
            return datatables()->of($data)
                ->editColumn('claim_date', function ($data) {
                    return date('d-m-Y', strtotime($data->claim_date));
                })
                ->editColumn('customer_id', function ($data) {
                    if (is_numeric($data->customer_id)) {
                        return $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust;
                    } else {
                        return $data->customer_id;
                    }
                })
                ->editColumn('cost', function ($data) {
                    return 'Rp ' . number_format($data->cost);
                })
                ->editColumn('product_id', function ($data) {
                    return  $data->productSales->sub_materials->nama_sub_material . ' ' . $data->productSales->sub_types->type_name . ' ' . $data->productSales->nama_barang;
                })
                ->addColumn('claim_number', fn ($data) => view(
                    'claim._option_final',
                    ['data' => $data, 'customer' => $this->customer, 'car_brand' => $this->car_brand, 'motor_brand' => $this->motor_brand],

                )->render())
                ->rawColumns(['claim_number'])
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'Final Checking ',
        ];
        return view('claim.final_check', $datas);
    }
    public function createFinalClaim($id)
    {
        $title = 'Finish Claim';
        $value = AccuClaimModel::find($id);
        return view('claim.edit', compact('title',  'value'));
    }
    public function storeFinalClaim(Request $request, $id)
    {
        try {
            // dd($request->all());
            DB::beginTransaction();
            $model = AccuClaimModel::find($id);
            $model->f_voltage = $request->f_voltage;
            $model->f_cca = $request->f_cca;
            $model->f_starting = $request->f_starting;
            $model->f_charging = $request->f_charging;

            //* UPDATE CLAIM DIAGNOSIS
            $data =  AccuClaimDetailModel::where('id_accu_claim', $id)->get();
            foreach ($data as $key => $item) {
                $item->delete();
            }
            if ($request->input('diagnosa') != null) {
                $diagnosa = $request->input('diagnosa');
                $diagnosaArray = [];
                foreach ($diagnosa as $value) {
                    array_push($diagnosaArray, $value);
                    $data = new  AccuClaimDetailModel();
                    $data->id_accu_claim = $model->id;
                    $data->diagnosa = $value;
                    $data->save();
                }
            }
            if ($request->other_diagnosa != null) {
                $data = new  AccuClaimDetailModel();
                $data->id_accu_claim = $model->id;
                $data->diagnosa = $request->other_diagnosa;
                $data->save();
            }
            // * EVIDENCE RECEIVED
            $file = $request->file;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("file_evidence/", $nama_file);
            $model->f_foto = $nama_file;

            //* SIGNATURE RECEIVED
            $folderPath = 'file_signature/';
            $image_parts = explode(";base64,", $request->signed);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = uniqid() . '.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $image_base64);
            $model->f_receivedBy = $fileName;

            // * DATE REPLACEMENT & RESULT
            $model->date_replaced = Carbon::now();
            $model->status = 1;

            //* UPDATE STOCK LOAN
            if ($model->loan_product_id != '') {
                $stock_loan = StockModel::join('warehouses', 'warehouses.id', '=', 'stocks.warehouses_id')
                    ->join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
                    ->select('stocks.*', 'warehouses.type AS type', 'warehouse_types.name')
                    ->where('type', 1)
                    ->where('warehouses.id', $model->loan_warehouses)
                    ->where('stocks.products_id', $model->loan_product_id)
                    ->first();
                $stock_loan->stock = $stock_loan->stock + 1;
                $stock_loan->save();
            }

            //* UPDATE CLAIM ACCU
            if ($model->save()) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('claim/final/check')->with('error', 'Claim failed to create');
        }
    }
    public function historyClaim(Request $request)
    {
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();
        $warehouse_vendor = WarehouseModel::join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
            ->select('warehouses.*', 'warehouse_types.name')
            ->where('warehouses.type', 6)
            ->whereIn('id_area', array_column($user_warehouse->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();
        $warehouse_damaged = WarehouseModel::join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
            ->select('warehouses.*', 'warehouse_types.name')
            ->whereIn('warehouses.type', [2, 3])
            ->whereIn('id_area', array_column($user_warehouse->toArray(), 'id_area'))
            ->oldest('warehouses')
            ->get();
        if ($request->ajax()) {
            $data = AccuClaimModel::with('customerBy', 'productSales', 'loanBy', 'createdBy', 'carBrandBy', 'carTypeBy', 'accuClaimDetailsBy', 'motorBrandBy', 'motorTypeBy')
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    $fromDate = $fromDate ?: date('Y-m-01');
                    $endDate = $request->to_date ?: date('Y-m-t');
                    return $query->whereBetween('date_replaced', [$fromDate, $endDate]);
                })
                ->when($request->status, function ($query) use ($request) {
                    return $request->status == '1' ? $query->where('result', '!=', null) : $query->where('result', null);
                }, function ($query) {
                    return $query->where('result', null);
                })
                ->where('status', 1)
                ->latest('created_at')
                ->get();

            return datatables()->of($data)
                ->editColumn('customer', fn ($data) => is_numeric($data->customer_id) ? $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust . ' : ' . $data->sub_name : $data->customer_id . ' / ' . $data->sub_name)
                ->editColumn('product', fn ($data) => $data->productSales->sub_materials->nama_sub_material . ' ' .  $data->productSales->sub_types->type_name . ' ' . $data->productSales->nama_barang . ' : ' .  $data->product_code)
                ->editColumn('duration', fn ($data) => date('d F Y H:i', strtotime($data->created_at)) . ' - ' . date('d F Y H:i', strtotime($data->updated_at)))
                ->addColumn('action', fn ($data) => view('claim._option', ['data' => $data, 'user_warehouse' => $user_warehouse, 'warehouse_vendor' => $warehouse_vendor, 'warehouse_damaged' => $warehouse_damaged])->render())
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'History Claim',

        ];
        return view('claim.history_claim', $datas);
    }
    public function mutasiClaim(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $model = AccuClaimModel::where('id', $id)->first();
            if ($request->result == 'CP03 - Waranty Accepted') {
                $mutasi = new StockMutationModel();
                $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                    ->select('customer_areas.area_code', 'warehouses.id')
                    ->where('warehouses.id', $model->warehouse_id)
                    ->first();
                // $lastRecord = StockMutationModel::with('fromWarehouse')->whereHas('fromWarehouse', function($query) use ($kode_area){
                // $query->where('id_area', $kode_area->id_area);
                //     })->latest()->first();
                //     // $lastRecord = null;
                $lastRecord = StockMutationModel::where('from', $model->warehouse_id)->latest()->first();

                if ($lastRecord) {
                    $lastRecordMonth = Carbon::parse($lastRecord->mutation_date)->format('m');
                    $currentMonth = Carbon::now()->format('m');

                    if ($lastRecordMonth != $currentMonth) {
                        // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                        $cust_number_id = 1;
                        $mutasi->id_sort = $cust_number_id;
                    } else {
                        // Jika masih dalam bulan yang sama, increment $cust_number_id
                        $cust_number_id = intval($lastRecord->id_sort) + 1;
                        $mutasi->id_sort = $cust_number_id;
                    }
                } else {
                    // Jika belum ada record sebelumnya, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $mutasi->id_sort = $cust_number_id;
                }
                $length = 3;
                $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
                $year = Carbon::now()->format('Y'); // 2022
                $month = Carbon::now()->format('m'); // 2022
                $tahun = substr($year, -2);
                $mutation_number = 'SMPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
                $mutasi->mutation_number = $mutation_number;
                $mutasi->mutation_date = Carbon::now()->format('Y-m-d');
                $mutasi->from = $model->warehouse_id;
                $mutasi->to = $request->to_vendor;
                $mutasi->isapprove = 1;
                $mutasi->isreceived = 1;
                $mutasi->remark = 'CP03 - Waranty Accepted ' . $mutasi->fromWarehouse->warehouses . ' to ' . $mutasi->toWarehouse->warehouses;
                $mutasi->created_by = Auth::user()->id;
                $mutasi->save();
                $mutasi_detail = new StockMutationDetailModel();
                $mutasi_detail->mutation_id  = $mutasi->id;
                $mutasi_detail->product_id = $model->product_id;
                $mutasi_detail->qty = 1;
                $mutasi_detail->save();
                $stock = StockModel::where('warehouses_id', $model->warehouse_id)->where('products_id', $model->product_id)->first();
                if ($stock != null) {
                    $stock->stock = $stock->stock - 1;
                    if($stock->stock < 0){
                         DB::rollback();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Stock not enough!'
                        ]);
                    }
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
                $model->mutation_number = $mutasi->mutation_number;
                $model->cost = 0;
            } elseif ($request->result == 'CP04 - Good Will') {
                $mutasi = new StockMutationModel();
                $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                    ->select('customer_areas.area_code', 'warehouses.id')
                    ->where('warehouses.id', $model->warehouse_id)
                    ->first();
                // $lastRecord = StockMutationModel::with('fromWarehouse')->whereHas('fromWarehouse', function($query) use ($kode_area){
                // $query->where('id_area', $kode_area->id_area);
                //     })->latest()->first();
                //     // $lastRecord = null;
                $lastRecord = StockMutationModel::where('from', $model->warehouse_id)->latest()->first();


                if ($lastRecord) {
                    $lastRecordMonth = Carbon::parse($lastRecord->mutation_date)->format('m');
                    $currentMonth = Carbon::now()->format('m');

                    if ($lastRecordMonth != $currentMonth) {
                        // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                        $cust_number_id = 1;
                        $mutasi->id_sort = $cust_number_id;
                    } else {
                        // Jika masih dalam bulan yang sama, increment $cust_number_id
                        $cust_number_id = intval($lastRecord->id_sort) + 1;
                        $mutasi->id_sort = $cust_number_id;
                    }
                } else {
                    // Jika belum ada record sebelumnya, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $mutasi->id_sort = $cust_number_id;
                }
                $length = 3;
                $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
                $year = Carbon::now()->format('Y'); // 2022
                $month = Carbon::now()->format('m'); // 2022
                $tahun = substr($year, -2);
                $mutation_number = 'SMPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
                $mutasi->mutation_number = $mutation_number;
                $mutasi->mutation_date = Carbon::now()->format('Y-m-d');
                $mutasi->from = $model->warehouse_id;
                $mutasi->to = (int) $request->to_warehouse;
                // dd($mutasi->from);
                // dd($mutasi->to);
                $mutasi->isapprove = 1;
                $mutasi->isreceived = 1;
                $mutasi->remark = 'CP04 - Good Will ' . $mutasi->fromWarehouse->warehouses . ' to ' . $mutasi->toWarehouse->warehouses;
                $mutasi->created_by = Auth::user()->id;
                $mutasi->save();

                $mutasi_detail = new StockMutationDetailModel();
                $mutasi_detail->mutation_id  = $mutasi->id;
                $mutasi_detail->product_id = $model->product_id;
                $mutasi_detail->qty = 1;
                $mutasi_detail->note  = $model->product_code;
                $mutasi_detail->save();
                // dd($model->product_id);
                // dd(Auth::user()->warehouse_id);
                //* UPDATE STOCK
                $stock = StockModel::where('warehouses_id', $model->warehouse_id)->where('products_id', $model->product_id)->first();
                if ($stock->stock < 1) {
                    DB::rollback();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Stock not enough!'
                    ]);
                } else {
                    // dd($stock);
                    $stock->stock = $stock->stock - 1;
                    $stock->save();
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
                $model->mutation_number = $mutasi->mutation_number;
                $model->cost = 0;
            }
            $model->result = $request->result;
            $saved = $model->save();
            if ($saved) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data has been fail to saved.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Data has been failed to saved.'
            ]);
        }
    }
    public function settlement(Request $request)
    {
        if ($request->ajax()) {
            $data =  AccuClaimModel::with('customerBy', 'productSales', 'loanBy', 'createdBy', 'carBrandBy', 'carTypeBy', 'accuClaimDetailsBy', 'motorBrandBy', 'motorTypeBy')
                ->where('result', '!=', null)
                ->where('cost', '>', 0)
                ->latest('created_at')
                ->get();
            return datatables()->of($data)
                ->editColumn('cost', fn ($data) =>  number_format($data->cost))
                ->editColumn('customer', fn ($data) => is_numeric($data->customer_id) ? $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust  : $data->customer_id)
                ->editColumn('product', fn ($data) => $data->productSales->sub_materials->nama_sub_material . ' ' .  $data->productSales->sub_types->type_name . ' ' . $data->productSales->nama_barang . ' : ' .  $data->product_code)
                ->editColumn('duration', fn ($data) => date('d F Y H:i', strtotime($data->created_at)) . ' - ' . date('d F Y H:i', strtotime($data->updated_at)))
                ->addColumn('action', fn ($data) => view('claim._option_settlement', ['data' => $data])->render())
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'Settlement Claim',

        ];
        return view('claim.settlement', $datas);
    }

    public function updateSettlement(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = AccuClaimModel::findOrFail($id);
            $data->save();

            $claim_credit = new ClaimCreditModel();
            $claim_credit->id_claim = $data->id;
            $claim_credit->payment_method = $request->input('payment_method');
            $this_time = date('H:i:s');
            $claim_credit->payment_date = date('Y-m-d H:i:s', strtotime($request->input('payment_date') . $this_time));
            $claim_credit->amount = $request->input('amount');
            $claim_credit->updated_by = Auth::user()->id;


            if ($data->save() && $claim_credit->save()) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ]);
        }
    }

    public function listClaim(Request $request)
    {
        if ($request->ajax()) {
            if ($request->from_date != '' && $request->to_date != '') {
                $data = AccuClaimModel::join('customers', 'customers.id', '=', 'accu_claims.customer_id')
                    ->selectRaw('count(*) as qty, customers.name_cust as name,customers.code_cust as code')
                    ->whereBetween('accu_claims.claim_date', [$request->from_date, $request->to_date])
                    ->where('accu_claims.result', '!=', null)
                    ->groupBy('accu_claims.customer_id')
                    ->latest('accu_claims.customer_id')
                    ->get();
            } else {
                $data = AccuClaimModel::join('customers', 'customers.id', '=', 'accu_claims.customer_id')
                    ->selectRaw('count(*) as qty, customers.name_cust as name,customers.code_cust as code')
                    ->where('accu_claims.result', '!=', null)
                    ->groupBy('accu_claims.customer_id')
                    ->latest('accu_claims.customer_id')
                    ->get();
            }

            return datatables()->of($data)
                ->editColumn('qty', function ($data) {
                    return $data->qty;
                })
                ->editColumn('name', function ($data) {
                    return $data->code . ' - ' . $data->name;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'List Claim',
        ];

        return view('claim.list_claim', $data);
    }
    public function pdfClaimAccu($id)
    {
        $data = AccuClaimModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
        $pdf = FacadePdf::loadView('claim.pdf_accu_claims', compact('warehouse', 'data'))->setPaper('B5', 'potrait');
        return $pdf->stream();
    }

    public function pdfClaimAccuFinish($id)
    {
        $data = AccuClaimModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
        $pdf = FacadePdf::loadView('claim.pdf_accu_claims_finish', compact('warehouse', 'data'))->setPaper('B5', 'potrait');
        return $pdf->stream("", array("Attachment" => false));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function selectProduct()
    {
        $warehouse = request()->w;
        if (request()->has('q')) {
            $search = request()->q;
            $stock = StockModel::join('products', 'products.id', '=', 'stocks.products_id')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                ->join('warehouses', 'warehouses.id', '=', 'stocks.warehouses_id')
                ->join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
                ->select('stocks.*', 'warehouses.type AS type', 'warehouse_types.name', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                ->where('type_name', 'LIKE', "%$search%")
                ->where('type', 1)
                ->where('stocks.warehouses_id', $warehouse)
                ->orWhere('nama_barang', 'LIKE', "%$search%")
                ->where('type', 1)
                ->where('stocks.warehouses_id', $warehouse)
                ->orWhere('nama_sub_material', 'LIKE', "%$search%")
                ->where('type', 1)
                ->where('stocks.warehouses_id', $warehouse)
                ->get();
        } else {
            $stock = StockModel::join('products', 'products.id', '=', 'stocks.products_id')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                ->join('warehouses', 'warehouses.id', '=', 'stocks.warehouses_id')
                ->join('warehouse_types', 'warehouse_types.id', '=', 'warehouses.type')
                ->select('stocks.*', 'warehouses.type AS type', 'warehouse_types.name', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                ->where('type', 1)
                ->where('stocks.warehouses_id', $warehouse)
                ->where('stocks.stock', '>', 0)
                ->oldest('nama_sub_material')
                ->oldest('type_name')
                ->oldest('nama_barang')
                ->get();
        }
        return response()->json($stock);
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



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = AccuClaimModel::find($id);
        // unlink('file_evidence/' . $model->e_foto);
        // unlink('file_evidence/' . $model->e_receivedBy);
        // unlink('file_signature/' . $model->f_foto);
        // unlink('file_signature/' . $model->f_receivedBy);
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
        $data = TyreClaimModel::where('e_submittedBy', Auth::user()->id)->where('status', 0)->latest()->get();

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
