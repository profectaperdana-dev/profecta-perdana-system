<?php

namespace App\Helpers;

use App\Models\Finance\CoaSaldo;
use App\Models\Finance\Journal;
use App\Models\Finance\JournalDetail;
use Illuminate\Support\Facades\Auth;

function createJournal($date, $memo, $warehouse_id)
{
    $journal = new Journal();
    $journal->date = $date;
    $journal->memo = $memo;
    $journal->warehouse_id = $warehouse_id;
    $journal->created_by = Auth::user()->id;
    $saved = $journal->save();
    if ($saved) {
        return $journal->id;
    } else return false;
}

function changeSaldoTambah($coa, $warehouse, $saldo)
{
    $data = CoaSaldo::where('coa_id', $coa)
        ->where('warehouse_id', $warehouse)
        ->first();
    if ($data) {
        $data->saldo = $data->saldo + $saldo;
        $data->save();
    }
}
function changeSaldoKurang($coa, $warehouse, $saldo)
{
    $data = CoaSaldo::where('coa_id', $coa)
        ->where('warehouse_id', $warehouse)
        ->first();
    if ($data) {
        $data->saldo = $data->saldo - $saldo;
        $data->save();
    }
}

function createJournalDetail($journal_id, $account_id, $ref, $debit, $credit)
{
    $detail = new JournalDetail();
    $detail->journal_id = $journal_id;
    $detail->coa_code = $account_id;
    $detail->ref = $ref;
    $detail->debit = $debit;
    $detail->credit = $credit;
    $detail->created_by = Auth::user()->id;
    $detail->save();
}
