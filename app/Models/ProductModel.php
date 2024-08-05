<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

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

    public function stockBy()
    {
        return $this->hasOne(StockModel::class, 'products_id', 'id')->withTrashed();
    }

    public function productCosts()
    {
        return $this->hasMany(ProductCostModel::class, 'id_product', 'id');
    }
    public function retailPriceBy()
    {
        return $this->hasMany(ProductCostModel::class, 'id_product');
    }

    public function getRouteKeyName()
    {
        return 'kode_barang';
    }

    public function decryptPrice()
    {
        if ($this->harga_beli != null) {
            return Crypt::decryptString($this->harga_beli);
        } else return 0;
    }
    
    public function dotSeparator($harga)
    {
        $harga_s = (float) $harga;
        $explode = explode(',', $harga_s);
        if (sizeof($explode) > 1) {
            $dotSeparator = number_format(intval($explode[0]), 0, ',', '.');
            $separated = $dotSeparator . ',' . $explode[1];
            return $separated;
        } else {
            return number_format($harga_s, 0, ',', '.');
        }
    }
}
