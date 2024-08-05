<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryModel extends Model
{
    use HasFactory;
    protected $connection = "cms_mysql";
    protected $table = "galleries";

    public function categoryBy()
    {
        return $this->hasOne(GalleryCategoryModel::class, 'id', 'category_id');
    }
}
