<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSalesModel extends Model
{
    use HasFactory;
    protected $table = 'direct_sales';

    public function directSalesDetailBy()
    {
        return $this->hasMany(DirectSalesDetailModel::class, 'direct_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }
    

    public function carBrandBy()
    {
        return $this->hasOne(CarBrandModel::class, 'id', 'car_brand_id')->withTrashed();
    }

    public function carTypeBy()
    {
        return $this->hasOne(CarTypeModel::class, 'id', 'car_type_id')->withTrashed();
    }

    public function motorBrandBy()
    {
        return $this->hasOne(MotorBrandModel::class, 'id', 'motor_brand_id')->withTrashed();
    }

    public function motorTypeBy()
    {
        return $this->hasOne(MotorTypeModel::class, 'id', 'motor_type_id')->withTrashed();
    }

    public function customerBy()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'cust_name')->withTrashed();
    }
    
    public function customerNumericby()
    {
        $name = CustomerModel::where('id', $this->cust_name)->first()->name_cust;
        return $name;
    }
    
    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id')->withTrashed();
    }

    public function directSalesCreditBy()
    {
        return $this->hasMany(DirectSalesCreditModel::class, 'direct_id');
    }

    public function directSalesReturnBy()
    {
        return $this->hasMany(ReturnRetailModel::class, 'retail_id');
    }

    public function tradeBy()
    {
        return $this->belongsTo(TradeInModel::class, 'order_number', 'retail_order_number');
    }
    
    public function manyTrade()
    {
        return $this->hasMany(TradeInModel::class, 'retail_order_number', 'order_number');
    }
    
    public function getCustomerOther()
    {
        switch ($this->warehouse_id) {
            case 1:
                return CustomerModel::where('name_cust', 'Direct Other Customer (Palembang)')->first();
                break;
            case 8:
                return CustomerModel::where('name_cust', 'Direct Other Customer (Jambi)')->first();
            default:
                return null;
                break;
        }
    }
}
