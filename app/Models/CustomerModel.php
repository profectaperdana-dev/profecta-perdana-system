<?php

namespace App\Models;

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

    public function getRouteKeyName()
    {
        return 'code_cust';
    }
}
