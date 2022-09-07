<?php

namespace App\Http\Controllers;

use App\Models\MaterialModel;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\SubMaterialModel;
use App\Models\SubTypeModel;
use App\Models\UomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $title = 'Data Products';

        $data = ProductModel::join('uoms', 'uoms.id', '=', 'products.id_uom')
            ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
            ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
            ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
            ->latest('products.id')
            ->get(['products.*', 'uoms.satuan', 'product_materials.nama_material', 'product_sub_materials.nama_sub_material', 'product_sub_types.type_name']);
        // dd($data);
        return view('products.index', compact('data', 'title'));
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
                    ->get();
            } else {
                $product = ProductModel::join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->select('products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->get();
            }
            return response()->json($product);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function select()
    {
        try {
            $product = [];
            if (request()->has('q')) {
                $search = request()->q;
                $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('product_sub_types.type_name', 'LIKE', "%$search%")
                    ->where('stocks.warehouses_id', Auth::user()->warehouseBy->id)
                    ->orWhere('products.nama_barang', 'LIKE', "%$search%")
                    ->where('stocks.warehouses_id', Auth::user()->warehouseBy->id)
                    ->get();
            } else {
                $product = StockModel::join('products', 'products.id', '=', 'stocks.products_id')->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id')
                    ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
                    ->join('product_sub_materials', 'product_sub_materials.id', '=', 'product_sub_types.sub_material_id')
                    ->select('stocks.*', 'products.nama_barang AS nama_barang', 'products.id AS id', 'product_sub_types.type_name AS type_name', 'product_sub_materials.nama_sub_material AS nama_sub_material')
                    ->where('stocks.warehouses_id', Auth::user()->warehouseBy->id)
                    ->latest()->get();
            }
            return response()->json($product);
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
        $material = MaterialModel::latest()->get();
        // var_dump($data);
        return view('products.create', compact('title', 'uom', 'material', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_barang' => 'required',
                'no_seri' => 'required',
                'uom' => 'required',
                'material_grup' => 'required',
                'sub_material' => 'required',
                'sub_type' => 'required',
                'berat' => 'required',
                'harga_beli' => 'required',
                'harga_jual' => 'required',
                'harga_jual_nonretail' => 'required',
                'minstok' => 'required',
                'foto_barang' => 'required',

            ],
            [
                'nama_barang.required' => 'The Product Name is required',
                'no_seri.required' => 'The Serial Number is required',
                'uom.required' => 'You have to choose Unit of Measurement',
                'material_grup.required' => 'You have to choose Product Material',
                'sub_material.required' => 'You have to choose Product Sub Material',
                'sub_type.required' => 'You have to choose Product Sub Material Type',
                'berat.required' => 'The Product Weight is required',
                'harga_beli.required' => 'The Purchase Price is required',
                'harga_jual.required' => 'The Retail Selling Price is required',
                'harga_jual_nonretail.required' => 'The Non Retail Selling Price is required',
                'minstok.required' => 'The Min Stock required',
                'foto_barang.required' => 'You have to choose Product Photo File',

            ]
        );

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
        $model->harga_beli = $request->get('harga_beli');
        $model->harga_jual = $request->get('harga_jual');
        $model->harga_jual_nonretail = $request->get('harga_jual_nonretail');
        $model->minstok = $request->get('minstok');
        // $model->tgl_produksi = $request->get('tgl_produksi');
        $model->status = 1;
        $file = $request->foto_barang;
        $nama_file = time() . '.' . $file->getClientOriginalExtension();
        $file->move("foto_produk/", $nama_file);
        $model->foto_barang = $nama_file;
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect('/products')->with('success', 'Create data product  ' . $model->nama_barang . ' is success');
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
        $title = 'Data Products';
        $uom = UomModel::latest()->get();
        $material = MaterialModel::latest()->get();
        $subMaterial = SubMaterialModel::latest()->get();
        $data = ProductModel::where('id', $id)->firstOrFail();
        $data_sub = SubMaterialModel::select('nama_sub_material')->where('id', $data->id_sub_material)->firstOrFail();
        $data_sub_type = SubTypeModel::select('type_name')->where('id', $data->id_sub_type)->firstOrFail();
        // dd($data_sub);
        return view('products.edit', compact('title', 'uom', 'material', 'subMaterial', 'data', 'data_sub', 'data_sub_type'));
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
        $request->validate(
            [
                'nama_barang' => 'required',
                'no_seri' => 'required',
                'uom' => 'required',
                'material_grup' => 'required',
                'sub_material' => 'required',
                'berat' => 'required',
                'harga_beli' => 'required',
                'harga_jual' => 'required',
                'harga_jual_nonretail' => 'required',
                'minstok' => 'required',
                'status' => 'required',


            ],
            [
                'nama_barang.required' => 'The Product Name is required',
                'no_seri.required' => 'The Serial Number is required',
                'uom.required' => 'You have to choose Unit of Measurement',
                'material_grup.required' => 'You have to choose Product Material',
                'sub_material.required' => 'You have to choose Product Sub Material',
                'berat.required' => 'The Product Weight is required',
                'harga_beli.required' => 'The Purchase Price is required',
                'harga_jual.required' => 'The Retail Selling Price is required',
                'harga_jual_nonretail.required' => 'The Non Retail Selling Price is required',
                'minstok.required' => 'The Min Stock required',
                'status.required' => 'The Status is required',


            ]
        );
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
        $model->berat = $request->get('berat');
        $model->harga_beli = $request->get('harga_beli');
        $model->harga_jual = $request->get('harga_jual');
        $model->harga_jual_nonretail = $request->get('harga_jual_nonretail');
        $model->minstok = $request->get('minstok');
        $model->status = $request->get('status');

        $url_lama = $request->get('url_lama');
        if ($request->foto_barang == NULL) {
            $model->foto_barang = $url_lama;
        } else {

            unlink('foto_produk/' . $url_lama);
            $file = $request->foto_barang;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("foto_produk/", $nama_file);
            $model->foto_barang = $nama_file;
        }
        $model->created_by = Auth::user()->id;
        $model->save();
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
        unlink("foto_produk/" . $model->foto_barang);

        $stock = StockModel::where('products_id', $model->id)->delete();
        $model->delete();
        return redirect('/products')->with('error', 'Delete data product  ' . $model->nama_barang . ' is success');
    }
}
