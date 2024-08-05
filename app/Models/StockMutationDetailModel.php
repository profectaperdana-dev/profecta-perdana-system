<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMutationDetailModel extends Model
{
    use HasFactory;

    protected $table = 'stock_mutation_details';

    public function stockMutationBy()
    {
        return $this->belongsTo(StockMutationModel::class, 'mutation_id', 'id');
    }

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id')->withTrashed();
    }

    public function productSecondBy()
    {
        return $this->hasOne(ProductTradeInModel::class, 'id', 'product_id')->withTrashed();
    }

    public function mutationDotBy()
    {
        return $this->hasMany(StockMutationDotModel::class, 'mutation_detail_id');
    }

    public function getDot($warehouse)
    {
        $get_dot = TyreDotModel::where('id_product', $this->product_id)->where('id_warehouse', $warehouse)->oldest('dot')->get();
        return $get_dot;
    }
}
