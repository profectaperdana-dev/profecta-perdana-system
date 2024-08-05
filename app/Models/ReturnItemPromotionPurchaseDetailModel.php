<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItemPromotionPurchaseDetailModel extends Model
{
    use HasFactory;

    protected $table = 'return_purchase_item_promotion_details';

    public function returnBy()
    {
        return $this->belongsTo(ReturnItemPromotionPurchaseModel::class, 'return_id', 'id');
    }

    public function itemBy()
    {
        return $this->hasOne(ItemPromotionModel::class, 'id', 'item_id');
    }
}
