<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_materials';

    public function sub_materials()
    {
        return $this->hasMany(SubMaterialModel::class, 'material_id')->withTrashed();
    }
}
