<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutModel extends Model
{
    use HasFactory;
    protected $connection = 'cms_mysql';
    protected $table = 'abouts';

    public function journeyBy()
    {
        return $this->hasMany(AboutJourneyModel::class, 'journeys_id', 'id');
    }

    public function provideBy()
    {
        return $this->hasMany(AboutProvideModel::class, 'provides_id', 'id');
    }
}
