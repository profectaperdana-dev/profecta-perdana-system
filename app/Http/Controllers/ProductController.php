<?php

namespace App\Http\Controllers;

use App\Models\MaterialModel;
use App\Models\ProductModel;
use App\Models\SubMaterialModel;
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
            ->latest('products.id')
            ->get(['products.*', 'uoms.*', 'product_materials.*', 'product_sub_materials.*']);

        // $data = ProductModel::latest()->limit('10')->get();
        // var_dump($data);
        return view('products.index', compact('data', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Data Products';
        $uom = UomModel::latest()->get();
        $material = MaterialModel::latest()->get();
        $subMaterial = SubMaterialModel::latest()->get();
        // var_dump($data);
        return view('products.create', compact('title', 'uom', 'material', 'subMaterial'));
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
                'kode_barang' => 'required',
                'nama_barang' => 'required',
                'no_seri' => 'required',
                'uom' => 'required',
                'material_grup' => 'required',
                'sub_material' => 'required',
                'berat' => 'required',
                'harga_beli' => 'required',
                'harga_jual' => 'required',
                'harga_jual_nonretail' => 'required',
                'qty' => 'required',
                'minstok' => 'required',
                'status' => 'required',
                'foto_barang' => 'required',

            ],
            [
                'kode_barang.required' => 'The Product Code is required',
                'nama_barang.required' => 'The Product Name is required',
                'no_seri.required' => 'The Serial Number is required',
                'uom.required' => 'You have to choose Unit of Measurement',
                'material_grup.required' => 'You have to choose Product Material',
                'sub_material.required' => 'You have to choose Product Sub Material',
                'berat.required' => 'The Product Weight is required',
                'harga_beli.required' => 'The Purchase Price is required',
                'harga_jual.required' => 'The Retail Selling Price is required',
                'harga_jual_nonretail.required' => 'The Non Retail Selling Price is required',
                'qty.required' => 'The Qty is required',
                'minstok.required' => 'The Min Stock required',
                'status.required' => 'The Status is required',
                'foto_barang.required' => 'You have to choose Product Photo File',

            ]
        );

        $model = new ProductModel();
        $model->kode_barang = $request->get('kode_barang');
        $model->nama_barang = $request->get('nama_barang');
        $model->no_seri = $request->get('no_seri');
        $model->id_uom = $request->get('uom');
        $model->id_material = $request->get('material_grup');
        $model->id_sub_material = $request->get('sub_material');
        $model->berat = $request->get('berat');
        $model->harga_beli = $request->get('harga_beli');
        $model->harga_jual = $request->get('harga_jual');
        $model->harga_jual_nonretail = $request->get('harga_jual_nonretail');
        $model->qty = $request->get('qty');
        $model->minstok = $request->get('minstok');
        $model->status = $request->get('status');


        $file = $request->foto_barang;
        $nama_file = time() . '.' . $file->getClientOriginalExtension();
        $file->move("foto_produk/", $nama_file);
        $model->foto_barang = $nama_file;


        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect('/products')->with('success', 'Add Data Product Success');
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
        //
    }
}
