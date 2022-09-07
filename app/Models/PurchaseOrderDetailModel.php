<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetailModel extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_details';

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id')->withTrashed();
    }

    public function purchaseOrderBy()
    {
        return $this->belongsTo(PurchaseOrderModel::class, 'id', 'purchase_order_id');
    }
}
