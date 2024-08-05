<?php

namespace App\Models\Finance;

use App\Models\WarehouseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'journal';


    public function jurnal_detail()
    {
        return $this->hasMany(JournalDetail::class, 'journal_id', 'id');
    }

    public function warehouse()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id');
    }
}
