<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimModel extends Model
{
    use HasFactory;
    protected $table = 'claims';
    public function customerBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'customer_id')->withTrashed();
    }
    public function productSales()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id')->withTrashed();
    }
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'e_submittedBy')->withTrashed();
    }
}
