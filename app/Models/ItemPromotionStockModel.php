<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPromotionStockModel extends Model
{
    use HasFactory;

    protected $table = 'item_promotion_stocks';

    public function itemBy()
    {
        return $this->belongsTo(ItemPromotionModel::class, 'id_item', 'id');
    }

    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'id_warehouse');
    }
}
