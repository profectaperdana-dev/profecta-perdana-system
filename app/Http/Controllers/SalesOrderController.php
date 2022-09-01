<?php

namespace App\Http\Controllers;

use App\Events\ApprovalMessage;
use DateTimeZone;
use Carbon\Carbon;
use DateTimeImmutable;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use App\Events\SOMessage;
use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Models\NotificationsModel;
use App\Models\SalesOrderModel;
use Illuminate\Support\Facades\Auth;
use App\Models\SalesOrderDetailModel;
use App\Models\StockModel;
use App\Models\WarehouseModel;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Facades\Gate;
// use Barryvdh\DomPDF\PDF;
use PDF;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as FacadesRequest;

use function App\Helpers\checkOverDue;
use function App\Helpers\checkOverDueByCustomer;
use function App\Helpers\checkOverPlafone;
use function App\Helpers\setOverDue;
use function App\Helpers\setOverPlafone;
use function PHPUnit\Framework\isEmpty;

class SalesOrderController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // index() : CREATE SALES ORDERS
    public function index()

    {
        $title = 'Create Sales Order';
        $product = ProductModel::latest()->get();
        $customer = CustomerModel::where('status', 1)->latest()->get();

        return view('sales_orders.index', compact('title', 'product', 'customer'));
    }


    // print invoice dengan PPN
    public function printInoiceWithPpn($id)
    {
        $data = SalesOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = PDF::loadView('invoice.invoice_with_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape');
        return $pdf->download($data->order_number . '.pdf');
    }
    // print invoice tanpa PPN
    public function printInoiceWithoutPpn($id)
    {
        $data = SalesOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = PDF::loadView('invoice.invoice_without_ppn', compact('warehouse', 'data'))->setPaper('A5', 'landscape');
        return $pdf->download($data->order_number . '.pdf');
    }

    //print delivery order
    public function deliveryOrder($id)
    {
        $data = SalesOrderModel::find($id);
        $warehouse = WarehouseModel::where('id', Auth::user()->warehouse_id)->first();
        $pdf = PDF::loadView('invoice.delivery_order', compact('warehouse', 'data'))->setPaper('A5', 'landscape');
        return $pdf->download($data->order_number . '.pdf');
    }


    // getRecentData() : READ DATA RECENT SALES ORDERS ADMIN & SALES ADMIN
    public function getRecentData()
    {
        $title = 'Recent Sales Order';
        // get kode area
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', Auth::user()->warehouse_id)
            ->first();
        // get sales no debt
        $dataSalesOrder = SalesOrderModel::with([
            'customerBy',
            'salesOrderDetailsBy.productSales.sub_types',
            'salesOrderDetailsBy.productSales.sub_materials'
        ])
            ->whereIn('payment_method', [1, 2])
            ->where('isverified', 0)
            ->where('order_number', 'like', "%$kode_area->area_code%")
            ->latest()
            ->get();

        // get sales with
        $dataSalesOrderDebt = SalesOrderModel::with([
            'customerBy',
            'salesOrderDetailsBy.productSales.sub_types',
            'salesOrderDetailsBy.productSales.sub_materials'
        ])
            ->where('payment_method', 3)
            ->where('isverified', 0)
            ->where('order_number', 'like', "%$kode_area->area_code%")
            ->latest()
            ->get();

        checkOverDue();
        $customer = CustomerModel::where('status', 1)->latest()->get();


        return view('recent_sales_order.index', compact('title', 'dataSalesOrder', 'dataSalesOrderDebt', 'customer'));
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

    //  store() : SIMPAN DATA CREATE SALES ORDERS
    public function store(Request $request)
    {
        // validasi sebelum save
        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",
            "remark" => "required",
            "soFields.*.product_id" => "required|numeric",
            "soFields.*.qty" => "required|numeric"
        ]);

        //Check Stock
        foreach ($request->soFields as $qty) {
            $getStock = StockModel::where('products_id', $qty['product_id'])
                ->where('warehouses_id', Auth::user()->warehouse_id)
                ->first();
            if ($qty['qty'] > $getStock->stock) {
                return redirect('/sales_order')->with('error', 'Add Sales Order Fail! The number of items exceeds the stock');
            }
        }

        // query cek kode warehouse/area sales orders
        $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
            ->select('customer_areas.area_code', 'warehouses.id')
            ->where('warehouses.id', Auth::user()->warehouse_id)
            ->first();
        $length = 3;
        $id = intval(SalesOrderModel::where('order_number', 'like', "%$kode_area->area_code%")->max('id')) + 1;
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $year = Carbon::now()->format('Y'); // 2022
        $month = Carbon::now()->format('m'); // 2022
        $tahun = substr($year, -2);
        $order_number = 'SOPP-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
        //

        // save sales orders
        $model = new SalesOrderModel();
        $model->order_number = $order_number;
        $model->order_date = Carbon::now()->format('Y-m-d');
        $model->customers_id = $request->get('customer_id');
        $model->remark = $request->get('remark');
        $model->created_by = Auth::user()->id;
        $model->payment_method = $request->get('payment_method');

        // metode bayar
        if ($model->payment_method == 3) {
            $top = CustomerModel::where('id', $model->customers_id)->first();
            $model->top = $top->due_date;
            $dt = new DateTimeImmutable(Carbon::now()->format('Y-m-d'), new DateTimeZone('Asia/Jakarta'));
            $dt = $dt->modify("+" . $model->top . " days");
            $model->duedate = $dt;
        } else {
            $model->top = NULL;
            $model->duedate = NULL;
        }
        $model->isapprove = 0;
        $model->isverified = 0;

        $saved = $model->save();

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
                    $diskon =  $value['discount'] / 100;
                    $hargaDiskon = $harga->harga_jual_nonretail * $diskon;
                    $hargaAfterDiskon = $harga->harga_jual_nonretail -  $hargaDiskon;
                    $total = $total + ($hargaAfterDiskon * $data->qty);
                    $data->save();
                }
            }
        }
        $ppn = 0.11 * $total;
        $model->ppn = $ppn;
        $model->total = $total;
        $model->total_after_ppn = $total + $ppn;
        $saved = $model->save();

        if (empty($message_duplicate) && $saved) {
            $message = $model->order_number . ' Sales Order has been created! Please check';
            event(new SOMessage('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->role_id = 5;
            $notif->save();
            return redirect('/sales_order')->with('success', 'Create sales orders ' . $model->order_number . ' success');
        } elseif (!empty($message_duplicate) && $saved) {
            $message = $model->order_number . ' Sales Order has been created! Please check';
            event(new SOMessage('From: ' . Auth::user()->name,  $message));
            $notif = new NotificationsModel();
            $notif->message = $message;
            $notif->status = 0;
            $notif->role_id = 5;
            $notif->save();
            return redirect('/sales_order')->with('info', 'Some of SO add maybe Success! ' . $message_duplicate);
        } else {
            return redirect('/sales_order')->with('error', 'Add Sales Order Fail! Please make sure you have filled all the input');
        }
    }

    // editSo() :TAMPILAN EDIT SALES ORDER TANPA PRODUCT
    public function editSo($id)
    {
        $title = 'Edit Data Sales Order';
        $value = SalesOrderModel::find($id);
        $customer = CustomerModel::where('status', 1)->latest()->get();
        return view('recent_sales_order.edit', compact('title', 'value', 'customer'));
    }
    // updateSo() : PROSES UPDATE SALES ORDER TANPA PRODUCT
    public function updateSo(Request $request, $id)
    {
    }


    // editProduuctP() :TAMPILAN EDIT PRODUCT
    public function editProduct($id)
    {
        $title = 'Edit Data Product in Sales Order :';
        $value = SalesOrderModel::with(['salesOrderDetailsBy.productSales.sub_types', 'salesOrderDetailsBy.productSales.sub_materials'])->find($id);
        $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')
            ->select('products.*', 'stocks.warehouses_id')
            ->where('stocks.warehouses_id', Auth::user()->warehouse_id)
            ->get();
        $customer = CustomerModel::where('status', 1)->latest()->get();
        return view('recent_sales_order.edit_product', compact('title', 'value', 'customer', 'product'));
    }

    public function addProduct(Request $request, $id)
    {
        $request->validate([
            "soFields.*.product_id" => "required|numeric",
            "soFields.*.qty" => "required|numeric"
        ]);

        //Check Stock
        foreach ($request->soFields as $qty) {
            $getStock = StockModel::where('products_id', $qty['product_id'])
                ->where('warehouses_id', Auth::user()->warehouse_id)
                ->first();
            if ($qty['qty'] > $getStock->stock) {
                return Redirect::back()->with('error', 'Add Sales Order Fail! The number of items exceeds the stock');
            }
        }

        $model = SalesOrderModel::where('id', $id)->first();
        $total = 0;
        $message_duplicate = "";
        foreach ($request->soFields as $value) {
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
                $message_duplicate = "You enter duplication of products. Please recheck the Detail Product you set.";
                continue;
            } else {
                $data->save();
                $harga = ProductModel::where('id', $data->products_id)->first();
                $diskon =  $data->discount / 100;
                $hargaDiskon = $harga->harga_jual_nonretail * $diskon;
                $hargaAfterDiskon = $harga->harga_jual_nonretail -  $hargaDiskon;
                $total = $total + ($hargaAfterDiskon * $data->qty);
            }
        }
        $old_ppn = $model->ppn;
        $old_total = $model->total;
        $old_total_after_ppn = $model->total_after_ppn;

        $ppn = 0.11 * $total;
        $model->ppn = $ppn + $old_ppn;
        $model->total = $total + $old_total;
        $model->total_after_ppn = ($total + $ppn) + $old_total_after_ppn;
        $saved = $model->save();
        if (empty($message_duplicate) && $saved) {
            return Redirect::back()->with('success', 'Add Products to Sales Order ' . $model->order_number . ' success');
        } elseif (!empty($message_duplicate) && $saved) {
            return Redirect::back()->with('info', 'Some of Products add maybe Success! ' . $message_duplicate);
        } else {
            return Redirect::back()->with('error', 'Add Products Fail! Please make sure you have filled all the input');
        }
    }

    // updateProduct()
    public function updateProduct(Request $request, $id)
    {
        // dd($request->all());
        $model = SalesOrderModel::find($id);
        // dd($model);

        //Check Stock
        foreach ($request->editProduct as $qty) {
            $getStock = StockModel::where('products_id', $qty['products_id'])
                ->where('warehouses_id', Auth::user()->warehouse_id)
                ->first();
            if ($qty['qty'] > $getStock->stock) {
                return Redirect::back()->with('error', 'Add Sales Order Fail! The number of items exceeds the stock');
            }
        }
        $total = 0;
        $isduplicate = false;
        foreach ($request->editProduct as $key => $value) {
            $sod = SalesOrderDetailModel::where('id', $value['id_sod'])->first();
            $temp_product = $sod->products_id;
            $temp_discount = $sod->discount;
            $temp_qty = $sod->qty;
            $sod->products_id = $value['products_id'];
            $sod->qty = $value['qty'];
            $sod->discount = $value['discount'];
            $sod->save();
            $check_duplicate = SalesOrderDetailModel::where('sales_orders_id', $sod->sales_orders_id)
                ->where('products_id', $sod->products_id)
                ->count();
            if ($check_duplicate > 1) {
                $sod->products_id = $temp_product;
                $sod->discount = $temp_discount;
                $sod->qty = $temp_qty;
                $sod->save();
                $isduplicate = true;
            }
            $dataHarga = ProductModel::select('harga_jual_nonretail')->where('id', $sod->products_id)->first();
            $diskon =   $sod->discount / 100;
            $hargaDiskon = $dataHarga->harga_jual_nonretail * $diskon;
            $hargaAfterDiskon = $dataHarga->harga_jual_nonretail -  $hargaDiskon;
            $total = $total + ($hargaAfterDiskon * $sod->qty);
        }

        $ppn = 0.11 * $total;
        $model->ppn = $ppn;
        $model->total = $total;
        $model->total_after_ppn = $total + $ppn;
        $saved = $model->save();

        if ($saved && $isduplicate == false) {
            return Redirect::back()->with('success', 'Update product in sales orders ' . $model->order_number . ' success');
        } elseif ($saved && $isduplicate == true) {
            return Redirect::back()->with('info', 'Some of update product in sales orders ' . $model->order_number . ' maybe success, but you enter existing products. Please check again!');
        } else {
            return Redirect::back()->with('error', 'Update product in sales orders ' . $model->order_number . ' fail');
        }
    }
    // deleteProduct() : HAPUS DATA PRODUCT PADA SO DAN UPDATE JUMLAH HARGA SERTA TOTAL
    public function deleteProduct($id_so, $id_sod)
    {
        $cekDetail = SalesOrderDetailModel::where('sales_orders_id', $id_so)->count();

        if ($cekDetail > 1) {
            // dd($model);
            $cekProduct = SalesOrderDetailModel::find($id_sod);
            $hapus =  $cekProduct->delete();
            $total = 0;
            if ($hapus) {
                $productDetail = SalesOrderDetailModel::where('sales_orders_id', $id_so)->get();
                // dd($productDetail);

                foreach ($productDetail as $produk) {
                    $harga = ProductModel::where('id', $produk->products_id)->first();
                    $diskon =  $produk['discount'] / 100;
                    $hargaDiskon = $harga->harga_jual_nonretail * $diskon;
                    $hargaAfterDiskon = $harga->harga_jual_nonretail -  $hargaDiskon;
                    $total = $total + ($hargaAfterDiskon * $produk->qty);
                    // dd($produk);
                }
            }
            $model = SalesOrderModel::find($id_so);
            $ppn = 0.11 * $total;
            $model->ppn = $ppn;
            $model->total = $total;
            $model->total_after_ppn = $total + $ppn;
            $model->save();
            return Redirect::back()->with('error', 'Delete product in sales orders success');
        } else {
            return Redirect::back()->with('error', 'Delete product in sales orders fail because the product in the sales order cannot be empty');
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
        $modelSalesOrder = SalesOrderModel::where('id', $id)->first();
        $modelSalesOrder->salesOrderDetailsBy()->delete();
        $modelSalesOrder->delete();
        return redirect('/recent_sales_order')->with('error', 'Delete Data Sales Order Success');
    }
    public function soNeedApproval()
    {
        $title = 'Sales Order Need Approval By Admin';

        $dataInvoice = SalesOrderModel::where('isapprove', 0)->where('isverified', 1)->latest('created_at')->get();

        return view('need_approval.index', compact('title', 'dataInvoice'));
    }
    public function verify(Request $request, $id)
    {
        // Validate Input
        $request->validate([
            "customer_id" => "required|numeric",
            "payment_method" => "required|numeric",
            "editProduct.*.products_id" => "required|numeric",
            "editProduct.*.qty" => "required|numeric",
            "editProduct.*.discount" => "required|numeric",
            "remark" => "required"
        ]);

        //Assign Object Model and Save SO Input
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
            $model->top = NULL;
            $model->duedate = NULL;
        }
        $saved_temp = $model->save();

        //Check Duplicate
        $products_arr = [];
        foreach ($request->get('editProduct') as $check) {
            array_push($products_arr, $check['products_id']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));

        if (!empty($duplicates)) {
            return redirect('/recent_sales_order')->with('error', "You enter duplicate products! Please check again!");
        }

        //Save SOD Input and Count total
        $total = 0;

        foreach ($request->editProduct as $product) {
            $product_exist = SalesOrderDetailModel::where('sales_orders_id', $id)
                ->where('products_id', $product['products_id'])->first();
            if ($product_exist != null) {
                $product_exist->qty = $product['qty'];
                $product_exist->discount = $product['discount'];
                $product_exist->save();
            } else {
                $new_product = new SalesOrderDetailModel();
                $new_product->sales_orders_id = $id;
                $new_product->products_id = $product['products_id'];
                $new_product->qty = $product['qty'];
                $new_product->discount = $product['discount'];
                $new_product->created_by = Auth::user()->id;
                $new_product->save();
            }
            $harga = ProductModel::where('id', $product['products_id'])->first();
            $diskon =  $product['discount'] / 100;
            $hargaDiskon = $harga->harga_jual_nonretail * $diskon;
            $hargaAfterDiskon = $harga->harga_jual_nonretail -  $hargaDiskon;
            $total = $total + ($hargaAfterDiskon * $product['qty']);
        }

        //Delete product that not in SOD Input
        $del = SalesOrderDetailModel::where('sales_orders_id', $id)
            ->whereNotIn('products_id', $products_arr)->delete();

        //Count PPN and Total
        $ppn = 0.11 * $total;
        $model->ppn = $ppn;
        $model->total = $total;
        $model->total_after_ppn = $total + $ppn;

        //Verify
        $getCredential = CustomerModel::where('id', $model->customers_id)->firstOrFail();
        $model->isverified = 1;
        $model->verifiedBy = Auth::user()->id;
        if ($model->payment_method != 3) {
            $model->isapprove = 1;
            $model->isPaid = 1;
            $so_number = $model->order_number;
            $so_number = str_replace('SOPP', 'IVPP', $so_number);
            $model->order_number = $so_number;

            //Potong Stock
            $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $id)->get();
            foreach ($selected_sod as $value) {
                $getStock = StockModel::where('products_id', $value->products_id)
                    ->where('warehouses_id', Auth::user()->warehouse_id)
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
        } else {
            $checkoverplafone = checkOverPlafone($model->customers_id);
            $checkoverdue = checkOverDueByCustomer($model->customers_id);
            // dd("Overdue: " . $checkoverdue . ", " . "Overplafone: " . $checkoverplafone);
            if ($checkoverdue == false & $checkoverplafone == false & $getCredential->label != 'Bad Customer') {
                $model->isapprove = 1;
                $so_number = $model->order_number;
                $so_number = str_replace('SOPP', 'IVPP', $so_number);
                $model->order_number = $so_number;

                //Potong Stock
                $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $id)->get();
                foreach ($selected_sod as $value) {
                    $getStock = StockModel::where('products_id', $value->products_id)
                        ->where('warehouses_id', Auth::user()->warehouse_id)
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
            } else {
                $message = 'Sales Order indicated overdue or overceiling. Please check immediately!';
                event(new ApprovalMessage('From:' . Auth::user()->name, $message));
                $notif = new NotificationsModel();
                $notif->message = $message;
                $notif->status = 0;
                $notif->role_id = 2;
                $notif->save();
            }
        }

        $saved_model = $model->save();
        if ($saved_model == true) {
            return redirect('/recent_sales_order')->with('success', "Sales Order Verification Success");
        } else {
            return redirect('/recent_sales_order')->with('error', "Sales Order Verification Fail! Please check again!");
        }
    }

    // getInvoiceData() : Tampilkan data invoice dengan yajra
    public function getInvoiceData(Request $request)
    {
        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            if (!empty($request->from_date)) {
                $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                    ->where('isapprove', 1)
                    ->where('isverified', 1)
                    ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                    ->where('isapprove', 1)
                    ->where('isverified', 1)
                    ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->latest()
                    ->get();
            }
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
                ->editColumn('isPaid', function ($data) {
                    if ($data->isPaid == 0) {
                        return 'Unpaid';
                    } else {
                        return 'Paid';
                    }
                })
                ->editColumn('total_after_ppn', function ($data) {
                    return number_format($data->total_after_ppn);
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('ppn', function ($data) {
                    return number_format($data->ppn);
                })
                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data->order_date));
                })
                ->editColumn('duedate', function ($data) {
                    return date('d-M-Y', strtotime($data->duedate));
                })
                ->editColumn('customers_id', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->customerBy->name_cust;
                })
                ->editColumn('created_by', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->createdSalesOrder->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', 'invoice._option')
                ->rawColumns(['action'], ['customerBy'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All data invoice in profecta perdana : " . Auth::user()->warehouseBy->warehouses,
            // 'order_number' =>
        ];

        return view('invoice.index', $data);
    }

    public function approve($id)
    {
        $selected_so = SalesOrderModel::where('id', $id)->firstOrFail();

        //Potong Stock
        $selected_sod = SalesOrderDetailModel::where('sales_orders_id', $selected_so->id)->get();
        foreach ($selected_sod as $value) {
            $getStock = StockModel::where('products_id', $value->products_id)
                ->where('warehouses_id', Auth::user()->warehouse_id)
                ->first();
            $old_stock = $getStock->stock;
            $getStock->stock = $old_stock - $value->qty;
            if ($getStock->stock < 0) {
                return Redirect::back()->with('error', 'Approval Fail! Not enough stock. Please re-confirm to the customer.');
            } else {
                $getStock->save();
            }
        }

        //Update Last Transaction Customer
        $selected_customer = CustomerModel::where('id', $selected_so->customers_id)->first();
        $selected_customer->last_transaction = $selected_so->order_date;
        $selected_customer->save();

        $so_number = $selected_so->order_number;
        $so_number = str_replace('SOPP', 'IVPP', $so_number);
        $selected_so->order_number = $so_number;
        $selected_so->isapprove = 1;
        $selected_so->approvedBy = Auth::user()->id;
        $selected_so->save();
        return redirect('/invoice')->with('success', "Sales Order Approval Success");
    }

    public function updatePaid($id)
    {
        $selected_so = SalesOrderModel::where('id', $id)->firstOrFail();

        $selected_so->isPaid = 1;
        $selected_so->save();

        $checkoverplafone = checkOverPlafone($selected_so->customers_id);
        checkOverDueByCustomer($selected_so->customers_id);

        return redirect('/invoice')->with('success', "Order number " . $selected_so->order_number . " already paid!");
    }

    public function traceFouls($id)
    {
        $selected_customer = CustomerModel::where('id', $id)->firstOrFail();
        $selected_inv = SalesOrderModel::where('customers_id', $id)
            ->where('isPaid', 0)
            ->where('isverified', 1)
            ->latest()
            ->get();

        $total_credit = 0;
        foreach ($selected_inv as $invoice) {
            $total_credit = $total_credit + $invoice->total_after_ppn;
        }

        $data = [
            'title' => 'Tracing Fouls for ' . $selected_customer->name_cust,
            'all_inv' => $selected_inv,
            'selected_customer' => $selected_customer,
            'total_credit' => $total_credit
        ];

        return view('need_approval.trace_fouls', $data);
    }

    public function paidManagement(Request $request)
    {
        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            if (!empty($request->from_date)) {
                $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                    ->where('isapprove', 1)
                    ->where('isverified', 1)
                    ->where('isPaid', 0)
                    ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->whereBetween('order_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $invoice = SalesOrderModel::with('customerBy', 'createdSalesOrder')
                    ->where('isapprove', 1)
                    ->where('isverified', 1)
                    ->where('isPaid', 0)
                    ->where('order_number', 'like', "%$kode_area->area_code%")
                    ->latest()
                    ->get();
            }
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
                ->editColumn('isPaid', function ($data) {
                    if ($data->isPaid == 0) {
                        return 'Unpaid';
                    } else {
                        return 'Paid';
                    }
                })
                ->editColumn('total_after_ppn', function ($data) {
                    return number_format($data->total_after_ppn);
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->total);
                })
                ->editColumn('ppn', function ($data) {
                    return number_format($data->ppn);
                })
                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data->order_date));
                })
                ->editColumn('duedate', function ($data) {
                    return date('d-M-Y', strtotime($data->duedate));
                })
                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data));
                })
                ->editColumn('customers_id', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->customerBy->name_cust;
                })
                ->editColumn('created_by', function (SalesOrderModel $SalesOrderModel) {
                    return $SalesOrderModel->createdSalesOrder->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function () use ($invoice) {
                    return view('invoice._option', compact('invoice'));
                })
                ->rawColumns(['action'], ['customerBy'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => "All data unpaid invoice in Profecta Perdana : " . Auth::user()->warehouseBy->warehouses,
            // 'order_number' =>
        ];
        return view('invoice.paid_management', $data);
    }
}
