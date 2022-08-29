<?php

namespace App\Helpers;

use App\Models\CustomerModel;
use App\Models\SalesOrderModel;
use Carbon\Carbon;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Types\DateImmutableType;

function checkOverDue()
{
    //update overdue
    $SODebts = SalesOrderModel::where('payment_method', 3)
        ->where('isPaid', 0)
        ->get();

    foreach ($SODebts as $SODebt) {
        if (Carbon::now()->format('Y-m-d') >= $SODebt->duedate) {
            $selected_customer = CustomerModel::where('id', $SODebt->customers_id)->first();
            $selected_customer->isOverDue = 1;
            $selected_customer->label = 'Bad Customer';
            $selected_customer->save();
        }
    }
}

function checkOverPlafone($customer_id)
{
    $SODebts = SalesOrderModel::where('customers_id', $customer_id)
        ->where('payment_method', 3)
        ->where('isPaid', 0)
        ->get();
    $selected_customer = CustomerModel::where('id', $customer_id)->first();

    $total_credit = 0;
    foreach ($SODebts as $SODebt) {
        $total_credit = $total_credit + $SODebt->total_after_ppn;
    }

    if ($total_credit > $selected_customer->credit_limit) {
        $selected_customer->isOverPlafoned = 1;
        $selected_customer->label = 'Bad Customer';
        $selected_customer->save();
        return true;
    }
    return false;
}

function checkLastTransaction()
{
    $all_customers = CustomerModel::select('last_transaction', 'status')->get();
    foreach ($all_customers as $customer) {
        $dt = new DateTimeImmutable($customer->last_transaction, new DateTimeZone('Asia/Jakarta'));
        $dt = $dt->modify("+6 months");
        if ($customer->last_transaction == $dt) {
            $customer->status = 0;
        }
    }
}
