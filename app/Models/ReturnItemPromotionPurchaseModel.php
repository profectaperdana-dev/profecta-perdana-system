<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItemPromotionPurchaseModel extends Model
{
    use HasFactory;
    protected $table = 'return_purchase_item_promotions';

    public function returnDetailsBy()
    {
        return $this->hasMany(ReturnItemPromotionPurchaseDetailModel::class, 'return_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function purchaseBy()
    {
        return $this->belongsTo(ItemPromotionPurchaseModel::class, 'purchase_id', 'id');
    }
}
