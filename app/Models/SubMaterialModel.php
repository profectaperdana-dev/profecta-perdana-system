<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubMaterialModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_sub_materials';

    public function sub_types()
    {
        return $this->hasMany(SubTypeModel::class, 'sub_material_id')->withTrashed();
    }

    public function materials()
    {
        return $this->belongsTo(MaterialModel::class, 'id', 'material_id')->withTrashed();
    }
}
