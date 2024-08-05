<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaModel extends Model
{
    use HasFactory;
    protected $connection = "cms_mysql";
    protected $table = "areas";

    public function contactBy()
    {
        return $this->hasOne(ContactModel::class, 'area_id', 'id');
    }
}
