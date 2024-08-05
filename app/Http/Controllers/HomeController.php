<?php

namespace App\Http\Controllers;

use App\Models\AccuClaimModel;
use App\Models\AssetModel;
use App\Models\ClaimModel;
use App\Models\CustomerModel;
use App\Models\DirectSalesModel;
use App\Models\EmployeeModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderCreditModel;
use App\Models\PurchaseOrderModel;
use App\Models\ReturnModel;
use App\Models\ReturnPurchaseModel;
use App\Models\ReturnRetailModel;
use App\Models\SalesOrderModel;
use App\Models\SuppliersModel;
use App\Models\User;
use App\Models\VacationModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use Cron\MonthField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    //broadcast message with api whatsapp


    public function index(Request $request)
    {
        $title = 'Dashboard';

        //** check vacation days */
        $check_vacation = EmployeeModel::where('id', Auth::user()->employee_id)->first();
        $year_work_date = date('Y-m-d', strtotime("+365 day", strtotime($check_vacation->work_date)));
        $now = date('Y-m-d');

        // ! cek jika tanggal kerja sudah sama dengan hari ini maka tambahkan 12 hari cuti
        if ($year_work_date == $now) {
            $check_vacation->vacation = 12;
            $year_start_reset = date('Y-m-d', strtotime("+365 day", strtotime($year_work_date)));
            $check_vacation->vacation_reset = $year_start_reset;
            $check_vacation->save();
        }
        // ! cek jika tanggal reset sudah sama dengan hari ini maka tambahkan 12 hari cuti
        if ($check_vacation->vacation_reset == $now) {
            $check_vacation->vacation = 12;
            $year_reset = date('Y-m-d', strtotime("+365 day", strtotime($check_vacation->vacation_reset)));
            $check_vacation->vacation_reset = $year_reset;
            $check_vacation->save();
        }
        //** end */

        // $purchase_today = PurchaseOrderModel::where('isapprove', 1)
        //     ->where('isvalidated', 1)
        //     ->where(DB::raw('DATE(order_date)'), date('Y-m-d'))
        //     ->sum('total');
        // $purchase_month = PurchaseOrderModel::where('isapprove', 1)
        //     ->where('isvalidated', 1)
        //     ->where(DB::raw('MONTH(order_date)'), date('m'))
        //     ->sum('total');
        // $purchase_year = PurchaseOrderModel::where('isapprove', 1)
        //     ->where('isvalidated', 1)
        //     ->where(DB::raw('YEAR(order_date)'), date('Y'))
        //     ->sum('total');
        
        $purchase_overview = PurchaseOrderModel::with('supplierBy')
            ->where('isapprove', 1)
            ->where('isvalidated', 1)
            ->select('supplier_id')
            ->selectRaw('SUM(CASE WHEN DATE(order_date) = CURDATE() AND MONTH(CURDATE()) AND YEAR(order_date) THEN total/1.11 ELSE 0 END) as total_sum_today')
            ->selectRaw('SUM(CASE WHEN MONTH(order_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE()) THEN total/1.11 ELSE 0 END) as total_sum_month')
            ->selectRaw('SUM(CASE WHEN YEAR(order_date) = YEAR(CURDATE()) THEN total/1.11 ELSE 0 END) as total_sum_year')
            ->groupBy('supplier_id')
            ->get();
            
        $sales_indirect_overview = SalesOrderModel::with('warehouseBy')
        ->where('isapprove', 'approve')
        ->where('isverified', 1)     
        ->select('warehouse_id')
        ->selectRaw('SUM(CASE WHEN DATE(order_date) = CURDATE() AND MONTH(CURDATE()) AND YEAR(order_date) THEN total ELSE 0 END) as total_sum_today')
        ->selectRaw('SUM(CASE WHEN MONTH(order_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE()) THEN total ELSE 0 END) as total_sum_month')
        ->selectRaw('SUM(CASE WHEN YEAR(order_date) = YEAR(CURDATE()) THEN total ELSE 0 END) as total_sum_year')
        ->groupBy('warehouse_id')
        ->get();
        
        $sales_direct_overview =  DirectSalesModel::with('warehouseBy')
        ->where('isapproved', 1)
        ->where('isrejected', 0)     
        ->select('warehouse_id')
        ->selectRaw('SUM(CASE WHEN DATE(order_date) = CURDATE() AND MONTH(CURDATE()) AND YEAR(order_date) THEN total_excl ELSE 0 END) as total_sum_today')
        ->selectRaw('SUM(CASE WHEN MONTH(order_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE()) THEN total_excl ELSE 0 END) as total_sum_month')
        ->selectRaw('SUM(CASE WHEN YEAR(order_date) = YEAR(CURDATE()) THEN total_excl ELSE 0 END) as total_sum_year')
        ->groupBy('warehouse_id')
        ->get();
        
        $return_sales_indirect_overview = ReturnModel::join('sales_orders', 'returns.sales_order_id', '=', 'sales_orders.id')
        ->join('warehouses', 'sales_orders.warehouse_id', '=', 'warehouses.id')
        ->select('warehouses.warehouses')
        ->selectRaw('SUM(CASE WHEN DATE(return_date) = CURDATE() AND MONTH(CURDATE()) AND YEAR(order_date) THEN returns.total ELSE 0 END) as total_sum_today')
        ->selectRaw('SUM(CASE WHEN MONTH(return_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE()) THEN returns.total ELSE 0 END) as total_sum_month')
        ->selectRaw('SUM(CASE WHEN YEAR(return_date) = YEAR(CURDATE()) THEN returns.total ELSE 0 END) as total_sum_year')
        ->groupBy('sales_orders.warehouse_id')
        ->get();
        // dd($return_sales_indirect_overview);
        
        $return_sales_direct_overview =  ReturnRetailModel::join('direct_sales', 'return_retails.retail_id', '=', 'direct_sales.id')
        ->join('warehouses', 'direct_sales.warehouse_id', '=', 'warehouses.id')
        ->select('warehouses.warehouses')
        ->selectRaw('SUM(CASE WHEN DATE(return_date) = CURDATE() AND MONTH(CURDATE()) AND YEAR(order_date) THEN return_retails.total ELSE 0 END) as total_sum_today')
        ->selectRaw('SUM(CASE WHEN MONTH(return_date) = MONTH(CURDATE()) AND YEAR(order_date) = YEAR(CURDATE()) THEN return_retails.total ELSE 0 END) as total_sum_month')
        ->selectRaw('SUM(CASE WHEN YEAR(return_date) = YEAR(CURDATE()) THEN return_retails.total ELSE 0 END) as total_sum_year')
        ->groupBy('direct_sales.warehouse_id')
        ->get();
       

        //** this card for general information */
        // ! warehouse
        $count_warehouse = WarehouseModel::count();
        // ! produk
        $count_product = ProductModel::where('status', 1)->count();
        // ! customer
        $count_customer = CustomerModel::where('status', 1)->count();
        // ! user
        $count_user = User::where('status', 1)->count();
        // ! employee
        $count_employee = EmployeeModel::where('status',1)->count();
        // ! suppliers
        $count_suppliers = SuppliersModel::count();
        //** end  */

        //* this card for non retail sales */
        // ! sales today
        // $sales_today = SalesOrderModel::where('isapprove', 'approve')->where('isverified', 1)
        //     ->where(DB::raw('DATE(order_date)'), date('Y-m-d'))
        //     ->sum('total_after_ppn');
        // // ! sales month
        // $sales_month = SalesOrderModel::where('isapprove', 'approve')->where('isverified', 1)
        //     ->where(DB::raw('MONTH(order_date)'), date('m'))
        //     ->sum('total_after_ppn');
        // // ! sales year
        // $sales_year = SalesOrderModel::where('isapprove', 'approve')->where('isverified', 1)
        //     ->where(DB::raw('YEAR(order_date)'), date('Y'))
        //     ->sum('total_after_ppn');
        // //** end */

        // //** this card for retail sales */
        // // ! retail sales today
        // $retail_sales_today = DirectSalesModel::where(DB::raw('DATE(order_date)'), date('Y-m-d'))
        //     ->sum('total_incl');
        // // ! retail sales month
        // $retail_sales_month = DirectSalesModel::where(DB::raw('MONTH(order_date)'), date('m'))
        //     ->sum('total_incl');
        // // ! retail sales year
        // $retail_sales_year = DirectSalesModel::where(DB::raw('YEAR(order_date)'), date('Y'))
        //     ->sum('total_incl');
        //** end */

        //!  chart
        $record = SalesOrderModel::select(DB::raw('SUM(total_after_ppn) as total'), DB::raw('DAYNAME(order_date) as day_name'), DB::raw("DAY(order_date) as day"))
            ->where('order_number', 'like', '%IVPP%')
            ->where('isapprove', 'approve')->where('isverified', 1)
            ->groupBy('day', 'order_date')
            ->limit(7)
            ->orderBy('order_date', 'ASC')
            ->get();
        $record_profit = SalesOrderModel::select(DB::raw('SUM(profit) as total'), DB::raw('DAYNAME(order_date) as day_name'), DB::raw("DAY(order_date) as day"))
            ->where('order_number', 'like', '%IVPP%')
            ->where('isapprove', 'approve')->where('isverified', 1)
            ->where('isPaid', 1)
            ->groupBy('day', 'order_date')
            ->get();

        //Purchase Order Record
        $po_record = PurchaseOrderModel::select(DB::raw('SUM(total) as total'), DB::raw('MONTHNAME(order_date) as month_name'), DB::raw("MONTH(order_date) as month"))
            ->where('order_number', 'like', '%POPP%')
            ->where('isapprove', 1)
            ->where('isvalidated', 1)
            ->where(DB::raw('YEAR(order_date)'), date('Y'))
            ->groupBy('month', 'order_date')
            ->get();
        $data = [];
        $data_profit = [];
        $data_po = [];

        foreach ($record as $row) {
            $data['label'][] = $row->day_name;
            $data['data'][] = $row->total;
        }
        $data['chart_data'] = json_encode($data);

        foreach ($record_profit as $row) {
            $data_profit['label'][] = $row->day_name;
            $data_profit['data'][] = $row->total;
        }
        $data_profit['chart_profit'] = json_encode($data_profit);

        foreach ($po_record as $row) {
            $data_po['label_po'][] = $row->month_name;
            $data_po['data_po'][] = $row->total;
        }
        $data_po['chart_po'] = json_encode($data_po);

        $maintenance = AssetModel::where('status', 'un maintenance')->latest()->get();

        $datas = [
            'title' => $title,
            'count_warehouse' => $count_warehouse,
            'count_product' => $count_product,
            'count_customer' => $count_customer,
            'count_user' => $count_user,
            'count_employee' => $count_employee,
            'count_suppliers' => $count_suppliers,
            'sales_indirect_overview' => $sales_indirect_overview,
            'sales_direct_overview' => $sales_direct_overview,
            'return_sales_indirect_overview' => $return_sales_indirect_overview,
            'return_sales_direct_overview' => $return_sales_direct_overview,
            // 'sales_today' => $sales_today,
            // 'sales_month' => $sales_month,
            // 'sales_year' => $sales_year,
            // 'retail_sales_today' => $retail_sales_today,
            // 'retail_sales_month' => $retail_sales_month,
            // 'retail_sales_year' => $retail_sales_year,
            'chart_data' => $data['chart_data'],
            'chart_profit' => $data_profit['chart_profit'],
            'chart_po' => $data_po['chart_po'],
            'maintenance' => $maintenance,
            // 'purchase_today' => $purchase_today,
            // 'purchase_month' => $purchase_month,
            // 'purchase_year' => $purchase_year,
            'purchase_overview' => $purchase_overview,
        ];




        return view('home', $datas);
    }

    public function purchase(Request $request)
    {
        if ($request->ajax()) {


            $purchase = PurchaseOrderModel::with('supplierBy', 'createdPurchaseOrder', 'warehouseBy')
                ->where('isapprove', 1)
                ->whereMonth('order_date', now()->month)
                ->latest()
                ->get();

            return datatables()->of($purchase)
                ->editColumn('order_date', function ($data) {
                    return date('d F Y', strtotime($data->order_date));
                })
                ->editColumn('vendor', fn ($purchase) => $purchase->supplierBy->nama_supplier)
                ->editColumn('warehouse', function ($data) {
                    return $data->warehouseBy->warehouses;
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('receive', function ($data) {
                    if ($data->isvalidated == 0) {
                        return 'Not Received';
                    } else {
                        return 'Received';
                    }
                })
                ->editColumn('settlement', function ($data) {
                    if ($data->isPaid == 1) {
                        return '<b class="text-success fw-bold">Paid</b>';
                    } else return '<b class="text-danger fw-bold">Unpaid</b>';
                })
                ->rawColumns(['settlement'])
                ->make(true);
        }
    }
    public function return(Request $request)
    {
        $return = ReturnRetailModel::with('returnDetailsBy', 'createdBy', 'retailBy')
            ->whereMonth('return_date', now()->month)
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
}
