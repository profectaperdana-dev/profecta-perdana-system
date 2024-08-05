<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnTradePurchaseModel extends Model
{
    use HasFactory;

    protected $table = 'return_trade_purchases';

    public function returnDetailsBy()
    {
        return $this->hasMany(ReturnTradePurchaseDetailModel::class, 'return_id');
    }

    public function created_by()
    {
        return $this->hasOne(User::class, 'id', 'createdBy')->withTrashed();
    }

    public function TradeInBy()
    {
        return $this->belongsTo(TradeInModel::class, 'trade_in_id', 'id');
    }
}
