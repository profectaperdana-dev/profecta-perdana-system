<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderCodeModel extends Model
{
    use HasFactory;
    protected $table = 'purchase_order_codes';


    public function purchaseDetailBy()
    {
        return $this->belongsTo(PurchaseOrderDetailModel::class, 'id', 'purchase_detail_id');
    }

    public function dotBy()
    {
        return $this->hasOne(TyreDotModel::class, 'id', 'dot');
    }
}
