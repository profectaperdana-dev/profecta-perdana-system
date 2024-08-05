<?php

namespace App\Helpers;

use App\Models\CustomerModel;
use App\Models\DirectSalesModel;
use App\Models\ReturnModel;
use App\Models\ReturnRetailModel;
use App\Models\SalesOrderModel;
use Carbon\Carbon;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Types\DateImmutableType;

function checkOverDue()
{
    //NO LONGER USED
    
    //update overdue
    // $SODebts = SalesOrderModel::where('isPaid', 0)
    //     ->get();

    // $retailDebts = DirectSalesModel::where('isPaid', 0)->get();

    // if ($SODebts) {
    //     foreach ($SODebts as $SODebt) {
    //         if (Carbon::now()->format('Y-m-d') >= $SODebt->duedate) {
    //             $selected_customer = CustomerModel::where('id', $SODebt->customers_id)->first();
    //             $selected_customer->isOverDue = 1;
    //             $selected_customer->label = 'Bad Customer';
    //             $selected_customer->save();
    //         }
    //     }
    // }

    // if ($retailDebts) {
    //     foreach ($retailDebts as $retail) {
    //         if ($retail->due_date && Carbon::now()->format('Y-m-d') >= $retail->due_date) {
    //             // dd(Carbon::now()->format('Y-m-d') . ' - ' . $retail->due_date);
    //             if (is_numeric($retail->cust_name)) {
    //                 $selected_customer = CustomerModel::where('id', $retail->cust_name)->first();
    //             } else {
    //                 if ($retail->warehouse_id == 1) {
    //                     $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Palembang)')->first();
    //                 } elseif ($retail->warehouse_id == 8) {
    //                     $selected_customer = CustomerModel::where('name_cust', 'Direct Other Customer (Jambi)')->first();
    //                 }
    //             }

    //             if ($selected_customer != null) {
    //                 $selected_customer->isOverDue = 1;
    //                 $selected_customer->label = 'Bad Customer';
    //                 $selected_customer->save();
    //             }
    //         }
    //     }
    // }
}

function checkOverPlafone($customer_id, $current_order = 0)
{
    $SODebts = SalesOrderModel::where('customers_id', $customer_id)
        ->where('isverified', 1)
        ->where('isPaid', 0)
        ->get();
    $selected_customer = CustomerModel::where('id', $customer_id)->first();
    if ($selected_customer->name_cust == 'Direct Other Customer (Palembang)' || $selected_customer->name_cust == 'Direct Other Customer (Jambi)') {
        $retailDebts = DirectSalesModel::where('cust_name', 'NOT REGEXP', '^[0-9]+$')
            ->when($selected_customer->name_cust, function ($q) use ($selected_customer) {
                if ($selected_customer->name_cust == 'Direct Other Customer (Palembang)') {
                    return $q->where('warehouse_id', 1);
                } else {
                    return $q->where('warehouse_id', 8);
                }
            })
            ->where('isPaid', 0)
            ->where('isapproved', 1)
            ->get();
    } else {
        $retailDebts = DirectSalesModel::where('cust_name', $customer_id)->where('isPaid', 0)->where('isapproved', 1)->get();
    }

    //dd($selected_customer);
    $total_credit = 0;
    $total_return = 0;
    if ($SODebts) {
        foreach ($SODebts as $SODebt) {
            $total_credit = $total_credit + $SODebt->total_after_ppn;
            $selected_return = ReturnModel::where('sales_order_id', $SODebt->id)->sum('total');
            $total_return += $selected_return;
        }
    }


    $final_total = $total_credit - $total_return;

    $total_credit_ds = 0;
    $total_return_ds = 0;
    if ($retailDebts) {
        foreach ($retailDebts as $retail) {
            $total_credit_ds += $retail->total_incl;
            $selected_return_ds = ReturnRetailModel::where('retail_id', $retail->id)->sum('total');
            $total_return_ds += $selected_return_ds;
        }
    }


    $final_total_ds = $total_credit_ds - $total_return_ds;

    if ($final_total + $final_total_ds + $current_order > $selected_customer->credit_limit) {
        $selected_customer->isOverPlafoned = 1;
        $selected_customer->label = 'Bad Customer';
        $selected_customer->save();
        return true;
    } else {
        $selected_customer->isOverPlafoned = 0;
        $selected_customer->label = 'Customer';
        $selected_customer->save();
        return false;
    }
}

function checkOverDueByCustomer($customer_id)
{
    //update overdue
    $SODebts = SalesOrderModel::where('customers_id', $customer_id)
        ->where('isPaid', 0)
        ->where('isverified', 1)
        ->where('isrejected', 0)
        ->get();
        


    $selected_customer = CustomerModel::where('id', $customer_id)->first();

    if (
        $selected_customer->name_cust == 'Direct Other Customer (Palembang)'
        || $selected_customer->name_cust == 'Direct Other Customer (Jambi)'
    ) {
        $retailDebts = DirectSalesModel::where('cust_name', 'NOT REGEXP', '^[0-9]+$')
            ->when($selected_customer->name_cust, function ($q) use ($selected_customer) {
                if ($selected_customer->name_cust == 'Direct Other Customer (Palembang)') {
                    return $q->where('warehouse_id', 1);
                } else {
                    return $q->where('warehouse_id', 8);
                }
            })
            ->where('isPaid', 0)
            ->where('isapproved', 1)
            ->where('isrejected', 0)
            ->get();
    } else {
        $retailDebts = DirectSalesModel::where('cust_name', $customer_id)->where('isPaid', 0)->where('isapproved', 1)->where('isrejected', 0)->get();
    }
    // dd($retailDebts);

    $isoverdue_so = false;
    $isoverdue_retail = false;
    
    if ($SODebts) {
        foreach ($SODebts as $SODebt) {
            if ($SODebt->duedate != null && Carbon::now()->format('Y-m-d') >= $SODebt->duedate) {
                $isoverdue_so = true;
            }
        }
    }

    if ($retailDebts) {
        foreach ($retailDebts as $retail) {
            if ($retail->due_date != null && Carbon::now()->format('Y-m-d') >= $retail->due_date) {
                $isoverdue_retail = true;
            }
        }
    }
    // dd($retailDebts);

    // dd($isoverdue);

    if ($isoverdue_so == true || $isoverdue_retail == true) {
        $selected_customer->isOverDue = 1;
        $selected_customer->label = 'Bad Customer';
        $selected_customer->save();
        return true;
    } else {
        $selected_customer->isOverDue = 0;
        $selected_customer->label = 'Customer';
        $selected_customer->save();
        return false;
    }
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
