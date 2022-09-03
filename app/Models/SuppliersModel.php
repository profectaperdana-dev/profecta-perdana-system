<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliersModel extends Model
{
    use HasFactory;
    protected $table = 'suppliers';

    public function subMaterialBy()
    {
        return $this->hasOne(SubMaterialModel::class, 'id', 'sub_materials_id');
    }
}
