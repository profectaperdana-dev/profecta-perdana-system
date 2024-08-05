<?php

namespace App\Http\Controllers;

use App\Events\ApprovalMessage;
use App\Models\CarBrandModel;
use App\Models\CustomerModel;
use App\Models\DirectSalesCodesModel;
use App\Models\DirectSalesCreditModel;
use App\Models\DirectSalesDetailModel;
use App\Models\DirectSalesModel;
use App\Models\DistrictModel;
use App\Models\Finance\Coa;
use App\Models\Finance\Journal;
use App\Models\Finance\JournalDetail;
use App\Models\JurnalDetailModel;
use App\Models\JurnalModel;
use App\Models\MotorBrandModel;
use App\Models\NotificationsModel;
use App\Models\ProductCostModel;
use App\Models\ProductModel;
use App\Models\ReturnRetailDetailModel;
use App\Models\ReturnRetailModel;
use App\Models\StockModel;
use App\Models\SubMaterialModel;
use App\Models\UserWarehouseModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use App\Models\TyreDotModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use stdClass;
use function App\Helpers\changeSaldoTambah;
use function App\Helpers\changeSaldoKurang;
use function App\Helpers\checkOverDueByCustomer;
use function App\Helpers\checkOverPlafone;
use function App\Helpers\createJournal;
use function App\Helpers\createJournalDetail;
use Yajra\DataTables\Facades\DataTables;

// use function App\Helpers\checkOverDueByCustomer;
// use function App\Helpers\checkOverPlafone;

class DirectSalesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
                $direct = DirectSalesModel::with('createdBy', 'carBrandBy', 'carTypeBy', 'motorBrandBy', 'motorTypeBy', 'directSalesDetailBy', 'directSalesDetailBy.productBy', 'directSalesDetailBy.retailPriceBy')
                    ->whereIn('warehouse_id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
                    ->when($request->from_date, function ($query) use ($request) {
                        return $query->whereBetween('order_date', [$request->from_date, $request->to_date]);
                    }, function ($query) use ($request) {
                        if($request->filter == "this_month"){
                            // Mendapatkan tanggal awal bulan ini
                            $firstDayOfMonth = date("Y-m-01");
                            
                            // Mendapatkan tanggal akhir bulan ini
                            $lastDayOfMonth = date("Y-m-t");
                            
                            return empty($query->from_date) ? $query->whereBetween('order_date', [$firstDayOfMonth, $lastDayOfMonth]) : $query;
                        }else{
                            $today = date('Y-m-d');
                            //  dd("hey");
                            return empty($query->from_date) ? $query->whereDate('order_date', $today) : $query;
                        }
                         
                    })
                    ->where('isapproved', 1)
                    ->latest()
                    ;
            
            return Datatables::eloquent($direct)

                // ->editColumn('order_number', function ($data) { 
                //     return  view('direct_sales._order_number', compact('data'))->render();
                // })
                ->editColumn('order_date', function ($data) {
                    return date('d F Y', strtotime($data->order_date));
                })
                ->editColumn('car_brand_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->car_brand_id == null) {
                        return '-';
                    } else return $directSalesModel->car_brand_id;
                })
                ->editColumn('car_type_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->car_type_id == null) {
                        return '-';
                    } else return $directSalesModel->car_type_id;
                })
                ->editColumn('status_mail', function ($data) {
                    if ($data->status_mail == null) {
                        return 'Not Sent';
                    } else {
                        return 'Sent at ( '  . date('d F y H:i:s', strtotime($data->status_mail)) . ' )';
                    }
                })
                ->editColumn('motor_brand_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->motor_brand_id == null) {
                        return '-';
                    } else return $directSalesModel->motor_brand_id;
                })
                ->editColumn('motor_type_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->motor_type_id == null) {
                        return '-';
                    } else return $directSalesModel->motor_type_id;
                })
                ->editColumn('cust_name', function (DirectSalesModel $directSalesModel) {
                    if (is_numeric($directSalesModel->cust_name)) {
                        if ($directSalesModel->customerBy == null) {
                            return $directSalesModel->cust_name;
                        } else return $directSalesModel->customerBy->code_cust . ' - ' . $directSalesModel->customerBy->name_cust;
                    } else return $directSalesModel->cust_name;
                })
                ->editColumn('isPaid', function ($data) {
                    if ($data->isPaid == 0) {
                        return "<b class='text-danger fw-bold'> Unpaid </b>";
                    } else return "<b class='text-success fw-bold'> Paid </b>";
                })
                ->editColumn('payment_method', function ($data) {
                    if ($data->payment_method == 0) {
                        return "Credit";
                    } else return "Cash";
                })
                ->editColumn('cust_ktp', function ($data) {
                    if ($data->cust_ktp == null) {
                        return "-";
                    } else return $data->cust_ktp;
                })
                ->editColumn('cust_email', function ($data) {
                    if ($data->cust_email == null) {
                        return "-";
                    } else return $data->cust_email;
                })
                ->editColumn('other', function ($data) {
                    if ($data->other == null) {
                        return "-";
                    } else return $data->other;
                })
                ->editColumn('total_excl', function ($data) {
                    return number_format((int)$data->total_excl);
                })
                ->editColumn('total_ppn', function ($data) {
                    return number_format((int)$data->total_ppn);
                })
                ->editColumn('total_incl', function ($data) {
                    return number_format($data->total_incl);
                })
                ->editColumn('created_by', function (DirectSalesModel $directSalesModel) {
                    return $directSalesModel->createdBy->name;
                })
                // ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($direct) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    $car_brands = CarBrandModel::with('typeBy')->oldest('car_brand')->get();
                    $motor_brands = MotorBrandModel::with('typeBy')->oldest('name_brand')->get();
                    $customers = CustomerModel::latest()->get();

                    return view('direct_sales._option', compact('direct', 'ppn', 'car_brands', 'motor_brands', 'customers'))->render();
                    // return 'direct';
                })
                ->rawColumns(['isPaid'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = [
            "title" => "Invoicing DS",
            "ppn" => $ppn
            // 'order_number' =>
        ];

        return view('direct_sales.index', $data);
    }
    
     public function approval()
    {
        $title = 'Direct Sales Approval';
        $dataInvoice = DirectSalesModel::where('isapproved', 0)
            ->where('isrejected', 0)
            ->latest('created_at')
            //->whereIn('warehouse_id', Auth::user()->userWarehouseBy->pluck('warehouse_id'))
            ->get();
        return view('direct_sales.approval', compact('title', 'dataInvoice'));
    }

    public function approve($id)
    {
        try {
            DB::beginTransaction();
            $selected_so = DirectSalesModel::where('id', $id)->firstOrFail();
            //Potong Stock
            $selected_sod = DirectSalesDetailModel::where('direct_id', $selected_so->id)->get();
            $hpp = 0;

            foreach ($selected_sod as $value) {
                $getStock = StockModel::where('products_id', $value->product_id)
                    ->where('warehouses_id', $selected_so->warehouse_id)
                    ->first();

                $old_stock = $getStock->stock;
                $getStock->stock = $old_stock - $value->qty;
                if ($getStock->stock < 0) {
                    return Redirect::back()->with('error', 'Approval Fail! Not enough stock. Please re-confirm to the customer.');
                } else {
                    $getStock->save();
                }


                //change dot stock
                $getDOT = DirectSalesCodesModel::where('direct_detail_id', $value->id)->get();
                if ($getDOT != null) {
                    foreach ($getDOT as $dots) {
                        $dot_model = TyreDotModel::where('id', $dots->dot)->first();
                        if ($dot_model) {
                            $dot_model->qty--;
                            $dot_model->save();
                        }
                    }
                }
                $getHPP = ProductModel::where('id', $value->product_id)->first();
                $hpp = $hpp + ($getHPP->hpp * $value->qty); // akun HPP exclude PPN
            }
            $hpp_ppn = $hpp * (ValueAddedTaxModel::first()->ppn / 100); // akun Pajak Keluaran
            $hpp_include_ppn = $hpp + $hpp_ppn; // akun HPP include PPN
            

            // //Update Last Transaction Customer
            // $selected_customer = CustomerModel::where('id', $selected_so->cust_name)->first();
            // $selected_customer->last_transaction = $selected_so->order_date;
            // $selected_customer->save();

            $so_number = $selected_so->order_number;
            $selected_so->isapproved = 1;
            $selected_so->save();
            
            $journal = createJournal(
                Carbon::now()->format('Y-m-d'),
                'Penjualan Direct No.' . $selected_so->order_number,
                $selected_so->warehouse_id
            );

            // ** Perubahan Saldo Piutang Usaha ** //
            $get_coa_p_masukan =  Coa::where('coa_code', '1-200')->first()->id;
            changeSaldoTambah($get_coa_p_masukan, $selected_so->warehouse_id,  $selected_so->total_incl);

            // ** Perubahan Saldo Pendapatan Penjualan ** //
            $get_coa_persediaan =  Coa::where('coa_code', '4-100')->first()->id;
            changeSaldoTambah($get_coa_persediaan, $selected_so->warehouse_id, $selected_so->total_excl);

            // ** Perubahan Saldo PPN Keluaaran ** //
            $get_coa_hutang_dagang =  Coa::where('coa_code', '2-300')->first()->id;
            changeSaldoTambah($get_coa_hutang_dagang, $selected_so->warehouse_id, $selected_so->total_ppn);

            if ($journal != "" && $journal != null && $journal != false) {
                // akun piutang
                createJournalDetail(
                    $journal,
                    '1-200',
                    $selected_so->order_number,
                    $selected_so->total_incl,
                    0
                );


                // akun penjualan
                
                createJournalDetail(
                    $journal,
                    '4-100',
                    $selected_so->order_number,
                    0,
                    $selected_so->total_excl
                );

                // akun pajak masukan
                
                createJournalDetail(
                    $journal,
                    '2-300',
                    $selected_so->order_number,
                    0,
                    $selected_so->total_ppn
                );
            }

            //Save HPP

            $hpp = createJournal(
                Carbon::now()->format('Y-m-d'),
                'HPP Direct No.' . $selected_so->order_number,
                $selected_so->warehouse_id
            );

            if ($hpp != "" && $hpp != null && $hpp != false) {
                // $hpp_id = $hpp->id;
                $hpp_excl = 0;
                foreach ($selected_sod as $hpp_c) {
                    $getProduct = ProductModel::where('id', $hpp_c->product_id)->first();
                    $hpp_excl = $hpp_excl + ($getProduct->hpp * $hpp_c->qty);
                }

                $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
                $hpp_ppn = $hpp_excl * $current_ppn;
                $hpp_incl = $hpp_excl + $hpp_ppn;

                // akun HPP
                createJournalDetail(
                    $hpp,
                    '6-000',
                    $selected_so->order_number,
                    $hpp_incl,
                    0
                );

                // akun persediaan
                createJournalDetail(
                    $hpp,
                    '1-401',
                    $selected_so->order_number,
                    0,
                    $hpp_excl + $hpp_ppn
                );

                // akun PPN Keluaran
                // createJournalDetail(
                //     $hpp,
                //     '2-300',
                //     $selected_so->order_number,
                //     0,
                //     $hpp_ppn
                // );
            }
            $selected_so->jurnal_id = $journal;
            $selected_so->hpp_id = $hpp;
            $selected_so->save();

            
            //Update Last Transaction Customer
            if (is_numeric($selected_so->cust_name)) {
                $selected_customer = CustomerModel::where('id', $selected_so->cust_name)->first();
            } else {
                switch ($selected_so->warehouse_id) {
                    case 1:
                        $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Palembang)')->first();
                        break;
                    case 8:
                        $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Jambi)')->first();
                        break;
                    default:
                        # code...
                        break;
                }
            }
            if ($selected_customer) {
                $selected_customer->last_transaction = $selected_so->order_date;
                $selected_customer->save();
            }


            $so_number = $selected_so->order_number;
            $selected_so->isapproved = 1;
            $selected_so->save();

            DB::commit();
            return redirect('/retail/approval')->with('success', "Direct Sales Approval Success");
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            throw $e;
        }
    }

    public function rejected(Request $request)
    {
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->oldest('warehouses')->get();

        if ($request->ajax()) {

            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            // Filter invoice berdasarkan gudang dan tanggal yang diminta
            $invoice = DirectSalesModel::with(
                'createdBy',
                'carBrandBy',
                'carTypeBy',
                'motorBrandBy',
                'motorTypeBy',
                'directSalesDetailBy',
                'directSalesDetailBy.productBy',
                'directSalesDetailBy.retailPriceBy'
            )
                ->where('isrejected', 1)
                ->whereIn('warehouse_id', $userWarehouseIds)
                // ->when($request->from_date, function ($query, $fromDate) use ($request) {
                //     return $query->whereBetween('order_date', [$fromDate, $request->to_date]);
                // }, function ($query) {
                //     // Add this condition to use today's date as default
                //     $today = date('Y-m-d');
                //     return empty($query->from_date) ? $query->whereDate('order_date', $today) : $query;
                // })
                ->oldest('order_date')
                ->get();

            return datatables()->of($invoice)

                ->editColumn('payment_method', function ($data) {
                    if ($data->payment_method == 0) {
                        return "Credit";
                    } else return "Cash";
                })
                ->editColumn('order_number', function ($data) {
                    return '<strong >' . $data->order_number . '</strong>';
                })
                ->editColumn('ppn', function ($data) {
                    return number_format($data->ppn);
                })
                ->editColumn('total_after_ppn', function ($data) {
                    return number_format($data->total_after_ppn);
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->total_incl);
                })

                ->editColumn('order_date', function ($data) {
                    return date('d F Y', strtotime($data->order_date));
                })

                ->editColumn('customers_id', function (DirectSalesModel $directSalesModel) {
                    if (is_numeric($directSalesModel->cust_name)) {
                        if ($directSalesModel->customerBy == null) {
                            return $directSalesModel->cust_name;
                        } else return $directSalesModel->customerBy->code_cust . ' - ' . $directSalesModel->customerBy->name_cust;
                    } else return $directSalesModel->cust_name;
                })
                ->editColumn('created_by', function (DirectSalesModel $directSalesModel) {
                    return $directSalesModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->rawColumns(['order_number'])
                ->addIndexColumn()
                ->make(true);
        }
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = [
            'title' => "Direct Order Rejection",
            'ppn' => $ppn
        ];

        return view('direct_sales.rejected', $data);
    }

   public function reject($id)
    {
        try {
            DB::beginTransaction();
            $selected_so = DirectSalesModel::where('id', $id)->firstOrFail();
            $selected_so->isrejected = 1;
            $selected_so->save();

            if (is_numeric($selected_so->cust_name)) {
                $name = $selected_so->cust_name;
            } else {
                switch ($selected_so->warehouse_id) {
                    case 1:
                        $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Palembang)')->first();
                        $name = $selected_customer->id;
                        break;
                    case 8:
                        $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Jambi)')->first();
                        $name = $selected_customer->id;
                        break;
                    default:
                        # code...
                        break;
                }
            }
            $checkoverplafone = checkOverPlafone($name,  $selected_so->total_incl);
            $checkoverdue = checkOverDueByCustomer($name);
            DB::commit();
            return redirect('/retail/approval')->with('info', "Direct Sales " . $selected_so->order_number . " Reject");
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            throw $e;
        }
    }

    public function create()
    {
        $selected_user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->first();
        $retail_products = ProductCostModel::with(['productBy', 'warehouseBy', 'productBy.sub_materials', 'productBy.stockBy', 'productBy.sub_types', 'productBy.uoms'])
            ->where('id_warehouse',  $selected_user_warehouse->id)
            ->whereHas('productBy', function ($query) {
                $query->whereIn('shown', ['all', 'retail']);
                $query->where('status', 1);
            })
            ->get()
            ->sortBy([
                ['productBy.sub_materials.nama_sub_material', 'asc'],
                ['productBy.sub_types.type_name', 'asc'],
                ['productBy.nama_barang', 'asc']
            ]);
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();

        $car_brands = CarBrandModel::with('typeBy')->oldest('car_brand')->get();
        $motor_brands = MotorBrandModel::with('typeBy')->oldest('name_brand')->get();
        $sub_materials = SubMaterialModel::all();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;

        if ($user_warehouse->count() > 1) {
            $customers = CustomerModel::where('status', 1)->where('category_cust_id', 4)->oldest('name_cust')->get();
        } else {
            $customers = CustomerModel::where('status', 1)->whereIn('area_cust_id', array_column($user_warehouse->toArray(), 'id_area'))->where('category_cust_id', 4)->oldest('name_cust')->get();
        }
        // $customers = CustomerModel::where('status', 1)->oldest('name_cust')->get();

        $data = [
            'title' => 'Create DS Order',
            'retail_products' => $retail_products,
            'car_brands' => $car_brands,
            'motor_brands' => $motor_brands,
            'sub_materials' => $sub_materials,
            'ppn' => $ppn,
            'customers' => $customers,
            'selected_user_warehouse' => $selected_user_warehouse,
            'user_warehouse' => $user_warehouse
        ];

        return view('direct_sales.create', $data);
    }
    
    
    public function store(Request $request)
    {
        // dd('wait');
        // dd($request->all());
        try {
            DB::beginTransaction();
            $model = new DirectSalesModel();

            //Create Order Number
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->warehouse_id)
                ->first();
            $lastRecord = DirectSalesModel::where('warehouse_id', $request->warehouse_id)->latest()->first();

            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->order_date)->format('m');
                $currentMonth = Carbon::now()->format('m');

                if ($lastRecordMonth != $currentMonth) {
                    // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $model->id_sort = $cust_number_id;
                } else {
                    // Jika masih dalam bulan yang sama, increment $cust_number_id
                    $cust_number_id = intval($lastRecord->id_sort) + 1;
                    $model->id_sort = $cust_number_id;
                }
            } else {
                // Jika belum ada record sebelumnya, set $cust_number_id menjadi 1
                $cust_number_id = 1;
                $model->id_sort = $cust_number_id;
            }
            $length = 3;
            $direct_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $order_number = 'DS-' . $kode_area->area_code . '-' . $tahun  . $month  . $direct_number_id;
            $model->order_number = $order_number;
            $model->warehouse_id = $request->warehouse_id;

            $model->order_date = date('Y-m-d');

            if ($request->cust_name == 'other_cust') {
                $getWarehouse = WarehouseModel::where('id', $request->warehouse_id)->first();
                if ($getWarehouse->id_area == 9) {
                    $get_customer_other = CustomerModel::where('name_cust', 'Direct Other Customer (Palembang)')->first();
                } else if ($getWarehouse->id_area == 10) {
                    $get_customer_other = CustomerModel::where('name_cust', 'Direct Other Customer (Jambi)')->first();
                }
                
                $model->cust_name = $request->cust_name_manual;
                $model->cust_phone = $request->cust_phone;
                $model->cust_ktp = $request->cust_ktp;
                $model->cust_email = $request->cust_email;
                $model->payment_method = 1;
                $model->isPaid = 0;
                $model->paid_date = null;
                $model->top = $get_customer_other->due_date;
                $dt = new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta'));
                $dt = $dt->modify("+" . $model->top . " days");
                $model->due_date = $dt;

                //Get Province, City, District, Village
                $province_name = $this->getNameProvince($request->province);
                $model->province = ucwords(strtolower($province_name));
                $district_name = $this->getNameCity($request->district);
                $model->district = ucwords(strtolower($district_name));
                $sub_district_name = $this->getNameDistrict($request->sub_district);
                $model->sub_district = ucwords(strtolower($sub_district_name));

                $model->address = $request->address;
                $model->plate_number = strtoupper(str_replace(' ', '', $request->plate_number));
                if ($request->vehicle == 'Car') {
                    $brand = CarBrandModel::where('id', $request->car_brand_id)->first();
                    $model->car_brand_id = $brand->car_brand;
                    if ($request->car_type_id == 'other_car') {
                        $model->car_type_id = strtoupper($request->other_car);
                    } else $model->car_type_id = $request->car_type_id;

                    $model->motor_brand_id = null;
                    $model->motor_type_id = null;
                    $model->other = null;
                } else if ($request->vehicle == 'Motocycle') {
                    $model->car_brand_id = null;
                    $model->car_type_id = null;
                    $model->other = null;
                    $brand = MotorBrandModel::where('id', $request->motor_brand_id)->first();
                    $model->motor_brand_id = $brand->name_brand;
                    if ($request->motor_type_id == 'other_motor') {
                        $model->motor_type_id = strtoupper($request->other_motor);
                    } else $model->motor_type_id = $request->motor_type_id;
                } else {
                    $model->car_brand_id = null;
                    $model->car_type_id = null;
                    $model->other = $request->other;
                    $model->motor_brand_id = null;
                    $model->motor_type_id = null;
                }

                $checkoverplafone = checkOverPlafone($get_customer_other->id,  $request->total_incl);
                $checkoverdue = checkOverDueByCustomer($get_customer_other->id);
                // dd($get_customer_other->label != 'Bad Customer');
                
                if (!$checkoverdue & !$checkoverplafone) {
                    // dd("Dia True. Sedang Maintenance");
                    $model->isapproved = 1;
                    $get_customer_other->last_transaction = $model->order_date;
                    $get_customer_other->save();
                } else {
                    // dd("Dia False. Sedang Maintenance");
                    $model->isapproved = 0;
                    $message = 'Direct Sales ' . $model->order_number . ' from ' . $get_customer_other->name_cust . ' is overdue or over plafond. Please review immediately!';
                    event(new ApprovalMessage('From:' . Auth::user()->name, $message));
                    $notif = new NotificationsModel();
                    $notif->message = $message;
                    $notif->status = 0;
                    $notif->job_id = 44;
                    $notif->save();
                }
            } else {
                $model->cust_name = $request->cust_name;
                $selected_cust = CustomerModel::where('id', $request->cust_name)->first();
                $model->cust_phone = $selected_cust->phone_cust;
                $model->cust_ktp = null;
                $model->cust_email = $selected_cust->email_cust;
                $model->province = $selected_cust->province;
                $model->district = $selected_cust->city;
                $model->sub_district = $selected_cust->district;
                $model->address = $selected_cust->address_cust;
                $model->plate_number = '-';
                $model->car_brand_id = null;
                $model->car_type_id = null;
                $model->other = null;
                $model->motor_brand_id = null;
                $model->motor_type_id = null;
                $model->payment_method = $request->payment_method;
                if ($request->payment_method == 1) {
                    $model->top = 14;
                    $dt = new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta'));
                    $dt = $dt->modify("+" . $model->top . " days");
                    $model->due_date = $dt;
                } else {
                    $model->top = $selected_cust->due_date;
                    $dt = new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta'));
                    $dt = $dt->modify("+" . $model->top . " days");
                    $model->due_date = $dt;
                }
                $model->isPaid = 0;
                $model->paid_date = null;

                $checkoverplafone = checkOverPlafone($model->cust_name,  $request->total_incl);
                $checkoverdue = checkOverDueByCustomer($model->cust_name);
                
                $getCust = CustomerModel::where('id', $model->cust_name)->first();

                if (!$checkoverdue && !$checkoverplafone) {
                    $model->isapproved = 1;
                    $getCust->last_transaction = $model->order_date;
                    $getCust->save();
                } else {
                    $model->isapproved = 0;
                    $message = 'Direct Sales ' . $model->order_number . ' from ' . $getCust->name_cust . ' is overdue or over plafond. Please review immediately!';
                    event(new ApprovalMessage('From:' . Auth::user()->name, $message));
                    $notif = new NotificationsModel();
                    $notif->message = $message;
                    $notif->status = 0;
                    $notif->job_id = 44;
                    $notif->save();
                }
            }
            if ($request->delivery_point_option == 1) {
                $model->delivery_point = '-';
            } else {
                $model->delivery_point = $request->delivery_point_value;
            }
            $model->remark = $request->remark;
            $model->created_by = Auth::user()->id;
            $model->total_excl = $request->total_excl;
            $model->total_ppn = $request->total_ppn;
            $model->total_incl = $request->total_incl;
            $model->pdf_invoice = $model->order_number . '.pdf';
            $model->pdf_do = $model->order_number . '.pdf';
            $saved = $model->save();


            if (!$saved) {
                DB::rollback();
                return redirect('/retail')->with('error', 'Create Order Fail! Please check again the inputs.');
            }

            foreach ($request->retails as $item) {
                $detail = new DirectSalesDetailModel();
                $detail->direct_id = $model->id;
                $detail->product_id = $item['product_id'];
                $get_data_product = ProductModel::where('id', $item['product_id'])->first();

                //get price
                $getPrice = ProductCostModel::where('id_product', $item['product_id'])->where('id_warehouse', $request->warehouse_id)->first();
                $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $getPrice->harga_jual;
                $price = $getPrice->harga_jual + $ppn;
                $detail->price = $price;
                // if ($item['product_code'] == null) {
                //     $detail->product_code = "-";
                // } else $detail->product_code = $item['product_code'];
                $detail->qty = $item['qty'];
                $detail->discount = $item['discount'];
                $detail->discount_rp = $item['discount_rp'];
                $detail->save();

                for ($i = 0; $i < sizeof($item) - 4; $i++) {
                    $code = new DirectSalesCodesModel();
                    $code->direct_detail_id = $detail->id;
                    $code->product_code = $item[$i]['product_code'];
                    if ($get_data_product->materials->nama_material == 'Tyre') {
                        if (isset($item[$i]['dot'])) {
                            $code->dot = $item[$i]['dot'];
                        }
                    }
                    $code->save();
                }

            
                if ($model->isapproved == 1) {
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Penjualan Direct No.' . $model->order_number,
                        $model->warehouse_id
                    );


                    // ** Perubahan Saldo Piutang Usaha ** //
                    $get_coa_p_masukan =  Coa::where('coa_code', '1-200')->first()->id;
                    changeSaldoTambah($get_coa_p_masukan, $model->warehouse_id,  $model->total_incl);

                    // ** Perubahan Saldo Pendapatan Penjualan ** //
                    $get_coa_persediaan =  Coa::where('coa_code', '4-100')->first()->id;
                    changeSaldoTambah($get_coa_persediaan, $model->warehouse_id, $model->total_excl);

                    // ** Perubahan Saldo PPN Keluaaran ** //
                    $get_coa_hutang_dagang =  Coa::where('coa_code', '2-300')->first()->id;
                    changeSaldoTambah($get_coa_hutang_dagang, $model->warehouse_id, $model->total_ppn);

                    if ($journal != "" && $journal != null && $journal != false) {

                        // akun piutang
                        createJournalDetail(
                            $journal,
                            '1-200',
                            $model->order_number,
                            $model->total_incl,
                            0
                        );


                        // akun penjualan
                        createJournalDetail(
                            $journal,
                            '4-100',
                            $model->order_number,
                            0,
                            $model->total_excl
                        );

                        // akun pajak masukan
                        createJournalDetail(
                            $journal,
                            '2-300',
                            $model->order_number,
                            0,
                            $model->total_ppn
                        );
                    }

                    //Save HPP
                    $hpp = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'HPP Direct No.' . $model->order_number,
                        $model->warehouse_id
                    );

                    if ($hpp != "" && $hpp != null && $hpp != false) {
                        // $hpp_id = $hpp->id;
                        $hpp_excl = 0;
                        foreach ($request->retails as $hpp_c) {
                            $getProduct = ProductModel::where('id', $hpp_c['product_id'])->first();
                            $hpp_excl = $hpp_excl + ($getProduct->hpp * $hpp_c['qty']);
                        }

                        $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
                        $hpp_ppn = $hpp_excl * $current_ppn;
                        $hpp_incl = $hpp_excl + $hpp_ppn;

                        // akun HPP
                        createJournalDetail(
                            $hpp,
                            '6-000',
                            $model->order_number,
                            $hpp_incl,
                            0
                        );

                        // akun persediaan
                        createJournalDetail(
                            $hpp,
                            '1-401',
                            $model->order_number,
                            0,
                            $hpp_excl + $hpp_ppn
                        );

                        // akun PPN Keluaran
                        // createJournalDetail(
                        //     $hpp,
                        //     '2-300',
                        //     $model->order_number,
                        //     0,
                        //     $hpp_ppn
                        // );
                    }
                    $model->jurnal_id = $journal;
                    $model->hpp_id = $hpp;
                    $model->save();
                    
                    //Change stock
                    $getStock = StockModel::where('products_id', $item['product_id'])
                        ->where('warehouses_id', $request->warehouse_id)
                        ->first();
                    if ($getStock == null) {
                        DB::rollback();
                        return Redirect::back()->with('error', 'Order Fail! Not enough stock. Please re-confirm to the customer.');
                    }

                    $old_stock = $getStock->stock;
                    $getStock->stock = $old_stock - $item['qty'];
                    if ($getStock->stock < 0) {
                        DB::rollback();
                        return Redirect::back()->with('error', 'Order Fail! Not enough stock. Please re-confirm to the customer.');
                    } else {
                        $getStock->save();
                    }

                    if ($get_data_product->materials->nama_material == 'Tyre') {
                        //change dot stock
                        for ($i = 0; $i < sizeof($item) - 4; $i++) {
                            if (isset($item[$i]['dot'])) {
                                $getDot = TyreDotModel::where('id', $item[$i]['dot'])->first();
                                $getDot->qty--;
                                $getDot->save();
                            }
                        }
                    }
                }
            }

            // $request->replace([]); // Menghapus semua elemen dalam $request
            DB::commit();
            if($model->isapproved == 1){
                return redirect('/retail')->with('success', 'Create Order Success!');
            }else{
                return redirect('/retail')->with('info', 'The order is overdue or has exceeded the credit limit! Please contact the finance.');
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/retail')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function select()
    {
        $sub_materials = [];
        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = DistrictModel::where('district_name', 'LIKE', "%$search%")
                ->get();
        } else {
            $sub_materials = DistrictModel::get();
        }
        return response()->json($sub_materials);
    }

    public function selectWarehouse()
    {
        $product_retails = [];
        $warehouse = request()->w;

        $product_retails = ProductCostModel::join('products', 'products.id', '=', 'product_costs.id_product')
            ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
            ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
            ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
            ->join('uoms', 'uoms.id', '=', 'products.id_uom')
            ->join('stocks', 'stocks.products_id', '=', 'products.id')
            ->where('product_costs.id_warehouse', $warehouse)
            ->where('stocks.warehouses_id', $warehouse)
            ->where('products.status', 1)
            ->whereIn('products.shown', ['all', 'retail'])
            ->select('product_costs.*', 'products.*', 'product_materials.*', 'product_sub_materials.*', 'product_sub_types.*', 'stocks.stock', 'uoms.satuan')
            ->orderBy('product_sub_materials.nama_sub_material')
            ->orderBy('product_sub_types.type_name')
            ->orderBy('products.nama_barang')
            ->get();

        return response()->json($product_retails);
    }

    public function selectProductAll()
    {
        $sub_materials = [];
        $warehouse = request()->w;
        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = ProductModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                ->with(['stockBy', 'uoms'])
                ->whereIn('shown', ['all', 'retail'])
                ->whereHas('stockBy', function ($query) use ($warehouse) {
                    $query->where('warehouses_id', $warehouse);
                })
                ->oldest('product_sub_materials.nama_sub_material')
                ->oldest('product_sub_types.type_name')
                ->oldest('products.nama_barang')
                ->where('nama_barang', 'LIKE', "%$search%")
                ->get();
        } else {
            $sub_materials = ProductModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                ->with(['stockBy', 'uoms'])
                ->whereIn('shown', ['all', 'retail'])
                ->whereHas('stockBy', function ($query) use ($warehouse) {
                    $query->where('warehouses_id', $warehouse);
                })
                ->oldest('product_sub_materials.nama_sub_material')
                ->oldest('product_sub_types.type_name')
                ->oldest('products.nama_barang')
                ->get();
        }
        return response()->json($sub_materials);
    }

    public function search()
    {
        $sub_materials = [];
        $search = request()->q;
        $warehouse = request()->w;
        if (!empty($search)) {
            $sub_materials = ProductCostModel::join('products', 'products.id', '=', 'product_costs.id_product')
                ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
                ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->join('uoms', 'uoms.id', '=', 'products.id_uom')
                ->join('stocks', 'stocks.products_id', '=', 'products.id')
                ->where('product_costs.id_warehouse', $warehouse)
                ->where('stocks.warehouses_id', $warehouse)
                ->whereIn('products.shown', ['all', 'retail'])
                ->where('products.status', 1)
                ->where('products.nama_barang', 'LIKE', "%$search%")
                ->select('product_costs.*', 'products.*', 'product_materials.*', 'product_sub_materials.*', 'product_sub_types.*', 'stocks.stock', 'uoms.satuan')
                ->orderBy('product_sub_materials.nama_sub_material')
                ->orderBy('product_sub_types.type_name')
                ->orderBy('products.nama_barang')
                ->get();
        } else {
            $sub_materials = ProductCostModel::join('products', 'products.id', '=', 'product_costs.id_product')
                ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
                ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->join('uoms', 'uoms.id', '=', 'products.id_uom')
                ->join('stocks', 'stocks.products_id', '=', 'products.id')
                ->where('product_costs.id_warehouse', $warehouse)
                ->where('stocks.warehouses_id', $warehouse)
                ->whereIn('products.shown', ['all', 'retail'])
                ->where('products.status', 1)
                ->select('product_costs.*', 'products.*', 'product_materials.*', 'product_sub_materials.*', 'product_sub_types.*', 'stocks.stock', 'uoms.satuan')
                ->orderBy('product_sub_materials.nama_sub_material')
                ->orderBy('product_sub_types.type_name')
                ->orderBy('products.nama_barang')
                ->get();
        }

        return response()->json($sub_materials);
    }

    public function selectById()
    {
        $product_retails = [];
        $warehouse = request()->w;
        $search = request()->s;
        if ($search != "all") {
            $product_retails = ProductCostModel::join('products', 'products.id', '=', 'product_costs.id_product')
                ->join('warehouses', 'warehouses.id', '=', 'product_costs.id_warehouse')
                ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
                ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->join('uoms', 'uoms.id', '=', 'products.id_uom')
                ->join('stocks', 'stocks.products_id', '=', 'products.id')
                ->whereIn('products.shown', ['all', 'retail'])
                ->where('product_costs.id_warehouse', $warehouse)
                ->where('stocks.warehouses_id', $warehouse)
                ->where('products.status', 1)
                ->where('products.id_sub_material', $search)
                ->select('product_costs.*', 'products.*', 'stocks.stock', 'warehouses.*', 'product_materials.*', 'product_sub_materials.*', 'product_sub_types.*', 'uoms.*')
                ->orderBy('product_sub_materials.nama_sub_material')
                ->orderBy('product_sub_types.type_name')
                ->orderBy('products.nama_barang')
                ->get();
        } else {
            $product_retails = ProductCostModel::join('products', 'products.id', '=', 'product_costs.id_product')
                ->join('uoms', 'uoms.id', '=', 'products.id_uom')
                ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
                ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->join('stocks', 'stocks.products_id', '=', 'products.id')
                ->whereIn('products.shown', ['all', 'retail'])
                ->where('product_costs.id_warehouse', $warehouse)
                ->where('stocks.warehouses_id', $warehouse)
                ->where('products.status', 1)
                ->select('product_costs.*', 'products.*', 'stocks.stock', 'uoms.satuan', 'product_materials.nama_material', 'product_sub_materials.nama_sub_material', 'product_sub_types.type_name')
                ->orderBy('product_sub_materials.nama_sub_material')
                ->orderBy('product_sub_types.type_name')
                ->orderBy('products.nama_barang')
                ->get();
        }
        return response()->json($product_retails);
    }

    public function print_invoice($id)
    {
        $data = DirectSalesModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
        $data->pdf_invoice = $data->order_number . '.pdf';
        $data->save();

        $ppn = ValueAddedTaxModel::first()->ppn / 100;

        $pdf = Pdf::loadView('direct_sales.print_invoice', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $data->order_number . '.pdf');

        return $pdf->stream($data->pdf_invoice);
    }
    public function PrintStruk($id)
    {
        $data = DirectSalesModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
        $data->pdf_invoice = $data->order_number . '.pdf';
        $data->save();

        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $ppn_ = $ppn * 100;

        $pdf = Pdf::loadView('direct_sales.print_struk', compact('warehouse', 'data', 'ppn_'))->save('pdf/' . $data->order_number . '.pdf');

        return $pdf->stream($data->pdf_invoice);
    }

    public function print_do($id)
    {
        $data = DirectSalesModel::find($id);
        $so_number = str_replace('DS', 'DOPP', $data->order_number);
        $data->pdf_do = $so_number . '.pdf';
        $data->save();
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
        $pdf = Pdf::loadView('direct_sales.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $so_number . '.pdf');
        return $pdf->stream($data->pdf_do);
    }

    public function update_retail(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $retail = DirectSalesModel::where('id', $id)->first();
            //Restore stock to before changed
            $direct_restore = DirectSalesDetailModel::where('direct_id', $id)->get();
            foreach ($direct_restore as $restore) {
                $stock = StockModel::where('warehouses_id', $retail->warehouse_id)
                    ->where('products_id', $restore->product_id)->first();
                $stock->stock = $stock->stock + $restore->qty;
                $stock->save();
            }
            //Check Number of product
            if ($request->retails == null) {
                DB::rollback();
                return Redirect::back()->with('error', 'There are no products!');
            }

            //Check Duplicate
            $products_arr = [];

            foreach ($request->retails as $check) {
                array_push($products_arr, $check['product_id']);
                $getstock = StockModel::where('products_id', $check['product_id'])->where('warehouses_id', $retail->warehouse_id)->first();
                if ($check['qty'] > $getstock->stock) {
                    DB::rollback();
                    return Redirect::back()->with('error', 'Edit Fail! The number of items exceeds the stock.');
                }
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                DB::rollback();
                return Redirect::back()->with('error', 'Edit Fail! You enter duplicate product.');
            }

            $selected_direct = DirectSalesModel::where('id', $id)->first();
            $selected_cust = CustomerModel::where('id', $request->cust_name)->first();
            if ($request->cust_name == 'other_cust') {
                $selected_direct->cust_name = $request->cust_name_manual;
                $selected_direct->cust_phone = $request->cust_phone;
                $selected_direct->cust_ktp = $request->cust_ktp;
                $selected_direct->cust_email = $request->cust_email;

                if (is_numeric($request->province)) {
                    //Get Province, City, District, Village
                    $province_name = $this->getNameProvince($request->province);
                    $selected_direct->province = ucwords(strtolower($province_name));
                    $district_name = $this->getNameCity($request->district);
                    $selected_direct->district = ucwords(strtolower($district_name));
                    $sub_district_name = $this->getNameDistrict($request->sub_district);
                    $selected_direct->sub_district = ucwords(strtolower($sub_district_name));
                }

                $selected_direct->address = $request->address;
                $selected_direct->plate_number = strtoupper(str_replace(' ', '', $request->plate_number));
                if ($request->vehicle == 'Car') {
                    $brand = CarBrandModel::where('id', $request->car_brand_id)->first();
                    $selected_direct->car_brand_id  = $brand->car_brand;
                    if ($request->car_type_id == 'other_car') {
                        $selected_direct->car_type_id = strtoupper($request->other_car);
                    } else $selected_direct->car_type_id = $request->car_type_id;

                    $selected_direct->motor_brand_id = null;
                    $selected_direct->motor_type_id = null;
                    $selected_direct->other = null;
                } else if ($request->vehicle == 'Motocycle') {
                    $selected_direct->car_brand_id = null;
                    $selected_direct->car_type_id = null;
                    $selected_direct->other = null;
                    $brand = MotorBrandModel::where('id', $request->motor_brand_id)->first();
                    $selected_direct->motor_brand_id = $brand->name_brand;
                    if ($request->motor_type_id == 'other_motor') {
                        $selected_direct->motor_type_id = strtoupper($request->other_motor);
                    } else $selected_direct->motor_type_id = $request->motor_type_id;
                } else {
                    $selected_direct->car_brand_id = null;
                    $selected_direct->car_type_id = null;
                    $selected_direct->other = $request->other;
                    $selected_direct->motor_brand_id = null;
                    $selected_direct->motor_type_id = null;
                }
            } else {
                $selected_direct->cust_name = $request->cust_name;
                $selected_direct->cust_phone = $selected_cust->phone_cust;
                $selected_direct->cust_ktp = null;
                $selected_direct->cust_email = $selected_cust->email_cust;
                $selected_direct->province = $selected_cust->province;
                $selected_direct->district = $selected_cust->city;
                $selected_direct->sub_district = $selected_cust->district;
                $selected_direct->address = $selected_cust->address_cust;
                $selected_direct->plate_number = '-';
                $selected_direct->car_brand_id = null;
                $selected_direct->car_type_id = null;
                $selected_direct->other = null;
                $selected_direct->motor_brand_id = null;
                $selected_direct->motor_type_id = null;
            }

            $selected_direct->remark = $request->remark;
            $old_payment = $selected_direct->payment_method;
            $selected_direct->payment_method = $request->payment_method;
            // if ($request->payment_method == 1) {
            //     $selected_direct->top = null;
            //     $selected_direct->due_date = null;
            // } else {
            //     if ($old_payment != $request->payment_method) {
            //         $selected_direct->top = $selected_cust->due_date;
            //         $dt = new DateTimeImmutable($selected_direct->order_date, new DateTimeZone('Asia/Jakarta'));
            //         $dt = $dt->modify("+" . $selected_direct->top . " days");
            //         $selected_direct->due_date = $dt;
            //     }
            // }


            //Save Return Input and Total and Change Stock
            $total = 0;

            foreach ($request->retails as $product) {
                $product_exist = DirectSalesDetailModel::where('direct_id', $id)
                    ->where('product_id', $product['product_id'])->first();

                if ($product_exist != null) {
                    $old_qty = $product_exist->qty;
                    $product_exist->qty = $product['qty'];
                    $double_disc = str_replace(',', '.', $product['discount']);
                    $product_exist->discount = floatval($double_disc);
                    $product_exist->discount_rp = $product['discount_rp'];
                    $product_exist->save();

                    $code_exist = DirectSalesCodesModel::where('direct_detail_id', $product_exist->id)->get();
                    foreach ($code_exist as $cx) {
                        $cx->delete();
                    }

                    $get_all_index = array_keys($product);
                    $get_code_series_index = array_slice($get_all_index, 4);
                    for ($i = 0; $i < sizeof($get_code_series_index); $i++) {
                        if (isset($product[$get_code_series_index[$i]]['product_code'])) {
                            $code = new DirectSalesCodesModel();
                            $code->direct_detail_id = $product_exist->id;
                            $code->product_code = $product[$get_code_series_index[$i]]['product_code'];
                            $code->save();
                        } else continue;
                    }
                } else {
                    $new_product = new DirectSalesDetailModel();
                    $new_product->direct_id = $id;
                    $new_product->product_id = $product['product_id'];

                    $getPrice = ProductCostModel::where('id_product', $product['product_id'])->where('id_warehouse', $retail->warehouse_id)->first();
                    $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $getPrice->harga_jual;
                    $price = $getPrice->harga_jual + $ppn;
                    $new_product->price = $price;

                    $new_product->qty = $product['qty'];
                    $double_disc = str_replace(',', '.', $product['discount']);
                    $new_product->discount = floatval($double_disc);
                    $new_product->discount_rp = $product['discount_rp'];
                    $new_product->save();

                    $get_all_index = array_keys($product);
                    $get_code_series_index = array_slice($get_all_index, 4);
                    for ($i = 0; $i < sizeof($product) - 4; $i++) {
                        if (isset($product[$get_code_series_index[$i]]['product_code'])) {
                            $code = new DirectSalesCodesModel();
                            $code->direct_detail_id = $new_product->id;
                            $code->product_code = $product[$get_code_series_index[$i]]['product_code'];
                            $code->save();
                        } else continue;
                    }
                }

                //Delete product that not in Detail Input
                $del = DirectSalesDetailModel::where('direct_id', $id)
                    ->whereNotIn('product_id', $products_arr)->delete();

                //Count Total
                $products = ProductModel::where('id', $product['product_id'])->first();
                $retail_price = 0;
                foreach ($products->retailPriceBy as $value) {
                    if ($value->id_warehouse == $retail->warehouse_id) {

                        $retail_price = str_replace(',', '.', $value->harga_jual);
                        $retail_price = floatval($retail_price);
                    }
                }
                $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $retail_price;
                $ppn_cost = $retail_price + $ppn;
                $diskon = floatval(str_replace(',', '.', $product['discount'])) / 100;
                $hargaDiskon = $ppn_cost * $diskon;
                $hargaAfterDiskon = ($ppn_cost -  $hargaDiskon) - $product['discount_rp'];
                $total = $total + ($hargaAfterDiskon * $product['qty']);

                //Change Stock
                $getStock = StockModel::where('products_id', $product['product_id'])
                    ->where('warehouses_id', $retail->warehouse_id)
                    ->first();
                $old_stock = $getStock->stock;
                $getStock->stock = $old_stock - $product['qty'];
                $getStock->save();
            }


            $selected_direct->total_excl = $total / 1.11;
            $selected_direct->total_ppn = $total / 1.11 * (ValueAddedTaxModel::first()->ppn / 100);
            $selected_direct->total_incl = $total;
            $saved = $selected_direct->save();
            
            //Change Journal
            $jurnal_detail = JournalDetail::where('journal_id', $selected_direct->jurnal_id)->get();
            //    ** edit saldo belum

            foreach ($jurnal_detail as $detail) {
                if ($detail->debit != 0) {
                    $detail->debit = $selected_direct->total_incl;
                } else {
                    if ($detail->coa_code == '4-100') {
                        $detail->credit = $selected_direct->total_excl;
                    } else $detail->credit = $selected_direct->total_ppn;
                }
                $detail->save();
            }

            //Change HPP
            $hpp_excl = 0;
            foreach ($request->retails as $value) {
                $getProduct = ProductModel::where('id', $value['product_id'])->first();
                $hpp_excl = $hpp_excl + ($getProduct->hpp * $value['qty']);
            }

            $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
            $hpp_ppn = $hpp_excl * $current_ppn;
            $hpp_incl = $hpp_excl + $hpp_ppn;

            $hpp_detail = JournalDetail::where('journal_id', $selected_direct->hpp_id)->get();
            foreach ($hpp_detail as $detail) {
                if ($detail->debit != 0) {
                    $detail->debit = $hpp_incl;
                } else {
                    if ($detail->coa_code == '1-401') {
                        $detail->credit = $hpp_excl;
                    } else $detail->credit = $hpp_ppn;
                }
                $detail->save();
            }

            DB::commit();
            return redirect('/retail')->with('success', 'Edit Invoice Retail Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/retail')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function selectReturn()
    {
        try {
            $retail_id = request()->r;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = DirectSalesDetailModel::join('products', 'products.id', '=', 'direct_sales_details.product_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('products.nama_barang', 'LIKE', "%$search%")
                    ->where('direct_id', $retail_id)
                    ->orWhere('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->where('direct_id', $retail_id)
                    ->orWhere('product_sub_materials.nama_sub_material', 'LIKE', "%$search%")
                    ->where('direct_id', $retail_id)
                    ->get();
            } else {
                $product = DirectSalesDetailModel::join('products', 'products.id', '=', 'direct_sales_details.product_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('direct_id', $retail_id)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function getQtyDetail()
    {
        $retail_id = request()->r;
        $product_id = request()->p;

        $getqty = DirectSalesDetailModel::where('direct_id', $retail_id)->where('product_id', $product_id)->first();
        $_qty = $getqty->qty;
        $selected_return = ReturnRetailModel::with('returnDetailsBy')->where('retail_id', $retail_id)->get();

        $return = 0;
        if ($selected_return != null) {
            foreach ($selected_return as $value) {
                $selected_detail = ReturnRetailDetailModel::where('return_id', $value->id)->where('product_id', $product_id)->first();
                if ($selected_detail != null) {
                    $return = $return + $selected_detail->qty;
                } else $return = $return + 0;
            }
        }
        $data = [
            'qty' => $_qty,
            'return' => $return
        ];
        return response()->json($data);
    }

    public function getDot()
    {
        $retail_id = request()->r;
        $product_id = request()->p;

        $getdetail = DirectSalesDetailModel::where('direct_id', $retail_id)->where('product_id', $product_id)->first();
        $dots = [];
        foreach ($getdetail->directSalesCodeBy as $value) {
            if ($value->dotBy != null && !in_array($value->dotBy->dot, array_column($dots, 'dot'))) {
                $dot_obj = new stdClass();
                $dot_obj->id = $value->dot;
                $dot_obj->dot = $value->dotBy->dot;

                array_push($dots, $dot_obj);
            }
        }



        $data = [
            'dots' => $dots,
        ];
        return response()->json($data);
    }


    public function paidManagement(Request $request)
    {
        // $invoice = DirectSalesModel::with('directSalesDetailBy', 'createdBy', 'customerBy')
        //     ->where('isPaid', 0)
        //     ->latest()
        //     ->get()
        //     ->groupBy('cust_name');
        // dd($invoice);
        // foreach ($invoice as  $value) {
        //     foreach ($value as $key => $value2) {
        //         dd($value2->order_number);
        //     }
        // }

        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $invoice = DirectSalesModel::with('directSalesDetailBy', 'createdBy', 'customerBy')
                    ->where('isPaid', 0)
                    ->where('isapproved', 1)
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get()
                    ->groupBy('cust_name')
                    ->sortBy(function ($item) {
                        if (is_numeric($item->first()->cust_name)) {
                            return $item->first()->customerBy->name_cust;
                        }
                        return $item->first()->cust_name;
                    });
            } else {
                $invoice = DirectSalesModel::with('directSalesDetailBy', 'createdBy', 'customerBy')
                   ->when($request->invoice_number, function ($query) use ($request) {
                        return $query->where('order_number', $request->invoice_number);
                    }, function ($query) {
                        return $query->where('isPaid', 0)->where('isapproved', 1);
                    })
                    ->latest()
                    ->get()
                    ->groupBy('cust_name')
                    ->sortBy(function ($item) {
                        if (is_numeric($item->first()->cust_name)) {
                            return $item->first()->customerBy->name_cust;
                        }
                        return $item->first()->cust_name;
                    });
            }
            return datatables()->of($invoice)
                ->editColumn('total_after_ppn', function ($data) {
                    $total_return = 0;
                    $total_sale = 0;
                    $total_credit = 0;

                    foreach ($data as $value) {
                        if (ReturnRetailModel::where('retail_id', $value->id)->sum('total') != null) {
                            $total_return += ReturnRetailModel::where('retail_id', $value->id)->where('isreceived',1)->where('isapproved',1)->sum('total');
                        }
                        if (DirectSalesCreditModel::where('direct_id', $value->id)->sum('amount') != null) {
                            $total_credit += DirectSalesCreditModel::where('direct_id', $value->id)->sum('amount');
                        }
                        $total_sale += $value->total_incl;
                    }
                    return number_format(($total_sale - $total_credit) - $total_return);
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) {
                    $total_return = 0;
                    $total_sale = 0;
                    $total_credit = 0;
                    $id_cust = '';
                    $name_cust = '';
                    $code_cust = 'Person';

                    foreach ($invoice as $value) {
                        if (ReturnRetailModel::where('retail_id', $value->id)->first() != null) {
                            $total_return += ReturnRetailModel::where('retail_id', $value->id)->where('isreceived',1)->where('isapproved',1)->sum('total');
                        }
                        if (DirectSalesCreditModel::where('direct_id', $value->id)->first() != null) {
                            $total_credit += DirectSalesCreditModel::where('direct_id', $value->id)->sum('amount');
                        }
                        $total_sale += $value->total_incl;
                    }
                    if (is_numeric($invoice->first()->cust_name)) {
                        $id_cust = $invoice->first()->customerBy->id;
                        $name_cust = $invoice->first()->customerBy->name_cust;
                        $code_cust = $invoice->first()->customerBy->code_cust;
                    } else {
                        $id_cust = uniqid();
                        $name_cust = $invoice->first()->cust_name;
                    }
                    return view('direct_sales._option_paid_management', compact(
                        'invoice',
                        'total_sale',
                        'total_return',
                        'total_credit',
                        'id_cust',
                        'name_cust',
                        'code_cust'
                    ))->render();
                })
                ->rawColumns(['order_number'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "Settlement",
            // 'order_number' =>
        ];
        return view('direct_sales.paid_management', $data);
    }

     public function updatePaid(Request $request, $id)
    {
        // dd('sedang di-fix');
        // $request->validate([
        //     "amount_method" => "required",
        // ]);

        try {
            DB::beginTransaction();

            $selected_retail = DirectSalesModel::where('id', $id)->first();

            //Save Sales Order Credit
            $array_date = [];
            $total_current_amount = 0;
            $current_ids = [];
            foreach ($request->pay as $item) {
                $roc = new DirectSalesCreditModel();
                $roc->direct_id = $selected_retail->id;
                $roc->payment_date =  date('Y-m-d', strtotime($item['payment_date']));
                if ($item['amount_method'] == 'full') {
                    $roc->amount = $selected_retail->total_incl - $selected_retail->directSalesReturnBy->sum('total') - $selected_retail->directSalesCreditBy->sum('amount');
                } else {
                    $roc->amount = $item['amount'];
                }
                $roc->payment_method = $item['payment_method'];
                $roc->update_by = Auth::user()->id;
                $roc->save();
                array_push($array_date, $roc->payment_date);
                $total_current_amount += $roc->amount;
                array_push($current_ids, $roc->id);
            }
            sort($array_date);
            $last_date = end($array_date);
            //Count total amount instalment
            $all_roc = DirectSalesCreditModel::where('direct_id', $id)->get();
            $total_amount = 0;
            $total_return = 0;
            $total_return = ReturnRetailModel::where('retail_id', $id)->sum('total');
            foreach ($all_roc as $value) {
                $total_amount = $total_amount + $value->amount;
            }

            //Save Journal
            $created_journal = createJournal(
                $last_date,
                'Pembayaran Direct No.' . $selected_retail->order_number,
                $selected_retail->warehouse_id
            );

            // ** Perubahan Saldo Kas ** //
            $get_coa_p_masukan =  Coa::where('coa_code', $request->acc_coa)->first()->id;
            changeSaldoTambah($get_coa_p_masukan, $selected_retail->warehouse_id,  $selected_retail->total_excl);

            // ** Perubahan Saldo PPN Keluaran ** //
            $get_coa_persediaan =  Coa::where('coa_code', '2-300')->first()->id;
            changeSaldoTambah($get_coa_persediaan, $selected_retail->warehouse_id, $selected_retail->total_ppn);

            // ** Perubahan Saldo Piutang Usaha ** //
            $get_coa_hutang_dagang =  Coa::where('coa_code', '1-200')->first()->id;
            changeSaldoKurang($get_coa_hutang_dagang, $selected_retail->warehouse_id, $selected_retail->total_incl);


            if ($created_journal != "" && $created_journal != null && $created_journal != false) {
                createJournalDetail(
                    $created_journal,
                    $request->acc_coa,
                    $selected_retail->order_number,
                    $total_current_amount,
                    0
                );
                // createJournalDetail(
                //     $created_journal,
                //     '2-300',
                //     $selected_retail->order_number,
                //     ($total_current_amount / 1.11) * (ValueAddedTaxModel::first()->ppn / 100),
                //     0
                // );
                createJournalDetail(
                    $created_journal,
                    '1-200',
                    $selected_retail->order_number,
                    0,
                    $total_current_amount
                );
            }

            foreach ($current_ids as $key => $value) {
                $get_current_credit = DirectSalesCreditModel::where('id', $value)->first();
                $get_current_credit->journal_id = $created_journal;
                $get_current_credit->save();
            }
           
            // dd('total amount:' . $total_amount . ' ' . 'total incl:' . $selected_retail->total_incl . ' ' . 'total return:' . $total_return);
            if ($total_amount >= (round($selected_retail->total_incl) - $total_return)) {
                $selected_retail->isPaid = 1;
                $selected_retail->paid_date = date('Y-m-d', strtotime($last_date));
                $selected_retail->save();

                //update overplafone and overdue
                if (is_numeric($selected_retail->cust_name)) {
                    $checkoverplafone = checkOverPlafone($selected_retail->cust_name);
                    $checkoverdue = checkOverDueByCustomer($selected_retail->cust_name);
                } else {
                    switch ($selected_retail->warehouse_id) {
                        case 1:
                            $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Palembang)')->first();
                            break;
                        case 8:
                            $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Jambi)')->first();
                            break;
                        default:
                            # code...
                            break;
                    }
                    $checkoverplafone = checkOverPlafone($selected_customer->id);
                    $checkoverdue = checkOverDueByCustomer($selected_customer->id);
                }
                // $selected_retail::savesss();

                DB::commit();
                return redirect('/retail/manage_payment')->with('success', "Order number " . $selected_retail->order_number . " already paid!");
            } else {
                // $selected_retail::savesss();
                DB::commit();
                return redirect('/retail/manage_payment')->with('success', "Update Payment of Order number " . $selected_retail->order_number . " Success!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/retail/manage_payment')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function cancelPaid(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $total_return = ReturnRetailModel::where('retail_id', $id)->sum('total');
            $direct = DirectSalesModel::where('id', $id)->first();
            $direct_credit = DirectSalesCreditModel::where('direct_id', $id)->get();
            $total_credit = DirectSalesCreditModel::where('direct_id', $id)->sum('amount');
            
            //Journal Return
            if ($total_return > 0) {
                // ** jika terjadi return ** //
                $data_kas = '';
                foreach ($direct_credit as $value) {
                    $journal = Journal::where('id', $value->journal_id)->first();
                    $journal_detail = JournalDetail::where('journal_id', $value->journal_id)
                        ->where('debit', '!=', 0)
                        ->orderBy('debit', 'desc') // Menyusun data berdasarkan 'credit' secara descending
                        ->first(); // Mengambil entri pertama dengan 'credit' terbesar
                    // dd($journal_detail);
                    $data_kas = $journal_detail->coa_code;
                }

                // ** Jika sudah terjadi pelunasan
                if (round($direct->total_incl) - $total_credit == 0) {
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Penjualan Direct Tunai.' . $direct->order_number,
                        $direct->warehouse_id
                    );
                    // ** Perubahan Saldo Retur Penjualan ** //
                    $get_coa_p_masukan =  Coa::where('coa_code', '4-102')->first()->id;
                    changeSaldoTambah($get_coa_p_masukan, $direct->warehouse_id,  $total_credit / 1.11);

                    // ** Perubahan Saldo PPN Keluaran ** //
                    $get_coa_persediaan =  Coa::where('coa_code', '2-300')->first()->id;
                    changeSaldoKurang($get_coa_persediaan, $direct->warehouse_id, $total_credit / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));

                    // ** Perubahan Saldo Piutang Usaha ** //
                    $get_coa_hutang_dagang =  Coa::where('coa_code', $data_kas)->first()->id;
                    changeSaldoKurang($get_coa_hutang_dagang, $direct->warehouse_id, $total_credit);

                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA Return Penjualan ** //
                        createJournalDetail(
                            $journal,
                            '4-102',
                            $direct->order_number,
                            $total_credit,
                            0
                        );
                        // ** COA PPn Keluaran ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '2-300',
                        //     $direct->order_number,
                        //     $total_credit / 1.11 * (ValueAddedTaxModel::first()->ppn / 100),
                        //     0
                        // );
                        // ** COA KAS ** //
                        createJournalDetail(
                            $journal,
                            $data_kas,
                            $direct->order_number,
                            0,
                            $total_credit
                        );
                    }
                } else if ($direct_credit->count() > 0) {

                    // ** ini jika sudah bayar setengah
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Penjualan Direct Tunai.' . $direct->order_number,
                        $direct->warehouse_id
                    );


                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA Return Penjualan ** //
                        createJournalDetail(
                            $journal,
                            '4-102',
                            $direct->order_number,
                            $total_credit,
                            0
                        );
                        // ** COA PPn Keluaran ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '2-300',
                        //     $direct->order_number,
                        //     $total_credit / 1.11 * (ValueAddedTaxModel::first()->ppn / 100),
                        //     0
                        // );
                        // ** COA KAS ** //
                        createJournalDetail(
                            $journal,
                            $data_kas,
                            $direct->order_number,
                            0,
                            $total_credit
                        );
                    }

                    // ** ini sisa yang belum bayar
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Penjualan Direct Kredit.' . $direct->order_number,
                        $direct->warehouse_id
                    );

                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA Return Penjualan ** //
                        createJournalDetail(
                            $journal,
                            '4-102',
                            $direct->order_number,
                            (round($direct->total_incl) - $total_credit) ,
                            0
                        );
                        // ** COA PPn Keluaran ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '2-300',
                        //     $direct->order_number,
                        //     (round($direct->total_incl) - $total_credit) / 1.11 * (ValueAddedTaxModel::first()->ppn / 100),
                        //     0
                        // );
                        // ** COA Piutang ** //
                        createJournalDetail(
                            $journal,
                            '1-200',
                            $direct->order_number,
                            0,
                            round($direct->total_incl) - $total_credit
                        );
                    }

                    // ** Perubahan Saldo Retur Penjualan ** //
                    $get_coa_p_masukan =  Coa::where('coa_code', '4-102')->first()->id;
                    changeSaldoTambah($get_coa_p_masukan, $direct->warehouse_id, ($total_credit / 1.11) + ((round($direct->total_incl) - $total_credit) / 1.11));

                    // ** Perubahan Saldo PPN Keluaran ** //
                    $get_coa_persediaan =  Coa::where('coa_code', '2-300')->first()->id;
                    changeSaldoKurang($get_coa_persediaan, $direct->warehouse_id, ($total_credit / 1.11 * (ValueAddedTaxModel::first()->ppn / 100)) + (round($direct->total_incl) - $total_credit) / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));

                    // ** Perubahan Saldo Piutang Usaha ** //
                    $get_coa_hutang_dagang =  Coa::where('coa_code', '1-200')->first()->id;
                    changeSaldoKurang($get_coa_hutang_dagang, $direct->warehouse_id, round($direct->total_incl) - $total_credit);
                    // ** Perubahan Saldo Kas ** //
                    $get_coa_hutang_dagang =  Coa::where('coa_code', $data_kas)->first()->id;
                    changeSaldoKurang($get_coa_hutang_dagang, $direct->warehouse_id, $total_credit);
                }
            } else {
                // ** jika hanya cancel payment saja ** //
                $get_credit_in_cancel = DirectSalesCreditModel::where('direct_id', $id)->get();
                foreach ($get_credit_in_cancel as $value) {
                    $journal = Journal::where('id', $value->journal_id)->first();
                    if ($journal) {
                        $journal->jurnal_detail()->delete();
                        $journal->delete();
                    }
                }
            }
            
            $all_credit_amount = 0;
            foreach ($request->cancel as $value) {
                $credits = DirectSalesCreditModel::where('id', $value['credit_id'])->first();
                $credits->amount = $credits->amount - $value['amount'];
                $all_credit_amount += $value['amount'];
                if ($credits->amount <= 0) {
                    $credits->delete();
                } else {
                    $credits->save();
                }
            }
            // $total_return = ReturnRetailModel::where('retail_id', $id)->sum('total');
            $total_credit = DirectSalesCreditModel::where('direct_id', $id)->sum('amount');
            // $direct = DirectSalesModel::where('id', $id)->first();
            if (round($direct->total_incl) - $total_return == $total_credit) {
                $direct->isPaid = 1;
                $direct->paid_date = date('Y-m-d');
                $direct->save();

                //update overplafone and overdue
                if (is_numeric($direct->cust_name)) {
                    $checkoverplafone = checkOverPlafone($direct->cust_name);
                    $checkoverdue = checkOverDueByCustomer($direct->cust_name);
                } else {
                    switch ($direct->warehouse_id) {
                        case 1:
                            $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Palembang)')->first();
                            break;
                        case 8:
                            $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Jambi)')->first();
                            break;
                        default:
                            # code...
                            break;
                    }
                    $checkoverplafone = checkOverPlafone($selected_customer->id);
                    $checkoverdue = checkOverDueByCustomer($selected_customer->id);
                }
            }

            DB::commit();
            return redirect('/retail/manage_payment')->with('error', 'Cancel payment success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function printCashReceipt($id)
    {
        $data = DirectSalesModel::find($id);
        $credit = DirectSalesDetailModel::where('direct_id', $id)->first();
        $datas = [
            'data' => $data,
            'credit' => $credit
        ];
        $pdf = Pdf::loadView('direct_sales.cash_receipt', $datas);
        $pdf->setPaper('a4');
        return $pdf->stream('cash_receipt.pdf');
    }

    // Delete Indirect Invoice
    public function deleteInvoice($id)
    {
        try {
            DB::beginTransaction();

            $sales_order = DirectSalesModel::find($id);
            
            //Delete Journal
            $jurnal_detail = JournalDetail::where('journal_id', $sales_order->jurnal_id)->get();
            foreach ($jurnal_detail as $key => $detail) {
                $detail->delete();
            }

            //Delete HPP
            $hpp_detail = JournalDetail::where('journal_id', $sales_order->hpp_id)->get();
            foreach ($hpp_detail as $key => $detail) {
                $detail->delete();
            }

            // restore stock
            $sales_order_detail = DirectSalesDetailModel::where('direct_id', $id)->get();

            foreach ($sales_order_detail as $key => $value) {
                $stock = StockModel::where('products_id', $value->product_id)->where('warehouses_id', $sales_order->warehouse_id)->first();
                $stock->stock = $stock->stock + $value->qty;
                $stock->save();

                if ($value->productBy->materials->nama_material == 'Tyre') {
                    foreach ($value->directSalesCodeBy as $code) {
                        $getDot = TyreDotModel::where('id', $code->dot)->first();
                        $getDot->qty++;
                        $getDot->save();

                        $code->delete();
                    }
                }

                $value->delete();
            }
            
            //Delete Settlement
            $sales_order_credit = DirectSalesCreditModel::where('direct_id', $id)->get();
            if ($sales_order_credit) {
                foreach ($sales_order_credit as $key => $value) {
                    //Delete Journal
                    $get_credit_journal = Journal::where('id', $value->journal_id)->first();
                    if ($get_credit_journal) {
                        foreach ($get_credit_journal->journal_detail as $key => $credit) {
                            $credit->delete();
                        }
                        $get_credit_journal->delete();
                    }

                    $value->delete();
                }
            }

            $sales_order->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Direct Invoice ' . $sales_order->order_number . ' has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e->getMessage());
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function getTotalInstalment($id)
    {
        $roc = DirectSalesCreditModel::where('direct_id', $id)->get();

        $total_amount = 0;
        foreach ($roc as $value) {
            $total_amount = $total_amount + $value->amount;
        }
        return response()->json($total_amount);
    }

    public function getNameProvince($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/province/' . $id . '.json');
        $getProvinces = $getAPI->json();
        // dd($getProvinces['name']);
        return $getProvinces['name'];
        // dd($getProvinces['name']);
    }

    public function getNameCity($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/regency/' . $id . '.json');
        $getCities = $getAPI->json();
        return $getCities['name'];
    }

    public function getNameDistrict($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/district/' . $id . '.json');
        $getDistricts = $getAPI->json();
        return $getDistricts['name'];
    }
}
