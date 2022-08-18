<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $guarded = ["id"];

    public function discounts()
    {
        return $this->belongsTo(DiscountModel::class, 'customer_id', 'id');
    }

    public function getRouteKeyName()
    {
        return 'code_cust';
    }
}
