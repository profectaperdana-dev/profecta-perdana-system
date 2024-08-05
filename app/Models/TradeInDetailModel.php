<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeInDetailModel extends Model
{
    use HasFactory;

    protected $table = 'trade_in_details';
    public function productTradeIn()
    {
        return $this->hasOne(ProductTradeInModel::class, 'id', 'product_trade_in');
    }

    public function tradeInOrderBy()
    {
        return $this->belongsTo(TradeInModel::class, 'trade_in_id', 'id');
    }
    public function priceBy()
    {
        return $this->hasMany(ProductCostSecondModel::class, 'id_product_trade_in', 'product_trade_in');
    }
}
