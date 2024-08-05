<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\DeliveryHistoriesModel;
use App\Models\SalesOrderDotModel;
use App\Models\SalesOrderModel;
use App\Models\TyreDotModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryHistoriesController extends Controller
{
    public function index(Request $request)
    {

        // dd($datas);
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $all_undelivered_so = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                    ->where('isapprove', 'approve')
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->whereIn('warehouse_id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
                    ->where('isdelivered', 0)
                    ->latest('order_number')
                    ->get();
            } else {
                $all_undelivered_so = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                    ->where('isapprove', 'approve')
                    ->whereIn('warehouse_id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))
                    ->where('order_date', date('Y-m-d'))
                    ->where('isdelivered', 0)
                    ->latest('order_number')
                    ->get();
            }
            return datatables()->of($all_undelivered_so)
                ->editColumn('payment_method', function ($data) {
                    if ($data->payment_method == 1) {
                        return 'COD';
                    } elseif ($data->payment_method == 2) {
                        return 'CBD';
                    } else {
                        return 'Credit';
                    }
                })
                ->editColumn('order_number', function ($data) {
                    return '<strong >' . $data->order_number . '</strong>';
                })
                ->editColumn('order_date', function ($data) {
                    return date('d F Y', strtotime($data->order_date));
                })
                ->editColumn('customers_id', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->customerBy->code_cust . ' - ' . $SalesOrderModel->customerBy->name_cust;
                })
                ->editColumn('created_by', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->createdSalesOrder->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($all_undelivered_so) {
                    $undelivered = $all_undelivered_so;
                    $customer = CustomerModel::latest()->get();
                    $warehouses = WarehouseModel::latest()->get();
                    $datas =  TyreDotModel::with('tyreBy', 'warehouseBy')
                        ->oldest('dot')
                        ->get();
                    // dd($datas);
                    return view('delivery_histories._option', compact('undelivered', 'customer', 'warehouses', 'datas'))->render();
                })
                ->rawColumns(['order_number'], ['action'])
                ->addIndexColumn()
                ->make(true);
        }


        $data = [
            'title' => 'SO Process'
        ];

        return view('delivery_histories.index', $data);
    }

    public function store(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'status' => 'required',
                'history_date' => 'required',
                'remark' => 'required',
            ]);

            $model = new DeliveryHistoriesModel();
            $model->order_id = $id;
            $model->status = $request->status;
            $this_time = date('H:i:s');
            $model->history_date = date('Y-m-d H:i:s', strtotime($request->history_date . $this_time));
            $model->remark = $request->remark;
            $model->created_by = Auth::user()->id;
            $model->save();

            if ($request->status == 'Done') {
                $selected_so = SalesOrderModel::where('id', $id)->first();
                $selected_so->isdelivered = 1;
                $selected_so->save();
            }
            DB::commit();
            return redirect('delivery_history')->with('success', 'Delivery history update success!');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update_dot_stock(Request $request)
    {
        // dd($request->all());
        // update stock dot tyre
        try {
            DB::beginTransaction();

            //check duplicate
            $products_arr = [];
            foreach ($request->dotForm as $check) {
                array_push($products_arr, $check['dot']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));
            if (!empty($duplicates)) {
                return redirect()->back()->with('error', 'Update Stock DOT Fail! You enter duplicate DOT.');
            }

            $data = SalesOrderModel::where('id', $request->id_so)->first();

            $warehouse = $data->warehouse_id;

            foreach ($request->dotForm as $value) {
                $datas = TyreDotModel::find($value['dot']);
                if ($datas->qty >= $value['qty_dot']) {
                    $datas->qty = $datas->qty - $value['qty_dot'];

                    // save to record stock dot
                    $record = new SalesOrderDotModel();
                    $record->sales_order_detail_id = $value['so_detail_id'];
                    $record->dot_id = $datas->dot;
                    $record->qty = $value['qty_dot'];
                    $record->save();
                } else {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Update Stock DOT Fail! the amount of stock on the dot is insufficient please check again .');
                }
                $datas->save();
            }
            $data->statusDot = 1;
            $data->save();
            DB::commit();
            return redirect()->back()->with('success', 'Update Stock DOT Success!');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
