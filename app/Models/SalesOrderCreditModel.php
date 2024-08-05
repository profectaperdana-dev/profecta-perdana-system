<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderCreditModel extends Model
{
    use HasFactory;
    protected $table = 'sales_order_credits';

    public function salesorders()
    {
        return $this->belongsTo(SalesOrderModel::class, 'sales_order_id', 'id');
    }
    
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'updated_by')->withTrashed();
    }
}
