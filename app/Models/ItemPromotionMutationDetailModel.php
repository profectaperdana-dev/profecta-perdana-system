<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPromotionMutationDetailModel extends Model
{
    use HasFactory;

    protected $table = 'item_promotion_mutation_details';

    public function stockMutationBy()
    {
        return $this->belongsTo(ItemPromotionMutationModel::class, 'mutation_id', 'id');
    }

    public function itemBy()
    {
        return $this->hasOne(ItemPromotionModel::class, 'id', 'item_id');
    }
}
