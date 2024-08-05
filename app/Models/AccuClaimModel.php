<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccuClaimModel extends Model
{
    use HasFactory;
    protected $table = 'accu_claims';
    public function customerBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'customer_id')->withTrashed();
    }

    public function productSales()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id')->withTrashed();
    }
    public function loanBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'loan_product_id')->withTrashed();
    }
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'e_submittedBy')->withTrashed();
    }
    public function carBrandBy()
    {
        return $this->hasOne(CarBrandModel::class, 'id', 'car_brand_id');
    }
    public function carTypeBy()
    {
        return $this->hasOne(CarTypeModel::class, 'id', 'car_type_id');
    }
    public function accuClaimDetailsBy()
    {
        return $this->hasMany(AccuClaimDetailModel::class, 'id_accu_claim');
    }

    // motor
    public function motorBrandBy()
    {
        return $this->hasOne(MotorBrandModel::class, 'id', 'motor_brand_id');
    }
    public function motorTypeBy()
    {
        return $this->hasOne(MotorTypeModel::class, 'id', 'motor_type_id');
    }

    public function claimCredit()
    {
        return $this->hasMany(ClaimCreditModel::class, 'id_claim');
    }
}
