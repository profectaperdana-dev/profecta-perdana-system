<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPurchaseModel extends Model
{
    use HasFactory;
    protected $table = 'return_purchases';

    public function returnDetailsBy()
    {
        return $this->hasMany(ReturnPurchaseDetailModel::class, 'return_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function purchaseOrderBy()
    {
        return $this->belongsTo(PurchaseOrderModel::class, 'purchase_order_id', 'id');
    }
}
