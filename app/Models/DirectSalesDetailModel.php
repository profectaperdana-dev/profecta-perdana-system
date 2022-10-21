<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSalesDetailModel extends Model
{
    use HasFactory;
    protected $table = 'direct_sales_details';

    public function directSalesBy()
    {
        return $this->belongsTo(DirectSalesModel::class, 'direct_id', 'id');
    }

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id')->withTrashed();
    }
}
