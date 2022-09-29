<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\ReturnDetailModel;
use App\Models\ReturnModel;
use App\Models\SalesOrderDetailModel;
use App\Models\SalesOrderModel;
use App\Models\StockModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Return_;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }

        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $return = ReturnModel::with('returnDetailsBy', 'createdBy')
                    ->whereBetween('return_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $return = ReturnModel::with('returnDetailsBy', 'createdBy')
                    ->latest()
                    ->get();
            }
            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, ',', '.');
                })
                ->editColumn('return_date', function ($data) {
                    return date('d-M-Y', strtotime($data->return_date));
                })
                ->editColumn('sales_order_id', function (ReturnModel $returnModel) {
                    return $returnModel->salesOrderBy->order_number;
                })
                ->editColumn('created_by', function (ReturnModel $returnModel) {
                    return $returnModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($return) {
                    return view('returns._option', compact('return'))->render();
                })
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'All Return Sales Orders in Profecta Perdana'
        ];
        return view('returns.index', $data);
    }

    public function create(Request $request, $id)
    {
        $selected_so = SalesOrderModel::with('salesOrderDetailsBy')->where('id', $id)->first();
        $selected_return = ReturnModel::with('returnDetailsBy')->where('sales_order_id', $id)->get();
        $return_amount = [];

        foreach ($selected_so->salesOrderDetailsBy as $value) {
            $return = 0;
            if ($selected_return != null) {
                foreach ($selected_return as $detail) {
                    $selected_detail = ReturnDetailModel::where('return_id', $detail->id)->where('product_id', $value->products_id)->first();
                    $return += $selected_detail->qty;
                }
            }
            array_push($return_amount, $return);
        }
        $data = [
            'title' => 'Return From Order: ' . $selected_so->order_number,
            'sales_order' => $selected_so,
            'return_amount' => $return_amount
        ];

        return view('returns.create', $data);
    }

    public function store(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        // Validate Input
        $request->validate([
            "returnFields.*.product_id" => "required|numeric",
            "returnFields.*.qty" => "required|numeric",
            "return_reason1" => "required"
        ]);

        if ($request->returnFields == null) {
            return Redirect::back()->with('error', 'There are no products!');
        }

        //Check Duplicate
        $products_arr = [];

        foreach ($request->returnFields as $check) {
            array_push($products_arr, $check['product_id']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate product.');
        }

        $model = new ReturnModel();

        //create return number
        $selected_so = SalesOrderModel::where('id', $request->get('so_id'))->first();
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', $selected_so->customerBy->warehouseBy->id)
            ->first();
        $length = 3;
        $id = intval(ReturnModel::where('return_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $return_number = 'REPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

        $model->return_number = $return_number;
        $model->sales_order_id = $request->get('so_id');
        $model->return_date = Carbon::now()->format('Y-m-d');
        $model->created_by = Auth::user()->id;
        $model->save();

        $get_reason = $request->get('return_reason1');
        if ($get_reason == "Wrong Quantity" || $get_reason == "Wrong Product Type") {
            $model->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
        } elseif ($get_reason == "Other") {
            $model->return_reason = $request->get('return_reason');
        } else {
            $model->return_reason = $get_reason;
        }

        $total = 0;
        foreach ($request->returnFields as $item) {
            $detail = new ReturnDetailModel();
            $detail->return_id = $model->id;
            $detail->product_id = $item['product_id'];
            $detail->qty = $item['qty'];

            //Check exceed order
            $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $model->sales_order_id)
                ->where('products_id', $detail->product_id)->first();

            $selected_return = ReturnDetailModel::with('returnBy')
                ->whereHas('returnBy', function ($query) use ($model) {
                    $query->where('sales_order_id', $model->sales_order_id);
                })->where('product_id', $item['product_id'])->get();
            //Get Total Returned Qty 
            $returned_qty = 0;
            if ($selected_return == null) {
                $returned_qty = 0;
            } else {
                foreach ($selected_return as $return) {
                    $returned_qty = $returned_qty + $return->qty;
                }
            }
            // dd('detail: ' . $detail->qty . ', ' . $selected_sod->qty . ', ' . $returned_qty);

            if ($detail->qty > ($selected_sod->qty - $returned_qty)) {
                $previous_product = ReturnDetailModel::where('return_id', $model->id)->get();
                if ($previous_product != null) {
                    $previous_product->each->delete();
                    $model->delete();
                }
                return Redirect::back()->with('error', 'Return Order Fail! The number of items exceeds the order');
            }

            $detail->save();


            //Count Total
            $product = ProductModel::where('id', $detail->product_id)->first();
            $diskon =  $selected_sod->discount / 100;
            $hargaDiskon = $product->harga_jual_nonretail * $diskon;
            $hargaAfterDiskon = $product->harga_jual_nonretail -  $hargaDiskon;
            $total = $total + ($hargaAfterDiskon * $detail->qty);
        }
        $ppn = 0.11 * $total;
        $model->total = $total + $ppn;
        $model->save();

        //Change Stock
        $returnDetail = ReturnDetailModel::where('return_id', $model->id)->get();
        $selected_so = SalesOrderModel::where('id', $model->sales_order_id)->first();
        foreach ($returnDetail as $value) {
            $getStock = StockModel::where('products_id', $value->product_id)
                ->where('warehouses_id', $selected_so->customerBy->warehouseBy->id)
                ->first();
            $old_stock = $getStock->stock;
            $getStock->stock = $old_stock + $value->qty;
            $getStock->save();
        }
        return redirect('/return')->with('success', 'Return Order Success!');
    }

    public function update_return(Request $request, $id)
    {
        if (
            !Gate::allows('isSuperAdmin')
        ) {
            abort(403);
        }
        // Validate Input
        $request->validate([
            "returnFields.*.product_id" => "required|numeric",
            "returnFields.*.qty" => "required|numeric",
            "return_reason1" => "required"
        ]);

        //Check Number of product
        if ($request->returnFields == null) {
            return Redirect::back()->with('error', 'There are no products!');
        }

        //Check Duplicate
        $products_arr = [];

        foreach ($request->returnFields as $check) {
            array_push($products_arr, $check['product_id']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate product.');
        }

        $selected_return = ReturnModel::where('id', $id)->first();

        //Check Number of Qty
        foreach ($request->returnFields as $product) {
            $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $selected_return->sales_order_id)
                ->where('products_id', $product['product_id'])->first();

            $selected_detail = ReturnDetailModel::with('returnBy')
                ->whereHas('returnBy', function ($query) use ($selected_return) {
                    $query->where('sales_order_id', $selected_return->sales_order_id);
                })->where('product_id', $product['product_id'])->get();

            $returned_qty = 0;
            if ($selected_detail == null) {
                $returned_qty = 0;
            } else {
                foreach ($selected_detail as $detail) {
                    $returned_qty = $returned_qty + $detail->qty;
                }
            }
            // dd('detail: ' . $detail->qty . ', ' . $selected_sod->qty . ', ' . $returned_qty);

            if ($product['qty'] > ($selected_sod->qty - $returned_qty)) {
                return Redirect::back()->with('error', 'Edit Return Order Fail! The number of items exceeds the order');
            }
        }

        $get_reason = $request->get('return_reason1');
        if ($get_reason == "Wrong Quantity" || $get_reason == "Wrong Product Type") {
            $selected_return->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
        } elseif ($get_reason == "Other") {
            $selected_return->return_reason = $request->get('return_reason');
        } else {
            $selected_return->return_reason = $get_reason;
        }

        //Restore stock to before changed
        $return_restore = ReturnDetailModel::where('return_id', $id)->get();
        foreach ($return_restore as $restore) {
            $stock = StockModel::where('warehouses_id', $selected_return->salesOrderBy->customerBy->warehouseBy->id)
                ->where('products_id', $restore->product_id)->first();
            $stock->stock = $stock->stock - $restore->qty;
            $stock->save();
        }

        //Save Return Input and Total and Change Stock
        $total = 0;

        foreach ($request->returnFields as $product) {
            $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $selected_return->sales_order_id)
                ->where('products_id', $product['product_id'])->first();

            $product_exist = ReturnDetailModel::where('return_id', $id)
                ->where('product_id', $product['product_id'])->first();

            if ($product_exist != null) {
                $old_qty = $product_exist->qty;
                $product_exist->qty = $product['qty'];
                $product_exist->save();
            } else {
                $new_product = new ReturnDetailModel();
                $new_product->return_id = $id;
                $new_product->product_id = $product['product_id'];
                $new_product->qty = $product['qty'];
                $new_product->save();
            }
            //Count Total
            $product = ProductModel::where('id', $product['product_id'])->first();
            $diskon =  $selected_sod->discount / 100;
            $hargaDiskon = $product->harga_jual_nonretail * $diskon;
            $hargaAfterDiskon = $product->harga_jual_nonretail -  $hargaDiskon;
            $total = $total + ($hargaAfterDiskon * $product['qty']);
        }
        $ppn = 0.11 * $total;
        $selected_return->total = $total + $ppn;
        $saved = $selected_return->save();

        //Change Stock
        $returnDetail = ReturnDetailModel::where('return_id', $selected_return->id)->get();
        $selected_so = SalesOrderModel::where('id', $selected_return->sales_order_id)->first();
        foreach ($returnDetail as $value) {
            $getStock = StockModel::where('products_id', $value->product_id)
                ->where('warehouses_id', $selected_so->customerBy->warehouseBy->id)
                ->first();
            $old_stock = $getStock->stock;
            $getStock->stock = $old_stock + $value->qty;
            $getStock->save();
        }
        return redirect('/return')->with('success', 'Edit Return Order Success!');
    }

    public function print_return($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $data = ReturnModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $data->pdf_return = $data->return_number . '.pdf';
        $data->save();

        $pdf = FacadePdf::loadView('returns.print_return', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->return_number . '.pdf');

        return $pdf->download($data->pdf_return);
    }
}
