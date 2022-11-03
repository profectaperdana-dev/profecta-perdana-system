<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\AccountSubModel;
use App\Models\AccountSubTypeModel;
use App\Models\AssetModel;
use App\Models\DepreciationModel;
use App\Models\JurnalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //* view data from database
        $data = JurnalModel::latest()->get();
        $title = 'Jurnal';
        return view('accounting.index', compact('data'));
    }
    public function jurnal()
    {
        //* view data from database
        $data = JurnalModel::orderBy('date', 'DESC')->get();
        $title = 'Journal';
        return view('accounting.jurnal', compact('data', 'title'));
    }

    public function createExpenses()
    {
        $title = 'Create Expenses';
        $account = AccountModel::latest()->get();
        return view('accounting.create_expanse', compact('title', 'account'));
    }

    public function storeExpenses(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'account_sub_id' => 'required',
            'account_sub_type_id' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);
        $data = [
            'account_id' => $request->account_id,
            'account_sub_id' => $request->account_sub_id,
            'account_sub_type_id' => $request->account_sub_type_id,
            'description' => $request->description,
            'amount' => $request->amount,
            'date' => $request->date,
        ];
        JurnalModel::create($data);
        return redirect()->route('accounting.jurnal')->with('success', 'Data has been added');
    }

    public function select($id)
    {
        $sub_materials = [];
        $material_id = $id;

        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = AccountSubModel::select("id", "account_id", "code", "name")
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('code', 'LIKE', "%$search%")
                ->where('account_id', $material_id)
                ->get();
        } else {
            $sub_materials = AccountSubModel::where('account_id', $material_id)->get();
        }
        return response()->json($sub_materials);
    }
    public function select_type($id)
    {
        $sub_materials = [];
        $material_id = $id;

        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = AccountSubTypeModel::select("id", "account_sub_id", "code", "name")
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('code', 'LIKE', "%$search%")
                ->where('account_sub_id', $material_id)
                ->get();
        } else {
            $sub_materials = AccountSubTypeModel::where('account_sub_id', $material_id)->get();
        }
        return response()->json($sub_materials);
    }

    public function profit_loss(Request $request)
    {
        if (!empty($request->from_date)) {
            $income = JurnalModel::where('account_code', '1')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $load_discount = JurnalModel::where('account_code', '2.2.703.804.102')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $load_return = JurnalModel::where('account_code', '2.2.703.804.8')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $load_hpp = JurnalModel::where('account_code', '2')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $load_return_hpp = JurnalModel::where('account_code', '3')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');

            //* get data
            $komunikasi =   JurnalModel::where('account_code', '2.2.703.802.2.11')->whereBetween('date', array($request->from_date, $request->to_date))->latest()->get();
            $pembelian = JurnalModel::where('account_code', '2.2.703.802.2.1')->whereBetween('date', array($request->from_date, $request->to_date))->latest()->get();
            $promosi = JurnalModel::where('account_code', '2.2.703.802.2.16')->whereBetween('date', array($request->from_date, $request->to_date))->latest()->get();
            $gaji = JurnalModel::where('account_code', '2.2.703.802.2.13')->whereBetween('date', array($request->from_date, $request->to_date))->latest()->get();
            $kendaraan = JurnalModel::where('account_code', '2.2.703.802.2.17')->whereBetween('date', array($request->from_date, $request->to_date))->latest()->get();
            $gedung = JurnalModel::where('account_code', '2.2.703.802.2.18')->whereBetween('date', array($request->from_date, $request->to_date))->latest()->get();
            $penjualan = JurnalModel::where('account_code', '2.2.703.802.2.19')->whereBetween('date', array($request->from_date, $request->to_date))->latest()->get();
            $kantor = JurnalModel::where('account_code', '2.2.703.802.2.20')->whereBetween('date', array($request->from_date, $request->to_date))->latest()->get();


            //* opersional
            $biaya_komunikasi =  JurnalModel::where('account_code', '2.2.703.802.2.11')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $biaya_pembelian = JurnalModel::where('account_code', '2.2.703.802.2.1')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $biaya_promosi = JurnalModel::where('account_code', '2.2.703.802.2.16')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $biaya_gaji = JurnalModel::where('account_code', '2.2.703.802.2.13')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $biaya_kendaraan = JurnalModel::where('account_code', '2.2.703.802.2.17')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $biaya_gedung = JurnalModel::where('account_code', '2.2.703.802.2.18')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $biaya_penjualan = JurnalModel::where('account_code', '2.2.703.802.2.19')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
            $biaya_kantor = JurnalModel::where('account_code', '2.2.703.802.2.20')->whereBetween('date', array($request->from_date, $request->to_date))->sum('total');
        } else {
            $income = JurnalModel::where('account_code', '1')->whereMonth('date', date('m'))->sum('total');
            $load_discount = JurnalModel::where('account_code', '2.2.703.804.102')->whereMonth('date', date('m'))->sum('total');
            $load_return = JurnalModel::where('account_code', '2.2.703.804.8')->whereMonth('date', date('m'))->sum('total');
            $load_hpp = JurnalModel::where('account_code', '2')->whereMonth('date', date('m'))->sum('total');
            $load_return_hpp = JurnalModel::where('account_code', '3')->whereMonth('date', date('m'))->sum('total');

            //* operasional komunikasi
            $biaya_komunikasi =  JurnalModel::where('account_code', '2.2.703.802.2.11')->whereMonth('date', date('m'))->sum('total');
            $komunikasi =   JurnalModel::where('account_code', '2.2.703.802.2.11')->whereMonth('date', date('m'))->latest()->get();

            //* operasional pembelian
            $biaya_pembelian = JurnalModel::where('account_code', '2.2.703.802.2.1')->whereMonth('date', date('m'))->sum('total');
            $pembelian = JurnalModel::where('account_code', '2.2.703.802.2.1')->whereMonth('date', date('m'))->latest()->get();

            //* operasional promosi
            $biaya_promosi = JurnalModel::where('account_code', '2.2.703.802.2.16')->whereMonth('date', date('m'))->sum('total');
            $promosi = JurnalModel::where('account_code', '2.2.703.802.2.16')->whereMonth('date', date('m'))->latest()->get();

            //* operasional gaji
            $biaya_gaji = JurnalModel::where('account_code', '2.2.703.802.2.13')->whereMonth('date', date('m'))->sum('total');
            $gaji = JurnalModel::where('account_code', '2.2.703.802.2.13')->whereMonth('date', date('m'))->latest()->get();

            //* operasional kendaraan
            $biaya_kendaraan = JurnalModel::where('account_code', '2.2.703.802.2.17')->whereMonth('date', date('m'))->sum('total');
            $kendaraan = JurnalModel::where('account_code', '2.2.703.802.2.17')->whereMonth('date', date('m'))->latest()->get();

            //* operasional gedung
            $biaya_gedung = JurnalModel::where('account_code', '2.2.703.802.2.18')->whereMonth('date', date('m'))->sum('total');
            $gedung = JurnalModel::where('account_code', '2.2.703.802.2.18')->whereMonth('date', date('m'))->latest()->get();

            //* operasional penjualan
            $biaya_penjualan = JurnalModel::where('account_code', '2.2.703.802.2.19')->whereMonth('date', date('m'))->sum('total');
            $penjualan = JurnalModel::where('account_code', '2.2.703.802.2.19')->whereMonth('date', date('m'))->latest()->get();

            //* operasional kantor
            $biaya_kantor = JurnalModel::where('account_code', '2.2.703.802.2.20')->whereMonth('date', date('m'))->sum('total');
            $kantor = JurnalModel::where('account_code', '2.2.703.802.2.20')->whereMonth('date', date('m'))->latest()->get();
        }

        // dd($income);
        $data = [
            'title' => 'Profit and Loss',
            'income' => $income,
            'load_discount' => $load_discount,
            'load_return' => $load_return,
            'load_hpp' => $load_hpp,
            'load_return_hpp' => $load_return_hpp,

            //* sum operasional
            'biaya_komunikasi' => $biaya_komunikasi,
            'biaya_pembelian' => $biaya_pembelian,
            'biaya_promosi' => $biaya_promosi,
            'biaya_gaji' => $biaya_gaji,
            'biaya_kendaraan' => $biaya_kendaraan,
            'biaya_gedung' => $biaya_gedung,
            'biaya_penjualan' => $biaya_penjualan,
            'biaya_kantor' => $biaya_kantor,

            //*get data
            'pembelian' => $pembelian,
            'komunikasi' => $komunikasi,
            'gaji' => $gaji,
            'promosi' => $promosi,
            'kendaraan' => $kendaraan,
            'gedung' => $gedung,
            'penjualan' => $penjualan,
            'kantor' => $kantor,



        ];

        return view('accounting.loss_profit', $data);
    }

    public function store_expense(Request $request)
    {
        //* validate
        $request->validate([
            "account_id" => "required",
            "sub_account_id" => "required",
            "date" => "required",
            "memo" => "required",
            "total" => "required"
        ]);

        $model = new JurnalModel();
        $model->date = $request->date;
        $account_id = AccountModel::where('id', $request->account_id)->first();
        $sub_account_id = AccountSubModel::where('id', $request->sub_account_id)->first();
        $type_account_id = "";
        if ($request->type_account_id) {
            $type_account_id = AccountSubTypeModel::where('id', $request->type_account_id)->first();
        }
        if ($account_id->code == "2.2.703.802.2") {
            $model->code = $sub_account_id->name . " - " . $type_account_id->name;
            $model->account_code = $sub_account_id->code;
        } else {
            $model->code = $sub_account_id->name;
            $model->account_code = $sub_account_id->code;
        }
        $model->memo = $request->memo;
        $model->total = $request->total;
        $model->status = 1;
        $saved = $model->save();

        if ($saved) {
            return redirect('/expenses/create')->with('success', 'Add Expense Success!');
        } else {
            return redirect('/expenses/create')->with('error', 'Add Expense Fail!');
        }
    }

    public function depreciation()
    {
        $all_depreciation = AssetModel::latest()->get();

        $smallest_date = AssetModel::all('acquisition_year')->min('acquisition_year');
        $smallest_year = date('Y', strtotime($smallest_date));
        $current_year = date('Y') - 1;
        $data = [
            'title' => 'Depreciation',
            'depreciations' => $all_depreciation,
            'smallest_year' => $smallest_year,
            'current_year' => $current_year
        ];
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
    public function store(Request $request)
    {
        //* 
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
