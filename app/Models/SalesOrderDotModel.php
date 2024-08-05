<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDotModel extends Model
{
    use HasFactory;

    protected $table = 'sales_order_dots';

    public function salesOrderDetailBy()
    {
        return $this->belongsTo(SalesOrderDetailModel::class, 'id', 'sales_order_detail_id');
    }
}
