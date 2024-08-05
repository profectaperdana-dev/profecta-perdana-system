<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRetailDetailModel extends Model
{
    use HasFactory;
    protected $table = 'return_retail_details';

    public function returnBy()
    {
        return $this->belongsTo(ReturnRetailModel::class, 'return_id', 'id');
    }

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id')->withTrashed();
    }

    public function returnDirectCodeBy()
    {
        return $this->hasMany(ReturnRetailCodeModel::class, 'return_detail_id');
    }

    public function productPrice($warehouse)
    {
        $getPrice = ProductCostModel::where('id_product', $this->product_id)->where('id_warehouse', $warehouse)->first();
        return $getPrice->harga_jual;
    }
    
    public function getDetail($invoice)
    {
        $getPrice = DirectSalesDetailModel::where('product_id', $this->product_id)->where('direct_id', $invoice)->first();
        return $getPrice;
    }
}
