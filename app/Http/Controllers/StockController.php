<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\MaterialModel;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\SubTypeModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use phpDocumentor\Reflection\Types\Null_;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = ' Stock Profecta Perdana';
        $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
            $query->where('type', 5);
        })->whereHas('productBy', function ($query) {
            $query->whereHas('sub_materials', function ($queryy) {
                $queryy->oldest('nama_sub_material');
            });
        })->get();
        $product = ProductModel::with('sub_materials', 'sub_types')->whereHas('sub_materials', function ($query) {
            $query->oldest('nama_sub_material');
        })

            ->get();
        $warehouse = WarehouseModel::whereIn('type', [5])->latest()->get();
        // $get_today_stock = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
        //             $query->where('type', 5);
        //         })
        //         ->whereDate('created_at', date('Y-m-d'))
        //         // ->where('deleted_at', '!=', null)
        //         ->withTrashed()
        //         // ->take(5)
        //             ->get();
        // foreach($get_today_stock as $item){
        //     $item->forceDelete();
        // }            
                    
        // dd($get_today_stock);            
        //  foreach ($product as $p) {
        //     foreach ($warehouse as $w) {

        //         $datas = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
        //             $query->where('type', 6);
        //         })
        //             ->where('products_id', $p->id)
        //             ->where('warehouses_id', $w->id)
        //             ->first();

        //         if (!$datas) {
        //             $stock = new StockModel();
        //             $stock->products_id = $p->id;
        //             $stock->warehouses_id = $w->id;
        //             $stock->stock = 0;
        //             $stock->save();
        //         }
        //     }
        // }
        return view('stocks.index', compact('title', 'data', 'product', 'warehouse'));
    }
    public function reportStock(Request $request)
    {
        if ($request->ajax()) {

            if (!empty($request)) {
                // * get stock sekarang
                $stock = StockModel::with('warehouseBy', 'productBy')
                    ->when($request->warehouse, function ($query) use ($request) {
                        $query->where('warehouses_id', $request->warehouse);
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
                    ->latest()
                    ->get();
            } else {
                $stock = StockModel::with('warehouseBy', 'productBy')
                    ->latest()->get();
            }
            return datatables()->of($stock)
                ->editColumn('material', function ($data) {
                    return $data->productBy->materials->nama_material;
                })
                ->editColumn('sub_material', function ($data) {
                    return $data->productBy->sub_materials->nama_sub_material;
                })
                ->editColumn('type', function ($data) {
                    return $data->productBy->sub_types->type_name;
                })
                ->editColumn('nama_barang', function ($data) use ($request) {
                    return $data->productBy->nama_barang;
                })
                ->editColumn('warehouse', function ($data) use ($request) {
                    return $data->warehouseBy->warehouses;
                })
                ->editColumn('satuan', function ($data) {
                    return $data->productBy->uoms->satuan;
                })
                ->addIndexColumn()
                ->make(true);
        }
        $material = MaterialModel::all();
        $product = ProductModel::all();
        $warehouse = WarehouseModel::all();
        $type = SubTypeModel::all();

        $data = [
            'title' => "Data Stock Report ",
            'material_group' => $material,
            'product' => $product,
            'warehouse' => $warehouse,
            'type' => $type,
        ];
        return view('stocks.index', $data);
    }
    // ! stock c01
    public function stock_c01()
    {
        
        $title = 'Stock C01';
        $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
            $query->where('type', 1);
        })->latest()->get();
        $product = ProductModel::latest()->get();
        $warehouse = WarehouseModel::whereIn('type', [1])->latest()->get();

        return view('stocks.stock_c01', compact('title', 'data', 'product', 'warehouse'));
        // }
    }
    // ! stock c02
    public function stock_c02()
    {
        
        $title = 'Stock C02';
        $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
            $query->where('type', 2);
        })->latest()->get();
        $product = ProductModel::latest()->get();
        $warehouse = WarehouseModel::whereIn('type', [2])->latest()->get();
        
        foreach ($product as $p) {
            foreach ($warehouse as $w) {

                $datas = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                    $query->where('type', 2);
                })
                    ->where('products_id', $p->id)
                    ->where('warehouses_id', $w->id)
                    ->first();

                if (!$datas) {
                    $stock = new StockModel();
                    $stock->products_id = $p->id;
                    $stock->warehouses_id = $w->id;
                    $stock->stock = 0;
                    $stock->save();
                }
            }
        }
       
        return view('stocks.stock_c02', compact('title', 'data', 'product', 'warehouse'));
       
    }
    // ! stock c03
    public function stock_c03()
    {
        
        $title = 'Stock C03';
        $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
            $query->where('type', 3);
        })->latest()->get();
        $product = ProductModel::latest()->get();
        $warehouse = WarehouseModel::whereIn('type', [3])->latest()->get();
        foreach ($product as $p) {
            foreach ($warehouse as $w) {

                $datas = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                    $query->where('type', 3);
                })
                    ->where('products_id', $p->id)
                    ->where('warehouses_id', $w->id)
                    ->first();

                if (!$datas) {
                    $stock = new StockModel();
                    $stock->products_id = $p->id;
                    $stock->warehouses_id = $w->id;
                    $stock->stock = 0;
                    $stock->save();
                }
            }
        }
