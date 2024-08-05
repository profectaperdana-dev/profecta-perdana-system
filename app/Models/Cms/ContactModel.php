<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    use HasFactory;
    protected $connection = "cms_mysql";
    protected $table = "contacts";

    public function areaBy()
    {
        return $this->hasOne(AreaModel::class, 'id', 'area_id');
    }
}
