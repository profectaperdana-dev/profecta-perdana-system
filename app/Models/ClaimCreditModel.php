<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimCreditModel extends Model
{
    use HasFactory;

    protected $table = 'claim_credits';
    public function claim()
    {
        return $this->belongsTo(AccuClaimModel::class, 'id', 'id_claim');
    }
}
