<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnDetailModel extends Model
{
    use HasFactory;

    protected $table = 'return_details';

    public function returnBy()
    {
        return $this->belongsTo(ReturnModel::class, 'return_id', 'id');
    }
}
