<?php

namespace App\Models;

use App\Http\Controllers\ItemPromotionController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItemPromotionDetailModel extends Model
{
    use HasFactory;

    protected $table = 'return_item_promotion_details';

    public function returnBy()
    {
        return $this->belongsTo(ReturnItemPromotionModel::class, 'return_id', 'id');
    }

    public function itemBy()
    {
        return $this->hasOne(ItemPromotionModel::class, 'id', 'id_item');
    }
}
