<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderModel extends Model
{
    use HasFactory;
    protected $table = 'sales_orders';

    public function customerBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'customers_id');
    }
    public function createdSalesOrder()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
