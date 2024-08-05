<?php

namespace App\Http\Controllers;

use App\Models\CustomerModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\MaterialModel;
use App\Models\ProductCostModel;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\SubMaterialModel;
use App\Models\SubTypeModel;
use App\Models\UomModel;
use App\Models\ValueAddedTaxModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Gate;
use Products;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Create Product';
        // $harga = ProductModel::all();

        // foreach ($harga as $key => $value) {

        //     $harga_de = Crypt::decryptString($value->harga_beli);
        //     // dd($harga_de);
        //     $harga_de = str_replace(',', '.', $harga_de);
        //     // dd($harga_de);
        //     $harga_en = Crypt::encryptString($harga_de);
        //     $value->harga_beli = $harga_en;
        //     // dd(Crypt::decryptString($value->harga_beli));
        //     $value->save();
        // }


        $data = ProductModel::join('uoms', 'uoms.id', '=', 'products.id_uom')
            ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
            ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
            ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
            ->orderBy('product_sub_materials.nama_sub_material')
            ->orderBy('product_sub_types.type_name')
            ->orderBy('products.nama_barang')
            ->get(['products.*', 'uoms.satuan', 'product_materials.nama_material', 'product_sub_materials.nama_sub_material', 'product_sub_types.type_name']);
        return view('products.index', compact('data', 'title'));
    }

    public function selectPrice($id)
    {
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $data = ProductModel::where('id', $id)->first();
        $price = floatval(str_replace(',', '.', $data->harga_jual_nonretail));
        $harga_ppn = (float) $price *  (float)$ppn;
        $real_price = (float) $price + (float)$harga_ppn;
        return response()->json(number_format(round($real_price), 0, ',', '.'));
    }
    public function selectAll()
    {
        try {
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = ProductModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('products.nama_barang', 'LIKE', "%$search%")
                    ->orWhere('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->orWhere('product_sub_materials.nama_sub_material', 'LIKE', "%$search%")
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->oldest('product_sub_types.type_name')
                    ->oldest('products.nama_barang')
                    ->get();
            } else {
                $product = ProductModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->oldest('product_sub_types.type_name')
                    ->oldest('products.nama_barang')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function selectByWarehouse()
    {
        try {
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = ProductModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('stocks', 'stocks.products_id', '=', 'products.id')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material', 'stocks.stock AS stock')
                    ->where('stocks.warehouses_id', request()->c)
                    ->where('products.nama_barang', 'LIKE', "%$search%")
                    ->orWhere('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->orWhere('product_sub_materials.nama_sub_material', 'LIKE', "%$search%")
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->oldest('product_sub_types.type_name')
                    ->oldest('products.nama_barang')
                    ->get();
            } else {
                $product = ProductModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('stocks', 'stocks.products_id', '=', 'products.id')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material', 'stocks.stock AS stock')
                    ->where('stocks.warehouses_id', request()->c)
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->oldest('product_sub_types.type_name')
                    ->oldest('products.nama_barang')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function selectProduct($id)
    {
        $data = ProductModel::where('id_sub_type', $id)->oldest('nama_barang')->get();
        return response()->json($data);
    }
    public function select()
    {
        try {
            $warehouse_id = request()->w;
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;

                $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->where('stocks.warehouses_id', $warehouse_id)
                    ->whereIn('products.shown', ['non-retail', 'all'])
                    ->orWhere('products.nama_barang', 'LIKE', "%$search%")
                    ->where('stocks.warehouses_id', $warehouse_id)
                    ->whereIn('products.shown', ['non-retail', 'all'])
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->oldest('product_sub_types.type_name')
                    ->oldest('products.nama_barang')
                    ->get();
            } else {
                $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('stocks.warehouses_id', $warehouse_id)
                    ->whereIn('products.shown', ['non-retail', 'all'])
                    ->oldest('product_sub_materials.nama_sub_material')
                    ->oldest('product_sub_types.type_name')
                    ->oldest('products.nama_barang')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function selectCostDecrypted($product_id)
    {
        try {
            $get_harga_beli = PurchaseOrderDetailModel::where('purchase_order_id', request()->purchase_id)
                ->where('product_id', $product_id)->first();
            if($get_harga_beli == null || $get_harga_beli->price == null || $get_harga_beli->price == 0){
                $product = ProductModel::select('id', 'harga_jual_nonretail', 'harga_beli')
                ->where('id', $product_id)
                ->first();
                $harga_float = Crypt::decryptString($product->harga_beli);
                $price = str_replace(',', '.', $harga_float);
            }else{
                $price = $get_harga_beli->price;
                $price = str_replace(',', '.', $price);
            }
            

            return response()->json($price);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function selectCost($product_id)
    {
        try {
            $product = ProductModel::select('id', 'harga_jual_nonretail', 'harga_beli')
                ->where('id', $product_id)
                ->first();

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
        $title = 'Data Products';
        $data = new ProductModel();
        $uom = UomModel::latest()->get();
        $material = MaterialModel::oldest('nama_material')->get();
        $warehouse = WarehouseModel::select("id", "type", 'warehouses')
            ->whereIn('type', [5,1,7,2,3,4])
            ->orderBy('id_area','ASC')
            ->orderBy('warehouses','ASC')
            ->get();
        $warehouse_vendor = WarehouseModel::select("id", "type", 'warehouses')
            ->whereIn('type', [6])
            ->orderBy('id_area','ASC')
            ->orderBy('warehouses','ASC')
            ->get();    
            
        // var_dump($data);
        return view('products.create', compact('title', 'uom', 'material', 'data', 'warehouse', 'warehouse_vendor'));
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
        $materials = MaterialModel::select('code_materials')->where('id', $request->get('material_grup'))->firstOrFail();
        $sub_materials = SubMaterialModel::select('code_sub_material')->where('id', $request->get('sub_material'))->firstOrFail();
        $sub_types = SubTypeModel::select('code_sub_type')->where('id', $request->get('sub_type'))->firstOrFail();

        $model = new ProductModel();
        // dd($sub_types);
        $text = substr($request->get('nama_barang'), -4);
        $model->kode_barang = $materials->code_materials  .  $sub_materials->code_sub_material .  $sub_types->code_sub_type . $text;
        $model->nama_barang = $request->get('nama_barang');
        $model->no_seri = $request->get('no_seri');
        $model->id_uom = $request->get('uom');
        $model->id_material = $request->get('material_grup');
        $model->id_sub_material = $request->get('sub_material');
        $model->id_sub_type = $request->get('sub_type');
        $model->berat = $request->get('berat');

        $model->harga_beli = Crypt::encryptString($request->get('harga_beli'));
        $model->harga_jual_nonretail = $request->get('harga_jual_nonretail');
        $model->minstok = $request->get('minstok');
        $model->shown = $request->get('shown');
        $model->status = 1;
        if ($request->foto_barang != null) {
            $file = $request->foto_barang;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("foto_produk/", $nama_file);
            $model->foto_barang = $nama_file;
        }

        $model->created_by = Auth::user()->id;
        $saved = $model->save();
        if ($saved) {
            foreach ($request->tradeFields as $value) {
                $cost = new ProductCostModel();
                $cost->id_product = $model->id;
                $cost->id_warehouse = $value['id_warehouse'];
                $cost->harga_jual = $value['harga_jual'];
                //? check duplicate
                $check_duplicate = ProductCostModel::where('id_product', $cost->id_product)
                    ->where('id_warehouse', $cost->id_warehouse)
                    ->count();
                if ($check_duplicate > 0) {
                    $message_duplicate = "You enter duplication of products. Please recheck the PO you set.";
                    continue;
                } else {
                    $cost->save();
                }
            }
        }
        if (empty($message_duplicate) && $saved) {

            //! insert stock
            $model_stock = $request->input('cek_warehouse');
            $stock_migration = [];
            foreach ($model_stock as $key => $value) {
                array_push($stock_migration, $value);
                $data_stock =  new StockModel();
                $data_stock->products_id = $model->id;
                $data_stock->warehouses_id = $value;
                $data_stock->stock = 0;
                $data_stock->created_by = Auth::user()->id;
                $data_stock->save();
            }
            return redirect()->back()->with('success', 'Create data product ' . $model->nama_barang . ' success');
        } elseif (!empty($message_duplicate) && $saved) {
            //! insert stock
            $model_stock = $request->input('cek_warehouse');
            $stock_migration = [];
            foreach ($model_stock as $key => $value) {
                array_push($stock_migration, $value);
                $data_stock =  new StockModel();
                $data_stock->products_id = $model->id;
                $data_stock->warehouses_id = $value;
                $data_stock->stock = 0;
                $data_stock->created_by = Auth::user()->id;
                $data_stock->save();
            }
            return redirect()->back()->with('info', 'Create data product success but ! ' . $message_duplicate);
        } else {
            return redirect()->back()->with('error', 'Create data product Fail! Please make sure you have filled all the input');
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

        $title = 'Data Products';
        $uom = UomModel::latest()->get();
        $material = MaterialModel::latest()->get();
        $subMaterial = SubMaterialModel::latest()->get();
        $data = ProductModel::where('id', $id)->firstOrFail();
        $data_sub = SubMaterialModel::select('nama_sub_material')->where('id', $data->id_sub_material)->firstOrFail();
        $data_sub_type = SubTypeModel::select('type_name')->where('id', $data->id_sub_type)->firstOrFail();
        $stock = StockModel::where('products_id', $id)->get();
        // dd($stock);
        $warehouse = WarehouseModel::select("id", "type", 'warehouses')
                       ->whereIn('type', [5,1,7,2,3,4])
                                   ->orderBy('type','ASC')

                    //   ->orderBy('warehouses','ASC')

            ->get();
            $warehouse_vendor = WarehouseModel::select("id", "type", 'warehouses')
            ->whereIn('type', [6])
            ->orderBy('id_area','ASC')
            ->orderBy('warehouses','ASC')
            ->get();
        // dd($data_sub);
        return view('products.edit', compact('title', 'uom', 'material', 'subMaterial', 'data', 'data_sub', 'data_sub_type', 'warehouse', 'stock', 'warehouse_vendor'));
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
        // dd($request->all());

        //! Check Stock
        if ($request->input('cek_warehouse')) {

            $stock = stockModel::where('products_id', $id)->whereNotIn('warehouses_id', $request->input('cek_warehouse'))
                ->whereHas('warehouseBy', function ($query) {
                    $query->where('type', 5);
                })
                ->get();
            // dd($stock);
            foreach ($stock as $key => $value) {
                if ($value->stock > 0) {
                    return redirect()->back()->with('error', 'Update data product Fail! please check the remaining warehouse stock');
                } else {
                    $value->delete();
                }
            }
        } else {
            $stock = stockModel::where('products_id', $id)->get();
            foreach ($stock as $key => $value) {
                if ($value->stock > 0) {
                    return redirect()->back()->with('error', 'Update data product Fail! please check the remaining warehouse stock');
                } else {
                    $value->delete();
                }
            }
        }

        // ! insert information product
        $materials = MaterialModel::select('code_materials')->where('id', $request->get('material_grup'))->firstOrFail();
        $sub_materials = SubMaterialModel::select('code_sub_material')->where('id', $request->get('sub_material'))->firstOrFail();
        $sub_types = SubTypeModel::select('code_sub_type')->where('id', $request->get('sub_type'))->firstOrFail();
        $model = ProductModel::find($id);
        $text = substr($request->get('nama_barang'), -4);
        $model->kode_barang = $materials->code_materials  .  $sub_materials->code_sub_material .  $sub_types->code_sub_type . $text;
        $model->nama_barang = $request->get('nama_barang');
        $model->no_seri = $request->get('no_seri');
        $model->id_uom = $request->get('uom');
        $model->id_material = $request->get('material_grup');
        $model->id_sub_material = $request->get('sub_material');
        $model->id_sub_type = $request->get('sub_type');
        $model->berat = $request->get('berat');
        $model->harga_beli = Crypt::encryptString($request->get('harga_beli'));
        $model->harga_jual_nonretail = $request->get('harga_jual_nonretail');
        $model->minstok = $request->get('minstok');
        $model->shown = $request->get('shown');
        $model->status = $request->get('status');
        $url_lama = $request->get('url_lama');
        if ($request->foto_barang == NULL) {
            $model->foto_barang = $url_lama;
        } else {
            if ($model->foto_barang != null) {
                unlink('foto_produk/' . $url_lama);
            }
            $file = $request->foto_barang;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("foto_produk/", $nama_file);
            $model->foto_barang = $nama_file;
        }
        $model->created_by = Auth::user()->id;
        $saved =  $model->save();

        $products_arr = [];
        foreach ($request->tradeFields as $check) {
            array_push($products_arr, $check['id_warehouse']);
        }
        $duplicates = array_unique(array_diff_assoc($products_arr, array_unique($products_arr)));
        // dd($products_arr);



        if (!empty($duplicates)) {
            return redirect()->back()->with('error', "You enter duplicate data 'Retail Price'! Please check again!");
        }
        if ($saved) {

            if ($request->input('cek_warehouse') != NULL) {
                //! insert stock
                $model_stock = $request->input('cek_warehouse');
                $stock_migration = [];
                foreach ($model_stock as $key => $value) {
                    array_push($stock_migration, $value);

                    $data_stock = StockModel::where('products_id', $model->id)
                        ->where('warehouses_id', $value)
                        ->first();
                    if (!$data_stock) {

                        $data_stock = new StockModel();
                        $data_stock->products_id = $model->id;
                        $data_stock->warehouses_id = $value;
                        $data_stock->stock = 0;
                        $data_stock->created_by = Auth::user()->id;
                        $data_stock->save();
                    }
                }
            }


            // ! insert cost retail
            foreach ($request->tradeFields as $value) {
                $data = ProductCostModel::where('id_product', $model->id)
                    ->where('id_warehouse', $value['id_warehouse'])
                    ->first();
                if ($data) {
                    $data->id_warehouse = $value['id_warehouse'];
                    $data->harga_jual = $value['harga_jual'];
                    $data->save();
                } else {
                    $data = new ProductCostModel();
                    $data->id_product = $model->id;
                    $data->id_warehouse = $value['id_warehouse'];
                    $data->harga_jual = $value['harga_jual'];
                    $data->save();
                }
            }
        }
        ProductCostModel::where('id_product', $model->id)->whereNotIn('id_warehouse', $products_arr)->delete();
        // dd($test);
        return redirect('/products')->with('info', 'Edit data product  ' . $model->nama_barang . ' is success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = ProductModel::find($id);
        if ($model->foto_barang != null) {
            unlink("foto_produk/" . $model->foto_barang);
        }
        $stock = StockModel::where('products_id', $model->id)->delete();
        $model->delete();
        return redirect('/products')->with('error', 'Delete data product  ' . $model->nama_barang . ' is success');
    }

    public function getWarehouse()
    {
        try {
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = WarehouseModel::select("id", "type", 'warehouses')
                    ->where('warehouses', 'LIKE', "%$search%")
                    ->where('type', 5)
                    ->get();
            } else {
                $product = WarehouseModel::select("id", "type", 'warehouses')
                    ->where('type', 5)
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    
    // public function ApiGetProduct(Request $request){
        
    //     try {
    //         $product = ProductModel::where('created_by', $id)
    //             ->get()->all();

    //         // Jika data tidak ditemukan, kembalikan respons 404
    //         if ($product->isEmpty()) {
    //             return response()->json(
    //                 [
    //                     'status' => 404,
    //                     'message' => 'Product not found!',
    //                 ],
    //                 404,
    //             );
    //         }

    //         // Kembalikan data attendance dalam format JSON
    //         return response()->json([
    //             'status' => 200,
    //             'message' => 'Get Product successfully!',
    //             'data' => $product,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 500,
    //             'message' => 'Failed to fetch plan: ' . $e->getMessage(),
    //         ]);
    //     }
        
    // }
}
