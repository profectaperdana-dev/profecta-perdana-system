<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountModel extends Model
{
    use HasFactory;
    protected $table = 'discounts';
    protected $guarded = ['id'];

    public function customerBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'customer_id');
    }

    public function productBy()
    {
        return $this->hasOne(SubTypeModel::class, 'id', 'product_id');
    }
}
