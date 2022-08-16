<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetailModel extends Model
{
    use HasFactory;
    protected $table = 'sales_order_details';
    protected $hidden = ['created_at', 'updated_at'];

    public function productSales()
    {
        return $this->hasOne(ProductModel::class, 'id', 'products_id');
    }
    public function soBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'customers_id');
    }
}
