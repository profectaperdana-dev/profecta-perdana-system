<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Coa;
use App\Models\Finance\Journal;
use App\Models\Finance\JournalDetail;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $data = Journal::find($id);
        $data->reason = 'Cancel Journal';
        $data->save();
        $journal = JournalDetail::where('journal_id', $id)->get();

        foreach ($journal as $val) {
            $val->status = 0;
            $val->save();
        }
        return redirect('finance/journal/create')->with('success', 'Journal Has Been Canceled');
    }

    public function revisi($id)
    {

        $department = ["Logistic", "Sales", "HRD & GA", "Finance", "IT", "Technician"];
        sort($department);
        $data = [
            'title' => 'Journal Revisi',
            'department' => $department,
            'journal' => Journal::with('warehouse', 'jurnal_detail')->find($id),
            'journal_detail' => JournalDetail::with('coa')->where('journal_id', $id)->get(),
            'warehouse' => WarehouseModel::where('type', 5)->orderBy('warehouses')->get(),
            'coa' => Coa::orderBy('coa_code')->get(),
        ];

        return view('journal.revisi', $data);
    }

    public function history(Request $request)
    {
        $data = [
            'title' => 'Journal History Revision',

        ];
        if ($request->ajax()) {
            $journals = Journal::with('warehouse', 'jurnal_detail')
                ->whereHas('jurnal_detail', function ($q) {
                    $q->where('status', 0);
                })
                ->when($request->warehouse, function ($q) use ($request) {
                    $q->where('warehouse_id', $request->warehouse);
                })
                ->when($request->from_date, function ($q) use ($request) {
                    $q->whereBetween('date', array($request->from_date, $request->to_date));

                    // default
                }, function ($q) {
                    $q->whereYear('date', date('Y'));
                })
                ->get();

            return datatables()->of($journals)
                ->addColumn('detail', function ($journal) {
                    $details = $journal->jurnal_detail->where('status', 0)->map(function ($detail) use (&$totalDebit, &$totalCredit) {
                        $totalDebit += $detail->debit;
                        $totalCredit += $detail->credit;
                        return '
                            <tr>
                                <td>' . $detail?->coa?->name . '</td>
                                <td>' . $detail->ref . '</td>
                                <td class="text-end">' . number_format($detail->debit) . '</td>
                                <td class="text-end">' . number_format($detail->credit) . '</td>
                            </tr>
                        ';
                    });
                    $footer = '
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total:</strong></td>
                            <td class="text-end"><strong>' . number_format($totalDebit) . '</strong></td>
                            <td class="text-end"><strong>' . number_format($totalCredit) . '</strong></td>
                        </tr>
                    </tfoot>
                ';
                    return '<table class="table table-bordered table-sm">
                                <thead>
                                    <tr class="text-center">
                                        <th>Account</th>
                                        <th>Ref</th>
                                        <th>Debit</th>
                                        <th>Credit</th>
                                    </tr>
                                </thead>
                                <tbody>' . $details->implode('') . '</tbody>
                                ' . $footer . '
                            </table>';
                })
                ->editColumn('date', function ($journal) {
                    return date('d F Y', strtotime($journal->date));
                })
                ->editColumn('warehouse', function ($journal) {
                    return $journal->warehouse->warehouses;
                })
                ->editColumn('user', function ($journal) {
                    return $journal->jurnal_detail()->first()?->user?->name;
                })
                ->rawColumns(['detail'])
                ->make(true);
        }


        return view('journal.history', $data);
    }

    public function revisiStore($id, Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $journal = Journal::find($id);
            $journal->date = date('Y-m-d', strtotime($request->date));
            $journal->warehouse_id = $request->warehouse_id;
            $journal->memo = $request->memo;
            $journal->reason = $request->reason;
            $journal->department = $request->department;
            $journal->created_by = Auth::user()->id;
            $journal->save();

            foreach ($journal->jurnal_detail as $detail) {
                $detail->status = 0;
                $detail->save();
            }

            foreach ($request->requestForm as $value) {
                $detail = new JournalDetail();
                $detail->journal_id = $journal->id;
                $detail->coa_code = $value['account_id'];
                $detail->ref = $value['ref'];
                if ($value['type'] == 'debit') {
                    $detail->debit = $value['total'];
                } else {
                    $detail->credit = $value['total'];
                }
                $detail->save();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data has been saved');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request  $request)
    {
        if ($request->ajax()) {

            if (!empty($request->from_date)) {
                $jurnal = JournalDetail::with('jurnal')
                    ->whereHas('jurnal', function ($q) use ($request) {
                        $q->whereBetween('date', array($request->from_date, $request->to_date));
                    })
                    ->where('status', 1)
                    ->get()
                    ->sortBy(function ($data) {
                        return $data->jurnal->date;
                    });
            } else {
                $jurnal = JournalDetail::with('jurnal')
                    ->whereHas('jurnal', function ($q) use ($request) {
                        $q->where('date', date('Y-m-d'));
                    })
                    ->where('status', 1)
                    ->get()
                    ->sortBy(function ($data) {
                        return $data->jurnal->date;
                    });
            }
            return datatables()->of($jurnal)
                ->editColumn('total', function ($data) {
                    if ($data->debit != 0) {
                        return  number_format($data->debit);
                    } else return  number_format($data->credit);
                })
                ->editColumn('id', function ($data) {
                    return $data->jurnal->id;
                })
                ->editColumn('isadjusted', function ($data) {
                    return $data->jurnal->isadjusted;
                })
                ->editColumn('revisi', function ($data) {
                    return '<a href="' . $data->journal_id . '/revisi" class="btn btn-sm btn-primary">Revisi</i></a>';
                    // return '';
                })
                ->editcolumn('warehouse', function ($data) {
                    return $data->jurnal->warehouse->warehouses;
                })
                ->editColumn('date', function ($data) {
                    return date('d F Y', strtotime($data->jurnal->date));
                })
                ->editColumn('type', function ($data) {
                    if ($data->debit != 0) {
                        return 'Debit';
                    } else return 'Credit';
                })
                ->editColumn('account_id', function ($data) {
                    return $data->coa->name;
                    // return view('journal._option', compact('data'))->render();
                })
                ->editColumn('created_by', function ($data) {

                    return $data->user->name;
                })
                ->editColumn('department', function ($data) {
                    if (!$data->jurnal->department) {
                        return "-";
                    }
                    return $data->jurnal->department;
                })

                ->addIndexColumn()
                ->rawColumns(['account_id', 'revisi'])
                ->make(true);
        }

        $department = ["Logistic", "Sales", "HRD & GA", "Finance", "IT", "Technician"];
        sort($department);

        $data = [
            'department' => $department,
            'warehouse' => WarehouseModel::where('type', 5)->orderBy('warehouses')->get(),
            'coa' => Coa::orderBy('coa_code')->get(),
            'title' => 'Journal Create',
        ];
        return view('journal.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //* save to journal
        // dd($request->isAdjusted ? 'yrs' : 'sda');
        try {
            DB::beginTransaction();
            $journal = new Journal();
            $journal->date = date('Y-m-d', strtotime($request->date));
            $journal->warehouse_id = $request->warehouse;
            $journal->memo = $request->memo;
            $journal->department = $request->department;
            $journal->isadjusted = $request->isAdjusted ? 1 : 0;

            $kode_area = WarehouseModel::join('customer_areas', 'customer_areas.id', '=', 'warehouses.id_area')
                ->select('customer_areas.area_code', 'warehouses.id')
                ->where('warehouses.id', $journal->warehouse_id)
                ->first();

            $lastRecord = Journal::where('warehouse_id', $journal->warehouse_id)->latest()->first();

            if ($lastRecord) {
                $lastRecordMonth = Carbon::parse($lastRecord->date)->format('m');
                $currentMonth = Carbon::now()->format('m');

                if ($lastRecordMonth != $currentMonth) {
                    // Jika terjadi pergantian bulan, set $cust_number_id menjadi 1
                    $cust_number_id = 1;
                    $journal->id_sort = $cust_number_id;
                } else {
                    // Jika masih dalam bulan yang sama, increment $cust_number_id
                    $cust_number_id = intval($lastRecord->id_sort) + 1;
                    $journal->id_sort = $cust_number_id;
                }
            } else {
                // Jika belum ada record sebelumnya, set $cust_number_id menjadi 1
                $cust_number_id = 1;
                $journal->id_sort = $cust_number_id;
            }
            $length = 3;
            // $id = intval(SalesOrderModel::where('warehouse_id', $request->warehouse_id)->max('id_sort')) + 1;
            $cust_number_id = str_pad($cust_number_id, $length, '0', STR_PAD_LEFT);
            $year = Carbon::now()->format('Y'); // 2022
            $month = Carbon::now()->format('m'); // 2022
            $tahun = substr($year, -2);
            $order_number = 'Trans-' . $kode_area->area_code . '-' . $tahun  . $month  . $cust_number_id;
            //

            // save sales orders
            $journal->journal_number = $order_number;

            $journal->save();

            foreach ($request->accountFields as $value) {
                $detail = new JournalDetail();
                $detail->journal_id = $journal->id;
                $detail->coa_code = $value['account'];
                $detail->ref = $value['ref'];
                if ($value['type'] == 'debit') {
                    $detail->debit = $value['total'];
                } else {
                    $detail->credit = $value['total'];
                }
                $detail->created_by =  Auth::user()->id;
                $detail->save();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data has been saved');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function get_ref()
    {
        $get_jurnal = JournalDetail::with(['jurnal'])->where('ref', 'REGEXP', '^\d{2}-\d{2}\.\d{1,2}\.\d{6}$')
            ->whereHas('jurnal', function ($q) {
                $q->whereMonth('date', date('m'));
                $q->whereYear('date', date('Y'));
            })
            ->latest()->first();
        return response()->json($get_jurnal);
    }

    public function general_journal(Request $request)
    {
        //! view data from database
        if ($request->ajax()) {
            $invoice = JournalDetail::with('jurnal')
                ->whereHas('jurnal', function ($query) use ($request) {
                    $query->when(!empty($request->from_date), function ($q) use ($request) {
                        return $q->whereBetween('date', [$request->from_date, $request->to_date]);
                    }, function ($q) {
                        return $q->where('date', now()->format('Y-m-d'));
                    });
                    $query->when($request->warehouse, function ($q) use ($request) {
                        return $q->where('warehouse_id', $request->warehouse);
                    });
                })
                ->get()
                ->sortByDesc(function ($inv) {
                    return $inv->jurnal->created_at;
                });

            return datatables()->of($invoice)
                ->editColumn('date', function ($data) {
                    return date('d F Y', strtotime($data->jurnal->date));
                })
                ->editColumn('ket', function ($data) {
                    if ($data->credit != 0) {

                        return  '<span class="ms-3">&nbsp;&nbsp;' . $data->coa->name . '</span>';
                    } else {

                        return  '<span> ' . $data->coa->name . ' </span>';
                    }
                })
                ->editColumn('debit', function ($data) {
                    if ($data->debit != null) {
                        return  number_format($data->debit);
                    } else {
                        return  '';
                    }
                })
                ->editColumn('credit', function ($data) {
                    if ($data->credit != null) {
                        return  number_format($data->credit);
                    } else {
                        return  '';
                    }
                })
                ->editColumn('memo', function ($data) {
                    if ($data->jurnal->memo != null) {
                        return  $data->jurnal->memo;
                    } else {
                        return  '';
                    }
                })
                ->rawColumns(['ket'])
                ->make(true);
        }
        $warehouse = WarehouseModel::with('typeBy')->whereHas('typeBy', function ($query) {
            $query->where('id', 5);
        })->orderBy('warehouses')->get();
        $data = [
            'title' => "General Journal",
            'warehouse' => $warehouse,
        ];
        return view('accounting.jurnal', $data);
    }

    public function general_ledger()
    {
        $get_jurnal = JournalDetail::with(
            'jurnal'
        )
            ->whereHas('jurnal', function ($q) {

                $q->when(
                    request()->from_date,
                    function ($que) {
                        return $que->whereBetween(
                            'date',
                            [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                        );
                    },
                    function ($quer) {
                        return $quer->where('date', date('Y-m-d'));
                    }
                );

                $q->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                });
            })
            ->when(request()->acc_code,  function ($query) {
                return $query->where('coa_code', request()->acc_code);
            })
            ->select('coa_code')
            ->distinct()
            ->get();
        // dd($get_jurnal);
        $account_name = [];

        foreach ($get_jurnal as  $value) {
            $acc = Coa::where('coa_code', $value->coa_code)->first()->name;

            $account_name[$value->coa_code] = $acc;
        }

        // $getAllAcc = AccountModel::select('code', 'name')->get();
        // $getAllSubAcc = AccountSubModel::select('code', 'name')->get();
        // $getAllTypeAcc = AccountSubTypeModel::select('code', 'name')->get();

        // $combinedCollection = $getAllAcc->concat($getAllSubAcc)->concat($getAllTypeAcc);
        $combinedCollection = Coa::select('coa_code', 'name')->get();
        $combinedCollection =  $combinedCollection->toArray();
        $all_warehouses = WarehouseModel::where('type', 5)->orderBy('warehouses')->get();

        if (request()->ajax()) {
            $data = [
                'grouped_jurnal' => $get_jurnal,
                'account_name' => $account_name,
                'all_warehouses' => $all_warehouses
            ];
            return response()->json($data);
        } else {

            $data = [
                'grouped_jurnal' => $get_jurnal,
                'account_name' => $account_name,
                'all_warehouses' => $all_warehouses,
                'all_account' => $combinedCollection,
                'title' => 'General Ledger'
            ];

            return view('accounting.general_ledger', $data);
        }
    }

    public function general_ledger_table()
    {
        $totalDebit = 0;
        $totalKredit = 0;
        $account = JournalDetail::with(
            'jurnal'
        )
            ->where('coa_code', request()->acc_id)
            ->whereHas('jurnal', function ($q) {
                $q->when(
                    request()->from_date,
                    function ($que) {
                        return $que->whereBetween(
                            'date',
                            [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                        );
                    },
                    function ($quer) {
                        return $quer->where('date', date('Y-m-d'));
                    }
                );
                $q->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                });
            })
            ->get()
            ->sortBy(function ($acc) {
                return $acc->jurnal->date;
            });


        return datatables()->of($account)
            ->editColumn('date', function ($data) {
                return date('d-m-Y', strtotime($data->jurnal->date));
            })
            ->editColumn('memo', function ($data) {
                return $data->jurnal->memo;
            })
            ->editColumn('ref', function ($data) {
                return $data->ref;
            })
            ->editColumn('debit', function ($data) {
                if ($data->debit != 0) {
                    return number_format($data->debit);
                } else {
                    return 0;
                }
            })
            ->editColumn('credit', function ($data) {
                if ($data->credit != 0) {
                    return number_format($data->credit);
                } else {
                    return 0;
                }
            })
            ->editColumn('sub_debit', function ($data) use (&$totalDebit_for_subDebit, &$totalKredit_for_subDebit) {
                $debit = $data->debit ? $data->debit : 0;
                $kredit = $data->credit ? $data->credit : 0;

                $totalDebit_for_subDebit += $debit;
                $totalKredit_for_subDebit += $kredit;

                $saldoDebit = $totalDebit_for_subDebit - $totalKredit_for_subDebit;
                // $saldoKredit = $totalKredit - $totalDebit;
                if ($saldoDebit > 0) {
                    return number_format($saldoDebit);
                } else {
                    return '-';
                }
            })
            ->editColumn('sub_credit', function ($data) use (&$totalDebit_for_subKredit, &$totalKredit_for_subKredit) {
                $debit = $data->debit ? $data->debit : 0;
                $kredit = $data->credit ? $data->credit : 0;

                $totalDebit_for_subKredit += $debit;
                $totalKredit_for_subKredit += $kredit;

                // $saldoDebit = $totalDebit - $totalKredit;
                $saldoKredit = $totalKredit_for_subKredit - $totalDebit_for_subKredit;
                if ($saldoKredit > 0) {
                    return number_format($saldoKredit);
                } else {
                    return '-';
                }
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function trial_balance()
    {
        if (request()->ajax()) {

            $get_jurnal = JournalDetail::with(
                'jurnal'
            )
                ->whereHas('jurnal', function ($q) {

                    $q->when(
                        request()->from_date,
                        function ($que) {
                            return $que->whereBetween(
                                'date',
                                [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                            );
                        },
                        function ($quer) {
                            return $quer->whereYear('date', Carbon::now()->year);
                        }
                    );
                    $q->when(request()->warehouse, function ($que) {
                        return $que->where('warehouse_id', request()->warehouse);
                    });
                })
                // ->when(request()->acc_code,  function ($query) {
                //     return $query->where('account_id', request()->acc_code);
                // })
                ->select('coa_code')
                ->distinct()
                ->get();
            // dd($get_jurnal);
            return datatables()->of($get_jurnal)
                ->editColumn('acc_code', function ($data) {
                    return $data->coa_code;
                })
                ->editColumn('acc_name', function ($data) {
                    $acc = Coa::where('coa_code', $data->coa_code)->first()->name;
                    // $raw_key = explode('.', $data->account_id);
                    // switch (sizeof($raw_key)) {
                    //     case 1:
                    //         $acc = AccountModel::where('code', $data->account_id)->first()->name;
                    //         break;
                    //     case 2:
                    //         $acc = AccountSubModel::where('code', $data->account_id)->first()->name;
                    //         break;
                    //     case 3:
                    //         $acc = AccountSubTypeModel::where('code', $data->account_id)->first()->name;
                    //         break;
                    //     default:
                    //         $acc = null;
                    //         break;
                    // }

                    return $acc;
                })
                ->editColumn('sub_debit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)
                        ->whereHas('jurnal', function ($q) {
                            $q->when(
                                request()->from_date,
                                function ($que) {
                                    return $que->whereBetween(
                                        'date',
                                        [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                                    );
                                },
                                function ($quer) {
                                    return $quer->whereYear('date', Carbon::now()->year);
                                }
                            );
                            $q->when(request()->warehouse, function ($que) {
                                return $que->where('warehouse_id', request()->warehouse);
                            });
                        })->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    if ($total > 0) {
                        return number_format($total);
                    } else {
                        return '-';
                    }
                })
                ->editColumn('sub_credit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->whereHas('jurnal', function ($q) {
                        $q->when(
                            request()->from_date,
                            function ($que) {
                                return $que->whereBetween(
                                    'date',
                                    [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                                );
                            },
                            function ($quer) {
                                return $quer->whereYear('date', Carbon::now()->year);
                            }
                        );
                        $q->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        });
                    })->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    if ($total < 0) {
                        return number_format(abs($total));
                    } else {
                        return '-';
                    }
                })
                ->addIndexColumn()
                ->make(true);
        }

        $all_warehouses = WarehouseModel::where('type', 5)->orderBy('warehouses')->get();


        $data = [
            'title' => 'Trial Balance',
            'all_warehouses' => $all_warehouses
        ];

        return view('accounting.trial_balance', $data);
    }

    public function profit_loss()
    {
        // $getAccountWithoutSubType = AccountSubModel::where('code', 'LIKE', '%500.%')
        //     ->whereDoesntHave('accountSubTypes')->where('account_id', 12);

        // $getAccountWithSubType = AccountSubTypeModel::where('code', 'LIKE', '%500.%')->where('account_id', 12);
        // $getPendapatan = $getAccountWithoutSubType->union($getAccountWithSubType)->get()->sortBy(function ($q) {
        //     return $q->name;
        // });
        // dd($getPendapatan);

        //Get Pendapatan dan Biaya
        $getPendapatan = Coa::where('coa_category_id', 14);
        $getBiaya = Coa::whereIn('coa_category_id', [15, 18]);

        $getAllAccount = $getPendapatan
            ->union($getBiaya)
            ->get()->sortBy(function ($q) {
                return $q->name;
            })->groupBy('coa_category_id')->sortBy(function ($grouped, $accountId) {
                return $accountId;
            });

        //Get Nama Pendapatan dan Biaya
        $nameHeader = [];
        foreach ($getAllAccount as $key => $value) {
            $getSub = Coa::where('coa_category_id', $key)->first();
            $nameHeader[$key] = $getSub->name;
        }
        // dd($nameHeader);

        if (request()->ajax()) {
            if (request()->acc_id == 14) {
                $getPendapatan = Coa::where('coa_category_id', 14)
                    ->get()->sortBy(function ($q) {
                        return $q->name;
                    });
            } else if (request()->acc_id == 15) {
                $getPendapatan = Coa::where('coa_category_id', 15)->get()->sortBy(function ($q) {
                    return $q->name;
                });
            } else {
                $getPendapatan = Coa::where('coa_category_id', 18)->get()->sortBy(function ($q) {
                    return $q->name;
                });
            }

            return datatables()->of($getPendapatan)
                ->editColumn('acc_name', function ($data) {
                    $acc = Coa::where('coa_code', $data->coa_code)->first()->name;
                    // $raw_key = explode('.', $data->code);
                    // switch (sizeof($raw_key)) {
                    //     case 1:
                    //         $acc = AccountModel::where('code', $data->code)->first()->name;
                    //         break;
                    //     case 2:
                    //         $acc = AccountSubModel::where('code', $data->code)->first()->name;
                    //         break;
                    //     case 3:
                    //         $acc = AccountSubTypeModel::where('code', $data->code)->first()->name;
                    //         break;
                    //     default:
                    //         $acc = null;
                    //         break;
                    // }

                    return $acc;
                })
                ->editColumn('sub_total', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->whereHas('jurnal', function ($q) {
                        $q->when(
                            request()->from_date,
                            function ($que) {
                                return $que->whereBetween(
                                    'date',
                                    [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                                );
                            },
                            function ($quer) {
                                return $quer->whereYear('date', Carbon::now()->year);
                            }
                        );
                        $q->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        });
                    })->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }

                    return number_format(abs($total));
                })
                // ->editColumn('total', function ($data) {

                //     return '';
                // })
                ->addIndexColumn()
                ->make(true);
        }

        $getPendapatan = JournalDetail::with(
            'jurnal',
        )->whereHas('coa', function ($q) {
            $q->where('coa_category_id', 14);
        })->select('coa_code')
            ->distinct()->get();
        $pendapatan_arr = [];
        foreach ($getPendapatan as $value) {
            $get_all_jurnal = JournalDetail::where('coa_code', $value->coa_code)->whereHas('jurnal', function ($q) {
                $q->when(
                    request()->from_date,
                    function ($que) {
                        return $que->whereBetween(
                            'date',
                            [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                        );
                    },
                    function ($quer) {
                        return $quer->whereYear('date', Carbon::now()->year);
                    }
                );
                $q->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                });
            })->get();
            $pendapatan_c = 0;
            foreach ($get_all_jurnal as $jurnal) {
                if ($jurnal->debit) {
                    $pendapatan_c += $jurnal->debit;
                } else {
                    $pendapatan_c -= $jurnal->credit;
                }
            }
            array_push($pendapatan_arr, abs($pendapatan_c));
        }
        $pendapatan = array_sum($pendapatan_arr);

        $getBiaya = JournalDetail::with(
            'jurnal',
        )->whereHas('coa', function ($q) {
            $q->whereIn('coa_category_id', [15, 18]);
        })->select('coa_code')
            ->distinct()->get();
        $biaya_arr = [];
        foreach ($getBiaya as $value2) {
            $get_all_jurnal = JournalDetail::where('coa_code', $value2->coa_code)->whereHas('jurnal', function ($q) {
                $q->when(
                    request()->from_date,
                    function ($que) {
                        return $que->whereBetween(
                            'date',
                            [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                        );
                    },
                    function ($quer) {
                        return $quer->whereYear('date', Carbon::now()->year);
                    }
                );
                $q->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                });
            })->get();
            $biaya_c = 0;
            foreach ($get_all_jurnal as $jurnal) {
                if ($jurnal->debit) {
                    $biaya_c += $jurnal->debit;
                } else {
                    $biaya_c -= $jurnal->credit;
                }
            }
            array_push($biaya_arr, abs($biaya_c));
        }

        $biaya = array_sum($biaya_arr);
        $profit = $pendapatan - $biaya;
        $all_warehouses = WarehouseModel::where('type', 5)->orderBy('warehouses')->get();

        $data = [
            'title' => 'Profit and Loss',
            'profit' => $profit,
            'all_account' => $getAllAccount,
            'nameHeader' => $nameHeader,
            'all_warehouses' => $all_warehouses
        ];

        return view('accounting.loss_profit', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editJournal($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $selected_exp = JournalDetail::where('id', $id)->first();
            // dd($selected_exp);
            // dd($request->total);
            if ($request->type == 'debit') {
                // dd('debit');
                $selected_exp->debit = $request->total;
                $selected_exp->credit = 0;
            } else {
                // dd('credit');
                $selected_exp->credit = $request->total;
                $selected_exp->debit = 0;
            }
            $selected_exp->ref = $request->ref;
            $selected_exp->save();
            DB::commit();
            return redirect()->back()->with('success', 'Data has been updated');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
    public function deleteJournal($id)
    {
        try {
            DB::beginTransaction();

            $selected_dexp = JournalDetail::with('jurnal')->where('id', $id)->first();
            $check_dexp = JournalDetail::with('jurnal')->where('journal_id', $selected_dexp->expenses_id)->get();
            if ($check_dexp->count() > 1) {
                $selected_dexp->delete();
            } else {
                $selected_dexp->jurnal->delete();
                $selected_dexp->delete();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data has been deleted');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return redirect()->back()->with('error2', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
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
