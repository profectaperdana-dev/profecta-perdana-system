<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetCategoryModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'asset_categories';

    public function assetBy()
    {
        return $this->hasMany(AssetModel::class, 'category_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }
}
