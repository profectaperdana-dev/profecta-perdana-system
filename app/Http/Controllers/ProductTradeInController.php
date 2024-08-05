<?php

namespace App\Http\Controllers;

use App\Models\DirectSalesModel;
use App\Models\ProductCostSecondModel;
use App\Models\ProductTradeInModel;
use App\Models\PurchaseCostSecondModel;
use App\Models\ReturnTradePurchaseDetailModel;
use App\Models\ReturnTradePurchaseModel;
use App\Models\SaleCostSecondModel;
use App\Models\SecondProductModel;
use App\Models\StockModel;
use App\Models\TradeInDetailModel;
use App\Models\TradeInModel;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Trait_;
use PhpParser\Node\Stmt\TryCatch;

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
        $data = ProductTradeInModel::oldest()->get();
        $warehouse = WarehouseModel::select("id", "type", 'warehouses')
            ->where('type', 5)
            ->get();
        return view('product_trade_in.index', compact('title', 'data', 'warehouse'));
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
        // dd($request->all());
        try {
            DB::beginTransaction();
            $data = new ProductTradeInModel();
            $data->name_product_trade_in = $request->name_product_trade_in;
            if ($data->save()) {
                foreach ($request->priceForm as $value) {
                    // price
                    $price = new ProductCostSecondModel();
                    $price->id_product_trade_in = $data->id;
                    $price->id_warehouse = $value['warehouse'];
                    $price->price_purchase = $value['price_purchase'];
                    $price->price_sale = $value['price_sale'];
                    $check_price = ProductCostSecondModel::where('id_product_trade_in', $price->id_product_trade_in)
                        ->where('id_warehouse', $price->id_warehouse)
                        ->count();
                    if ($check_price > 0) {
                        $message_duplicate = "-";
                        continue;
                    } else {
                        $price->save();
                    }
                }
            }
            if (isset($message_duplicate)) {
                DB::commit();
                return redirect()->back()->with('info', 'Data has been saved without duplicate data');
            } else {
                DB::commit();
                return redirect()->back()->with('success', 'Data has been saved');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        // dd($request->all());
        try {
            DB::beginTransaction();
            $data = ProductTradeInModel::find($id);
            $data->name_product_trade_in = $request->name_product;
            $data->status = $request->status;
            if ($data->save()) {
                $deleteAll = ProductCostSecondModel::where('id_product_trade_in', $data->id)->delete();

                foreach ($request->priceForm as $value) {
                    if ($deleteAll) {
                        // price
                        $price = new ProductCostSecondModel();
                        $price->id_product_trade_in = $data->id;
                        $price->id_warehouse = $value['warehouse'];
                        $price->price_purchase = $value['price_purchase'];
                        $price->price_sale = $value['price_sale'];
                        $check_price = ProductCostSecondModel::where('id_product_trade_in', $price->id_product_trade_in)
                            ->where('id_warehouse', $price->id_warehouse)
                            ->count();
                        if ($check_price > 0) {
                            $message_duplicate = "-";
                            continue;
                        } else {
                            $price->save();
                        }
                    }
                }
            }
            if (isset($message_duplicate)) {
                DB::commit();
                return redirect()->back()->with('info', 'Data has been updated without duplicate data');
            } else {
                DB::commit();
                return redirect()->back()->with('success', 'Data has been updated');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        try {
            DB::beginTransaction();
            //destroy data
            ProductTradeInModel::destroy($id);
            ProductCostSecondModel::where('id_product_trade_in', $id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Data has been deleted !');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function product_trade_in($any = null)
    {
        //create trade in
        $title = 'Create Trade In';
        $ref = $any ?? ''; // memberikan nilai default jika $any kosong
        $data = ProductTradeInModel::oldest('name_product_trade_in')->get();
        $retail = DirectSalesModel::whereDay('order_date', Carbon::now()->day)->get();
        $warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->oldest('warehouses')->get();
        $user_warehouse = WarehouseModel::where('type', 7)->whereIn('id_area', array_column($warehouse->toArray(), 'id_area'))->oldest('warehouses')->get();
        return  view('product_trade_in.create_trade_in', compact('title', 'data', 'retail', 'user_warehouse', 'ref'));
    }

    public function product_trade_in_all()
    {
        try {
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = ProductTradeInModel::where('name_product_trade_in', 'LIKE', "%$search%")
                    ->oldest('name_product_trade_in')
                    ->get();
            } else {
                $product = ProductTradeInModel::oldest('name_product_trade_in')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function getWarehouse()
    {
        try {
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = WarehouseModel::select("id", "type", 'warehouses')
                    ->where('warehouses', 'LIKE', "%$search%")
                    ->where('type', 7)
                    ->get();
            } else {
                $product = WarehouseModel::select("id", "type", 'warehouses')
                    ->where('type', 7)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function storeTradeIn(Request $request)
    {

        try {
            DB::beginTransaction();
            // Initiate new model
            $model = new TradeInModel();
            // Get last record for order number
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->warehouse_id)
                ->first();
            $length = 3;
            $lastRecord = TradeInModel::where('warehouse_id', $request->warehouse_id)->latest()->first();
            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->trade_in_date)->format('m');
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
            $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);

            // insert model trade in
            $order_number = 'TIPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
            $model->trade_in_number = $order_number;
            $model->warehouse_id = $request->warehouse_id;
            $model->trade_in_date = Carbon::now()->format('Y-m-d');
            $model->retail_order_number = $request->retail_order_number;
            $model->createdBy = Auth::user()->id;
            $saved = $model->save();

            // Proses insert data ke table trade in detail
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
                        $message_duplicate = "You enter duplication of products. Please recheck the order you set.";
                        continue;
                    } else {
                        // Ambil Harga dari table product cost second
                        $harga = ProductCostSecondModel::where('id_product_trade_in', $data->product_trade_in)
                            ->where('id_warehouse', $model->warehouse_id)
                            ->first();
                        $total = $total + ($harga->price_purchase * $data->qty);
                        $data->price = $harga->price_purchase;
                        $data->save();
                    }
                    if ($request->warehouse_id == null) {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Please create warehouse for trade in because the warehouse to accommodate used goods does not yet exist');
                    }
                    $second_stock = SecondProductModel::where('warehouses_id', $request->warehouse_id)->where('products_id', $data->product_trade_in)->first();
                    if ($second_stock == null) {
                        $second_stock = new SecondProductModel();
                        $second_stock->warehouses_id = $request->warehouse_id;
                        $second_stock->products_id = $data->product_trade_in;
                        $second_stock->qty = $data->qty;
                        $second_stock->save();
                    } else {
                        $second_stock->qty = $second_stock->qty +  $data->qty;
                        $second_stock->save();
                    }
                }
            }
            $model->total = $total;
            $saved = $model->save();

            if (empty($message_duplicate) && $saved) {

                DB::commit();
                return redirect('create/trade_in')->with('success', 'Create Trade-In order ' . $model->trade_in_number . ' success');
            } elseif (!empty($message_duplicate) && $saved) {

                DB::commit();
                return redirect('create/trade_in')->with('info', 'Trade-In Order add Success! ' . $message_duplicate);
            } else {

                DB::rollback();
                return redirect('create/trade_in')->with('error', 'Add Trade-In Order Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('create/trade_in')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function tradeInvoice(Request $request)
    {

        if ($request->ajax()) {
            $warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->get();
            $warehouse_second = WarehouseModel::where('type', 7)->whereIn('id_area', array_column($warehouse->toArray(), 'id_area'))->get();
            $invoice = TradeInModel::with('tradeBy')
                ->whereIn('warehouse_id', array_column($warehouse_second->toArray(), 'id'))
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    return $query->whereBetween('trade_in_date', [$fromDate, $request->to_date]);
                }, function ($query) {
                    // Add this condition to use today's date as default
                    $today = date('Y-m-d');
                    return empty($query->from_date) ? $query->whereDate('trade_in_date', $today) : $query;
                })
                ->latest('trade_in_number')
                ->get();

            return datatables()->of($invoice)
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, '.', ',');
                })
                ->editColumn('trade_in_date', function ($data) {
                    return date('d F Y', strtotime($data->trade_in_date));
                })
                ->editColumn('createdBy', function (TradeInModel $TradeInModel) {
                    return $TradeInModel->tradeBy->name;
                })
                ->editColumn('customer', function (TradeInModel $TradeInModel) {
                    if ($TradeInModel->retailBy != null) {
                        if (is_numeric($TradeInModel->retailBy->cust_name)) {
                            if ($TradeInModel->retailBy->customerBy == null) {
                                return $TradeInModel->retailBy->cust_name;
                            } else return $TradeInModel->retailBy->customerBy->code_cust . ' - ' . $TradeInModel->retailBy->customerBy->name_cust;
                        } else return $TradeInModel->retailBy->cust_name;
                    } else return '-';
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) use ($warehouse_second) {
                    $user_warehouse = $warehouse_second;
                    return view('product_trade_in._option', compact('invoice', 'user_warehouse'))->render();
                })
                ->rawColumns(['action'], ['createdBy'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "Trade In Invoicing ",
        ];
        return view('product_trade_in.trade_invoice', $data);

        // return view('invoice.index', $data);
    }
    public function printStruk($id)
    {

        $data = TradeInModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
        $price = productCostSecondModel::where('id_warehouse', $data->warehouse_id)
            ->get();
        $pdf = FacadePdf::loadView('product_trade_in.print_struk', compact('warehouse', 'data', 'price'));

        // dd($price);
        return $pdf->stream($data->trade_in_number . '.pdf');
    }


    public function selectCost()
    {
        try {
            $warehouse_id = request()->warehouse;
            $product = request()->id;
            $cost = ProductCostSecondModel::select('id', 'price_purchase', 'price_sale')
                ->where('id_product_trade_in', $product)
                ->where('id_warehouse', $warehouse_id)
                ->first();

            return response()->json($cost);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function editSuperadmin(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $model = tradeInModel::find($id);
            //* get customer
            $model->customer = $request->customer;
            $model->customer_phone = $request->customer_phone;
            $old_warehouse = $model->warehouse_id;
            $model->warehouse_id = $request->warehouse_id;

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

            //Check Duplicate
            $products_arr = [];
            foreach ($request->tradeFields as $check) {
                array_push($products_arr, $check['product_trade_in']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                DB::rollback();
                return redirect()->back()->with('error', "You enter duplicate products! Please check again!");
            }


            $po_restore = TradeInDetailModel::where('trade_in_id', $id)->get();
            foreach ($po_restore as $restore) {
                $stock = SecondProductModel::where('warehouses_id', $old_warehouse)
                    ->where('products_id', $restore->product_trade_in)->first();
                $stock->qty = $stock->qty - $restore->qty;
                $stock->save();
            }



            if ($saved) {
                foreach ($request->tradeFields as $value) {
                    $data = TradeInDetailModel::where('trade_in_id', $model->id)
                        ->where('product_trade_in', $value['product_trade_in'])
                        ->first();
                    $harga = ProductCostSecondModel::where('id_product_trade_in', $value['product_trade_in'])
                        ->where('id_warehouse', $model->warehouse_id)
                        ->first();
                    if ($data) {
                        $data->product_trade_in = $value['product_trade_in'];
                        $data->qty = $value['qty'];
                        $data->price = $harga->price_purchase;
                        $data->save();
                    } else {
                        $detail = new TradeInDetailModel();
                        $detail->trade_in_id = $model->id;
                        $detail->product_trade_in = $value['product_trade_in'];
                        $detail->qty = $value['qty'];
                        $detail->price = $harga->price_purchase;
                        $detail->save();
                    }
                    // dd($harga->price_purchase);
                    // $data->price = $harga->price_purchase;
                    $total = $total + ($harga->price_purchase * $value['qty']);


                    $second_stock = SecondProductModel::where('warehouses_id', $request->warehouse_id)->where('products_id', $value['product_trade_in'])->first();

                    // dd($second_stock);
                    if ($second_stock == null) {
                        $second_stock = new SecondProductModel();
                        $second_stock->warehouses_id = $request->warehouse_id;
                        $second_stock->products_id = $value['product_trade_in'];
                        $second_stock->qty = $value['qty'];
                        $second_stock->save();
                    } else {
                        $second_stock->qty = $second_stock->qty +  $value['qty'];
                        $second_stock->save();
                    }
                }
                //Delete product that not in SOD Input
                $del = TradeInDetailModel::where('trade_in_id', $id)
                    ->whereNotIn('product_trade_in', $products_arr)->delete();
                $model->total = $total;
                $saved = $model->save();
                if (empty($message_duplicate) && $saved) {

                    DB::commit();
                    return redirect()->back()->with('success', 'Create Trade-In order ' . $model->trade_in_number . ' success');
                } elseif (!empty($message_duplicate) && $saved) {

                    DB::commit();
                    return redirect()->back()->with('info', 'Trade-In Order add Success! ' . $message_duplicate);
                } else {

                    DB::rollback();
                    return redirect()->back()->with('error', 'Add Trade-In Order Fail! Please make sure you have filled all the input');
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function deleteTradePurchase($id)
    {
        try {
            DB::beginTransaction();
            $sales_order = TradeInModel::find($id);
            $sales_order_detail = TradeInDetailModel::where('trade_in_id', $id)->get();
            foreach ($sales_order_detail as $key => $value) {
                $stock = SecondProductModel::where('products_id', $value->product_trade_in)->where('warehouses_id', $sales_order->warehouse_id)->first();
                $stock->qty = $stock->qty - $value->qty;
                $stock->save();
                $value->delete();
            }
            $sales_order->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Trade-In Order ' . $sales_order->order_number . ' has been deleted');
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error2', '. Please call your Most Valuable IT Team.');
        }
    }

    public function selectReturn()
    {
        try {
            $so_id = request()->p;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = TradeInDetailModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'trade_in_details.product_trade_in')
                    ->select('product_trade_ins.name_product_trade_in', 'product_trade_ins.id AS id')
                    ->where('trade_in_details.trade_in_id', $so_id)
                    ->where('product_trade_ins.name_product_trade_in', 'LIKE', "%$search%")
                    ->oldest('product_trade_ins.name_product_trade_in')
                    ->get();
            } else {
                $product = TradeInDetailModel::join('product_trade_ins', 'product_trade_ins.id', '=', 'trade_in_details.product_trade_in')
                    ->select('product_trade_ins.name_product_trade_in', 'product_trade_ins.id AS id')
                    ->where('trade_in_details.trade_in_id', $so_id)
                    ->oldest('product_trade_ins.name_product_trade_in')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
    public function getQtyDetail()
    {
        $so_id = request()->s;
        $product_id = request()->p;

        $getqty = TradeInDetailModel::where('trade_in_id', $so_id)->where('product_trade_in', $product_id)->first();
        $_qty = $getqty->qty;
        $selected_return = ReturnTradePurchaseModel::with('returnDetailsBy')->where('trade_in_id', $so_id)->get();

        $return = 0;
        if ($selected_return != null) {
            foreach ($selected_return as $value) {
                $selected_detail = ReturnTradePurchaseDetailModel::where('return_id', $value->id)->where('product_id', $product_id)->first();
                $return = $return - $selected_detail->qty;
            }
        }
        $data = [
            'qty' => $_qty,
            'return' => $return
        ];
        return response()->json($data);
    }
}
