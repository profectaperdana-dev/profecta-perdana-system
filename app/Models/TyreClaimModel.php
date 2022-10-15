<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TyreClaimModel extends Model
{
    use HasFactory;

    protected $table = 'tyre_claims';
    public function customerBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'customer_id')->withTrashed();
    }

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }
    public function carBrandBy()
    {
        return $this->hasOne(CarBrandModel::class, 'id', 'car_brand_id');
    }
    public function carTypeBy()
    {
        return $this->hasOne(CarTypeModel::class, 'id', 'car_type_id');
    }
}
