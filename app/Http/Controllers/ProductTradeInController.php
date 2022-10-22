<?php

namespace App\Http\Controllers;

use App\Models\ProductTradeInModel;
use App\Models\TradeInDetailModel;
use App\Models\TradeInModel;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;


class ProductTradeInController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //view data
        $title = 'Product Trade In';
        $data = ProductTradeInModel::latest()->get();
        return view('product_trade_in.index', compact('title', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate
        $request->validate([
            'name_product_trade_in' => 'required',
            'price_product_trade_in' => 'required|numeric',
        ]);

        //insert data
        $model = new ProductTradeInModel();
        $model->name_product_trade_in = $request->name_product_trade_in;
        $model->price_product_trade_in = $request->price_product_trade_in;
        $saved = $model->save();
        if ($saved) {
            return redirect('trade_in')->with('success', 'Data has been saved');
        } else {
            return redirect('trade_in')->with('error', 'Data fail saved');
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
        //
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
        //validate
        $request->validate([
            'name_product_trade_ins' => 'required',
            'price_product_trade_ins' => 'required|numeric',
        ]);

        //update data
        $model = ProductTradeInModel::find($id);
        $model->name_product_trade_in = $request->name_product_trade_ins;
        $model->price_product_trade_in = $request->price_product_trade_ins;
        $updated = $model->save();
        if ($updated) {
            return redirect('trade_in')->with('success', 'Data has been updated');
        } else {
            return redirect('trade_in')->with('error', 'Data fail updated');
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
        //destroy data
        ProductTradeInModel::destroy($id);
        return redirect()->back()->with('success', 'Data has been deleted !');
    }

    public function product_trade_in()
    {
        //create trade in
        $title = 'Create Trade In';
        $data = ProductTradeInModel::all();
        return  view('product_trade_in.create_trade_in', compact('title', 'data'));
    }
    public function product_trade_in_all()
    {
        try {
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = ProductTradeInModel::where('name_product_trade_in', 'LIKE', "%$search%")
                    ->get();
            } else {
                $product = ProductTradeInModel::latest()
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function storeTradeIn(Request $request)
    {
        if (!Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')) {
            abort(403);
        }

        //* validator
        $request->validate([
            "customer" => "required",
            "customer_phone" => "required",
            "tradeFields.*.product_trade_in" => "required",
            "tradeFields.*.qty" => "required",
        ]);

        //* save data trade in
        $model = new TradeInModel();

        //* get trade number
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', Auth::user()->warehouse_id)
            ->first();
        $length = 3;
        $id = intval(TradeInModel::where('trade_in_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $order_number = 'TTPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
        $model->trade_in_number = $order_number;

        //* get trade date
        $model->trade_in_date = Carbon::now()->format('Y-m-d');

        //* get customer 
        $model->customer = $request->customer;
        $model->customer_phone = $request->customer_phone;

        if ($request->customer_email) {
            $model->customer_email = $request->customer_email;
        } else {
            $model->customer_email = '-';
        }
        if ($request->customer_nik) {
            $model->customer_nik = $request->customer_nik;
        } else {
            $model->customer_nik = '-';
        }

        //* created by
        $model->createdBy = Auth::user()->id;
        $saved = $model->save();

        // save purchase order details
        $total = 0;
        $message_duplicate = '';
        if ($saved) {
            foreach ($request->tradeFields as $value) {
                $data = new TradeInDetailModel();
                $data->trade_in_id =  $model->id;
                $data->product_trade_in = $value['product_trade_in'];
                $data->qty = $value['qty'];

                $check_duplicate = TradeInDetailModel::where('trade_in_id', $data->trade_in_id)
                    ->where('product_trade_in', $data->product_trade_in)
                    ->count();
                if ($check_duplicate > 0) {
                    $message_duplicate = "You enter duplication of products. Please recheck the PO you set.";
                    continue;
                } else {
                    $harga = ProductTradeInModel::where('id', $data->product_trade_in)->first();
                    $total = $total + ($harga->price_product_trade_in * $data->qty);
                    $data->save();
                }
            }
        }
        $model->total = $total;
        $saved = $model->save();

        if (empty($message_duplicate) && $saved) {
            return redirect('trade_invoice')->with('success', 'Create Trade-In order ' . $model->trade_in_number . ' success');
        } elseif (!empty($message_duplicate) && $saved) {

            return redirect('trade_invoice')->with('info', 'Trade-In Order add Success! ' . $message_duplicate);
        } else {
            return redirect('trade_invoice')->with('error', 'Add Trade-In Order Fail! Please make sure you have filled all the input');
        }
    }

    public function tradeInvoice(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isSales') && !Gate::allows('isVerificator')
            && !Gate::allows('isFinance')
        ) {
            abort(403);
        }

        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            if (!empty($request->from_date)) {
                if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                    $invoice = TradeInModel::with('tradeBy')
                        ->whereBetween('trade_in_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                } else {
                    $invoice = TradeInModel::with('tradeBy')
                        ->where('trade_in_number', 'like', "%$kode_area->area_code%")
                        ->whereBetween('trade_in_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                }
            } else {
                if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                    $invoice = TradeInModel::with('tradeBy')
                        ->latest()
                        ->get();
                } else {
                    $invoice = TradeInModel::with('tradeBy')
                        ->where('trade_in_number', 'like', "%$kode_area->area_code%")
                        ->latest()
                        ->get();
                }
            }
            return datatables()->of($invoice)
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, ',', '.');
                })
                ->editColumn('trade_in_date', function ($data) {
                    return date('d-M-Y', strtotime($data->trade_in_date));
                })
                ->editColumn('createdBy', function (TradeInModel $TradeInModel) {
                    return $TradeInModel->tradeBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) {

                    return view('product_trade_in._option', compact('invoice'))->render();
                })
                ->rawColumns(['action'], ['createdBy'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "Trade In Invoicing : " . Auth::user()->warehouseBy->warehouses,
        ];
        return view('product_trade_in.trade_invoice', $data);

        // return view('invoice.index', $data);
    }

    public function printTradeInvoice($id)
    {
        $data = TradeInModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = FacadePdf::loadView('product_trade_in.print_trade_in', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->stream('pdf_trade_in/' . $data->trade_in_number . '.pdf');

        return $pdf;
    }
}
