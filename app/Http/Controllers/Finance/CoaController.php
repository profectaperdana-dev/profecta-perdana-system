<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Coa;
use App\Models\Finance\CoaCategories;
use App\Models\Finance\CoaSaldo;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCoaCategories(Request $request)
    {
        if ($request->ajax()) {
            $data = CoaCategories::orderBy('coa_group')
                ->orderBy('category_number')
                ->get();
            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    return view('coa_categories._option', ['data' => $data])->render();
                })
                ->editColumn('coa_group', function ($data) {
                    return $data->coa_group . '-' . $data->category_number;
                })
                ->rawColumns(['action', 'coa_group'])
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'Create CoA Categories',
        ];

        return view('coa_categories.index', $datas);
    }

    public function StoreCoaCategories(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = new CoaCategories();
            $data->name = $request->input('name');
            $data->coa_group = $request->input('coa_group');
            $data->category_number = $request->input('category_number');
            if ($data->save()) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data failed to saved.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    public function UpdateCoaCategories(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = CoaCategories::find($id);

            if (!$data) {
                // Record not found, handle the error accordingly (e.g., return an error response).
                return response()->json([
                    'status' => 'error',
                    'message' => 'Record not found.',
                ]);
            }

            // Record exists, so update its properties
            $data->name = $request->input('name');
            $data->coa_group = $request->input('coa_group');
            $data->category_number = $request->input('category_number');

            if ($data->save()) {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been updated.',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data failed to be updated.',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function getCode(Request $request, $id)
    {
        $data = Coa::where('coa_category_id', $id)->get();

        $max_code = [];
        foreach ($data as $d) {
            $explode = explode('-', $d->coa_code);
            $code = $explode[1];
            $code = substr($code, 1);
            array_push($max_code, (int)$code);
        }
        sort($max_code);
        $max = end($max_code);
        $category = CoaCategories::where('id', $id)->first();
        if (count($data) > 0) {
            // $explode = explode('-', $data->coa_code);
            $code = $category->coa_group . '-' . $category->category_number . '0' . $max + 1;
        } else {

            $code = $category->coa_group . '-' . $category->category_number . '00';
        }

        return response()->json([
            'status' => 'success',
            'code' => $code,
        ]);
    }

    public function getCoaCashBank()
    {

        $data = Coa::where('coa_category_id', 5)->get();


        return response()->json($data);
    }
    public function getSaldo(Request $request)
    {
        if ($request->ajax()) {
            $data = CoaSaldo::with('coa', 'warehouse')
                // ->whereYear('cut_off', '=', date('Y'))
                ->when($request->warehouse, function ($q) use ($request) {
                    return $q->where('warehouse_id', $request->warehouse);
                }, function ($q) {
                    return $q->where('warehouse_id', 1);
                })
                ->get();

            return datatables()->of($data)
                ->editColumn('coa_code', function ($data) {
                    return $data->coa->coa_code;
                })
                ->editColumn('name', function ($data) {
                    return $data->coa->name;
                })
                ->editColumn('debit', function ($data) {
                    return '<input class="numberSaldoDebit text-end form-control" value="' . number_format($data->debit) . '" />
                    <input type="hidden" class="text-end form-control" value="' . $data->debit . '" name="formSaldo[' . $data->coa->id  . '][debit]" />
                    ';
                })
                ->editColumn('kredit', function ($data) {
                    return '<input class="numberSaldoKredit text-end form-control"  value="' . number_format($data->kredit) . '" />
                    <input type="hidden" class="text-end form-control" name="formSaldo[' . $data->coa->id  . '][kredit]" value="' . $data->kredit . '" />
                    ';
                })
                ->rawColumns(['debit', 'kredit'])
                ->addIndexColumn()
                ->make(true);
        }
    }
    public function storeSaldo(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $totalDebit = 0;
            $totalKredit = 0;

            foreach ($request->formSaldo as $key => $v) {
                $data = CoaSaldo::where('coa_id', $key)
                    ->where('warehouse_id', $request->warehouse_id)
                    ->first();
                $data->debit = $v['debit'];
                $data->kredit = $v['kredit'];
                $totalDebit += $v['debit'];
                $totalKredit += $v['kredit'];
                $data->save();
            }

            // Mengecek apakah totalDebit sama dengan totalKredit
            if ($totalDebit == $totalKredit) {
                DB::commit();
                return redirect()->back()->with('success', 'Saldo berhasil disimpan.');
            } else {
                DB::rollback();
                return redirect()->back()->with('error', 'Saldo tidak sama.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CoaSaldo::with('coa', 'warehouse')

                ->when($request->warehouse, function ($q) use ($request) {
                    return $q->where('warehouse_id', $request->warehouse);
                }, function ($q) {
                    return $q->where('warehouse_id', 1);
                })
                ->when($request->year, function ($q) use ($request) {
                    return $q->whereYear('cut_off', $request->year);
                }, function ($q) {
                    // Jika tahun tidak diset, Anda dapat menentukan perlakuan khusus di sini.
                    // Misalnya, tidak menambahkan kondisi apapun.
                    return $q->whereYear('cut_off', '=', date('Y'));
                })
                ->get();


            return datatables()->of($data)
                ->editColumn('coa_code', function ($data) {
                    return $data->coa->coa_code;
                })
                ->editColumn('name', function ($data) {
                    return $data->coa->name;
                })
                ->editColumn('debit', function ($data) {
                    return number_format($data->debit);
                })
                ->editColumn('kredit', function ($data) {
                    return number_format($data->kredit);
                })
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'Create CoA',
            'warehouse' => WarehouseModel::where('type', 5)->get(),
            'category' => CoaCategories::orderBy('name')
                ->get(),

        ];

        return view('coa.index', $datas);
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
        try {
            DB::beginTransaction();
            $data = new Coa();
            $data->name = $request->input('name');
            $data->coa_category_id = $request->input('coa_category_id');
            $data->coa_code = $request->input('coa_code');
            $data->detail = $request->input('detail');
            $data->description = $request->input('description');
            if ($data->save()) {

                foreach ($request->coa_saldo as $v) {
                    $data_saldo = new CoaSaldo();
                    $data_saldo->coa_id = $data->coa_id;
                    $data_saldo->warehouse_id = $v['warehouse_id'];
                    $data_saldo->saldo = $v['start_balance'];
                    $data_saldo->save();
                }
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data failed to saved.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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

        try {
            DB::beginTransaction();
            $data =  Coa::find($id);
            $data->name = $request->input('name');
            $data->detail = $request->input('detail');
            $data->description = $request->input('description');
            if ($data->save()) {

                foreach ($request->coa_saldo as $v) {
                    $data_saldo = CoaSaldo::where('coa_id', $id)
                        ->where('warehouse_id', $v['warehouse_id'])
                        ->first();
                    $data_saldo->saldo = $v['start_balance'];
                    $data_saldo->save();
                }
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data has been saved.'
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data failed to saved.'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
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
        //
    }
}
