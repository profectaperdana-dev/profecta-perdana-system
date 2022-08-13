<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockModel extends Model
{
    use HasFactory;
    protected $table = 'stocks';

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'products_id');
    }

    public function warehouseStockBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouses_id');
    }
}
