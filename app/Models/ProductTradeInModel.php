<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTradeInModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_trade_ins';


    public function productCostSecond()
    {
        return $this->hasMany(ProductCostSecondModel::class, 'id_product_trade_in', 'id');
    }

    public function costWarehouseBy($warehouse)
    {
        $cost = ProductCostSecondModel::where('id_product_trade_in', $this->id)->where('id_warehouse', $warehouse)->first();
        return $cost;
    }
}
