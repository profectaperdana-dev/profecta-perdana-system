<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTypeModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_sub_types';
    protected $guarded = ['id'];

    public function sub_materials()
    {
        return $this->belongsTo(SubMaterialModel::class, 'id', 'sub_material_id')->withTrashed();
    }
}
