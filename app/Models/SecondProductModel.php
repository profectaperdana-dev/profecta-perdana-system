<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondProductModel extends Model
{
    use HasFactory;
    protected $table = 'second_products';

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'products_id');
    }

    public function warehouseStockBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouses_id');
    }
}
