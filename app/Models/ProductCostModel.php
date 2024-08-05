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


    public function productBy()
    {
        return $this->belongsTo(ProductModel::class, 'id_product');
    }

    public function warehouseBy()
    {
        return $this->belongsTo(WarehouseModel::class, 'id_warehouse')->withTrashed();
    }

    public function showRetail($ppn)
    {
        $harga = str_replace(',', '.', $this->harga_jual);
        $ppns = (float) $harga * $ppn;
        return (float) $harga + $ppns;
    }

    public function showStockByWarehouse($warehouse)
    {
        $product = StockModel::where('products_id', $this->id_product)->where('warehouses_id', $warehouse)->first();
        return $product->stock;
    }
}
