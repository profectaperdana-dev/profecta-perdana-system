<?php

namespace App\Http\Controllers;

use App\Models\ReturnRetailDetailModel;
use App\Models\ReturnRetailModel;
use App\Models\AccuAccuClaimModel;
use App\Models\AccuClaimDetailModel;
use App\Models\AccuClaimModel;
use App\Models\CarBrandModel;
use App\Models\CarTypeModel;
use App\Models\CustomerAreaModel;
use App\Models\CustomerCategoriesModel;
use App\Models\CustomerModel;
use App\Models\DailyActivityModel;
use App\Models\DirectSalesCreditModel;
use App\Models\DirectSalesDetailModel;
use App\Models\DirectSalesModel;
use App\Models\EmployeeModel;
use App\Models\AttendancesModel;
use App\Models\AssetModel;
use App\Models\ItemPromotionModel;
use App\Models\ItemPromotionMutationDetailModel;
use App\Models\ItemPromotionPurchaseDetailModel;
use App\Models\ItemPromotionStockModel;
use App\Models\ItemPromotionSupplierModel;
use App\Models\ItemPromotionTransactionDetailModel;
use App\Models\MotorBrandModel;
use App\Models\MotorTypeModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderCreditModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\PurchaseOrderModel;
use App\Models\ReturnDetailModel;
use App\Models\ReturnModel;
// use App\Models\CustomerAreaModel;
use App\Models\ReturnPurchaseDetailModel;
use App\Models\ReturnPurchaseModel;
use App\Models\SalesOrderCreditModel;
use App\Models\SalesOrderDetailModel;
use App\Models\SalesOrderModel;
use App\Models\StockModel;
use App\Models\StockMutationDetailModel;
use App\Models\SubMaterialModel;
use App\Models\SubTypeModel;
use App\Models\SuppliersModel;
use App\Models\TradeInDetailModel;
use App\Models\TradeInModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use App\Models\MaterialModel;
use App\Models\ProductCostModel;
use App\Models\ProductTradeInModel;
use App\Models\ReturnItemPromotionDetailModel;
use App\Models\ReturnItemPromotionPurchaseDetailModel;
use App\Models\ReturnTradePurchaseDetailModel;
use App\Models\SecondProductModel;
use App\Models\SecondSaleDetailModel;
use App\Models\StockMutationModel;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Finance\JournalDetail;

class ReportController extends Controller
{
    private $product, $material, $type, $customer, $car_brand, $car_type, $motor_brand, $motor_type, $warehouse, $suppliers, $all_warehouse, $group, $trade_product;
    public function __construct($trade_product = null, $group = null, $product = null, $material = null, $type = null, $customer = null, $car_brand = null, $car_type = null, $motor_brand = null, $motor_type = null, $warehouse = null, $suppliers = null)
    {
        $product = ProductModel::oldest('nama_barang')->get();
        $this->product = $product;

        $material = SubMaterialModel::oldest('nama_sub_material')->get();
        $this->material = $material;

        $type = SubTypeModel::oldest('type_name')->get();
        $this->type = $type;

        $customer = CustomerModel::oldest('code_cust')->get();
        $this->customer = $customer;

        $car_brand = CarBrandModel::orderBy('car_brand', 'ASC')->get();
        $this->car_brand = $car_brand;

        $car_type = CarTypeModel::orderBy('car_type', 'ASC')->get();
        $this->car_type = $car_type;

        $motor_brand = MotorBrandModel::orderBy('name_brand', 'ASC')->get();
        $this->motor_brand = $motor_brand;

        $motor_type = MotorTypeModel::orderBy('name_type', 'ASC')->get();
        $this->motor_type = $motor_type;

        $warehouse = WarehouseModel::with('typeBy')
            ->whereHas('typeBy', function ($query) {
                $query->where('id', 5);
            })
            ->latest()
            ->get();
        $this->warehouse = $warehouse;

        $all_warehouse = WarehouseModel::oldest('warehouses')->get();
        $this->all_warehouse = $all_warehouse;

        $suppliers = SuppliersModel::oldest('nama_supplier')->get();
        $this->suppliers = $suppliers;

        $group = MaterialModel::all();
        $this->group = $group;

        $trade_product = ProductTradeInModel::oldest('name_product_trade_in')->get();
        $this->trade_product = $trade_product;
    }

    //! function  report non retail
    //! function  report non retail
    public function index(Request $request)
    {
        $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $invoice = SalesOrderDetailModel::with('productSales', 'salesorders')
                    //! query untuk relasi dari salesorderdetail ke salesorder
                    ->whereHas('salesorders', function ($q) use ($request, $userWarehouseIds) {
                        $q->where('isapprove', 'approve');
                        $q->where('isrejected', 0);
                        $q->whereIn('warehouse_id', $userWarehouseIds);
                        $q->where('isverified', 1)
                            ->when($request->from_date, function ($query) use ($request) {
                                $query->whereBetween('order_date', [$request->from_date, $request->to_date]);
                                $query->latest('order_date');
                            })
                            ->when($request->warehouse, function ($query) use ($request) {
                                $query->where('warehouse_id', $request->warehouse);
                            });
                        //! query untuk relasi dari salesorder ke customer
                        $q->whereHas('customerBy', function ($q) use ($request) {
                            $q->when($request->customer, function ($query) use ($request) {
                                $query->where('id', $request->customer);
                            });

                            $q->whereHas('areaBy', function ($q) use ($request) {
                                $q->when($request->area, function ($query) use ($request) {
                                    $query->where('id', $request->area);
                                });
                            });
                        });
                        $q->oldest('order_date');
                    })
                    //! query untuk relasi dari salesorderdetail ke product
                    ->whereHas('productSales', function ($q) use ($request) {
                        //! get product
                        $q->when($request->product, function ($query) use ($request) {
                            $query->where('id', $request->product);
                        });
                        //! query untuk relasi dari product ke submaterial
                        $q->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $q->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })
                    ->whereHas('salesorders', function ($q) use ($request) {
                        $q->latest('order_number');
                    })
                    ->get()
                    ->sortBy(function ($invoice) {
                        return $invoice->salesorders->order_date;
                    });
            } else {
                $invoice = SalesOrderDetailModel::with('soBy', 'productSales', 'salesorders')
                    ->whereHas('salesorders', function ($q) use ($userWarehouseIds) {
                        $q->where('isapprove', 'approve');
                        $q->where('isverified', 1);
                        $q->where('isrejected', 0);
                        $q->where('order_date', date('Y-m-d'));
                        $q->whereIn('warehouse_id', $userWarehouseIds);
                        $q->latest('order_number');
                    })
                    ->get()
                    ->sortBy(function ($invoice) {
                        return $invoice->salesorders->order_date;
                    });
            }
            return datatables()
                ->of($invoice)
                //! get order number
                ->editColumn('order_number', function ($data) {
                    return $data->salesorders->order_number;
                })
                //! get customer name
                ->editColumn('name_cust', function ($data) {
                    return $data->salesorders->customerBy->name_cust . ' (' . $data->salesorders->customerBy->code_cust . ')';
                })
                //! get remark
                ->editColumn('remark', function ($data) {
                    return $data->salesorders->remark;
                })

                //! get nama barang
                ->editColumn('nama_barang', function ($data) {
                    return $data->productSales->nama_barang;
                })

                //! get order date
                ->editColumn('order_date', function ($data) {
                    return date('d M Y', strtotime($data->salesorders->order_date));
                })

                //! get created by
                ->editColumn('created_by', function ($data) {
                    return $data->salesorders->createdSalesOrder->name;
                })

                //! get payment method
                ->editColumn('payment_method', function ($data) {
                    if ($data->salesorders->payment_method == 1) {
                        return 'COD';
                    } elseif ($data->salesorders->payment_method == 2) {
                        return 'CBD';
                    } else {
                        return 'Credit';
                    }
                })

                //! get due date
                ->editColumn('duedate', function ($data) {
                    if ($data->salesorders->duedate != null) {
                        return date('d M Y', strtotime($data->salesorders->duedate));
                    } else {
                        return '-';
                    }
                })

                //! get top
                ->editColumn('top', function ($data) {
                    if ($data->salesorders->top != null) {
                        return $data->salesorders->top . ' Days';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('area', function ($data) {
                    return $data->salesorders->customerBy->areaBy->area_name;
                })
                ->editColumn('district', function ($data) {
                    return $data->salesorders->customerBy->city;
                })

                //! paid date
                ->editColumn('paid_date', function ($data) {
                    if ($data->salesorders->paid_date == null) {
                        return '-';
                    } else {
                        return date('d M Y', strtotime($data->salesorders->paid_date));
                    }
                })

                //! total per item
                ->editColumn('total', function ($data) {
                    // $diskon_persen = $data->discount / 100;
                    // $produk_diskon = str_replace(',', '.', $data->productSales->harga_jual_nonretail) * $diskon_persen;
                    // $harga_setelah_diskon = str_replace(',', '.', $data->productSales->harga_jual_nonretail) - $produk_diskon - $data->discount_rp;
                    // $total = $harga_setelah_diskon * $data->qty;
                    return number_format((float) $data->salesorders->total, 0, '.', ',');
                })
                ->editColumn('price', function ($data) {
                    if ($data->price == null) {
                        $harga = (float) $data->productSales->harga_jual_nonretail;
                        $harga = str_replace(',', '.', $harga);
                        $harga_ppn = (float) $harga * (ValueAddedTaxModel::first()->ppn / 100);
                        $harga_incl = (float) $harga + $harga_ppn;
                    } else {
                        $harga_incl = $data->price;
                    }

                    $harga_diskon = $harga_incl * ($data->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $data->discount_rp;
                    return number_format((float) $harga_final, 0, '.', ',');
                })
                ->editColumn('total_price', function ($data) {
                    if ($data->price == null) {
                        $harga = (float) $data->productSales->harga_jual_nonretail;
                        $harga = str_replace(',', '.', $harga);
                        $harga_ppn = (float) $harga * (ValueAddedTaxModel::first()->ppn / 100);
                        $harga_incl = (float) $harga + $harga_ppn;
                    } else {
                        $harga_incl = $data->price;
                    }

                    $harga_diskon = $harga_incl * ($data->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $data->discount_rp;
                    return number_format((float) $harga_final * $data->qty, 0, '.', ',');
                })
                ->editColumn('total_price_excl', function ($data) {
                    if ($data->price == null) {
                        $harga = (float) $data->productSales->harga_jual_nonretail;
                        $harga = str_replace(',', '.', $harga);
                        $harga_ppn = (float) $harga * (ValueAddedTaxModel::first()->ppn / 100);
                        $harga_incl = (float) $harga + $harga_ppn;
                    } else {
                        $harga_incl = $data->price;
                    }

                    $harga_diskon = $harga_incl * ($data->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $data->discount_rp;
                    return number_format((float) ($harga_final * $data->qty) / 1.11);
                })
                //! ppn per item
                ->editColumn('ppn', function ($data) {
                    // $diskon_persen = $data->discount / 100;
                    // $produk_diskon = str_replace(',', '.', $data->productSales->harga_jual_nonretail) * $diskon_persen;
                    // $harga_setelah_diskon = str_replace(',', '.', $data->productSales->harga_jual_nonretail) - $produk_diskon - $data->discount_rp;
                    // $total = $harga_setelah_diskon * $data->qty;
                    // $ppn = 0.11 * $total;
                    return number_format((float) $data->salesorders->ppn, 0, '.', ',');
                })

                //! total + ppn per item
                ->editColumn('total_ppn', function ($data) {
                    // $diskon_persen = $data->discount / 100;
                    // $produk_diskon = str_replace(',', '.', $data->productSales->harga_jual_nonretail) * $diskon_persen;
                    // $harga_setelah_diskon = str_replace(',', '.', $data->productSales->harga_jual_nonretail) - $produk_diskon - $data->discount_rp;
                    // $total = $harga_setelah_diskon * $data->qty;
                    // $ppn = 0.11 * $total;
                    // $total_ppn = $total + $ppn;
                    return number_format((float) $data->salesorders->total_after_ppn, 0, '.', ',');
                })

                //! return
                ->editColumn('return_total', function ($data) {
                    $total_return = ReturnModel::where('sales_order_id', $data->sales_orders_id)->sum('total');
                    return number_format((float) $total_return, 0, '.', ',');
                })

                //! get material group
                ->editColumn('material', function (SalesOrderDetailModel $SalesOrderDetailModel) {
                    return $SalesOrderDetailModel->productSales->sub_materials->nama_sub_material;
                })

                //! get sub type
                ->editColumn('sub_type', function (SalesOrderDetailModel $SalesOrderDetailModel) {
                    return $SalesOrderDetailModel->productSales->sub_types->type_name;
                })
                ->rawColumns(['sub_type'])
                ->addIndexColumn()
                ->make(true);
        }

        $get_area = CustomerAreaModel::oldest('area_name')->get();

        $data = [
            'title' => 'Indirect Sales Report',
            'material_group' => $this->material,
            'type' => $this->type,
            'product' => $this->product,
            'customer' => $this->customer,
            'warehouse' => $this->warehouse,
            'area' => $get_area,
        ];

        return view('report.index', $data);
    }

    //Report Trade-In Purchase Return
    public function report_trade_in_return(Request $request)
    {
        // dd($data);
        if ($request->ajax()) {
            $data = ReturnTradePurchaseDetailModel::with('returnBy', 'productBy')
                ->whereHas('returnBy', function ($q) use ($request) {
                    $q->when(
                        $request->from_date,
                        function ($query, $fromDate) use ($request) {
                            return $query->whereBetween('return_date', [$fromDate, $request->to_date]);
                        },
                        function ($query) {
                            // Use start and end of the current month as the default date range
                            $startDate = date('Y-m-01');
                            $endDate = date('Y-m-t');
                            return $query->whereBetween('return_date', [$startDate, $endDate]);
                        },
                    );
                })
                ->when($request->product, function ($query) use ($request) {
                    $query->where('product_id', $request->product);
                })
                ->get();

            return datatables()
                ->of($data)
                ->editColumn('return_number', function ($data) {
                    return $data->returnBy->return_number;
                })
                ->editColumn('ref', function ($data) {
                    return $data->returnBy->TradeInBy->trade_in_number;
                })
                ->editColumn('trade_by', function ($data) {
                    return $data->returnBy->TradeInBy->tradeBy->name;
                })
                ->editColumn('return_date', function ($data) {
                    return date('d-m-Y', strtotime($data->returnBy->return_date));
                })
                ->editColumn('return_reason', function ($data) {
                    return $data->returnBy->return_reason;
                })
                ->editColumn('product', function ($data) {
                    return $data->productBy->name_product_trade_in;
                })
                ->editColumn('qty', function ($data) {
                    return $data->qty;
                })
                ->editColumn('total', function ($data) {
                    return number_format((float) $data->returnBy->total);
                })
                ->editColumn('created_by', function ($data) {
                    return $data->returnBy->created_by->name;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Trade-In Purchase Return Report',
            'trade_product' => $this->trade_product,
        ];
        return view('report.return_trade_in', $data);
    }

    public function reportSecondStock(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->product) || !empty($request->warehouse)) {
                $invoice = SecondProductModel::with('productTradeBy', 'warehouseStockBy')
                    ->when($request->product, function ($query) use ($request) {
                        $query->where('products_id', $request->product);
                    })

                    ->when($request->warehouse, function ($query) use ($request) {
                        $query->where('warehouses_id', $request->warehouse);
                    })
                    ->get();
            } else {
                $invoice = SecondProductModel::with(['warehouseStockBy'], ['productTradeBy'])
                    ->latest()
                    ->get();
            }
            return datatables()
                ->of($invoice)

                ->editColumn('product', function (SecondProductModel $SecondProductModel) {
                    return $SecondProductModel->productTradeBy->name_product_trade_in;
                })
                ->editColumn('warehouse', function (SecondProductModel $SecondProductModel) {
                    return $SecondProductModel->warehouseStockBy->warehouses;
                })
                ->make(true);
        }
        $warehouse__ = WarehouseModel::with('typeBy')
            ->whereHas('typeBy', function ($query) {
                $query->where('type', '7');
            })
            ->latest()
            ->get();
        $data = [
            'title' => 'Second Product Stock Report',
            'trade_product' => $this->trade_product,
            'warehouse' => $warehouse__,
        ];
        return view('report.second_stock_report', $data);
    }
    // ! function report retail
    public function report_retail(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $invoice = DirectSalesDetailModel::with('productBy', 'directSalesBy', 'directSalesBy.createdBy', 'directSalesBy.carBrandBy', 'directSalesBy.carTypeBy', 'directSalesBy.motorBrandBy', 'directSalesBy.motorTypeBy', 'directSalesBy.customerBy', 'productBy.materials', 'productBy.sub_materials', 'productBy.sub_types')
                    ->whereHas('directSalesBy', function ($query) use ($request) {
                        $query->where('isrejected', 0);
                        $query->where('isapproved', 1);
                        $query->when($request->from_date, function ($order_date) use ($request) {
                            $order_date->whereBetween('order_date', [$request->from_date, $request->to_date]);
                        });
                        $query->when($request->province, function ($province) use ($request) {
                            $province->where('province', $this->getNameProvince($request->province));
                        });
                        $query->when($request->district, function ($district) use ($request) {
                            $district->where('district', $this->getNameCity($request->district));
                        });
                        $query->when($request->sub_district, function ($sub_district) use ($request) {
                            $sub_district->where('sub_district', $this->getNameDistrict($request->sub_district));
                        });
                        $query->when($request->customer, function ($customer) use ($request) {
                            $customer->where('cust_name', $request->customer);
                        });
                        $query->when($request->car_brand, function ($car_brand) use ($request) {
                            $car_brand->where('car_brand_id', $request->car_brand);
                        });
                        $query->when($request->car_type, function ($car_type) use ($request) {
                            $car_type->where('car_type_id', $request->car_type);
                        });
                        $query->when($request->motor_brand, function ($motor_brand) use ($request) {
                            $motor_brand->where('motor_brand_id', $request->motor_brand);
                        });
                        $query->when($request->motor_type, function ($motor_type) use ($request) {
                            $motor_type->where('motor_type_id', $request->motor_type);
                        });
                        // warehouse
                        $query->when($request->warehouse, function ($warehouse) use ($request) {
                            $warehouse->where('warehouse_id', $request->warehouse);
                        });
                    })
                    ->whereHas('productBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id', $request->product);
                        });
                        //! query untuk relasi dari product ke submaterial
                        $query->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $query->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })

