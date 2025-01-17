<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'assets';

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function categoryBy()
    {
        return $this->belongsTo(AssetCategoryModel::class, 'category_id', 'id');
    }
}
