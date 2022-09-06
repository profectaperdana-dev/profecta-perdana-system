<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderModel extends Model
{
    use HasFactory;
    protected $table = 'purchase_orders';

    public function supplierBy()
    {
        return $this->hasOne(SuppliersModel::class, 'id', 'supplier_id')->withTrashed();
    }

    public function purchaseOrderDetailsBy()
    {
        return $this->hasMany(PurchaseOrderDetailModel::class, 'purchase_order_id');
    }

    public function createdPurchaseOrder()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id')->withTrashed();
    }
}
