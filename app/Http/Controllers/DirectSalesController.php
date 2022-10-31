<?php

namespace App\Http\Controllers;

use App\Models\CarBrandModel;
use App\Models\DirectSalesDetailModel;
use App\Models\DirectSalesModel;
use App\Models\DistrictModel;
use App\Models\MotorBrandModel;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\SubMaterialModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

class DirectSalesController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            if (!empty($request->from_date)) {

                $direct = DirectSalesModel::with('createdBy', 'carBrandBy', 'carTypeBy', 'motorBrandBy', 'motorTypeBy')
                    ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {

                $direct = DirectSalesModel::with('createdBy', 'carBrandBy', 'carTypeBy', 'motorBrandBy', 'motorTypeBy')
                    ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->latest()
                    ->get();
            }
            return datatables()->of($direct)
                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data->order_date));
                })
                ->editColumn('car_brand_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->car_brand_id == null) {
                        return '-';
                    } else return $directSalesModel->carBrandBy->car_brand;
                })
                ->editColumn('car_type_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->car_type_id == null) {
                        return '-';
                    } else return $directSalesModel->carTypeBy->car_type;
                })
                ->editColumn('motor_brand_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->motor_brand_id == null) {
                        return '-';
                    } else return $directSalesModel->motorBrandBy->name_brand;
                })
                ->editColumn('motor_type_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->motor_type_id == null) {
                        return '-';
                    } else return $directSalesModel->motorTypeBy->name_type;
                })
                ->editColumn('total_excl', function ($data) {
                    return number_format($data->total_excl, 0, ',', '.');
                })
                ->editColumn('total_ppn', function ($data) {
                    return number_format($data->total_ppn, 0, ',', '.');
                })
                ->editColumn('total_incl', function ($data) {
                    return number_format($data->total_incl, 0, ',', '.');
                })
                ->editColumn('created_by', function (DirectSalesModel $directSalesModel) {
                    return $directSalesModel->createdBy->name;
                })
                ->editColumn('isPaid', function ($data) {
                    if ($data->isPaid == 1) {
                        return 'Paid';
                    } else return 'Unpaid';
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($direct) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    $car_brands = CarBrandModel::with('typeBy')->oldest('car_brand')->get();
                    $motor_brands = MotorBrandModel::with('typeBy')->oldest('name_brand')->get();
                    return view('direct_sales._option', compact('direct', 'ppn', 'car_brands', 'motor_brands'))->render();
                })
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = [
            "title" => "Invoicing Retail",
            "ppn" => $ppn
            // 'order_number' =>
        ];

        return view('direct_sales.index', $data);
    }

    public function create()
    {
        $retail_products = ProductModel::with(['stockBy', 'materials', 'sub_materials', 'sub_types', 'uoms'])
            ->whereIn('shown', ['all', 'retail'])
            ->whereHas('stockBy', function ($query) {
                $query->where('warehouses_id', Auth::user()->warehouse_id);
            })->get();
        $car_brands = CarBrandModel::with('typeBy')->oldest('car_brand')->get();
        $motor_brands = MotorBrandModel::with('typeBy')->oldest('name_brand')->get();
        $sub_materials = SubMaterialModel::all();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $districts = DistrictModel::all();

        $data = [
            'title' => 'Create Retail Order',
            'retail_products' => $retail_products,
            'car_brands' => $car_brands,
            'motor_brands' => $motor_brands,
            'sub_materials' => $sub_materials,
            'ppn' => $ppn,
            'districts' => $districts
        ];

        return view('direct_sales.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // validate
        $request->validate([
            'cust_name' => 'required',
            'cust_phone' => 'required',
            'cust_ktp' => 'required',
            'cust_email' => 'required',
            'district' => 'required',
            'plate_number' => 'required',
            'vehicle' => 'required',
            'address' => 'required',
            'remark' => 'required',
            'retails.*.product_id' => 'required',
            'retails.*.qty' => 'required',
            'retails.*.discount' => 'required',
            'total_excl' => 'required|numeric',
            'total_ppn' => 'required|numeric',
            'total_incl' => 'required|numeric'
        ]);

        $model = new DirectSalesModel();

        //Create Order Number
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', Auth::user()->warehouse_id)
            ->first();
        $length = 3;
        $id = intval(DirectSalesModel::where('order_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $direct_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $order_number = 'RSPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $direct_number_id;
        $model->order_number = $order_number;

        $model->order_date = date('Y-m-d');
        $model->cust_name = $request->cust_name;
        $model->cust_phone = $request->cust_phone;
        $model->cust_ktp = $request->cust_ktp;
        $model->cust_email = $request->cust_email;
        $model->district = $request->district;
        $model->address = $request->address;
        $model->created_by = Auth::user()->id;
        $model->plate_number = strtoupper(str_replace(' ', '', $request->plate_number));
        if ($request->vehicle == 'Car') {
            $model->car_brand_id = $request->car_brand_id;
            $model->car_type_id = $request->car_type_id;
            $model->motor_brand_id = null;
            $model->motor_type_id = null;
        } else {
            $model->car_brand_id = null;
            $model->car_type_id = null;
            $model->motor_brand_id = $request->motor_brand_id;
            $model->motor_type_id = $request->motor_type_id;
        }
        $model->remark = $request->remark;
        $model->total_excl = $request->total_excl;
        $model->total_ppn = $request->total_ppn;
        $model->total_incl = $request->total_incl;
        if ($request->payment == "cash") {
            $model->isPaid = 1;
        } else {
            $model->isPaid = 0;
        }
        $model->pdf_invoice = $model->order_number . '.pdf';
        $model->pdf_do = $model->order_number . '.pdf';
        $saved = $model->save();

        if (!$saved) {
            return redirect('/retail')->with('error', 'Create Order Fail! Please check again the inputs.');
        }

        foreach ($request->retails as $item) {
            $detail = new DirectSalesDetailModel();
            $detail->direct_id = $model->id;
            $detail->product_id = $item['product_id'];
            $detail->qty = $item['qty'];
            $detail->discount = $item['discount'];
            $detail->save();

            //Change stock
            $getStock = StockModel::where('products_id', $item['product_id'])
                ->where('warehouses_id', Auth::user()->warehouse_id)
                ->first();

            $old_stock = $getStock->stock;
            $getStock->stock = $old_stock - $item['qty'];
            if ($getStock->stock < 0) {
                DirectSalesDetailModel::where('direct_id', $model->id)->delete();
                DirectSalesModel::where('id', $model->id)->delete();
                return Redirect::back()->with('error', 'Verification Fail! Not enough stock. Please re-confirm to the customer.');
            } else {
                $getStock->save();
            }
        }

        return redirect('/retail')->with('success', 'Create Order Success!');
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

    public function selectProductAll()
    {
        $sub_materials = [];
        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = ProductModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                ->with(['stockBy', 'uoms'])
                ->whereIn('shown', ['all', 'retail'])
                ->whereHas('stockBy', function ($query) {
                    $query->where('warehouses_id', Auth::user()->warehouse_id);
                })
                ->where('nama_barang', 'LIKE', "%$search%")
                ->get();
        } else {
            $sub_materials = ProductModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                ->with(['stockBy', 'uoms'])
                ->whereIn('shown', ['all', 'retail'])
                ->whereHas('stockBy', function ($query) {
                    $query->where('warehouses_id', Auth::user()->warehouse_id);
                })->get();
        }
        return response()->json($sub_materials);
    }

    public function search()
    {
        $sub_materials = [];
        $search = request()->q;
        if (!empty($search)) {
            $sub_materials = ProductModel::with(['stockBy', 'materials', 'sub_materials', 'sub_types', 'uoms'])
                ->whereIn('shown', ['all', 'retail'])
                ->whereHas('stockBy', function ($query) {
                    $query->where('warehouses_id', Auth::user()->warehouse_id);
                })->where('nama_barang', 'LIKE', "%$search%")
                ->get();
        } else {
            $sub_materials = ProductModel::with(['stockBy', 'materials', 'sub_materials', 'sub_types', 'uoms'])
                ->whereIn('shown', ['all', 'retail'])
                ->whereHas('stockBy', function ($query) {
                    $query->where('warehouses_id', Auth::user()->warehouse_id);
                })
                ->get();
        }


        return response()->json($sub_materials);
    }

    public function selectById()
    {
        $sub_materials = [];
        $search = request()->s;
        if ($search != "all") {
            $sub_materials = ProductModel::with(['stockBy', 'materials', 'sub_materials', 'sub_types', 'uoms'])
                ->whereIn('shown', ['all', 'retail'])
                ->whereHas('stockBy', function ($query) {
                    $query->where('warehouses_id', Auth::user()->warehouse_id);
                })->where('id_sub_material', $search)
                ->get();
        } else {
            $sub_materials = ProductModel::with(['stockBy', 'materials', 'sub_materials', 'sub_types', 'uoms'])
                ->whereIn('shown', ['all', 'retail'])
                ->whereHas('stockBy', function ($query) {
                    $query->where('warehouses_id', Auth::user()->warehouse_id);
                })
                ->get();
        }


        return response()->json($sub_materials);
    }

    public function credit(Request $request)
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }
        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            if (!empty($request->from_date)) {

                $direct = DirectSalesModel::with('createdBy', 'carBrandBy', 'carTypeBy', 'motorBrandBy', 'motorTypeBy')
                    ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->where('isPaid', 0)
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {

                $direct = DirectSalesModel::with('createdBy', 'carBrandBy', 'carTypeBy', 'motorBrandBy', 'motorTypeBy')
                    ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->where('isPaid', 0)
                    ->latest()
                    ->get();
            }
            return datatables()->of($direct)
                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data->order_date));
                })
                ->editColumn('car_brand_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->car_brand_id == null) {
                        return '-';
                    } else return $directSalesModel->carBrandBy->car_brand;
                })
                ->editColumn('car_type_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->car_type_id == null) {
                        return '-';
                    } else return $directSalesModel->carTypeBy->car_type;
                })
                ->editColumn('motor_brand_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->motor_brand_id == null) {
                        return '-';
                    } else return $directSalesModel->motorBrandBy->name_brand;
                })
                ->editColumn('motor_type_id', function (DirectSalesModel $directSalesModel) {
                    if ($directSalesModel->motor_type_id == null) {
                        return '-';
                    } else return $directSalesModel->motorTypeBy->name_type;
                })
                ->editColumn('total_excl', function ($data) {
                    return number_format($data->total_excl, 0, ',', '.');
                })
                ->editColumn('total_ppn', function ($data) {
                    return number_format($data->total_ppn, 0, ',', '.');
                })
                ->editColumn('total_incl', function ($data) {
                    return number_format($data->total_incl, 0, ',', '.');
                })
                ->editColumn('created_by', function (DirectSalesModel $directSalesModel) {
                    return $directSalesModel->createdBy->name;
                })
                ->editColumn('isPaid', function ($data) {
                    if ($data->isPaid == 1) {
                        return 'Paid';
                    } else return 'Unpaid';
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($direct) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('direct_sales._option', compact('direct', 'ppn'))->render();
                })
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = [
            "title" => "Invoicing Credit Retail",
            "ppn" => $ppn
            // 'order_number' =>
        ];

        return view('direct_sales.credit', $data);
    }

    public function print_invoice($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $data = DirectSalesModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $data->pdf_invoice = $data->order_number . '.pdf';
        $data->save();

        $ppn = ValueAddedTaxModel::first()->ppn / 100;

        $pdf = Pdf::loadView('direct_sales.print_invoice', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $data->order_number . '.pdf');

        return $pdf->download($data->pdf_invoice);
    }

    public function mark_as_paid($id)
    {
        $selected_direct = DirectSalesModel::where('id', $id)->first();
        $selected_direct->isPaid = 1;
        $selected_direct->save();
        return Redirect::back()->with('success', 'Paid Success!');
    }

    public function update_retail(Request $request, $id)
    {
        if (
            !Gate::allows('isSuperAdmin')
        ) {
            abort(403);
        }
        // Validate Input
        $request->validate([
            'cust_name' => 'required',
            'cust_phone' => 'required',
            'cust_ktp' => 'required',
            'cust_email' => 'required',
            'district' => 'required',
            'plate_number' => 'required',
            'address' => 'required',
            'remark' => 'required',
            'retails.*.product_id' => 'required',
            'retails.*.qty' => 'required',
            'retails.*.discount' => 'required',

        ]);

        //Check Number of product
        if ($request->retails == null) {
            return Redirect::back()->with('error', 'There are no products!');
        }

        //Check Duplicate
        $products_arr = [];

        foreach ($request->retails as $check) {
            array_push($products_arr, $check['product_id']);
            $getstock = StockModel::where('products_id', $check['product_id'])->where('warehouses_id', Auth::user()->warehouse_id)->first();
            if ($check['qty'] > $getstock->stock) {
                return Redirect::back()->with('error', 'Edit Fail! The number of items exceeds the stock.');
            }
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return Redirect::back()->with('error', 'Edit Fail! You enter duplicate product.');
        }

        $selected_direct = DirectSalesModel::where('id', $id)->first();
        $selected_direct->cust_name = $request->cust_name;
        $selected_direct->cust_phone = $request->cust_phone;
        $selected_direct->cust_ktp = $request->cust_ktp;
        $selected_direct->cust_email = $request->cust_email;
        $selected_direct->district = $request->district;
        $selected_direct->address = $request->address;
        $selected_direct->plate_number = strtoupper(str_replace(' ', '', $request->plate_number));
        if ($request->vehicle == 'Car') {
            $selected_direct->car_brand_id = $request->car_brand_id;
            $selected_direct->car_type_id = $request->car_type_id;
            $selected_direct->motor_brand_id = null;
            $selected_direct->motor_type_id = null;
        } else {
            $selected_direct->car_brand_id = null;
            $selected_direct->car_type_id = null;
            $selected_direct->motor_brand_id = $request->motor_brand_id;
            $selected_direct->motor_type_id = $request->motor_type_id;
        }
        $selected_direct->remark = $request->remark;

        //Restore stock to before changed
        $direct_restore = DirectSalesDetailModel::where('direct_id', $id)->get();
        foreach ($direct_restore as $restore) {
            $stock = StockModel::where('warehouses_id', Auth::user()->warehouse_id)
                ->where('products_id', $restore->product_id)->first();
            $stock->stock = $stock->stock + $restore->qty;
            $stock->save();
        }

        //Save Return Input and Total and Change Stock
        $total = 0;

        foreach ($request->retails as $product) {
            $product_exist = DirectSalesDetailModel::where('direct_id', $id)
                ->where('product_id', $product['product_id'])->first();

            if ($product_exist != null) {
                $old_qty = $product_exist->qty;
                $product_exist->qty = $product['qty'];
                $product_exist->discount = $product['discount'];
                $product_exist->save();
            } else {
                $new_product = new DirectSalesDetailModel();
                $new_product->direct_id = $id;
                $new_product->product_id = $product['product_id'];
                $new_product->qty = $product['qty'];
                $new_product->discount = $product['discount'];
                $new_product->save();
            }
            //Count Total
            $products = ProductModel::where('id', $product['product_id'])->first();
            $diskon =  $product['discount'] / 100;
            $hargaDiskon = $products->harga_jual * $diskon;
            $hargaAfterDiskon = ($products->harga_jual -  $hargaDiskon);
            $total = $total + ($hargaAfterDiskon * $product['qty']);

            //Change Stock
            $getStock = StockModel::where('products_id', $product['product_id'])
                ->where('warehouses_id', Auth::user()->warehouse_id)
                ->first();
            $old_stock = $getStock->stock;
            $getStock->stock = $old_stock - $product['qty'];
            $getStock->save();
        }
        $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $total;
        $selected_direct->total_excl = $total;
        $selected_direct->total_ppn = $ppn;
        $selected_direct->total_incl = $total + $ppn;
        $saved = $selected_direct->save();

        return redirect('/retail')->with('success', 'Edit Invoice Retail Success!');
    }
}
