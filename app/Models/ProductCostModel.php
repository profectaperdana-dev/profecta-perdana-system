<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductCostModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_costs';


    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'id_product')->withTrashed();
    }

    public function warehouse()
    {
        return $this->belongsTo(WarehouseModel::class, 'id_warehouse')->withTrashed();
    }
}
