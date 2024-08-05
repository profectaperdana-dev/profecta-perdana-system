<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMutationDotModel extends Model
{
    use HasFactory;

    protected $table = 'stock_mutation_codes';

    public function dotBy()
    {
        return $this->hasOne(TyreDotModel::class, 'id', 'dot');
    }

    public function mutationDetailBy()
    {
        return $this->belongsTo(StockMutationDetailModel::class, 'id', 'mutation_detail_id');
    }
}
