<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCostSecondModel extends Model
{
    use HasFactory;

    protected $table = 'product_cost_second';

    public function productTradeIn()
    {
        return $this->belongsTo(ProductTradeInModel::class, 'id_product_trade_in')->withTrashed();
    }
    public function warehouseBy()
    {
        return $this->belongsTo(WarehouseModel::class, 'id_warehouse')->withTrashed();
    }
}
