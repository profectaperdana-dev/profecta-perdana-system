<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TyreDotModel extends Model
{
    use HasFactory;

    protected $table = 'tyre_dots';

    //relation to product
    public function tyreBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'id_product');
    }

    // relation to warehouse
    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'id_warehouse');
    }
    public function stockModel()
    {
        return $this->hasOne(StockModel::class, 'products_id', 'id_product')->where('warehouses_id', $this->id_warehouse);
    }
}
