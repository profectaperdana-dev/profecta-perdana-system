<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    use HasFactory;
    protected $table = 'returns';

    public function returnDetailsBy()
    {
        return $this->hasMany(ReturnDetailModel::class, 'return_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function salesOrderBy()
    {
        return $this->belongsTo(SalesOrderModel::class, 'sales_order_id', 'id');
    }
}
