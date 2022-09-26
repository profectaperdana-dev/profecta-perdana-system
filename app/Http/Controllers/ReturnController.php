<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use App\Models\ReturnDetailModel;
use App\Models\ReturnModel;
use App\Models\SalesOrderDetailModel;
use App\Models\SalesOrderModel;
use App\Models\StockModel;
use App\Models\WarehouseModel;
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
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
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
                ->addColumn('action', 'returns._option')
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'All Return Orders in Profecta Perdana'
        ];
        return view('returns.index', $data);
    }

    public function create(Request $request, $id)
    {
        // $product = SalesOrderDetailModel::with('productSales')
        //     ->select('productSales.nama_barang', 'productSales.kode_barang')
        //     ->where('sales_orders_id', $id)
        //     ->get();
        // dd($product);
        $selected_so = SalesOrderModel::with('salesOrderDetailsBy')->where('id', $id)->first();
        $data = [
            'title' => 'Return From Order: ' . $selected_so->order_number,
            'sales_order' => $selected_so
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

        $model = new ReturnModel();
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

        $model->total = $total;
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
}
