<?php

namespace App\Models\finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoaCategories extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'coa_categories';

    public function coas()
    {
        return $this->hasMany(Coa::class, 'coa_category_id', 'id')->withTrashed();
    }
}
