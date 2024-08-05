<?php

namespace App\Http\Controllers;

use App\Events\ApprovalMessage;
use DateTimeZone;
use Carbon\Carbon;
use DateTimeImmutable;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Events\SOMessage;
use App\Models\AccountSubTypeModel;
use App\Models\CustomerModel;
use App\Models\DeliveryHistoriesModel;
use App\Models\DiscountModel;
use App\Models\Finance\Coa;
use App\Models\Finance\Journal;
use App\Models\Finance\JournalDetail;
use App\Models\JurnalDetailModel;
use App\Models\JurnalModel;
use App\Models\NotificationsModel;
use App\Models\ReturnDetailModel;
use App\Models\ReturnModel;
use App\Models\SalesOrderCreditModel;
use App\Models\SalesOrderModel;
use Illuminate\Support\Facades\Auth;
use App\Models\SalesOrderDetailModel;
use App\Models\SalesOrderDotModel;
use App\Models\StockModel;
use App\Models\TyreDotModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Dompdf\Options;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use PDF;
use Illuminate\Support\Facades\Redirect;
use function App\Helpers\checkOverDue;
use function App\Helpers\checkOverDueByCustomer;
use function App\Helpers\checkOverPlafone;
use function App\Helpers\createJournal;
use function App\Helpers\createJournalDetail;
use function App\Helpers\changeSaldoTambah;
use function App\Helpers\changeSaldoKurang;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{

    public function ubahHarga()
    {
        $title = 'Ubah Harga';
        $data = DB::table('sales_order_details')
            ->join('products', 'sales_order_details.products_id', '=', 'products.id')
            ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
            ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
            ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
            ->join('sales_orders', 'sales_order_details.sales_orders_id', '=', 'sales_orders.id')
            ->select('*', 'sales_order_details.id AS id_detail')
            ->get();

        // dd($data[0]->salesorders);
        $datas = [
            'title' => $title,
            'data' => $data
        ];
        return view('uoms.ubah_harga', $datas);
    }
    public function ubahHarga_get(Request $request, $id)
    {
        $data_request = request()->week;
        $data = SalesOrderDetailModel::where('id', $id)->first();
        $data->price = $data_request;
        $data->save();
        return response()->json(true);
    }
    // Index => Halaman Create Sales Order
    public function index()
    {

        $title = 'Create Sales Order';
        $product = ProductModel::latest()->get();
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->oldest('warehouses')->get();
        if ($user_warehouse->count() > 1) {
            $customer = CustomerModel::where('status', 1)->oldest('name_cust')->get();
        } else {
            $customer = CustomerModel::where('status', 1)->whereIn('area_cust_id', array_column($user_warehouse->toArray(), 'id_area'))->oldest('name_cust')->get();
        }
        $data = compact('title', 'product', 'customer', 'user_warehouse');
        return view('sales_orders.index', $data);
    }

    // Store => Simpan Data Sales Order ke Database belum potong stock
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // validasi sebelum save
            $request->validate([
                "customer_id" => "required|numeric",
                "payment_method" => "required|numeric",
                "warehouse_id" => "required|numeric",
                "remark" => "required",
                "soFields.*.product_id" => "required|numeric",
                "soFields.*.qty" => "required|numeric"
            ]);

            // dd($customer->name_cust);
            foreach ($request->soFields as $qty) {
                $getStock = StockModel::where('products_id', $qty['product_id'])
                    ->where('warehouses_id', $request->warehouse_id)
                    ->first();

                if ($qty['qty'] > $getStock->stock) {
                    return redirect('/sales_order')->with('error', 'Add Sales Order Fail! The number of items exceeds the stock');
                }
            }
            $model = new SalesOrderModel();

            // buat order_number
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $request->warehouse_id)
                ->first();

            $lastRecord = SalesOrderModel::where('warehouse_id', $request->warehouse_id)->latest()->first();

            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->order_date)->format('m');
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
            $order_number = 'SOPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
            //

            // save sales orders
            $model->order_number = $order_number;
            $model->order_date = Carbon::now()->format('Y-m-d');
            $model->customers_id = $request->customer_id;
            $model->warehouse_id = $request->warehouse_id;
            $model->remark = $request->get('remark');
            $model->created_by = Auth::user()->id;
            $model->payment_method = $request->get('payment_method');
            $model->isdelivered = 0;

            // menyimpan due date berdasarkan payment method
            if ($model->payment_method == 3) {
                $top = CustomerModel::where('id', $model->customers_id)->first();
                $model->top = $top->due_date;
                $dt = new DateTimeImmutable(Carbon::now()->format('Y-m-d'), new DateTimeZone('Asia/Jakarta'));
                $dt = $dt->modify("+" . $model->top . " days");
                $model->duedate = $dt;
            } else {
                $model->top = 7;
                $dt = new DateTimeImmutable(Carbon::now()->format('Y-m-d'), new DateTimeZone('Asia/Jakarta'));
                $dt = $dt->modify("+" . $model->top . " days");
                $model->duedate = $dt;
            }
            $model->isapprove = 'progress';
            $model->isverified = 0;

            $saved = $model->save();
            // $model::creates();
            // save sales order details
            $total = 0;
            $message_duplicate = '';
            if ($saved) {
                foreach ($request->soFields as $key => $value) {
                    $data = new SalesOrderDetailModel();
                    $data->products_id = $value['product_id'];
                    $data->qty = $value['qty'];
                    if ($value['discount'] == NULL) {
                        $data->discount = 0;
                    } else {
                        $data->discount = $value['discount'];
                    }
                    $data->sales_orders_id = $model->id;
                    $data->created_by = Auth::user()->id;
                    $check_duplicate = SalesOrderDetailModel::where('sales_orders_id', $data->sales_orders_id)
                        ->where('products_id', $data->products_id)
                        ->count();
                    if ($check_duplicate > 0) {
                        $message_duplicate = "You enter duplication of products. Please recheck the SO you set.";
                        continue;
                    } else {
                        $harga = ProductModel::where('id', $data->products_id)->first();
                        $diskon = floatval($value['discount']) / 100;
                        $ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float)$harga->harga_jual_nonretail;
                        $ppn_cost = (float)$harga->harga_jual_nonretail + $ppn;
                        $hargaDiskon = (float) $ppn_cost * $diskon;
                        $hargaAfterDiskon = (float) $ppn_cost -  $hargaDiskon;
                        $total = (float) $total + ($hargaAfterDiskon * $data->qty);

                        $data->price = $ppn_cost;
                        $data->save();
                    }
                }
            }

            $model->ppn = round($total / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));
            $model->total = round($total / 1.11);
            $model->total_after_ppn = round($total);
            $saved = $model->save();

            if (empty($message_duplicate) && $saved) {
                $message = 'Indirect Sales ' . $model->order_number . ' from ' . $model->customerBy->name_cust . ' has been created! Please check';
                event(new SOMessage('From: ' . Auth::user()->name,  $message));
                $notif = new NotificationsModel();
                $notif->message = $message;
                $notif->status = 0;
                $notif->job_id = 43;
                $notif->save();

                // $request->replace([]); // Menghapus semua elemen dalam $request

                DB::commit();

                return redirect('/sales_order')->with('success', 'Create sales orders ' . $model->order_number . ' success');
            } elseif (!empty($message_duplicate) && $saved) {
                $message = 'Indirect Sales ' . $model->order_number . ' from ' . $model->customerBy->name_cust . ' has been created! Please check';
                event(new SOMessage('From: ' . Auth::user()->name,  $message));
                $notif = new NotificationsModel();
                $notif->message = $message;
                $notif->status = 0;
                $notif->job_id = 43;
                $notif->save();

                // $request->replace([]); // Menghapus semua elemen dalam $request

                DB::commit();

                return redirect('/sales_order')->with('info', 'Some of SO add maybe Success! ' . $message_duplicate);
            } else {
                // $request->replace([]); // Menghapus semua elemen dalam $request
                DB::commit();

                return redirect('/sales_order')->with('error', 'Add Sales Order Fail! Please make sure you have filled all the input');
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // preview sales order
    public function preview(Request $request)
    {
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->oldest('warehouses')->get();

        if ($request->ajax()) {

            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            // Filter invoice berdasarkan gudang dan tanggal yang diminta
            $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                ->where('isverified', 0)
                ->whereIn('warehouse_id', $userWarehouseIds)
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    return $query->whereBetween('order_date', [$fromDate, $request->to_date]);
                }, function ($query) {
                    // Add this condition to use today's date as default
                    $today = date('Y-m-d');
                    return empty($query->from_date) ? $query->whereDate('order_date', $today) : $query;
                })
                ->oldest('order_number')
                ->get();

            return datatables()->of($invoice)
                ->editColumn('top', function ($data) {
                    if ($data->top != null)
                        return $data->top . ' Days';
                    else return '-';
                })
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
                ->editColumn('ppn', function ($data) {
                    return number_format($data->ppn, 0, '.', ',');
                })
                ->editColumn('total_after_ppn', function ($data) {
                    return number_format($data->total_after_ppn, 0, '.', ',');
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, '.', ',');
                })
                ->editColumn('status_mail', function ($data) {
                    if ($data->status_mail == null) {
                        return 'Not Sent';
                    } else {
                        return 'Sent at ( '  . date('d F y H:i:s', strtotime($data->status_mail)) . ' )';
                    }
                })
                ->editColumn('isPaid', function ($data) {
                    if ($data->isPaid == 0) {
                        return 'Unpaid';
                    } else {
                        return 'Paid';
                    }
                })
                ->editColumn('order_date', function ($data) {
                    return date('d F Y', strtotime($data->order_date));
                })
                ->editColumn('duedate', function ($data) {
                    if ($data->duedate != null) {
                        return date('d/M/Y', strtotime($data->duedate));
                    } else {
                        return "-";
                    }
                })
                ->editColumn('customers_id', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->customerBy->code_cust . ' - ' . $SalesOrderModel->customerBy->name_cust;
                })
                ->editColumn('created_by', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->createdSalesOrder->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) {
                    $customer = CustomerModel::latest()->get();
                    $warehouses = WarehouseModel::latest()->get();
                    return view('recent_sales_order._option_preview', compact('invoice', 'customer', 'warehouses'))->render();
                })
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = [
            'title' => "Sales Order Data Preview",
            'ppn' => $ppn
        ];

        return view('recent_sales_order.preview', $data);
    }

    public function rejected_sales_order(Request $request)
    {
        $user_warehouse = WarehouseModel::whereIn('id', array_column(Auth::user()->userWarehouseBy->toArray(), 'warehouse_id'))->oldest('warehouses')->get();

        if ($request->ajax()) {

            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            // Filter invoice berdasarkan gudang dan tanggal yang diminta
            $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                ->where('isrejected', 1)
                ->whereIn('warehouse_id', $userWarehouseIds)
                // ->when($request->from_date, function ($query, $fromDate) use ($request) {
                //     return $query->whereBetween('order_date', [$fromDate, $request->to_date]);
                // }, function ($query) {
                //     // Add this condition to use today's date as default
                //     $today = date('Y-m-d');
                //     return empty($query->from_date) ? $query->whereDate('order_date', $today) : $query;
                // })
                ->oldest('order_number')
                ->get();

            return datatables()->of($invoice)

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
                ->editColumn('ppn', function ($data) {
                    return number_format($data->ppn, 0, '.', ',');
                })
                ->editColumn('total_after_ppn', function ($data) {
                    return number_format($data->total_after_ppn, 0, '.', ',');
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->total, 0, '.', ',');
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
                ->rawColumns(['order_number'])
                ->addIndexColumn()
                ->make(true);
        }
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = [
            'title' => "Rejected Sales ORder",
            'ppn' => $ppn
        ];

        return view('recent_sales_order.rejected', $data);
    }

    // View Verificate Sales order
    public function getRecentData()
    {

        $title = 'Sales Order Verification';

        // get sales no debt
        $dataSalesOrder = SalesOrderModel::with([
            'customerBy',
            'salesOrderDetailsBy.productSales.sub_types',
            'salesOrderDetailsBy.productSales.sub_materials'
        ])
            ->where('isverified', 0)
            ->where('isrejected', 0)
            ->where('isapprove', 'progress')
            ->oldest('order_number')
            ->get();

        // get sales with
        $dataSalesOrderReject = SalesOrderModel::with([
            'customerBy',
            'salesOrderDetailsBy.productSales.sub_types',
            'salesOrderDetailsBy.productSales.sub_materials'
        ])
            ->where('isapprove', 'reject')
            ->where('isrejected', 0)
            ->oldest('order_number')
            ->get();

        // checkOverDue();
        $customer = CustomerModel::where('status', 1)->latest()->get();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        return view('recent_sales_order.index', compact('title', 'dataSalesOrder', 'ppn', 'customer', 'dataSalesOrderReject'));
    }

    // delete sales order
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            if (!Gate::allows('level1')) {
                abort(403);
            }
            $modelSalesOrder = SalesOrderModel::where('id', $id)->first();
            $modelSalesOrder->salesOrderDetailsBy()->delete();
            $modelSalesOrder->delete();
            DB::commit();

            return redirect('/recent_sales_order')->with('error', 'Delete Data Sales Order Success');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


    // Verificate Sales Order
    public function verify(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            // Validate Input
            $request->validate([
                "customer_id" => "required|numeric",
                "payment_method" => "required|numeric",
                "order_date" => "required",
                "editProduct.*.products_id" => "required|numeric",
                "editProduct.*.qty" => "required|numeric",
                "editProduct.*.discount" => "required",
                "remark" => "required"
            ]);

            $model = SalesOrderModel::findOrFail($id);
            $model->customers_id = $request->customer_id;
            $model->payment_method = $request->payment_method;
            $model->remark = $request->remark;
            $old_date = $model->order_date;
            $new_date = date('Y-m-d', strtotime($request->order_date));
            
            $old_month_year = date('Y-m', strtotime($old_date));
            $new_month_year = date('Y-m', strtotime($new_date));
            
            if ($old_month_year != $new_month_year) {
               // query cek kode warehouse/area sales orders
                $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                    ->select('customer_areas.area_code', 'warehouses.id')
                    ->where('warehouses.id', $model->warehouse_id)
                    ->first();
                // $orderDate = date() Carbon::parse($request->order_date);
                $lastRecord = SalesOrderModel::where('warehouse_id', $model->warehouse_id)
                ->whereYear('order_date', '=', date('Y', strtotime($request->order_date)))
                ->whereMonth('order_date', '=', date('m', strtotime($request->order_date)))
                ->latest()->first();
    
                if ($lastRecord) {
                    $lastRecordMonth = Carbon::parse($lastRecord->order_date)->format('m');
                    $currentMonth = Carbon::now()->format('m');
    
                    // if ($lastRecordMonth != $currentMonth) {
                    //     // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                    //     $cust_number_id = 1;
                    //     $model->id_sort = $cust_number_id;
                    // } else {
                        // Jika masih dalam bulan yang sama, increment $cust_number_id
                        $cust_number_id = intval($lastRecord->id_sort) + 1;
                        $model->id_sort = $cust_number_id;
                    // }
                } else {
                    // Jika belum ada record sebelumnya, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $model->id_sort = $cust_number_id;
                }
                $length = 3;
                // $id = intval(SalesOrderModel::where('warehouse_id', $request->warehouse_id)->max('id_sort')) + 1;
                $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
                
                $year = $orderDate->format('Y');
                $month = $orderDate->format('m');
                $tahun = substr($year, -2);
                $order_number = 'SOPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
                $model->order_number = $order_number;
                //
            } 
            $model->order_date = $new_date;
            
            if ($request->payment_method == 3) {
                $top = CustomerModel::findOrFail($model->customers_id);
                $model->top = $top->due_date;
            } else {
                $model->top = 7;
            }
            $model->duedate = (new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta')))
                ->modify('+' . $model->top . ' days');
            $saved_temp = $model->save();

            //Check Duplicate
            $products_arr = [];
            foreach ($request->editProduct as $check) {
                array_push($products_arr, $check['products_id']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return redirect('/recent_sales_order')->with('error', "You enter duplicate products! Please check again!");
            }

            //Save SOD Input and Count total
            $total = 0;
            $totalDiskon = 0;
            // dd($request->editProduct);
            foreach ($request->editProduct as $product) {
                // dd($product);
                // $harga = ProductModel::where('id', $product['products_id'])->first();
                $ppn = (ValueAddedTaxModel::first()->ppn / 100) * floatval(str_replace(',', '', $product['price']));
                // dd($ppn);
                $ppn_cost = floatval(str_replace(',', '', $product['price']));
                // dd($ppn_cost);
                $product_exist = SalesOrderDetailModel::where('sales_orders_id', $id)
                    ->where('products_id', $product['products_id'])->first();
                if ($product_exist != null) {
                    $product_exist->qty = $product['qty'];
                    $product_exist->discount = $product['discount'];
                    $product_exist->discount_rp = $product['discount_rp'];
                    $product_exist->save();
                } else {
                    $new_product = new SalesOrderDetailModel();
                    $new_product->sales_orders_id = $id;
                    $new_product->products_id = $product['products_id'];
                    $new_product->price = $ppn_cost;
                    $new_product->qty = $product['qty'];
                    $new_product->discount = $product['discount'];
                    $new_product->discount_rp = $product['discount_rp'];
                    $new_product->created_by = Auth::user()->id;
                    $new_product->save();
                }

                $diskon = floatval($product['discount'])  / 100;
                $hargaDiskon = (float) $ppn_cost * $diskon;
                $hargaAfterDiskon = (float) ($ppn_cost -  $hargaDiskon) - $product['discount_rp'];
                $total = (float) $total + ($hargaAfterDiskon * $product['qty']);

            }

            //Delete product that not in SOD Input
            $del = SalesOrderDetailModel::where('sales_orders_id', $id)
                ->whereNotIn('products_id', $products_arr)->delete();

            //Count PPN and Total
            $model->ppn = round($total / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));
            $model->total = round($total / 1.11);
            $model->total_after_ppn = round($total);
            // $model->profit = $model->total_after_ppn - $harga_awal;

            //Verify
            $getCredential = CustomerModel::where('id', $model->customers_id)->firstOrFail();
            $model->isverified = 1;
            $model->verifiedBy = Auth::user()->id;
            if ($model->isapprove == 'reject') {
                $old_revision = $model->revision;
                $model->revision = $old_revision + 1;
                $model->isapprove = 'progress';
                $message = 'Sales Order ' . $model->order_number . ' has revised. Please check immediately!';
                event(new ApprovalMessage('From:' . Auth::user()->name, $message));
                $notif = new NotificationsModel();
                $notif->message = $message;
                $notif->status = 0;
                $notif->job_id = 44;
                $notif->save();
            } else {

                $checkoverplafone = checkOverPlafone($model->customers_id,  $model->total_after_ppn);
                $checkoverdue = checkOverDueByCustomer($model->customers_id);
                if (!$checkoverdue & !$checkoverplafone) {
                    $model->isapprove = 'approve';
                    $so_number = $model->order_number;
                    $so_number = str_replace('SOPP', 'IVPP', $so_number);
                    $model->order_number = $so_number;
                    //Potong Stock
                    $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $id)->get();

                    foreach ($selected_sod as $value) {
                        $getStock = StockModel::where('products_id', $value->products_id)
                            ->where('warehouses_id', $model->warehouse_id)
                            ->first();

                        $old_stock = $getStock->stock;
                        $getStock->stock = $old_stock - $value->qty;
                        if ($getStock->stock < 0) {
                            return Redirect::back()->with('error', 'Verification Fail! Not enough stock. Please re-confirm to the customer.');
                        } else {
                            $getStock->save();
                        }
                    }

                    //Update Last Transaction Customer
                    $selected_customer = CustomerModel::where('id', $model->customers_id)->first();
                    $selected_customer->last_transaction = $model->order_date;
                    $selected_customer->save();

                    //Update Delivery History
                    $delivery = new DeliveryHistoriesModel();
                    $delivery->order_id = $model->id;
                    $delivery->status = 'Packing';
                    $delivery->remark = 'Sedang melakukan pengemasan oleh Warehouse Crew';
                    $delivery->history_date = date('Y-m-d H:i:s');
                    $delivery->created_by = Auth::user()->id;
                    $delivery->save();
                    
                    //Create Journal
                    // akun untuk penjualan
                   
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Penjualan Indirect No.' . $model->order_number,
                        $model->warehouse_id
                    );
                    // ** Perubahan Saldo Piutang Usaha ** //
                    $get_coa_p_masukan =  Coa::where('coa_code', '1-200')->first()->id;
                    changeSaldoTambah($get_coa_p_masukan, $model->warehouse_id,  $model->total_after_ppn);

                    // ** Perubahan Saldo Pendapatan Penjualan ** //
                    $get_coa_persediaan =  Coa::where('coa_code', '4-100')->first()->id;
                    changeSaldoTambah($get_coa_persediaan, $model->warehouse_id,  $model->total);

                    // ** Perubahan Saldo PPN Keluaaran ** //
                    $get_coa_hutang_dagang =  Coa::where('coa_code', '2-300')->first()->id;
                    changeSaldoTambah($get_coa_hutang_dagang, $model->warehouse_id, $model->ppn);
                    if ($journal != "" && $journal != null && $journal != false) {
                        // akun piutang

                        createJournalDetail(
                            $journal,
                            '1-200',
                            $model->order_number,
                            $model->total_after_ppn,
                            0
                        );

                        // akun pendapatan
                      
                        createJournalDetail(
                            $journal,
                            '4-100',
                            $model->order_number,
                            0,
                            $model->total
                        );

                        // akun pajak masukan
                       
                        createJournalDetail(
                            $journal,
                            '2-300',
                            $model->order_number,
                            0,
                            $model->ppn
                        );
                    }

                    //akun untuk HPP
                    
                    $hpp = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'HPP Penjualan Indirect No.' . $model->order_number,
                        $model->warehouse_id
                    );
                    if ($hpp != "" && $hpp != null && $hpp != false) {
                        $hpp_excl = 0;
                        foreach ($request->editProduct as $hpp_c) {
                            $getProduct = ProductModel::where('id', $hpp_c['products_id'])->first();
                            $hpp_excl = $hpp_excl + ($getProduct->hpp * $hpp_c['qty']);
                        }

                        $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
                        $hpp_ppn = $hpp_excl * $current_ppn;
                        $hpp_incl = $hpp_excl + $hpp_ppn;
                        // akun HPP
                        
                        createJournalDetail(
                            $hpp,
                            '6-000',
                            $model->order_number,
                            $hpp_incl,
                            0
                        );

                        // akun pendapatan

                        createJournalDetail(
                            $hpp,
                            '1-401',
                            $model->order_number,
                            0,
                            $hpp_excl + $hpp_ppn
                        );

                        // akun pajak masukan
                       
                        // createJournalDetail(
                        //     $hpp,
                        //     '2-300',
                        //     $model->order_number,
                        //     0,
                        //     $hpp_ppn
                        // );
                        // // ** Perubahan Saldo Piutang Usaha ** //
                        // $get_coa_p_masukan =  Coa::where('coa_code', '6-000')->first()->id;
                        // changeSaldoTambah($get_coa_p_masukan, $model->warehouse_id,  $hpp_incl);

                        // // ** Perubahan Saldo Pendapatan Penjualan ** //
                        // $get_coa_persediaan =  Coa::where('coa_code', '1-401')->first()->id;
                        // changeSaldoKurang($get_coa_persediaan, $model->warehouse_id,  $hpp_excl);

                        // // ** Perubahan Saldo PPN Keluaaran ** //
                        // $get_coa_hutang_dagang =  Coa::where('coa_code', '2-300')->first()->id;
                        // changeSaldoKurang($get_coa_hutang_dagang, $model->warehouse_id, $hpp_ppn);
                    }

                    $model->id_jurnal = $journal;
                    $model->id_jurnal_hpp = $hpp;
                } else {
                    $message = 'Sales Order ' . $model->order_number . ' from ' . $model->customerBy->name_cust . ' is overdue or over plafond. Please review immediately!';
                    event(new ApprovalMessage('From:' . Auth::user()->name, $message));
                    $notif = new NotificationsModel();
                    $notif->message = $message;
                    $notif->status = 0;
                    $notif->job_id = 44;
                    $notif->save();
                }
            }

            $saved_model = $model->save();
            if ($saved_model == true) {
                $data = SalesOrderModel::where('order_number', $model->order_number)->first();
                $warehouse = WarehouseModel::where('id', $model->warehouse_id)->first();
                $ppn = ValueAddedTaxModel::first()->ppn / 100;



                if ($model->pdf_do != '') {
                    $pdf = FacadePdf::loadView('invoice.delivery_order', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_do . '_' . $getCredential->name_cust);
                }
                if ($model->pdf_invoice != '') {
                    $pdf = FacadePdf::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_invoice . '_' . $getCredential->name_cust);
                }




                DB::commit();
                return redirect('/recent_sales_order')->with('success', "Sales Order Verification Success");
            } else {
                DB::rollback();
                return redirect('/recent_sales_order')->with('error', "Sales Order Verification Fail! Please check again!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // View Approve Sales Order
    public function soNeedApproval()
    {
        $title = 'Sales Order Approval';
        $dataInvoice = SalesOrderModel::where('isapprove', 'progress')
            ->where('isverified', 1)
            ->where('isrejected', 0)
            ->latest('created_at')
            ->whereIn('warehouse_id', Auth::user()->userWarehouseBy->pluck('warehouse_id'))
            ->get();
        return view('need_approval.index', compact('title', 'dataInvoice'));
    }

    // Approve Sales Order
    public function approve($id)
    {
        try {
            DB::beginTransaction();
            $selected_so = SalesOrderModel::where('id', $id)->firstOrFail();
            //Potong Stock
            $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $selected_so->id)->get();
            foreach ($selected_sod as $value) {
                $getStock = StockModel::where('products_id', $value->products_id)
                    ->where('warehouses_id', $selected_so->warehouse_id)
                    ->first();

                $old_stock = $getStock->stock;
                $getStock->stock = $old_stock - $value->qty;
                if ($getStock->stock < 0) {
                    return Redirect::back()->with('error', 'Approval Fail! Not enough stock. Please re-confirm to the customer.');
                } else {
                    $getStock->save();
                }
            }
            // dd($hpp);

            //Update Last Transaction Customer
            $selected_customer = CustomerModel::where('id', $selected_so->customers_id)->first();
            $selected_customer->last_transaction = $selected_so->order_date;
            $selected_customer->save();

            $so_number = $selected_so->order_number;
            $so_number = str_replace(
                'SOPP',
                'IVPP',
                $so_number
            );
            $do = str_replace('SOPP', 'DOPP', $selected_so->order_number);
            $selected_so->pdf_invoice = $so_number . '_' . $selected_customer->name_cust . '.pdf';
            $selected_so->pdf_do = $do . '_' . $selected_customer->name_cust . '.pdf';
            $selected_so->order_number = $so_number;
            $selected_so->isapprove = 'approve';
            $selected_so->approvedBy = Auth::user()->id;
            $selected_so->isPaid = 0;
            $selected_so->save();
            $data = SalesOrderModel::where('order_number', $selected_so->order_number)->first();
            $warehouse = WarehouseModel::where(
                'id',
                $selected_so->warehouse_id
            )->first();
            $ppn = ValueAddedTaxModel::first()->ppn / 100;

            //Update Delivery History
            $delivery = new DeliveryHistoriesModel();
            $delivery->order_id = $id;
            $delivery->status = 'Packing';
            $delivery->remark = 'Sedang melakukan pengemasan oleh Warehouse Crew';
            $delivery->history_date = date('Y-m-d H:i:s');
            $delivery->created_by = Auth::user()->id;
            $delivery->save();
            
            $journal = createJournal(
                Carbon::now()->format('Y-m-d'),
                'Penjualan Indirect No.' . $selected_so->order_number,
                $selected_so->warehouse_id
            );
            // ** Perubahan Saldo Piutang Usaha ** //
            $get_coa_p_masukan =  Coa::where('coa_code', '1-200')->first()->id;
            changeSaldoTambah($get_coa_p_masukan, $selected_so->warehouse_id,  $selected_so->total_after_ppn);

            // ** Perubahan Saldo Pendapatan Penjualan ** //
            $get_coa_persediaan =  Coa::where('coa_code', '4-100')->first()->id;
            changeSaldoTambah($get_coa_persediaan, $selected_so->warehouse_id,  $selected_so->total);

            // ** Perubahan Saldo PPN Keluaaran ** //
            $get_coa_hutang_dagang =  Coa::where('coa_code', '2-300')->first()->id;
            changeSaldoTambah($get_coa_hutang_dagang, $selected_so->warehouse_id, $selected_so->ppn);
            if ($journal != "" && $journal != null && $journal != false) {
                // akun piutang
                
                createJournalDetail(
                    $journal,
                    '1-200',
                    $selected_so->order_number,
                    $selected_so->total_after_ppn,
                    0
                );

                // akun pendapatan

                createJournalDetail(
                    $journal,
                    '4-100',
                    $selected_so->order_number,
                    0,
                    $selected_so->total
                );

                // akun pajak masukan
                
                createJournalDetail(
                    $journal,
                    '2-300',
                    $selected_so->order_number,
                    0,
                    $selected_so->ppn
                );
            }

            //akun untuk HPP
         
            $hpp = createJournal(
                Carbon::now()->format('Y-m-d'),
                'HPP Penjualan Indirect No.' . $selected_so->order_number,
                $selected_so->warehouse_id
            );
            if ($hpp != "" && $hpp != null && $hpp != false) {
                $hpp_excl = 0;
                foreach ($selected_sod as $hpp_c) {
                    $getProduct = ProductModel::where('id', $hpp_c->products_id)->first();
                    $hpp_excl = $hpp_excl + ($getProduct->hpp * $hpp_c->qty);
                }

                $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
                $hpp_ppn = $hpp_excl * $current_ppn;
                $hpp_incl = $hpp_excl + $hpp_ppn;
                // akun HPP
                
                createJournalDetail(
                    $hpp,
                    '6-000',
                    $selected_so->order_number,
                    $hpp_incl,
                    0
                );

                // akun pendapatan

                createJournalDetail(
                    $hpp,
                    '1-401',
                    $selected_so->order_number,
                    0,
                    $hpp_excl + $hpp_ppn
                );

                // akun pajak masukan
              
                // createJournalDetail(
                //     $hpp,
                //     '2-300',
                //     $selected_so->order_number,
                //     0,
                //     $hpp_ppn
                // );

                // ** Perubahan Saldo Piutang Usaha ** //
                // $get_coa_p_masukan =  Coa::where('coa_code', '6-000')->first()->id;
                // changeSaldoTambah($get_coa_p_masukan, $selected_so->warehouse_id,  $hpp_incl);

                // // ** Perubahan Saldo Pendapatan Penjualan ** //
                // $get_coa_persediaan =  Coa::where('coa_code', '1-401')->first()->id;
                // changeSaldoKurang($get_coa_persediaan, $selected_so->warehouse_id,  $hpp_excl);

                // // ** Perubahan Saldo PPN Keluaaran ** //
                // $get_coa_hutang_dagang =  Coa::where('coa_code', '2-300')->first()->id;
                // changeSaldoKurang($get_coa_hutang_dagang, $selected_so->warehouse_id, $hpp_ppn);
            }

            $selected_so->id_jurnal = $journal;
            $selected_so->id_jurnal_hpp = $hpp;
            $selected_so->save();

            if ($selected_so->pdf_do != '') {
                $pdf = FacadePdf::loadView('invoice.delivery_order', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $selected_so->pdf_do);
            }
            if ($selected_so->pdf_invoice != '') {

                $pdf = FacadePdf::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $selected_so->pdf_invoice);
            }

            DB::commit();
            return redirect()->back()->with('success', "Sales Order Approval Success");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // edit sales order
    public function editSuperadmin(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $model = SalesOrderModel::where('id', $id)->firstOrFail();
            $customer_id = $request->get('customer_id');
            $model->customers_id = $customer_id;
            $model->payment_method = $request->get('payment_method');
            $model->remark = $request->get('remark');
            if ($request->get('payment_method') == 3) {
                $top = CustomerModel::where('id', $model->customers_id)->first();
                $model->top = $top->due_date;
                $dt = new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta'));
                $dt = $dt->modify("+" . $model->top . " days");
                $model->duedate = $dt;
            } else {
                $model->top = 7;
                $dt = new DateTimeImmutable($model->order_date, new DateTimeZone('Asia/Jakarta'));
                $dt = $dt->modify("+" . $model->top . " days");
                $model->duedate = $dt;
            }
            $saved_temp = $model->save();

            //Check Duplicate
            $products_arr = [];
            foreach ($request->editProduct as $check) {
                array_push($products_arr, $check['products_id']);
            }
            $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

            if (!empty($duplicates)) {
                return redirect('/invoice')->with('error', "You enter duplicate products! Please check again!");
            }

            if ($model->isapprove == 'approve') {
                //Restore data to before changed
                $po_restore = SalesOrderDetailModel::where('sales_orders_id', $id)->get();

                foreach ($po_restore as $restore) {
                    $stock = StockModel::where('warehouses_id', $model->warehouse_id)
                        ->where('products_id', $restore->products_id)->first();
                    $stock->stock = $stock->stock + $restore->qty;
                    $stock->save();
                }
            }
            //Save SOD Input and Count total
            $total = 0;
            foreach ($request->editProduct as $product) {
                $product_exist = SalesOrderDetailModel::where('sales_orders_id', $id)
                    ->where('products_id', $product['products_id'])->first();

                $harga = ProductModel::where('id', $product['products_id'])->first();
                $ppn = (ValueAddedTaxModel::first()->ppn / 100) * floatval((str_replace(',', '.', $harga->harga_jual_nonretail)));
                $ppn_cost = floatval(str_replace(',', '.', $harga->harga_jual_nonretail)) + $ppn;

                if ($product_exist != null) {
                    $product_exist->qty = $product['qty'];

                    //! cek discount null
                    if ($product['discount'] == '') {
                        $product_exist->discount = 0;
                    }
                    $product_exist->discount = $product['discount'];

                    //! cek discount_rp null
                    if ($product['discount_rp'] == '') {
                        $product_exist->discount_rp = 0;
                    } else {
                        $product_exist->discount_rp = $product['discount_rp'];
                    }

                    $product_exist->save();
                } else {
                    $new_product = new SalesOrderDetailModel();
                    $new_product->sales_orders_id = $id;
                    $new_product->products_id = $product['products_id'];
                    $new_product->price = $ppn_cost;
                    $new_product->qty = $product['qty'];

                    //! cek discount null
                    if ($product['discount'] == '') {
                        $product_exist->discount = 0;
                    }
                    $new_product->discount = $product['discount'];
                    //! cek discount_rp null
                    if ($product['discount_rp'] == '') {
                        $product['discount_rp'] = 0;
                    }
                    $new_product->discount_rp = $product['discount_rp'];
                    $new_product->created_by = Auth::user()->id;
                    $new_product->save();
                }


                $diskon = floatval(str_replace(',', '.', $product['discount'])) / 100;
                $hargaDiskon = (float) $ppn_cost * $diskon;
                $hargaAfterDiskon = (float) $ppn_cost -  $hargaDiskon - $product['discount_rp'];
                $total = (float) $total + ($hargaAfterDiskon * $product['qty']);

                $harga_awal = str_replace(
                    ',',
                    '.',
                    Crypt::decryptString($harga->harga_beli)
                ) * $product['qty'];

                
            }

            

            //Delete product that not in SOD Input
            $del = SalesOrderDetailModel::where('sales_orders_id', $id)
                ->whereNotIn('products_id', $products_arr)->delete();

            //Count PPN and Total
            $model->ppn = round($total / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));
            $model->total = round($total / 1.11);
            $model->total_after_ppn = round($total);
            // $model->profit = $model->total_after_ppn - $harga_awal;

            //Verify

            //Potong Stock
            $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $id)->get();
            foreach ($selected_sod as $value) {

                $getStock = StockModel::where('products_id', $value->products_id)
                    ->where('warehouses_id', $model->warehouse_id)
                    ->first();

                $old_stock = $getStock->stock;
                $getStock->stock = $old_stock - $value->qty;
                if ($getStock->stock < 0) {
                    DB::rollback();
                    return Redirect::back()->with('error', 'Verification Fail! Not enough stock. Please re-confirm to the customer.');
                } else {
                    $getStock->save();
                }

                $checkoverplafone = checkOverPlafone($model->customers_id);
                $checkoverdue = checkOverDueByCustomer($model->customers_id);
            }

            $saved_model = $model->save();
            if ($saved_model == true) {
                $ppn = ValueAddedTaxModel::first()->ppn / 100;
                $data = SalesOrderModel::where('order_number', $model->order_number)->first();
                $customer = CustomerModel::where('id', $data->customers_id)->first();
                $warehouse = WarehouseModel::where('id', $model->warehouse_id)->first();
                $ppn = ValueAddedTaxModel::first()->ppn / 100;

                if ($model->pdf_do != '') {
                    $pdf = FacadePdf::loadView('invoice.delivery_order', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_do);
                }
                if ($model->pdf_invoice != '') {
                    $pdf = FacadePdf::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data', 'ppn'))->setPaper('A5', 'landscape')->save('pdf/' . $model->pdf_invoice);
                }

                // akun untuk penjualan
                $journal = Journal::where('id', $model->id_jurnal)->first();
                // dd($journal);
                if ($journal) {
                    // akun piutang
                    $akun_hutang = JournalDetail::where('journal_id', $journal->id)->where('coa_code', '1-200')->first();
                    $akun_hutang->debit = $model->total_after_ppn;
                    $akun_hutang->credit = 0;
                    $akun_hutang->save();

                    // akun pendapatan
                    $akun_pembelian = JournalDetail::where('journal_id', $journal->id)->where('coa_code', '4-100')->first();
                    $akun_pembelian->debit = 0;
                    $akun_pembelian->credit =  $model->total;
                    $akun_pembelian->save();

                    // akun pajak masukan
                    $akun_pajak = JournalDetail::where('journal_id', $journal->id)->where('coa_code', '2-300')->first();
                    $akun_pajak->debit = 0;
                    $akun_pajak->credit =  $model->ppn;
                    $akun_pajak->save();
                }

                $hpp_excl = 0;
                foreach ($request->editProduct as $value) {
                    $getProduct = ProductModel::where('id', $value['products_id'])->first();
                    $hpp_excl = $hpp_excl + ($getProduct->hpp * $value['qty']);
                }

                $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
                $hpp_ppn = $hpp_excl * $current_ppn;
                $hpp_incl = $hpp_excl + $hpp_ppn;
                //akun untuk HPP
                $journal_hpp = Journal::where('id', $model->id_jurnal_hpp)->first();
                if ($journal_hpp) {

                    // akun HPP
                    $akun_hutang = JournalDetail::where('journal_id', $journal_hpp->id)->where('coa_code', '6-000')->first();
                    $akun_hutang->debit = $hpp_incl;
                    $akun_hutang->credit = 0;
                    $akun_hutang->save();

                    // akun pendapatan
                    $akun_pembelian = JournalDetail::where('journal_id', $journal_hpp->id)->where('coa_code', '1-401')->first();
                    $akun_pembelian->debit = 0;
                    $akun_pembelian->credit =  $hpp_excl + $hpp_ppn;
                    $akun_pembelian->save();

                    // akun pajak masukan
                    // $akun_pajak = JournalDetail::where('journal_id', $journal_hpp->id)->where('coa_code', '2-300')->first();
                    // $akun_pajak->debit = 0;
                    // $akun_pajak->credit = $hpp_ppn;
                    // $akun_pajak->save();
                }

                DB::commit();

                return redirect('/invoice')->with('info', "Invoice success update !");
            } else {
                DB::rollback();
                return redirect('/invoice')->with('error', "Invoice update Fail! Please check again!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    // Delete Indirect Invoice
    public function deleteInvoice($id)
    {
        try {
            DB::beginTransaction();

            $sales_order = SalesOrderModel::find($id);
            
            //Delete Journal
            $jurnal_detail = JournalDetail::where('journal_id', $sales_order->id_jurnal)->get();
            foreach ($jurnal_detail as $key => $detail) {
                $detail->delete();
            }

            //Delete HPP
            $hpp_detail = JournalDetail::where('journal_id', $sales_order->id_jurnal_hpp)->get();
            foreach ($hpp_detail as $key => $detail) {
                $detail->delete();
            }

            // restore stock
            $sales_order_detail = SalesOrderDetailModel::where('sales_orders_id', $id)->get();

            foreach ($sales_order_detail as $key => $value) {
                $stock = StockModel::where('products_id', $value->products_id)->where('warehouses_id', $sales_order->warehouse_id)->first();
                $stock->stock = $stock->stock + $value->qty;


                $sales_order_dot = SalesOrderDotModel::where('sales_order_detail_id', $value->id)->first();
                if($sales_order_dot){
                    $select_sod = SalesOrderDetailModel::where('id', $sales_order_dot->sales_order_detail_id)->first();
                    $id_product = salesOrderDetailModel::where('id', $sales_order_dot->sales_order_detail_id)->first()->products_id;
                    $id_warehouse = $select_sod->salesorders->warehouse_id;
                    $stock_dot = TyreDotModel::where('id_product', $id_product)->where('id_warehouse', $id_warehouse)->first();
                    $stock_dot->qty = $stock_dot->qty + $sales_order_dot->qty;
    
                    // action
                    $stock_dot->save();
                    $sales_order_dot->delete();
                }
                
                $stock->save();
                $value->delete();
            }
            
            //Delete Settlement
            $sales_order_credit = SalesOrderCreditModel::where('sales_order_id', $id)->get();
            if ($sales_order_credit) {
                foreach ($sales_order_credit as $key => $value) {
                    //Delete Journal
                    $get_credit_journal = Journal::where('id', $value->journal_id)->first();
                    if ($get_credit_journal) {
                        foreach ($get_credit_journal->journal_detail as $key => $credit) {
                            $credit->delete();
                        }
                        $get_credit_journal->delete();
                    }

                    $value->delete();
                }
            }

            $sales_order->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Indirect Invoice ' . $sales_order->order_number . ' has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/invoice')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }


    // print cash receipt
    public function printCashReceipt($id)
    {
        $data = SalesOrderModel::find($id);
        $credit = SalesOrderCreditModel::where('sales_order_id', $id)->first();
        $datas = [
            'data' => $data,
            'credit' => $credit
        ];
        $pdf = FacadePdf::loadView('invoice.cash_receipt', $datas);
        $pdf->setPaper('a4');
        return $pdf->stream('cash_receipt.pdf');
    }
    // print invoice
    public function printInoiceWithPpn($id)
    {
        $data = SalesOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
        $data->pdf_invoice = $data->order_number . '_' . $data->customerBy->name_cust . '.pdf';
        $data->save();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $pdf = FacadePdf::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data', 'ppn'))->save('pdf/' . $data->order_number  . '_' . $data->customerBy->name_cust . '.pdf');
        return $pdf->stream($data->pdf_invoice);
    }


    //print delivery order
    public function printDeliveryOrder($id)
    {

        $data = SalesOrderModel::find($id);
        $so_number = str_replace('IVPP', 'DOPP', $data->order_number);
        $data->pdf_do = $so_number . '_' . $data->customerBy->name_cust . '.pdf';
        $data->save();
        $warehouse = WarehouseModel::where('id', $data->warehouse_id)->first();
        $pdf = FacadePdf::loadView('invoice.delivery_order', compact('warehouse', 'data'))->save('pdf/' . $so_number . '_' . $data->customerBy->name_cust . '.pdf');
        return $pdf->stream($data->pdf_do);
    }

    // getRecentData() : READ DATA RECENT SALES ORDERS ADMIN & SALES ADMIN


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //  store() : SIMPAN DATA CREATE SALES ORDERS


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
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
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getInvoiceData(Request $request)
    {
        if ($request->ajax()) {
            // Ambil daftar gudang yang diizinkan oleh pengguna saat ini
            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            // Filter invoice berdasarkan gudang dan tanggal yang diminta
            $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                ->where('isverified', 1)
                ->where('isrejected', 0)
                ->where('isapprove', 'approve')
                ->whereIn('warehouse_id', $userWarehouseIds)
                ->when($request->from_date, function ($query, $fromDate) use ($request) {
                    return $query->whereBetween('order_date', [$fromDate, $request->to_date]);
                }, function ($query) use ($request) {
                        if($request->filter == "this_month"){
                            // Mendapatkan tanggal awal bulan ini
                            $firstDayOfMonth = date("Y-m-01");
                            
                            // Mendapatkan tanggal akhir bulan ini
                            $lastDayOfMonth = date("Y-m-t");
                            
                            return empty($query->from_date) ? $query->whereBetween('order_date', [$firstDayOfMonth, $lastDayOfMonth]) : $query;
                        }else{
                            $today = date('Y-m-d');
                            //  dd("hey");
                            return empty($query->from_date) ? $query->whereDate('order_date', $today) : $query;
                        }
                         
                })
                ->latest()
                ->get();


            return datatables()->of($invoice)
                ->editColumn('top', fn ($invoice) => $invoice->top ? $invoice->top . ' Days' : '-')
                ->editColumn('payment_method', fn ($invoice) => $invoice->payment_method === 1 ? 'COD' : ($invoice->payment_method === 2 ? 'CBD' : 'Credit'))
                ->editColumn('order_number', fn ($invoice) => '<strong>' . $invoice->order_number . '</strong>')
                ->editColumn('ppn', fn ($invoice) => number_format($invoice->ppn, 0, '.', ','))
                ->editColumn('total_after_ppn', fn ($invoice) => number_format($invoice->total_after_ppn, 0, '.', ','))
                ->editColumn('total', fn ($invoice) => number_format($invoice->total, 0, '.', ','))
                ->editColumn('status_mail', fn ($invoice) => $invoice->status_mail ? 'Sent at (' . date('d F Y H:i:s', strtotime($invoice->status_mail)) . ')' : 'Not Sent')
                ->editColumn('isPaid', fn ($invoice) => $invoice->isPaid ? '<b class="text-success">Paid</b>' : '<b class="text-danger">Unpaid</b>')
                ->editColumn('order_date', fn ($invoice) => date('d F Y', strtotime($invoice->order_date)))
                ->editColumn('duedate', fn ($invoice) => $invoice->duedate ? date('d/M/Y', strtotime($invoice->duedate)) : '-')
                ->editColumn('customers_id', fn ($invoice) => $invoice->customerBy->code_cust . ' - ' . $invoice->customerBy->name_cust)
                ->editColumn('created_by', fn ($invoice) => $invoice->createdSalesOrder->name)
                ->addIndexColumn()
                ->addColumn('action', fn ($invoice) => view('invoice._option', ['invoice' => $invoice, 'customer' => CustomerModel::latest()->get(), 'warehouses' => WarehouseModel::latest()->get(), 'ppn' => ValueAddedTaxModel::first()->ppn / 100])->render())
                ->rawColumns(['isPaid', 'order_number', 'action'])
                ->make(true);
        }
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = [
            'title' => "Invoicing Data",
            'ppn' => $ppn
        ];

        return view('invoice.index', $data);
    }



    public function reject($id)
    {
        try {
            DB::beginTransaction();
            $selected_so = SalesOrderModel::where('id', $id)->firstOrFail();
            $selected_so->isapprove = 'reject';
            $selected_so->isverified = 0;
            $selected_so->isPaid = 0;
            $selected_so->save();

            DB::commit();
            return redirect('/need_approval')->with('info', "Sales Order " . $selected_so->order_number . " Reject ");
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function reject_from_verification(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $selected_so = SalesOrderModel::where('id', $id)->firstOrFail();
            $selected_so->isrejected = 1;
            $selected_so->reject_reason = $request->reason;
            $selected_so->isverified = 0;
            $selected_so->isPaid = 0;
            $selected_so->save();

            DB::commit();
            return redirect('/recent_sales_order')->with('error', 'Reject Sales Order Success');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            throw $e;
        }
    }

    public function updatePaid(Request $request, $id)
    {
        // dd('sedang di-fix');
        try {
            DB::beginTransaction();
            // $request->validate([
            //     "amount_method" => "required",
            //     "payment_date" => 'required'
            // ]);
            // dd($request->all());

            $selected_so = SalesOrderModel::where('id', $id)->first();

            //Save Sales Order Credit
            $array_date = [];
            $total_current_amount = 0;
            $current_ids = [];
            foreach ($request->pay as $item) {
                $soc = new SalesOrderCreditModel();
                $soc->sales_order_id = $selected_so->id;
                $soc->payment_date = date('Y-m-d', strtotime($item['payment_date']));
                if ($item['amount_method'] == 'full') {
                    $soc->amount = $selected_so->total_after_ppn - $selected_so->returnBy->sum('total') - $selected_so->salesOrderCreditsBy->sum('amount');
                } else {
                    $soc->amount = $item['amount'];
                }
                $soc->payment_method = $item['payment_method'];
                $soc->updated_by = Auth::user()->id;
                $soc->save();
                array_push($array_date, $soc->payment_date);
                $total_current_amount += $soc->amount;
                array_push($current_ids, $soc->id);
            }
            sort($array_date);
            $last_date = end($array_date);
            //Count total amount instalment
            $all_soc = SalesOrderCreditModel::where('sales_order_id', $id)->get();
            $total_amount = 0;
            $total_return = 0;
            $total_return = ReturnModel::where('sales_order_id', $id)->sum('total');
            foreach ($all_soc as $value) {
                $total_amount = $total_amount + $value->amount;
            }
            
            //Save Journal
            $created_journal = createJournal(
                $last_date,
                'Pembayaran Indirect No.' . $selected_so->order_number,
                $selected_so->warehouse_id
            );
            if ($created_journal != "" && $created_journal != null && $created_journal != false) {
                createJournalDetail(
                    $created_journal,
                    $request->acc_coa,
                    $selected_so->order_number,
                    $total_current_amount ,
                    0
                );
                // createJournalDetail(
                //     $created_journal,
                //     '2-300',
                //     $selected_so->order_number,
                //     ($total_current_amount / 1.11) * (ValueAddedTaxModel::first()->ppn / 100),
                //     0
                // );
                createJournalDetail(
                    $created_journal,
                    '1-200',
                    $selected_so->order_number,
                    0,
                    $total_current_amount
                );
                // ** Perubahan Saldo Piutang Usaha ** //
                $get_coa_p_masukan =  Coa::where('coa_code',  $request->acc_coa)->first()->id;
                changeSaldoTambah($get_coa_p_masukan, $selected_so->warehouse_id, $total_current_amount / 1.11);

                // ** Perubahan Saldo Pendapatan Penjualan ** //
                $get_coa_persediaan =  Coa::where('coa_code', '2-300')->first()->id;
                changeSaldoKurang($get_coa_persediaan, $selected_so->warehouse_id, ($total_current_amount / 1.11) * (ValueAddedTaxModel::first()->ppn / 100));

                // ** Perubahan Saldo PPN Keluaaran ** //
                $get_coa_hutang_dagang =  Coa::where('coa_code', '1-200')->first()->id;
                changeSaldoKurang($get_coa_hutang_dagang, $selected_so->warehouse_id, $total_current_amount);
            }

            foreach ($current_ids as $key => $value) {
                $get_current_credit = SalesOrderCreditModel::where('id', $value)->first();
                $get_current_credit->journal_id = $created_journal;
                $get_current_credit->save();
            }
            
            if ($total_amount >= (round($selected_so->total_after_ppn)  - $total_return)) {
                $selected_so->isPaid = 1;
                $selected_so->paid_date = date('Y-m-d', strtotime($last_date));
                $selected_so->save();

                //update overplafone and overdue
                $checkoverplafone = checkOverPlafone($selected_so->customers_id);
                $checkoverdue = checkOverDueByCustomer($selected_so->customers_id);

                DB::commit();
                return redirect('/invoice')->with('success', "Order number " . $selected_so->order_number . " already paid!");
            } else {
                DB::commit();
                return redirect('/invoice/manage_payment')->with('success', "Update Payment of Order number " . $selected_so->order_number . " Success!");
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    public function cancelPaid(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $total_return = ReturnModel::where('sales_order_id', $id)->sum('total');
            $total_credit = SalesOrderCreditModel::where('sales_order_id', $id)->sum('amount');
            $indirect = SalesOrderModel::where('id', $id)->first();
            $indirect_credit = SalesOrderCreditModel::where('sales_order_id', $id)->get();
            
            //Journal Return
            if ($total_return > 0) {
                // ** jika terjadi return ** //
                $data_kas = '';
                foreach ($indirect_credit as $value) {
                    $journal = Journal::where('id', $value->journal_id)->first();
                    $journal_detail = JournalDetail::where('journal_id', $value->journal_id)
                        ->where('debit', '!=', 0)
                        ->orderBy('debit', 'desc') // Menyusun data berdasarkan 'credit' secara descending
                        ->first(); // Mengambil entri pertama dengan 'credit' terbesar
                    // dd($journal_detail);
                    $data_kas = $journal_detail->coa_code;
                }
                // ** Jika sudah terjadi pelunasan
                if (round($indirect->total_after_ppn) - $total_credit == 0) {
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Penjualan Indirect Tunai.' . $indirect->order_number,
                        $indirect->warehouse_id
                    );

                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA Return Penjualan ** //
                        createJournalDetail(
                            $journal,
                            '4-102',
                            $indirect->order_number,
                            $total_credit,
                            0
                        );
                        // ** COA PPn Keluaran ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '2-300',
                        //     $indirect->order_number,
                        //     $total_credit / 1.11 * (ValueAddedTaxModel::first()->ppn / 100),
                        //     0
                        // );
                        // ** COA KAS ** //
                        createJournalDetail(
                            $journal,
                            $data_kas,
                            $indirect->order_number,
                            0,
                            $total_credit
                        );

                        // ** Perubahan Saldo Piutang Usaha ** //
                        $get_coa_p_masukan =  Coa::where('coa_code',  '4-102')->first()->id;
                        changeSaldoTambah($get_coa_p_masukan, $indirect->warehouse_id, $total_credit / 1.11);

                        // ** Perubahan Saldo Pendapatan Penjualan ** //
                        $get_coa_persediaan =  Coa::where('coa_code', '2-300')->first()->id;
                        changeSaldoTambah($get_coa_persediaan, $indirect->warehouse_id, $total_credit / 1.11 * (ValueAddedTaxModel::first()->ppn / 100));

                        // ** Perubahan Saldo PPN Keluaaran ** //
                        $get_coa_hutang_dagang =  Coa::where('coa_code', $data_kas)->first()->id;
                        changeSaldoKurang($get_coa_hutang_dagang, $indirect->warehouse_id, $total_credit);
                    }
                } else if ($indirect_credit->count() > 0) {

                    // ** ini jika sudah bayar setengah
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Penjualan Indirect Tunai.' . $indirect->order_number,
                        $indirect->warehouse_id
                    );

                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA Return Penjualan ** //
                        createJournalDetail(
                            $journal,
                            '4-102',
                            $indirect->order_number,
                            $total_credit,
                            0
                        );
                        // ** COA PPn Keluaran ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '2-300',
                        //     $indirect->order_number,
                        //     $total_credit / 1.11 * (ValueAddedTaxModel::first()->ppn / 100),
                        //     0
                        // );
                        // ** COA KAS ** //
                        createJournalDetail(
                            $journal,
                            $data_kas,
                            $indirect->order_number,
                            0,
                            $total_credit
                        );
                    }

                    // ** ini sisa yang belum bayar
                    $journal = createJournal(
                        Carbon::now()->format('Y-m-d'),
                        'Retur Penjualan Indirect Kredit.' . $indirect->order_number,
                        $indirect->warehouse_id
                    );

                    // ** Jika Jurnal Berhasil Disimpan ** //
                    if ($journal != "" && $journal != null && $journal != false) {
                        // ** COA Return Penjualan ** //
                        createJournalDetail(
                            $journal,
                            '4-102',
                            $indirect->order_number,
                            (round($indirect->total_after_ppn) - $total_credit) ,
                            0
                        );
                        // ** COA PPn Keluaran ** //
                        // createJournalDetail(
                        //     $journal,
                        //     '2-300',
                        //     $indirect->order_number,
                        //     (round($indirect->total_after_ppn) - $total_credit) / 1.11 * (ValueAddedTaxModel::first()->ppn / 100),
                        //     0
                        // );
                        // ** COA Piutang ** //
                        createJournalDetail(
                            $journal,
                            '1-200',
                            $indirect->order_number,
                            0,
                            round($indirect->total_after_ppn) - $total_credit
                        );
                    }
                    // ** Perubahan Saldo Retur Penjualan ** //
                    $get_coa_p_masukan =  Coa::where('coa_code', '4-102')->first()->id;
                    changeSaldoTambah($get_coa_p_masukan, $indirect->warehouse_id, ($total_credit / 1.11) + ((round($indirect->total_after_ppn) - $total_credit) / 1.11));

                    // ** Perubahan Saldo PPN Keluaran ** //
                    $get_coa_persediaan =  Coa::where('coa_code', '2-300')->first()->id;
                    changeSaldoKurang($get_coa_persediaan, $indirect->warehouse_id, ($total_credit / 1.11 * (ValueAddedTaxModel::first()->ppn / 100)) + ((round($indirect->total_after_ppn) - $total_credit) / 1.11 * (ValueAddedTaxModel::first()->ppn / 100)));

                    // ** Perubahan Saldo Piutang Usaha ** //
                    $get_coa_hutang_dagang =  Coa::where('coa_code', '1-200')->first()->id;
                    changeSaldoKurang($get_coa_hutang_dagang, $indirect->warehouse_id,  round($indirect->total_after_ppn) - $total_credit);
                    // ** Perubahan Saldo Kas ** //
                    $get_coa_hutang_dagang =  Coa::where('coa_code', $data_kas)->first()->id;
                    changeSaldoKurang($get_coa_hutang_dagang, $indirect->warehouse_id, $total_credit);
                }
            } else {
                // ** jika hanya cancel payment saja ** //
                $get_credit_in_cancel = SalesOrderCreditModel::where('sales_order_id', $id)->get();
                foreach ($get_credit_in_cancel as $value) {
                    $journal = Journal::where('id', $value->journal_id)->first();
                    if ($journal) {
                        $journal->jurnal_detail()->delete();
                        $journal->delete();
                    }
                }
            }
            
            foreach ($request->cancel as $value) {
                $credits = SalesOrderCreditModel::where('id', $value['credit_id'])->first();
                $credits->amount = $credits->amount - $value['amount'];
                if ($credits->amount <= 0) {
                    $credits->delete();
                } else {
                    $credits->save();
                }
            }
            // $total_return = ReturnModel::where('sales_order_id', $id)->sum('total');
            $total_credit = SalesOrderCreditModel::where('sales_order_id', $id)->sum('amount');
            // $indirect = SalesOrderModel::where('id', $id)->first();
            if (round($indirect->total_after_ppn) - $total_return == $total_credit) {
                $indirect->isPaid = 1;
                $indirect->paid_date = date('Y-m-d');
                $indirect->save();

                //update overplafone and overdue
                $checkoverplafone = checkOverPlafone($indirect->customers_id);
                $checkoverdue = checkOverDueByCustomer($indirect->customers_id);
            }

            DB::commit();
            return redirect('/invoice/manage_payment')->with('error', 'Cancel payment success!');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function paidManagement(Request $request)
    {

        // dd($invoice);
        if ($request->ajax()) {

            $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                ->when($request->invoice_number, function ($query) use ($request) {
                    return $query->where('order_number', $request->invoice_number);
                }, function ($query) {
                    return $query->where('isapprove', 'approve')
                        ->where('isverified', 1)
                        ->where('isPaid', 0);
                })
                // ->where('order_date', 'Y-m-d')
                ->get()
                ->groupBy('customers_id')
                ->sortBy(function ($item) {
                    return $item->first()->customerBy->name_cust;
                });

            return datatables()->of($invoice)
                ->editColumn('total_after_ppn', function ($data) {
                    $total_return = 0;
                    $total_sale = 0;
                    $total_credit = 0;

                    foreach ($data as $value) {
                        $total_return += ReturnModel::where('sales_order_id', $value->id)->sum('total');
                        $total_credit += SalesOrderCreditModel::where('sales_order_id', $value->id)->sum('amount');
                        $total_sale += $value->total_after_ppn;
                    }
                    return number_format(($total_sale - $total_credit) - $total_return, 0, '.', ',');
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) {
                    $total_return = 0;
                    $total_sale = 0;
                    $total_credit = 0;
                    $id_cust = '';
                    $name_cust = '';
                    $code_cust = '';

                    foreach ($invoice as $value) {
                        $total_return += ReturnModel::where('sales_order_id', $value->id)->sum('total');
                        $total_credit += SalesOrderCreditModel::where('sales_order_id', $value->id)->sum('amount');
                        $total_sale += $value->total_after_ppn;
                    }
                    $id_cust = $invoice->first()->customerBy->id;
                    $name_cust = $invoice->first()->customerBy->name_cust;
                    $code_cust = $invoice->first()->customerBy->code_cust;

                    return view('invoice._option_paid_management', compact(
                        'invoice',
                        'total_sale',
                        'total_return',
                        'total_credit',
                        'id_cust',
                        'name_cust',
                        'code_cust'
                    ))->render();
                })
                ->rawColumns(['order_number'], ['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "Unpaid Invoice",
            // 'order_number' =>
        ];
        return view('invoice.paid_management', $data);
    }

    public function getTotalInstalment($id)
    {
        $soc = SalesOrderCreditModel::where('sales_order_id', $id)->get();

        $total_amount = 0;
        foreach ($soc as $value) {
            $total_amount = $total_amount + $value->amount;
        }
        return response()->json($total_amount);
    }

    public function getQtyDetail()
    {
        $so_id = request()->s;
        $product_id = request()->p;

        $getqty = SalesOrderDetailModel::where('sales_orders_id', $so_id)->where('products_id', $product_id)->first();
        $_qty = $getqty->qty;
        $selected_return = ReturnModel::with('returnDetailsBy')->where('sales_order_id', $so_id)->get();

        $return = 0;
        if ($selected_return != null) {
            foreach ($selected_return as $value) {
                $selected_detail = ReturnDetailModel::where('return_id', $value->id)->where('product_id', $product_id)->first();
                $return = $return + $selected_detail->qty;
            }
        }
        $data = [
            'qty' => $_qty,
            'return' => $return
        ];
        return response()->json($data);
    }

    public function getAllDetail()
    {
        $so_id = request()->s;

        $getqty = SalesOrderDetailModel::where('sales_orders_id', $so_id)->get();
        return response()->json($getqty);
    }

    public function selectReturn()
    {
        try {
            $so_id = request()->s;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = SalesOrderDetailModel::join('products', 'products.id', '=', 'sales_order_details.products_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('products.nama_barang', 'LIKE', "%$search%")
                    ->where('sales_orders_id', $so_id)
                    ->orWhere('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->where('sales_orders_id', $so_id)
                    ->orWhere('product_sub_materials.nama_sub_material', 'LIKE', "%$search%")
                    ->where('sales_orders_id', $so_id)
                    ->get();
            } else {
                $product = SalesOrderDetailModel::join('products', 'products.id', '=', 'sales_order_details.products_id')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('sales_orders_id', $so_id)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }
}
