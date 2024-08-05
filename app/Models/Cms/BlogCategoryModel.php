<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategoryModel extends Model
{
    use HasFactory;
    protected $connection = "cms_mysql";
    protected $table = "blog_categories";

    public function blogBy()
    {
        return $this->belongsTo(BlogModel::class, 'id', 'category_id');
    }
}
