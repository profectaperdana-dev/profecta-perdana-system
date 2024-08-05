<?php

namespace App\Http\Controllers;

use App\Models\AccountSubTypeModel;
use App\Models\CustomerModel;
use App\Models\DirectSalesDetailModel;
use App\Models\DirectSalesModel;
use App\Models\Finance\Coa;
use App\Models\Finance\JournalDetail;
use App\Models\JurnalModel;
use App\Models\ProductCostModel;
use App\Models\ProductCostSecondModel;
use App\Models\ProductModel;
use App\Models\ItemPromotionCostModel;
use App\Models\ItemPromotionModel;
use App\Models\ItemPromotionPurchaseDetailModel;
use App\Models\ItemPromotionPurchaseModel;
use App\Models\ItemPromotionStockModel;
use App\Models\ItemPromotionTransactionDetailModel;
use App\Models\ItemPromotionTransactionModel;
use App\Models\ProductTradeInModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\PurchaseOrderModel;
use App\Models\ReturnDetailModel;
use App\Models\ReturnModel;
use App\Models\ReturnItemPromotionDetailModel;
use App\Models\ReturnItemPromotionModel;
use App\Models\ReturnItemPromotionPurchaseDetailModel;
use App\Models\ReturnItemPromotionPurchaseModel;
use App\Models\ReturnPurchaseDetailModel;
use App\Models\ReturnPurchaseModel;
use App\Models\ReturnRetailCodeModel;
use App\Models\ReturnRetailDetailModel;
use App\Models\ReturnRetailModel;
use App\Models\ReturnTradePurchaseDetailModel;
use App\Models\ReturnTradePurchaseModel;
use App\Models\ReturnTradeSaleDetailModel;
use App\Models\ReturnTradeSaleModel;
use App\Models\SalesOrderDetailModel;
use App\Models\SalesOrderModel;
use App\Models\SecondProductModel;
use App\Models\SecondSaleDetailModel;
use App\Models\SecondSaleModel;
use App\Models\StockModel;
use App\Models\TradeInDetailModel;
use App\Models\TradeInModel;
use App\Models\TyreDotModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use App\Events\LeaveApproval;
use App\Models\NotificationsModel;
use function App\Helpers\changeSaldoTambah;
use function App\Helpers\changeSaldoKurang;
use function App\Helpers\createJournal;
use function App\Helpers\createJournalDetail;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
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
        if ($request->ajax()) {
            $return = ReturnModel::with('returnDetailsBy', 'createdBy', 'salesOrderBy', 'salesOrderBy.customerBy', 'salesOrderBy.createdSalesOrder')
                 ->when($request->from_date, function ($query) use ($request) {
                        return $query->whereBetween('return_date', [$request->from_date, $request->to_date]);
                    }, function ($query) use ($request) {
                        if($request->filter == "this_month"){
                            // Mendapatkan tanggal awal bulan ini
                            $firstDayOfMonth = date("Y-m-01");
                            
                            // Mendapatkan tanggal akhir bulan ini
                            $lastDayOfMonth = date("Y-m-t");
                            
                            return empty($query->from_date) ? $query->whereBetween('return_date', [$firstDayOfMonth, $lastDayOfMonth]) : $query;
                        }else{
                            $today = date('Y-m-d');
                            //  dd("hey");
                            return empty($query->from_date) ? $query->whereDate('return_date', $today) : $query;
                        }
                         
                })
                ->where('isapproved', 1)
                ->where('isreceived', 1)
                ->latest()
                ->get();

            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('return_reason', function ($data) {
                    return '<span class="fw-bold text-danger">' . $data->salesOrderBy->createdSalesOrder->name . '</span>' . ' - ' . $data->return_reason;
                })
                ->editColumn('cust_id', function (ReturnModel $returnModel) {
                    return $returnModel->salesOrderBy->customerBy->code_cust . ' - ' . $returnModel->salesOrderBy->customerBy->name_cust;
                })
                ->editColumn('created_by', function (ReturnModel $returnModel) {
                    return $returnModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran

                ->addColumn('action', function ($return) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('returns._option', compact('return', 'ppn'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'All Return Invoice in Profecta Perdana'
        ];
        return view('returns.index', $data);
    }
    public function delete_return_trade_in($id)
    {
        try {
            DB::beginTransaction();
            $return  = ReturnTradePurchaseModel::where('id', $id)->first();
            $return_detail = ReturnTradePurchaseDetailModel::where('return_id', $id)->get();
            // dd($return->salesOrderBy->warehouse_id);

            // dd($return_detail);
            foreach ($return_detail as $value) {
                $stock = SecondProductModel::where('warehouses_id', $return->tradeInBy->warehouse_id)
                    ->where('products_id', $value->product_id)->first();
                $stock->qty = $stock->qty + $value->qty;
                $stock->save();
                $value->delete();
            }
            // dd($return);
            $return->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Return Invoice has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function delete_return_trade_in_sale($id)
    {
        try {
            DB::beginTransaction();
            $return  = ReturnTradeSaleModel::where('id', $id)->first();
            $return_detail = ReturnTradeSaleDetailModel::where('return_id', $id)->get();
            // dd($return->salesOrderBy->warehouse_id);

            // dd($return_detail);
            foreach ($return_detail as $value) {
                $stock = SecondProductModel::where('warehouses_id', $return->secondSaleBy->warehouse_id)
                    ->where('products_id', $value->product_id)->first();
                $stock->qty = $stock->qty - $value->qty;
                $stock->save();
                $value->delete();
            }
            // dd($return);
            $return->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Return Invoice has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function delete_return_purchase($id)
    {
        try {
            DB::beginTransaction();
            $return  = ReturnPurchaseModel::where('id', $id)->first();
            $return_detail = ReturnPurchaseDetailModel::where('return_id', $id)->get();
            // dd($return->salesOrderBy->warehouse_id);

            // dd($return_detail);
            foreach ($return_detail as $value) {
                $stock = StockModel::where('products_id', $value->product_id)->where('warehouses_id', $return->purchaseOrderBy->warehouse_id)->first();
                $stock->stock = $stock->stock + $value->qty;
                $stock->save();
                $value->delete();
            }
            // dd($return);
            $return->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Return Invoice has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function delete_return_indirect($id)
    {
        try {
            DB::beginTransaction();
            $return  = ReturnModel::where('id', $id)->first();
            $return_detail = ReturnDetailModel::where('return_id', $id)->get();
            // dd($return->salesOrderBy->warehouse_id);

            // dd($return_detail);
            foreach ($return_detail as $value) {
                if ($return->isapproved == 1) {
                    $stock = StockModel::where('products_id', $value->product_id)->where('warehouses_id', $return->salesOrderBy->warehouse_id)->first();
                    $stock->stock = $stock->stock - $value->qty;
                    $stock->save();
                }

                $value->delete();
            }
            // dd($return);
            $return->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Return Invoice has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function delete_return_direct($id)
    {
        try {
            DB::beginTransaction();
            $return  = ReturnRetailModel::where('id', $id)->first();
            $return_detail = ReturnRetailDetailModel::where('return_id', $id)->get();
            // dd($return->salesOrderBy->warehouse_id);

            // dd($return_detail);
             foreach ($return_detail as $value) {
                if ($return->isapproved == 1) {
                    $stock = StockModel::where('products_id', $value->product_id)->where('warehouses_id', $return->retailBy->warehouse_id)->first();
                    $stock->stock = $stock->stock - $value->qty;
                    $stock->save();

                    foreach ($value->returnDirectCodeBy as $code) {
                        $getDot = TyreDotModel::where('id', $code->dot)->first();
                        $getDot->qty--;
                        $getDot->save();

                        $code->delete();
                    }
                }

                $value->delete();
            }
            // dd($return);
            $return->delete();
            DB::commit();

            return redirect()->back()->with('success', 'Return Invoice has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function indexTradeInSale(Request $request)
    {

        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $return = ReturnTradeSaleModel::with('returnDetailsBy', 'created_by', 'secondSaleBy')
                    ->whereBetween('return_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $return = ReturnTradeSaleModel::with('returnDetailsBy', 'created_by', 'secondSaleBy')
                    ->latest()
                    ->get();
            }
            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return  number_format($data->total, 0, '.', ',');
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('purchase_order_id', function (ReturnTradeSaleModel $ReturnTradeSaleModel) {
                    return $ReturnTradeSaleModel->secondSaleBy->second_sale_number;
                })
                ->editColumn('created_by', function (ReturnTradeSaleModel $ReturnTradeSaleModel) {
                    return $ReturnTradeSaleModel->created_by->name;
                })
                ->editColumn('return_reason', function (ReturnTradeSaleModel $ReturnTradeSaleModel) {
                    return '<span class="fw-bold text-danger">' . $ReturnTradeSaleModel->secondSaleBy->secondSaleBy->name . '</span>' . ' - ' . $ReturnTradeSaleModel->return_reason;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($return) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('returns._option_trade_sale', compact('return', 'ppn'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'Return Trade-In Sale'
        ];
        return view('returns.index_trade_sale', $data);
    }

    public function indexTradeIn(Request $request)
    {

        // dd($request->all());
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $return = ReturnTradePurchaseModel::with('returnDetailsBy', 'created_by', 'TradeInBy')
                    ->whereBetween('return_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $return = ReturnTradePurchaseModel::with('returnDetailsBy', 'created_by', 'TradeInBy')
                    ->where('return_date', date('Y-m-d'))
                    ->latest()
                    ->get();
            }
            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return  number_format($data->total, 0, '.', ',');
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('purchase_order_id', function (ReturnTradePurchaseModel $ReturnTradePurchaseModel) {
                    return $ReturnTradePurchaseModel->TradeInBy->trade_in_number;
                })
                ->editColumn('return_reason', function (ReturnTradePurchaseModel $ReturnTradePurchaseModel) {
                    return '<span class="fw-bold text-danger">' . $ReturnTradePurchaseModel->TradeInBy->tradeBy->name . '</span>' . ' - ' . $ReturnTradePurchaseModel->return_reason;
                })
                ->editColumn('created_by', function (ReturnTradePurchaseModel $ReturnTradePurchaseModel) {
                    return  $ReturnTradePurchaseModel->created_by->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($return) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('returns._option_trade_purchase', compact('return', 'ppn'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'Return Trade-In Purchase'
        ];
        return view('returns.index_trade_purchase', $data);
    }
    
     public function index_item_promotion(Request $request)
    {
        if ($request->ajax()) {
            $return = ReturnItemPromotionModel::with('returnDetailsBy', 'createdBy', 'transactionBy', 'transactionBy.customerBy', 'transactionBy.warehouseBy')
                ->whereBetween('return_date', [
                    !empty($request->from_date) ? $request->from_date : date('Y-m-d'),
                    $request->to_date ?? date('Y-m-d')
                ])
                ->latest()
                ->get();

            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('return_reason', function ($data) {
                    return '<span class="fw-bold text-danger">' . $data->transactionBy->createdBy->name . '</span>' . ' - ' . $data->return_reason;
                })
                ->editColumn('cust_id', function ($data) {
                    return $data->transactionBy->customerBy->code_cust . ' - ' . $data->transactionBy->customerBy->name_cust;
                })
                ->editColumn('created_by', function ($data) {
                    return $data->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran

                ->addColumn('action', function ($return) {
                    return view('returns._option_item_promotion', compact('return'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'All Item Promotion Return'
        ];
        return view('returns.index_item_promotion', $data);
    }
    
    public function index_purchase_item_promotion(Request $request)
    {
        if ($request->ajax()) {
            $return = ReturnItemPromotionPurchaseModel::with('returnDetailsBy', 'createdBy', 'purchaseBy', 'purchaseBy.supplierBy', 'purchaseBy.warehouseBy')
                ->whereBetween('return_date', [
                    !empty($request->from_date) ? $request->from_date : date('Y-m-d'),
                    $request->to_date ?? date('Y-m-d')
                ])
                ->latest()
                ->get();

            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('return_reason', function ($data) {
                    return '<span class="fw-bold text-danger">' . $data->purchaseBy->createdBy->name . '</span>' . ' - ' . $data->return_reason;
                })
                ->editColumn('supplier_id', function ($data) {
                    return $data->purchaseBy->supplierBy->name;
                })
                ->editColumn('created_by', function ($data) {
                    return $data->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran

                ->addColumn('action', function ($return) {
                    return view('returns._option_purchase_item_promotion', compact('return'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'All Item Promotion Purchase Return'
        ];
        return view('returns.index_purchase_item_promotion', $data);
    }


    public function index_purchase(Request $request)
    {

        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $return = ReturnPurchaseModel::with('returnDetailsBy', 'createdBy', 'purchaseOrderBy')
                    ->whereBetween('return_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $return = ReturnPurchaseModel::with('returnDetailsBy', 'createdBy', 'purchaseOrderBy')
                    ->where('return_date', date('Y-m-d'))
                    ->latest()
                    ->get();
            }
            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('return_reason', function ($data) {
                    return '<span class="fw-bold text-danger">' . $data->purchaseOrderBy->createdPurchaseOrder->name . '</span>' . ' - ' . $data->return_reason;
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('purchase_order_id', function (ReturnPurchaseModel $returnPurchaseModel) {
                    return $returnPurchaseModel->purchaseOrderBy->order_number;
                })
               
                ->editColumn('created_by', function (ReturnPurchaseModel $returnPurchaseModel) {
                    return $returnPurchaseModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($return) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('returns._option_purchase', compact('return', 'ppn'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'PO Return'
        ];
        return view('returns.index_purchase', $data);
    }

    public function index_retail(Request $request)
    {

        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            $return = ReturnRetailModel::with('returnDetailsBy', 'createdBy', 'retailBy')
                 ->when($request->from_date, function ($query) use ($request) {
                        return $query->whereBetween('return_date', [$request->from_date, $request->to_date]);
                    }, function ($query) use ($request) {
                        if($request->filter == "this_month"){
                            // Mendapatkan tanggal awal bulan ini
                            $firstDayOfMonth = date("Y-m-01");
                            
                            // Mendapatkan tanggal akhir bulan ini
                            $lastDayOfMonth = date("Y-m-t");
                            
                            return empty($query->from_date) ? $query->whereBetween('return_date', [$firstDayOfMonth, $lastDayOfMonth]) : $query;
                        }else{
                            $today = date('Y-m-d');
                            //  dd("hey");
                            return empty($query->from_date) ? $query->whereDate('return_date', $today) : $query;
                        }
                         
                    })
                ->where('isapproved', 1)
                ->where('isreceived', 1)
                ->latest()
                ->get();
            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('return_reason', function ($data) {
                    return '<span class="fw-bold text-danger">' . $data->retailBy->createdBy->name . '</span>' . ' - ' . $data->return_reason;
                })
                ->editColumn('retail_id', function (ReturnRetailModel $returnRetailModel) {
                    return $returnRetailModel->retailBy->order_number;
                })
                ->editColumn('created_by', function (ReturnRetailModel $returnRetailModel) {
                    return $returnRetailModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($return) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('returns._option_retail', compact('return', 'ppn'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'DS Return'
        ];
        return view('returns.index_retail', $data);
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
                    if ($selected_detail == null) {
                        $return += 0;
                    } else {
                        $return += $selected_detail->qty;
                    }
                }
            }
            array_push($return_amount, $return);
        }
        $data = [
            'title' => 'Return From Order  ' . $selected_so->order_number,
            'sales_order' => $selected_so,
            'return_amount' => $return_amount
        ];

        return view('returns.create', $data);
    }

    public function createTradeInSale(Request $request, $id)
    {
        // dd($id);
        $selected_so = SecondSaleModel::with('second_sale_details', 'secondSaleBy', 'warehouse')->where('id', $id)->first();
        // dd($selected_so);
        $selected_return = ReturnTradeSaleModel::with('returnDetailsBy')->where('second_sale_id', $id)->get();
        // dd($selected_return);
        $return_amount = [];

        foreach ($selected_so->second_sale_details as $value) {
            $return = 0;
            if ($selected_return != null) {
                foreach ($selected_return as $detail) {
                    $selected_detail = ReturnTradeSaleDetailModel::where('return_id', $detail->id)->where('product_id', $value->product_second_id)->first();
                    if ($selected_detail == null) {
                        $return += 0;
                    } else {
                        $return += $selected_detail->qty;
                    }
                }
            }
            array_push($return_amount, $return);
        }
        $data = [
            'title' => 'Return From Order: ' . $selected_so->trade_in_number,
            'trade_in' => $selected_so,
            'return_amount' => $return_amount
        ];

        return view('returns.create_trade_sale', $data);
    }
    public function createTradeIn(Request $request, $id)
    {
        // dd($id);
        $selected_so = TradeInModel::with('tradeInDetailBy')->where('id', $id)->first();
        // dd($selected_so);
        $selected_return = ReturnTradePurchaseModel::with('returnDetailsBy')->where('trade_in_id', $id)->get();
        // dd($selected_return);
        $return_amount = [];

        foreach ($selected_so->tradeInDetailBy as $value) {
            $return = 0;
            if ($selected_return != null) {
                foreach ($selected_return as $detail) {
                    $selected_detail = ReturnTradePurchaseDetailModel::where('return_id', $detail->id)->where('product_id', $value->product_trade_in)->first();
                    if ($selected_detail == null) {
                        $return += 0;
                    } else {
                        $return += $selected_detail->qty;
                    }
                }
            }
            array_push($return_amount, $return);
        }
        $data = [
            'title' => 'Return From Order ' . $selected_so->trade_in_number,
            'trade_in' => $selected_so,
            'return_amount' => $return_amount
        ];

        return view('returns.create_trade_purchase', $data);
    }

    public function storeTradeInSale(Request $request)
    {
        try {
            DB::beginTransaction();
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

            $model = new ReturnTradeSaleModel(); //Create New Model ReturnTradePurchaseModel

            //create return number
            $selected_po = SecondSaleModel::where('id', $request->get('po_id'))->first();
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $selected_po->warehouse_id)
                ->first();
            $length = 3;

            $lastRecord = ReturnTradeSaleModel::latest()->first();
            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->return_date)->format('m');
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
            $return_number = 'RTSPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

            $model->return_number = $return_number;
            $model->second_sale_id = $request->get('po_id');
            $model->return_date = Carbon::now()->format('Y-m-d');
            $model->createdBy = Auth::user()->id;
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
                $detail = new ReturnTradeSaleDetailModel(); //ReturnTradePurchaseDetailModel
                $detail->return_id = $model->id;
                $detail->product_id = $item['product_id'];
                $detail->qty = $item['qty'];

                //Check exceed order
                $selected_pod = SecondSaleDetailModel::where('second_sale_id', $model->second_sale_id) //TradeInDetailModel
                    ->where('product_second_id', $detail->product_id)->first();

                $selected_return = ReturnTradeSaleDetailModel::with('returnBy') //ReturnTradePurchaseDetailModel
                    ->whereHas('returnBy', function ($query) use ($model) {
                        $query->where('second_sale_id', $model->second_sale_id);
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
                // dd($returned_qty);
                if ($detail->qty > ($selected_pod->qty - $returned_qty)) {
                    $previous_product = ReturnTradeSaleDetailModel::where('return_id', $model->id)->get(); // ReturnTradePurchaseDetailModel
                    if ($previous_product != null) {
                        $previous_product->each->delete();
                        $model->delete();
                    }
                    return Redirect::back()->with('error', 'Return Purchase Order Fail! The number of items exceeds the order');
                }

                $detail->save();


                //Count Total
                // $harga = ProductCostSecondModel::where('id_product_trade_in', $detail->product_id)
                //     ->where('id_warehouse', $selected_po->warehouse_id)
                //     ->first();
                $diskon_persen = ($selected_pod->discount / 100) * $selected_pod->price;
                $harga_diskon = $selected_pod->price - $diskon_persen;
                $harga_rupiah = ($harga_diskon - $selected_pod->discount_rp);
                $total = $total + ($harga_rupiah * $detail->qty);
            }

            $model->total = $total;
            $model->save();

            $selected_po = SecondSaleModel::where('id', $model->second_sale_id)->first(); //TradeInModel


            //Change Stock
            $returnDetail = ReturnTradeSaleDetailModel::where('return_id', $model->id)->get(); //ReturnTradePurchaseDetailModel
            foreach ($returnDetail as $value) {
                $getStock = SecondProductModel::where('products_id', $value->product_id) //SecondProductModel
                    ->where('warehouses_id', $selected_po->warehouse_id)
                    ->first();
                $old_stock = $getStock->qty;
                $getStock->qty = $old_stock + $value->qty;
                $getStock->save();
            }

            DB::commit();
            return redirect('/retail_second_products')->with('success', 'Return Trade-In Purchase Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function create_item_promotion(Request $request, $id)
    {
        $selected_ip = ItemPromotionTransactionModel::with('transactionDetailBy')->where('id', $id)->first();
        $selected_return = ReturnItemPromotionModel::with('returnDetailsBy')->where('id_transaction', $id)->get();
        $return_amount = [];

        foreach ($selected_ip->transactionDetailBy as $value) {
            $return = 0;
            if ($selected_return != null) {
                foreach ($selected_return as $detail) {
                    $selected_detail = ReturnItemPromotionDetailModel::where('return_id', $detail->id)->where('id_item', $value->id_item)->first();
                    if ($selected_detail == null) {
                        $return += 0;
                    } else {
                        $return += $selected_detail->qty;
                    }
                }
            }
            array_push($return_amount, $return);
        }
        $data = [
            'title' => 'Return From Order  ' . $selected_ip->order_number,
            'item_promotion' => $selected_ip,
            'return_amount' => $return_amount
        ];

        return view('returns.create_item_promotion', $data);
    }

    public function create_purchase_item_promotion(Request $request, $id)
    {
        $selected_ip = ItemPromotionPurchaseModel::with('purchaseDetailBy')->where('id', $id)->first();
        $selected_return = ReturnItemPromotionPurchaseModel::with('returnDetailsBy')->where('purchase_id', $id)->get();
        $return_amount = [];

        foreach ($selected_ip->purchaseDetailBy as $value) {
            $return = 0;
            if ($selected_return != null) {
                foreach ($selected_return as $detail) {
                    $selected_detail = ReturnItemPromotionPurchaseDetailModel::where('return_id', $detail->id)->where('item_id', $value->item_id)->first();
                    if ($selected_detail == null) {
                        $return += 0;
                    } else {
                        $return += $selected_detail->qty;
                    }
                }
            }
            array_push($return_amount, $return);
        }
        $data = [
            'title' => 'Return From Order  ' . $selected_ip->order_number,
            'item_promotion' => $selected_ip,
            'return_amount' => $return_amount
        ];

        return view('returns.create_purchase_item_promotion', $data);
    }
    
    public function storeTradeIn(Request $request)
    {
        try {
            DB::beginTransaction();
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

            $model = new ReturnTradePurchaseModel(); //Create New Model

            //create return number
            $selected_po = TradeInModel::where('id', $request->get('po_id'))->first();
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $selected_po->warehouse_id)
                ->first();
            $length = 3;

            $lastRecord = ReturnTradePurchaseModel::latest()->first();
            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->return_date)->format('m');
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
            $return_number = 'RTIP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

            $model->return_number = $return_number;
            $model->trade_in_id = $request->get('po_id');
            $model->return_date = Carbon::now()->format('Y-m-d');
            $model->createdBy = Auth::user()->id;
            $model->save();

            $get_reason = $request->get('return_reason1');
            if ($get_reason == "Other") {
                $model->return_reason = $request->get('return_reason');
            } else {
                $model->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
            }
            $selected_po = TradeInModel::where('id', $model->trade_in_id)->first();

            $total = 0;
            foreach ($request->returnFields as $item) {
                $detail = new ReturnTradePurchaseDetailModel();
                $detail->return_id = $model->id;
                $detail->product_id = $item['product_id'];
                $detail->qty = $item['qty'];

                //Check exceed order
                $selected_pod = TradeInDetailModel::where('trade_in_id', $model->trade_in_id)
                    ->where('product_trade_in', $detail->product_id)->first();

                $selected_return = ReturnTradePurchaseDetailModel::with('returnBy')
                    ->whereHas('returnBy', function ($query) use ($model) {
                        $query->where('trade_in_id', $model->trade_in_id);
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
                // dd($returned_qty);
                if ($detail->qty > ($selected_pod->qty - $returned_qty)) {
                    $previous_product = ReturnTradePurchaseDetailModel::where('return_id', $model->id)->get();
                    if ($previous_product != null) {
                        $previous_product->each->delete();
                        $model->delete();
                    }
                    return Redirect::back()->with('error', 'Return Purchase Order Fail! The number of items exceeds the order');
                }
                // $harga = ProductCostSecondModel::where('id_product_trade_in', $detail->product_id)
                //     ->where('id_warehouse', $selected_po->warehouse_id)
                //     ->first();
                $total = $total + ($selected_pod->price * $detail->qty);
                $detail->save();
            }

            $model->total = $total;
            $model->save();



            //Change Stock
            $returnDetail = ReturnTradePurchaseDetailModel::where('return_id', $model->id)->get();
            foreach ($returnDetail as $value) {
                $getStock = SecondProductModel::where('products_id', $value->product_id)
                    ->where('warehouses_id', $selected_po->warehouse_id)
                    ->first();
                $old_stock = $getStock->qty;
                $getStock->qty = $old_stock - $value->qty;
                $getStock->save();
            }

            DB::commit();
            return redirect('/trade_invoice')->with('success', 'Return Trade-In Purchase Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function create_purchase(Request $request, $id)
    {
        $selected_po = PurchaseOrderModel::with('purchaseOrderDetailsBy')->where('id', $id)->first();
        $selected_return = ReturnPurchaseModel::with('returnDetailsBy')->where('purchase_order_id', $id)->get();
        $return_amount = [];

        foreach ($selected_po->purchaseOrderDetailsBy as $value) {
            $return = 0;
            if ($selected_return != null) {
                foreach ($selected_return as $detail) {
                    $selected_detail = ReturnPurchaseDetailModel::where('return_id', $detail->id)->where('product_id', $value->product_id)->first();
                    if ($selected_detail == null) {
                        $return += 0;
                    } else {
                        $return += $selected_detail->qty;
                    }
                }
            }
            array_push($return_amount, $return);
        }
        $data = [
            'title' => 'Return From Order ' . $selected_po->order_number,
            'purchase_order' => $selected_po,
            'return_amount' => $return_amount
        ];

        return view('returns.create_purchase', $data);
    }

    public function create_retail(Request $request, $id)
    {
        // dd('hei');
        $selected_retail = DirectSalesModel::with('directSalesDetailBy')->where('id', $id)->first();
        $selected_return = ReturnRetailModel::with('returnDetailsBy')->where('retail_id', $id)->get();
        $return_amount = [];

        foreach ($selected_retail->directSalesDetailBy as $value) {
            $return = 0;
            if ($selected_return != null) {
                foreach ($selected_return as $detail) {
                    $selected_detail = ReturnRetailDetailModel::where('return_id', $detail->id)->where('product_id', $value->product_id)->first();
                    if ($selected_detail == null) {
                        $return += 0;
                    } else {
                        $return += $selected_detail->qty;
                    }
                }
            }
            array_push($return_amount, $return);
        }
        $data = [
            'title' => 'Return From Order ' . $selected_retail->order_number,
            'retail' => $selected_retail,
            'return_amount' => $return_amount
        ];

        return view('returns.create_retail', $data);
    }
    
     public function receiving_indirect(Request $request)
    {
        if ($request->ajax()) {
            $return = ReturnModel::with('returnDetailsBy', 'createdBy', 'salesOrderBy', 'salesOrderBy.customerBy', 'salesOrderBy.createdSalesOrder')
                ->whereBetween('return_date', [
                    !empty($request->from_date) ? $request->from_date : date('Y-m-d'),
                    $request->to_date ?? date('Y-m-d')
                ])
                ->where('isapproved', 0)
                ->where('isreceived', 0)
                ->latest()
                ->get();

            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, '.', ',');
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('return_reason', function ($data) {
                    return '<span class="fw-bold text-danger">' . $data->salesOrderBy->createdSalesOrder->name . '</span>' . ' - ' . $data->return_reason;
                })
                ->editColumn('cust_id', function (ReturnModel $returnModel) {
                    return $returnModel->salesOrderBy->customerBy->code_cust . ' - ' . $returnModel->salesOrderBy->customerBy->name_cust;
                })
                ->editColumn('created_by', function (ReturnModel $returnModel) {
                    return $returnModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran

                ->addColumn('action', function ($return) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('returns._option_receiving_indirect', compact('return', 'ppn'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'Indirect Return Receiving'
        ];
        return view('returns.receiving_indirect', $data);
    }
    
    public function approval_indirect(Request $request)
    {
        if ($request->ajax()) {
            $return = ReturnModel::with('returnDetailsBy', 'createdBy', 'salesOrderBy', 'salesOrderBy.customerBy', 'salesOrderBy.createdSalesOrder')
                ->whereBetween('return_date', [
                    !empty($request->from_date) ? $request->from_date : date('Y-m-d'),
                    $request->to_date ?? date('Y-m-d')
                ])
                ->where('isapproved', 0)
                ->where('isreceived', 1)
                ->latest()
                ->get();

            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, '.', ',');
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('return_reason', function ($data) {
                    return '<span class="fw-bold text-danger">' . $data->salesOrderBy->createdSalesOrder->name . '</span>' . ' - ' . $data->return_reason;
                })
                ->editColumn('cust_id', function (ReturnModel $returnModel) {
                    return $returnModel->salesOrderBy->customerBy->code_cust . ' - ' . $returnModel->salesOrderBy->customerBy->name_cust;
                })
                ->editColumn('created_by', function (ReturnModel $returnModel) {
                    return $returnModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran

                ->addColumn('action', function ($return) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('returns._option_approval_indirect', compact('return', 'ppn'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'Indirect Return Approval'
        ];
        return view('returns.approval_indirect', $data);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
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
                ->where('warehouses.id', $selected_so->warehouse_id)
                ->first();
            $pad_area_code = str_pad($kode_area->area_code, 2,'0', STR_PAD_LEFT);
            $similar_number = 'RSPP-'.$pad_area_code.'-';
            $length = 3;
            $last_record = ReturnModel::where('return_number', 'like', "%$similar_number%")->latest()->first();
            $last_three_digits = (int) substr($last_record->return_number, -3);
            $next_three_digits = $last_three_digits + 1;
            $cust_number_id = str_pad($next_three_digits, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $return_number = 'RSPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
            // dd($return_number);

            $model->return_number = $return_number;
            $model->sales_order_id = $request->get('so_id');
            $model->return_date = Carbon::now()->format('Y-m-d');
            $model->created_by = Auth::user()->id;
            $model->invoice_created = $selected_so->created_by;
            $model->isapproved = 0;
            $model->isreceived = 0;
            $model->save();

           $get_reason = $request->get('return_reason1');
            if ($get_reason == "Other") {
                $model->return_reason = $request->get('return_reason');
            } else {
                $model->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
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
                $price = $selected_sod->price;
                if($selected_sod->price == null){
                    $product = ProductModel::where('id', $detail->product_id)->first();
                    $ppn = (ValueAddedTaxModel::first()->ppn / 100) * str_replace(',', '.', $product->harga_jual_nonretail);
                    $price = str_replace(',', '.', $product->harga_jual_nonretail) + $ppn;
                }
                
                $diskon =  $selected_sod->discount / 100;
                $hargaDiskon = $price * $diskon;
                $hargaAfterDiskon = ($price -  $hargaDiskon) - $selected_sod->discount_rp;
                $total = $total + ($hargaAfterDiskon * $detail->qty);
            }
            $model->total = $total;
            $model->save();

            $selected_so->isPaid = 0;
            $selected_so->paid_date = null;
            $selected_so->save();
            
            $cust_name = CustomerModel::where('id', $model->salesOrderBy->customers_id)->first()->name_cust;
            $message = 'Return Indirect Sales ' . $model->return_number . ' from ' . $cust_name . ' has been created! Please check';
            event(new LeaveApproval('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->job_id = 1070;
            $notif->save();

            // $returnDetail = ReturnDetailModel::where('return_id', $model->id)->get();
            // $selected_so = SalesOrderModel::where('id', $model->sales_order_id)->first();

            //Check Paid
            // $total_return = ReturnDetailModel::where('return_id', $model->id)->sum('qty');
            // $total_sales = SalesOrderDetailModel::where('sales_orders_id', $model->sales_order_id)->sum('qty');
            // if ($total_return == $total_sales) {
            //     $selected_so->isPaid = 1;
            //     $selected_so->paid_date = date('Y-m-d');
            //     $selected_so->save();
            // }

            //Change Stock
            // $customer = CustomerModel::where('id', $selected_so->customers_id)->first();
            // $warehouse = WarehouseModel::where('id', $selected_so->warehouse_id)->first();
            // foreach ($returnDetail as $value) {
            //     $getStock = StockModel::where('products_id', $value->product_id)
            //         ->where('warehouses_id', $warehouse->id)
            //         ->first();
            //     $old_stock = $getStock->stock;
            //     $getStock->stock = $old_stock + $value->qty;
            //     $getStock->save();
            // }
            DB::commit();
            return redirect('/return')->with('success', 'Return Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function receive_indirect(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
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
                    $last = count($selected_detail);
                    $i = 0;
                    foreach ($selected_detail as $detail) {
                        if (++$i != $last) {
                            $returned_qty = $returned_qty + $detail->qty;
                        }
                    }
                }
                // dd('detail: ' . $detail->qty . ', ' . $selected_sod->qty . ', ' . $returned_qty);

                if ($product['qty'] > ($selected_sod->qty - $returned_qty)) {
                    return Redirect::back()->with('error', 'Edit Return Order Fail! The number of items exceeds the order');
                }
            }

            $get_reason = $request->get('return_reason1');
            $old_reason = $selected_return->return_reason;
            if ($old_reason == $get_reason) {
                $selected_return->return_reason = $get_reason;
            } elseif ($get_reason == "Other") {
                $selected_return->return_reason = $request->get('return_reason');
            } else {
                $selected_return->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
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

                    // $price = $product_exist->price;
                } else {
                    $product_exist = new ReturnDetailModel();
                    $product_exist->return_id = $id;
                    //    $product_exist->price = $selected_sod->price;
                    $product_exist->product_id = $product['product_id'];
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();

                    //    $price = $product_exist->price
                }
                // $products = ProductModel::where('id', $product['product_id'])->first();

                // if ($selected_sod != null && $selected_sod->price != null) {
                //     $price = $selected_sod->price;
                // } else {
                //     $price = $products->harga_jual_nonretail;
                // }
                //Count Total
                $price = $selected_sod->price;
                if($selected_sod->price == null){
                    $product = ProductModel::where('id', $product['product_id'])->first();
                    $ppn = (ValueAddedTaxModel::first()->ppn / 100) * str_replace(',', '.', $product->harga_jual_nonretail);
                    $price = str_replace(',', '.', $product->harga_jual_nonretail) + $ppn;
                }
                
                $diskon =  $selected_sod->discount / 100;
                $hargaDiskon = $price * $diskon;
                $hargaAfterDiskon = ($price -  $hargaDiskon) - $selected_sod->discount_rp;
                $total = $total + ($hargaAfterDiskon * $product['qty']);
            }
           
            $selected_return->total = $total;
            $selected_return->isapproved = 0;
            $selected_return->isreceived = 1;
            $saved = $selected_return->save();

            $del = ReturnDetailModel::where('return_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            $cust_name = CustomerModel::where('id', $selected_return->salesOrderBy->customers_id)->first()->name_cust;
            $message = 'Return Indirect Sales ' . $selected_return->return_number . ' from ' . $cust_name . ' has been received! Please do the approval';
            event(new LeaveApproval('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->job_id = 1068;
            $notif->save();

            DB::commit();
            return redirect('/return/receiving')->with('success', 'Receive Return Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/return/receiving')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function approve_indirect(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
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
                    $last = count($selected_detail);
                    $i = 0;
                    foreach ($selected_detail as $detail) {
                        if (++$i != $last) {
                            $returned_qty = $returned_qty + $detail->qty;
                        }
                    }
                }
                // dd('detail: ' . $detail->qty . ', ' . $selected_sod->qty . ', ' . $returned_qty);

                if ($product['qty'] > ($selected_sod->qty - $returned_qty)) {
                    return Redirect::back()->with('error', 'Edit Return Order Fail! The number of items exceeds the order');
                }
            }

            $get_reason = $request->get('return_reason1');
            $old_reason = $selected_return->return_reason;
            if ($old_reason == $get_reason) {
                $selected_return->return_reason = $get_reason;
            } elseif ($get_reason == "Other") {
                $selected_return->return_reason = $request->get('return_reason');
            } else {
                $selected_return->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
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

                    // $price = $product_exist->price;
                } else {
                    $product_exist = new ReturnDetailModel();
                    $product_exist->return_id = $id;
                    //    $product_exist->price = $selected_sod->price;
                    $product_exist->product_id = $product['product_id'];
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();

                    //    $price = $product_exist->price
                }
                $products = ProductModel::where('id', $product['product_id'])->first();

                 if ($selected_sod != null && $selected_sod->price != null) {
                    $price = $selected_sod->price;
                } else {
                    $price = $products->harga_jual_nonretail;
                    $price = (ValueAddedTaxModel::first()->ppn / 100) * floatval((str_replace(',', '.', $price)));
                
                $price = floatval((str_replace(',', '.', $price)))+$ppn;
                }
                //Count Total
                $diskon =  $selected_sod->discount / 100;
                $ppn = (ValueAddedTaxModel::first()->ppn / 100) * floatval((str_replace(',', '.', $price)));
                
                $ppn_cost = floatval((str_replace(',', '.', $price)));
                $hargaDiskon = $ppn_cost * $diskon;
                $hargaAfterDiskon = ($ppn_cost -  $hargaDiskon) - $selected_sod->discount_rp;
                $total = $total + ($hargaAfterDiskon * $product['qty']);
            }
                        //   dd($total);

            $selected_return->total = $total;
            $selected_return->return_date = date('Y-m-d', strtotime($request->return_date));
            $selected_return->isapproved = 1;
            $selected_return->isreceived = 1;
            $saved = $selected_return->save();

            $del = ReturnDetailModel::where('return_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            $returnDetail = ReturnDetailModel::where('return_id', $selected_return->id)->get();
            $selected_so = SalesOrderModel::where('id', $selected_return->sales_order_id)->first();
            $journal = null;
            if ($selected_so->isPaid == 0) {
                $journal = createJournal(
                    Carbon::now()->format('Y-m-d'),
                    'Retur Penjualan Indirect Kredit.' . $selected_so->order_number,
                    $selected_so->warehouse_id
                );

                // ** Jika Jurnal Berhasil Disimpan ** //
                if ($journal != "" && $journal != null && $journal != false) {
                    // ** COA Return Penjualan ** //
                    createJournalDetail(
                        $journal,
                        '4-102',
                        $selected_return->return_number,
                        round($selected_so->total_after_ppn) / 1.11,
                        0
                    );
                    // ** COA PPn Keluaran ** //
                    createJournalDetail(
                        $journal,
                        '2-300',
                        $selected_return->return_number,
                        round($selected_so->total_after_ppn)  / 1.11 * (ValueAddedTaxModel::first()->ppn / 100),
                        0
                    );
                    // ** COA Piutang ** //
                    createJournalDetail(
                        $journal,
                        '1-200',
                        $selected_return->return_number,
                        0,
                        round($selected_so->total_after_ppn)
                    );
                }
            }

            //Check Paid
            $total_return = ReturnDetailModel::where('return_id', $selected_return->id)->sum('qty');
            $total_sales = SalesOrderDetailModel::where('sales_orders_id', $selected_return->sales_order_id)->sum('qty');
            if ($total_return == $total_sales) {
                $selected_so->isPaid = 1;
                $selected_so->paid_date = date('Y-m-d');
                $selected_so->save();
            }
            
            $get_hpp_detail = JournalDetail::where('journal_id', $selected_so->id_jurnal_hpp)->where('debit', '>', 0)->first();

            
            $hpp = createJournal(
                Carbon::now()->format('Y-m-d'),
                'Persediaan Bertambah.' . $selected_return->return_number,
                $selected_so->warehouse_id
            );

            if ($hpp != "" && $hpp != null && $hpp != false) {
                // $hpp_id = $hpp->id;
                $hpp_excl = 0;
                // foreach ($returnDetail as $hpp_c) {
                //     $getProduct = ProductModel::where('id', $hpp_c->product_id)->first();
                //     $hpp_excl = $hpp_excl + ($getProduct->hpp * $hpp_c->qty);
                // }

                // $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
                // $hpp_ppn = $hpp_excl * $current_ppn;
                // $hpp_incl = $hpp_excl + $hpp_ppn;

                //Persediaan Barang Dagang
                createJournalDetail(
                    $hpp,
                    '1-401',
                    $selected_return->return_number,
                    $get_hpp_detail->debit,
                    0
                );

                //HPP
                createJournalDetail(
                    $hpp,
                    '6-000',
                    $selected_return->return_number,
                    0,
                    $get_hpp_detail->debit
                );
            }
            
            $selected_return->id_jurnal = $journal;
            $selected_return->id_jurnal_hpp = $hpp;
            $selected_return->save();

            //Change Stock
            foreach ($returnDetail as $value) {
                $getStock = StockModel::where('products_id', $value->product_id)
                    ->where('warehouses_id', $selected_so->warehouse_id)
                    ->first();
                $old_stock = $getStock->stock;
                $getStock->stock = $old_stock + $value->qty;
                $getStock->save();
            }

            DB::commit();
            return redirect('/return/approval')->with('success', 'Approve Return Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/return/approval')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function store_purchase(Request $request)
    {
        // Validate Input
        $request->validate([
            "returnFields.*.product_id" => "required|numeric",
            "returnFields.*.qty" => "required|numeric",
            "return_reason1" => "required"
        ]);
        
        try {
            DB::beginTransaction();

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
    
            $model = new ReturnPurchaseModel();
    
            //create return number
            $selected_po = PurchaseOrderModel::where('id', $request->get('po_id'))->first();
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $selected_po->warehouse_id)
                ->first();
            $length = 3;
            $id = intval(ReturnPurchaseModel::where('return_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
            $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $return_number = 'RPPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
    
            $model->return_number = $return_number;
            $model->purchase_order_id = $request->get('po_id');
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
            $product_id = [];
            foreach ($request->returnFields as $item) {
                // ** kemballikan HPP lama
                $hpp_produk = ProductModel::where('id', $item['product_id'])->first();
                $hpp_produk->hpp = $hpp_produk->old_hpp;
                $hpp_produk->save();

                
                $detail = new ReturnPurchaseDetailModel();
                $detail->return_id = $model->id;
                $detail->product_id = $item['product_id'];
                $detail->qty = $item['qty'];
                array_push($product_id, $detail->product_id);
                //Check exceed order
                $selected_pod = PurchaseOrderDetailModel::where('purchase_order_id', $model->purchase_order_id)
                    ->where('product_id', $detail->product_id)->first();
    
                $selected_return = ReturnPurchaseDetailModel::with('returnBy')
                    ->whereHas('returnBy', function ($query) use ($model) {
                        $query->where('purchase_order_id', $model->purchase_order_id);
                    })->where('product_id', $item['product_id'])->get();
                
                if($selected_pod->price == null){
                    $product = ProductModel::where('id', $detail->product_id)->first();
                    $harga_double = Crypt::decryptString($product->harga_beli);
                    $harga_float = str_replace(',', '.', $harga_double);
                }else{
                    $harga_float = $selected_pod->price;
                }    
                $detail->price = $harga_float; 
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
    
                if ($detail->qty > ($selected_pod->qty - $returned_qty)) {
                    $previous_product = ReturnPurchaseDetailModel::where('return_id', $model->id)->get();
                    if ($previous_product != null) {
                        $previous_product->each->delete();
                        $model->delete();
                    }
                    return Redirect::back()->with('error', 'Return Purchase Order Fail! The number of items exceeds the order');
                }
    
                $detail->save();
    
    
                //Count Total
                $harga_ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float) $harga_float;
                $harga_incl = (float) $harga_float + $harga_ppn;
                $harga_diskon = (float) $harga_incl * ($selected_pod->discount / 100);
                $harga_final = (float) $harga_incl - $harga_diskon;
                $total = $total + ($harga_final * $detail->qty);
            }
            // $ppn_total = (ValueAddedTaxModel::first()->ppn / 100) * $total;
            // $total_sub = $total + $ppn_total;
            $model->total = $total;
            $model->save();
    
            $selected_po = PurchaseOrderModel::where('id', $model->purchase_order_id)->first();
    
            //Check Paid
           $data_kredit = PurchaseOrderCreditModel::where('purchase_order_id', $model->purchase_order_id)->get();
            if ($data_kredit->count() > 0) {
                $selected_po->isPaid = 0;
                $selected_po->paid_date = null;
            } else {
                // ** ini sisa yang belum bayar
                $journal = createJournal(
                    Carbon::now()->format('Y-m-d'),
                    'Retur Persediaan Pembelian Kredit.' . $selected_po->order_number,
                    $selected_po->warehouse_id
                );



                // ** Jika Jurnal Berhasil Disimpan ** //
                if ($journal != "" && $journal != null && $journal != false) {
                    // ** COA Hutang Dagang ** //
                    createJournalDetail(
                        $journal,
                        '2-101',
                        $selected_po->order_number,
                        round($selected_po->total),
                        0
                    );
                    // ** COA Return Pembelian ** //
                    createJournalDetail(
                        $journal,
                        '5-103',
                        $selected_po->order_number,
                        0,
                        round($selected_po->total)  / 1.11
                    );
                    // ** COA PPn Masukan ** //
                    createJournalDetail(
                        $journal,
                        '1-600',
                        $selected_po->order_number,
                        0,
                        round($selected_po->total)  / 1.11 * (ValueAddedTaxModel::first()->ppn / 100)
                    );
                }
                $model->jurnal_id = $journal;
                $model->save();
                $selected_po->isPaid = 1;
                $selected_po->paid_date = now();
            }
    
            if ($selected_po->isvalidated == 1) {
                //Change Stock
                $returnDetail = ReturnPurchaseDetailModel::where('return_id', $model->id)->get();
                foreach ($returnDetail as $value) {
                    $getStock = StockModel::where('products_id', $value->product_id)
                        ->where('warehouses_id', $selected_po->warehouse_id)
                        ->first();
                    $old_stock = $getStock->stock;
                    $getStock->stock = $old_stock - $value->qty;
                    $getStock->save();
                }
            }
            DB::commit();
            return redirect('/return_purchase')->with('success', 'Return Purchase Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function receiving_retail(Request $request)
    {
        //hrusnya masuk ke sini
        // get kode area
        // dd('helloooo');
        if ($request->ajax()) {
            $return = ReturnRetailModel::with('returnDetailsBy', 'createdBy', 'retailBy')
                ->whereBetween('return_date', [
                    !empty($request->from_date) ? $request->from_date : date('Y-m-d'),
                    $request->to_date ?? date('Y-m-d')
                ])
                ->where('isapproved', 0)
                ->where('isreceived', 0)
                ->latest()
                ->get();
            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('return_reason', function ($data) {
                    return '<span class="fw-bold text-danger">' . $data->retailBy->createdBy->name . '</span>' . ' - ' . $data->return_reason;
                })
                ->editColumn('retail_id', function (ReturnRetailModel $returnRetailModel) {
                    return $returnRetailModel->retailBy->order_number;
                })
                ->editColumn('created_by', function (ReturnRetailModel $returnRetailModel) {
                    return $returnRetailModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($return) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('returns._option_receiving_retail', compact('return', 'ppn'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'Receiving Direct Sales Return'
        ];
        return view('returns.receiving_retail', $data);
    }
    
     public function approval_retail(Request $request)
    {

        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            $return = ReturnRetailModel::with('returnDetailsBy', 'createdBy', 'retailBy')
                ->whereBetween('return_date', [
                    !empty($request->from_date) ? $request->from_date : date('Y-m-d'),
                    $request->to_date ?? date('Y-m-d')
                ])
                ->where('isapproved', 0)
                ->where('isreceived', 1)
                ->latest()
                ->get();
            return datatables()->of($return)
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->return_date));
                })
                ->editColumn('return_reason', function ($data) {
                    return '<span class="fw-bold text-danger">' . $data->retailBy->createdBy->name . '</span>' . ' - ' . $data->return_reason;
                })
                ->editColumn('retail_id', function (ReturnRetailModel $returnRetailModel) {
                    return $returnRetailModel->retailBy->order_number;
                })
                ->editColumn('created_by', function (ReturnRetailModel $returnRetailModel) {
                    return $returnRetailModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($return) {
                    $ppn = ValueAddedTaxModel::first()->ppn / 100;
                    return view('returns._option_approval_retail', compact('return', 'ppn'))->render();
                })
                ->rawColumns(['return_reason'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'Approval Direct Sales Return'
        ];
        return view('returns.approval_retail', $data);
    }

    public function store_retail(Request $request)
    {
         try {
            DB::beginTransaction();
        // dd($check[count($request->returnFields) - 1]);
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
        $dot_arr = [];


        foreach ($request->returnFields as $check) {
            $keys = array_keys($check);
            $lastKey = end($keys);
            if (is_numeric($lastKey)) {
                array_push($products_arr, $check['product_id']);

                for ($i = 0; $i <= $lastKey; $i++) {
                    if (isset($check[$i]['dot'])) {
                        array_push($dot_arr, $check[$i]['dot']);
                    }
                }
            }
        }

        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));
        $duplicates_dot = array_unique(array_diff_assoc($dot_arr, array_unique($dot_arr)));

        if (!empty($duplicates)) {
            return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate product.');
        }
        if (!empty($duplicates_dot)) {
            return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate DOT.');
        }

        // dd($duplicates_dot);
        $model = new ReturnRetailModel();

        //create return number
        $selected_retail = DirectSalesModel::where('id', $request->get('retail_id'))->first();
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', $selected_retail->warehouse_id)
            ->first();
        $pad_area_code = str_pad($kode_area->area_code, 2,'0', STR_PAD_LEFT);
        $similar_number = 'RRPP-'.$pad_area_code.'-';
        $length = 3;
        $last_record = ReturnRetailModel::where('return_number', 'like', "%$similar_number%")->latest()->first();
        $last_three_digits = (int) substr($last_record->return_number, -3);
        $next_three_digits = $last_three_digits + 1;
        // $id = intval(ReturnRetailModel::where('return_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $cust_number_id = str_pad($next_three_digits, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $return_number = 'RRPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

        $model->return_number = $return_number;
        $model->retail_id = $request->get('retail_id');
        $model->return_date = Carbon::now()->format('Y-m-d');
        $model->created_by = Auth::user()->id;
        $model->isapproved = 0;
        $model->isreceived = 0;
        $model->save();

        $get_reason = $request->get('return_reason1');
        if ($get_reason == "Other") {
            $model->return_reason = $request->get('return_reason');
        } else {
            $model->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
        }

        $total = 0;
        foreach ($request->returnFields as $item) {
            $detail = new ReturnRetailDetailModel();
            $detail->return_id = $model->id;
            $detail->product_id = $item['product_id'];
            $detail->qty = $item['qty'];

            //Check exceed order
            $selected_rod = DirectSalesDetailModel::where('direct_id', $model->retail_id)
                ->where('product_id', $detail->product_id)->first();

            $selected_return = ReturnRetailDetailModel::with('returnBy')
                ->whereHas('returnBy', function ($query) use ($model) {
                    $query->where('retail_id', $model->retail_id);
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

            if ($detail->qty > ($selected_rod->qty - $returned_qty)) {
                $previous_product = ReturnRetailDetailModel::where('return_id', $model->id)->get();
                if ($previous_product != null) {
                    $previous_product->each->delete();
                    $model->delete();
                }
                return Redirect::back()->with('error', 'Return Purchase Order Fail! The number of items exceeds the order');
            }

            //get Price
            $detail->price = $selected_rod->price;
            $detail->save();

            $keys = array_keys($item);
            $lastKey = end($keys);

            if (is_numeric($lastKey)) {
                for ($i = 0; $i <= $lastKey; $i++) {
                    if (isset($item[$i]['dot'])) {
                        $code = new ReturnRetailCodeModel();
                        $code->return_detail_id = $detail->id;
                        $code->dot = $item[$i]['dot'];
                        $code->qty = $item[$i]['qtyDot'];
                        $code->save();
                    }
                }

                //change dot stock
                // for ($i = 0; $i <= $lastKey; $i++) {
                //     if (isset($item[$i]['dot'])) {
                //         $getDot = TyreDotModel::where('id', $item[$i]['dot'])->first();
                //         $getDot->qty += $item[$i]['qtyDot'];
                //         $getDot->save();
                //     }
                // }
            }

            //Count Total
            $price = $detail->price;
            if ($detail->price == null) {
                $product = ProductCostModel::where('id_product', $detail->product_id)->where('id_warehouse', $selected_retail->warehouse_id)->first();
                $harga_double = $product->harga_jual;
                $harga_ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float) $harga_double;
                $price = (float) $harga_double + $harga_ppn;
            }

            $harga_diskon = (float) $price * ($selected_rod->discount / 100);
            $harga_final = (float) $price - $harga_diskon - $selected_rod->discount_rp;
            $total = $total + ($harga_final * $detail->qty);
        }
        $total_sub = $total;
        $model->total = $total_sub;
        $model->save();
        
        $selected_retail->isPaid = 0;
        $selected_retail->paid_date = null;
        $selected_retail->save();
        
        $cust_name = '';
        if (is_numeric($model->retailBy->cust_name)) {
            $cust_name = CustomerModel::where('id', $model->retailBy->cust_name)->first()->name_cust;
        } else {
            $cust_name = $model->retailBy->cust_name;
        }

        $message = 'Return Direct Sales ' . $model->return_number . ' from ' . $cust_name . ' has been created! Please check';
        event(new LeaveApproval('From: ' . Auth::user()->name,  $message));
        $notif = new NotificationsModel();
        $notif->message = $message;
        $notif->status = 0;
        $notif->job_id = 1071;
        $notif->save();

        //Check Paid
        // $total_return =  ReturnRetailDetailModel::where('return_id', $model->id)->sum('qty');
        // $total_retails = DirectSalesDetailModel::where('direct_id', $model->retail_id)->sum('qty');
        // if ($total_return == $total_retails) {
        //     $selected_retail->isPaid = 1;
        //     $selected_retail->paid_date = date('Y-m-d');
        //     $selected_retail->save();
        // }

        //Change Stock
        // $returnDetail = ReturnRetailDetailModel::where('return_id', $model->id)->get();
        // foreach ($returnDetail as $value) {
        //     $getStock = StockModel::where('products_id', $value->product_id)
        //         ->where('warehouses_id', $selected_retail->warehouse_id)
        //         ->first();
        //     $old_stock = $getStock->stock;
        //     $getStock->stock = $old_stock + $value->qty;
        //     $getStock->save();
        // }
        DB::commit();
        return redirect('/retail')->with('success', 'Return Retail Order Success!');
         } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/retail')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function receive_retail(Request $request, $id)
    {
        // dd($request->all());
        // Validate Input

        $request->validate([
            "returnFields.*.product_id" => "required|numeric",
            "returnFields.*.qty" => "required|numeric",
            "return_reason1" => "required"
        ]);
        try {
            DB::beginTransaction();

            //Check Number of product
            if ($request->returnFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }

            //Check Duplicate
            $products_arr = [];
            $dot_arr = [];

            foreach ($request->returnFields as $check) {
                $keys = array_keys($check);
                $lastKey = end($keys);
                array_push($products_arr, $check['product_id']);
                if (is_numeric($lastKey)) {
                    for ($i = 0; $i <= $lastKey; $i++) {
                        if (isset($check[$i]['dot'])) {
                            array_push($dot_arr, $check[$i]['dot']);
                        }
                    }
                }
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));
            $duplicates_dot = array_unique(array_diff_assoc($dot_arr, array_unique($dot_arr)));

            if (!empty($duplicates)) {
                return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate product.');
            }
            if (!empty($duplicates_dot)) {
                return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate DOT.');
            }

            $selected_return = ReturnRetailModel::where('id', $id)->first();
            $selected_retail = DirectSalesModel::where('id', $selected_return->retail_id)->first();

            //Check Number of Qty
            foreach ($request->returnFields as $product) {
                $selected_rod = DirectSalesDetailModel::where('direct_id', $selected_return->retail_id)
                    ->where('product_id', $product['product_id'])->first();

                $selected_detail = ReturnRetailDetailModel::with('returnBy')
                    ->whereHas('returnBy', function ($query) use ($selected_return) {
                        $query->where('retail_id', $selected_return->retail_id);
                    })->where('product_id', $product['product_id'])->get();

                $returned_qty = 0;
                if ($selected_detail == null) {
                    $returned_qty = 0;
                } else {
                    $last = count($selected_detail);
                    $i = 0;
                    foreach ($selected_detail as $detail) {
                        if (++$i != $last) {
                            $returned_qty = $returned_qty + $detail->qty;
                        }
                    }
                }
                // dd('detail: ' . $detail->qty . ', ' . $selected_sod->qty . ', ' . $returned_qty);
                // dd($product["qty"]);

                if ($product['qty'] > ($selected_rod->qty - $returned_qty)) {
                    return Redirect::back()->with('error', 'Edit Return Retail Order Fail! The number of items exceeds the order');
                }
            }

            $get_reason = $request->get('return_reason1');
            $old_reason = $selected_return->return_reason;
            if ($old_reason == $get_reason) {
                $selected_return->return_reason = $get_reason;
            } elseif ($get_reason == "Other") {
                $selected_return->return_reason = $request->get('return_reason');
            } else {
                $selected_return->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
            }

            //Save Return Input and Total and Change Stock
            $total = 0;

            foreach ($request->returnFields as $product) {
                $selected_rod = DirectSalesDetailModel::where('direct_id', $selected_return->retail_id)
                    ->where('product_id', $product['product_id'])->first();

                $product_exist = ReturnRetailDetailModel::where('return_id', $id)
                    ->where('product_id', $product['product_id'])->first();

                $price = 0;
                if ($product_exist != null) {
                    $old_qty = $product_exist->qty;
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();

                    $price = $product_exist->price;
                } else {
                    $product_exist = new ReturnRetailDetailModel();
                    $product_exist->return_id = $id;

                    //get Price
                    $product_exist->price = $selected_rod->price;

                    $product_exist->product_id = $product['product_id'];
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();

                    $price = $product_exist->price;
                }

                $keys = array_keys($product);
                $lastKey = end($keys);

                if (is_numeric($lastKey)) {
                    for ($i = 0; $i <= $lastKey; $i++) {
                        if (isset($product[$i]['dot'])) {

                            $dotExist = ReturnRetailCodeModel::where('return_detail_id', $product_exist->id)
                                ->where('dot', $product[$i]['dot'])
                                ->first();


                            if ($dotExist != null) {
                                $dotExist->qty = $product[$i]['qtyDot'];
                                $dotExist->save();
                            } else {
                                $dotExist = new ReturnRetailCodeModel();
                                $dotExist->return_detail_id = $product_exist->id;
                                $dotExist->dot = $product[$i]['dot'];
                                $dotExist->qty = $product[$i]['qtyDot'];
                                $dotExist->save();
                            }
                        }
                    }

                    //change dot stock
                    for ($i = 0; $i <= $lastKey; $i++) {
                        if (isset($product[$i]['dot'])) {
                            $getDot = TyreDotModel::where('id', $product[$i]['dot'])->first();
                            $getDot->qty += $product[$i]['qtyDot'];
                            $getDot->save();
                        }
                    }
                }

                //Count Total
                if ($price == 0 || $price == null) {
                    $products = ProductCostModel::where('id_product', $product['product_id'])->where('id_warehouse', $selected_retail->warehouse_id)->first();
                    $harga_double = $products->harga_jual;
                    $harga_ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float) $harga_double;
                    $price = (float) $harga_double + $harga_ppn;
                }

                $harga_diskon = (float) $price * ($selected_rod->discount / 100);
                $harga_final = (float) $price - $harga_diskon - $selected_rod->discount_rp;

                $total = $total + ($harga_final * $product['qty']);
            }

            $total_sub = $total;
            $selected_return->total = $total_sub;
            $selected_return->isapproved = 0;
            $selected_return->isreceived = 1;
            $saved = $selected_return->save();
            // dd($products_arr);
            $del = ReturnRetailDetailModel::where('return_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            $selected_retail = DirectSalesModel::where('id', $selected_return->retail_id)->first();

            
            $cust_name = '';
            if (is_numeric($selected_return->retailBy->cust_name)) {
                $cust_name = CustomerModel::where('id', $selected_return->retailBy->cust_name)->first()->name_cust;
            } else {
                $cust_name = $selected_return->retailBy->cust_name;
            }
            $message = 'Return Direct Sales ' . $selected_return->return_number . ' from ' . $cust_name . ' has been Received! Please do the approval';
            event(new LeaveApproval('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->job_id = 1069;
            $notif->save();

            DB::commit();
            return redirect('/return_retail/receiving')->with('success', 'Receiving Retail Return Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/return_retail/receiving')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function approve_retail(Request $request, $id)
    {
        // dd($request->all());
        // Validate Input

        $request->validate([
            "returnFields.*.product_id" => "required|numeric",
            "returnFields.*.qty" => "required|numeric",
            "return_reason1" => "required"
        ]);
        try {
            DB::beginTransaction();

            //Check Number of product
            if ($request->returnFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }

            //Check Duplicate
            $products_arr = [];
            $dot_arr = [];

            foreach ($request->returnFields as $check) {
                $keys = array_keys($check);
                $lastKey = end($keys);
                array_push($products_arr, $check['product_id']);
                if (is_numeric($lastKey)) {
                    for ($i = 0; $i <= $lastKey; $i++) {
                        if (isset($check[$i]['dot'])) {
                            array_push($dot_arr, $check[$i]['dot']);
                        }
                    }
                }
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));
            $duplicates_dot = array_unique(array_diff_assoc($dot_arr, array_unique($dot_arr)));

            if (!empty($duplicates)) {
                return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate product.');
            }
            if (!empty($duplicates_dot)) {
                return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate DOT.');
            }

            $selected_return = ReturnRetailModel::where('id', $id)->first();
            $selected_retail = DirectSalesModel::where('id', $selected_return->retail_id)->first();

            //Check Number of Qty
            foreach ($request->returnFields as $product) {
                $selected_rod = DirectSalesDetailModel::where('direct_id', $selected_return->retail_id)
                    ->where('product_id', $product['product_id'])->first();

                $selected_detail = ReturnRetailDetailModel::with('returnBy')
                    ->whereHas('returnBy', function ($query) use ($selected_return) {
                        $query->where('retail_id', $selected_return->retail_id);
                    })->where('product_id', $product['product_id'])->get();

                $returned_qty = 0;
                if ($selected_detail == null) {
                    $returned_qty = 0;
                } else {
                    $last = count($selected_detail);
                    $i = 0;
                    foreach ($selected_detail as $detail) {
                        if (++$i != $last) {
                            $returned_qty = $returned_qty + $detail->qty;
                        }
                    }
                }
                // dd('detail: ' . $detail->qty . ', ' . $selected_sod->qty . ', ' . $returned_qty);
                // dd($product["qty"]);

                if ($product['qty'] > ($selected_rod->qty - $returned_qty)) {
                    return Redirect::back()->with('error', 'Edit Return Retail Order Fail! The number of items exceeds the order');
                }
            }

            $get_reason = $request->get('return_reason1');
            $old_reason = $selected_return->return_reason;
            if ($old_reason == $get_reason) {
                $selected_return->return_reason = $get_reason;
            } elseif ($get_reason == "Other") {
                $selected_return->return_reason = $request->get('return_reason');
            } else {
                $selected_return->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
            }

            //Save Return Input and Total and Change Stock
            $total = 0;

            foreach ($request->returnFields as $product) {
                $selected_rod = DirectSalesDetailModel::where('direct_id', $selected_return->retail_id)
                    ->where('product_id', $product['product_id'])->first();

                $product_exist = ReturnRetailDetailModel::where('return_id', $id)
                    ->where('product_id', $product['product_id'])->first();

                $price = 0;
                if ($product_exist != null) {
                    $old_qty = $product_exist->qty;
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();

                    $price = $product_exist->price;
                } else {
                    $product_exist = new ReturnRetailDetailModel();
                    $product_exist->return_id = $id;

                    //get Price
                    $product_exist->price = $selected_rod->price;

                    $product_exist->product_id = $product['product_id'];
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();

                    $price = $product_exist->price;
                }

                $keys = array_keys($product);
                $lastKey = end($keys);

                if (is_numeric($lastKey)) {
                    for ($i = 0; $i <= $lastKey; $i++) {
                        if (isset($product[$i]['dot'])) {

                            $dotExist = ReturnRetailCodeModel::where('return_detail_id', $product_exist->id)
                                ->where('dot', $product[$i]['dot'])
                                ->first();


                            if ($dotExist != null) {
                                $dotExist->qty = $product[$i]['qtyDot'];
                                $dotExist->save();
                            } else {
                                $dotExist = new ReturnRetailCodeModel();
                                $dotExist->return_detail_id = $product_exist->id;
                                $dotExist->dot = $product[$i]['dot'];
                                $dotExist->qty = $product[$i]['qtyDot'];
                                $dotExist->save();
                            }
                        }
                    }

                    //change dot stock
                    for ($i = 0; $i <= $lastKey; $i++) {
                        if (isset($product[$i]['dot'])) {
                            $getDot = TyreDotModel::where('id', $product[$i]['dot'])->first();
                            $getDot->qty += $product[$i]['qtyDot'];
                            $getDot->save();
                        }
                    }
                }

                //Count Total
                if ($price == 0 || $price == null) {
                    $products = ProductCostModel::where('id_product', $product['product_id'])->where('id_warehouse', $selected_retail->warehouse_id)->first();
                    $harga_double = $products->harga_jual;
                    $harga_ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float) $harga_double;
                    $price = (float) $harga_double + $harga_ppn;
                }

                $harga_diskon = (float) $price * ($selected_rod->discount / 100);
                $harga_final = (float) $price - $harga_diskon - $selected_rod->discount_rp;

                $total = $total + ($harga_final * $product['qty']);
            }

            $total_sub = $total;
            $selected_return->total = $total_sub;
            $selected_return->return_date = date('Y-m-d', strtotime($request->return_date));
            $selected_return->isapproved = 1;
            $selected_return->isreceived = 1;
            $saved = $selected_return->save();
            // dd($products_arr);
            $del = ReturnRetailDetailModel::where('return_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            $selected_retail = DirectSalesModel::where('id', $selected_return->retail_id)->first();
            $journal = null;
            if ($selected_retail->isPaid == 0) {
                $journal = createJournal(
                    Carbon::now()->format('Y-m-d'),
                    'Retur Penjualan Direct Kredit.' . $selected_retail->order_number,
                    $selected_retail->warehouse_id
                );

                // ** Jika Jurnal Berhasil Disimpan ** //
                if ($journal != "" && $journal != null && $journal != false) {
                    // ** COA Return Penjualan ** //
                    createJournalDetail(
                        $journal,
                        '4-102',
                        $selected_return->return_number,
                        round($selected_retail->total_incl) / 1.11,
                        0
                    );
                    // ** COA PPn Keluaran ** //
                    createJournalDetail(
                        $journal,
                        '2-300',
                        $selected_return->return_number,
                        round($selected_retail->total_incl)  / 1.11 * (ValueAddedTaxModel::first()->ppn / 100),
                        0
                    );
                    // ** COA Piutang ** //
                    createJournalDetail(
                        $journal,
                        '1-200',
                        $selected_return->return_number,
                        0,
                        round($selected_retail->total_incl)
                    );
                }
            }

            //Check Paid
            $total_return =  ReturnRetailDetailModel::where('return_id', $selected_return->id)->sum('qty');
            $total_retails = DirectSalesDetailModel::where('direct_id', $selected_return->retail_id)->sum('qty');
            if ($total_return == $total_retails) {
                $selected_retail->isPaid = 1;
                $selected_retail->paid_date = date('Y-m-d');
                $selected_retail->save();
            }
            
            $selected_return_detail = ReturnRetailDetailModel::where('return_id', $selected_return->id)->get();
            $get_hpp_detail = JournalDetail::where('journal_id', $selected_retail->hpp_id)->where('debit', '>', 0)->first();

            $hpp = createJournal(
                Carbon::now()->format('Y-m-d'),
                'Persediaan Bertambah.' . $selected_return->return_number,
                $selected_retail->warehouse_id
            );

            if ($hpp != "" && $hpp != null && $hpp != false) {
                // $hpp_id = $hpp->id;
                $hpp_excl = 0;
                // foreach ($selected_return_detail as $hpp_c) {
                //     $getProduct = ProductModel::where('id', $hpp_c->product_id)->first();
                //     $hpp_excl = $hpp_excl + ($getProduct->hpp * $hpp_c->qty);
                // }

                // $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
                // $hpp_ppn = $hpp_excl * $current_ppn;
                // $hpp_incl = $hpp_excl + $hpp_ppn;


                createJournalDetail(
                    $hpp,
                    '1-401',
                    $selected_return->return_number,
                    $get_hpp_detail->debit,
                    0
                );


                createJournalDetail(
                    $hpp,
                    '6-000',
                    $selected_return->return_number,
                    0,
                    $get_hpp_detail->debit
                );
            }
            
            $selected_return->jurnal_id = $journal;
            $selected_return->hpp_id = $hpp;
            $selected_return->save();

            //Change Stock
            $returnDetail = ReturnRetailDetailModel::where('return_id', $selected_return->id)->get();
            foreach ($returnDetail as $value) {
                $getStock = StockModel::where('products_id', $value->product_id)
                    ->where('warehouses_id', $selected_retail->warehouse_id)
                    ->first();
                $old_stock = $getStock->stock;
                $getStock->stock = $old_stock + $value->qty;
                $getStock->save();
            }

            DB::commit();
            return redirect('/return_retail/approval')->with('success', 'Approve Retail Return Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/return_retail/approval')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function store_item_promotion(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            // Validate Input
            $request->validate([
                "returnFields.*.product_id" => "required|numeric",
                "returnFields.*.qty" => "required|numeric",
                "return_reason1" => "required"
            ]);

            if ($request->returnFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }

            // //Check Duplicate
            // $products_arr = [];

            // foreach ($request->returnFields as $check) {
            //     array_push($products_arr, $check['product_id']);
            // }
            // $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            // if (!empty($duplicates)) {
            //     return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate product.');
            // }

            $model = new ReturnItemPromotionModel();

            //create return number
            $selected_so = ItemPromotionTransactionModel::where('id', $request->get('so_id'))->first();

            // query cek kode warehouse/area sales orders
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $selected_so->id_warehouse)
                ->first();

            $lastRecord = ReturnItemPromotionModel::whereHas('transactionBy', function ($query) use ($selected_so) {
                $query->where('id_warehouse', $selected_so->id_warehouse);
            })->latest()->first();

            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->return_date)->format('m');
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
            // $id = intval(SalesOrderModel::where('warehouse_id', $request->warehouse_id)->max('id_sort')) + 1;
            $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $return_number = 'PRPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

            $model->return_number = $return_number;
            $model->id_transaction = $request->get('so_id');
            $model->return_date = Carbon::now()->format('Y-m-d');
            $model->created_by = Auth::user()->id;
            $model->invoice_created = $selected_so->created_by;
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
                $selected_sod = ItemPromotionTransactionDetailModel::where('id_transaction', $model->id_transaction)
                    ->where('id_item',  $item['product_id'])->where('price', $item['price'])->first();

                $detail = new ReturnItemPromotionDetailModel();
                $detail->return_id = $model->id;
                $detail->id_item = $item['product_id'];
                $detail->qty = $item['qty'];
                $detail->price = $selected_sod->price;

                //Check exceed order
                $selected_return = ReturnItemPromotionDetailModel::with('returnBy')
                    ->whereHas('returnBy', function ($query) use ($model) {
                        $query->where('id_transaction', $model->id_transaction);
                    })->where('id_item', $item['product_id'])->where('price', $item['price'])->get();
                //Get Total Returned Qty
                $returned_qty = 0;
                if ($selected_return == null) {
                    $returned_qty = 0;
                } else {
                    foreach ($selected_return as $return) {
                        $returned_qty = $returned_qty + $return->qty;
                    }
                }

                if ($detail->qty > ($selected_sod->qty - $returned_qty)) {

                    $previous_product = ReturnItemPromotionDetailModel::where('return_id', $model->id)->get();
                    if ($previous_product != null) {
                        $previous_product->each->delete();
                        $model->delete();
                    }
                    // dd($detail->qty . '>' . ($selected_sod->qty - $returned_qty));
                    return Redirect::back()->with('error', 'Return Order Fail! The number of items exceeds the order');
                }

                $detail->save();

                //Count Total
                $cost = $selected_sod->price;

                $total = $total + ($cost * $detail->qty);
            }
            //Ambil Total PPN
            $model->total = $total;
            $model->save();

            $returnDetail = ReturnItemPromotionDetailModel::where('return_id', $model->id)->get();

            //Change Stock

            foreach ($returnDetail as $value) {
                $getCostModel = ItemPromotionCostModel::where('item_id', $value->id_item)->where('warehouse_id', $selected_so->id_warehouse)
                    ->where('cost', $value->price)->first();
                if ($getCostModel != null) {
                    $getCostModel->qty = $getCostModel->qty + $value->qty;
                    $getCostModel->save();
                }

                $getStock = ItemPromotionStockModel::where('id_item', $value->id_item)
                    ->where('id_warehouse', $selected_so->id_warehouse)
                    ->first();
                $old_stock = $getStock->qty;
                $getStock->qty = $old_stock + $value->qty;
                $getStock->save();
            }

            DB::commit();
            return redirect('/material-promotion/return')->with('success', 'Return Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function store_purchase_item_promotion(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
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

            $model = new ReturnItemPromotionPurchaseModel();

            //create return number
            $selected_so = ItemPromotionPurchaseModel::where('id', $request->get('so_id'))->first();

            // query cek kode warehouse/area sales orders
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $selected_so->warehouse_id)
                ->first();

            $lastRecord = ReturnItemPromotionPurchaseModel::whereHas('purchaseBy', function ($query) use ($selected_so) {
                $query->where('warehouse_id', $selected_so->warehouse_id);
            })->latest()->first();

            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->return_date)->format('m');
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
            // $id = intval(SalesOrderModel::where('warehouse_id', $request->warehouse_id)->max('id_sort')) + 1;
            $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $return_number = 'ROPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;

            $model->return_number = $return_number;
            $model->purchase_id = $request->get('so_id');
            $model->return_date = Carbon::now()->format('Y-m-d');
            $model->created_by = Auth::user()->id;
            $model->invoice_created = $selected_so->created_by;
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
                $selected_sod = ItemPromotionPurchaseDetailModel::where('purchase_id', $model->purchase_id)
                    ->where('item_id',  $item['product_id'])->first();

                $detail = new ReturnItemPromotionPurchaseDetailModel();
                $detail->return_id = $model->id;
                $detail->item_id = $item['product_id'];
                $detail->qty = $item['qty'];
                $detail->price = $selected_sod->price;

                //Check exceed order
                $selected_return = ReturnItemPromotionPurchaseDetailModel::with('returnBy')
                    ->whereHas('returnBy', function ($query) use ($model) {
                        $query->where('purchase_id', $model->purchase_id);
                    })->where('item_id', $item['product_id'])->get();
                //Get Total Returned Qty
                $returned_qty = 0;
                if ($selected_return == null) {
                    $returned_qty = 0;
                } else {
                    foreach ($selected_return as $return) {
                        $returned_qty = $returned_qty + $return->qty;
                    }
                }

                if ($detail->qty > ($selected_sod->qty - $returned_qty)) {
                    $previous_product = ReturnItemPromotionPurchaseDetailModel::where('return_id', $model->id)->get();
                    if ($previous_product != null) {
                        $previous_product->each->delete();
                        $model->delete();
                    }
                    return Redirect::back()->with('error', 'Return Order Fail! The number of items exceeds the order');
                }

                $detail->save();

                //Count Total
                $cost = $selected_sod->price;

                $total = $total + ($cost * $detail->qty);
            }
            //Ambil Total PPN
            $model->total = $total;
            $model->save();

            $returnDetail = ReturnItemPromotionPurchaseDetailModel::where('return_id', $model->id)->get();

            //Change Stock

            foreach ($returnDetail as $value) {
                $getCostModel = ItemPromotionCostModel::where('item_id', $value->item_id)->where('warehouse_id', $selected_so->warehouse_id)
                    ->where('cost', $value->price)->first();
                if ($getCostModel != null) {
                    $getCostModel->qty = $getCostModel->qty - $value->qty;
                    $getCostModel->save();
                }

                $getStock = ItemPromotionStockModel::where('id_item', $value->item_id)
                    ->where('id_warehouse', $selected_so->warehouse_id)
                    ->first();
                $old_stock = $getStock->qty;
                $getStock->qty = $old_stock - $value->qty;
                $getStock->save();
            }

            DB::commit();
            return redirect('/material-promotion/purchase/return')->with('success', 'Return Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/material-promotion/purchase')->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function update_return(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
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
                    $last = count($selected_detail);
                    $i = 0;
                    foreach ($selected_detail as $detail) {
                        if (++$i != $last) {
                            $returned_qty = $returned_qty + $detail->qty;
                        }
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
            $customer = CustomerModel::where('id', $selected_return->salesOrderBy->customerBy->id)->first();
            $warehouse = WarehouseModel::where('id_area', $customer->area_cust_id)->where('type', 5)->first();
            foreach ($return_restore as $restore) {
                $stock = StockModel::where('warehouses_id', $warehouse->id)
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
                $price = $selected_sod->price;
                if($selected_sod->price == null || $selected_sod->price == 0){
                    $product = ProductModel::where('id', $detail->product_id)->first();
                    $ppn = (ValueAddedTaxModel::first()->ppn / 100) * str_replace(',', '.', $product->harga_jual_nonretail);
                    $price = str_replace(',', '.', $product->harga_jual_nonretail) + $ppn;
                }
                
                $diskon =  $selected_sod->discount / 100;
                $hargaDiskon = $price * $diskon;
                $hargaAfterDiskon = ($price -  $hargaDiskon) - $selected_sod->discount_rp;
                $total = $total + ($hargaAfterDiskon * $product['qty']);
            }
            $selected_return->total = $total;
            $saved = $selected_return->save();

            $del = ReturnDetailModel::where('return_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            $returnDetail = ReturnDetailModel::where('return_id', $selected_return->id)->get();
            $selected_so = SalesOrderModel::where('id', $selected_return->sales_order_id)->first();

            //Check Paid
            $total_return = ReturnDetailModel::where('return_id', $selected_return->id)->sum('qty');
            $total_sales = SalesOrderDetailModel::where('sales_orders_id', $selected_return->sales_order_id)->sum('qty');
            if ($total_return == $total_sales) {
                $selected_so->isPaid = 1;
                $selected_so->paid_date = date('Y-m-d');
                $selected_so->save();
            }

            //Change Stock
            $customer = CustomerModel::where('id', $selected_so->customers_id)->first();
            $warehouse = WarehouseModel::where('id_area', $customer->area_cust_id)->where('type', 5)->first();
            foreach ($returnDetail as $value) {
                $getStock = StockModel::where('products_id', $value->product_id)
                    ->where('warehouses_id', $warehouse->id)
                    ->first();
                $old_stock = $getStock->stock;
                $getStock->stock = $old_stock + $value->qty;
                $getStock->save();
            }
            $journal = JurnalModel::where('id', $selected_return->id_jurnal)->first();
            // dd($journal);
            if ($journal) {

                // akun retur penjualan
                $akun_hutang = JurnalDetailModel::where('expenses_id', $journal->id)->where('account_id', '600.05')->first();
                $akun_hutang->debit = $total_ex;
                $akun_hutang->credit = null;
                $akun_hutang->save();

                // akun pajak keluaran
                $akun_pajak = JurnalDetailModel::where('expenses_id', $journal->id)->where('account_id', '600.07')->first();
                $akun_pajak->debit = $ppn_total;
                $akun_pajak->credit = null;
                $akun_pajak->save();

                // akun return pembelian
                $akun_pembelian = JurnalDetailModel::where('expenses_id', $journal->id)->where('account_id', '100.01.04')->first();
                $akun_pembelian->debit = null;
                $akun_pembelian->credit = $selected_return->total;
                $akun_pembelian->save();
            }
            DB::commit();
            return redirect('/return')->with('success', 'Edit Return Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function updateTradeIn(Request $request, $id)
    {
        // dd($request->all());
        try {
            if ($request->returnFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }
            $products_arr = [];
            foreach ($request->returnFields as $check) {
                array_push($products_arr, $check['product_id']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));
            if (!empty($duplicates)) {
                return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate product.');
            }
            $selected_return = ReturnTradePurchaseModel::where('id', $id)->first();
            $selected_retail = TradeInModel::where('id', $selected_return->trade_in_id)->first();

            // cek jumlah qty
            foreach ($request->returnFields as $product) {
                $selected_rod = TradeInDetailModel::where('trade_in_id', $selected_return->trade_in_id)
                    ->where('product_trade_in', $product['product_id'])->first();

                $selected_detail = ReturnTradePurchaseDetailModel::with('returnBy')
                    ->whereHas('returnBy', function ($query) use ($selected_return) {
                        $query->where('trade_in_id', $selected_return->trade_in_id);
                    })->where('product_id', $product['product_id'])->get();
                $returned_qty = 0;
                if ($selected_detail == null) {
                    $returned_qty = 0;
                } else {
                    $last = count($selected_detail);
                    $i = 0;
                    foreach ($selected_detail as $detail) {
                        if (++$i != $last) {
                            $returned_qty = $returned_qty + $detail->qty;
                        }
                    }
                }
                if ($product['qty'] > ($selected_rod->qty - $returned_qty)) {
                    return Redirect::back()->with('error', 'Edit Return Retail Order Fail! The number of items exceeds the order');
                }
            }
            $get_reason = $request->get('return_reason1');
            $old_reason = $selected_return->return_reason;
            if ($get_reason == $old_reason) {
                $selected_return->return_reason = $get_reason;
            } elseif ($get_reason == "Other") {
                $selected_return->return_reason = $request->get('return_reason');
            } else {
                $selected_return->return_reason = $get_reason . ' by ' . $request->get('return_reason2');
            }

            $return_restore = ReturnTradePurchaseDetailModel::where('return_id', $id)->get();
            foreach ($return_restore as $restore) {
                $stock = SecondProductModel::where('warehouses_id', $selected_return->TradeInBy->warehouse_id)
                    ->where('products_id', $restore->product_id)->first();
                $stock->qty = $stock->qty + $restore->qty;
                $stock->save();
            }

            $total = 0;
            $deleteAll = ReturnTradePurchaseDetailModel::where('return_id', $selected_return->id)->delete();

            foreach ($request->returnFields as $product) {
                if ($deleteAll) {
                    $data = new ReturnTradePurchaseDetailModel();
                    $data->return_id = $selected_return->id;
                    $data->product_id = $product['product_id'];
                    $data->qty = $product['qty'];
                    $data->save();
                }
                // $harga = ProductCostSecondModel::where('id_product_trade_in', $product['product_id'])
                //     ->where('id_warehouse', $selected_retail->warehouse_id)
                //     ->first();
                $total = $total + ($data->price * $product['qty']);
            }
            $selected_return->total = $total;
            $selected_return->save();

            // update stok
            foreach ($request->returnFields as $restore) {
                $second_stock = SecondProductModel::where('warehouses_id', $selected_retail->warehouse_id)->where('products_id', $restore['product_id'])->first();
                $second_stock->qty = $second_stock->qty - $restore['qty'];
                $second_stock->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Edit Trade-In Purchase Return Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function updateTradeInSale(Request $request, $id)
    {
        // dd($request->all());
        try {
            if ($request->returnFields == null) {
                return Redirect::back()->with('error', 'There are no products!');
            }
            $products_arr = [];
            foreach ($request->returnFields as $check) {
                array_push($products_arr, $check['product_id']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));
            if (!empty($duplicates)) {
                return Redirect::back()->with('error', 'Return Order Fail! You enter duplicate product.');
            }
            $selected_return = ReturnTradeSaleModel::where('id', $id)->first();
            $selected_retail = SecondSaleModel::where('id', $selected_return->second_sale_id)->first();
            // dd($selected_return);
            // cek jumlah qty
            foreach ($request->returnFields as $product) {
                $selected_rod = SecondSaleDetailModel::where('second_sale_id', $selected_return->second_sale_id)
                    ->where('product_second_id', $product['product_id'])->first();

                $selected_detail = ReturnTradeSaleDetailModel::with('returnBy')
                    ->whereHas('returnBy', function ($query) use ($selected_return) {
                        $query->where('second_sale_id', $selected_return->second_sale_id);
                    })->where('product_id', $product['product_id'])->get();
                $returned_qty = 0;
                if ($selected_detail == null) {
                    $returned_qty = 0;
                } else {
                    $last = count($selected_detail);
                    $i = 0;
                    foreach ($selected_detail as $detail) {
                        if (++$i != $last) {
                            $returned_qty = $returned_qty + $detail->qty;
                        }
                    }
                }
                if ($product['qty'] > ($selected_rod->qty - $returned_qty)) {
                    return Redirect::back()->with('error', 'Edit Return Trade Sale Order Fail! The number of items exceeds the order');
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

            $return_restore = ReturnTradeSaleDetailModel::where('return_id', $id)->get();
            foreach ($return_restore as $restore) {
                $stock = SecondProductModel::where('warehouses_id', $selected_return->secondSaleBy->warehouse_id)
                    ->where('products_id', $restore->product_id)->first();
                $stock->qty = $stock->qty + $restore->qty;
                $stock->save();
            }

            $total = 0;
            $deleteAll = ReturnTradeSaleDetailModel::where('return_id', $selected_return->id)->delete();

            foreach ($request->returnFields as $product) {
                if ($deleteAll) {
                    $data = new ReturnTradeSaleDetailModel();
                    $data->return_id = $selected_return->id;
                    $data->product_id = $product['product_id'];
                    $data->qty = $product['qty'];
                    $data->save();
                }
                $harga = ProductCostSecondModel::where('id_product_trade_in', $product['product_id'])
                    ->where('id_warehouse', $selected_retail->warehouse_id)
                    ->first();
                $diskon_persen = ($selected_rod->discount / 100) * $selected_rod->price;
                $harga_diskon = $selected_rod->price - $diskon_persen;
                $harga_rupiah = ($harga_diskon - $selected_rod->discount_rp);
                $total = $total + ($harga_rupiah * $product['qty']);
            }
            $selected_return->total = $total;
            $selected_return->save();

            // update stok
            foreach ($request->returnFields as $restore) {
                $second_stock = SecondProductModel::where('warehouses_id', $selected_retail->warehouse_id)->where('products_id', $restore['product_id'])->first();
                $second_stock->qty = $second_stock->qty - $restore['qty'];
                $second_stock->save();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Edit Trade-In Sale Return Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function update_return_purchase(Request $request, $id)
    {
        try {
            DB::beginTransaction();
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

            $selected_return = ReturnPurchaseModel::where('id', $id)->first();

            //Check Number of Qty
            foreach ($request->returnFields as $product) {
                $selected_pod = PurchaseOrderDetailModel::where('purchase_order_id', $selected_return->purchase_order_id)
                    ->where('product_id', $product['product_id'])->first();

                $selected_detail = ReturnPurchaseDetailModel::with('returnBy')
                    ->whereHas('returnBy', function ($query) use ($selected_return) {
                        $query->where('purchase_order_id', $selected_return->purchase_order_id);
                    })->where('product_id', $product['product_id'])->get();

                $returned_qty = 0;
                if ($selected_detail == null) {
                    $returned_qty = 0;
                } else {
                    $last = count($selected_detail);
                    $i = 0;
                    foreach ($selected_detail as $detail) {
                        if (++$i != $last) {
                            $returned_qty = $returned_qty + $detail->qty;
                        }
                    }
                }

                if ($product['qty'] > ($selected_pod->qty - $returned_qty)) {
                    return Redirect::back()->with('error', 'Edit Return Purchase Order Fail! The number of items exceeds the order');
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

            if ($selected_return->purchaseOrderBy->isvalidated == 1) {
                //Restore stock to before changed
                $return_restore = ReturnPurchaseDetailModel::where('return_id', $id)->get();
                foreach ($return_restore as $restore) {
                    $stock = StockModel::where('warehouses_id', $selected_return->purchaseOrderBy->warehouse_id)
                        ->where('products_id', $restore->product_id)->first();
                    $stock->stock = $stock->stock + $restore->qty;
                    $stock->save();
                }
            }


            //Save Return Input and Total and Change Stock
            $total = 0;

            foreach ($request->returnFields as $product) {
                $selected_pod = PurchaseOrderDetailModel::where('purchase_order_id', $selected_return->purchase_order_id)
                    ->where('product_id', $product['product_id'])->first();

                $product_exist = ReturnPurchaseDetailModel::where('return_id', $id)
                    ->where('product_id', $product['product_id'])->first();
                    
                if($selected_pod->price == null || $selected_pod->price == 0){
                    $products = ProductModel::where('id', $product->product_id)->first();
                    $harga_double = Crypt::decryptString($products->harga_beli);
                    $harga_float = str_replace(',', '.', $harga_double);
                }else{
                    $harga_float = $selected_pod->price;
                }    

                if ($product_exist != null) {
                    $old_qty = $product_exist->qty;
                    $product_exist->qty = $product['qty'];
                    $product_exist->save();
                } else {
                    $new_product = new ReturnPurchaseDetailModel();
                    $new_product->return_id = $id;
                    $new_product->product_id = $product['product_id'];
                    $new_product->price = $harga_float;
                    $new_product->qty = $product['qty'];
                    $new_product->save();
                }
                //Count Total
                $harga_ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float) $harga_float;
                $harga_incl = (float) $harga_float + $harga_ppn;
                $harga_diskon = (float) $harga_incl * ($selected_pod->discount / 100);
                $harga_final = (float) $harga_incl - $harga_diskon;
                $total = $total + ($harga_final * $product['qty']);
            }

            $del = ReturnPurchaseDetailModel::where('return_id', $id)
                ->whereNotIn('product_id', $products_arr)->delete();

            $total_sub = $total;
            $selected_return->total = $total_sub;
            $saved = $selected_return->save();

            $selected_po = PurchaseOrderModel::where('id', $selected_return->purchase_order_id)->first();

            //Check Paid
            $total_return = ReturnPurchaseDetailModel::where('return_id', $selected_return->id)->sum('qty');
            $total_purchases = PurchaseOrderDetailModel::where('purchase_order_id', $selected_return->purchase_order_id)->sum('qty');
            if ($total_return == $total_purchases) {
                $selected_po->isPaid = 1;
                $selected_po->paid_date = date('Y-m-d');
                $selected_po->save();
            }

            if ($selected_return->purchaseOrderBy->isvalidated == 1) {
                //Change Stock
                $returnDetail = ReturnPurchaseDetailModel::where('return_id', $selected_return->id)->get();
                foreach ($returnDetail as $value) {
                    $getStock = StockModel::where('products_id', $value->product_id)
                        ->where('warehouses_id', $selected_po->warehouse_id)
                        ->first();
                    $old_stock = $getStock->stock;
                    $getStock->stock = $old_stock - $value->qty;
                    $getStock->save();
                }
            }
            DB::commit();
            return redirect('/return_purchase')->with('success', 'Edit Purchase Return Order Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/return_purchase')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function update_return_retail(Request $request, $id)
    {

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

        $selected_return = ReturnRetailModel::where('id', $id)->first();
        $selected_retail = DirectSalesModel::where('id', $selected_return->retail_id)->first();

        //Check Number of Qty
        foreach ($request->returnFields as $product) {
            $selected_rod = DirectSalesDetailModel::where('direct_id', $selected_return->retail_id)
                ->where('product_id', $product['product_id'])->first();

            $selected_detail = ReturnRetailDetailModel::with('returnBy')
                ->whereHas('returnBy', function ($query) use ($selected_return) {
                    $query->where('retail_id', $selected_return->retail_id);
                })->where('product_id', $product['product_id'])->get();

            $returned_qty = 0;
            if ($selected_detail == null) {
                $returned_qty = 0;
            } else {
                $last = count($selected_detail);
                $i = 0;
                foreach ($selected_detail as $detail) {
                    if (++$i != $last) {
                        $returned_qty = $returned_qty + $detail->qty;
                    }
                }
            }
            // dd('detail: ' . $detail->qty . ', ' . $selected_sod->qty . ', ' . $returned_qty);

            if ($product['qty'] > ($selected_rod->qty - $returned_qty)) {
                return Redirect::back()->with('error', 'Edit Return Retail Order Fail! The number of items exceeds the order');
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
        $return_restore = ReturnRetailDetailModel::where('return_id', $id)->get();
        foreach ($return_restore as $restore) {
            $stock = StockModel::where('warehouses_id', $selected_return->retailBy->warehouse_id)
                ->where('products_id', $restore->product_id)->first();
            $stock->stock = $stock->stock + $restore->qty;
            $stock->save();
        }

        //Save Return Input and Total and Change Stock
        $total = 0;

        foreach ($request->returnFields as $product) {
            $selected_rod = DirectSalesDetailModel::where('direct_id', $selected_return->retail_id)
                ->where('product_id', $product['product_id'])->first();

            $product_exist = ReturnRetailDetailModel::where('return_id', $id)
                ->where('product_id', $product['product_id'])->first();

            $price = 0;
            if ($product_exist != null) {
                $old_qty = $product_exist->qty;
                $product_exist->qty = $product['qty'];
                $product_exist->save();

                $price = $product_exist->price;
            } else {
                $new_product = new ReturnRetailDetailModel();
                $new_product->return_id = $id;

                //get Price
                $new_product->price = $selected_rod->price;

                $new_product->product_id = $product['product_id'];
                $new_product->qty = $product['qty'];
                $new_product->save();

                $price = $new_product->price;
            }

            //Count Total
            if ($price == 0 || $price == null) {
                $products = ProductCostModel::where('id_product', $product['product_id'])->where('id_warehouse', $selected_retail->warehouse_id)->first();
                $harga_double = $products->harga_jual;
                $harga_ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float) $harga_double;
                $price = (float) $harga_double + $harga_ppn;
            }

            $harga_diskon = (float) $price * ($selected_rod->discount / 100);
            $harga_final = (float) $price + $harga_diskon + $selected_rod->discount_rp;

            $total = $total + ($harga_final * $product['qty']);
        }

        $total_sub = $total;
        $selected_return->total = $total_sub;
        $saved = $selected_return->save();

        $del = ReturnRetailDetailModel::where('return_id', $id)
            ->whereNotIn('product_id', $products_arr)->delete();

        $selected_retail = DirectSalesModel::where('id', $selected_return->retail_id)->first();

        //Check Paid
        $total_return =  ReturnRetailDetailModel::where('return_id', $selected_return->id)->sum('qty');
        $total_retails = DirectSalesDetailModel::where('direct_id', $selected_return->retail_id)->sum('qty');
        if ($total_return == $total_retails) {
            $selected_retail->isPaid = 1;
            $selected_retail->paid_date = date('Y-m-d');
            $selected_retail->save();
        }

        //Change Stock
        $returnDetail = ReturnRetailDetailModel::where('return_id', $selected_return->id)->get();
        foreach ($returnDetail as $value) {
            $getStock = StockModel::where('products_id', $value->product_id)
                ->where('warehouses_id', $selected_retail->warehouse_id)
                ->first();
            $old_stock = $getStock->stock;
            $getStock->stock = $old_stock - $value->qty;
            $getStock->save();
        }
        
        //Change Journal
        $jurnal_detail = JurnalDetailModel::where('expenses_id', $selected_return->jurnal_id)->get();
        $total_ex = $total / 1.11;
        $total_ppn = ($total / 1.11) * (ValueAddedTaxModel::first()->ppn / 100);
        foreach ($jurnal_detail as $detail) {
            if ($detail->debit != null) {
                if ($detail->account_id == '600.05') {
                    $detail->debit = $total_ex;
                } else $detail->debit = $total_ppn;
            } else $detail->credit = $total;
        }

        return redirect('/return_retail')->with('success', 'Edit Retail Return Success!');
    }

    public function print_return($id)
    {
        $data = ReturnModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->salesOrderBy->warehouse_id)->first();
        $data->pdf_return = $data->return_number . '.pdf';
        $data->save();

        $ppn = ValueAddedTaxModel::first()->ppn / 100;

        $pdf = FacadePdf::loadView('returns.print_return', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $data->return_number . '.pdf');

        return $pdf->stream($data->pdf_return);
    }

    public function print_return_purchase($id)
    {
        $data = ReturnPurchaseModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->purchaseOrderBy->warehouse_id)->first();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data->pdf_return = $data->return_number . '.pdf';
        $data->save();

        $pdf = FacadePdf::loadView('returns.print_return_purchase', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $data->return_number . '.pdf');

        return $pdf->stream($data->pdf_return);
    }
    public function printTradeIn($id)
    {
        $data = ReturnTradePurchaseModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->TradeInBy->warehouse_id)->first();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data->pdf_return = $data->return_number . '.pdf';
        $data->save();

        $pdf = FacadePdf::loadView('returns.print_return_trade_purchase', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $data->return_number . '.pdf');

        return $pdf->stream($data->pdf_return);
    }
    public function printTradeInSale($id)
    {
        $data = ReturnTradeSaleModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->secondSaleBy->warehouse_id)->first();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data->pdf_return = $data->return_number . '.pdf';
        $data->save();

        $pdf = FacadePdf::loadView('returns.print_return_trade_sale', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $data->return_number . '.pdf');

        return $pdf->stream($data->pdf_return);
    }
    
    public function print_item_promotion($id)
    {
        $data = ReturnItemPromotionModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->transactionBy->id_warehouse)->first();
        $data->pdf_return = $data->return_number . '.pdf';
        $data->save();
        $pdf = FacadePdf::loadView('returns.print_item_promotion', compact('warehouse', 'data'))->setPaper('A5', 'landscape')->save('pdf/' . $data->return_number . '.pdf');
        return $pdf->stream($data->pdf_return);
    }

    public function print_return_retail($id)
    {
        $data = ReturnRetailModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->retailBy->warehouse_id)->first();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data->pdf_return = $data->return_number . '.pdf';
        $data->save();

        $pdf = FacadePdf::loadView('returns.print_return_retail', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $data->return_number . '.pdf');

        return $pdf->stream($data->pdf_return);
    }
}
