<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class ReturnPurchaseDetailModel extends Model
{
    use HasFactory;
    protected $table = 'return_purchase_details';

    public function returnBy()
    {
        return $this->belongsTo(ReturnPurchaseModel::class, 'return_id', 'id');
    }

    public function productBy()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id')->withTrashed();
    }
    
    public function getPrice()
    {
        $product = ProductModel::where('id', $this->product_id)->first();
        $harga_double = Crypt::decryptString($product->harga_beli);
        $harga_float = str_replace(',', '.', $harga_double);
        return (int) $harga_float;
    }
}
