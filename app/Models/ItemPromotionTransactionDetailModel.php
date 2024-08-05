<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPromotionTransactionDetailModel extends Model
{
    use HasFactory;

    protected $table = 'item_promotion_transaction_details';

    public function transactionBy()
    {
        return $this->belongsTo(ItemPromotionTransactionModel::class, 'id_transaction', 'id');
    }

    public function itemBy()
    {
        return $this->hasOne(ItemPromotionModel::class, 'id', 'id_item');
    }
}
