<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderCreditModel extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_credits';

    public function purchaseorders()
    {
        return $this->belongsTo(PurchaseOrderModel::class, 'purchase_order_id', 'id');
    }
}
