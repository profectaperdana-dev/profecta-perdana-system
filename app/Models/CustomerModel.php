<?php

namespace App\Models;

use App\Http\Controllers\CustomerAreasController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'customers';
    protected $guarded = ["id"];

    public function discounts()
    {
        return $this->belongsTo(DiscountModel::class, 'customer_id', 'id')->withTrashed();
    }

    public function haveDiscounts()
    {
        return $this->hasMany(DiscountModel::class, 'customer_id');
    }

    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id_area', 'area_cust_id')->withTrashed();
    }

    public function getDiscount($product_id)
    {
        $disc = DiscountModel::where('customer_id', $this->id)->where('product_id', $product_id)->first();
        if ($disc != null) {
            return str_replace(".", ",", $disc->discount);
        } else return 0;
    }

    // public function getRouteKeyName()
    // {
    //     return 'code_cust';
    // }

    public function areaBy()
    {
        return $this->hasOne(CustomerAreaModel::class, 'id', 'area_cust_id')->withTrashed();
    }

    public function categoryBy()
    {
        return $this->hasOne(CustomerCategoriesModel::class, 'id', 'category_cust_id')->withTrashed();
    }
    
     public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }
}
