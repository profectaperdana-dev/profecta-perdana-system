<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeInModel extends Model
{
    use HasFactory;
    protected $table = 'trade_ins';

    public function tradeBy()
    {
        return $this->hasOne(User::class, 'id', 'createdBy')->withTrashed();
    }
    public function tradeInDetailBy()
    {
        return $this->hasMany(TradeInDetailModel::class, 'trade_in_id');
    }
    // warehouse
    public function warehouse()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id');
    }
    public function retailBy()
    {
        return $this->hasOne(DirectSalesModel::class, 'order_number', 'retail_order_number');
    }
     public function returnBy()
    {
        return $this->hasOne(ReturnTradePurchaseModel::class, 'trade_in_id', 'id');
    }
}
