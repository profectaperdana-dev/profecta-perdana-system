<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSalesCodesModel extends Model
{
    use HasFactory;
    protected $table = 'direct_sales_codes';

    public function directDetailSalesBy()
    {
        return $this->belongsTo(DirectSalesDetailModel::class, 'id', 'direct_detail_id');
    }

    public function dotBy()
    {
        return $this->hasOne(TyreDotModel::class, 'id', 'dot');
    }
}
