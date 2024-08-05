<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPromotionPurchaseModel extends Model
{
    use HasFactory;

    protected $table = 'item_promotion_purchases';

    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id')->withTrashed();
    }

    public function supplierBy()
    {
        return $this->hasOne(ItemPromotionSupplierModel::class, 'id', 'supplier_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function purchaseDetailBy()
    {
        return $this->hasMany(ItemPromotionPurchaseDetailModel::class, 'purchase_id');
    }
}
