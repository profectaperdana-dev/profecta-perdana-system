<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'discounts';
    protected $guarded = ['id'];

    public function customerBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'customer_id')->withTrashed();
    }

    public function productBy()
    {
        return $this->hasOne(SubTypeModel::class, 'id', 'product_id')->withTrashed();
    }
}
