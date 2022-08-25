<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $guarded = ['id'];

    public function sub_types()
    {
        return $this->hasOne(SubTypeModel::class, 'id', 'id_sub_type');
    }

    public function sub_materials()
    {
        return $this->hasOne(SubMaterialModel::class, 'id', 'id_sub_material');
    }

    public function getRouteKeyName()
    {
        return 'kode_barang';
    }
}
