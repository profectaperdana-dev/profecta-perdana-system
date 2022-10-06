<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "products";
    protected $guarded = ['id'];

    public function sub_types()
    {
        return $this->hasOne(SubTypeModel::class, 'id', 'id_sub_type')->withTrashed();
    }
    public function materials()
    {
        return $this->hasOne(MaterialModel::class, 'id', 'id_material')->withTrashed();
    }

    public function sub_materials()
    {
        return $this->hasOne(SubMaterialModel::class, 'id', 'id_sub_material')->withTrashed();
    }

    public function uoms()
    {
        return $this->hasOne(UomModel::class, 'id', 'id_uom')->withTrashed();
    }

    public function getRouteKeyName()
    {
        return 'kode_barang';
    }
}
