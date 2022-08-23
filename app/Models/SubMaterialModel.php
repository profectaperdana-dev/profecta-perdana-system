<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMaterialModel extends Model
{
    use HasFactory;
    protected $table = 'product_sub_materials';

    public function sub_types()
    {
        return $this->hasMany(SubTypeModel::class, 'sub_material_id');
    }

    public function materials()
    {
        return $this->belongsTo(MaterialModel::class, 'id', 'material_id');
    }
}
