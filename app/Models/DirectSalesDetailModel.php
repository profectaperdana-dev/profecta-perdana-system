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
        return $this->hasOne(ProductModel::class, 'id', 'product_id');
    }

    public function retailPriceBy()
    {
        return $this->hasMany(ProductCostModel::class, 'id_product', 'product_id');
    }

    public function directSalesCodeBy()
    {
        return $this->hasMany(DirectSalesCodesModel::class, 'direct_detail_id');
    }

    public function getPrice($warehouse)
    {
        $product = ProductCostModel::where('id_product', $this->product_id)->where('id_warehouse', $warehouse)->first();
        return $product->harga_jual;
    }
}
