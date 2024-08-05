<?php

namespace App\Http\Controllers;

use App\Models\MaterialModel;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\SubMaterialModel;
use App\Models\SubTypeModel;
use App\Models\UserWarehouseModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\ValueAddedTaxModel;

class CheckStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $cek_user = UserWarehouseModel::where('user_id', Auth::user()->id)->first();
        $cek_warehouse = UserWarehouseModel::where('user_id', Auth::user()->id)->count();
        if ($request->ajax()) {

            if (!empty($request)) {
                if ($cek_warehouse > 1) {
                    // * get stock sekarang
                    $stock = StockModel::with('warehouseBy', 'productBy')
                        ->whereHas('warehouseBy',function($query){
                           $query->where('type', [5]);
                        })
                        ->when($request->warehouse, function ($query) use ($request) {
                            $query->where('warehouses_id', $request->warehouse);
                        }, function ($query) {
                            $query->where('warehouses_id', 0);
                        })
                        ->whereHas('productBy', function ($query) use ($request) {
                            $query->when($request->product, function ($product) use ($request) {
                                $product->where('id', $request->product);
                            });
                            $query->where('status',1);
                            //! query untuk relasi dari product ke submaterial
                            $query->whereHas(
                                'sub_materials',
                                function ($q) use ($request) {
                                    $q->when($request->material, function ($query) use ($request) {
                                        $query->where('id', $request->material);
                                    });
                                }
                            );
                            //! query untuk relasi dari product ke subtype
                            $query->whereHas(
                                'sub_types',
                                function ($q) use ($request) {
                                    $q->when($request->type, function ($query) use ($request) {
                                        $query->where('id', $request->type);
                                    });
                                }
                            );
                        })
                        // ->latest()
                        ->get()
                        ->sortBy(function ($stock) {
                            return $stock->productBy->sub_materials->nama_sub_material . $stock->productBy->sub_types->type_name . $stock->productBy->nama_barang;
                        });
                } else {
                    $stock = StockModel::with('warehouseBy', 'productBy')
                        ->where('warehouses_id', $cek_user->warehouse_id)
                        ->whereHas('productBy', function ($query) use ($request) {
                            $query->when($request->product, function ($product) use ($request) {
                                $product->where('id', $request->product);
                            });
                            //! query untuk relasi dari product ke submaterial
                            $query->whereHas(
                                'sub_materials',
                                function ($q) use ($request) {
                                    $q->when($request->material, function ($query) use ($request) {
                                        $query->where('id', $request->material);
                                    });
                                }
                            );
                            //! query untuk relasi dari product ke subtype
                            $query->whereHas(
                                'sub_types',
                                function ($q) use ($request) {
                                    $q->when($request->type, function ($query) use ($request) {
                                        $query->where('id', $request->type);
                                    });
                                }
                            );
                        })
                        // ->latest()
                        ->get()
                        ->sortBy(function ($stock) {
                            return $stock->productBy->sub_materials->nama_sub_material . $stock->productBy->sub_types->type_name . $stock->productBy->nama_barang;
                        });
                }
            }
            return datatables()->of($stock)
                // ->editColumn('material', function ($data) {
                //     return $data->productBy->materials->nama_material;
                // })
                // ->editColumn('sub_material', function ($data) {
                //     return $data->productBy->sub_materials->nama_sub_material . ' ' .$data->productBy->sub_types->type_name .' '. $data->productBy->nama_barang;
                // })
                // ->editColumn('type', function ($data) {
                //     return $data->productBy->sub_types->type_name;
                // })
                ->editColumn('nama_barang', function ($data) use ($request) {
                    return $data->productBy->sub_materials->nama_sub_material . ' ' .$data->productBy->sub_types->type_name .' '. $data->productBy->nama_barang;
                })
                ->editColumn('warehouse', function ($data) use ($request) {
                    return $data->warehouseBy->warehouses;
                })
                ->editColumn('stock', function ($data) {
                    return $data->stock;
                })
                ->editColumn('price_list',function($data){
                    $ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float)$data->productBy->harga_jual_nonretail;
                    $ppn_cost = (float)$data->productBy->harga_jual_nonretail + $ppn;
                    return number_format($ppn_cost);
                })
               ->editColumn('price_retail', function ($data) {
                    $arrayharga = [];
                    $warehouse = $data->warehouses_id;
                    
                    foreach ($data->productBy->retailPriceBy as $retail) {
                        if ($warehouse == $retail->id_warehouse) {
                            $arrayharga[] = $retail->harga_jual;
                        }
                    }
                    
                    // Menghitung rata-rata harga jual (jika ada lebih dari satu harga)
                    $averagePrice = count($arrayharga) > 0 ? array_sum($arrayharga) / count($arrayharga) : 0;
                     $ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float)$averagePrice;
                    $ppn_cost = (float)$averagePrice + $ppn;
                    // Mengembalikan harga dalam format angka dengan pemisah ribuan
                    return number_format($ppn_cost);
                })

                ->addIndexColumn()
                ->make(true);
        }
        // $material_list = MaterialModel::all();
        $material = SubMaterialModel::oldest('nama_sub_material')->get();
        $product = ProductModel::oldest('nama_barang')->get();
        $warehouse = WarehouseModel::with('typeBy')->whereHas('typeBy', function ($query) {
            $query->where('type', 5);
        })->latest()->get();
        $type = SubTypeModel::oldest('type_name')->get();
        $data = [
            'title' => "RFS Stock ",
            'material_group' => $material,
            'product' => $product,
            'warehouse' => $warehouse,
            'type' => $type,
            'cek_warehouse' => $cek_warehouse,
            'cek_user' => $cek_user,
            // 'material_list' => $material_list,
        ];

        return view('cek_stok.index', $data);
    }
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
    public function store(Request $request)
    {
        abort(404);
    }

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
    public function destroy($id)
    {
        abort(404);
    }
}
