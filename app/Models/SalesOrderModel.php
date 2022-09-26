<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderModel extends Model
{
    use HasFactory;
    protected $table = 'sales_orders';
    // protected $hidden = ['id'];

    public function customerBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'customers_id')->withTrashed();
    }

    public function salesOrderDetailsBy()
    {
        return $this->hasMany(SalesOrderDetailModel::class, 'sales_orders_id');
    }

    public function salesOrderCreditsBy()
    {
        return $this->hasMany(SalesOrderCreditModel::class, 'sales_order_id');
    }

    public function createdSalesOrder()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function returnBy()
    {
        return $this->hasMany(ReturnModel::class, 'sales_order_id');
    }
}
