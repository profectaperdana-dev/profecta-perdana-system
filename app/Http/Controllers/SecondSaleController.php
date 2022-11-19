<?php

namespace App\Http\Controllers;

use App\Models\ProductTradeInModel;
use App\Models\SecondProductModel;
use App\Models\SecondSaleDetailModel;
use App\Models\SecondSaleModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecondSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
                    $invoice = SecondSaleModel::with('secondSaleBy')
                        ->whereBetween('second_sale_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                } else {
                    $invoice = SecondSaleModel::with('secondSaleBy')
                        ->where('trade_in_number', 'like', "%$kode_area->area_code%")
                        ->whereBetween('second_sale_date', array($request->from_date, $request->to_date))
                        ->latest()
                        ->get();
                }
            } else {
                if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
                    $invoice = SecondSaleModel::with('secondSaleBy')
                        ->latest()
                        ->get();
                } else {
                    $invoice = SecondSaleModel::with('secondSaleBy')
                        ->where('second_sale_date', 'like', "%$kode_area->area_code%")
                        ->latest()
                        ->get();
                }
            }
            return datatables()->of($invoice)
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, ',', '.');
                })
                ->editColumn('second_sale_date', function ($data) {
                    return date('d M Y', strtotime($data->second_sale_date));
                })
                ->editColumn('secondSaleBy', function (SecondSaleModel $SecondSaleModel) {
                    return $SecondSaleModel->secondSaleBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) {

                    return view('second_sale._option', compact('invoice'))->render();
                })
                ->rawColumns(['action'], ['createdBy'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "Second Products Invoicing : " . Auth::user()->warehouseBy->warehouses,
        ];
        return view('second_sale.index', $data);
    }
    public function printStruk($id)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isFinance')
        ) {
            abort(403);
        }
        $data = SecondSaleModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();

        $pdf = Pdf::loadView('second_sale.print_struk', compact('warehouse', 'data'));

        return $pdf->stream($data->second_sale_number . '.pdf');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Second Product Sale';
        return view('second_sale.create', compact('title'));
    }
    public function select()
    {
        try {
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = SecondProductModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'second_products.products_id')
                    ->select('second_products.*', 'product_trade_ins.*, warehouses.*')
                    ->join('warehouses', 'warehouses.id', '=', 'second_products.warehouses_id')
                    ->select('second_products.*', 'product_trade_ins.*')
                    ->where('product_trade_ins.name_product_trade_in', 'LIKE', "%$search%")
                    ->where('warehouses.id_area', '=', Auth::user()->warehouseBy->id_area)
                    ->where('second_products.qty', '>', 0)
                    ->get();
            } else {
                $product = SecondProductModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'second_products.products_id')
                    ->select('second_products.*', 'product_trade_ins.*, warehouses.*')
                    ->join('warehouses', 'warehouses.id', '=', 'second_products.warehouses_id')
                    ->select('second_products.*', 'product_trade_ins.*')
                    ->where('warehouses.id_area', '=', Auth::user()->warehouseBy->id_area)
                    ->where('second_products.qty', '>', 0)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function cekQty($id_product)
    {
        $qty = SecondProductModel::join('warehouses', 'warehouses.id', '=', 'second_products.warehouses_id')
            ->select('second_products.*', 'warehouses.*')
            ->where('warehouses.id_area', '=', Auth::user()->warehouseBy->id_area)
            ->where('second_products.products_id', $id_product)
            ->first();


        return response()->json($qty);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        //* Store to database
        $model = new SecondSaleModel();
        //* get trade number
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', Auth::user()->warehouse_id)
            ->first();
        $length = 3;
        $id = intval(SecondSaleModel::where('second_sale_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $order_number = 'SSPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
        $model->second_sale_number = $order_number;
        $model->second_sale_date = Carbon::now()->format('Y-m-d');
        $model->created_by = Auth::user()->id;

        //* customer 
        $model->customer_name = $request->customer;
        $model->customer_phone = $request->customer_phone;
        if ($request->customer_nik == null || $request->customer_email == '') {
            $model->customer_nik = '-';
            $model->customer_email = '-';
        } else {
            $model->customer_nik = $request->customer_nik;
            $model->customer_email = $request->customer_email;
        }
        $saved = $model->save();
        $total = 0;

        if ($saved) {
            $message_duplicate = '';

            foreach ($request->tradeFields as $key => $value) {
                $model_detail = new SecondSaleDetailModel();
                $model_detail->second_sale_id = $model->id;
                $model_detail->product_second_id = $value['product_trade_in'];
                $model_detail->qty = $value['qty'];
                if ($value['disc_percent'] == null) {
                    $model_detail->discount = 0;
                } else {
                    $model_detail->discount = $value['disc_percent'];
                }
                if ($value['disc_rp'] == null) {
                    $model_detail->discount_rp = 0;
                } else {
                    $model_detail->discount_rp = $value['disc_rp'];
                }
                $check_duplicate = SecondSaleDetailModel::where('second_sale_id', $model_detail->second_sale_id)
                    ->where('product_second_id', $model_detail->product_second_id)
                    ->count();
                if ($check_duplicate > 0) {
                    $message_duplicate = "You enter duplication of products. Please recheck the order you set.";
                    continue;
                } else {
                    $get_harga = ProductTradeInModel::where('id', $value['product_trade_in'])->first();
                    $diskon =  $value['disc_percent'] / 100;
                    $harga_diskon = $get_harga->price_product_trade_in * $diskon;
                    $diskon_rupiah = $value['disc_rp'];
                    $harga_akhir = $get_harga->price_product_trade_in - ($harga_diskon  + $diskon_rupiah);
                    $total += $harga_akhir * $value['qty'];
                    $detail_saved = $model_detail->save();

                    if ($detail_saved) {
                        $model_product = SecondProductModel::join('warehouses', 'warehouses.id', '=', 'second_products.warehouses_id')
                            ->select('second_products.*', 'warehouses.id_area')
                            ->where('warehouses.id_area', '=', Auth::user()->warehouseBy->id_area)
                            ->where('second_products.products_id', $value['product_trade_in'])
                            ->first();
                        $model_product->qty = $model_product->qty - $value['qty'];
                        $model_product->save();
                    }
                }
            }
        }
        $model->total = $total;
        $cek_save = $model->save();
        if (empty($message_duplicate && $cek_save)) {
            return redirect()->back()->with('success', 'Create Order Has Been Success');
        } else if (!empty($message_duplicate) && $cek_save) {
            return redirect()->back()->with('success', 'Create Order Has Been Success and ' . $message_duplicate);
        } else {
            return redirect()->back()->with('error', 'Create Order Has Been Failed');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
