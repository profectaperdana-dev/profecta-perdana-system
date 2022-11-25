<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCostModel extends Model
{
    use HasFactory;

    protected $table = 'product_costs';


    public function productBy()
    {
        return $this->belongsTo(ProductModel::class, 'id_product');
    }

    public function warehouseBy()
    {
        return $this->belongsTo(WarehouseModel::class, 'id_warehouse');
    }
}
