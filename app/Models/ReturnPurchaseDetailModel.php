<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnPurchaseDetailModel extends Model
{
    use HasFactory;
    protected $table = 'return_purchase_details';

    public function returnBy()
    {
        return $this->belongsTo(ReturnPurchaseModel::class, 'return_id', 'id');
    }

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id')->withTrashed();
    }
}
