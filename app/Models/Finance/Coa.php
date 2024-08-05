<?php

namespace App\Models\finance;

use App\Models\Finance\CoaSaldo;
use App\Models\Finance\Journal;
use App\Models\Finance\JournalDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coa extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'coas';


    public function categories()
    {
        return $this->hasOne(CoaCategories::class, 'id', 'coa_category_id')->withTrashed();
    }

    public function journals()
    {
        return $this->hasMany(JournalDetail::class, 'coa_code', 'coa_code');
    }

    public function saldos()
    {
        return $this->hasMany(CoaSaldo::class, 'coa_id');
    }
}
