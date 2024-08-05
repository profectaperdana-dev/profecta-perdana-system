<?php

namespace App\Http\Controllers;

use App\Models\AssetModel;
use App\Models\CitiesModel;
use App\Models\CustomerModel;
use App\Models\EmployeeModel;
use App\Models\JurnalDetailModel;
use App\Models\JurnalModel;
use App\Models\TripCompletedDetailModel;
use App\Models\TripCompletedModel;
use App\Models\TripModel;
use App\Models\TripRouteModel;
use App\Models\TripVehicleCompletedModel;
use App\Models\TripVehicleModel;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class TripController extends Controller
{
    public function destroy($id){
         try {
            DB::beginTransaction();
            TripModel::where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with('error', 'Business Trip Proposal Delete');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function create()
    {

        $data = [
            'title' => 'Create Business Trip Proposal',
            'customer' => CustomerModel::orderBy('name_cust', 'asc')->get(),
            'destination' => CustomerModel::select('city')
                ->distinct()
                ->orderBy('city', 'asc')
                ->get(),

            'employee' => EmployeeModel::orderBy('name', 'asc')->get(),
            'warehouse' => WarehouseModel::where('type', 5)
                ->latest()->get(),
            'warehouse_' => WarehouseModel::where('type', 5)
                ->latest()->get(),
            'vehicle' => AssetModel::where('category_id', 2)->get(),
        ];
        return view('trip.index', $data);
    }
    public function getEmployee(Request $request)
    {
        $user = Auth::user()->employee_id;
        $data = EmployeeModel::where('status', 1)->orderBy('name', 'asc')->get();
        return response()->json($data);
    }
    public function getSelect(Request $request)
    {
        if ($request->has('q')) {
            $search = $request->q;
            $data = CustomerModel::where('name_cust', 'LIKE', "%$search%")
                ->orderBy('name_cust', 'asc')
                ->get();
        } else {
            $data = CustomerModel::orderBy('name_cust', 'asc')->get();
        }

        return response()->json($data);
    }
    public function getCities(Request $request)
    {
        $filter = $request->get('q');
        $data = CitiesModel::when($filter, function ($query) use ($filter) {
            $query->where('city', 'LIKE', '%' . $filter . '%');
        })
            ->orderBy('city', 'asc')
            ->get();

        return response()->json($data);
    }
    public function getBank(Request $request)
    {
        $q = $request->q;
        $getAPI = Http::get('https://preposterous-cat.github.io/gudang-data/bank/bank.json');
        $getBank = $getAPI->json();

        if ($q) {
            $filteredBank = array_filter($getBank, function ($bank) use ($q) {
                return stripos($bank['name'], $q) !== false;
            });

            $getBank = array_values($filteredBank);
        }

        usort($getBank, function ($a, $b) {
            return strcasecmp($a['name'], $b['name']);
        });

        return response()->json($getBank);
    }

    public function store(Request $request)
    {   
        
        // dd($request->all());

        try {
            DB::beginTransaction();
            $data = new TripModel();
            // menentukan nomor trip
            $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $user_warehouse[0]->id)
                ->first();
            $lastRecord = TripModel::where('id_warehouse', $user_warehouse[0]->id)->latest()->first();

            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->created_at)->format('m');
                $currentMonth = Carbon::now()->format('m');
                if ($lastRecordMonth != $currentMonth) {
                    $new_number = 1;
                    $data->id_sort = $new_number;
                } else {
                    $new_number = intval($lastRecord->id_sort) + 1;
                    $data->id_sort = $new_number;
                }
            } else {
                $new_number = 1;
                $data->id_sort = $new_number;
            }
            $length = 3;
            $new_number = str_pad($new_number, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y');
            $month = Carbon::now()->format('m');
            $tahun = substr($year, -2);
            $trip_number = 'BTRPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $new_number;

            // partner
            $employeeIds = [];
            foreach ($request->formPartner as $value) {
                $employeeIds[] = $value['id_employee'];
            }
            $data->id_employee = implode(', ', $employeeIds);
            $data->id_warehouse = $user_warehouse[0]->id;
            $data->trip_number = $trip_number;




            $date = DateTime::createFromFormat('d-m-Y', $request->departure_date);

            if ($date == false) {
                $date = DateTime::createFromFormat('d/m/Y', $request->departure_date);
            }

            $formattedDate = $date->format('Y-m-d');
            $data->departure_date = $formattedDate . ' ' . $request->departure_time;
            $date_ = DateTime::createFromFormat('d-m-Y', $request->return_date);

            if ($date_ == false) {
                $date_ = DateTime::createFromFormat('d/m/Y', $request->return_date);
            }

            $formattedDate_ = $date_->format('Y-m-d');
            $data->return_date = $formattedDate_ . ' ' . $request->return_time;
            // dd($data->return_date);
            $data->transport = $request->transport;
            $data->vehicle = $request->vehicle;

            // expense
            if ($request->fuel_price == null) {
                $data->fuel_price = 0;
            } else {
                $data->fuel_price = $request->fuel_price;
            }
            $data->toll_cost = $request->toll_cost;
            $data->transport_expense = $request->transport_expense;
            $data->acomodation_expense = $request->acomodation_expense;
            $data->other_expense = $request->other_expense;

            //route
            $data->distance_route =str_replace(",", "",$request->distance_route) ;

            // bank
            $data->account_number = $request->account_number;
            $data->account_bank = $request->account_bank;
            $data->account_name  = $request->account_name;
            // dd($data->account_number);

            //purpose
            $data->notes = $request->notes;
            $data->purpose = $request->purpose;

            if ($request->hasFile('pict_odomoter')) {
                $file = $request->file('pict_odomoter');
                $filename = $file->getClientOriginalName();
                $file->move('images/trip', $filename);
                $data->pict_odometer = $filename;
            }


            if ($request->vehicle != null) {
                //pict.vehicle
                if (strpos($data->vehicle, 'Granmax') !== false) {
                    $canvasDataUrl = $request->input('canvasDataUrlGranmax');
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $canvasDataUrl));
                } else {
                    $canvasDataUrl = $request->input('canvasDataUrlMobilio');
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $canvasDataUrl));
                }
                // Menyimpan    gambar sebagai file
                $filename = $data->trip_number . uniqid() . '.png';
                $path = public_path('images/' . $filename);
                file_put_contents($path, $imageData);
                $data->pict_vehicle = $filename;
            }
            $data->save();


            $dataStart = new TripRouteModel();
            $dataStart->id_trip = $data->id;
            $dataStart->place = $request->start;
            $dataStart->save();
            // Menyimpan Route
            $route = explode(',', $request->route);
            foreach ($route as $value) {
                $data_route = new TripRouteModel();
                $data_route->id_trip = $data->id;
                $data_route->place = $value;
                $data_route->save();
            }
            $dataEnd = new TripRouteModel();
            $dataEnd->id_trip = $data->id;
            $dataEnd->place = $request->end;
            $dataEnd->save();
            // dd($request->formVehicle);

            //menyimpan notes
            if ($request->formVehicle != null) {
                foreach ($request->formVehicle as $item) {
                    $dataNotes = new TripVehicleModel();
                    $dataNotes->id_trip = $data->id;
                    $dataNotes->color = $item['color'];
                    $dataNotes->note = $item['notes'];
                    $dataNotes->save();
                }
            }
            $data->save();
            DB::commit();
            return redirect('trip/create')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('trip/create')->with('error', 'Gagal' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        if ($request->ajax()) {
            $data = TripModel::with('employeeBy')
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    // Mengurangkan 1 hari dari 'from_date'
                    $fromDate = date('Y-m-d', strtotime($fromDate . ' -1 day'));
                    // Mengurangkan 1 hari dari 'to_date'
                    $toDate = date('Y-m-d', strtotime($request->to_date . ' +1 day'));
                    return $query->whereBetween('departure_date', [$fromDate, $toDate]);
                }, function ($query) {
                    // Use start and end of the current month as the default date range
                    $startDate = date('Y-m-01');
                    $endDate = date('Y-m-t');
                    return $query->whereBetween('departure_date', [$startDate, $endDate]);
                })
                // ->where('ga_approval', null)
                // ->where('finance_approval', null)
                ->latest();


            return datatables()->of($data)
                ->editColumn('trip_number', function ($data) {
                    return '<span class="fw-bold text-nowrap">'
                        .  $data->trip_number . '</span>';
                })
                ->editColumn('id_employee', function ($data) {
                    $employee = explode(',', $data->id_employee);
                    $employee_name = '';
                    foreach ($employee as $value) {
                        $name_employee = EmployeeModel::where('id', $value)->first();
                        $employee_name .= $name_employee->name . ', ';
                    }
                    $employee_name = rtrim($employee_name, ', ');
                    return $employee_name;
                })


                ->editColumn('departure_date', function ($data) {
                    return Carbon::parse($data->departure_date)->format('d M Y H:i');
                })
                ->editColumn('return_date', function ($data) {
                    return Carbon::parse($data->return_date)->format('d M Y H:i');
                })
                ->editColumn('down_payment', function ($data) {
                    return 'Rp. ' . number_format($data->down_payment, 0, ',', '.');
                })
                ->addIndexColumn()
                ->rawColumns(['departure_date', 'trip_number'])
                ->make(true);
        }
        $datas = [
            'title' => 'Business Trip Proposal History',
            'data' =>  TripModel::with('employeeBy')
                // ->where('ga_approval', null)
                // ->where('finance_approval', null)
                ->latest()
                ->get(),
        ];
        return view('trip.history', $datas);
    }
    public function index_approval(Request $request)
    {
        if ($request->ajax()) {
            $data = TripModel::with('employeeBy')
                ->where('ga_approval', null)
                ->orWhere('finance_approval', null)
                ->latest();

            return datatables()->of($data)
                ->editColumn('trip_number', function ($data) {
                    return '<a href="#" class="fw-bold text-nowrap text-success modal-btn2" data-id="' . $data->id . '"  data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">'
                        .  $data->trip_number . '</a>';
                })
                ->editColumn('id_employee', function ($data) {
                    $employee = explode(',', $data->id_employee);
                    $employee_name = '';
                    foreach ($employee as $value) {
                        $name_employee = EmployeeModel::where('id', $value)->first();
                        $employee_name .= $name_employee->name . ', ';
                    }
                    $employee_name = rtrim($employee_name, ', ');
                    return $employee_name;
                })


                ->editColumn('departure_date', function ($data) {
                    return Carbon::parse($data->departure_date)->format('d M Y H:i');
                })
                ->editColumn('return_date', function ($data) {
                    return Carbon::parse($data->return_date)->format('d M Y H:i');
                })
                ->editColumn('down_payment', function ($data) {
                    return 'Rp. ' . number_format($data->down_payment, 0, ',', '.');
                })
                ->editColumn('status', function ($data) {
                    if (!$data->finance_approval) {
                        return 'Finance Approval';
                    } else {
                        return "GA Approval";
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['departure_date', 'trip_number'])
                ->make(true);
        }
        $datas = [
            'title' => 'Business Trip : GA Approval',
           'data' =>  TripModel::with('employeeBy')
                ->where('ga_approval', null)
                ->orWhere('finance_approval', null)
                ->latest()
                ->get(),
            'data_route' => TripRouteModel::orderBy('created_at', 'asc')->get(),
            'data_vehicle' => TripVehicleModel::orderBy('created_at', 'asc')->get(),
            'vehicle' => AssetModel::where('category_id', 2)->get(),
            'warehouse' => WarehouseModel::where('type', 5)
                ->latest()->get(),
            'warehouse_' => WarehouseModel::where('type', 5)
                ->latest()->get(),
            'customer' => CustomerModel::orderBy('name_cust', 'asc')->get(),
            'destination' => CustomerModel::select('city')
                ->distinct()
                ->orderBy('city', 'asc')
                ->get(),

        ];
        return view('trip.approval', $datas);
    }
    public function finance_approval(Request $request)
    {
        if ($request->ajax()) {
            $data = TripModel::with('employeeBy')
                ->where('ga_approval', null)
                ->orWhere('finance_approval', null)
                ->latest();

            return datatables()->of($data)
                ->editColumn('trip_number', function ($data) {
                    return '<a href="#" class="fw-bold text-nowrap text-success modal-btn2" data-id="' . $data->id . '"  data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">'
                        .  $data->trip_number . '</a>';
                })
                ->editColumn('id_employee', function ($data) {
                    $employee = explode(',', $data->id_employee);
                    $employee_name = '';
                    foreach ($employee as $value) {
                        $name_employee = EmployeeModel::where('id', $value)->first();
                        $employee_name .= $name_employee->name . ', ';
                    }
                    $employee_name = rtrim($employee_name, ', ');
                    return $employee_name;
                })


                ->editColumn('departure_date', function ($data) {
                    return Carbon::parse($data->departure_date)->format('d M Y H:i');
                })
                ->editColumn('return_date', function ($data) {
                    return Carbon::parse($data->return_date)->format('d M Y H:i');
                })
                ->editColumn('down_payment', function ($data) {
                    return 'Rp. ' . number_format($data->down_payment, 0, ',', '.');
                })
                ->editColumn('status', function ($data) {
                    if (!$data->finance_approval) {
                        return "Finance Approval";
                    } else {
                        return "GA Approval";
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['departure_date', 'trip_number'])
                ->make(true);
        }
        $datas = [
            'title' => 'Business Trip : Finance Approval',
            'data' =>  TripModel::with('employeeBy')

               ->where('ga_approval', null)
                ->orWhere('finance_approval', null)
                ->latest()
                ->get(),
            'data_route' => TripRouteModel::get()->reverse(),
            'data_vehicle' => TripVehicleModel::orderBy('created_at', 'asc')->get(),
        ];
        // dd(TripRouteModel::orderBy('created_at', 'asc')->get());
        return view('trip.finance_approval', $datas);
    }

    public function approval(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $data = TripModel::where('id', $request->id)->first();
            $data->ga_approval = Auth::user()->id;
            $data->transport = $request->transport;

            if ($request->transport == 'Own Vehicle') {
                // dd('own');
                $data->vehicle = null;
                // dd($data->vehicle);
            } else if ($request->transport == 'Public Transport') {
                $data->vehicle = null;
                $data->toll_cost = 0;
            }
            if ($data->vehicle != null) {
                if ($data->vehicle == $request->vehicle) {
                    $data->vehicle = $request->vehicle;
                    $canvasDataUrl = $request->input('canvasDataUrlDefault');
                    // dd('default');
                    // dd($canvasDataUrl);
                } else if (strpos($request->vehicle, 'Granmax') !== false) {
                    $data->vehicle = $request->vehicle;
                    $canvasDataUrl = $request->input('canvasDataUrlGranmax');
                    // Ganti canvasDataUrl dengan gambar default jika null
                    if ($canvasDataUrl == null) {
                        $path = asset('images/granmax_.png');
                        $canvasDataUrl = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
                    }
                    // dd('granmax');
                    // dd($canvasDataUrl);
                } else {
                    $data->vehicle = $request->vehicle;
                    $canvasDataUrl = $request->input('canvasDataUrlMobilio');
                    // dd('mobilio');
                    // Ganti canvasDataUrl dengan gambar default jika null
                    if ($canvasDataUrl == null) {
                        $path = asset('images/mobilio.png');
                        $canvasDataUrl = 'data:image/png;base64,' . base64_encode(file_get_contents($path));
                    }
                    // dd($canvasDataUrl);
                }

                if ($canvasDataUrl != null) {
                    // Menyimpan    gambar sebagai file
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $canvasDataUrl));
                    $filename = $data->trip_number . uniqid() . '.png';
                    // dd($filename);
                    $path = public_path('images/' . $filename);
                    file_put_contents($path, $imageData);
                    $data->pict_vehicle = $filename;
                }
            }
            if ($request->hasFile('pict_odomoter')) {
                $file = $request->file('pict_odomoter');
                $filename = $file->getClientOriginalName();
                $file->move('images/trip', $filename);
                $data->pict_odometer = $filename;
            }

            $data->save();

            TripVehicleModel::where('id_trip', $data->id)->delete();
            if ($request->formVehicle != null) {
                foreach ($request->formVehicle as $item) {
                    $dataNotes = new TripVehicleModel();
                    $dataNotes->id_trip = $data->id;
                    $dataNotes->color = $item['color'];
                    $dataNotes->note = $item['notes'];
                    $dataNotes->save();
                }
            }


            if ($data->save()) {
                DB::commit();
                return redirect()->back()->with('success', 'Data has been saved.');
            } else {
                DB::rollback();
                return redirect()->back()->with('error', 'Data failed to save.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e,
            ]);
        }
    }
    public function financeApproval(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = TripModel::where('id', $request->id)->first();
            $data->finance_approval = Auth::user()->employeeBy->id;
            $data->fuel_price = $request->fuel_price;
            $data->toll_cost = $request->toll_cost;
            $data->transport_expense = $request->transport_expense;
            $data->acomodation_expense = $request->acomodation_expense;
            $data->other_expense = $request->other_expense;
            if ($data->save()) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data failed to save.'
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

    public function list_trip(Request $request)
    {
        if ($request->ajax()) {
            $data = TripModel::with('employeeBy')

                ->latest();

            return datatables()->of($data)
                ->editColumn('trip_number', function ($data) {
                    return '<a href="#" class="fw-bold text-nowrap text-success modalItem" data-id="' . $data->id . '"  data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">'
                        .  $data->trip_number . '</a>';
                })
                ->editColumn('id_employee', function ($data) {
                    return $data->employeeBy->name;
                })

                ->editColumn('departure_date', function ($data) {
                    return Carbon::parse($data->departure_date)->format('d M Y H:i') . ' - ' . Carbon::parse($data->return_date)->format('d M Y H:i');
                })
                ->editColumn('departure', function ($data) {
                    return $data->departure . ' - ' . $data->destination;
                })
                ->editColumn('down_payment', function ($data) {
                    return 'Rp. ' . number_format($data->down_payment, 0, ',', '.');
                })
                ->addIndexColumn()
                ->rawColumns(['departure_date', 'trip_number'])
                ->make(true);
        }
        $datas = [
            'title' => 'List of All Business Trips',
            'data' =>  TripModel::with('employeeBy')

                ->latest()
                ->get(),
                
        ];
        return view('trip.list_trip', $datas);
    }

    public function trip_list($request)
    {
        if ($request->ajax()) {
            $data = TripModel::with('employeeBy')
                ->where('known_by', null)
                ->where('approved_by', null)
                ->latest();

            return datatables()->of($data)
                ->editColumn('trip_number', function ($data) {
                    return '<a href="#" class="fw-bold text-nowrap text-success modalItem" data-id="' . $data->id . '"  data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">'
                        .  $data->trip_number . '</a>';
                })
                ->editColumn('id_employee', function ($data) {
                    $employee = explode(',', $data->id_employee);
                    $employee_name = '';
                    foreach ($employee as $value) {
                        $name_employee = EmployeeModel::where('id', $value)->first();
                        $employee_name .= $name_employee->name . ', ';
                    }
                    $employee_name = rtrim($employee_name, ', ');
                    return $employee_name;
                })


                ->editColumn('departure_date', function ($data) {
                    return Carbon::parse($data->departure_date)->format('d M Y H:i');
                })
                ->editColumn('return_date', function ($data) {
                    return Carbon::parse($data->return_date)->format('d M Y H:i');
                })
                ->addIndexColumn()
                ->rawColumns(['departure_date', 'trip_number'])
                ->make(true);
        }
        $datas = [
            'title' => 'Trip Proposal List',
            'data' =>  TripModel::with('employeeBy')
                ->where('known_by', null)
                ->where('approved_by', null)
                ->latest()
                ->get(),
            'data_route' => TripRouteModel::orderBy('created_at', 'asc')->get(),
            'data_vehicle' => TripVehicleModel::orderBy('created_at', 'asc')->get(),
        ];
        return view('trip.trip_list', $datas);
    }

    public function trip_completed_approval_ga(Request $request)
    {
        if ($request->ajax()) {
            $data = TripModel::with('employeeBy', 'completedBy')
                ->where('status_lpd', 1)
                ->whereHas('completedBy', function ($query) {
                    $query->where('approval', 'In Progress');
                    // $query->where('approval_ga', null);
                    // $query->where('approval_finance', null);
                })
                ->latest();
            return datatables()->of($data)
                ->editColumn('trip_number', function ($item) {
                    return view('trip._option_completed_approval', compact('item'))->render();
                    // return '<a href="#" class="fw-bold text-nowrap modalItem ' .
                    // ($data->status_lpd == 0 ? 'text-danger' : 'text-success') . '" data-id="' . $data->id . '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">'
                    //     .  $data->trip_number . '</a>';
                })
                ->editColumn('employees', function ($data) {
                    $getname = explode(',', $data->id_employee);
                    $arr_name = '';
                    foreach ($getname as $value) {
                        $getemployee = EmployeeModel::where('id', $value)->first();
                        $arr_name .=  $getemployee->name . ', ';
                    }

                    return rtrim($arr_name, ', ');
                })
                ->editColumn('departure_date', function ($data) {
                    return Carbon::parse($data->departure_date)->format('d F Y') . ' - ' . Carbon::parse($data->return_date)->format('d F Y');
                })
                ->editColumn('submission', function ($data) {
                    return Carbon::parse($data->completedBy->propose_date)->format('d F Y');
                })
                ->editColumn('status', function ($data) {
                    // return $data->completedBy->approval_ga;
                    if ($data->completedBy->approval_ga) {
                        return "Finance Approval";
                    } else {
                        return "GA Approval";
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['departure_date', 'trip_number'])
                ->make(true);
        }
        $datas = [
            'title' => 'Report Trip: GA Approval',
            'data' =>  TripModel::with('employeeBy')
                ->latest()
                ->get(),
        ];
        return view('trip.completed_approval_trip', $datas);
    }


    public function trip_completed_approval_finance(Request $request)
    {
        if ($request->ajax()) {
            $data = TripModel::with('employeeBy', 'completedBy')
                ->where('status_lpd', 1)
                ->whereHas('completedBy', function ($query) {
                    $query->where('approval', 'In Progress');
                    // $query->where('approval_ga', '!=', null);
                    // $query->where('approval_finance', null);
                })
                ->latest();
            return datatables()->of($data)
                ->editColumn('trip_number', function ($item) {
                    return view('trip._option_completed_approval_finance', compact('item'))->render();
                    // return '<a href="#" class="fw-bold text-nowrap modalItem ' .
                    // ($data->status_lpd == 0 ? 'text-danger' : 'text-success') . '" data-id="' . $data->id . '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">'
                    //     .  $data->trip_number . '</a>';
                })
                ->editColumn('employees', function ($data) {
                    $getname = explode(',', $data->id_employee);
                    $arr_name = '';
                    foreach ($getname as $value) {
                        $getemployee = EmployeeModel::where('id', $value)->first();
                        $arr_name .=  $getemployee->name . ', ';
                    }

                    return rtrim($arr_name, ', ');
                })
                ->editColumn('departure_date', function ($data) {
                    return Carbon::parse($data->departure_date)->format('d F Y') . ' - ' . Carbon::parse($data->return_date)->format('d F Y');
                })
                ->editColumn('submission', function ($data) {
                    return Carbon::parse($data->completedBy->propose_date)->format('d F Y');
                })
                ->editColumn('status', function ($data) {
                    if ($data->completedBy->approval_ga) {
                        return "Finance Approval";
                    } else {
                        return "GA Approval";
                    }
                })
                ->addIndexColumn()
                ->rawColumns(['departure_date', 'trip_number'])
                ->make(true);
        }
        $datas = [
            'title' => 'Report Trip: Finance Approval',
            'data' =>  TripModel::with('employeeBy')
                ->latest()
                ->get(),
        ];
        return view('trip.completed_approval_trip_finance', $datas);
    }

    public function trip_completed_approve_ga(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            //Select Trip
            $selected_trip = TripCompletedModel::where('trip_id', $id)->first();
            $selected_trip->approval_ga = Auth::user()->id;
            $selected_trip->save();

            if ($selected_trip->img_vehicle != null) {
                //pict.vehicle
                $canvasDataUrl = $request->input('canvasDataUrlGranmax');
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $canvasDataUrl));

                //Delete Old
                $path_vehicle = public_path('images/') . $selected_trip->img_vehicle;
                if (File::exists($path_vehicle)) {
                    File::delete($path_vehicle);
                }

                // Menyimpan    gambar sebagai file
                $filename = 'new_' . $selected_trip->img_vehicle;
                $path = public_path('images/' . $filename);
                file_put_contents($path, $imageData);
                $selected_trip->img_vehicle = $filename;

                $selected_trip->save();

                foreach ($selected_trip->annotationBy as $anno) {
                    $anno->delete();
                }

                if($request->formVehicle){
                    foreach ($request->formVehicle as $item) {
                        $dataNotes = new TripVehicleCompletedModel();
                        $dataNotes->id_trip = $selected_trip->id;
                        $dataNotes->color = $item['color'];
                        $dataNotes->note = $item['notes'];
                        $dataNotes->save();
                    }
                }
            }

            DB::commit();
            return redirect('/trip/completed/ga_approval')->with('success', 'Proposal trip report approved');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
    }

    public function trip_completed_reject_ga($id)
    {
        try {
            DB::beginTransaction();
            //Select Trip
            $selected_trip = TripCompletedModel::where('trip_id', $id)->first();
            foreach ($selected_trip->detailBy as $value) {
                $value->delete();
            }
            $selected_trip->delete();
            $current_trip = TripModel::where('id', $id)->first();
            $current_trip->status_lpd = 0;
            $current_trip->save();
            DB::commit();
            return redirect('/trip/completed/ga_approval')->with('error', 'Proposal trip report rejected');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
    }
    
    public function trip_completed_reject_finance($id)
    {
        try {
            DB::beginTransaction();
            //Select Trip
            $selected_trip = TripCompletedModel::where('trip_id', $id)->first();
            foreach ($selected_trip->detailBy as $value) {
                $value->delete();
            }
            $selected_trip->delete();
            $current_trip = TripModel::where('id', $id)->first();
            $current_trip->status_lpd = 0;
            $current_trip->save();
            DB::commit();
            return redirect('/trip/completed/finance_approval')->with('error', 'Proposal trip report rejected');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
    }

    public function trip_completed_approve_finance(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            //Select Trip
            $selected_trip = TripCompletedModel::where('trip_id', $id)->first();
            $selected_trip->approval = 'Approved';
            $selected_trip->approval_finance = Auth::user()->id;

            foreach ($selected_trip->detailBy as $key => $value) {
                $value->delete();
            }

            //Save Completed trip Detail
            $total = 0;
            foreach ($request->expense as $detail) {
                $new_detail = new TripCompletedDetailModel();
                $new_detail->completed_trip_id = $selected_trip->id;
                // dd($detail);
                $new_detail->date = date('Y-m-d', strtotime($detail['date']));
                $new_detail->description = $detail['desc'];
                $new_detail->transport = $detail['transport'];
                $new_detail->accommodation = $detail['acomodation'];
                $new_detail->perdiem = $detail['per_diem'];
                // $new_detail->toll = $detail['toll'];
                $new_detail->other = $detail['other'];
                $new_detail->save();
                
                $current_total = $new_detail->transport + $new_detail->accommodation + $new_detail->perdiem + $new_detail->other;
                $total += $current_total;
            }
            $selected_trip->total = $total;
            $selected_trip->save();

            $getname = explode(',', $selected_trip->tripBy->id_employee);
            $arr_name = '';
            $arr_div = '';
            foreach ($getname as $value) {
                $getemployee = EmployeeModel::where('id', $value)->first();
                $arr_name =  $getemployee->name;
                $arr_div = $getemployee->job;
            }
            // dd($arr_name, $arr_div);
            //Save Journal
            // $journal = new JurnalModel();
            // $journal->date = Carbon::now()->format('Y-m-d');
            // $journal->memo = 'Perjalanan Dinas No.' . $selected_trip->tripBy->trip_number . ' (' . $arr_name . ', ' . $arr_div . ') ';
            // $journal->save();

            // if ($journal->save()) {
            //     $journal_id = $journal->id;


            //     // akun piutang
            //     $akun_hutang = new JurnalDetailModel();
            //     $akun_hutang->expenses_id = $journal_id;
            //     $akun_hutang->account_id = '500.07.03';
            //     $akun_hutang->ref = $selected_trip->tripBy->trip_number;
            //     $akun_hutang->debit = $selected_trip->total;
            //     $akun_hutang->credit = null;
            //     $akun_hutang->save();

            //     // akun penjualan
            //     $akun_pembelian = new JurnalDetailModel();
            //     $akun_pembelian->expenses_id = $journal_id;
            //     $akun_pembelian->account_id = '100.01.02';
            //     $akun_pembelian->ref = $selected_trip->tripBy->trip_number;
            //     $akun_pembelian->debit = null;
            //     $akun_pembelian->credit = $selected_trip->total;
            //     $akun_pembelian->save();
            // }
            DB::commit();
            return redirect('/trip/completed/finance_approval')->with('success', 'Proposal trip report approved');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
    }

    public function trip_completed(Request $request)
    {
        if ($request->ajax()) {
            $data = TripModel::with('employeeBy', 'completedBy')
                ->when($request->status, function ($q) use ($request) {
                    if ($request->status == 'In Progress') {
                        return $q->where('status_lpd', 1);
                    } else {
                        return $q->where('status_lpd', 0)->where('ga_approval', '!=', null)->where('finance_approval', '!=', null);
                    }
                }, function ($q) {
                    return $q->where('status_lpd', 0)->where('ga_approval', '!=', null)->where('finance_approval', '!=', null);
                })
                ->when($request->start_date, function ($q) use ($request) {
                    return $q->whereHas('completedBy', function ($query) use ($request) {
                        $query->whereBetween('propose_date', array($request->start_date, $request->end_date));
                    });
                })
                ->latest();
            return datatables()->of($data)
                ->editColumn('trip_number', function ($item) {
                    $vehicle =  AssetModel::where('category_id', 2)->get();
                    return view('trip._option_completed', compact('item', 'vehicle'))->render();
                    // return '<a href="#" class="fw-bold text-nowrap modalItem ' .
                    // ($data->status_lpd == 0 ? 'text-danger' : 'text-success') . '" data-id="' . $data->id . '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' . $data->id . '">'
                    //     .  $data->trip_number . '</a>';
                })
                ->editColumn('id_employee', function ($data) {
                    $employee = explode(',', $data->id_employee);
                    $employee_name = '';
                    foreach ($employee as $value) {
                        $name_employee = EmployeeModel::where('id', $value)->first();
                        $employee_name .= $name_employee->name . ', ';
                    }
                    $employee_name = rtrim($employee_name, ', ');
                    return $employee_name;
                })

                ->editColumn('departure_date', function ($data) {
                    return Carbon::parse($data->departure_date)->format('d M Y H:i') . ' - ' . Carbon::parse($data->return_date)->format('d M Y H:i');
                })
                ->editColumn('departure', function ($data) {
                    return $data->departure . ' - ' . $data->destination;
                })
                ->addIndexColumn()
                ->rawColumns(['departure_date', 'trip_number'])
                ->make(true);
        }
        $datas = [
            'title' => 'Report Trip Proposal',
            'data' =>  TripModel::with('employeeBy')
                ->latest()
                ->get(),
            'vehicle' => AssetModel::where('category_id', 2)->get(),
        ];
        return view('trip.completed_trip', $datas);
    }

    public function trip_completed_list(Request $request)
    {
        // $data = TripModel::with('employeeBy', 'completedBy')
        //     ->where('status_lpd', 1)
        //     ->whereHas('completedBy', function ($query) use ($request) {
        //         $query->where('approval', 'Approved');
        //         $query->whereBetween('propose_date', ["2023-06-01", "2023-06-30"]);
        //     })->get();
        // dd($data);
        if ($request->ajax()) {
            $data = TripModel::with('employeeBy', 'completedBy')
                ->where('status_lpd', 1)
                ->whereHas('completedBy', function ($query) use ($request) {
                    $query->where('approval', 'Approved');
                    $query->when($request->start_date, function ($q) use ($request) {
                        // dd($request->ajax());
                        $q->whereBetween('propose_date', [$request->start_date, $request->end_date]);
                    }, function ($q) {
                        $q->whereMonth('propose_date', Carbon::now()->month)->whereYear('propose_date', Carbon::now()->year);
                    });
                })
                ->get();
            return datatables()->of($data)
                ->editColumn('trip_number', function ($item) {
                    $data_route= TripRouteModel::get()->reverse();
                    return view('trip._option_completed_list', compact('item', 'data_route'))->render();
                })

                ->editColumn('employees', function ($data) {
                    $getname = explode(',', $data->id_employee);
                    $arr_name = '';
                    foreach ($getname as $value) {
                        $getemployee = EmployeeModel::where('id', $value)->first();
                        $arr_name .=  $getemployee->name . ', ';
                    }

                    return rtrim($arr_name, ', ');
                })
                ->editColumn('departure_date', function ($data) {
                    return Carbon::parse($data->departure_date)->format('d F Y') . ' - ' . Carbon::parse($data->return_date)->format('d F Y');
                })
                ->editColumn('submission', function ($data) {
                    return Carbon::parse($data->completedBy->propose_date)->format('d F Y');
                })
                ->addIndexColumn()
                ->rawColumns(['departure_date', 'trip_number'])
                ->make(true);
        }
        
        $datas = [
            'title' => 'Report Trip List',
            'data' =>  TripModel::with('employeeBy')
                ->latest()
                ->get(),
            
        ];
        // dd($datas);
        return view('trip.completed_trip_list', $datas);
    }

    public function trip_completed_print($id)
    {
        $item = TripModel::where('id', $id)->first();
        return view('trip.completed_trip_print', compact('item'));
    }


    public function trip_completed_store(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            //Select Trip
            $selected_trip = TripModel::where('id', $id)->first();
            $selected_trip->status_lpd = 1;
            $selected_trip->save();

            //Save Completed Trip
            $new_trip_completed = new TripCompletedModel();
            $new_trip_completed->trip_id = $id;
            $new_trip_completed->propose_date = date('Y-m-d');
            $new_trip_completed->approval = "In Progress";
            $new_trip_completed->total = $request->sub_total;
            $new_trip_completed->created_by = Auth::user()->id;

            if ($selected_trip->transport == 'Own Vehicle' || $selected_trip->transport == 'Public Transport') {
                $new_trip_completed->img_vehicle = null;
            } else {
                //pict.vehicle
                $canvasDataUrl = $request->input('canvasDataUrlGranmax');
                // dd($canvasDataUrl);
                $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $canvasDataUrl));

                if ($canvasDataUrl != null) {
                    // Menyimpan    gambar sebagai file
                    $filename = $selected_trip->trip_number . uniqid() . '_completed.png';
                    $path = public_path('images/' . $filename);
                    file_put_contents($path, $imageData);
                    $new_trip_completed->img_vehicle = $filename;
                } else {
                    $new_trip_completed->img_vehicle = $selected_trip->pict_vehicle;
                }
            }

            $file_ref = $request->file('evidence');
            if ($file_ref) {
                $name_file_ref = time() . '_' . $file_ref->getClientOriginalName();
                $file_ref->storeAs('pdf/trip/evidence', $name_file_ref);
            } else {
                $name_file_ref = "-";
            }
            $new_trip_completed->evidence = $name_file_ref;

            $file_odometer = $request->file('odometer');
            if ($file_odometer) {
                $name_file_odometer = time() . '_' . $file_odometer->getClientOriginalName();
                $file_odometer->move(public_path('images/trip/'), $name_file_odometer);
                $new_trip_completed->img_odometer = $name_file_odometer;
            }
            $new_trip_completed->save();

            //Save Completed trip Detail
            foreach ($request->expense as $detail) {
                $new_detail = new TripCompletedDetailModel();
                $new_detail->completed_trip_id = $new_trip_completed->id;
                // dd($detail);
                $new_detail->date = date('Y-m-d', strtotime($detail['date']));
                $new_detail->description = $detail['desc'];
                $new_detail->transport = $detail['transport'];
                $new_detail->accommodation = $detail['acomodation'];
                $new_detail->perdiem = $detail['per_diem'];
                // $new_detail->toll = $detail['toll'];
                $new_detail->other = $detail['other'];
                $new_detail->save();
            }

            if ($request->formVehicle != null ||  $request->formVehicle != '') {
                foreach ($request->formVehicle as $item) {
                    $dataNotes = new TripVehicleCompletedModel();
                    $dataNotes->id_trip = $new_trip_completed->id;
                    $dataNotes->color = $item['color'];
                    $dataNotes->note = $item['notes'];
                    $dataNotes->save();
                }
            }



            DB::commit();
            return redirect('/trip/completed')->with('success', 'Create Proposal Trip Report Success');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
        }
    }
}
