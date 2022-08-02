<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCategoriesModel extends Model
{
    use HasFactory;

    protected $table = "customer_categories";
    protected $guarded = ['id'];
}
