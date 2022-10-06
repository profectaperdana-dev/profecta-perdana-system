<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccuClaimDetailModel extends Model
{
    use HasFactory;
    protected $table = 'accu_claim_details';
    public function accuClaimBy()
    {
        return $this->belongsTo(AccuClaimModel::class, 'id', 'id_accu_claim');
    }
}
