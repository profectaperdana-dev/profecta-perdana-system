<?php

namespace App\Models;

use App\Models\Finance\Coa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ItemPromotionModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'item_promotions';

    public function stockBy()
    {
        return $this->hasMany(ItemPromotionStockModel::class, 'id_item');
    }
    
    public function categoryBy()
    {
        return $this->hasOne(Coa::class, 'coa_code', 'category_id');
    }
}
