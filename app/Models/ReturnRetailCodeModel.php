<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRetailCodeModel extends Model
{
    use HasFactory;
    protected $table = 'return_retail_codes';

    public function returnDetailBy()
    {
        return $this->belongsTo(ReturnRetailDetailModel::class, 'id', 'return_detail_id');
    }

    public function dotBy()
    {
        return $this->hasOne(TyreDotModel::class, 'id', 'dot');
    }
}
