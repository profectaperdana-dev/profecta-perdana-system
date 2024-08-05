<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItemPromotionModel extends Model
{
    use HasFactory;

    protected $table = 'return_item_promotions';

    public function returnDetailsBy()
    {
        return $this->hasMany(ReturnItemPromotionDetailModel::class, 'return_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function transactionBy()
    {
        return $this->belongsTo(ItemPromotionTransactionModel::class, 'id_transaction', 'id');
    }
}
