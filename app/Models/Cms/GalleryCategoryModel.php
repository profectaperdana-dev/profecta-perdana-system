<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryCategoryModel extends Model
{
    use HasFactory;
    protected $connection = "cms_mysql";
    protected $table = "gallery_categories";
}
