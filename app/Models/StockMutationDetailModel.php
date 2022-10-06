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
}
