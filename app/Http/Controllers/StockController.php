<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (Gate::allows('warehouse_keeper')) {
            $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%Profecta Perdana%');
                $query->where('warehouses_id', Auth::user()->warehouseBy->id);
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [5])->latest()->get();

            return view('stocks.index', compact('title', 'data', 'product', 'warehouse'));
        } else {
            $title = 'Data Stocks Product All Warehouse';
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%Profecta Perdana%');
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [5])->latest()->get();

            return view('stocks.index', compact('title', 'data', 'product', 'warehouse'));
        }
    }

    // ! stock c01
    public function stock_c01()
    {
        if (Gate::allows('warehouse_keeper')) {
            $id = Auth::user()->warehouseBy->id;
            $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(C0-1)%');
                $query->where('warehouses_id', Auth::user()->warehouseBy->id);
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [1])->latest()->get();

            return view('stocks.stock_c01', compact('title', 'data', 'product', 'warehouse'));
        } else {
            $title = 'Data Stocks Product All Warehouse';
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(C0-1)%');
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [1])->latest()->get();

            return view('stocks.stock_c01', compact('title', 'data', 'product', 'warehouse'));
        }
    }
    // ! stock c02
    public function stock_c02()
    {
        if (Gate::allows('warehouse_keeper')) {
            $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(C0-2)%');
                $query->where('warehouses_id', Auth::user()->warehouseBy->id);
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [2])->latest()->get();

            return view('stocks.stock_c02', compact('title', 'data', 'product', 'warehouse'));
        } else {
            $title = 'Data Stocks Product All Warehouse';
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(C0-2)%');
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [2])->latest()->get();

            return view('stocks.stock_c02', compact('title', 'data', 'product', 'warehouse'));
        }
    }
    // ! stock c03
    public function stock_c03()
    {
        if (Gate::allows('warehouse_keeper')) {
            $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(C0-3)%');
                $query->where('warehouses_id', Auth::user()->warehouseBy->id);
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [3])->latest()->get();

            return view('stocks.stock_c03', compact('title', 'data', 'product', 'warehouse'));
        } else {
            $title = 'Data Stocks Product All Warehouse';
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(C0-3)%');
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [3])->latest()->get();

            return view('stocks.stock_c03', compact('title', 'data', 'product', 'warehouse'));
        }
    }
    // ! stock ss-01
    public function stock_ss01()
    {
        if (Gate::allows('warehouse_keeper')) {
            $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(SS-01)%');
                $query->where('warehouses_id', Auth::user()->warehouseBy->id);
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [4])->latest()->get();

            return view('stocks.stock_ss01', compact('title', 'data', 'product', 'warehouse'));
        } else {
            $title = 'Data Stocks Product All Warehouse';
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(SS-01)%');
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [4])->latest()->get();

            return view('stocks.stock_ss01', compact('title', 'data', 'product', 'warehouse'));
        }
    }
    // ! stock supplier
    public function stock_supplier()
    {
        if (Gate::allows('warehouse_keeper')) {
            $title = 'Data Stocks Product ' . Auth::user()->warehouseBy->warehouses;
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(supplier)%');
                $query->where('warehouses_id', Auth::user()->warehouseBy->id);
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [6])->latest()->get();

            return view('stocks.stock_supplier', compact('title', 'data', 'product', 'warehouse'));
        } else {
            $title = 'Data Stocks Product All Warehouse';
            $data = StockModel::with('warehouseBy', 'productBy')->whereHas('warehouseBy', function ($query) {
                $query->where('warehouses', 'like', '%(supplier)%');
            })->latest()->get();
            $product = ProductModel::latest()->get();
            $warehouse = WarehouseModel::whereIn('type', [6])->latest()->get();

            return view('stocks.stock_supplier', compact('title', 'data', 'product', 'warehouse'));
        }
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

        $message_duplicate = "";
        $issaved = false;
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
            }
        }
        $cek_redirect = WarehouseModel::where('id', $request->get('warehouses_id'))->first();
        if ($cek_redirect->type == 1) {
            if (empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_c01')->with('success', 'Create Stocks Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_c01')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
            } else {
                return redirect('/stock_c01')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
            }
        } else if ($cek_redirect->type == 2) {
            if (empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_c02')->with('success', 'Create Stocks Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_c02')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
            } else {
                return redirect('/stock_c02')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
            }
        } else if ($cek_redirect->type == 3) {
            if (empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_c03')->with('success', 'Create Stocks Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_c03')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
            } else {
                return redirect('/stock_c03')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
            }
        } else if ($cek_redirect->type == 4) {
            if (empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_ss01')->with('success', 'Create Stocks Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_ss01')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
            } else {
                return redirect('/stock_ss01')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
            }
        } else if ($cek_redirect->type == 5) {
            if (empty($message_duplicate) && $issaved == true) {
                return redirect('/stocks')->with('success', 'Create Stocks Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {
                return redirect('/stocks')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
            } else {
                return redirect('/stocks')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
            }
        } else if ($cek_redirect->type == 6) {
            if (empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_supplier')->with('success', 'Create Stocks Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {
                return redirect('/stock_supplier')->with('success', 'Some of Stocks add maybe Success! ' . $message_duplicate);
            } else {
                return redirect('/stock_supplier')->with('error', 'Create Stocks Fail! Please make sure you have filled all the input');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function cekQty($product_id)
    {
        if (Gate::allows('isSuperAdmin') || Gate::allows('isFinance') || Gate::allows('isVerificator')) {
            $customer_id = request()->c;
            $customer = CustomerModel::where('id', $customer_id)->first();
            $qty = StockModel::select("id", "stock")
                ->where('warehouses_id', $customer->warehouseBy->id)
                ->where('products_id', $product_id)
                ->first();
        } else {
            $customer_id = request()->c;
            $customer = CustomerModel::where('id', $customer_id)->first();
            $qty = StockModel::select("id", "stock")
                ->where('warehouses_id', Auth::user()->warehouseBy->id)
                ->where('products_id', $product_id)
                ->first();
        }

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

        $model = StockModel::where('id', $id)->firstOrFail();
        $model->stock = $validate_data['stock_'];
        $model->save();
        return redirect()->back()->with('success', 'Stocks Edit Success');
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
        $model = StockModel::find($id);
        $model->delete();
        return redirect('')->back()->with('error', 'Delete Data Stocks Success');
    }
}
