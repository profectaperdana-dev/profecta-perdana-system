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
use Illuminate\Support\Facades\Gate;

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
    public function jurnal(Request $request)
    {
        //! view data from database
        if ($request->ajax()) {

            if (!empty($request->from_date)) {
                $invoice = JurnalModel::whereBetween('date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $invoice = JurnalModel::orderBy('date', 'DESC')
                    ->latest()
                    ->get();
            }
            return datatables()->of($invoice)
                ->editColumn('total', function ($data) {
                    return  'Rp ' . number_format($data->total, 0, ',', '.');
                })
                ->editColumn('date', function ($data) {
                    return date('d M Y', strtotime($data->date));
                })

                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($invoice) {
                    $account = AccountSubTypeModel::latest()->get();
                    return view('accounting._option', compact('invoice', 'account'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $data = [
            'title' => "Journal in : " . Auth::user()->warehouseBy->warehouses,
        ];
        return view('accounting.jurnal', $data);
    }

    public function createExpenses()
    {
        $title = 'Create Expenses';
        $account = AccountSubTypeModel::latest()->get();
        return view('accounting.create_expanse', compact('title', 'account'));
    }


    public function select_type()
    {
        $sub_materials = [];

        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = AccountSubTypeModel::select("id", "code", "name")
                ->where('name', 'LIKE', "%$search%")
                ->orWhere('code', 'LIKE', "%$search%")
                ->get();
        } else {
            $sub_materials = AccountSubTypeModel::latest()->get();
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
        // dd($request->all());
        //* save to journal

        foreach ($request->accountFields as $value) {
            $jurnal = new JurnalModel();
            $date = $request->date;
            $type = AccountSubTypeModel::where('id', $value['account'])->first();
            $type_acccount = AccountSubModel::where('id', $type->account_sub_id)->first();
            $jurnal->date = $date;
            $jurnal->code_type = $type->code;
            $jurnal->code = $type->code . ' -- ' . $type->name;
            $jurnal->memo = $value['memo'];
            $jurnal->account_code = $type_acccount->code;
            $jurnal->total = $value['total'];
            $jurnal->save();
        }
        return redirect()->back()->with('success', 'Data has been saved');
    }

    public function editSuperadmin(Request $request, $id)
    {
        $data = JurnalModel::find($id);
        $data->date = $request->date;
        $type = AccountSubTypeModel::where('id', $request->account)->first();
        $type_acccount = AccountSubModel::where('id', $type->account_sub_id)->first();
        $data->code = $type->code . ' -- ' . $type->name;
        $data->memo = $request->memo;
        $data->total = $request->total;
        $data->account_code = $type_acccount->code;
        $data->code_type = $type->code;
        $saved = $data->save();

        if ($saved) {
            return redirect()->back()->with('success', 'Data has been updated');
        } else {
            return redirect()->back()->with('error', 'Data failed to update');
        }
    }

    public function depreciation()
    {
        $all_depreciation = AssetModel::latest()->get();

        $smallest_date = AssetModel::all('acquisition_year')->min('acquisition_year');
        $smallest_year = date('Y', strtotime($smallest_date));
        $current_year = date('Y') - 1;

        //Count Total
        $total = [];

        foreach ($all_depreciation as $item) {
            $temp_cost = $item->acquisition_cost;
            for ($i = 0; $i < $current_year - $smallest_year + 1; $i++) {
                if (date('Y', strtotime($item->acquisition_year)) == $smallest_year + $i) {
                    $month = date('n', strtotime($item->acquisition_year));
                    $countmonth = 13 - intval($month);
                    $cost_per_month = $item->acquisition_cost / $item->lifetime;
                    $cost_current = $cost_per_month * $countmonth;
                } else {
                    $cost_per_month = $item->acquisition_cost / $item->lifetime;
                    $cost_current = $cost_per_month * 12;
                }
                $temp_cost = $temp_cost - $cost_current;
            }
        }

        $data = [
            'title' => 'Depreciation of Assets',
            'depreciations' => $all_depreciation,
            'smallest_year' => $smallest_year,
            'current_year' => $current_year
        ];
        return view('accounting.depreciation', $data);
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
