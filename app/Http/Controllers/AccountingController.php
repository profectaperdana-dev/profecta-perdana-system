<?php

namespace App\Http\Controllers;

use App\Models\AccountModel;
use App\Models\AccountSubModel;
use App\Models\AccountSubTypeModel;
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
        $data = JurnalModel::latest()->get();
        $title = 'Journal';
        return view('accounting.jurnal', compact('data', 'title'));
    }

    public function createExpenses()
    {
        $title = 'Create Expenses';
        $account = AccountModel::latest()->get();
        return view('accounting.create_expanse', compact('title', 'account'));
    }

    public function create_depreciation()
    {
        $title = 'Create Depreciation';
        // $account = AccountModel::latest()->get();
        return view('accounting.create_depreciation', compact('title'));
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

    public function profit_loss()
    {
        $income = JurnalModel::where('account_code', '1')->sum('total');
        $load_discount = JurnalModel::where('account_code', '2.2.703.804.102')->sum('total');
        $load_return = JurnalModel::where('account_code', '2.2.703.804.8')->sum('total');

        $data = [
            'title' => 'Profit and Loss',
            'income' => $income,
            'load_discount' => $load_discount,
            'load_return' => $load_return
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
            $model->account_code = $type_account_id->code;
        } else {
            $model->code = $sub_account_id->name;
            $model->account_code = $sub_account_id->code;
        }
        $model->memo = $request->memo;
        $model->total = $request->total;
        $saved = $model->save();

        if ($saved) {
            return redirect('/expenses/create')->with('success', 'Add Expense Success!');
        } else {
            return redirect('/expenses/create')->with('error', 'Add Expense Fail!');
        }
    }

    public function store_depreciation(Request $request)
    {
        //* validate
        $request->validate([
            "asset" => "required",
            "amount" => "required|numeric",
            "lifetime" => "required|numeric",
            "acquisition_year" => "required",
            "acquisition_cost" => "required|numeric"
        ]);

        $model = new DepreciationModel();
        $model->asset = $request->asset;
        $model->amount = $request->amount;
        $model->lifetime = $request->lifetime;
        $model->acquisition_year = $request->acquisition_year;
        $model->acquisition_cost = $request->acquisition_cost;
        $model->created_by = Auth::user()->id;
        $saved = $model->save();

        if ($saved) {
            return redirect('/depreciation/create')->with('success', 'Add Depreciation Success!');
        } else {
            return redirect('/depreciation/create')->with('error', 'Add Depreciation Fail!');
        }
    }

    public function depreciation()
    {
        $all_depreciation = DepreciationModel::latest()->get();

        $smallest_date = DepreciationModel::all('acquisition_year')->min('acquisition_year');
        $smallest_year = date('Y', strtotime($smallest_date));
        $current_year = date('Y') - 1;
        $data = [
            'title' => 'Depreciation',
            'depreciations' => $all_depreciation,
            'smallest_year' => $smallest_year,
            'current_year' => $current_year
        ];
        foreach ($all_depreciation as $value) {
        }
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
        //
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
