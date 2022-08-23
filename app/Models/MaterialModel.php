<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialModel extends Model
{
    use HasFactory;
    protected $table = 'product_materials';

    public function sub_materials()
    {
        return $this->hasMany(SubMaterialModel::class, 'material_id');
    }
}
