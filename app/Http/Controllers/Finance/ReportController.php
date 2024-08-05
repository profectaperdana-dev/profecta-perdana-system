<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\Coa;
use App\Models\Finance\CoaSaldo;
use App\Models\Finance\Journal;
use App\Models\Finance\JournalDetail;
use App\Models\PurchaseOrderModel;
use App\Models\WarehouseModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class ReportController extends Controller
{
    public function journal(Request $request)
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
                // ->where(function ($query) {
                //     $query->where('coa_code', '!=', '2-300')->orWhere('debit', '<=', 0);
                // })
                // ->where(function ($query) {
                //     $query->where('coa_code', '!=', '1-600')->orWhere('credit', '<=', 0);
                // })
                ->where('status', 1)
                ->get()
                ->sortBy(function ($inv) {
                    return [$inv->jurnal->date, $inv->jurnal->created_at];
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
                        $debit = $data->debit;
                        // $checkingCoaPPN = JournalDetail::where('journal_id', $data->journal_id)->where('status', 1)
                        //     ->get();
                        // if ($checkingCoaPPN->pluck('coa_code')->contains('2-300') && $data->coa_code != "2-300" && $data->credit <= 0) {
                        //     $ppn_keluaran = $checkingCoaPPN->where('coa_code', '2-300')->pluck('debit')->first();
                        //     if ($ppn_keluaran > 0) {
                        //         $debit += $ppn_keluaran;
                        //     }
                        // }
                        return  number_format($debit);
                    } else {
                        return  '';
                    }
                })
                ->editColumn('credit', function ($data) {
                    if ($data->credit != null) {
                        $credit = $data->credit;
                        // $checkingCoaPPN = JournalDetail::where('journal_id', $data->journal_id)->where('status', 1)
                        //     ->get();
                        // if ($checkingCoaPPN->pluck('coa_code')->contains('1-600') && $data->coa_code != "1-600" && $data->debit <= 0) {
                        //     $ppn_keluaran = $checkingCoaPPN->where('coa_code', '1-600')->pluck('credit')->first();
                        //     if ($ppn_keluaran > 0) {
                        //         $credit += $ppn_keluaran;
                        //     }
                        // }
                        return  number_format($credit);
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
                ->editColumn('department', function ($data) {
                    if ($data->jurnal->department != null) {
                        return  $data->jurnal->department;
                    } else {
                        return  '-';
                    }
                })
                ->rawColumns(['ket'])
                ->make(true);
        }
        $warehouse = WarehouseModel::with('typeBy')->whereHas('typeBy', function ($query) {
            $query->where('id', 5);
        })->orderBy('warehouses')->get();
        $data = [
            'title' => "Journal Report",
            'warehouse' => $warehouse,
        ];
        return view('finance_report.jurnal', $data);
    }

    public function general_ledger()
    {
        // dd('sdsdsd');
        $get_jurnal = JournalDetail::with(
            'jurnal'
        )->where('status', 1)

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
            // ->when(request()->show, function ($que) {
            //     if (request()->show != 'all') {
            //         return $que->where('debit', '>', 0)->where('credit', '>', 0);
            //     }
            // })
            ->select('coa_code')
            ->distinct()
            ->get();
        // dd($get_jurnal);
        $account_name = [];

        foreach ($get_jurnal as  $value) {
            $acc = Coa::where('coa_code', $value->coa_code)->first()->name;

            $account_name[$value->coa_code] = $acc;
        }

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

            return view('finance_report.general_ledger', $data);
        }
    }

    public function general_ledger_table()
    {
        $totalDebit = 0;
        $totalKredit = 0;
        $get_coa = Coa::where('coa_code', request()->acc_id)->first();
        $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
            ->when(
                request()->warehouse,
                function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                },
                function ($quer) {
                    return $quer->where('warehouse_id', 1);
                }
            )
            // ->when(request()->show, function ($que) {
            //     if (request()->show != 'all') {
            //         return $que->where('debit', '>', 0)->where('kredit', '>', 0);
            //     }
            // })
            ->first();
        $sub_debit = 0;
        $sub_kredit = 0;
        $total_sub = $saldo_awal->debit - $saldo_awal->kredit;
        if ($total_sub < 0) {
            $sub_kredit = $total_sub;
        } else if ($total_sub > 0) {
            $sub_debit = $total_sub;
        }
        $dataTambahan = [];
        if (request()->show == 'all') {
            $dataTambahan = [
                'date' => $saldo_awal->cut_off,
                'memo' => 'Saldo Awal',
                'ref' => '-',
                'debit' => $saldo_awal->debit,  // Isi sesuai kebutuhan
                'credit' => $saldo_awal->kredit, // Isi sesuai kebutuhan
                'sub_debit' => $sub_debit,  // Isi sesuai kebutuhan
                'sub_credit' => $sub_kredit, // Isi sesuai kebutuhan
            ];
        } else {

            if ($saldo_awal->debit > 0 || $saldo_awal->kredit > 0) {
                $dataTambahan = [
                    'date' => $saldo_awal->cut_off,
                    'memo' => 'Saldo Awal',
                    'ref' => '-',
                    'debit' => $saldo_awal->debit,  // Isi sesuai kebutuhan
                    'credit' => $saldo_awal->kredit, // Isi sesuai kebutuhan
                    'sub_debit' => $sub_debit,  // Isi sesuai kebutuhan
                    'sub_credit' => $sub_kredit, // Isi sesuai kebutuhan
                ];
            }
        }

        $account = JournalDetail::with(
            'jurnal'
        )->where('status', 1)

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
                $q->when(
                    request()->warehouse,
                    function ($que) {
                        return $que->where('warehouse_id', request()->warehouse);
                    },
                    function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    }
                );
            })
            // ->when(request()->show, function ($que) {
            //     if (request()->show != 'all') {
            //         return $que->orWhere('debit', '>', 0)->orWhere('credit', '>', 0);
            //     }
            // })
            ->get()
            ->sortBy(function ($acc) {
                return $acc->jurnal->date;
            });
        if (sizeof($dataTambahan) > 0) {
            $account = collect([$dataTambahan])->merge($account);
        }

        return datatables()->of($account)
            ->editColumn('date', function ($data) {
                if (isset($data->jurnal)) {
                    return date('d-m-Y', strtotime($data->jurnal->date));
                } else {
                    return date('d-m-Y', strtotime($data['date']));
                }
            })
            ->editColumn('memo', function ($data) {
                if (isset($data->jurnal)) {
                    return $data->jurnal->memo;
                } else {
                    return $data['memo'];
                }
            })
            ->editColumn('ref', function ($data) {
                if (isset($data->jurnal)) {
                    return $data->ref;
                } else {
                    return $data['ref'];
                }
            })
            ->editColumn('debit', function ($data) {
                if (isset($data->jurnal)) {
                    if ($data->debit != 0) {

                        $debit = $data->debit;
                        // $checkingCoaPPN = JournalDetail::where('journal_id', $data->journal_id)->where('status', 1)
                        //     ->get();
                        // if ($checkingCoaPPN->pluck('coa_code')->contains('2-300') && $data->coa_code != "2-300" && $data->credit <= 0) {
                        //     $ppn_keluaran = $checkingCoaPPN->where('coa_code', '2-300')->pluck('debit')->first();
                        //     if ($ppn_keluaran > 0) {
                        //         $debit += $ppn_keluaran;
                        //     }
                        // }
                        return  number_format($debit);
                    } else {
                        return 0;
                    }
                } else {
                    if ($data['debit'] != 0) {
                        return number_format($data['debit']);
                    } else {
                        return 0;
                    }
                }
            })
            ->editColumn('credit', function ($data) {
                if (isset($data->jurnal)) {
                    if ($data->credit != 0) {
                        $credit = $data->credit;
                        // $checkingCoaPPN = JournalDetail::where('journal_id', $data->journal_id)->where('status', 1)
                        //     ->get();
                        // if ($checkingCoaPPN->pluck('coa_code')->contains('1-600') && $data->coa_code != "1-600" && $data->debit <= 0) {
                        //     $ppn_keluaran = $checkingCoaPPN->where('coa_code', '1-600')->pluck('credit')->first();
                        //     if ($ppn_keluaran > 0) {
                        //         $credit += $ppn_keluaran;
                        //     }
                        // }
                        return  number_format($credit);
                    } else {
                        return 0;
                    }
                } else {
                    if ($data['credit'] != 0) {
                        return number_format($data['credit']);
                    } else {
                        return 0;
                    }
                }
            })
            ->editColumn('sub_debit', function ($data) use (&$totalDebit_for_subDebit, &$totalKredit_for_subDebit) {
                if (isset($data->jurnal)) {
                    $debit = $data->debit ? $data->debit : 0;
                    // $checkingCoaPPN = JournalDetail::where('journal_id', $data->journal_id)->where('status', 1)
                    //     ->get();
                    // if ($checkingCoaPPN->pluck('coa_code')->contains('2-300') && $data->coa_code != "2-300" && $data->credit <= 0) {
                    //     $ppn_keluaran = $checkingCoaPPN->where('coa_code', '2-300')->pluck('debit')->first();
                    //     if ($ppn_keluaran > 0) {
                    //         $debit += $ppn_keluaran;
                    //     }
                    // }

                    // $debit = $data->debit ? $data->debit : 0;
                    $kredit = $data->credit ? $data->credit : 0;
                    // $kredit = $data->credit;
                    // $checkingCoaPPN = JournalDetail::where('journal_id', $data->journal_id)->where('status', 1)
                    //     ->get();
                    // if ($checkingCoaPPN->pluck('coa_code')->contains('1-600') && $data->coa_code != "1-600" && $data->debit <= 0) {
                    //     $ppn_keluaran = $checkingCoaPPN->where('coa_code', '1-600')->pluck('credit')->first();
                    //     if ($ppn_keluaran > 0) {
                    //         $kredit += $ppn_keluaran;
                    //     }
                    // }

                    $totalDebit_for_subDebit += $debit;
                    $totalKredit_for_subDebit += $kredit;

                    $saldoDebit = $totalDebit_for_subDebit - $totalKredit_for_subDebit;
                    // $saldoKredit = $totalKredit - $totalDebit;
                    if ($saldoDebit > 0) {
                        return number_format($saldoDebit);
                    } else {
                        return 0;
                    }
                } else {
                    $debit = $data['debit'] ? $data['debit'] : 0;
                    $kredit = $data['credit'] ? $data['credit'] : 0;

                    $totalDebit_for_subDebit += $debit;
                    $totalKredit_for_subDebit += $kredit;

                    // $saldoDebit = $totalDebit - $totalKredit;
                    $saldoKredit = $totalDebit_for_subDebit - $totalKredit_for_subDebit;
                    if ($saldoKredit > 0) {
                        return number_format($saldoKredit);
                    } else {
                        return 0;
                    }
                }
            })
            ->editColumn('sub_credit', function ($data) use (&$totalDebit_for_subKredit, &$totalKredit_for_subKredit) {
                if (isset($data->jurnal)) {
                    $debit = $data->debit ? $data->debit : 0;
                    // $checkingCoaPPN = JournalDetail::where('journal_id', $data->journal_id)->where('status', 1)
                    //     ->get();
                    // if ($checkingCoaPPN->pluck('coa_code')->contains('2-300') && $data->coa_code != "2-300" && $data->credit <= 0) {
                    //     $ppn_keluaran = $checkingCoaPPN->where('coa_code', '2-300')->pluck('debit')->first();
                    //     if ($ppn_keluaran > 0) {
                    //         $debit += $ppn_keluaran;
                    //     }
                    // }

                    // $debit = $data->debit ? $data->debit : 0;
                    $kredit = $data->credit ? $data->credit : 0;
                    // $kredit = $data->credit;
                    // $checkingCoaPPN = JournalDetail::where('journal_id', $data->journal_id)->where('status', 1)
                    //     ->get();
                    // if ($checkingCoaPPN->pluck('coa_code')->contains('1-600') && $data->coa_code != "1-600" && $data->debit <= 0) {
                    //     $ppn_keluaran = $checkingCoaPPN->where('coa_code', '1-600')->pluck('credit')->first();
                    //     if ($ppn_keluaran > 0) {
                    //         $kredit += $ppn_keluaran;
                    //     }
                    // }

                    $totalDebit_for_subKredit += $debit;
                    $totalKredit_for_subKredit += $kredit;

                    // $saldoDebit = $totalDebit - $totalKredit;
                    $saldoKredit = $totalKredit_for_subKredit - $totalDebit_for_subKredit;
                    if ($saldoKredit > 0) {
                        return number_format($saldoKredit);
                    } else {
                        return 0;
                    }
                } else {
                    $debit = $data['debit'] ? $data['debit'] : 0;
                    $kredit = $data['credit'] ? $data['credit'] : 0;

                    $totalDebit_for_subKredit += $debit;
                    $totalKredit_for_subKredit += $kredit;

                    // $saldoDebit = $totalDebit - $totalKredit;
                    $saldoKredit = $totalKredit_for_subKredit - $totalDebit_for_subKredit;
                    if ($saldoKredit > 0) {
                        return number_format($saldoKredit);
                    } else {
                        return 0;
                    }
                }
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function balance_sheet()
    {
        // dd('sdsdsd');
        $all_warehouses = WarehouseModel::where('type', 5)->get();
        $data = [

            'all_warehouses' => $all_warehouses,
            'title' => 'Balance Sheet'
        ];

        return view('finance_report.balance_sheet', $data);
    }

    public function balance_sheet_table()
    {
        $dataTambahan = [
            'code' => '',
            'acc' => '',
            'total' => '',
        ];
        if (request()->type == "aktiva_lancar") {
            $get_coa = Coa::whereIn('coa_category_id', [2, 3, 5, 4, 11, 6])->orWhere('coa_code', '1-600')->get();
        } else if (request()->type == "kewajiban") {
            $get_coa = Coa::whereIn('coa_category_id', [10, 9, 12])->get();
        } else if (request()->type == "aktiva_tetap") {

            $get_coa = Coa::whereIn('coa_category_id', [7, 8])->where('coa_code', '!=', '1-600')->get();
            for ($x = 0; $x < 8; $x++) {
                $get_coa->push($dataTambahan);
            }
        } else {
            $get_coa = Coa::whereIn('coa_category_id', [13])->get();
            for ($x = 0; $x < 8; $x++) {
                $get_coa->push($dataTambahan);
            }
        }


        // $account = JournalDetail::with(
        //     'jurnal'
        // )
        //     ->where('coa_code', request()->acc_id)
        //     ->whereHas('jurnal', function ($q) {
        //         $q->when(
        //             request()->from_date,
        //             function ($que) {
        //                 return $que->whereBetween(
        //                     'date',
        //                     [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
        //                 );
        //             },
        //             function ($quer) {
        //                 return $quer->where('date', date('Y-m-d'));
        //             }
        //         );
        //         $q->when(
        //             request()->warehouse,
        //             function ($que) {
        //                 return $que->where('warehouse_id', request()->warehouse);
        //             },
        //             function ($quer) {
        //                 return $quer->where('warehouse_id', 1);
        //             }
        //         );
        //     })
        //     ->get()
        //     ->sortBy(function ($acc) {
        //         return $acc->jurnal->date;
        //     });

        return datatables()->of($get_coa)
            ->editColumn('code', function ($data) {
                if (!isset($data->coa_code)) {
                    return html_entity_decode("&nbsp;");
                }
                return $data->coa_code;
            })
            ->editColumn('acc', function ($data) {
                if (!isset($data->coa_code)) {
                    return html_entity_decode("&nbsp;");
                }
                return $data->name;
            })


            ->editColumn('total', function ($data) {
                if (!isset($data->coa_code)) {
                    return html_entity_decode("&nbsp;");
                }
                if ($data->coa_code == "3-103") {
                    //Start count Pendapatan
                    $getPendapatan = JournalDetail::with('coa')->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        ->whereHas('coa', function ($q) {
                            $q->where('coa_category_id', 14);
                        })
                        ->where('coa_code', '!=', '4-102')
                        ->get()->sum('debit');

                    $getPendapatan -= JournalDetail::with('coa')->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        ->whereHas('coa', function ($q) {
                            $q->where('coa_category_id', 14);
                        })
                        ->where('coa_code', '!=', '4-102')
                        ->get()->sum('credit');

                    $saldo_awal_pendapatan = CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                        $q->where('coa_category_id', 14);
                        $q->where('coa_code', '!=', '4-102');
                    })
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->sum("debit");

                    $saldo_awal_pendapatan -= CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                        $q->where('coa_category_id', 14);
                        $q->where('coa_code', '!=', '4-102');
                    })
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->sum("kredit");

                    $getPendapatan += $saldo_awal_pendapatan;

                    $getReturPenjualan = JournalDetail::whereHas('jurnal', function ($q) {
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
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        });
                    })->where('status', 1)

                        ->where('coa_code', '4-102')
                        ->get()->sum('debit');

                    $getReturPenjualan -= JournalDetail::whereHas('jurnal', function ($q) {
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
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        });
                    })->where('status', 1)

                        ->where('coa_code', '4-102')
                        ->get()->sum('credit');

                    $saldo_awal_return_penjualan = CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                        $q->where('coa_code', '4-102');
                    })
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->sum("debit");

                    $saldo_awal_return_penjualan -= CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                        $q->where('coa_code', '4-102');
                    })
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->sum("kredit");

                    $getReturPenjualan += $saldo_awal_return_penjualan;

                    $total_pendapatan = $getPendapatan - $getReturPenjualan;
                    //End count pendapatan

                    //Start count Persediaan barang
                    $getPembelian = PurchaseOrderModel::when(
                        request()->from_date,
                        function ($que) {
                            return $que->whereBetween(
                                'order_date',
                                [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                            );
                        },
                        function ($quer) {
                            return $quer->whereYear('order_date', Carbon::now()->year);
                        }
                    )->when(request()->warehouse, function ($que) {
                        return $que->where('warehouse_id', request()->warehouse);
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    })->sum('total') / 1.11;

                    $getPersediaanAwal = CoaSaldo::when(request()->warehouse, function ($que) {
                        return $que->where('warehouse_id', request()->warehouse);
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    })->where('coa_id', 12)->first();
                    $persediaan_awal = $getPersediaanAwal->debit - $getPersediaanAwal->kredit;

                    $getReturPembelian = JournalDetail::where('coa_code', '5-103')->where('status', 1)
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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })->get();
                    $total = 0;
                    foreach ($getReturPembelian as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', '5-103')->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
                    $retur_pembelian = $total;

                    $total_persediaan_awal = $persediaan_awal + $getPembelian - $retur_pembelian;
                    //End count Persediaan barang

                    //Start count Persediaan akhir
                    $getPersediaanBarang = JournalDetail::where('coa_code', '1-401')->where('status', 1)
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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })->get();
                    $total = 0;
                    foreach ($getPersediaanBarang as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', '1-401')->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
                    $persediaan_barang = $total;

                    $getReturPembelian = JournalDetail::where('coa_code', '5-103')->where('status', 1)
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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })->get();
                    $total = 0;
                    foreach ($getReturPembelian as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', '5-103')->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
                    $retur_pembelian = $total;

                    $persediaan_akhir = $persediaan_barang - $retur_pembelian;
                    //End count Persediaan akhir

                    //start count laba kotor
                    $total_hpp = $total_persediaan_awal - $persediaan_akhir;
                    $laba_kotor = $total_pendapatan + $total_hpp;
                    //end count laba kotor

                    //start count biaya

                    $getBiaya = JournalDetail::with('coa')->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        ->whereHas('coa', function ($q) {
                            $q->where('coa_category_id', [15, 18]);
                        })
                        ->where('coa_code', '!=', '5-103')
                        ->get()->sum('debit');

                    $getBiaya -= JournalDetail::with('coa')->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        ->whereHas('coa', function ($q) {
                            $q->where('coa_category_id', [15, 18]);
                        })
                        ->where('coa_code', '!=', '5-103')
                        ->get()->sum('credit');

                    $saldo_awal_biaya = CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                        $q->where('coa_category_id', [15, 18]);
                        $q->where('coa_code', '!=', '5-103');
                    })
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->sum("debit");

                    $saldo_awal_biaya -= CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                        $q->where('coa_category_id', [15, 18]);
                        $q->where('coa_code', '!=', '5-103');
                    })
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->sum("kredit");

                    $getBiaya += $saldo_awal_biaya;
                    //end count biaya

                    //start count laba bersih
                    $laba_bersih = $laba_kotor + $getBiaya;
                    //end count laba bersih
                    $total = $laba_bersih;
                } else {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        // ->whereHas('coa', function ($q) {
                        //     $q->where('post', 'NRC');
                        // })
                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
                }
                if (request()->type == "kewajiban" || request()->type == "modal") {
                    if ($total < 0) {
                        return number_format(abs($total));
                    } else if ($total > 0) {
                        return number_format(-$total);
                    } else {
                        return 0;
                    }
                } else {
                    return number_format($total);
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
            )->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })->when(request()->adjustment, function ($que) {
                    if (request()->adjustment == 'before') {
                        return $que->whereNotIn('coa_code', [
                            '5-504', '5-700', '5-800', '5-900', '5-1000', '5-1500',
                            '5-1600'
                        ]);
                    }
                })
                // ->whereHas('coa', function ($q) {
                //     $q->where('post', 'NRC');
                // })
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

                    return $acc;
                })
                ->editColumn('post', function ($data) {
                    $acc = Coa::where('coa_code', $data->coa_code)->first()->post;

                    return $acc;
                })
                ->editColumn('sub_debit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                            // $q->when(request()->adjustment, function ($que) {
                            //     if (request()->adjustment == 'before') {
                            //         return $que->where('isadjusted', 1);
                            //     }
                            // });
                        })
                        ->when(request()->adjustment, function ($que) use ($data) {
                            if (request()->adjustment == 'before') {
                                if (in_array($data->coa_code, ['1-500', '1-501', '1-502', '1-800', '1-801', '1-802', '1-803', '1-804'])) {
                                    return $que->where('credit', 0);
                                }
                            }
                        })
                        // ->whereHas('coa', function ($q) {
                        //     $q->where('post', 'NRC');
                        // })
                        ->get();
                    $total_adjustment = 0;
                    if (request()->adjustment == 'before') {
                    }

                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
                    if ($total > 0) {

                        return number_format($total + $total_adjustment);
                    } else {
                        return '-';
                    }
                })
                ->editColumn('sub_credit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)
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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        // ->whereHas('coa', function ($q) {
                        //     $q->where('post', 'NRC');
                        // })
                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();

                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
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

        return view('finance_report.trial_balance', $data);
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
        $keyElement1 = array_key_last($nameHeader);
        $removedElements1 = array_pop($nameHeader);
        $keyElement2 = array_key_last($nameHeader);
        $removedElements2 = array_pop($nameHeader);

        $nameHeader["333"] = "Persediaan Barang";
        $nameHeader["666"] = "Harga Pokok Penjualan";
        $nameHeader[$keyElement1] = $removedElements1;
        $nameHeader[$keyElement2] = $removedElements2;
        // dd($nameHeader);

        if (request()->ajax()) {

            if (request()->acc_id < 300) {
                $getPendapatan = Coa::where('coa_category_id', request()->acc_id)->where('coa_code', '!=', '5-103')
                    ->get()->sortBy(function ($q) {
                        return $q->name;
                    });
            } else {
                switch (request()->acc_id) {
                    case 333:
                        $getPembelian = PurchaseOrderModel::when(
                            request()->from_date,
                            function ($que) {
                                return $que->whereBetween(
                                    'order_date',
                                    [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                                );
                            },
                            function ($quer) {
                                return $quer->whereYear('order_date', Carbon::now()->year);
                            }
                        )->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->sum('total') / 1.11;

                        $getPersediaanAwal = CoaSaldo::when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->where('coa_id', 12)->first();
                        $persediaan_awal = $getPersediaanAwal->debit - $getPersediaanAwal->kredit;

                        $getReturPembelian = JournalDetail::where('coa_code', '5-103')->where('status', 1)
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
                                }, function ($quer) {
                                    return $quer->where('warehouse_id', 1);
                                });
                            })->get();
                        $total = 0;
                        foreach ($getReturPembelian as $value) {
                            if ($value->debit) {
                                $total += $value->debit;
                            } else {
                                $total -= $value->credit;
                            }
                        }
                        // $saldoKredit = $totalKredit - $totalDebit;
                        $get_coa = Coa::where('coa_code', '5-103')->first();
                        $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                            ->when(request()->warehouse, function ($que) {
                                return $que->where('warehouse_id', request()->warehouse);
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            })->first();
                        $total += $saldo_awal->debit;
                        $total -= $saldo_awal->kredit;
                        $retur_pembelian = $total;

                        $getPendapatan = [
                            ["acc_name" => "Persediaan Awal", "sub_total" => $persediaan_awal],
                            ["acc_name" => "Pembelian", "sub_total" => $getPembelian],
                            ["acc_name" => "Retur Pembelian", "sub_total" => $retur_pembelian]
                        ];
                        break;

                    case 666:
                        $getPersediaanBarang = JournalDetail::where('coa_code', '1-401')->where('status', 1)
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
                                }, function ($quer) {
                                    return $quer->where('warehouse_id', 1);
                                });
                            })->get();
                        $total = 0;
                        foreach ($getPersediaanBarang as $value) {
                            if ($value->debit) {
                                $total += $value->debit;
                            } else {
                                $total -= $value->credit;
                            }
                        }
                        // $saldoKredit = $totalKredit - $totalDebit;
                        $get_coa = Coa::where('coa_code', '1-401')->first();
                        $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                            ->when(request()->warehouse, function ($que) {
                                return $que->where('warehouse_id', request()->warehouse);
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            })->first();
                        $total += $saldo_awal->debit;
                        $total -= $saldo_awal->kredit;
                        $persediaan_barang = $total;

                        $getReturPembelian = JournalDetail::where('coa_code', '5-103')->where('status', 1)
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
                                }, function ($quer) {
                                    return $quer->where('warehouse_id', 1);
                                });
                            })->get();
                        $total = 0;
                        foreach ($getReturPembelian as $value) {
                            if ($value->debit) {
                                $total += $value->debit;
                            } else {
                                $total -= $value->credit;
                            }
                        }
                        // $saldoKredit = $totalKredit - $totalDebit;
                        $get_coa = Coa::where('coa_code', '5-103')->first();
                        $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                            ->when(request()->warehouse, function ($que) {
                                return $que->where('warehouse_id', request()->warehouse);
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            })->first();
                        $total += $saldo_awal->debit;
                        $total -= $saldo_awal->kredit;
                        $retur_pembelian = $total;

                        $persediaan_akhir = $persediaan_barang - $retur_pembelian;
                        $getPendapatan = [
                            ["acc_name" => "Persediaan Akhir", "sub_total" => $persediaan_akhir],
                        ];
                    default:
                        # code...
                        break;
                }
            }

            return datatables()->of($getPendapatan)
                ->editColumn('acc_name', function ($data) {
                    if (isset($data->coa_code)) {
                        $acc = Coa::where('coa_code', $data->coa_code)->first()->name;
                    } else {
                        $acc = $data["acc_name"];
                    }
                    return $acc;
                })
                ->editColumn('sub_total', function ($data) {
                    if (isset($data->coa_code)) {
                        $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)
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
                                }, function ($quer) {
                                    return $quer->where('warehouse_id', 1);
                                });
                            })->get();
                        $total = 0;
                        foreach ($get_all_jurnal as $value) {
                            
                                $total += $value->debit;
                            
                                $total -= $value->credit;
                            
                        }
                        // $saldoKredit = $totalKredit - $totalDebit;
                        $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                        $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                            ->when(request()->warehouse, function ($que) {
                                return $que->where('warehouse_id', request()->warehouse);
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            })->first();
                        $total += $saldo_awal->debit;
                        $total -= $saldo_awal->kredit;
                    } else {
                        $total = $data["sub_total"];
                    }

                    return number_format(abs($total));

                    // return number_format(abs($total));
                })
                // ->editColumn('total', function ($data) {

                //     return '';
                // })
                ->addIndexColumn()
                ->make(true);
        }
        $all_warehouses = WarehouseModel::where('type', 5)->orderBy('warehouses')->get();

        $data = [
            'title' => 'Profit and Loss',
            // 'profit' => $profit,
            'all_account' => $getAllAccount,
            'nameHeader' => $nameHeader,
            'all_warehouses' => $all_warehouses
        ];

        return view('finance_report.loss_profit', $data);
    }

    public function adjustment_journal()
    {
        if (request()->ajax()) {

            $get_jurnal = JournalDetail::with(
                'jurnal'
            )->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })
                // ->whereIn('coa_code', [
                //     '1-800', '1-801', '1-802', '1-803', '1-804', '5-504',
                //     '5-700', '5-800', '5-900', '5-1000', '5-1500',
                //     '5-1600', '1-500', '1-501', '1-502'
                // ])
                ->where(function ($query) {
                    $query->whereIn('coa_code', ['1-500', '1-501', '1-502', '1-800', '1-801', '1-802', '1-803', '1-804'])
                        ->where('credit', '>', 0)
                        ->orWhereIn('coa_code', ['5-504', '5-700', '5-800', '5-900', '5-1000', '5-1500', '5-1600'])
                        ->where('debit', '>', 0);
                })
                // ->orWhere(function ($query) {
                //     $query->where('status', 1);
                //     $query->whereIn('coa_code', ['5-504', '5-700', '5-800', '5-900', '5-1000', '5-1500', '5-1600'])
                //         ->where('debit', '>', 0);
                // })

                // ->whereHas('coa', function ($q) {
                //     $q->where('post', 'NRC');
                // })
                // ->when(request()->acc_code,  function ($query) {
                //     return $query->where('account_id', request()->acc_code);
                // })
                ->get()
                ->sortBy(function ($inv) {
                    return [$inv->jurnal->date, $inv->jurnal->created_at];
                });
            // dd($get_jurnal);
            return datatables()->of($get_jurnal)
                ->editColumn('date', function ($data) {
                    return date('d-m-Y', strtotime($data->jurnal->date));
                })
                ->editColumn('ref', function ($data) {
                    return $data->ref;
                })
                ->editColumn('account', function ($data) {
                    if ($data->credit != 0) {

                        return  '<span class="ms-3">&nbsp;&nbsp;' . $data->coa->name . '</span>';
                    } else {

                        return  '<span> ' . $data->coa->name . ' </span>';
                    }
                })
                ->editColumn('code', function ($data) {
                    return $data->coa_code;
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
                ->rawColumns(['account'])
                ->addIndexColumn()
                ->make(true);
        }

        $all_warehouses = WarehouseModel::where('type', 5)->orderBy('warehouses')->get();


        $data = [
            'title' => 'Adjustment Journal',
            'all_warehouses' => $all_warehouses
        ];

        return view('finance_report.adjustment_journal', $data);
    }

    public function worksheet()
    {
        if (request()->ajax()) {

            $get_jurnal = Coa::orderBy('coa_code')
                ->get();
            // dd($get_jurnal);
            return datatables()->of($get_jurnal)
                ->editColumn('code', function ($data) {
                    return $data->coa_code;
                })
                ->editColumn('name', function ($data) {
                    return $data->name;
                })
                ->editColumn('sn', function ($data) {
                    return $data->bertambah;
                })
                ->editColumn('pos', function ($data) {
                    return $data->post;
                })
                ->editColumn('tb_debit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        ->whereNotIn('coa_code', [
                            '1-800', '1-801', '1-802', '1-803', '1-804', '5-504',
                            '5-700', '5-800', '5-900', '5-1000', '5-1500', '5-1600'
                        ])
                        // ->whereHas('coa', function ($q) {
                        //     $q->where('post', 'NRC');
                        // })
                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
                    if ($total > 0) {

                        return number_format($total);
                    } else {
                        return '-';
                    }
                })
                ->editColumn('tb_credit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)
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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        ->whereNotIn('coa_code', [
                            '1-800', '1-801', '1-802', '1-803', '1-804', '5-504',
                            '5-700', '5-800', '5-900', '5-1000', '5-1500', '5-1600'
                        ])
                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();

                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
                    if ($total < 0) {
                        return number_format(abs($total));
                    } else {
                        return '-';
                    }
                })
                ->editColumn('aj_debit', function ($data) {
                    $array_adj = [
                        '1-800', '1-801', '1-802', '1-803', '1-804', '5-504',
                        '5-700', '5-800', '5-900', '5-1000', '5-1500',
                        '5-1600', '1-500', '1-501', '1-502'
                    ];
                    if (in_array($data->coa_code, $array_adj)) {
                        $get_all_jurnal = JournalDetail::with(
                            'jurnal'
                        )->where('status', 1)
                            ->where('coa_code', $data->coa_code)
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
                                }, function ($quer) {
                                    return $quer->where('warehouse_id', 1);
                                });
                            })
                            ->whereIn('coa_code', [
                                '1-800', '1-801', '1-802', '1-803', '1-804', '5-504',
                                '5-700', '5-800', '5-900', '5-1000', '5-1500', '5-1600'
                            ])

                            ->get();
                        $total = 0;

                        if ($get_all_jurnal) {
                            foreach ($get_all_jurnal as $value) {
                                if ($value->debit) {
                                    $total += $value->debit;
                                } else {
                                    $total -= $value->credit;
                                }
                            }
                        }

                        // $saldoKredit = $totalKredit - $totalDebit;
                        $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                        $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                            ->when(request()->warehouse, function ($que) {
                                return $que->where('warehouse_id', request()->warehouse);
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            })->first();
                        $total += $saldo_awal->debit;
                        $total -= $saldo_awal->kredit;

                        if ($total > 0) {
                            return number_format($total);
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->editColumn('aj_credit', function ($data) {
                    $array_adj = [
                        '1-800', '1-801', '1-802', '1-803', '1-804', '5-504',
                        '5-700', '5-800', '5-900', '5-1000', '5-1500',
                        '5-1600', '1-500', '1-501', '1-502'
                    ];
                    if (in_array($data->coa_code, $array_adj)) {
                        $get_all_jurnal = JournalDetail::with(
                            'jurnal'
                        )->where('status', 1)
                            ->where('coa_code', $data->coa_code)
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
                                }, function ($quer) {
                                    return $quer->where('warehouse_id', 1);
                                });
                            })
                            ->whereIn('coa_code', [
                                '1-800', '1-801', '1-802', '1-803', '1-804', '5-504',
                                '5-700', '5-800', '5-900', '5-1000', '5-1500', '5-1600'
                            ])

                            ->get();
                        $total = 0;

                        if ($get_all_jurnal) {
                            foreach ($get_all_jurnal as $value) {
                                if ($value->debit) {
                                    $total += $value->debit;
                                } else {
                                    $total -= $value->credit;
                                }
                            }
                        }

                        // $saldoKredit = $totalKredit - $totalDebit;
                        $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                        $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                            ->when(request()->warehouse, function ($que) {
                                return $que->where('warehouse_id', request()->warehouse);
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            })->first();
                        $total += $saldo_awal->debit;
                        $total -= $saldo_awal->kredit;

                        if ($total < 0) {
                            return number_format(abs($total));
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->editColumn('atb_debit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })

                        // ->whereHas('coa', function ($q) {
                        //     $q->where('post', 'NRC');
                        // })
                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
                    if ($total > 0) {

                        return number_format($total);
                    } else {
                        return '-';
                    }
                })
                ->editColumn('atb_credit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })

                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;
                    if ($total < 0) {

                        return number_format(abs($total));
                    } else {
                        return '-';
                    }
                })
                ->editColumn('pl_debit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        ->whereHas('coa', function ($q) {
                            $q->where('post', 'LR');
                        })
                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;


                    if ($total > 0) {
                        return number_format(abs($total));
                    } else {
                        return '-';
                    }
                })
                ->editColumn('pl_credit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        ->whereHas('coa', function ($q) {
                            $q->where('post', 'LR');
                        })
                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;


                    if ($total < 0) {
                        return number_format(abs($total));
                    } else {
                        return '-';
                    }
                })
                ->editColumn('bs_debit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        // ->whereHas('coa', function ($q) {
                        //     $q->where('post', 'NRC');
                        // })
                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;

                    if ($total > 0) {
                        return number_format(abs($total));
                    } else {
                        return '-';
                    }
                })
                ->editColumn('bs_credit', function ($data) {
                    $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)

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
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            });
                        })
                        // ->whereHas('coa', function ($q) {
                        //     $q->where('post', 'NRC');
                        // })
                        ->get();
                    $total = 0;
                    foreach ($get_all_jurnal as $value) {
                        if ($value->debit) {
                            $total += $value->debit;
                        } else {
                            $total -= $value->credit;
                        }
                    }
                    // $saldoKredit = $totalKredit - $totalDebit;
                    $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                    $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total += $saldo_awal->debit;
                    $total -= $saldo_awal->kredit;

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
            'title' => 'Worksheet',
            'all_warehouses' => $all_warehouses
        ];

        return view('finance_report.worksheet', $data);
    }

    public function closing_journal()
    {
        //Get Pendapatan dan Biaya
        $getPendapatan = Coa::where('coa_category_id', 14);
        $getBiaya = Coa::whereIn('coa_category_id', [15, 18]);
        // $getModalPrive = Coa::where('coa_category_id', 13)->whereIn('coa_code', ['3-100', '3-101']);
        $dataTambahan = [
            'coa_category_id' => '3-100',

        ];
        $getAllAccount = $getPendapatan
            ->union($getBiaya)
            // ->union(collect($dataTambahan))
            ->get()->sortBy(function ($q) {
                return $q->name;
            })->groupBy('coa_category_id');

        $newValues = [2, 3];
        $array_acc = $getAllAccount->toArray();
        $array_acc['3-100'] = 'Modal';
        $array_acc['3-101'] = 'Prive';
        // Menambahkan nilai baru ke dalam setiap grup
        // $getAllAccount->transform(function ($group) use ($newValues) {
        //     return $group->merge($newValues);
        // });

        //Get Nama Pendapatan dan Biaya
        $nameHeader = [];
        foreach ($array_acc as $key => $value) {
            $getSub = Coa::where('coa_category_id', strval($key))->first();

            if ($key != "3-100" && $key != "3-101") {
                $nameHeader[$key] = $getSub->name;
            } else {
                $nameHeader[$key] = $array_acc[$key];
            }
        }
        // dd($nameHeader);

        if (request()->ajax()) {
            $saldo_debit = 0;
            $saldo_credit = 0;
            $acc_name = '';
            $acc_code = '';
            if (request()->acc_id != '3-100' && request()->acc_id != '3-101') {
                $getPendapatan = Coa::with('journals')->where('coa_category_id', request()->acc_id)
                    ->get()->sortBy(function ($q) {
                        return $q->name;
                    });
                $total_debit = 0;
                $total_credit = 0;
                foreach ($getPendapatan as $key => $value) {
                    $total_debit += $value->journals()->whereHas('jurnal', function ($q) {
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
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        });
                    })->sum('debit');
                    $total_credit += $value->journals()->whereHas('jurnal', function ($q) {
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
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        });
                    })->sum('credit');

                    $saldo_awal = CoaSaldo::where('coa_id', $value->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total_debit += $saldo_awal->debit;
                    $total_credit += $saldo_awal->credit;
                }

                $total_lr = $total_debit - $total_credit;
                $acc_name = 'Ikhtisar Laba/Rugi';
                $acc_code = '-';
                if (request()->acc_id == 14) {
                    if ($total_lr > 0) {
                        $saldo_debit = abs($total_lr);
                        $saldo_credit = '-';
                    } else {
                        $saldo_debit = '-';
                        $saldo_credit = abs($total_lr);
                    }
                } else {
                    if ($total_lr < 0) {
                        $saldo_debit = abs($total_lr);
                        $saldo_credit = '-';
                    } else {
                        $saldo_debit = '-';
                        $saldo_credit = abs($total_lr);
                    }
                }
            } else {
                $getPendapatan = Coa::with('journals')->where('coa_code', request()->acc_id)
                    ->get()->sortBy(function ($q) {
                        return $q->name;
                    });
                $total_debit = 0;
                $total_credit = 0;
                foreach ($getPendapatan as $key => $value) {
                    $total_debit += $value->journals()->whereHas('jurnal', function ($q) {
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
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        });
                    })->sum('debit');
                    $total_credit += $value->journals()->whereHas('jurnal', function ($q) {
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
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        });
                    })->sum('credit');

                    $saldo_awal = CoaSaldo::where('coa_id', $value->id)
                        ->when(request()->warehouse, function ($que) {
                            return $que->where('warehouse_id', request()->warehouse);
                        }, function ($quer) {
                            return $quer->where('warehouse_id', 1);
                        })->first();
                    $total_debit += $saldo_awal->debit;
                    $total_credit += $saldo_awal->credit;
                }

                $total_lr = $total_debit - $total_credit;

                if ($total_lr > 0) {
                    $saldo_debit = abs($total_lr);
                    $saldo_credit = '-';
                } else {
                    $saldo_debit = '-';
                    $saldo_credit = abs($total_lr);
                }

                if (request()->acc_id == '3-100') {
                    $acc_name = 'Ikhtisar Laba/Rugi';
                    $acc_code = '-';
                } else {
                    $acc_name = 'Modal';
                    $acc_code = '3-100';
                }
            }

            $dataTambahan = [
                'date' => date('t-m-Y'),
                'acc_name' => $acc_name,
                'acc_code' => $acc_code,
                'debit' => $saldo_debit,  // Isi sesuai kebutuhan
                'credit' => $saldo_credit, // Isi sesuai kebutuhan
            ];
            $combinedData = collect([$dataTambahan])->merge($getPendapatan);

            return datatables()->of($combinedData)
                ->editColumn('date', function () {
                    $lastDayThisYear = date('t-m-Y');
                    return $lastDayThisYear;
                })
                ->editColumn('acc_name', function ($data) {
                    if (isset($data->coa_code)) {
                        $acc = Coa::where('coa_code', $data->coa_code)->first()->name;
                        return $acc;
                    } else {
                        return $data['acc_name'];
                    }
                })
                ->editColumn('acc_code', function ($data) {

                    if (isset($data->coa_code)) {
                        return $data->coa_code;
                    } else {
                        return $data['acc_code'];
                    }
                })
                ->editColumn('debit', function ($data) {
                    if (isset($data->coa_code)) {
                        $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)
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
                                }, function ($quer) {
                                    return $quer->where('warehouse_id', 1);
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
                        $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                        $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                            ->when(request()->warehouse, function ($que) {
                                return $que->where('warehouse_id', request()->warehouse);
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            })->first();
                        $total += $saldo_awal->debit;
                        $total -= $saldo_awal->kredit;

                        if (request()->acc_id != '3-100' && request()->acc_id != '3-101') {
                            if ($total < 0) {

                                return number_format(abs($total));
                            } else {
                                return '-';
                            }
                        } else {
                            if ($total > 0) {

                                return number_format(abs($total));
                            } else {
                                return '-';
                            }
                        }
                    } else {
                        if ($data['debit'] != '-') {
                            return number_format(abs(intval($data['debit'])));
                        } else return $data['debit'];
                    }
                })
                ->editColumn('credit', function ($data) {
                    if (isset($data->coa_code)) {
                        $get_all_jurnal = JournalDetail::where('coa_code', $data->coa_code)->where('status', 1)
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
                                }, function ($quer) {
                                    return $quer->where('warehouse_id', 1);
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
                        $get_coa = Coa::where('coa_code', $data->coa_code)->first();
                        $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                            ->when(request()->warehouse, function ($que) {
                                return $que->where('warehouse_id', request()->warehouse);
                            }, function ($quer) {
                                return $quer->where('warehouse_id', 1);
                            })->first();
                        $total += $saldo_awal->debit;
                        $total -= $saldo_awal->kredit;

                        if (request()->acc_id != '3-100' && request()->acc_id != '3-101') {
                            if ($total > 0) {

                                return number_format(abs($total));
                            } else {
                                return '-';
                            }
                        } else {
                            if ($total < 0) {

                                return number_format(abs($total));
                            } else {
                                return '-';
                            }
                        }
                    } else {
                        if ($data['credit'] != '-') {
                            return number_format(abs(intval($data['credit'])));
                        } else return $data['credit'];
                    }
                })

                ->addIndexColumn()
                ->make(true);
        }


        $all_warehouses = WarehouseModel::where('type', 5)->orderBy('warehouses')->get();

        $data = [
            'title' => 'Closing Journal',
            'all_account' => $array_acc,
            'nameHeader' => $nameHeader,
            'all_warehouses' => $all_warehouses
        ];

        return view('finance_report.closing_journal', $data);
    }

    public function capital_change()
    {
        // dd('sdsdsd');
        $all_warehouses = WarehouseModel::where('type', 5)->get();
        $data = [

            'all_warehouses' => $all_warehouses,
            'title' => 'Capital Change'
        ];

        return view('finance_report.capital_change', $data);
    }

    public function capital_change_table()
    {
        if (request()->type == "modal_awal") {
            $get_coa_saldo = CoaSaldo::where('coa_id', 29)->when(request()->warehouse, function ($que) {
                return $que->where('warehouse_id', request()->warehouse);
            }, function ($quer) {
                return $quer->where('warehouse_id', 1);
            })->first();
            $get_coa = [[
                "acc" => "Modal Awal",
                "total_left" => "",
                "total_right" => $get_coa_saldo->debit - $get_coa_saldo->kredit
            ]];
        } else if (request()->type == "penambahan") {
            //Counting Laba

            //Start count Pendapatan
            $getPendapatan = JournalDetail::with('coa')->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })
                ->whereHas('coa', function ($q) {
                    $q->where('coa_category_id', 14);
                })
                ->where('coa_code', '!=', '4-102')
                ->get()->sum('debit');

            $getPendapatan -= JournalDetail::with('coa')->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })
                ->whereHas('coa', function ($q) {
                    $q->where('coa_category_id', 14);
                })
                ->where('coa_code', '!=', '4-102')
                ->get()->sum('credit');

            $saldo_awal_pendapatan = CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                $q->where('coa_category_id', 14);
                $q->where('coa_code', '!=', '4-102');
            })
                ->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                })->sum("debit");

            $saldo_awal_pendapatan -= CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                $q->where('coa_category_id', 14);
                $q->where('coa_code', '!=', '4-102');
            })
                ->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                })->sum("kredit");

            $getPendapatan += $saldo_awal_pendapatan;

            $getReturPenjualan = JournalDetail::whereHas('jurnal', function ($q) {
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
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                });
            })->where('status', 1)

                ->where('coa_code', '4-102')
                ->get()->sum('debit');

            $getReturPenjualan -= JournalDetail::whereHas('jurnal', function ($q) {
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
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                });
            })->where('status', 1)

                ->where('coa_code', '4-102')
                ->get()->sum('credit');

            $saldo_awal_return_penjualan = CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                $q->where('coa_code', '4-102');
            })
                ->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                })->sum("debit");

            $saldo_awal_return_penjualan -= CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                $q->where('coa_code', '4-102');
            })
                ->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                })->sum("kredit");

            $getReturPenjualan += $saldo_awal_return_penjualan;

            $total_pendapatan = $getPendapatan - $getReturPenjualan;
            //End count pendapatan

            //Start count Persediaan barang
            $getPembelian = PurchaseOrderModel::when(
                request()->from_date,
                function ($que) {
                    return $que->whereBetween(
                        'order_date',
                        [date('Y-m-d', strtotime(request()->from_date)), date('Y-m-d', strtotime(request()->to_date))]
                    );
                },
                function ($quer) {
                    return $quer->whereYear('order_date', Carbon::now()->year);
                }
            )->when(request()->warehouse, function ($que) {
                return $que->where('warehouse_id', request()->warehouse);
            }, function ($quer) {
                return $quer->where('warehouse_id', 1);
            })->sum('total') / 1.11;

            $getPersediaanAwal = CoaSaldo::when(request()->warehouse, function ($que) {
                return $que->where('warehouse_id', request()->warehouse);
            }, function ($quer) {
                return $quer->where('warehouse_id', 1);
            })->where('coa_id', 12)->first();
            $persediaan_awal = $getPersediaanAwal->debit - $getPersediaanAwal->kredit;

            $getReturPembelian = JournalDetail::where('coa_code', '5-103')->where('status', 1)
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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })->get();
            $total = 0;
            foreach ($getReturPembelian as $value) {
                if ($value->debit) {
                    $total += $value->debit;
                } else {
                    $total -= $value->credit;
                }
            }
            // $saldoKredit = $totalKredit - $totalDebit;
            $get_coa = Coa::where('coa_code', '5-103')->first();
            $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                ->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                })->first();
            $total += $saldo_awal->debit;
            $total -= $saldo_awal->kredit;
            $retur_pembelian = $total;

            $total_persediaan_awal = $persediaan_awal + $getPembelian - $retur_pembelian;
            //End count Persediaan barang

            //Start count Persediaan akhir
            $getPersediaanBarang = JournalDetail::where('coa_code', '1-401')->where('status', 1)
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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })->get();
            $total = 0;
            foreach ($getPersediaanBarang as $value) {
                if ($value->debit) {
                    $total += $value->debit;
                } else {
                    $total -= $value->credit;
                }
            }
            // $saldoKredit = $totalKredit - $totalDebit;
            $get_coa = Coa::where('coa_code', '1-401')->first();
            $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                ->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                })->first();
            $total += $saldo_awal->debit;
            $total -= $saldo_awal->kredit;
            $persediaan_barang = $total;

            $getReturPembelian = JournalDetail::where('coa_code', '5-103')->where('status', 1)
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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })->get();
            $total = 0;
            foreach ($getReturPembelian as $value) {
                if ($value->debit) {
                    $total += $value->debit;
                } else {
                    $total -= $value->credit;
                }
            }
            // $saldoKredit = $totalKredit - $totalDebit;
            $get_coa = Coa::where('coa_code', '5-103')->first();
            $saldo_awal = CoaSaldo::where('coa_id', $get_coa->id)
                ->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                })->first();
            $total += $saldo_awal->debit;
            $total -= $saldo_awal->kredit;
            $retur_pembelian = $total;

            $persediaan_akhir = $persediaan_barang - $retur_pembelian;
            //End count Persediaan akhir

            //start count laba kotor
            $total_hpp = $total_persediaan_awal - $persediaan_akhir;
            $laba_kotor = $total_pendapatan - $total_hpp;
            //end count laba kotor

            //start count biaya

            $getBiaya = JournalDetail::with('coa')->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })
                ->whereHas('coa', function ($q) {
                    $q->where('coa_category_id', [15, 18]);
                })
                ->where('coa_code', '!=', '5-103')
                ->get()->sum('debit');

            $getBiaya -= JournalDetail::with('coa')->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })
                ->whereHas('coa', function ($q) {
                    $q->where('coa_category_id', [15, 18]);
                })
                ->where('coa_code', '!=', '5-103')
                ->get()->sum('credit');

            $saldo_awal_biaya = CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                $q->where('coa_category_id', [15, 18]);
                $q->where('coa_code', '!=', '5-103');
            })
                ->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                })->sum("debit");

            $saldo_awal_biaya -= CoaSaldo::with('coa')->whereHas('coa', function ($q) {
                $q->where('coa_category_id', [15, 18]);
                $q->where('coa_code', '!=', '5-103');
            })
                ->when(request()->warehouse, function ($que) {
                    return $que->where('warehouse_id', request()->warehouse);
                }, function ($quer) {
                    return $quer->where('warehouse_id', 1);
                })->sum("kredit");

            $getBiaya += $saldo_awal_biaya;
            //end count biaya

            //start count laba bersih
            $laba_bersih = $laba_kotor - $getBiaya;
            //end count laba bersih

            //start count modal
            $getModal = JournalDetail::with('coa')->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })
                ->where('coa_code', '3-100')
                ->get()->sum('debit');

            $getModal -= JournalDetail::with('coa')->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })

                ->where('coa_code', '3-100')
                ->get()->sum('credit');

            //end count modal

            $get_coa = [[
                "acc" => "Laba Tahun Berjalan",
                "total_left" => $laba_bersih,
                "total_right" => ""
            ], [
                "acc" => "Modal yang Disetor",
                "total_left" => $getModal,
                "total_right" => ""
            ]];
        } else if (request()->type == "pengurangan") {
            //start count prive
            $getPrive = JournalDetail::with('coa')->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })
                ->where('coa_code', '3-101')
                ->get()->sum('debit');

            $getPrive -= JournalDetail::with('coa')->where('status', 1)

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
                    }, function ($quer) {
                        return $quer->where('warehouse_id', 1);
                    });
                })

                ->where('coa_code', '3-101')
                ->get()->sum('credit');
            //end count prive
            $get_coa = [[
                "acc" => "Prive",
                "total_left" => $getPrive,
                "total_right" => ""
            ]];
        }
        return datatables()->of($get_coa)
            ->editColumn('acc', function ($data) {
                return $data["acc"];
            })
            ->editColumn('total_left', function ($data) {
                if ((int)$data["total_left"] == 0) {
                    return "-";
                }
                return number_format((int)$data["total_left"]);
            })
            ->editColumn('total_right', function ($data) {
                if ((int)$data["total_right"] == 0) {
                    return "-";
                }
                return number_format((int)$data["total_right"]);
            })
            ->addIndexColumn()
            ->make(true);
    }
}
