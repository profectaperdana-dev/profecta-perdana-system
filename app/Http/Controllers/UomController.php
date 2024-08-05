<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
// use App\Models\ValueAddedTaxModel;

use App\Models\DirectSalesCreditModel;
use App\Models\DirectSalesDetailModel;
use App\Models\DirectSalesModel;
use App\Models\UomModel;
use App\Models\ReturnModel;
use App\Models\ReturnRetailModel;
use App\Models\ReturnPurchaseModel;
use App\Models\ProductModel;
use App\Models\SalesOrderDetailModel;
use App\Models\SalesOrderModel;
use App\Models\SalesOrderCreditModel;
use App\Models\PurchaseOrderCreditModel;
use App\Models\PurchaseOrderDetailModel;
use App\Models\PurchaseOrderModel;
use App\Models\ValueAddedTaxModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

use App\Models\DiscountModel;
use App\Models\Finance\Coa;
use App\Models\Finance\Journal;
use App\Models\Finance\JournalDetail;
use App\Models\JurnalDetailModel;
use App\Models\JurnalModel;
use function App\Helpers\createJournal;
use function App\Helpers\createJournalDetail;
use function App\Helpers\changeSaldoTambah;
use function App\Helpers\changeSaldoKurang;

class UomController extends Controller
{
    public function ubahHargaDirect()
    {
        $title = 'Ubah Harga';
        $data = DB::table('direct_sales_details')
            ->join('products', 'direct_sales_details.product_id', '=', 'products.id')
            ->join('product_materials', 'product_materials.id', '=', 'products.id_material')
            ->join('product_sub_materials', 'product_sub_materials.id', '=', 'products.id_sub_material')
            ->join('product_sub_types', 'product_sub_types.id', '=', 'products.id_sub_type')
            ->join('direct_sales', 'direct_sales_details.direct_id', '=', 'direct_sales.id')
            ->select('*', 'direct_sales_details.id AS id_detail')
            ->get();

        // dd($data[0]->salesorders);
        $datas = [
            'title' => $title,
            'data' => $data
        ];

        // dd($data);
        return view('uoms.ubah_harga_direct', $datas);
    }
    public function ubahHargaDirect_get(Request $request, $id)
    {
        $data_request = request()->week;
        $data = DirectSalesDetailModel::where('id', $id)->first();
        $data->price = $data_request;
        $data->save();
        return response()->json(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Product Unit of Measurement';
        $data = UomModel::latest()->get();
        
       try {
            DB::beginTransaction();
            //Create direct Return Journal
            $return = ReturnModel::where('id_jurnal_hpp',null)->where('id_jurnal', '!=', null)
            ->get();
            
            dd($return);
            
            foreach($return as $item){
                $selected_so = SalesOrderModel::where('id', $item->sales_order_id)->first();
                // $get_hpp = Journal::where('id', $item->id_jurnal_hpp)->first();
                $get_hpp_detail = JournalDetail::where('journal_id', $selected_so->id_jurnal_hpp)->where('debit', '>', 0)->first();
                
                // foreach($get_hpp->jurnal_detail as $val){
                //     $val->delete();
                // }
                
                // $get_hpp->delete();
                // $hpp = createJournal(
                //     $item->return_date,
                //     'Persediaan Bertambah.' . $item->return_number,
                //     $selected_so->warehouse_id
                // );
    
                if ($hpp != "" && $hpp != null && $hpp != false) {
                    // $hpp_id = $hpp->id;
                    $hpp_excl = 0;
                    // foreach ($returnDetail as $hpp_c) {
                    //     $getProduct = ProductModel::where('id', $hpp_c->product_id)->first();
                    //     $hpp_excl = $hpp_excl + ($getProduct->hpp * $hpp_c->qty);
                    // }
    
                    // $current_ppn = (ValueAddedTaxModel::first()->ppn / 100);
                    // $hpp_ppn = $hpp_excl * $current_ppn;
                    // $hpp_incl = $hpp_excl + $hpp_ppn;
    
                    //Persediaan Barang Dagang
                    createJournalDetail(
                        $hpp,
                        '1-401',
                        $item->return_number,
                        $get_hpp_detail->debit,
                        0
                    );
    
                    //Pajak
                    // createJournalDetail(
                    //     $hpp,
                    //     '2-300',
                    //     $selected_return->return_number,
                    //     $hpp_ppn,
                    //     0
                    // );
    
                    //HPP
                    createJournalDetail(
                        $hpp,
                        '6-000',
                        $item->return_number,
                        0,
                        $get_hpp_detail->debit
                    );
            }
    
                $item->id_jurnal_hpp = $hpp;
                $item->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            throw $e;
        }
        
        dd('success');
        
        // $this->getUtangPO();
        
        return view('uoms.index', compact('title', 'data'));
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

        $request->validate([
            'uom' => 'required',

        ]);
        try {
            DB::beginTransaction();
            $model = new UomModel();
            $model->satuan = $request->get('uom');
            $model->created_by = Auth::user()->id;

            $model->save();

            DB::commit();
            return redirect('/product_uoms')->with('success', 'Add Data Unit of Measurement Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/product_uoms')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        $request->validate([
            'editSatuan' => 'required',

        ]);
        try {
            DB::beginTransaction();
            $model = UomModel::find($id);
            $model->satuan = $request->get('editSatuan');
            $model->created_by = Auth::user()->id;
            $model->save();

            DB::commit();
            return redirect('/product_uoms')->with('info', 'Changes Data Unit of Measurement Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/product_uoms')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
            $model = UomModel::find($id);
            $model->delete();

            DB::commit();
            return redirect('/product_uoms')->with('error', 'Delete Data Unit of Measurement Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/product_uoms')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    
    //EXPERIMENT SECTION
    function PisahGobat(){
        //Experiment
        //Ambil sales_order_id di Return
        $return = ReturnModel::where('isapproved',1)->pluck('sales_order_id')->all();
        //Ambil retail_id di ReturnRetail
        $return_retail = ReturnRetailModel::where('isapproved',1)->pluck('retail_id')->all();
        //Ambil product gobat
        $gobat = ProductModel::where('id_sub_material',4)->pluck('id')->all();
        
        $invoiceIndirect = SalesOrderDetailModel::whereNotIn('sales_orders_id', $return)
        ->whereIn('products_id', $gobat)
        ->whereHas('salesorders',function($query){
            $query->where('isapprove', 'approve');
            $query->where('isPaid', 0);
            $query->whereBetween('order_date',['2023-01-01', '2023-12-31']);
        })->get();
        $total= 0;
        foreach($invoiceIndirect as $item){
            $diskon = floatval($item->discount) / 100;
            $ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float)$item->price;
            $ppn_cost = (float)$item->price + $ppn;
            $hargaDiskon = (float) $ppn_cost * $diskon;
            $hargaAfterDiskon = (float) $ppn_cost -  $hargaDiskon - $item->discount_rp;
            $total = (float) $total + ($hargaAfterDiskon * $item->qty);
            // $total = round($total / 1.11);
            
            // $total_exc += $total;
        }
        $total_excl_indirect = round($total / 1.11);
        // dd($total_excl_indirect);
        
        $invoiceDirect = DirectSalesDetailModel::whereNotIn('direct_id', $return_retail)
        ->whereIn('product_id', $gobat)
        ->whereHas('directSalesBy', function($query){
            $query->where('isapproved', 1);
            $query->where('isPaid', 0);
            $query->whereBetween('order_date',['2023-01-01', '2023-12-31']);
        })->get();
        
        $total_direct = 0;
        foreach($invoiceDirect as $item){
            $diskon = floatval($item->discount) / 100;
            $ppn = (ValueAddedTaxModel::first()->ppn / 100) * (float)$item->price;
            $ppn_cost = (float)$item->price + $ppn;
            $hargaDiskon = (float) $ppn_cost * $diskon;
            $hargaAfterDiskon = (float) $ppn_cost -  $hargaDiskon - $item->discount_rp;
            $total_direct = (float) $total_direct + ($hargaAfterDiskon * $item->qty);
            // $total = round($total / 1.11);
            
            // $total_exc += $total;
        }
        $total_excl_direct = round($total_direct / 1.11);
        dd($total_excl_direct);
    }
    
    function getHPPByProductAndPeriod($product_id, $end_date){
        $return_purch = ReturnPurchaseModel::pluck('purchase_order_id')->all();
        
        $purchase = PurchaseOrderDetailModel::whereNotIn('purchase_order_id', $return_purch)
        ->where('product_id', $product_id)
        ->whereHas('purchaseOrderBy',function($query) use($end_date) {
            $query->where('isvalidated',1);
            $query->whereBetween('order_date',['2023-01-01', $end_date]);
        })
        ->get();
        
        $total_temp = 0;
        $total_qty=0;
        $get_product_purch = ProductModel::where('id', $product_id)->first();
        // Lakukan sesuatu dengan setiap grup
        foreach ($purchase as $item) {
            $price = $item->price;
            if(!$price){
                $price = Crypt::decryptString($get_product_purch->harga_beli);
            }
            $harga_diskon = $price - ($price * ($item->discount / 100));
            $total_temp += $harga_diskon * $item->qty;
            $total_qty += $item->qty;
        }
        $hpp = $total_temp / ($total_qty ?: 1);
        if($hpp <= 0){
            $hpp = Crypt::decryptString($get_product_purch->harga_beli);
        }
        return $hpp;
    }
    
    function getHppbyPurchase2023(){
        $return_purch = ReturnPurchaseModel::pluck('purchase_order_id')->all();
        
        $purchase = PurchaseOrderDetailModel::whereNotIn('purchase_order_id', $return_purch)
        ->whereHas('purchaseOrderBy',function($query){
            $query->where('isvalidated',1);
            $query->whereBetween('order_date',['2023-01-01', '2023-12-31']);
        })
        ->get()->groupBy('product_id');
        
        $result = '';
        foreach ($purchase as $productId => $group) {
            // $group adalah koleksi dari baris-baris yang memiliki product_id yang sama
            // $productId adalah id dari produk yang digunakan untuk mengelompokkan baris-baris tersebut
            $name_product = ProductModel::where('id', $productId)->first();
            $result .= $name_product->sub_materials->nama_sub_material . ' ' .$name_product->sub_types->type_name . ' ' . $name_product->nama_barang . ': ';
            
            $total_temp = 0;
            $total_qty=0;
            // Lakukan sesuatu dengan setiap grup
            foreach ($group as $item) {
                $price = $item->price;
                if(!$price){
                    $get_product_purch = ProductModel::where('id', $productId)->first();
                    $price = Crypt::decryptString($get_product_purch->harga_beli);
                }
                $harga_diskon = $price - ($price * ($item->discount / 100));
                $total_temp += $harga_diskon * $item->qty;
                $total_qty += $item->qty;
            }
            $hpp = $total_temp / $total_qty;
            $result .= $hpp;
            $result .= '<br>';
        }
        
        echo $result;
        die();
    }
    
    function getUtangPO(){
        $return_purch = ReturnPurchaseModel::pluck('purchase_order_id')->all();
        //Ambil product gobat
        $gobat = ProductModel::where('id_sub_material',4)->pluck('id')->all();
        
        $purchase = PurchaseOrderDetailModel::whereNotIn('purchase_order_id', $return_purch)
        ->whereNotIn('product_id', $gobat)
        ->whereHas('purchaseOrderBy',function($query){
            $query->where('isvalidated',1);
            // $query->where('isPaid',0);
            $query->whereBetween('order_date',['2023-10-03', '2023-12-31']);
        })
        ->get();
        
        $result = 'Utang PO Belum lunas 2023 Excl: ';
        $total_temp = 0;
        foreach ($purchase as $item) {
            $price = $item->price;
            if(!$price){
                $get_product_purch = ProductModel::where('id', $item->product_id)->first();
                $price = Crypt::decryptString($get_product_purch->harga_beli);
            }
            $harga_diskon = $price - ($price * ($item->discount / 100));
            $total_temp += $harga_diskon * $item->qty;
        }
        
        $result .= number_format($total_temp);
        echo $result;
        die();
    }
    
    function setCurrentHPP(){
        $return_purch = ReturnPurchaseModel::pluck('purchase_order_id')->all();
        
        $purchase = PurchaseOrderDetailModel::whereNotIn('purchase_order_id', $return_purch)
        ->whereHas('purchaseOrderBy',function($query){
            $query->where('isvalidated',1);
        })
        ->get()->groupBy('product_id');
        
        foreach ($purchase as $productId => $group) {
            // $group adalah koleksi dari baris-baris yang memiliki product_id yang sama
            // $productId adalah id dari produk yang digunakan untuk mengelompokkan baris-baris tersebut
            $name_product = ProductModel::where('id', $productId)->first();

            $total_temp = 0;
            $total_qty=0;
            // Lakukan sesuatu dengan setiap grup
            foreach ($group as $item) {
                $price = $item->price;
            }
            
        }
    }
}