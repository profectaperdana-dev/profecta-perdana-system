<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPromotionMutationModel extends Model
{
    use HasFactory;

    protected $table = 'item_promotion_mutations';

    public function stockMutationDetailBy()
    {
        return $this->hasMany(ItemPromotionMutationDetailModel::class, 'mutation_id');
    }

    public function fromWarehouse()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'from')->withTrashed();
    }

    public function toWarehouse()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'to')->withTrashed();
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function approvedBy()
    {
        return $this->hasOne(User::class, 'id', 'approved_by')->withTrashed();
    }
}
