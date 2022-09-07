<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuppliersModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'suppliers';

    public function subMaterialBy()
    {
        return $this->hasOne(SubMaterialModel::class, 'id', 'sub_materials_id')->withTrashed();
    }
}
