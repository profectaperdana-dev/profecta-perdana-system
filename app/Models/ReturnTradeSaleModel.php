<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnTradeSaleModel extends Model
{
    use HasFactory;

    protected $table = 'return_trade_sales';
    public function returnDetailsBy()
    {
        return $this->hasMany(ReturnTradeSaleDetailModel::class, 'return_id');
    }

    public function created_by()
    {
        return $this->hasOne(User::class, 'id', 'createdBy')->withTrashed();
    }

    public function secondSaleBy()
    {
        return $this->belongsTo(SecondSaleModel::class, 'second_sale_id', 'id');
    }
}
