<?php

namespace App\Http\Controllers;

use App\Models\CarBrandModel;
use App\Models\CustomerModel;
use App\Models\DirectSalesDetailModel;
use App\Models\DirectSalesModel;
use App\Models\DistrictModel;
use App\Models\MotorBrandModel;
use App\Models\ProductCostModel;
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
use Illuminate\Support\Facades\Http;
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
                $direct = DirectSalesModel::with('createdBy', 'carBrandBy', 'carTypeBy', 'motorBrandBy', 'motorTypeBy', 'directSalesDetailBy', 'directSalesDetailBy.productBy', 'directSalesDetailBy.retailPriceBy')
                    ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $direct = DirectSalesModel::with('createdBy', 'carBrandBy', 'carTypeBy', 'motorBrandBy', 'motorTypeBy', 'directSalesDetailBy', 'directSalesDetailBy.productBy', 'directSalesDetailBy.retailPriceBy')
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
                ->editColumn('cust_name', function (DirectSalesModel $directSalesModel) {
                    if (is_numeric($directSalesModel->cust_name)) {
                        return $directSalesModel->customerBy->name_cust;
                    } else return $directSalesModel->cust_name;
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
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($direct) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    $car_brands = CarBrandModel::with('typeBy')->oldest('car_brand')->get();
                    $motor_brands = MotorBrandModel::with('typeBy')->oldest('name_brand')->get();
                    $customers = CustomerModel::latest()->get();
                    return view('direct_sales._option', compact('direct', 'ppn', 'car_brands', 'motor_brands', 'customers'))->render();
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
        $retail_products = ProductCostModel::with(['productBy', 'warehouseBy', 'productBy.sub_materials', 'productBy.stockBy', 'productBy.sub_types', 'productBy.uoms'])
            ->whereHas('productBy', function ($query) {
                $query->whereIn('shown', ['all', 'retail']);
            })
            ->where('id_warehouse', Auth::user()->warehouse_id)
            ->get();
        $car_brands = CarBrandModel::with('typeBy')->oldest('car_brand')->get();
        $motor_brands = MotorBrandModel::with('typeBy')->oldest('name_brand')->get();
        $sub_materials = SubMaterialModel::all();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $customers = CustomerModel::where('status', 1)->latest()->get();

        $data = [
            'title' => 'Create Retail Order',
            'retail_products' => $retail_products,
            'car_brands' => $car_brands,
            'motor_brands' => $motor_brands,
            'sub_materials' => $sub_materials,
            'ppn' => $ppn,
            'customers' => $customers
        ];

        return view('direct_sales.create', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // validate
        if ($request->cust_name == 'other_cust') {
            $request->validate([
                'cust_name' => 'required',
                'cust_phone' => 'required',
                'district' => 'required',
                'plate_number' => 'required',
                'vehicle' => 'required',
                'province' => 'required',
                'district' => 'required',
                'sub_district' => 'required',
                'address' => 'required',
                'remark' => 'required',
                'retails.*.product_id' => 'required',
                'retails.*.qty' => 'required',
                'retails.*.discount' => 'required',
                'retails.*.discount_rp' => 'required',
                'total_excl' => 'required|numeric',
                'total_ppn' => 'required|numeric',
                'total_incl' => 'required|numeric'
            ]);
        } else {
            $request->validate([
                'cust_name' => 'required',
                'remark' => 'required',
                'retails.*.product_id' => 'required',
                'retails.*.qty' => 'required',
                'retails.*.discount' => 'required',
                'retails.*.discount_rp' => 'required',
                'total_excl' => 'required|numeric',
                'total_ppn' => 'required|numeric',
                'total_incl' => 'required|numeric'
            ]);
        }


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

        if ($request->cust_name == 'other_cust') {
            $model->cust_name = $request->cust_name_manual;
            $model->cust_phone = $request->cust_phone;
            $model->cust_ktp = $request->cust_ktp;
            $model->cust_email = $request->cust_email;

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
                $model->car_brand_id = $request->car_brand_id;
                $model->car_type_id = $request->car_type_id;
                $model->motor_brand_id = null;
                $model->motor_type_id = null;
                $model->other = null;
            } else if ($request->vehicle == 'Motocycle') {
                $model->car_brand_id = null;
                $model->car_type_id = null;
                $model->other = null;
                $model->motor_brand_id = $request->motor_brand_id;
                $model->motor_type_id = $request->motor_type_id;
            } else {
                $model->car_brand_id = null;
                $model->car_type_id = null;
                $model->other = $request->other;
                $model->motor_brand_id = null;
                $model->motor_type_id = null;
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
            return redirect('/retail')->with('error', 'Create Order Fail! Please check again the inputs.');
        }

        foreach ($request->retails as $item) {
            $detail = new DirectSalesDetailModel();
            $detail->direct_id = $model->id;
            $detail->product_id = $item['product_id'];
            if ($item['product_code'] == null) {
                $detail->product_code = "-";
            } else $detail->product_code = $item['product_code'];
            $detail->qty = $item['qty'];
            $detail->discount = $item['discount'];
            $detail->discount_rp = $item['discount_rp'];
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
            $sub_materials = ProductCostModel::with(['productBy', 'warehouseBy', 'productBy.materials', 'productBy.sub_materials', 'productBy.stockBy', 'productBy.sub_types', 'productBy.uoms'])
                ->whereHas('productBy', function ($query) {
                    $query->whereIn('shown', ['all', 'retail']);
                })
                ->where('id_warehouse', Auth::user()->warehouse_id)
                ->whereHas('productBy', function ($query) use ($search) {
                    $query->where('nama_barang', 'LIKE', "%$search%");
                })
                ->get();
        } else {
            $sub_materials = ProductCostModel::with(['productBy', 'warehouseBy', 'productBy.sub_materials', 'productBy.materials', 'productBy.stockBy', 'productBy.sub_types', 'productBy.uoms'])
                ->whereHas('productBy', function ($query) {
                    $query->whereIn('shown', ['all', 'retail']);
                })
                ->where('id_warehouse', Auth::user()->warehouse_id)
                ->get();
        }


        return response()->json($sub_materials);
    }

    public function selectById()
    {
        $sub_materials = [];
        $search = request()->s;
        if ($search != "all") {
            $sub_materials = ProductCostModel::with(['productBy', 'warehouseBy', 'productBy.materials', 'productBy.sub_materials', 'productBy.stockBy', 'productBy.sub_types', 'productBy.uoms'])
                ->whereHas('productBy', function ($query) {
                    $query->whereIn('shown', ['all', 'retail']);
                })
                ->where('id_warehouse', Auth::user()->warehouse_id)
                ->whereHas('productBy', function ($query) use ($search) {
                    $query->where('id_sub_material', $search);
                })
                ->get();
        } else {
            $sub_materials = ProductCostModel::with(['productBy', 'warehouseBy', 'productBy.sub_materials', 'productBy.materials', 'productBy.stockBy', 'productBy.sub_types', 'productBy.uoms'])
                ->whereHas('productBy', function ($query) {
                    $query->whereIn('shown', ['all', 'retail']);
                })
                ->where('id_warehouse', Auth::user()->warehouse_id)
                ->get();
        }
        return response()->json($sub_materials);
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
    public function PrintStruk($id)
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
        $ppn_ = $ppn * 100;

        $pdf = Pdf::loadView('direct_sales.print_struk', compact('warehouse', 'data', 'ppn_'))->save('pdf/' . $data->order_number . '.pdf');

        return $pdf->stream($data->pdf_invoice);
    }

    public function print_do($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $data = DirectSalesModel::find($id);
        $so_number = str_replace('RSPP', 'DOPP', $data->order_number);
        $data->pdf_do = $so_number . '.pdf';
        $data->save();
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = Pdf::loadView('direct_sales.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $so_number . '.pdf');
        return $pdf->download($data->pdf_do);
    }

    public function update_retail(Request $request, $id)
    {
        if (
            !Gate::allows('isSuperAdmin')
        ) {
            abort(403);
        }

        if ($request->cust_name == 'other_cust') {
            $validation = $request->validate([
                'cust_name' => 'required',
                'cust_phone' => 'required',
                'plate_number' => 'required',
                'province' => 'required',
                'district' => 'required',
                'sub_district' => 'required',
                'address' => 'required',
                'remark' => 'required',
                'retails.*.product_id' => 'required',
                'retails.*.qty' => 'required',
                'retails.*.discount' => 'required',
                'retails.*.discount_rp' => 'required',
            ]);
        } else {
            $validation = $request->validate([
                'cust_name' => 'required',
                'remark' => 'required',
                'retails.*.product_id' => 'required',
                'retails.*.qty' => 'required',
                'retails.*.discount' => 'required',
                'retails.*.discount_rp' => 'required',
            ]);
        }

        //Restore stock to before changed
        $direct_restore = DirectSalesDetailModel::where('direct_id', $id)->get();
        foreach ($direct_restore as $restore) {
            $stock = StockModel::where('warehouses_id', Auth::user()->warehouse_id)
                ->where('products_id', $restore->product_id)->first();
            $stock->stock = $stock->stock + $restore->qty;
            $stock->save();
        }
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
                $selected_direct->car_brand_id = $request->car_brand_id;
                $selected_direct->car_type_id = $request->car_type_id;
                $selected_direct->motor_brand_id = null;
                $selected_direct->motor_type_id = null;
                $selected_direct->other = null;
            } else if ($request->vehicle == 'Motocycle') {
                $selected_direct->car_brand_id = null;
                $selected_direct->car_type_id = null;
                $selected_direct->other = null;
                $selected_direct->motor_brand_id = $request->motor_brand_id;
                $selected_direct->motor_type_id = $request->motor_type_id;
            } else {
                $selected_direct->car_brand_id = null;
                $selected_direct->car_type_id = null;
                $selected_direct->other = $request->other;
                $selected_direct->motor_brand_id = null;
                $selected_direct->motor_type_id = null;
            }
        } else {
            $selected_direct->cust_name = $request->cust_name;
            $selected_cust = CustomerModel::where('id', $request->cust_name)->first();
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


        //Save Return Input and Total and Change Stock
        $total = 0;

        foreach ($request->retails as $product) {
            $product_exist = DirectSalesDetailModel::where('direct_id', $id)
                ->where('product_id', $product['product_id'])->first();

            if ($product_exist != null) {
                $old_qty = $product_exist->qty;
                $product_exist->qty = $product['qty'];
                if ($product['product_code'] == null) {
                    $product_exist->product_code = '-';
                } else $product_exist->product_code = $product['product_code'];
                $product_exist->product_code = $product['product_code'];
                $product_exist->discount = $product['discount'];
                $product_exist->discount_rp = $product['discount_rp'];
                $product_exist->save();
            } else {
                $new_product = new DirectSalesDetailModel();
                $new_product->direct_id = $id;
                $new_product->product_id = $product['product_id'];
                if ($product['product_code'] == null) {
                    $new_product->product_code = '-';
                } else $new_product->product_code = $product['product_code'];
                $new_product->qty = $product['qty'];
                $new_product->discount = $product['discount'];
                $new_product->discount_rp = $product['discount_rp'];
                $new_product->save();
            }

            //Delete product that not in Detail Input
            $del = DirectSalesDetailModel::where('direct_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            //Count Total
            $products = ProductModel::where('id', $product['product_id'])->first();
            $retail_price = 0;
            foreach ($products->retailPriceBy as $value) {
                if ($value->id_warehouse == Auth::user()->warehouse_id) {
                    $retail_price = $value->harga_jual;
                }
            }
            $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $retail_price;
            $ppn_cost = $retail_price + $ppn;
            $diskon =  $product['discount'] / 100;
            $hargaDiskon = $ppn_cost * $diskon;
            $hargaAfterDiskon = ($ppn_cost -  $hargaDiskon) - $product['discount_rp'];
            $total = $total + ($hargaAfterDiskon * $product['qty']);

            //Change Stock
            $getStock = StockModel::where('products_id', $product['product_id'])
                ->where('warehouses_id', Auth::user()->warehouse_id)
                ->first();
            $old_stock = $getStock->stock;
            $getStock->stock = $old_stock - $product['qty'];
            $getStock->save();
        }


        $selected_direct->total_excl = $total / 1.11;
        $selected_direct->total_ppn = $total / 1.11 * (ValueAddedTaxModel::first()->ppn / 100);
        $selected_direct->total_incl = $total;
        $saved = $selected_direct->save();

        return redirect('/retail')->with('success', 'Edit Invoice Retail Success!');
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
