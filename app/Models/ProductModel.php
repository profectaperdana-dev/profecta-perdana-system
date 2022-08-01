<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;
    protected $table = "products";

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
