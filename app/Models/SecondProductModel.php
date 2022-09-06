<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SecondProductModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'second_products';

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'products_id')->withTrashed();
    }

    public function warehouseStockBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouses_id')->withTrashed();
    }
}
