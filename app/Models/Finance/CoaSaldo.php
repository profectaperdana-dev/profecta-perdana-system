<?php

namespace App\Models\Finance;

use App\Models\WarehouseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoaSaldo extends Model
{
    use HasFactory;
    protected $table = "coa_saldo";

    public function coa()
    {
        return $this->belongsTo(Coa::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(WarehouseModel::class);
    }
}
