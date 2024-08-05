<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPromotionTransactionModel extends Model
{
    use HasFactory;

    protected $table = 'item_promotion_transactions';

    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'id_warehouse');
    }

    public function customerBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'id_customer');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function transactionDetailBy()
    {
        return $this->hasMany(ItemPromotionTransactionDetailModel::class, 'id_transaction');
    }
}
