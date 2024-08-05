<?php

namespace App\Models\Cms;

use App\Models\ProductModel as ModelsProductModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductModel extends Model
{
    use HasFactory;
    protected $connection = 'cms_mysql';
    protected $table = 'products';

    public function productBy()
    {
        return $this->belongsTo(ModelsProductModel::class, 'product_id', 'id');
    }
}
