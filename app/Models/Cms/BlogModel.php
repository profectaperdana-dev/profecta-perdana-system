<?php

namespace App\Models\Cms;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogModel extends Model
{
    use HasFactory;
    protected $connection = "cms_mysql";
    protected $table = "blogs";

    public function categoryBy()
    {
        return $this->hasOne(BlogCategoryModel::class, 'id', 'category_id');
    }

    public function authorBy()
    {
        return $this->hasOne(User::class, 'id', 'author')->withTrashed();
    }
}
