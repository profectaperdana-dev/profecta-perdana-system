<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRetailModel extends Model
{
    use HasFactory;
    protected $table = 'return_retails';

    public function returnDetailsBy()
    {
        return $this->hasMany(ReturnRetailDetailModel::class, 'return_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function retailBy()
    {
        return $this->belongsTo(DirectSalesModel::class, 'retail_id', 'id');
    }
    
    public function retailCodeBy($product_id)
    {
        $getDetailRetail = DirectSalesDetailModel::where('direct_id', $this->retail_id)->where('product_id', $product_id)->first();
        return DirectSalesCodesModel::where('direct_detail_id', $getDetailRetail->id)->get();
    }
}
