<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSalesCreditModel extends Model
{
    use HasFactory;
    protected $table = 'direct_sales_credits';

    public function directSalesBy()
    {
        return $this->belongsTo(DirectSalesModel::class, 'direct_id', 'id');
    }
    
    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'update_by')->withTrashed();
    }
}