//  foreach ($product as $p) {
//             foreach ($warehouse as $w) {

//                 $datas = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
//                     $query->where('type', 3);
//                 })
//                     ->where('products_id', $p->id)
//                     ->where('warehouses_id', $w->id)
//                     ->first();

//                 if (!$datas) {
//                     $stock = new StockModel();
//                     $stock->products_id = $p->id;
//                     $stock->warehouses_id = $w->id;
//                     $stock->stock = 0;
//                     $stock->save();
//                 }
//             }
//         }
        return view('stocks.stock_c03', compact('title', 'data', 'product', 'warehouse'));
        // }
    }
    // ! stock ss-01
    public function stock_ss01()
    {
        // if (Gate::allows('warehouse_keeper')) {
        //     $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
        //     $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
        //         $query->where('warehouses', 'like', '%(CVS)%');
        //         $query->where('warehouses_id', Auth::user()->warehouseBy->id);
        //     })->latest()->get();
        //     $product = ProductModel::latest()->get();
        //     $warehouse = WarehouseModel::whereIn('type', [4])->latest()->get();

        //     return view('stocks.stock_ss01', compact('title', 'data', 'product', 'warehouse'));
        // } else {
        $title = 'Stock SS01';
        $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
            $query->where('warehouses', 'like', '%(CVS)%');
        })->latest()->get();
        $product = ProductModel::latest()->get();
        $warehouse = WarehouseModel::whereIn('type', [4])->latest()->get();

        return view('stocks.stock_ss01', compact('title', 'data', 'product', 'warehouse'));
        // }
    }
    // ! stock supplier
    public function stock_supplier()
    {
        // if (Gate::allows('warehouse_keeper')) {
        //     $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
        //     $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
        //         $query->where('warehouses', 'like', '%(SUPPLIER)%');
        //         $query->where('warehouses_id', Auth::user()->warehouseBy->id);
        //     })->latest()->get();
        //     $product = ProductModel::latest()->get();
        //     $warehouse = WarehouseModel::whereIn('type', [6])->latest()->get();

        //     return view('stocks.stock_supplier', compact('title', 'data', 'product', 'warehouse'));
        // } else {
        $title = 'Stock Vendor';
        $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
            $query->where('type', 6);
        })->latest()->get();
        $product = ProductModel::latest()->get();
        $warehouse = WarehouseModel::whereIn('type', [6])->oldest('warehouses')->get();
        
        //  foreach ($product as $p) {
        //     foreach ($warehouse as $w) {

        //         $datas = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
        //             $query->where('type', 6);
        //         })
        //             ->where('products_id', $p->id)
        //             ->where('warehouses_id', $w->id)
        //             ->first();

        //         if (!$datas) {
        //             $stock = new StockModel();
        //             $stock->products_id = $p->id;
        //             $stock->warehouses_id = $w->id;
        //             $stock->stock = 0;
        //             $stock->save();
        //         }
        //     }
        // }

        return view('stocks.stock_supplier', compact('title', 'data', 'product', 'warehouse'));
        // }
    }


    public function cekProduk(Request $request)
    {
        try {
            $product = [];
            if ($request->has('q')) {
                $search = $request->q;
                $product = ProductModel::select("id", "nama_barang")
                    ->where('nama_barang', 'LIKE', "%$search%")
                    ->get();
            } else {
                $product = ProductModel::latest()->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
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

        $validate_data = $request->validate([
            "warehouses_id" => "required|numeric",
            "stockFields.*.product_id" => "required|numeric",
            "stockFields.*.stock" => "required|numeric"
        ]);

        try {
            DB::beginTransaction();
            $message_duplicate = "";
            $issaved = true;
            foreach ($request->stockFields as $key => $value) {
                $model = new StockModel();
                $model->warehouses_id = $request->get('warehouses_id');
                $model->products_id = $value['product_id'];
                $model->stock = $value['stock'];
                $model->created_by = Auth::user()->id;
                $cek = StockModel::where('products_id', $value['product_id'])
                    ->where('warehouses_id', $request->get('warehouses_id'))
                    ->count();

                if ($cek > 0) {
                    $message_duplicate = "You enter duplication of products. Please recheck the Stock you set.";
                    continue;
                } else {
                    $issaved = $model->save();
                    // DB::commit();
                }
            }
            // dd($issaved);

            $cek_redirect = WarehouseModel::where('id', $request->get('warehouses_id'))->first();
            if ($cek_redirect->type == 1) {
                if (empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_c01')->with('success', 'Create Stocks Success');
                } elseif (!empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_c01')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
                } else {

                    DB::rollback();
                    return redirect('/stock_c01')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
                }
            } else if ($cek_redirect->type == 2) {
                if (empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_c02')->with('success', 'Create Stocks Success');
                } elseif (!empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_c02')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
                } else {

                    DB::rollback();
                    return redirect('/stock_c02')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
                }
            } else if ($cek_redirect->type == 3) {
                if (empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_c03')->with('success', 'Create Stocks Success');
                } elseif (!empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_c03')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
                } else {

                    DB::rollback();
                    return redirect('/stock_c03')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
                }
            } else if ($cek_redirect->type == 4) {
                if (empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_ss01')->with('success', 'Create Stocks Success');
                } elseif (!empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_ss01')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
                } else {

                    DB::rollback();
                    return redirect('/stock_ss01')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
                }
            } else if ($cek_redirect->type == 5) {
                if (empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stocks')->with('success', 'Create Stocks Success');
                } elseif (!empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stocks')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
                } else {

                    DB::rollback();
                    return redirect('/stocks')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
                }
            } else if ($cek_redirect->type == 6) {
                if (empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_vendor')->with('success', 'Create Stocks Success');
                } elseif (!empty($message_duplicate) && $issaved == true) {

                    DB::commit();
                    return redirect('/stock_vendor')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
                } else {

                    DB::rollback();
                    return redirect('/stock_vendor')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    public function stock_vendor()
    {
        // if (Gate::allows('warehouse_keeper')) {
        //     $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
        //     $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
        //         $query->where('warehouses', 'like', '%(SUPPLIER)%');
        //         $query->where('warehouses_id', Auth::user()->warehouseBy->id);
        //     })->latest()->get();
        //     $product = ProductModel::latest()->get();
        //     $warehouse = WarehouseModel::whereIn('type', [6])->latest()->get();

        //     return view('stocks.stock_supplier', compact('title', 'data', 'product', 'warehouse'));
        // } else {
        $title = 'Data Stock Product All Warehouse';
        $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
            $query->where('type', 6);
        })->latest()->get();
        $product = ProductModel::latest()->get();
        $warehouse = WarehouseModel::whereIn('type', [6])->oldest('warehouses')->get();
        
        // foreach ($product as $p) {
        //     foreach ($warehouse as $w) {

        //         $datas = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
        //             $query->where('type', 6);
        //         })
        //             ->where('products_id', $p->id)
        //             ->where('warehouses_id', $w->id)
        //             ->first();

        //         if (!$datas) {
        //             $stock = new StockModel();
        //             $stock->products_id = $p->id;
        //             $stock->warehouses_id = $w->id;
        //             $stock->stock = 0;
        //             $stock->save();
        //         }
        //     }
        // }

        return view('stocks.stock_supplier', compact('title', 'data', 'product', 'warehouse'));
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function cekQty($product_id)
    {
        $warehouse_id = request()->w;

        $qty = StockModel::select("id", "stock")
            ->where('warehouses_id', $warehouse_id)
            ->where('products_id', $product_id)
            ->first();

        return response()->json($qty);
    }
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
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        $validate_data = $request->validate([
            "stock_" => "required|numeric",

        ]);

        try {
            DB::beginTransaction();

            $model = StockModel::where('id', $id)->firstOrFail();
            $model->stock = $validate_data['stock_'];
            $model->save();

            DB::commit();
            return redirect()->back()->with('success', 'Stocks Edit Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        if (!Gate::allows('level1')) {
            abort(403);
        }

        try {
            DB::beginTransaction();
            $model = StockModel::find($id);
            $model->delete();

            DB::commit();
            return redirect('')->back()->with('error', 'Delete Data Stocks Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
