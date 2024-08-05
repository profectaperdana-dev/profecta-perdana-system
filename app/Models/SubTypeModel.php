<?php

namespace App\Models;

use App\Models\Cms\ProductModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubTypeModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $connection = 'mysql';
    
    protected $table = 'product_sub_types';
    protected $guarded = ['id'];

    public function sub_materials()
    {
        return $this->belongsTo(SubMaterialModel::class, 'sub_material_id', 'id')->withTrashed();
    }
    
    public function cmsProductBy()
    {
        return $this->belongsTo(ProductModel::class, 'id', 'product_id');
    }
}
