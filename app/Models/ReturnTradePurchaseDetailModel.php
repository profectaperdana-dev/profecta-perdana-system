<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnTradePurchaseDetailModel extends Model
{
    use HasFactory;
    protected $table = 'return_trade_purchase_details';

    public function returnBy()
    {
        return $this->belongsTo(ReturnTradePurchaseModel::class, 'return_id', 'id');
    }

    public function productBy()
    {
        return $this->hasOne(ProductTradeInModel::class, 'id', 'product_id')->withTrashed();
    }
}