                    ->get()
                    ->sortBy(function ($retail) {
                        return [$retail->directSalesBy->order_date, $retail->directSalesBy->created_at];
                    });
            } else {
                $invoice = DirectSalesDetailModel::with('productBy', 'directSalesBy', 'directSalesBy.createdBy', 'directSalesBy.carBrandBy', 'directSalesBy.carTypeBy', 'directSalesBy.motorBrandBy', 'directSalesBy.motorTypeBy', 'directSalesBy.customerBy', 'productBy.materials', 'productBy.sub_materials', 'productBy.sub_types')
                    ->whereHas('directSalesBy', function ($query) {
                        $query->where('isrejected', 0);
                        $query->where('isapproved', 1);
                        $query->where('order_date', date('Y-m-d'));
                    })
                    ->get()
                    ->sortBy(function ($retail) {
                        return $retail->directSalesBy->order_date;
                    });
            }

            return datatables()
                ->of($invoice)
                // ! get order number
                ->editColumn('order_number', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->directSalesBy->order_number;
                })
                // ! get order date
                ->editColumn('order_date', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return date('d/M/Y', strtotime($directSalesDetailModel->directSalesBy->order_date));
                })
                // ! get customer name
                ->editColumn('cust_name', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if (is_numeric($directSalesDetailModel->directSalesBy->cust_name)) {
                        return $directSalesDetailModel->directSalesBy->customerBy->name_cust . ' (' . $directSalesDetailModel->directSalesBy->customerBy->code_cust . ')';
                    } else {
                        return $directSalesDetailModel->directSalesBy->cust_name;
                    }
                })
                // ! get customer phone
                ->editColumn('cust_phone', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->directSalesBy->cust_phone == null) {
                        return '-';
                    } else {
                        return $directSalesDetailModel->directSalesBy->cust_phone;
                    }
                })
                // ! get customer ktp
                ->editColumn('cust_ktp', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->directSalesBy->cust_ktp == null) {
                        return '-';
                    } else {
                        return $directSalesDetailModel->directSalesBy->cust_ktp;
                    }
                })
                // ! get customer email
                ->editColumn('cust_email', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->directSalesBy->cust_email == null) {
                        return '-';
                    } else {
                        return $directSalesDetailModel->directSalesBy->cust_email;
                    }
                })
                ->editColumn('area', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if (is_numeric($directSalesDetailModel->directSalesBy->cust_name)) {
                        return $directSalesDetailModel->directSalesBy->customerBy->areaBy->area_name;
                    } else {
                        return $directSalesDetailModel->directSalesBy->warehouseBy->areaBy->area_name;
                    }
                })
                // ! get customer province
                ->editColumn('province', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->directSalesBy->province;
                })
                // ! get customer city
                ->editColumn('district', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->directSalesBy->district;
                })
                // ! get customer sub district
                ->editColumn('sub_district', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->directSalesBy->sub_district;
                })
                // ! get customer address
                ->editColumn('address', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->directSalesBy->address;
                })
                // ! get plate number
                ->editColumn('plate_number', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->directSalesBy->plate_number == null) {
                        return '-';
                    } else {
                        return $directSalesDetailModel->directSalesBy->plate_number;
                    }
                })
                // ! get car brand
                ->editColumn('car_brand_id', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->directSalesBy->car_brand_id == null) {
                        return '-';
                    } elseif (is_numeric($directSalesDetailModel->directSalesBy->car_brand_id)) {
                        return $directSalesDetailModel->directSalesBy->carBrandBy->car_brand;
                    } else {
                        return $directSalesDetailModel->directSalesBy->car_brand_id;
                    }
                })
                // ! get car type
                ->editColumn('car_type_id', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->directSalesBy->car_type_id == null) {
                        return '-';
                    } elseif (is_numeric($directSalesDetailModel->directSalesBy->car_type_id)) {
                        return $directSalesDetailModel->directSalesBy->carTypeBy->car_type;
                    } else {
                        return $directSalesDetailModel->directSalesBy->car_type_id;
                    }
                    // return $directSalesDetailModel->directSalesBy->carTypeBy->car_type;
                })
                // ! get motor brand
                ->editColumn('motor_brand_id', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->directSalesBy->motor_brand_id == null) {
                        return '-';
                    } elseif (is_numeric($directSalesDetailModel->directSalesBy->motor_brand_id)) {
                        return $directSalesDetailModel->directSalesBy->motorBrandBy->name_brand;
                    } else {
                        return $directSalesDetailModel->directSalesBy->motor_brand_id;
                    }
                })
                // ! get motor type
                ->editColumn('motor_type_id', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->directSalesBy->motor_type_id == null) {
                        return '-';
                    } elseif (is_numeric($directSalesDetailModel->directSalesBy->motor_type_id)) {
                        return $directSalesDetailModel->directSalesBy->motorTypeBy->name_type;
                    } else {
                        return $directSalesDetailModel->directSalesBy->motor_type_id;
                    }
                })
                // ! get material group
                ->editColumn('material', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->productBy->materials->nama_material;
                })
                // ! get sub material group
                ->editColumn('sub_material', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->productBy->sub_materials->nama_sub_material;
                })
                // ! get type group
                ->editColumn('sub_type', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->productBy->sub_types->type_name;
                })
                // ! get product name
                ->editColumn('nama_barang', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->productBy->nama_barang;
                })
                // ! get qty
                ->editColumn('qty', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->qty;
                })
                // ! get discount
                ->editColumn('discount', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->discount;
                })
                ->editColumn('price', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->price == null) {
                        $harga = ProductCostModel::where('id_product', $directSalesDetailModel->product_id)
                            ->where('id_warehouse', $directSalesDetailModel->directSalesBy->warehouse_id)
                            ->first()->harga_jual;
                        $harga = $harga;
                        $harga_ppn = (float) $harga * (ValueAddedTaxModel::first()->ppn / 100);
                        $harga_incl = (float) $harga + $harga_ppn;
                    } else {
                        $harga_incl = $directSalesDetailModel->price;
                    }

                    $harga_diskon = $harga_incl * ($directSalesDetailModel->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $directSalesDetailModel->discount_rp;
                    return number_format((float) $harga_final);
                })
                ->editColumn('total_price', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->price == null) {
                        $harga = ProductCostModel::where('id_product', $directSalesDetailModel->product_id)
                            ->where('id_warehouse', $directSalesDetailModel->directSalesBy->warehouse_id)
                            ->first()->harga_jual;
                        $harga = $harga;
                        $harga_ppn = (float) $harga * (ValueAddedTaxModel::first()->ppn / 100);
                        $harga_incl = (float) $harga + $harga_ppn;
                    } else {
                        $harga_incl = $directSalesDetailModel->price;
                    }
                    $harga_diskon = $harga_incl * ($directSalesDetailModel->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $directSalesDetailModel->discount_rp;
                    return number_format((float) $harga_final * $directSalesDetailModel->qty);
                })
                ->editColumn('total_price_excl', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->price == null) {
                        $harga = ProductCostModel::where('id_product', $directSalesDetailModel->product_id)
                            ->where('id_warehouse', $directSalesDetailModel->directSalesBy->warehouse_id)
                            ->first()->harga_jual;
                        $harga = $harga;
                        $harga_ppn = (float) $harga * (ValueAddedTaxModel::first()->ppn / 100);
                        $harga_incl = (float) $harga + $harga_ppn;
                    } else {
                        $harga_incl = $directSalesDetailModel->price;
                    }
                    $harga_diskon = $harga_incl * ($directSalesDetailModel->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $directSalesDetailModel->discount_rp;
                    return number_format((float) ($harga_final * $directSalesDetailModel->qty) / 1.11);
                })
                // ! get discount rp
                ->editColumn('discount_rp', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return number_format($directSalesDetailModel->discount_rp);
                })
                // ! get total excl ppn
                ->editColumn('total_excl', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return number_format((float) $directSalesDetailModel->directSalesBy->total_excl);
                })
                // ! get total ppn
                ->editColumn('total_ppn', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return number_format((float) $directSalesDetailModel->directSalesBy->total_ppn);
                })
                // ! get total incl ppn
                ->editColumn('total_incl', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return number_format((float) $directSalesDetailModel->directSalesBy->total_incl);
                })
                // ! get total incl ppn
                ->editColumn('total_return', function (DirectSalesDetailModel $directSalesDetailModel) {
                    $total_return = ReturnRetailModel::where('retail_id', $directSalesDetailModel->directSalesBy->id)->sum('total');
                    return number_format((float) $total_return);
                })
                // ! get other
                ->editColumn('other', function (DirectSalesDetailModel $directSalesDetailModel) {
                    if ($directSalesDetailModel->directSalesBy->other == null) {
                        return '-';
                    } else {
                        return $directSalesDetailModel->directSalesBy->other;
                    }
                })
                // ! get remark
                ->editColumn('remark', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->directSalesBy->remark;
                })
                // ! get created by
                ->editColumn('created_by', function (DirectSalesDetailModel $directSalesDetailModel) {
                    return $directSalesDetailModel->directSalesBy->createdBy->name;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Direct Sales Report',
            'car_brand' => $this->car_brand,
            'car_type' => $this->car_type,
            'motor_brand' => $this->motor_brand,
            'motor_type' => $this->motor_type,
            'customer' => $this->customer,
            'material_group' => $this->material,
            'product' => $this->product,
            'type' => $this->type,
            'warehouse' => $this->warehouse,
        ];

        return view('report.retail', $data);
    }

    // ! function report return retail
    public function reportReturnRetail(Request $request)
    {
        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $return = ReturnRetailDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($query) use ($request) {
                            $query->whereBetween('return_date', [$request->from_date, $request->to_date]);
                        });
                        $query->when($request->warehouse, function ($warehouse) use ($request) {
                            $warehouse->whereHas('retailBy', function ($q) use ($request) {
                                $q->where('warehouse_id', $request->warehouse);
                            });
                        });
                        $query->where('isapproved', 1);
                        $query->where('isreceived', 1);
                    })
                    ->whereHas('productBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id', $request->product);
                        });
                        //! query untuk relasi dari product ke submaterial
                        $query->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $query->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->returnBy->return_date;
                    });
            } else {
                $return = ReturnRetailDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) {
                        $query->where('return_date', date('Y-m-d'));
                        $query->where('isapproved', 1);
                        $query->where('isreceived', 1);
                    })
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->returnBy->return_date;
                    });
            }
            return datatables()
                ->of($return)

                ->editColumn('return_number', function ($data) {
                    return $data->returnBy->return_number;
                })
                ->editColumn('retail_id', function (ReturnRetailDetailModel $ReturnRetailDetailModel) {
                    return $ReturnRetailDetailModel->returnBy->retailBy->order_number;
                })
                ->editColumn('direct_by', function (ReturnRetailDetailModel $ReturnRetailDetailModel) {
                    return $ReturnRetailDetailModel->returnBy->retailBy->createdBy->name;
                })
                ->editColumn('return_date', function ($data) {
                    return date('d/M/Y', strtotime($data->returnBy->return_date));
                })
                ->editColumn('customer', function (ReturnRetailDetailModel $ReturnRetailDetailModel) {
                    if (is_numeric($ReturnRetailDetailModel->returnBy->retailBy->cust_name)) {
                        return $ReturnRetailDetailModel->returnBy->retailBy->customerBy->name_cust . ' (' . $ReturnRetailDetailModel->returnBy->retailBy->customerBy->code_cust . ')';
                    } else {
                        return $ReturnRetailDetailModel->returnBy->retailBy->cust_name;
                    }
                })
                ->editColumn('area', function (ReturnRetailDetailModel $ReturnRetailDetailModel) {
                    if (is_numeric($ReturnRetailDetailModel->returnBy->retailBy->cust_name)) {
                        return $ReturnRetailDetailModel->returnBy->retailBy->customerBy->areaBy->area_name;
                    } else {
                        return $ReturnRetailDetailModel->returnBy->retailBy->warehouseBy->areaBy->area_name;
                    }
                })
                ->editColumn('district', function (ReturnRetailDetailModel $ReturnRetailDetailModel) {
                    return $ReturnRetailDetailModel->returnBy->retailBy->district;
                })
                ->editColumn('material', function ($data) {
                    return $data->productBy->sub_materials->nama_sub_material;
                })
                ->editColumn('type', function ($data) {
                    return $data->productBy->sub_types->type_name;
                })
                ->editColumn('product', function ($data) {
                    return $data->productBy->nama_barang;
                })
                ->editColumn('price', function ($data) {
                    $harga_incl = $data->getDetail($data->returnBy->retail_id)->price;

                    $harga_diskon = $harga_incl * ($data->getDetail($data->returnBy->retail_id)->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $data->getDetail($data->returnBy->retail_id)->discount_rp;
                    return number_format((float) $harga_final * $data->qty);
                })
                ->editColumn('price_excl', function ($data) {
                    $harga_incl = $data->getDetail($data->returnBy->retail_id)->price;

                    $harga_diskon = $harga_incl * ($data->getDetail($data->returnBy->retail_id)->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $data->getDetail($data->returnBy->retail_id)->discount_rp;
                    return number_format((float) ($harga_final * $data->qty) / 1.11);
                })
                ->editColumn('total', function ($data) {
                    return number_format($data->returnBy->total);
                })
                ->editColumn('total_ppn', function ($data) {
                    return number_format(($data->returnBy->total / 1.11) * 0.11);
                })
                ->editColumn('total_exl', function ($data) {
                    return number_format($data->returnBy->total / 1.11);
                })
                ->editColumn('return_reason', function ($data) {
                    return $data->returnBy->return_reason;
                })
                ->editColumn('created_by', function ($data) {
                    return $data->returnBy->createdBy->name;
                })

                ->make(true);
        }

        $data = [
            'title' => 'Return Direct Sales Report',
            'material_group' => $this->material,
            'product' => $this->product,
            'type' => $this->type,
            'warehouse' => $this->warehouse,
        ];
        return view('report.retail_return', $data);
    }
    // ! function report purchase
    public function report_po(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $purchase = PurchaseOrderDetailModel::with('purchaseOrderBy', 'purchaseOrderBy.supplierBy', 'purchaseOrderBy.createdPurchaseOrder', 'purchaseOrderBy.warehouseBy', 'productBy')
                    ->whereHas('purchaseOrderBy', function ($query) {
                        $query->where('isapprove', 1);
                    })
                    ->whereHas('purchaseOrderBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($order_date) use ($request) {
                            $order_date->whereBetween('order_date', [$request->from_date, $request->to_date]);
                        });
                    })
                    ->whereHas('purchaseOrderBy.warehouseBy', function ($query) use ($request) {
                        $query->when($request->warehouse, function ($warehouse) use ($request) {
                            $warehouse->where('id', $request->warehouse);
                        });
                    })
                    ->whereHas('purchaseOrderBy.supplierBy', function ($query) use ($request) {
                        $query->when($request->supplier, function ($supplier) use ($request) {
                            $supplier->where('id', $request->supplier);
                        });
                    })
                    ->whereHas('productBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id', $request->product);
                        });
                        //! query untuk relasi dari product ke submaterial
                        $query->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $query->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })
                    ->get()
                    ->sortBy(function ($purchase) {
                        return $purchase->purchaseOrderBy->order_date;
                    });
            } else {
                $purchase = PurchaseOrderDetailModel::with('purchaseOrderBy', 'purchaseOrderBy.supplierBy', 'purchaseOrderBy.createdPurchaseOrder', 'purchaseOrderBy.warehouseBy', 'productBy')
                    ->whereHas('purchaseOrderBy', function ($query) {
                        $query->where('order_date', date('Y-m-d'));
                    })
                    ->whereHas('purchaseOrderBy', function ($query) {
                        $query->where('isapprove', 1);
                    })
                    ->get()
                    ->sortBy(function ($purchase) {
                        return $purchase->purchaseOrderBy->order_date;
                    });
            }

            return datatables()
                ->of($purchase)
                // ! get purchase order number
                ->editColumn('order_number', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->order_number;
                })
                // ! get order date
                ->editColumn('order_date', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return date('d F Y', strtotime($purchaseOrderDetailModel->purchaseOrderBy->order_date));
                })
                // ! get terms of payment
                ->editColumn('top', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->top;
                })
                // ! get due date
                ->editColumn('due_date', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return date('d F Y', strtotime($purchaseOrderDetailModel->purchaseOrderBy->due_date));
                })
                // ! get warehouse
                ->editColumn('warehouse_id', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->warehouseBy->warehouses;
                })
                // ! get supplier
                ->editColumn('supplier_id', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->supplierBy->nama_supplier;
                })
                // ! get status
                ->editColumn('isvalidated', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    if ($purchaseOrderDetailModel->purchaseOrderBy->isvalidated == 0) {
                        return 'Not Received';
                    } else {
                        return 'Received';
                    }
                })
                // ! get remark
                ->editColumn('remark', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->remark;
                })
                // ! get material group
                ->editColumn('sub_material', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->productBy->sub_materials->nama_sub_material;
                })
                // ! get type
                ->editColumn('sub_type', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->productBy->sub_types->type_name;
                })
                // ! get product
                ->editColumn('product', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->productBy->nama_barang;
                })
                // ! get total ex. ppn
                ->editColumn('total', function (purchaseOrderDetailModel $purchaseOrderDetailModel) {
                    // $total = Crypt::decryptString($purchaseOrderDetailModel->productBy->harga_beli) * $purchaseOrderDetailModel->qty;
                    $total = $purchaseOrderDetailModel->price * $purchaseOrderDetailModel->qty;
                    $disc = $total * ($purchaseOrderDetailModel->discount / 100);
                    $total = $total - $disc;

                    return number_format((float) $total, 0, '.', ',');
                })
                // ! get ppn
                ->editColumn('ppn', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    // $total = Crypt::decryptString($purchaseOrderDetailModel->productBy->harga_beli) * $purchaseOrderDetailModel->qty;
                    $total = $purchaseOrderDetailModel->price * $purchaseOrderDetailModel->qty;
                    // $disc = $total * ($purchaseOrderDetailModel->discount / 100);
                    $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $total;
                    $disc = $ppn * ($purchaseOrderDetailModel->discount / 100);
                    return number_format($ppn - $disc, 0, '.', ',');
                })
                // ! get total ppn
                ->editColumn('total_ppn', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    // $total = Crypt::decryptString($purchaseOrderDetailModel->productBy->harga_beli)  * $purchaseOrderDetailModel->qty;
                    $total = $purchaseOrderDetailModel->price * $purchaseOrderDetailModel->qty;

                    $ppn = (ValueAddedTaxModel::first()->ppn / 100) * $total;
                    $total_ppn = $total + $ppn;
                    $disc = $total_ppn * ($purchaseOrderDetailModel->discount / 100);
                    return number_format($total_ppn - $disc, 0, '.', ',');
                })
                // ! get created by
                ->editColumn('created_by', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->createdPurchaseOrder->name;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Purchase Order Report',
            'warehouse' => $this->warehouse,
            'suppliers' => $this->suppliers,
            'material_group' => $this->material,
            'product' => $this->product,
            'type' => $this->type,
        ];
        return view('report.po_report', $data);
    }

    public function report_po_safe(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $purchase = PurchaseOrderDetailModel::with('purchaseOrderBy', 'purchaseOrderBy.supplierBy', 'purchaseOrderBy.createdPurchaseOrder', 'purchaseOrderBy.warehouseBy', 'productBy')
                    ->whereHas('purchaseOrderBy', function ($query) {
                        $query->where('isapprove', 1);
                    })
                    ->whereHas('purchaseOrderBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($order_date) use ($request) {
                            $order_date->whereBetween('order_date', [$request->from_date, $request->to_date]);
                        });
                    })
                    ->whereHas('purchaseOrderBy.warehouseBy', function ($query) use ($request) {
                        $query->when($request->warehouse, function ($warehouse) use ($request) {
                            $warehouse->where('id', $request->warehouse);
                        });
                    })
                    ->whereHas('purchaseOrderBy.supplierBy', function ($query) use ($request) {
                        $query->when($request->supplier, function ($supplier) use ($request) {
                            $supplier->where('id', $request->supplier);
                        });
                    })
                    ->whereHas('productBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id', $request->product);
                        });
                        //! query untuk relasi dari product ke submaterial
                        $query->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $query->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })
                    ->get()
                    ->sortBy(function ($purchase) {
                        return $purchase->purchaseOrderBy->order_date;
                    });
            } else {
                $purchase = PurchaseOrderDetailModel::with('purchaseOrderBy', 'purchaseOrderBy.supplierBy', 'purchaseOrderBy.createdPurchaseOrder', 'purchaseOrderBy.warehouseBy', 'productBy')
                    ->whereHas('purchaseOrderBy', function ($query) {
                        $query->where('order_date', date('Y-m-d'));
                    })
                    ->whereHas('purchaseOrderBy', function ($query) {
                        $query->where('isapprove', 1);
                    })
                    ->get()
                    ->sortBy(function ($purchase) {
                        return $purchase->purchaseOrderBy->order_date;
                    });
            }

            return datatables()
                ->of($purchase)
                // ! get purchase order number
                ->editColumn('order_number', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->order_number;
                })
                // ! get order date
                ->editColumn('order_date', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return date('d F Y', strtotime($purchaseOrderDetailModel->purchaseOrderBy->order_date));
                })
                // ! get warehouse
                ->editColumn('warehouse_id', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->warehouseBy->warehouses;
                })
                // ! get supplier
                ->editColumn('supplier_id', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->supplierBy->nama_supplier;
                })
                // ! get status
                ->editColumn('isvalidated', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    if ($purchaseOrderDetailModel->purchaseOrderBy->isvalidated == 0) {
                        return 'Not Received';
                    } else {
                        return 'Received';
                    }
                })
                // ! get remark
                ->editColumn('remark', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->remark;
                })
                // ! get material group
                ->editColumn('sub_material', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->productBy->sub_materials->nama_sub_material;
                })
                // ! get type
                ->editColumn('sub_type', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->productBy->sub_types->type_name;
                })
                // ! get product
                ->editColumn('product', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->productBy->nama_barang;
                })
                // ! get created by
                ->editColumn('created_by', function (PurchaseOrderDetailModel $purchaseOrderDetailModel) {
                    return $purchaseOrderDetailModel->purchaseOrderBy->createdPurchaseOrder->name;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Purchase Order Report (Safe Mode)',
            'warehouse' => $this->warehouse,
            'suppliers' => $this->suppliers,
            'material_group' => $this->material,
            'product' => $this->product,
            'type' => $this->type,
        ];
        return view('report.po_report_safe', $data);
    }

    // ! report return non retail
    public function report_return(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $return = ReturnDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($query) use ($request) {
                            $query->whereBetween('return_date', [$request->from_date, $request->to_date]);
                        });
                        $query->when($request->warehouse, function ($warehouse) use ($request) {
                            $warehouse->whereHas('salesOrderBy', function ($q) use ($request) {
                                $q->where('warehouse_id', $request->warehouse);
                            });
                        });
                        $query->where('isapproved', 1);
                        $query->where('isreceived', 1);
                    })
                    ->whereHas('productBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id', $request->product);
                        });
                        //! query untuk relasi dari product ke submaterial
                        $query->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $query->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })

                    ->get()
                    ->sortBy(function ($return) {
                        return $return->returnBy->return_date;
                    });
            } else {
                $return = ReturnDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) {
                        $query->where('return_date', date('Y-m-d'));
                        $query->where('isapproved', 1);
                        $query->where('isreceived', 1);
                    })
                    ->get()
                    ->sortBy(function ($return) {
                        return $return->returnBy->return_date;
                    });
            }

            return datatables()
                ->of($return)
                ->editColumn('return_number', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->return_number;
                })
                ->editColumn('sales_order_id', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->salesOrderBy->order_number;
                })
                ->editColumn('sales_order_created', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->salesOrderBy->createdSalesOrder->name;
                })
                ->editColumn('return_date', function (ReturnDetailModel $returnDetailModel) {
                    return date('d/M/Y', strtotime($returnDetailModel->returnBy->return_date));
                })
                ->editColumn('customer', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->salesOrderBy->customerBy->name_cust . ' (' . $returnDetailModel->returnBy->salesOrderBy->customerBy->code_cust . ')';
                })
                ->editColumn('district', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->salesOrderBy->customerBy->city;
                })
                ->editColumn('area', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->salesOrderBy->customerBy->areaBy->area_name;
                })
                ->editColumn('price', function ($data) {
                    $harga_incl = $data->getPrice($data->returnBy->sales_order_id)->price;

                    $harga_diskon = $harga_incl * ($data->getPrice($data->returnBy->sales_order_id)->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $data->getPrice($data->returnBy->sales_order_id)->discount_rp;
                    return number_format((float) $harga_final * $data->qty);
                })
                ->editColumn('price_excl', function ($data) {
                    $harga_incl = $data->getPrice($data->returnBy->sales_order_id)->price;

                    $harga_diskon = $harga_incl * ($data->getPrice($data->returnBy->sales_order_id)->discount / 100);
                    $harga_final = $harga_incl - $harga_diskon - $data->getPrice($data->returnBy->sales_order_id)->discount_rp;
                    return number_format((float) ($harga_final * $data->qty) / 1.11);
                })
                ->editColumn('total', function (ReturnDetailModel $returnDetailModel) {
                    return number_format((float) $returnDetailModel->returnBy->total);
                })
                ->editColumn('ppn', function (ReturnDetailModel $returnDetailModel) {
                    return number_format(((float) ($returnDetailModel->returnBy->total / 1.11) * ValueAddedTaxModel::first()->ppn) / 100);
                })
                ->editColumn('total_excl', function (ReturnDetailModel $returnDetailModel) {
                    return number_format((float) $returnDetailModel->returnBy->total / 1.11);
                })
                ->editColumn('return_reason', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->return_reason;
                })
                ->editColumn('created_by', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->returnBy->createdBy->name;
                })
                ->editColumn('product', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->productBy->nama_barang;
                })
                ->editColumn('sub_material', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->productBy->sub_materials->nama_sub_material;
                })
                ->editColumn('sub_type', function (ReturnDetailModel $returnDetailModel) {
                    return $returnDetailModel->productBy->sub_types->type_name;
                })
                ->editColumn('discount', function (ReturnDetailModel $returnDetailModel) {
                    $diskon = 0;
                    $getdiskon = $returnDetailModel->returnBy->salesOrderBy->salesOrderDetailsBy;
                    foreach ($getdiskon as $dis) {
                        if ($dis->products_id == $returnDetailModel->product_id) {
                            $diskon = $dis->discount;
                        }
                    }
                    return $diskon;
                })
                ->editColumn('discount_rp', function (ReturnDetailModel $returnDetailModel) {
                    $diskon = 0;
                    $getdiskon = $returnDetailModel->returnBy->salesOrderBy->salesOrderDetailsBy;
                    foreach ($getdiskon as $dis) {
                        if ($dis->products_id == $returnDetailModel->product_id) {
                            $diskon = $dis->discount_rp;
                        }
                    }
                    return $diskon;
                })
                ->addIndexColumn()
                ->make(true);
        }

        $area = CustomerAreaModel::oldest('area_name')->get();
        $data = [
            'title' => 'Return Indirect Report',
            'product' => $this->product,
            'type' => $this->type,
            'material_group' => $this->material,
            'warehouse' => $this->warehouse,
        ];
        return view('report.return', $data);
    }

    // ! report return purchase
    public function report_return_purchase(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $return = ReturnPurchaseDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($query) use ($request) {
                            $query->whereBetween('return_date', [$request->from_date, $request->to_date]);
                        });
                    })
                    ->whereHas('productBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id', $request->product);
                        });
                        //! query untuk relasi dari product ke submaterial
                        $query->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $query->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })
                    ->get()
                    ->sortBy(function ($return) {
                        return $return->returnBy->return_date;
                    });
            } else {
                $return = ReturnPurchaseDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) {
                        $query->where('return_date', date('Y-m-d'));
                    })
                    ->get()
                    ->sortBy(function ($return) {
                        return $return->returnBy->return_date;
                    });
            }

            return datatables()
                ->of($return)
                ->editColumn('return_number', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->returnBy->return_number;
                })
                ->editColumn('purchase_order_id', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->returnBy->purchaseOrderBy->order_number;
                })
                ->editColumn('return_date', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return date('d/M/Y', strtotime($returnPurchaseDetailModel->returnBy->return_date));
                })
                ->editColumn('total', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    $total = (int) Crypt::decryptString($returnPurchaseDetailModel->productBy->harga_beli) * $returnPurchaseDetailModel->qty;

                    return number_format($total, 0, '.', ',');
                })
                ->editColumn('return_reason', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->returnBy->return_reason;
                })
                ->editColumn('created_by', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->returnBy->createdBy->name;
                })
                ->editColumn('product', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->productBy->nama_barang;
                })
                ->editColumn('sub_material', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->productBy->sub_materials->nama_sub_material;
                })
                ->editColumn('sub_type', function (ReturnPurchaseDetailModel $returnPurchaseDetailModel) {
                    return $returnPurchaseDetailModel->productBy->sub_types->type_name;
                })
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'Return Purchase Report',
            'product' => $this->product,
            'type' => $this->type,
            'material_group' => $this->material,
        ];
        return view('report.return_purchase', $data);
    }

    // ! report receivable
    public function report_receivable(Request $request)
    {
        $invoice = SalesOrderModel::with('customerBy', 'salesOrderDetailsBy', 'salesOrderCreditsBy', 'createdSalesOrder', 'returnBy')->where('isapprove', 'approve')->where('isPaid', 0)->where('paid_date', null)->where('isrejected', 0)->get();
        $retail = DirectSalesModel::select('*', DB::raw('cust_name AS customers_id'))->with('directSalesDetailBy', 'createdBy', 'customerBy', 'directSalesCreditBy')->where('isPaid', 0)->where('isrejected', 0)->get();
        if ($request->ajax()) {
            $all = $invoice
                ->concat($retail)
                ->when($request->from_date, function ($query) use ($request) {
                    return $query->whereBetween('order_date', [$request->from_date, $request->to_date]);
                })
                ->when($request->customer, function ($query) use ($request) {
                    return $query->where('customers_id', $request->customer[0]);
                })
                ->sortBy('order_date');
            return datatables()
                ->of($all)
                ->editColumn('day_passed', function ($data) {
                    $date1 = date_create($data->order_date);
                    $date2 = date_create(date('Y-m-d'));
                    $interval = date_diff($date2, $date1);

                    return $interval->format('%a');
                })
                ->editColumn('order_date', function ($data) {
                    return date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('due_date', function ($data) {
                    if ($data instanceof SalesOrderModel) {
                        return date('d-m-Y', strtotime($data->duedate));
                    } elseif ($data instanceof DirectSalesModel) {
                        if ($data->due_date == null) {
                            return date('d-m-Y', strtotime($data->order_date));
                        } else {
                            return date('d-m-Y', strtotime($data->due_date));
                        }
                    }
                })
                ->editColumn('customer', function ($data) {
                    if ($data instanceof SalesOrderModel) {
                        return $data->customerBy->name_cust;
                    } elseif ($data instanceof DirectSalesModel) {
                        if (is_numeric($data->cust_name)) {
                            return $data->customerBy->name_cust;
                        } else {
                            return $data->cust_name;
                        }
                    }
                })
                ->editColumn('receivable', function ($data) {
                    if ($data instanceof SalesOrderModel) {
                        $total_credit = $data->total_after_ppn;
                        $credit_paid = SalesOrderCreditModel::where('sales_order_id', $data->id)->sum('amount');
                        $total_return = ReturnModel::where('sales_order_id', $data->id)->sum('total');
                        $receivable = $total_credit - ($credit_paid + $total_return);
                        return number_format($receivable, 0, '.', ',');
                    } elseif ($data instanceof DirectSalesModel) {
                        $total_credit = $data->total_incl;
                        $credit_paid = DirectSalesCreditModel::where('direct_id', $data->id)->sum('amount');
                        $total_return = ReturnRetailModel::where('retail_id', $data->id)->sum('total');
                        $receivable = $total_credit - ($credit_paid + $total_return);
                        return number_format($receivable, 0, '.', ',');
                    }
                })
                ->addIndexColumn()
                ->make(true);
        }

        $all_customers = CustomerModel::oldest('name_cust')->get();
        $data = [
            'title' => 'Receivable Report Data',
            'customers' => $all_customers,
            // 'total' => number_format($total_receivable, 0, '.', ',')
        ];

        return view('report.receivable', $data);
    }

    public function report_receivable_indirect(Request $request)
    {
        if ($request->ajax()) {
            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            $invoice = SalesOrderModel::with('customerBy', 'salesOrderDetailsBy', 'salesOrderCreditsBy', 'createdSalesOrder', 'returnBy')
                ->whereIn('warehouse_id', $userWarehouseIds)
                ->where('isapprove', 'approve')
                ->where('isPaid', 0)
                ->where('paid_date', null)
                ->where('isrejected', 0)
                ->when($request->warehouse, function ($query) use ($request) {
                    return $query->where('warehouse_id', $request->warehouse);
                })
                ->when($request->customer, function ($query) use ($request) {
                    return $query->where('customers_id', $request->customer);
                })

                ->get()
                ->sortBy('order_date');
            $all = $invoice;

            return datatables()
                ->of($all)
                ->editColumn('day_passed', function ($data) {
                    $date1 = date_create($data->order_date);
                    $date2 = date_create(date('Y-m-d'));
                    $interval = date_diff($date2, $date1);

                    return $interval->format('%a');
                })
                ->editColumn('order_date', function ($data) {
                    return date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('due_date', function ($data) {
                    return date('d-m-Y', strtotime($data->duedate));
                })
                ->editColumn('day_passed', function ($data) {
                    // tanggal due date
                    $order_date = new DateTime($data->order_date);
                    $due_date = new DateTime($data->duedate);

                    $interval_due_date = $order_date->diff($due_date);
                    $interval_day_pass = $order_date->diff(date_create(date('Y-m-d')));

                    if ($interval_day_pass->days > $interval_due_date->days) {
                        return '<span class="fw-bold text-danger">' . $interval_day_pass->days . '</span>';
                    } else {
                        return '<span class="fw-bold text-success">' . $interval_day_pass->days . '</span>';
                    }
                })
                ->editColumn('customer', function ($data) {
                    return $data->customerBy->name_cust;
                })
                ->editColumn('order_number', function ($data) {
                    return '<div class="btn-group">
    <a href="javascript:void(0)" data-bs-toggle="modal" data-original-title="test"
        data-bs-target="#detailData' .
                        $data->id .
                        '" class=" text-nowrap code fw-bold text-success"
        type="text">' .
                        $data->order_number .
                        '</a> <span>&nbsp;</span>

</div>';
                    // $ppn = (ValueAddedTaxModel::first()->ppn / 100);

                    // return  view('report._option_ar_indirect', compact('data', 'ppn'))->render();
                })
                ->editColumn('receivable', function ($data) {
                    $total_credit = $data->total_after_ppn;
                    $credit_paid = SalesOrderCreditModel::where('sales_order_id', $data->id)->sum('amount');
                    $total_return = ReturnModel::where('sales_order_id', $data->id)->sum('total');
                    $receivable = $total_credit - ($credit_paid + $total_return);
                    return number_format($receivable, 0, '.', ',');
                })
                ->rawColumns(['day_passed', 'order_number'])

                ->addIndexColumn()
                ->make(true);
        }

        $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

        $all_customers = CustomerModel::oldest('name_cust')->get();
        $invoice = SalesOrderModel::with('customerBy', 'salesOrderDetailsBy', 'salesOrderCreditsBy', 'createdSalesOrder', 'returnBy')->whereIn('warehouse_id', $userWarehouseIds)->where('isapprove', 'approve')->where('isPaid', 0)->where('paid_date', null)->get()->sortBy('order_date');
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = [
            'title' => 'Indirect Receivable Report Data',
            'customers' => $all_customers,
            'warehouse' => $this->warehouse,
            'invoices' => $invoice,
            'ppn' => $ppn,
        ];

        return view('report.receivable_indirect', $data);
    }

    public function report_receivable_direct(Request $request)
    {
        if ($request->ajax()) {
            $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

            $retail = DirectSalesModel::with('directSalesDetailBy', 'createdBy', 'customerBy', 'directSalesCreditBy')
                ->where('isPaid', 0)
                ->where('isrejected', 0)
                ->where('isapproved', 1)
                ->whereIn('warehouse_id', $userWarehouseIds)
                ->when($request->customer, function ($query) use ($request) {
                    return $query->where('cust_name', $request->customer);
                })
                ->when($request->warehouse, function ($query) use ($request) {
                    return $query->where('warehouse_id', $request->warehouse);
                })
                ->get()
                ->sortBy('order_date');

            $all = $retail;

            return datatables()
                ->of($all)
                ->editColumn('day_passed', function ($data) {
                    // $date1 = date_create($data->order_date);
                    // $date2 = date_create(date('Y-m-d'));
                    // $interval = date_diff($date2, $date1);

                    // $due_date = new DateTime($data->duedate);

                    // $interval_due_date = $date1->diff($due_date);

                    // return $interval->format('%a');
                    // tanggal due date
                    $order_date = new DateTime($data->order_date);
                    if ($data->due_date != null) {
                        $due_date = new DateTime($data->due_date);
                        $interval_due_date = $order_date->diff($due_date);
                        $due_date_days = $interval_due_date->days;
                    } else {
                        $due_date_days = 7;
                    }

                    $interval_day_pass = $order_date->diff(date_create(date('Y-m-d')));
                    if ($interval_day_pass->days > $due_date_days) {
                        return '<span class="fw-bold text-danger">' . $interval_day_pass->days . '</span>';
                    } else {
                        return '<span class="fw-bold text-success">' . $interval_day_pass->days . '</span>';
                    }
                })
                ->editColumn('order_date', function ($data) {
                    return date('d-m-Y', strtotime($data->order_date));
                })
                ->editColumn('due_date', function ($data) {
                    if ($data->due_date == null) {
                        return date('d-m-Y', strtotime('+7 days', strtotime($data->order_date)));
                    } else {
                        return date('d-m-Y', strtotime($data->due_date));
                    }
                })
                ->editColumn('customer', function ($data) {
                    if (is_numeric($data->cust_name)) {
                        return $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust;
                    } else {
                        return $data->cust_name;
                    }
                })
                ->editColumn('order_number', function ($data) {
                    return '<div class="btn-group">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-original-title="test"
                            data-bs-target="#detailDirect' .
                        $data->id .
                        '" class=" text-nowrap code fw-bold text-success"
                            type="text">' .
                        $data->order_number .
                        '</a> <span>&nbsp;</span>
                    </div>';

                    // $ppn = (ValueAddedTaxModel::first()->ppn / 100);

                    // return  view('report._option_ar_direct', compact('data', 'ppn'))->render();
                })
                ->editColumn('receivable', function ($data) {
                    $total_credit = $data->total_incl;
                    $credit_paid = DirectSalesCreditModel::where('direct_id', $data->id)->sum('amount');
                    $total_return = ReturnRetailModel::where('retail_id', $data->id)->sum('total');
                    $receivable = $total_credit - ($credit_paid + $total_return);
                    return number_format($receivable, 0, '.', ',');
                })
                ->rawColumns(['order_number', 'day_passed'])
                ->addIndexColumn()
                ->make(true);
        }

        $userWarehouseIds = Auth::user()->userWarehouseBy->pluck('warehouse_id');

        $retail = DirectSalesModel::with('directSalesDetailBy', 'createdBy', 'customerBy', 'directSalesCreditBy')
            ->where('isPaid', 0)
            ->where('isapproved', 1)
            ->whereIn('warehouse_id', $userWarehouseIds)
            ->when($request->customer, function ($query) use ($request) {
                return $query->where('cust_name', $request->customer);
            })
            ->when($request->warehouse, function ($query) use ($request) {
                return $query->where('warehouse_id', $request->warehouse);
            })
            ->get()
            ->sortBy('order_date');
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $all_customers = CustomerModel::oldest('name_cust')->get();
        $data = [
            'title' => 'Direct Receivable Report Data',
            'customers' => $all_customers,
            'warehouses' => $this->warehouse,
            'retails' => $retail,
            'ppn' => $ppn,
        ];

        return view('report.receivable_direct', $data);
    }

    // ! report debt
    public function report_debt(Request $request)
    {
        if ($request->ajax()) {
            $purchase = PurchaseOrderModel::with('supplierBy', 'purchaseOrderDetailsBy', 'createdPurchaseOrder', 'warehouseBy', 'purchaseOrderCreditsBy')
                ->where('isapprove', 1)
                ->where('payment_method', 'credit')
                ->where('isPaid', 0)
                ->when($request->from_date, function ($query) use ($request) {
                    return $query->whereBetween('order_date', [$request->from_date, $request->to_date]);
                })
                ->when($request->warehouse_id, function ($query) use ($request) {
                    return $query->where('warehouse_id', $request->warehouse_id);
                })
                ->when($request->supplier_id, function ($query) use ($request) {
                    return $query->where('supplier_id', $request->supplier_id);
                })
                ->oldest('order_date')
                ->get();
            // }

            return datatables()
                ->of($purchase)
                ->editColumn('order_date', function ($data) {
                    return date('d-M-Y', strtotime($data->order_date));
                })
                ->editColumn('due_date', function ($data) {
                    return date('d-M-Y', strtotime($data->due_date));
                })
                ->editColumn('warehouse_id', function (PurchaseOrderModel $purchaseOrderModel) {
                    return $purchaseOrderModel->warehouseBy->warehouses;
                })
                ->editColumn('supplier_id', function (PurchaseOrderModel $purchaseOrderModel) {
                    return $purchaseOrderModel->supplierBy->nama_supplier;
                })
                ->editColumn('debt', function (PurchaseOrderModel $purchaseOrderModel) {
                    $total_credit = $purchaseOrderModel->total;
                    $credit_paid = PurchaseOrderCreditModel::where('purchase_order_id', $purchaseOrderModel->id)->sum('amount');
                    $total_return = ReturnPurchaseModel::where('purchase_order_id', $purchaseOrderModel->id)->sum('total');
                    $debt = $total_credit - ($credit_paid + $total_return);
                    return number_format($debt, 0, '.', ',');
                })
                ->addIndexColumn()
                ->make(true);
        }
        $warehouse = $this->warehouse;
        $supplier = $this->suppliers;
        $data = [
            'title' => 'Debt Report Data',
            'warehouse' => $warehouse,
            'suppliers' => $supplier,
        ];

        return view('report.debt', $data);
    }

    public function report_settlement_direct(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $invoice = DirectSalesCreditModel::with('directSalesBy', 'directSalesBy.customerBy', 'directSalesBy.directSalesDetailBy', 'directSalesBy.directSalesCreditBy', 'directSalesBy.createdBy', 'directSalesBy.directSalesReturnBy')
            ->whereHas('directSalesBy', function ($query) use ($request, $currentMonth) {
                // $query->where('isPaid', 1);
                // $query->where('paid_date', '!=', null);
                $query->when($request->area, function ($query) use ($request) {
                    return $query->whereHas('customerBy', function ($q) use ($request) {
                        $q->where('area_cust_id', $request->area);
                    });
                });
                $query->when($request->category, function ($query) use ($request) {
                    return $query->whereHas('customerBy', function ($q) use ($request) {
                        $q->where('category_cust_id', $request->category);
                    });
                });

                $query->when($request->customer, function ($query) use ($request) {
                    return $query->where('cust_name', $request->customer[0]);
                });
            })
            ->when($request->payment, function ($query) use ($request) {
                return $query->where('payment_method', $request->payment);
            })
            ->when(
                $request->from_date,
                function ($query) use ($request) {
                    return $query->whereBetween('payment_date', [$request->from_date, $request->to_date]);
                },
                function ($query) use ($currentMonth, $currentYear) {
                    return $query->whereMonth('payment_date', $currentMonth)->whereYear('payment_date', $currentYear);
                },
            )
            ->get()
            ->sortByDesc(function ($q) {
                return [$q->payment_date, $q->created_at];
            });
        // $retail = DirectSalesModel::select('*', DB::raw("cust_name AS customers_id"))
        //     ->with('directSalesDetailBy', 'createdBy', 'customerBy', 'directSalesCreditBy')
        //     ->where(function ($query) {
        //         // Add a whereRaw clause to filter results where cust_name is numeric
        //         $query->whereRaw('cust_name REGEXP "^[0-9]+$"');
        //     })
        //     ->where('isPaid', 1)
        //     ->oldest('order_date')
        //     ->where('paid_date', '!=', null)
        //     ->when($request->area, function ($query) use ($request) {
        //         return $query->whereHas('customerBy', function ($q) use ($request) {
        //             $q->where('area_cust_id', $request->area);
        //         });
        //     })
        //     ->when($request->category, function ($query) use ($request) {
        //         return $query->whereHas('customerBy', function ($q) use ($request) {
        //             $q->where('category_cust_id', $request->category);
        //         });
        //     })
        //     ->get();
        if ($request->ajax()) {
            return datatables()
                ->of($invoice)
                ->editColumn('order_date', function ($data) {
                    return date('d/M/Y', strtotime($data->directSalesBy->order_date));
                })
                ->editColumn('order_number', function ($data) {
                    return $data->directSalesBy->order_number;
                })
                ->editColumn('settle_date', function ($data) {
                    return date('d/M/Y', strtotime($data->payment_date));
                })
                ->editColumn('amount', function ($data) {
                    return number_format($data->amount);
                })
                // ->editColumn('paid_date', function ($data) {
                //     return date('d/M/Y', strtotime($data->directSalesBy->paid_date));
                // })
                ->editColumn('customer', function ($data) {
                    if (is_numeric($data->directSalesBy->cust_name)) {
                        return $data->directSalesBy->customerBy->code_cust . ' - ' . $data->directSalesBy->customerBy->name_cust;
                    } else {
                        return $data->directSalesBy->cust_name;
                    }
                })
                ->editColumn('settlement_period', function ($data) {
                    $date1 = date_create($data->directSalesBy->order_date);
                    $date2 = date_create($data->directSalesBy->paid_date);
                    $interval = date_diff($date2, $date1);

                    return $interval->format('%a');
                })
                ->editColumn('payment_recipient', function ($data) {
                    $journal_detail = JournalDetail::where('journal_id', $data->journal_id)
                        ->where('status', 1)
                        ->where('debit', '>', 0)
                        ->first();
                    return $journal_detail?->coa?->name;
                })
                ->editColumn('total_incl', function ($data) {
                    return number_format($data->directSalesBy->total_incl);
                })
                ->editColumn('remain', function ($data) {
                    $id = $data->direct_id;
                    $amount = DirectSalesCreditModel::where('direct_id', $id)->sum('amount');
                    $remain = $data->directSalesBy->total_incl - $amount;
                    return number_format($remain);
                })
                ->addIndexColumn()
                ->make(true);
        }

        $all_customers = CustomerModel::oldest('name_cust')->get();
        $area = CustomerAreaModel::oldest('area_name')->get();
        $category = CustomerCategoriesModel::oldest('category_name')->get();
        $data = [
            'title' => 'Direct Settlement Report Data',
            'customers' => $all_customers,
            'area' => $area,
            'categories' => $category,
        ];

        return view('report.settlement_direct', $data);
    }

    public function report_settlement(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $invoice = SalesOrderCreditModel::with('salesorders', 'salesorders.customerBy', 'salesorders.salesOrderDetailsBy', 'salesorders.salesOrderCreditsBy', 'salesorders.createdSalesOrder', 'salesorders.returnBy')
            ->whereHas('salesorders', function ($query) use ($request, $currentMonth) {
                // $query->where('isPaid', 1);
                // $query->where('paid_date', '!=', null);
                // $query->whereMonth('order_date', $currentMonth);
                $query->when($request->area, function ($query) use ($request) {
                    return $query->whereHas('customerBy', function ($q) use ($request) {
                        $q->where('area_cust_id', $request->area);
                    });
                });
                $query->when($request->category, function ($query) use ($request) {
                    return $query->whereHas('customerBy', function ($q) use ($request) {
                        $q->where('category_cust_id', $request->category);
                    });
                });

                $query->when($request->customer, function ($query) use ($request) {
                    return $query->where('customers_id', $request->customer[0]);
                });
            })
            ->when($request->payment, function ($query) use ($request) {
                return $query->where('payment_method', $request->payment);
            })
            ->when(
                $request->from_date,
                function ($query) use ($request) {
                    return $query->whereBetween('payment_date', [$request->from_date, $request->to_date]);
                },
                function ($query) use ($currentMonth, $currentYear) {
                    return $query->whereMonth('payment_date', $currentMonth)->whereYear('payment_date', $currentYear);
                },
            )
            ->get()
            ->sortByDesc(function ($q) {
                return [$q->payment_date, $q->created_at];
            });
        // $retail = DirectSalesModel::select('*', DB::raw("cust_name AS customers_id"))
        //     ->with('directSalesDetailBy', 'createdBy', 'customerBy', 'directSalesCreditBy')
        //     ->where(function ($query) {
        //         // Add a whereRaw clause to filter results where cust_name is numeric
        //         $query->whereRaw('cust_name REGEXP "^[0-9]+$"');
        //     })
        //     ->where('isPaid', 1)
        //     ->oldest('order_date')
        //     ->where('paid_date', '!=', null)
        //     ->when($request->area, function ($query) use ($request) {
        //         return $query->whereHas('customerBy', function ($q) use ($request) {
        //             $q->where('area_cust_id', $request->area);
        //         });
        //     })
        //     ->when($request->category, function ($query) use ($request) {
        //         return $query->whereHas('customerBy', function ($q) use ($request) {
        //             $q->where('category_cust_id', $request->category);
        //         });
        //     })
        //     ->get();
        if ($request->ajax()) {
            return datatables()
                ->of($invoice)
                ->editColumn('order_date', function ($data) {
                    return date('d/M/Y', strtotime($data->salesorders->order_date));
                })
                ->editColumn('order_number', function ($data) {
                    return $data->salesorders->order_number;
                })
                ->editColumn('settle_date', function ($data) {
                    return date('d/M/Y', strtotime($data->payment_date));
                })
                ->editColumn('amount', function ($data) {
                    return number_format($data->amount);
                })
                // ->editColumn('paid_date', function ($data) {
                //     return date('d/M/Y', strtotime($data->salesorders->paid_date));
                // })
                ->editColumn('customer', function ($data) {
                    return $data->salesorders->customerBy->code_cust . ' - ' . $data->salesorders->customerBy->name_cust;
                })
                ->editColumn('payment_recipient', function ($data) {
                    $journal_detail = JournalDetail::where('journal_id', $data->journal_id)
                        ->where('debit', '>', 0)
                        ->where('status', 1)
                        ->first();
                    return $journal_detail?->coa?->name;
                })
                ->editColumn('settlement_period', function ($data) {
                    $date1 = date_create($data->salesorders->order_date);
                    $date2 = date_create($data->salesorders->paid_date);
                    $interval = date_diff($date2, $date1);

                    return $interval->format('%a');
                })
                ->editColumn('total_incl', function ($data) {
                    return number_format($data->salesorders->total_after_ppn);
                })
                ->editColumn('remain', function ($data) {
                    $id = $data->sales_order_id;
                    $amount = SalesOrderCreditModel::where('sales_order_id', $id)->sum('amount');
                    $remain = $data->salesorders->total_after_ppn - $amount;
                    return number_format($remain);
                })
                ->addIndexColumn()
                ->make(true);
        }

        $all_customers = CustomerModel::oldest('name_cust')->get();
        $area = CustomerAreaModel::oldest('area_name')->get();
        $category = CustomerCategoriesModel::oldest('category_name')->get();
        $data = [
            'title' => 'Indirect Settlement Report Data',
            'customers' => $all_customers,
            'area' => $area,
            'categories' => $category,
        ];

        return view('report.settlement', $data);
    }

    public function report_settlement_purchase(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $invoice = PurchaseOrderCreditModel::with('purchaseorders', 'purchaseorders.supplierBy', 'purchaseorders.purchaseOrderDetailsBy', 'purchaseorders.purchaseOrderCreditsBy', 'purchaseorders.createdPurchaseOrder', 'purchaseorders.purchaseOrderReturnBy')
            ->whereHas('purchaseorders', function ($query) use ($request, $currentMonth, $currentYear) {
                $query->where('isPaid', 1);
                // $query->when($request->area, function ($query) use ($request) {
                //     return $query->whereHas('supplierBy', function ($q) use ($request) {
                //         $q->where('area_cust_id', $request->area);
                //     });
                // });
                // $query->when($request->category, function ($query) use ($request) {
                //     return $query->whereHas('supplierBy', function ($q) use ($request) {
                //         $q->where('category_cust_id', $request->category);
                //     });
                // });
                $query->when(
                    $request->from_date,
                    function ($query) use ($request) {
                        return $query->whereBetween('paid_date', [$request->from_date, $request->to_date]);
                    },
                    function ($query) use ($currentMonth, $currentYear) {
                        return $query->whereMonth('paid_date', $currentMonth)->whereYear('paid_date', $currentYear);
                    },
                );
                $query->when($request->supplier, function ($query) use ($request) {
                    return $query->where('supplier_id', $request->supplier[0]);
                });
            })
            ->get()
            ->sortByDesc(function ($q) {
                return [$q->payment_date, $q->created_at];
                // return $q->purchaseorders->paid_date;
            });

        if ($request->ajax()) {
            return datatables()
                ->of($invoice)
                ->editColumn('order_date', function ($data) {
                    return date('d/M/Y', strtotime($data->purchaseorders->order_date));
                })
                ->editColumn('order_number', function ($data) {
                    return $data->purchaseorders->order_number;
                })
                // ->editColumn('settle_date', function ($data) {
                //     return date('d/M/Y', strtotime($data->payment_date));
                // })
                // ->editColumn('amount', function ($data) {
                //     return number_format($data->amount);
                // })
                ->editColumn('paid_date', function ($data) {
                    return date('d/M/Y', strtotime($data->purchaseorders->paid_date));
                })
                ->editColumn('supplier', function ($data) {
                    return $data->purchaseorders->supplierBy->nama_supplier;
                })
                // ->editColumn('settlement_period', function ($data) {
                //     $date1 = date_create($data->directSalesBy->order_date);
                //     $date2 = date_create($data->directSalesBy->paid_date);
                //     $interval = date_diff($date2, $date1);

                //     return $interval->format('%a');
                // })
                ->editColumn('total', function ($data) {
                    return number_format($data->purchaseorders->total);
                })
                ->addIndexColumn()
                ->make(true);
        }

        $all_suppliers = SuppliersModel::oldest('nama_supplier')->get();
        // $area = CustomerAreaModel::oldest('area_name')->get();
        // $category = CustomerCategoriesModel::oldest('category_name')->get();
        $data = [
            'title' => 'AP Settlement Report Data',
            'suppliers' => $all_suppliers,
            // 'area' => $area,
            // 'categories' => $category
        ];

        return view('report.settlement_purchase', $data);
    }

    public function report_credit_limit(Request $request)
    {
        $invoice = SalesOrderModel::where('isPaid', 0)->where('paid_date', null)->where('isrejected', 0)->select('customers_id')->get();
        $invoice_customers = array_column($invoice->toArray(), 'customers_id');

        $retail = DirectSalesModel::with('directSalesDetailBy', 'createdBy', 'customerBy', 'directSalesCreditBy')->where('isPaid', 0)->where('isrejected', 0)->where('isapproved', 1)->where('paid_date', null)->select('cust_name')->whereRaw('cust_name REGEXP "^[0-9]+$"')->get();
        $invoice_retail = array_column($retail->toArray(), 'cust_name');
        $merge_invoice = array_merge($invoice_customers, $invoice_retail);
        $get_unique = array_unique($merge_invoice);

        $customer_has_credit = CustomerModel
            // whereIn('id', $get_unique)
            ::when($request->area, function ($query) use ($request) {
                return $query->where('area_cust_id', $request->area);
            })
            ->when($request->category, function ($query) use ($request) {
                return $query->where('category_cust_id', $request->category);
            })
            ->oldest('name_cust')
            ->get();
        if ($request->ajax()) {
            return datatables()
                ->of($customer_has_credit)
                ->editColumn('credit_total', function ($data) {
                    $total_so = SalesOrderModel::where('customers_id', $data->id)
                        ->where('isPaid', 0)
                        ->where('isrejected', 0)
                        ->where('paid_date', null)
                        ->sum('total_after_ppn');

                    $total_retail = DirectSalesModel::where('cust_name', $data->id)
                        ->where('isPaid', 0)
                        ->where('isapproved', 1)
                        ->where('isrejected', 0)
                        ->where('paid_date', null)
                        ->sum('total_incl');
                    return number_format(round($total_so + $total_retail), 0, '.', ',');
                })
                ->editColumn('credit_limit', function ($data) {
                    return number_format($data->credit_limit, 0, '.', ',');
                })
                ->editColumn('name_cust', function ($data) {
                    return $data->code_cust . ' - ' . $data->name_cust;
                })
                ->editColumn('category_cust', function ($data) {
                    if ($data->categoryBy != null) {
                        return $data->categoryBy->category_name;
                    } else {
                        return '-';
                    }
                })
                ->addIndexColumn()
                ->make(true);
        }

        $area = CustomerAreaModel::oldest('area_name')->get();
        $category = CustomerCategoriesModel::oldest('category_name')->get();

        $data = [
            'title' => 'Credit Limit Report Data',
            'area' => $area,
            'categories' => $category,
        ];

        return view('report.credit_limit', $data);
    }

    // ! Report Claim
    public function reportClaim(Request $request)
    {
        // dd($invoice);
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $invoice = AccuClaimModel::with('productSales', 'carBrandBy', 'carTypeBy', 'loanBy', 'createdBy', 'accuClaimDetailsBy')
                    ->when($request->from_date, function ($query) use ($request) {
                        $query->whereBetween('claim_date', [$request->from_date, $request->to_date]);
                    })
                    ->when($request->car_brand, function ($car_brand) use ($request) {
                        $car_brand->where('car_brand_id', $request->car_brand);
                    })
                    ->when($request->car_type, function ($car_type) use ($request) {
                        $car_type->where('car_type_id', $request->car_type);
                    })
                    ->when($request->product, function ($product) use ($request) {
                        $product->where('product_id', $request->product);
                    })
                    // customer
                    ->when($request->customer, function ($customer) use ($request) {
                        $customer->where('customer_id', $request->customer);
                    })
                    //warehouse
                    ->when($request->warehouse, function ($warehouse) use ($request) {
                        $warehouse->where('warehouse_id', $request->warehouse);
                    })

                    // ->whereHas('loanBy', function ($query) use ($request) {
                    //     $query->when($request->product_loan, function ($product_loan) use ($request) {
                    //         $product_loan->where('id', $request->product_loan);
                    //     });
                    // })
                    ->whereHas('productSales', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id', $request->product);
                        });
                        //! query untuk relasi dari product ke submaterial
                        $query->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $query->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })
                    ->where('result', '!=', '')
                    ->latest()
                    ->get();
            } else {
                $invoice = AccuClaimModel::with('productSales', 'carBrandBy', 'carTypeBy', 'loanBy', 'createdBy', 'accuClaimDetailsBy')->where('claim_date', date('Y-m-d'))->where('status', 1)->where('result', '!=', '')->latest()->get();
            }
            return datatables()
                ->of($invoice)
                ->editColumn('e_submittedBy', function (AccuClaimModel $AccuClaimModel) {
                    return $AccuClaimModel->createdBy->name;
                })
                ->editColumn('start_date', function (AccuClaimModel $AccuClaimModel) {
                    return date('d F Y H:i', strtotime($AccuClaimModel->created_at));
                })
                ->editColumn('finish_date', function (AccuClaimModel $AccuClaimModel) {
                    return date('d F Y H:i', strtotime($AccuClaimModel->updated_at));
                })

                ->editColumn('customer_id', function (AccuClaimModel $AccuClaimModel) {
                    if (is_numeric($AccuClaimModel->customer_id)) {
                        return $AccuClaimModel->customerBy->name_cust;
                    } else {
                        return $AccuClaimModel->customer_id;
                    }
                })
                ->editColumn('customer_name', function (AccuClaimModel $AccuClaimModel) {
                    return $AccuClaimModel->sub_name;
                })
                ->editColumn('email', function (AccuClaimModel $AccuClaimModel) {
                    if ($AccuClaimModel->email == null) {
                        return '-';
                    } else {
                        return $AccuClaimModel->email;
                    }
                })
                ->editColumn('mutation_number', function (AccuClaimModel $AccuClaimModel) {
                    if ($AccuClaimModel->mutation_number == null) {
                        return '-';
                    } else {
                        return $AccuClaimModel->mutation_number;
                    }
                })
                ->editColumn('material', function (AccuClaimModel $AccuClaimModel) {
                    return $AccuClaimModel->productSales->sub_materials->nama_sub_material;
                })
                ->editColumn('type', function (AccuClaimModel $AccuClaimModel) {
                    return $AccuClaimModel->productSales->sub_types->type_name;
                })
                ->editColumn('product_id', function (AccuClaimModel $AccuClaimModel) {
                    return $AccuClaimModel->productSales->nama_barang;
                })

                ->editColumn('loan_id', function (AccuClaimModel $AccuClaimModel) {
                    if ($AccuClaimModel->loan_id == null) {
                        return '-';
                    } else {
                        return $AccuClaimModel->loanBy->sub_materials->nama_sub_material . ' ' . $AccuClaimModel->loanBy->sub_types->type_name . ' ' . $AccuClaimModel->loanBy->nama_barang;
                    }
                })
                ->editColumn('motor_brand_id', function (AccuClaimModel $AccuClaimModel) {
                    if (is_numeric($AccuClaimModel->motor_brand_id)) {
                        return $AccuClaimModel->motorBrandBy->name_brand;
                    } else {
                        return $AccuClaimModel->motor_brand_id;
                    }
                    // return $AccuClaimModel->carBrandBy->car_brand;
                })
                ->editColumn('motor_type_id', function (AccuClaimModel $AccuClaimModel) {
                    if (is_numeric($AccuClaimModel->motor_type_id)) {
                        return $AccuClaimModel->motorTypeBy->name_type;
                    } else {
                        return $AccuClaimModel->motor_type_id;
                    }
                    // return $AccuClaimModel->carTypeBy->car_type;
                })
                ->editColumn('car_brand_id', function (AccuClaimModel $AccuClaimModel) {
                    if (is_numeric($AccuClaimModel->car_brand_id)) {
                        return $AccuClaimModel->carBrandBy->car_brand;
                    } else {
                        return $AccuClaimModel->car_brand_id;
                    }
                    // return $AccuClaimModel->carBrandBy->car_brand;
                })
                ->editColumn('car_type_id', function (AccuClaimModel $AccuClaimModel) {
                    if (is_numeric($AccuClaimModel->car_type_id)) {
                        return $AccuClaimModel->carTypeBy->car_type;
                    } else {
                        return $AccuClaimModel->car_type_id;
                    }
                    // return $AccuClaimModel->carTypeBy->car_type;
                })

                ->editColumn('diagnosa', function (AccuClaimModel $AccuClaimModel) {
                    $detail = AccuClaimDetailModel::where('id_accu_claim', $AccuClaimModel->id)->get();

                    $diagnosa = '';
                    foreach ($detail as $key => $value) {
                        $diagnosa .= $value->diagnosa . ', ';
                    }
                    return $diagnosa;

                    // return $valDiagnosa;
                })
                ->editColumn('plate_number', function (AccuClaimModel $AccuClaimModel) {
                    return '<div class="text-uppercase"> ' . $AccuClaimModel->plate_number . '</div>';
                })
                ->editColumn('cost', function (AccuClaimModel $AccuClaimModel) {
                    return number_format($AccuClaimModel->cost);
                })

                ->rawColumns(['plate_number', 'diagnosa', 'cost', 'e_submittedBy'])
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Battery Claim Report',
            'car_brand' => $this->car_brand,
            'car_type' => $this->car_type,
            'motor_brand' => $this->motor_brand,
            'motor_type' => $this->motor_type,
            'customer' => $this->customer,
            'material_group' => $this->material,
            'product' => $this->product,
            'type' => $this->type,
            'warehouse' => $this->warehouse,
        ];
        return view('report.claim_report', $data);
    }

    // ! report mutation
    public function report_mutation(Request $request)
    {
        if ($request->ajax()) {
            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', Auth::user()->warehouse_id)
                ->first();
            if (!empty($request->from_date)) {
                $mutation = StockMutationDetailModel::with('stockMutationBy', 'productBy', 'stockMutationBy.fromWarehouse', 'stockMutationBy.toWarehouse', 'productBy.materials', 'productBy.sub_materials', 'productBy.sub_types')
                    ->whereHas('stockMutationBy', function ($query) use ($request) {
                        $query->where('isapprove', 1);
                        $query->when($request->from_date, function ($query) use ($request) {
                            $query->whereBetween('mutation_date', [$request->from_date, $request->to_date]);
                        });
                        $query->whereHas('fromWarehouse', function ($query) use ($request) {
                            $query->when($request->from_warehouse, function ($query) use ($request) {
                                $query->where('id', $request->from_warehouse);
                            });
                        });
                        $query->whereHas('toWarehouse', function ($query) use ($request) {
                            $query->when($request->to_warehouse, function ($query) use ($request) {
                                $query->where('id', $request->to_warehouse);
                            });
                        });
                    })
                    ->whereHas('productBy', function ($q) use ($request) {
                        //! get product
                        $q->when($request->product, function ($query) use ($request) {
                            $query->where('id', $request->product);
                        });
                        //! query untuk relasi dari product ke submaterial
                        $q->whereHas('materials', function ($q) use ($request) {
                            $q->when($request->group, function ($query) use ($request) {
                                $query->where('id', $request->group);
                            });
                        });
                        $q->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $q->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })
                    ->get()
                    ->sortBy(function ($mutation) {
                        return $mutation->stockMutationBy->mutation_date;
                    });
            } else {
                $mutation = StockMutationDetailModel::with('stockMutationBy', 'productBy', 'stockMutationBy.fromWarehouse', 'stockMutationBy.toWarehouse', 'productBy.materials', 'productBy.sub_materials', 'productBy.sub_types')
                    ->whereHas('stockMutationBy', function ($query) {
                        $query->where('isapprove', 1);
                    })
                    ->whereHas('stockMutationBy', function ($query) use ($kode_area) {
                        $query->where('mutation_number', 'like', "%$kode_area->area_code%");
                    })
                    ->whereHas('stockMutationBy', function ($query) {
                        $query->where('mutation_date', date('Y-m-d'));
                    })
                    ->get()
                    ->sortBy(function ($mutation) {
                        return $mutation->stockMutationBy->mutation_date;
                    });
            }

            return datatables()
                ->of($mutation)
                ->editColumn('mutation_number', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return $stockMutationDetailModel->stockMutationBy->mutation_number;
                })
                ->editColumn('mutation_date', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return date('d/M/Y', strtotime($stockMutationDetailModel->stockMutationBy->mutation_date));
                })
                ->editColumn('from', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return $stockMutationDetailModel->stockMutationBy->fromWarehouse->warehouses;
                })
                ->editColumn('to', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return $stockMutationDetailModel->stockMutationBy->toWarehouse->warehouses;
                })
                ->editColumn('remark', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return $stockMutationDetailModel->stockMutationBy->remark;
                })
                ->editColumn('note', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return $stockMutationDetailModel->note;
                })
                ->editColumn('material', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return $stockMutationDetailModel->productBy->materials->nama_material;
                })
                ->editColumn('sub_material', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return $stockMutationDetailModel->productBy->sub_materials->nama_sub_material;
                })
                ->editColumn('sub_type', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return $stockMutationDetailModel->productBy->sub_types->type_name;
                })
                ->editColumn('product', function (StockMutationDetailModel $stockMutationDetailModel) {
                    return $stockMutationDetailModel->productBy->nama_barang;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Mutation Report Data',
            'all_warehouse' => $this->all_warehouse,
            'material_group' => $this->material,
            'group' => $this->group,
            'type' => $this->type,
            'product' => $this->product,
        ];

        return view('report.mutation', $data);
    }

    // ! trade in report
    public function reportTradeIn(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $invoice = TradeInDetailModel::with('tradeInOrderBy', 'productTradeIn')
                    ->whereHas('tradeInOrderBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($query) use ($request) {
                            $query->whereBetween('trade_in_date', [$request->from_date, $request->to_date]);
                        });
                        $query->when($request->warehouse, function ($query) use ($request) {
                            $query->where('warehouse_id', $request->warehouse);
                        });
                    })
                    ->whereHas('productTradeIn', function ($query) use ($request) {
                        $query->when($request->product, function ($query) use ($request) {
                            $query->where('product_trade_in', $request->product);
                        });
                    })

                    ->get()
                    ->sortBy(function ($trade) {
                        return $trade->productTradeIn->trade_in_date;
                    });
            } else {
                $invoice = TradeInDetailModel::with('tradeInOrderBy', 'productTradeIn')
                    ->whereHas('tradeInOrderBy', function ($query) {
                        $query->where('trade_in_date', date('Y-m-d'));
                    })
                    ->get()
                    ->sortBy(function ($trade) {
                        return $trade->productTradeIn->trade_in_date;
                    });
            }

            return datatables()
                ->of($invoice)
                ->editColumn('trade_in_number', function (TradeInDetailModel $TradeInDetailModel) {
                    return $TradeInDetailModel->tradeInOrderBy->trade_in_number;
                })
                ->editColumn('ref_number', function (TradeInDetailModel $TradeInDetailModel) {
                    return $TradeInDetailModel->tradeInOrderBy->retail_order_number;
                })
                ->editColumn('trade_in_date', function (TradeInDetailModel $TradeInDetailModel) {
                    return date('d/M/Y', strtotime($TradeInDetailModel->tradeInOrderBy->trade_in_date));
                })
                ->editColumn('customer', function (TradeInDetailModel $TradeInDetailModel) {
                    if ($TradeInDetailModel->tradeInOrderBy->retailBy) {
                        $getCustomer = $TradeInDetailModel->tradeInOrderBy->retailBy->cust_name;
                        if (is_numeric($getCustomer)) {
                            return $TradeInDetailModel->tradeInOrderBy->retailBy->customerBy->code_cust . ' - ' . $TradeInDetailModel->tradeInOrderBy->retailBy->customerBy->name_cust;
                        } else {
                            return $getCustomer;
                        }
                    } else {
                        return $TradeInDetailModel->tradeInOrderBy->retail_order_number;
                    }
                })
                ->editColumn('customer_nik', function (TradeInDetailModel $TradeInDetailModel) {
                    return $TradeInDetailModel->tradeInOrderBy->customer_nik;
                })
                ->editColumn('customer_phone', function (TradeInDetailModel $TradeInDetailModel) {
                    return $TradeInDetailModel->tradeInOrderBy->customer_phone;
                })
                ->editColumn('customer_email', function (TradeInDetailModel $TradeInDetailModel) {
                    return $TradeInDetailModel->tradeInOrderBy->customer_email;
                })
                ->editColumn('product_trade_in', function (TradeInDetailModel $TradeInDetailModel) {
                    return $TradeInDetailModel->productTradeIn->name_product_trade_in;
                })
                ->editColumn('total', function (TradeInDetailModel $TradeInDetailModel) {
                    return number_format($TradeInDetailModel->tradeInOrderBy->total, 0, '.', ',');
                })
                ->editColumn('createdBy', function (TradeInDetailModel $TradeInDetailModel) {
                    return $TradeInDetailModel->tradeInOrderBy->tradeBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addIndexColumn()
                ->make(true);
        }
        $warehouse__ = WarehouseModel::with('typeBy')
            ->whereHas('typeBy', function ($query) {
                $query->where('type', '7');
            })
            ->oldest('warehouses')
            ->get();
        $data = [
            'title' => 'Trade-In Purchase Report',
            'trade_product' => $this->trade_product,
            'warehouse' => $warehouse__,
        ];
        return view('report.trade_report', $data);
    }

    // ! second sale report
    public function reportSecondSale(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $invoice = SecondSaleDetailModel::with('secondProduct', 'second_sale')
                    ->whereHas('second_sale', function ($query) use ($request) {
                        $query->when($request->from_date, function ($query) use ($request) {
                            $query->whereBetween('second_sale_date', [$request->from_date, $request->to_date]);
                        });
                    })
                    ->whereHas('secondProduct', function ($query) use ($request) {
                        $query->when($request->product, function ($query) use ($request) {
                            $query->where('product_second_id', $request->product);
                        });
                    })
                    ->get()
                    ->sortBy(function ($sale) {
                        return $sale->second_sale->second_sale_date;
                    });
            } else {
                $invoice = SecondSaleDetailModel::with('secondProduct', 'second_sale')
                    ->whereHas('second_sale', function ($query) {
                        $query->where('second_sale_date', date('Y-m-d'));
                    })
                    ->get()
                    ->sortBy(function ($sale) {
                        return $sale->second_sale->second_sale_date;
                    });
            }
            return datatables()
                ->of($invoice)
                ->editColumn('second_sale_number', function (SecondSaleDetailModel $SecondSaleDetailModel) {
                    return $SecondSaleDetailModel->second_sale->second_sale_number;
                })
                ->editColumn('second_sale_date', function (SecondSaleDetailModel $SecondSaleDetailModel) {
                    return date('d/M/Y', strtotime($SecondSaleDetailModel->second_sale->second_sale_date));
                })
                ->editColumn('customer_name', function (SecondSaleDetailModel $SecondSaleDetailModel) {
                    return $SecondSaleDetailModel->second_sale->customer_name;
                })
                ->editColumn('customer_nik', function (SecondSaleDetailModel $SecondSaleDetailModel) {
                    return $SecondSaleDetailModel->second_sale->customer_nik;
                })
                ->editColumn('customer_phone', function (SecondSaleDetailModel $SecondSaleDetailModel) {
                    return $SecondSaleDetailModel->second_sale->customer_phone;
                })
                ->editColumn('customer_email', function (SecondSaleDetailModel $SecondSaleDetailModel) {
                    return $SecondSaleDetailModel->second_sale->customer_email;
                })
                ->editColumn('product', function (SecondSaleDetailModel $SecondSaleDetailModel) {
                    return $SecondSaleDetailModel->secondProduct->name_product_trade_in;
                })
                ->editColumn('total', function (SecondSaleDetailModel $SecondSaleDetailModel) {
                    $diskon_persen = $SecondSaleDetailModel->discount / 100;
                    $produk_diskon = $SecondSaleDetailModel->secondProduct->price_product_trade_in * $diskon_persen;
                    $harga_setelah_diskon = $SecondSaleDetailModel->secondProduct->price_product_trade_in - $produk_diskon - $SecondSaleDetailModel->discount_rp;
                    $total = $harga_setelah_diskon * $SecondSaleDetailModel->qty;
                    return number_format((float) $total, 0, '.', ',');
                })
                ->editColumn('createdBy', function (SecondSaleDetailModel $SecondSaleDetailModel) {
                    return $SecondSaleDetailModel->second_sale->secondSaleBy->name;
                })
                ->make(true);
        }
        $data = [
            'title' => 'Trade-In Sales Report',
            'trade_product' => $this->trade_product,
        ];
        return view('report.second_sale_report', $data);
    }

    // ! report report vendor
    public function reportVendor()
    {
        $model = SuppliersModel::all();
        $data = [
            'title' => 'Vendor Report',
            'model' => $model,
        ];
        return view('report.vendor', $data);
    }

    // ! report employee
    public function reportEmployee(Request $request)
    {
        $employee = EmployeeModel::all();

        if ($request->ajax()) {
            $employees = EmployeeModel::select(['id', 'name', 'gender', 'phone', 'emergency_phone', 'emergency_relation', 'emergency_phone_', 'emergency_relation_', 'email', 'birth_place', 'birth_date', 'address', 'address_identity', 'last_edu_first', 'school_name_first', 'from_first', 'to_first', 'last_edu_sec', 'school_name_sec', 'from_sec', 'to_sec', 'mom_name', 'mom_phone', 'father_name', 'father_phone', 'vacation', 'work_date', 'job'])->get();

            return datatables()
                ->of($employees)
                ->editColumn('gender', function ($employee) {
                    return $employee->gender == 1 ? 'Male' : 'Female';
                })
                ->addColumn('emergency_contact_1', function ($employee) {
                    return $employee->emergency_phone . '- ' . $employee->emergency_relation;
                })
                ->addColumn('emergency_contact_2', function ($employee) {
                    return $employee->emergency_phone_ . '- ' . $employee->emergency_relation_;
                })
                ->addColumn('birth_details', function ($employee) {
                    $formattedDate = Carbon::parse($employee->birth_date)->format('d-m-Y');
                    return $employee->birth_place . ', ' . $formattedDate;
                })
                ->addColumn('education_1', function ($employee) {
                    return $employee->last_edu_first . ', ' . $employee->school_name_first . ' from ' . $employee->from_first . ' to ' . $employee->to_first;
                })
                ->addColumn('education_2', function ($employee) {
                    return $employee->last_edu_sec . ', ' . $employee->school_name_sec . ' from ' . $employee->from_sec . ' to ' . $employee->to_sec;
                })
                ->addColumn('mother_contact', function ($employee) {
                    return $employee->mom_name . '- ' . $employee->mom_phone;
                })
                ->addColumn('father_contact', function ($employee) {
                    return $employee->father_name . '- ' . $employee->father_phone;
                })
                ->addColumn('work_date', function ($employee) {
                    $formattedDate = date('d-m-Y', strtotime($employee->work_date));
                    return $formattedDate . ' - ' . $employee->job;
                })
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'Employee Report',
            'employee' => $employee,
        ];

        return view('report.employee', $data);
    }

    // ! report attendance
    public function reportAttendance(Request $request)
    {
        if ($request->ajax()) {
            $today = Carbon::today()->format('Y-m-d');

            $startDate = request()->input('from_date', $today);
            $endDate = request()->input('to_date', $today);
            $userId = request()->input('user_id');

            $attendances = AttendancesModel::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    // Filter berdasarkan rentang tanggal
                    return $query->whereBetween(DB::raw('DATE(clock_time)'), [$startDate, $endDate]);
                })
                ->when($userId, function ($query) use ($userId) {
                    // Filter berdasarkan user_id
                    return $query->where('user_id', $userId);
                })
                ->orderBy('user_id')
                ->orderBy('clock_time')
                ->get()
                ->groupBy(function ($item) {
                    // Mengelompokkan berdasarkan user_id dan tanggal clock_time
                    return $item->user_id . '-' . Carbon::parse($item->clock_time)->format('Y-m-d');
                });

            $result = $attendances->map(function ($group) {
                $clock_in = $group->where('type', 'in')->sortBy('clock_time')->first();
                $clock_out = $group->where('type', 'out')->sortByDesc('clock_time')->first();

                return [
                    'user_id' => User::where('id', $group->first()->user_id)->first()->name,
                    'clock_in' => $clock_in ? Carbon::parse($clock_in->clock_time)->format('H:i:s') : null,
                    'clock_out' => $clock_out ? Carbon::parse($clock_out->clock_time)->format('H:i:s') : null,
                    'date' => Carbon::parse($group->first()->clock_time)->format('Y-m-d'),
                ];
            });

            return datatables()
                ->of($result)
                // ->editColumn('user_id', function ($attendance) {
                //     return $attendance->userBy ? $attendance->userBy->name : 'Unknown';
                // })
                // ->editColumn('date', function ($attendance) {
                //     return Carbon::parse($attendance->clock_time)->format('d M Y');
                // })
                // ->editColumn('clock_in', function ($attendance) {
                //     if ($attendance->type == 'in') {
                //         return Carbon::parse($attendance->clock_time)->format('H:i:s');
                //     } else {
                //         return 'No Time In';
                //     }
                // })
                // ->editColumn('clock_out', function ($attendance) {
                //     if ($attendance->type == 'out') {
                //         return Carbon::parse($attendance->clock_time)->format('H:i:s');
                //     } else {
                //         return 'No Time Out';
                //     }
                // })
                ->addIndexColumn()
                ->make(true);
        }

        return view('report.attendance', [
            'title' => 'Attendance Report',
            'attendances' => AttendancesModel::with('userBy')->latest()->get(),
        ]);
    }

    // ! report asset
    public function reportAsset()
    {
        $asset = AssetModel::oldest()->get();

        $data = [
            'title' => 'Asset Report',
            'asset' => $asset,
        ];

        return view('report.asset', $data);
    }

    // ! report car
    public function reportCar(Request $request)
    {
        if ($request->ajax()) {
            $data_car = CarTypeModel::with('brandBy')
                ->whereHas('brandBy', function ($query) {
                    $query->orderBy('car_brand', 'ASC');
                })
                ->get();

            return datatables()
                ->of($data_car)
                ->editColumn('id_car_brand', function (CarTypeModel $CarTypeModel) {
                    return $CarTypeModel->brandBy->car_brand;
                })
                ->make(true);
        }
        $data = [
            'title' => 'Car Report',
        ];
        return view('report.report_car', $data);
    }

    // ! report moto
    public function reportMoto(Request $request)
    {
        if ($request->ajax()) {
            $data_moto = MotorTypeModel::with('brandBy')
                ->whereHas('brandBy', function ($query) {
                    $query->orderBy('name_brand', 'ASC');
                })
                ->get();

            return datatables()
                ->of($data_moto)
                ->editColumn('id_motor_brand', function (MotorTypeModel $MotorTypeModel) {
                    return $MotorTypeModel->brandBy->name_brand;
                })
                ->make(true);
        }
        $data = [
            'title' => 'Motor Report',
        ];
        return view('report.report_moto', $data);
    }

    // ! get data province
    public function getProvince()
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/provinces.json');
        $getProvinces = $getAPI->json();
        return response()->json($getProvinces);
    }

    // ! get data name province
    public function getNameProvince($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/province/' . $id . '.json');
        $getProvinces = $getAPI->json();
        // dd($getProvinces['name']);
        return $getProvinces['name'];
        // dd($getProvinces['name']);
    }

    // ! get data city
    public function getCity($province_id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/regencies/' . $province_id . '.json');
        $getCities = $getAPI->json();
        return response()->json($getCities);
    }

    // ! get data name city
    public function getNameCity($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/regency/' . $id . '.json');
        $getCities = $getAPI->json();
        return $getCities['name'];
    }

    // ! get data district
    public function getDistrict($city_id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/districts/' . $city_id . '.json');
        $getDistricts = $getAPI->json();
        return response()->json($getDistricts);
    }

    // ! get data name district
    public function getNameDistrict($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/district/' . $id . '.json');
        $getDistricts = $getAPI->json();
        return $getDistricts['name'];
    }

    public function reportStock(Request $request)
    {
        // $to_date =  date('Y-m-d');
        // // ! get data qty non retail
        // $stock_sales_order = SalesOrderDetailModel::with('salesorders', 'productSales')
        //     ->whereHas('salesorders', function ($query) use ($request, $to_date) {
        //         $query->whereBetween('order_date', array(date('Y-m-d', strtotime('2023-02-25')), $to_date));
        //     })
        //     ->latest()
        //     ->get();

        // $stock = StockModel::with('warehouseBy', 'productBy')
        //     ->when($request->warehouse, function ($query) use ($request) {
        //         $query->where('warehouses_id', $request->warehouse);
        //     })
        //     ->whereHas('productBy', function ($query) use ($request) {
        //         $query->when($request->product, function ($product) use ($request) {
        //             $product->where('id', $request->product);
        //             $product->orderBy('nama_barang', 'ASC');
        //         });
        //         //! query untuk relasi dari product ke submaterial
        //         $query->whereHas(
        //             'sub_materials',
        //             function ($q) use ($request) {
        //                 $q->when($request->material, function ($query) use ($request) {
        //                     $query->where('id', $request->material);
        //                 });
        //             }
        //         );
        //         //! query untuk relasi dari product ke subtype
        //         $query->whereHas(
        //             'sub_types',
        //             function ($q) use ($request) {
        //                 $q->when($request->type, function ($query) use ($request) {
        //                     $query->where('id', $request->type);
        //                 });
        //             }
        //         );
        //     })
        //     ->get();
        // $stock1 = $stock->transform(function ($item) use (
        //     $stock_sales_order
        // ) {

        //     $stock_sales_order = $stock_sales_order->each(function ($item2) use ($item) {

        //         if ($item2->productSales->id == $item->products_id && $item2->salesorders->warehouse_id == $item->warehouses_id) {
        //             $item->stock = $item->stock + $item2->qty;
        //         }
        //     });

        //     return $item;
        // });
        // $stock = $stock1->where('products_id', 183);
        // dd($stock);
        // $to_date = date('Y-m-d');
        // $from_date = date('Y-m-d', strtotime('2023-02-25'));
        // $stock_sales_order_retail = DirectSalesDetailModel::with(
        //     'productBy',
        //     'directSalesBy',
        //     'directSalesBy.createdBy',
        //     'directSalesBy.carBrandBy',
        //     'directSalesBy.carTypeBy',
        //     'directSalesBy.motorBrandBy',
        //     'directSalesBy.motorTypeBy',
        //     'directSalesBy.customerBy',
        //     'productBy.materials',
        //     'productBy.sub_materials',
        //     'productBy.sub_types'
        // )
        //     ->whereHas('directSalesBy', function ($query) use ($from_date, $to_date) {
        //         $query->whereBetween('order_date', array($from_date, $to_date));
        //     })
        //     ->latest()
        //     ->get();

        // dd($stock_sales_order_retail->where('product_id', 183));
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $to_date = date('Y-m-d');
                $from_date = date('Y-m-d', strtotime($request->from_date . '+1 day'));
                // ! get data qty non retail
                $stock_sales_order = SalesOrderDetailModel::with('salesorders', 'productSales')
                    ->whereHas('salesorders', function ($query) use ($from_date, $to_date) {
                        $query->where('isverified', 1);
                        $query->where('isapprove', 'approve');
                        $query->whereBetween('order_date', [$from_date, $to_date]);
                    })

                    ->latest()
                    ->get();

                // ! get data qty return non retail
                $stock_return_sales_order = ReturnDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) use ($from_date, $to_date) {
                        $query->whereBetween('return_date', [$from_date, $to_date]);
                    })
                    ->latest()
                    ->get();

                // ! get data qty purchase
                $stock_purchase_order = PurchaseOrderDetailModel::with('purchaseOrderBy', 'purchaseOrderBy.supplierBy', 'purchaseOrderBy.createdPurchaseOrder', 'purchaseOrderBy.warehouseBy', 'productBy')
                    ->whereHas('purchaseOrderBy', function ($query) use ($from_date, $to_date) {
                        $query->where('isvalidated', 1);
                        $query->whereBetween('order_date', [date('Y-m-d', strtotime($from_date)), $to_date]);
                    })
                    ->latest()
                    ->get();

                // ! get data qty return purchase
                $stock_return_purchase_order = ReturnPurchaseDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) use ($from_date, $to_date) {
                        $query->whereBetween('return_date', [$from_date, $to_date]);
                    })
                    ->latest()
                    ->get();

                // ! get data qty retail
                $stock_sales_order_retail = DirectSalesDetailModel::with('productBy', 'directSalesBy', 'directSalesBy.createdBy', 'directSalesBy.carBrandBy', 'directSalesBy.carTypeBy', 'directSalesBy.motorBrandBy', 'directSalesBy.motorTypeBy', 'directSalesBy.customerBy', 'productBy.materials', 'productBy.sub_materials', 'productBy.sub_types')
                    ->whereHas('directSalesBy', function ($query) use ($from_date, $to_date) {
                        $query->where('isapproved', 1);
                        $query->whereBetween('order_date', [$from_date, $to_date]);
                    })
                    ->latest()
                    ->get();

                // ! get data qty return retail
                $stock_return_sales_order_retail = ReturnRetailDetailModel::with('returnBy', 'productBy')
                    ->whereHas('returnBy', function ($query) use ($from_date, $to_date) {
                        $query->whereBetween('return_date', [$from_date, $to_date]);
                    })
                    ->latest()
                    ->get();

                //get data mutation
                $stock_mutation = StockMutationDetailModel::with('stockMutationBy', 'productBy')
                    ->whereHas('stockMutationBy', function ($query) use ($from_date, $to_date) {
                        $query->where('isapprove', 1);
                        $query->whereBetween('mutation_date', [$from_date, $to_date]);
                    })
                    ->latest()
                    ->get();

                // * get stock sekarang
                $stock = StockModel::with('warehouseBy', 'productBy')
                    ->when($request->warehouse, function ($query) use ($request) {
                        $query->where('warehouses_id', $request->warehouse);
                    })
                    ->whereHas('productBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id', $request->product);
                            $product->orderBy('nama_barang', 'ASC');
                        });
                        $query->where('status', 1);
                        //! query untuk relasi dari product ke submaterial
                        $query->whereHas('sub_materials', function ($q) use ($request) {
                            $q->when($request->material, function ($query) use ($request) {
                                $query->where('id', $request->material);
                            });
                        });
                        //! query untuk relasi dari product ke subtype
                        $query->whereHas('sub_types', function ($q) use ($request) {
                            $q->when($request->type, function ($query) use ($request) {
                                $query->where('id', $request->type);
                            });
                        });
                    })
                    ->get();

                $stock1 = $stock->transform(function ($item) use ($stock_sales_order, $stock_return_sales_order, $stock_purchase_order, $stock_return_purchase_order, $stock_sales_order_retail, $stock_return_sales_order_retail, $stock_mutation) {
                    $stock_sales_order = $stock_sales_order->each(function ($item2) use ($item) {
                        if ($item2->productSales->id == $item->products_id && $item2->salesorders->warehouse_id == $item->warehouses_id) {
                            $item->stock = $item->stock + $item2->qty;
                        }
                    });
                    $stock_return_sales_order = $stock_return_sales_order->each(function ($item3) use ($item) {
                        if ($item3->product_id == $item->products_id && $item3->returnBy->salesOrderBy->warehouse_id == $item->warehouses_id) {
                            $item->stock = $item->stock - $item3->qty;
                        }
                    });
                    $stock_purchase_order = $stock_purchase_order->each(function ($item4) use ($item) {
                        if ($item4->product_id == $item->products_id && $item4->purchaseOrderBy->warehouse_id == $item->warehouses_id) {
                            $item->stock = $item->stock - $item4->qty;
                        }
                    });
                    $stock_return_purchase_order = $stock_return_purchase_order->each(function ($item5) use ($item) {
                        if ($item5->productBy->id == $item->products_id && $item5->returnBy->purchaseOrderBy->warehouse_id == $item->warehouses_id) {
                            $item->stock = $item->stock + $item5->qty;
                        }
                    });
                    $stock_sales_order_retail = $stock_sales_order_retail->each(function ($item6) use ($item) {
                        if ($item6->productBy->id == $item->products_id && $item6->directSalesBy->warehouse_id == $item->warehouses_id) {
                            $item->stock = $item->stock + $item6->qty;
                        }
                    });
                    $stock_return_sales_order_retail = $stock_return_sales_order_retail->each(function ($item7) use ($item) {
                        if ($item7->productBy->id == $item->products_id && $item7->returnBy->retailBy->warehouse_id == $item->warehouses_id) {
                            $item->stock = $item->stock - $item7->qty;
                        }
                    });
                    $stock_mutation = $stock_mutation->each(function ($item8) use ($item) {
                        if ($item8->product_id == $item->products_id) {
                            if ($item8->stockMutationBy->from == $item->warehouses_id) {
                                $item->stock = $item->stock + $item8->qty;
                            }

                            if ($item8->stockMutationBy->to == $item->warehouses_id) {
                                $item->stock = $item->stock - $item8->qty;
                            }
                        }
                    });
                    return $item;
                });
                $stock = $stock1->sortBy(function ($query) {
                    return $query->productBy->sub_materials->nama_sub_material . ' ' . $query->productBy->sub_types->type_name . ' ' . $query->productBy->nama_barang;
                });
            } else {
                $stock = StockModel::with('warehouseBy', 'productBy')
                    ->get()
                    ->sortBy(function ($query) {
                        return $query->productBy->sub_materials->nama_sub_material . ' ' . $query->productBy->sub_types->type_name . ' ' . $query->productBy->nama_barang;
                    });
            }
            return datatables()
                ->of($stock)
                ->editColumn('nama_barang', function ($data) use ($request) {
                    return '<a href="#" class="fw-bold text-success modal-trace" data-product="' .
                        $data->products_id .
                        '"
                    data-warehouse="' .
                        $data->warehouses_id .
                        '" data-stock="' .
                        $data->id .
                        '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' .
                        $data->id .
                        '">' .
                        $data->productBy->sub_materials->nama_sub_material .
                        ' ' .
                        $data->productBy->sub_types->type_name .
                        ' ' .
                        $data->productBy->nama_barang .
                        '</a>';
                })
                ->editColumn('warehouse', function ($data) use ($request) {
                    return $data->warehouseBy->warehouses;
                })
                ->editColumn('satuan', function ($data) {
                    return $data->productBy->uoms->satuan;
                })
                ->rawColumns(['nama_barang'])
                ->addIndexColumn()
                ->make(true);
        }

        $stock = StockModel::with('warehouseBy', 'productBy')->get();
        $data = [
            'title' => 'Data Stock Report ',
            'material_group' => $this->material,
            'product' => $this->product,
            'all_warehouse' => $this->all_warehouse,
            'type' => $this->type,
            'stocks' => $stock,
        ];
        return view('report.stock', $data);
    }

    public function reportStockTrace(Request $request)
    {
        // $to_date = date('Y-m-d');
        // $from_date = date('Y-m-d', strtotime('2023-02-01' . '+1 day'));
        // // ! get data qty non retail
        // $sales_order = SalesOrderDetailModel::with('salesorders', 'productSales')
        //     ->whereHas('salesorders', function ($query) use ($from_date, $to_date, $request) {
        //         $query->where('isverified', 1);
        //         $query->where('isapprove', 'approve');
        //         $query->whereBetween('order_date', array($from_date, $to_date));
        //         $query->where('warehouse_id', 1);
        //     })
        //     ->where('products_id', 173)
        //     ->latest()
        //     ->get();

        // // ! get data qty return non retail
        // $return_sales_order = ReturnDetailModel::with('returnBy', 'productBy')
        //     ->whereHas('returnBy', function ($query) use ($from_date, $to_date, $request) {
        //         $query->whereBetween('return_date', array($from_date, $to_date));
        //         $query->whereHas('salesOrderBy', function ($q) use ($request) {
        //             $q->where('warehouse_id', 1);
        //         });
        //     })
        //     ->where('product_id', 173)
        //     ->latest()
        //     ->get();

        // // ! get data qty purchase
        // $purchase_order = PurchaseOrderDetailModel::with(
        //     'purchaseOrderBy',
        //     'purchaseOrderBy.supplierBy',
        //     'purchaseOrderBy.createdPurchaseOrder',
        //     'purchaseOrderBy.warehouseBy',
        //     'productBy'
        // )
        //     ->whereHas('purchaseOrderBy', function ($query) use ($from_date, $to_date, $request) {
        //         $query->where('isvalidated', 1);
        //         $query->whereBetween('order_date', array(date('Y-m-d', strtotime($from_date)), $to_date));
        //         $query->where('warehouse_id', 173);
        //     })
        //     ->where('product_id', 173)
        //     ->latest()
        //     ->get();

        // // ! get data qty return purchase
        // $return_purchase_order = ReturnPurchaseDetailModel::with('returnBy', 'productBy')
        //     ->whereHas('returnBy', function ($query) use ($from_date, $to_date, $request) {
        //         $query->whereBetween('return_date', array($from_date, $to_date));
        //         $query->whereHas('purchaseOrderBy', function ($q) use ($request) {
        //             $q->where('warehouse_id', 1);
        //         });
        //     })
        //     ->where('product_id', 173)
        //     ->latest()
        //     ->get();

        // // ! get data qty retail
        // $sales_order_retail = DirectSalesDetailModel::with(
        //     'productBy',
        //     'directSalesBy',
        //     'directSalesBy.createdBy',
        //     'directSalesBy.carBrandBy',
        //     'directSalesBy.carTypeBy',
        //     'directSalesBy.motorBrandBy',
        //     'directSalesBy.motorTypeBy',
        //     'directSalesBy.customerBy',
        //     'productBy.materials',
        //     'productBy.sub_materials',
        //     'productBy.sub_types'
        // )
        //     ->whereHas('directSalesBy', function ($query) use ($from_date, $to_date, $request) {
        //         $query->whereBetween('order_date', array($from_date, $to_date));
        //         $query->where('warehouse_id', 1);
        //     })
        //     ->where('product_id', 173)
        //     ->latest()
        //     ->get();

        // // ! get data qty return retail
        // $return_sales_order_retail = ReturnRetailDetailModel::with('returnBy', 'productBy')
        //     ->whereHas('returnBy', function ($query) use ($from_date, $to_date, $request) {
        //         $query->whereBetween('return_date', array($from_date, $to_date));
        //         $query->whereHas('retailBy', function ($q) use ($request) {
        //             $q->where('warehouse_id', 1);
        //         });
        //     })
        //     ->where('product_id', 173)
        //     ->latest()
        //     ->get();

        // //get data mutation
        // $mutation_from = StockMutationDetailModel::with('stockMutationBy', 'productBy')
        //     ->whereHas('stockMutationBy', function ($query) use ($from_date, $to_date, $request) {
        //         $query->where('isapprove', 1);
        //         $query->whereBetween('mutation_date', array($from_date, $to_date));
        //         $query->where('from', 1);
        //     })
        //     ->where('product_id', 173)
        //     ->latest()
        //     ->get();

        // $mutation_to = StockMutationDetailModel::with('stockMutationBy', 'productBy')
        //     ->whereHas('stockMutationBy', function ($query) use ($from_date, $to_date, $request) {
        //         $query->where('isapprove', 1);
        //         $query->whereBetween('mutation_date', array($from_date, $to_date));
        //         $query->where('to', 1);
        //     })
        //     ->where('product_id', 173)
        //     ->latest()
        //     ->get();

        // // * get stock sekarang

        // $all = $sales_order->concat($return_sales_order)
        //     ->concat($purchase_order)
        //     ->concat($return_purchase_order)
        //     ->concat($sales_order_retail)
        //     ->concat($return_sales_order_retail)
        //     ->concat($mutation_from)
        //     ->concat($mutation_to);
        // dd($all);
        if ($request->ajax()) {
            $to_date = date('Y-m-d');
            $from_date = date('Y-m-d', strtotime($request->from_date . '+1 day'));
            // ! get data qty non retail
            $sales_order = SalesOrderDetailModel::with('salesorders', 'productSales')
                ->whereHas('salesorders', function ($query) use ($from_date, $to_date, $request) {
                    $query->where('isverified', 1);
                    $query->where('isapprove', 'approve');
                    $query->whereBetween('order_date', [$from_date, $to_date]);
                    $query->where('warehouse_id', $request->warehouse_id);
                })
                ->where('products_id', $request->product_id)
                ->select('sales_order_details.*', DB::raw('sales_orders.order_date AS date'))
                ->join('sales_orders', 'sales_order_details.sales_orders_id', '=', 'sales_orders.id')
                ->get();

            // ! get data qty return non retail
            $return_sales_order = ReturnDetailModel::with('returnBy', 'productBy')
                ->whereHas('returnBy', function ($query) use ($from_date, $to_date, $request) {
                    $query->select('*', DB::raw('return_date AS date'));
                    $query->whereBetween('return_date', [$from_date, $to_date]);
                    $query->whereHas('salesOrderBy', function ($q) use ($request) {
                        $q->where('warehouse_id', $request->warehouse_id);
                    });
                })
                ->where('product_id', $request->product_id)
                ->select('return_details.*', DB::raw('returns.return_date AS date'))
                ->join('returns', 'return_details.return_id', '=', 'returns.id')
                ->get();

            // ! get data qty purchase
            $purchase_order = PurchaseOrderDetailModel::with('purchaseOrderBy', 'purchaseOrderBy.supplierBy', 'purchaseOrderBy.createdPurchaseOrder', 'purchaseOrderBy.warehouseBy', 'productBy')
                ->whereHas('purchaseOrderBy', function ($query) use ($from_date, $to_date, $request) {
                    $query->select('*', DB::raw('order_date AS date'));
                    $query->where('isvalidated', 1);
                    $query->whereBetween('order_date', [date('Y-m-d', strtotime($from_date)), $to_date]);
                    $query->where('warehouse_id', $request->warehouse_id);
                })
                ->where('product_id', $request->product_id)
                ->select('purchase_order_details.*', DB::raw('purchase_orders.order_date AS date'))
                ->join('purchase_orders', 'purchase_order_details.purchase_order_id', '=', 'purchase_orders.id')
                ->get();

            // ! get data qty return purchase
            $return_purchase_order = ReturnPurchaseDetailModel::with('returnBy', 'productBy')
                ->whereHas('returnBy', function ($query) use ($from_date, $to_date, $request) {
                    $query->select('*', DB::raw('return_date AS date'));
                    $query->whereBetween('return_date', [$from_date, $to_date]);
                    $query->whereHas('purchaseOrderBy', function ($q) use ($request) {
                        $q->where('warehouse_id', $request->warehouse_id);
                    });
                })
                ->where('product_id', $request->product_id)
                ->select('return_purchase_details.*', DB::raw('return_purchases.return_date AS date'))
                ->join('return_purchases', 'return_purchase_details.return_id', '=', 'return_purchases.id')
                ->get();

            // ! get data qty retail
            $sales_order_retail = DirectSalesDetailModel::with('productBy', 'directSalesBy', 'directSalesBy.createdBy', 'directSalesBy.carBrandBy', 'directSalesBy.carTypeBy', 'directSalesBy.motorBrandBy', 'directSalesBy.motorTypeBy', 'directSalesBy.customerBy', 'productBy.materials', 'productBy.sub_materials', 'productBy.sub_types')
                ->whereHas('directSalesBy', function ($query) use ($from_date, $to_date, $request) {
                    $query->select('*', DB::raw('order_date AS date'));
                    $query->whereBetween('order_date', [$from_date, $to_date]);
                    $query->where('warehouse_id', $request->warehouse_id);
                    $query->where('isapproved', 1);
                })
                ->where('product_id', $request->product_id)
                ->select('direct_sales_details.*', DB::raw('direct_sales.order_date AS date'))
                ->join('direct_sales', 'direct_sales_details.direct_id', '=', 'direct_sales.id')
                ->get();

            // ! get data qty return retail
            $return_sales_order_retail = ReturnRetailDetailModel::with('returnBy', 'productBy')
                ->whereHas('returnBy', function ($query) use ($from_date, $to_date, $request) {
                    $query->select('*', DB::raw('return_date AS date'));
                    $query->whereBetween('return_date', [$from_date, $to_date]);
                    $query->whereHas('retailBy', function ($q) use ($request) {
                        $q->where('warehouse_id', $request->warehouse_id);
                    });
                })
                ->where('product_id', $request->product_id)
                ->select('return_retail_details.*', DB::raw('return_retails.return_date AS date'))
                ->join('return_retails', 'return_retail_details.return_id', '=', 'return_retails.id')
                ->get();

            //get data mutation
            $mutation_from = StockMutationDetailModel::with('stockMutationBy', 'productBy')
                ->whereHas('stockMutationBy', function ($query) use ($from_date, $to_date, $request) {
                    $query->select('*', DB::raw('mutation_date AS date'));
                    $query->where('isapprove', 1);
                    $query->whereBetween('mutation_date', [$from_date, $to_date]);
                    $query->where('from', $request->warehouse_id);
                })
                ->where('product_id', $request->product_id)
                ->select('stock_mutation_details.*', DB::raw('stock_mutations.mutation_date AS date'))
                ->join('stock_mutations', 'stock_mutation_details.mutation_id', '=', 'stock_mutations.id')
                ->get();

            $mutation_to = StockMutationDetailModel::with('stockMutationBy', 'productBy')
                ->whereHas('stockMutationBy', function ($query) use ($from_date, $to_date, $request) {
                    $query->select('*', DB::raw('mutation_date AS date'));
                    $query->where('isapprove', 1);
                    $query->whereBetween('mutation_date', [$from_date, $to_date]);
                    $query->where('to', $request->warehouse_id);
                })
                ->where('product_id', $request->product_id)
                ->select('stock_mutation_details.*', DB::raw('stock_mutations.mutation_date AS date'))
                ->join('stock_mutations', 'stock_mutation_details.mutation_id', '=', 'stock_mutations.id')
                ->get();

            // * get stock sekarang

            $all = $sales_order->concat($return_sales_order)->concat($purchase_order)->concat($return_purchase_order)->concat($sales_order_retail)->concat($return_sales_order_retail)->concat($mutation_from)->concat($mutation_to)->sortBy('date');
            // dd($sales_order);

            return datatables()
                ->of($all)
                ->editColumn('transaction', function ($data) use ($request) {
                    if ($data instanceof SalesOrderDetailModel) {
                        return 'Indirect Sales: ' . $data->salesorders->order_number;
                    } elseif ($data instanceof ReturnDetailModel) {
                        return 'Indirect Return: ' . $data->returnBy->return_number;
                    } elseif ($data instanceof PurchaseOrderDetailModel) {
                        return 'Purchase: ' . $data->purchaseOrderBy->order_number;
                    } elseif ($data instanceof ReturnPurchaseDetailModel) {
                        return 'Purchase Return: ' . $data->returnBy->return_number;
                    } elseif ($data instanceof DirectSalesDetailModel) {
                        return 'Direct Sales: ' . $data->directSalesBy->order_number;
                    } elseif ($data instanceof ReturnRetailDetailModel) {
                        return 'Direct Return: ' . $data->returnBy->return_number;
                    } elseif ($data instanceof StockMutationDetailModel) {
                        if ($data->stockMutationBy->from == $request->warehouse_id) {
                            return 'Mutation: ' . $data->stockMutationBy->mutation_number . ' from ' . $data->stockMutationBy->fromWarehouse->warehouses;
                        } elseif ($data->stockMutationBy->to == $request->warehouse_id) {
                            return 'Mutation: ' . $data->stockMutationBy->mutation_number . ' to ' . $data->stockMutationBy->toWarehouse->warehouses;
                        }
                    }
                })
                ->editColumn('qty', function ($data) use ($request) {
                    return $data->qty;
                    // if ($data instanceof SalesOrderDetailModel) {
                    //     return '<div class="text-danger">' . $data->qty . '</div>';
                    // } elseif ($data instanceof ReturnDetailModel) {
                    //     return '<div class="text-success">' . $data->qty . '</div>';
                    // } elseif ($data instanceof PurchaseOrderDetailModel) {
                    //     return '<div class="text-success">' . $data->qty . '</div>';
                    // } elseif ($data instanceof ReturnPurchaseDetailModel) {
                    //     return '<div class="text-danger">' . $data->qty . '</div>';
                    // } elseif ($data instanceof DirectSalesDetailModel) {
                    //     return '<div class="text-danger">' . $data->qty . '</div>';
                    // } elseif ($data instanceof ReturnRetailDetailModel) {
                    //     return '<div class="text-success">' . $data->qty . '</div>';
                    // } elseif ($data instanceof StockMutationDetailModel) {
                    //     if ($data->stockMutationBy->from == $request->warehouse_id) {
                    //         return '<div class="text-danger">' . $data->qty . '</div>';
                    //     } elseif ($data->stockMutationBy->to == $request->warehouse_id) {
                    //         return '<div class="text-success">' . $data->qty . '</div>';
                    //     }
                    // }
                })
                ->editColumn('date', function ($data) use ($request) {
                    if ($data instanceof SalesOrderDetailModel) {
                        return date('d/F/Y', strtotime($data->salesorders->order_date));
                    } elseif ($data instanceof ReturnDetailModel) {
                        return date('d/F/Y', strtotime($data->returnBy->return_date));
                    } elseif ($data instanceof PurchaseOrderDetailModel) {
                        return date('d/F/Y', strtotime($data->purchaseOrderBy->order_date));
                    } elseif ($data instanceof ReturnPurchaseDetailModel) {
                        return date('d/F/Y', strtotime($data->returnBy->return_date));
                    } elseif ($data instanceof DirectSalesDetailModel) {
                        return date('d/F/Y', strtotime($data->directSalesBy->order_date));
                    } elseif ($data instanceof ReturnRetailDetailModel) {
                        return date('d/F/Y', strtotime($data->returnBy->return_date));
                    } elseif ($data instanceof StockMutationDetailModel) {
                        if ($data->stockMutationBy->from == $request->warehouse_id) {
                            return date('d/F/Y', strtotime($data->stockMutationBy->mutation_date));
                        } elseif ($data->stockMutationBy->to == $request->warehouse_id) {
                            return date('d/F/Y', strtotime($data->stockMutationBy->mutation_date));
                        }
                    }
                })

                ->rawColumns(['qty'])
                ->addIndexColumn()
                ->make(true);
        }

        // $stock = StockModel::with('warehouseBy', 'productBy')
        //     ->get();
        // $data = [
        //     'title' => "Data Stock Report ",
        //     'material_group' => $this->material,
        //     'product' => $this->product,
        //     'all_warehouse' => $this->all_warehouse,
        //     'type' => $this->type,
        //     'stocks' => $stock
        // ];
        // return view('report.stock');
    }

    public function report_daily(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $activity = DailyActivityModel::with('userBy')
                    ->whereBetween('date', [$request->from_date, $request->to_date])
                    ->when($request->user, function ($q) use ($request) {
                        return $q->where('user_id', $request->user);
                    })
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->date;
                    });
            } else {
                $activity = DailyActivityModel::with('userBy')
                    ->where('date', date('Y-m-d'))
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->date;
                    });
            }
            return datatables()
                ->of($activity)
                ->editColumn('date', function ($data) {
                    return date('d/F/Y', strtotime($data->date));
                })
                ->editColumn('user', function ($data) {
                    return $data->userBy->name;
                })
                ->make(true);
        }

        $all_employees = User::oldest('name')->get();
        $data = [
            'title' => 'Daily Activity Report',
            'employees' => $all_employees,
        ];
        return view('report.daily_activity', $data);
    }

    public function report_promotion_stock(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $to_date = date('Y-m-d');
                $from_date = date('Y-m-d', strtotime($request->from_date . '+1 day'));
                // ! get data qty non retail
                $stock_transaction = ItemPromotionTransactionDetailModel::with('transactionBy', 'itemBy')
                    ->whereHas('transactionBy', function ($query) use ($from_date, $to_date) {
                        $query->where('isapproved', 1);
                        $query->whereBetween('order_date', [$from_date, $to_date]);
                    })
                    ->latest()
                    ->get();

                // ! get data qty return non retail
                $stock_return_transaction = ReturnItemPromotionDetailModel::with('returnBy', 'itemBy')
                    ->whereHas('returnBy', function ($query) use ($from_date, $to_date) {
                        $query->whereBetween('return_date', [$from_date, $to_date]);
                    })
                    ->latest()
                    ->get();

                // ! get data qty purchase
                $stock_purchase = ItemPromotionPurchaseDetailModel::with('purchaseBy', 'itemBy')
                    ->whereHas('purchaseBy', function ($query) use ($from_date, $to_date) {
                        $query->where('isapproved', 1);
                        $query->whereBetween('order_date', [date('Y-m-d', strtotime($from_date)), $to_date]);
                    })
                    ->latest()
                    ->get();

                // ! get data qty return purchase
                $stock_return_purchase = ReturnItemPromotionPurchaseDetailModel::with('returnBy', 'itemBy')
                    ->whereHas('returnBy', function ($query) use ($from_date, $to_date) {
                        $query->whereBetween('return_date', [$from_date, $to_date]);
                    })
                    ->latest()
                    ->get();

                //get data mutation
                $stock_mutation = ItemPromotionMutationDetailModel::with('stockMutationBy', 'itemBy')
                    ->whereHas('stockMutationBy', function ($query) use ($from_date, $to_date) {
                        $query->where('isapproved', 1);
                        $query->whereBetween('mutation_date', [$from_date, $to_date]);
                    })
                    ->latest()
                    ->get();

                // * get stock sekarang
                $stock = ItemPromotionStockModel::with('warehouseBy', 'itemBy')
                    ->when($request->warehouse, function ($query) use ($request) {
                        $query->where('id_warehouse', $request->warehouse);
                    })
                    ->whereHas('itemBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id', $request->product);
                            $product->orderBy('name', 'ASC');
                        });
                        //! query untuk relasi dari product ke submaterial
                    })
                    ->join('warehouses', 'item_promotion_stocks.id_warehouse', '=', 'warehouses.id')
                    ->join('item_promotions', 'item_promotion_stocks.id_item', '=', 'item_promotions.id')
                    ->select('item_promotion_stocks.*', 'item_promotion_stocks.id AS id_stock', 'item_promotions.*', 'warehouses.*')
                    ->orderBy('item_promotions.name')
                    ->orderBy('warehouses.warehouses')
                    ->get();

                $stock1 = $stock->transform(function ($item) use ($stock_transaction, $stock_return_transaction, $stock_purchase, $stock_return_purchase, $stock_mutation) {
                    $stock_transaction = $stock_transaction->each(function ($item2) use ($item) {
                        if ($item2->id_item == $item->id_item && $item2->transactionBy->id_warehouse == $item->id_warehouse) {
                            $item->qty = $item->qty + $item2->qty;
                        }
                    });
                    $stock_return_transaction = $stock_return_transaction->each(function ($item3) use ($item) {
                        if ($item3->id_item == $item->id_item && $item3->returnBy->transactionBy->id_warehouse == $item->id_warehouse) {
                            $item->qty = $item->qty - $item3->qty;
                        }
                    });
                    $stock_purchase = $stock_purchase->each(function ($item4) use ($item) {
                        if ($item4->item_id == $item->id_item && $item4->purchaseBy->warehouse_id == $item->id_warehouse) {
                            $item->qty = $item->qty - $item4->qty;
                        }
                    });
                    $stock_return_purchase = $stock_return_purchase->each(function ($item5) use ($item) {
                        if ($item5->item_id == $item->id_item && $item5->returnBy->purchaseBy->warehouse_id == $item->id_warehouse) {
                            $item->qty = $item->qty + $item5->qty;
                        }
                    });

                    $stock_mutation = $stock_mutation->each(function ($item8) use ($item) {
                        if ($item8->item_id == $item->id_item) {
                            if ($item8->stockMutationBy->from == $item->id_warehouse) {
                                $item->qty = $item->qty + $item8->qty;
                            }

                            if ($item8->stockMutationBy->to == $item->id_warehouse) {
                                $item->qty = $item->qty - $item8->qty;
                            }
                        }
                    });
                    return $item;
                });
                $stock = $stock1;
            } else {
                $stock = DB::table('item_promotion_stocks')->join('warehouses', 'item_promotion_stocks.id_warehouse', '=', 'warehouses.id')->join('item_promotions', 'item_promotion_stocks.id_item', '=', 'item_promotions.id')->select('item_promotion_stocks.*', 'item_promotion_stocks.id AS id_stock', 'item_promotions.*', 'warehouses.*')->orderBy('item_promotions.name')->orderBy('warehouses.warehouses')->get();
            }
            return datatables()
                ->of($stock)
                ->editColumn('nama_barang', function ($data) use ($request) {
                    return '<a href="#" class="fw-bold text-success modal-trace" data-product="' .
                        $data->id_item .
                        '"
                    data-warehouse="' .
                        $data->id_warehouse .
                        '" data-stock="' .
                        $data->qty .
                        '" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace' .
                        $data->id_stock .
                        '">' .
                        $data->name .
                        '</a>';
                })
                ->editColumn('warehouse', function ($data) use ($request) {
                    return $data->warehouses;
                })

                ->rawColumns(['nama_barang'])
                ->addIndexColumn()
                ->make(true);
        }

        $stock = ItemPromotionStockModel::with('warehouseBy', 'itemBy')->get();
        $data = [
            'title' => 'Promotion Item Stock Report ',
            'product' => ItemPromotionModel::oldest('name')->get(),
            'all_warehouse' => WarehouseModel::where('type', 5)->oldest('warehouses')->get(),
            'stocks' => $stock,
        ];
        return view('report.promotion_stock', $data);
    }

    public function report_promotion_transaction(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $invoice = ItemPromotionTransactionDetailModel::with('transactionBy', 'itemBy', 'transactionBy.createdBy', 'transactionBy.warehouseBy', 'transactionBy.customerBy', 'transactionBy.transactionDetailBy')
                    ->whereHas('transactionBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($order_date) use ($request) {
                            $order_date->whereBetween('order_date', [$request->from_date, $request->to_date]);
                        });
                        $query->when($request->warehouse, function ($warehouse) use ($request) {
                            $warehouse->where('id_warehouse', $request->warehouse);
                        });
                        $query->when($request->customer, function ($customer) use ($request) {
                            $customer->where('id_customer', $request->customer);
                        });
                    })
                    ->whereHas('itemBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id_item', $request->product);
                        });
                    })
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->transactionBy->order_date;
                    });
            } else {
                $invoice = ItemPromotionTransactionDetailModel::with('transactionBy', 'itemBy', 'transactionBy.createdBy', 'transactionBy.warehouseBy', 'transactionBy.customerBy', 'transactionBy.transactionDetailBy')
                    ->whereHas('transactionBy', function ($query) {
                        $query->where('order_date', date('Y-m-d'));
                    })
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->transactionBy->order_date;
                    });
            }

            return datatables()
                ->of($invoice)
                // ! get order number
                ->editColumn('order_number', function ($data) {
                    return $data->transactionBy->order_number;
                })
                // ! get order date
                ->editColumn('order_date', function ($data) {
                    return date('d F Y', strtotime($data->transactionBy->order_date));
                })
                // ! get customer name
                ->editColumn('id_customer', function ($data) {
                    if (is_numeric($data->transactionBy->id_customer)) {
                        return $data->transactionBy->customerBy->code_cust . ' - ' . $data->transactionBy->customerBy->name_cust;
                    } else {
                        return $data->transactionBy->id_customer;
                    }
                })
                ->editColumn('address', function ($data) {
                    return $data->transactionBy->address;
                })
                // ! get customer phone
                ->editColumn('id_warehouse', function ($data) {
                    return $data->transactionBy->warehouseBy->warehouses;
                })
                // ! get customer phone
                ->editColumn('created_by', function ($data) {
                    return $data->transactionBy->createdBy->name;
                })
                ->editColumn('price', function ($data) {
                    return number_format($data->price);
                })
                ->editColumn('total', function ($data) {
                    return number_format((float) $data->transactionBy->total);
                })
                ->editColumn('product', function ($data) {
                    return $data->itemBy->name;
                })
                // ! get remark
                ->editColumn('remark', function ($data) {
                    return $data->transactionBy->remark;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Item Promotion Transaction Report',
            'customer' => $this->customer->sortBy('name_cust'),
            'product' => ItemPromotionModel::oldest('name')->get(),
            'warehouse' => WarehouseModel::where('type', 5)->oldest('warehouses')->get(),
        ];

        return view('report.promotion_transaction', $data);
    }

    public function report_promotion_purchase(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $invoice = ItemPromotionPurchaseDetailModel::with('purchaseBy', 'itemBy', 'purchaseBy.createdBy', 'purchaseBy.warehouseBy', 'purchaseBy.customerBy', 'purchaseBy.purchaseDetailBy')
                    ->whereHas('purchaseBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($order_date) use ($request) {
                            $order_date->whereBetween('order_date', [$request->from_date, $request->to_date]);
                        });
                        $query->when($request->warehouse, function ($warehouse) use ($request) {
                            $warehouse->where('id_warehouse', $request->warehouse);
                        });
                        $query->when($request->supplier, function ($supplier) use ($request) {
                            $supplier->where('supplier_id', $request->supplier);
                        });
                    })
                    ->whereHas('itemBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('item_id', $request->product);
                        });
                    })
                    ->where('isapproved', 1)
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->purchaseBy->order_date;
                    });
            } else {
                $invoice = ItemPromotionPurchaseDetailModel::with('purchaseBy', 'itemBy', 'purchaseBy.createdBy', 'purchaseBy.warehouseBy', 'purchaseBy.supplierBy', 'purchaseBy.purchaseDetailBy')
                    ->whereHas('purchaseBy', function ($query) {
                        $query->where('order_date', date('Y-m-d'));
                    })
                    ->where('isapproved', 1)
                    ->get()
                    ->sortBy(function ($q) {
                        return $q->purchaseBy->order_date;
                    });
            }

            return datatables()
                ->of($invoice)
                // ! get order number
                ->editColumn('order_number', function ($data) {
                    return $data->purchaseBy->order_number;
                })
                // ! get order date
                ->editColumn('order_date', function ($data) {
                    return date('d F Y', strtotime($data->purchaseBy->order_date));
                })
                // ! get customer name
                ->editColumn('supplier_id', function ($data) {
                    return $data->purchaseBy->supplierBy->name;
                })
                // ! get customer phone
                ->editColumn('warehouse_id', function ($data) {
                    return $data->purchaseBy->warehouseBy->warehouses;
                })
                // ! get customer phone
                ->editColumn('created_by', function ($data) {
                    return $data->purchaseBy->createdBy->name;
                })
                ->editColumn('price', function ($data) {
                    return number_format($data->price);
                })
                ->editColumn('total', function ($data) {
                    return number_format((float) $data->purchaseBy->total);
                })
                ->editColumn('product', function ($data) {
                    return $data->itemBy->name;
                })
                // ! get remark
                ->editColumn('remark', function ($data) {
                    return $data->purchaseBy->remark;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Material Promotion Purchase Report',
            'supplier' => ItemPromotionSupplierModel::oldest('name')->get(),
            'product' => ItemPromotionModel::oldest('name')->get(),
            'warehouse' => WarehouseModel::where('type', 5)->oldest('warehouses')->get(),
        ];

        return view('report.promotion_purchase', $data);
    }

    public function report_promotion_return(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $return = ReturnItemPromotionDetailModel::with('returnBy', 'itemBy')
                    ->whereHas('returnBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($query) use ($request) {
                            $query->whereBetween('return_date', [$request->from_date, $request->to_date]);
                        });
                    })
                    ->whereHas('itemBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id_item', $request->product);
                        });
                    })
                    ->get()
                    ->sortBy(function ($return) {
                        return $return->returnBy->return_date;
                    });
            } else {
                $return = ReturnItemPromotionDetailModel::with('returnBy', 'itemBy')
                    ->whereHas('returnBy', function ($query) {
                        $query->where('return_date', date('Y-m-d'));
                    })
                    ->get()
                    ->sortBy(function ($return) {
                        return $return->returnBy->return_date;
                    });
            }

            return datatables()
                ->of($return)
                ->editColumn('return_number', function ($data) {
                    return $data->returnBy->return_number;
                })
                ->editColumn('id_transaction', function ($data) {
                    return $data->returnBy->transactionBy->order_number;
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->returnBy->return_date));
                })
                ->editColumn('total', function ($data) {
                    return number_format((float) $data->returnBy->total);
                })
                ->editColumn('return_reason', function ($data) {
                    return $data->returnBy->return_reason;
                })
                ->editColumn('created_by', function ($data) {
                    return $data->returnBy->createdBy->name;
                })
                ->editColumn('product', function ($data) {
                    return $data->itemBy->name;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Return Item Promotion Report',
            'product' => ItemPromotionModel::oldest('name')->get(),
        ];
        return view('report.promotion_return', $data);
    }

    public function report_promotion_purchase_return(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $return = ReturnItemPromotionPurchaseDetailModel::with('returnBy', 'itemBy')
                    ->whereHas('returnBy', function ($query) use ($request) {
                        $query->when($request->from_date, function ($query) use ($request) {
                            $query->whereBetween('return_date', [$request->from_date, $request->to_date]);
                        });
                    })
                    ->whereHas('itemBy', function ($query) use ($request) {
                        $query->when($request->product, function ($product) use ($request) {
                            $product->where('id_item', $request->product);
                        });
                    })
                    ->get()
                    ->sortBy(function ($return) {
                        return $return->returnBy->return_date;
                    });
            } else {
                $return = ReturnItemPromotionPurchaseDetailModel::with('returnBy', 'itemBy')
                    ->whereHas('returnBy', function ($query) {
                        $query->where('return_date', date('Y-m-d'));
                    })
                    ->get()
                    ->sortBy(function ($return) {
                        return $return->returnBy->return_date;
                    });
            }

            return datatables()
                ->of($return)
                ->editColumn('return_number', function ($data) {
                    return $data->returnBy->return_number;
                })
                ->editColumn('purchase_id', function ($data) {
                    return $data->returnBy->purchaseBy->order_number;
                })
                ->editColumn('return_date', function ($data) {
                    return date('d F Y', strtotime($data->returnBy->return_date));
                })
                ->editColumn('total', function ($data) {
                    return number_format((float) $data->returnBy->total);
                })
                ->editColumn('return_reason', function ($data) {
                    return $data->returnBy->return_reason;
                })
                ->editColumn('created_by', function ($data) {
                    return $data->returnBy->createdBy->name;
                })
                ->editColumn('product', function ($data) {
                    return $data->itemBy->name;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $data = [
            'title' => 'Return Item Promotion Purchase Report',
            'product' => ItemPromotionModel::oldest('name')->get(),
        ];
        return view('report.promotion_purchase_return', $data);
    }

    public function report_customer(Request $request)
    {
        if ($request->ajax()) {
            $customers = CustomerModel::oldest('name_cust')
                ->when($request->category, function ($cust) use ($request) {
                    $cust->where('category_cust_id', $request->category);
                })
                ->when($request->area, function ($cust) use ($request) {
                    $cust->where('area_cust_id', $request->area);
                })
                ->when($request->category, function ($cust) use ($request) {
                    $cust->where('category_cust_id', $request->category);
                })
                ->when($request->province, function ($cust) use ($request) {
                    $cust->where('province', $this->getNameProvince($request->province));
                })
                ->when($request->district, function ($cust) use ($request) {
                    $cust->where('city', $this->getNameCity($request->district));
                })
                ->when($request->sub_district, function ($cust) use ($request) {
                    $cust->where('district', $this->getNameDistrict($request->sub_district));
                })
                ->when($request->label, function ($cust) use ($request) {
                    $cust->where('label', $request->label);
                })
                ->when($request->status, function ($cust) use ($request) {
                    $cust->where('status', $request->status);
                })
                ->get();

            return datatables()
                ->of($customers)
                ->editColumn('category_cust_id', function ($data) {
                    return $data->categoryBy->category_name;
                })
                ->editColumn('area_cust_id', function ($data) {
                    return $data->areaBy->area_name;
                })
                ->editColumn('credit_limit', function ($data) {
                    return number_format($data->credit_limit);
                })
                ->editColumn('last_transaction', function ($data) {
                    if ($data->last_transaction == null) {
                        return '-';
                    } else {
                        return date('d F Y', strtotime($data->last_transaction));
                    }
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return 'Active';
                    } else {
                        return 'Non-active';
                    }
                })
                ->editColumn('created_by', function ($data) {
                    return $data->createdBy->name;
                })
                ->addIndexColumn()
                ->make(true);
        }

        $categories = CustomerCategoriesModel::oldest('category_name')->get();
        $areas = CustomerAreaModel::oldest('area_name')->get();
        $data = [
            'title' => 'Customer Report',
            'categories' => $categories,
            'areas' => $areas,
        ];

        return view('report.customer', $data);
    }
}
// function algorithm even odd
