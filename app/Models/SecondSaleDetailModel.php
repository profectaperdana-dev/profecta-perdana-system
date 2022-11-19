<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondSaleDetailModel extends Model
{
    use HasFactory;

    protected $table = 'second_sale_details';

    public function secondProduct()
    {
        return $this->hasOne(ProductTradeInModel::class, 'id', 'product_second_id');
    }
    public function second_sale()
    {
        return $this->belongsTo(SecondSaleModel::class, 'second_sale_id');
    }
}
