<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryHistoriesModel extends Model
{
    use HasFactory;
    protected $table = "delivery_histories";

    public function salesOrderBy()
    {
        return $this->belongsTo(SalesOrderModel::class, 'id', 'order_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }
}
