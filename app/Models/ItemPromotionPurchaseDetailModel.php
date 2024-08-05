<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPromotionPurchaseDetailModel extends Model
{
    use HasFactory;

    protected $table = 'item_promotion_purchase_details';

    public function purchaseBy()
    {
        return $this->belongsTo(ItemPromotionPurchaseModel::class, 'purchase_id', 'id');
    }

    public function itemBy()
    {
        return $this->hasOne(ItemPromotionModel::class, 'id', 'item_id');
    }
}
