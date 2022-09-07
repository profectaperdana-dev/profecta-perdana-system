<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'stocks';

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'products_id')->withTrashed();
    }

    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouses_id')->withTrashed();
    }
}
