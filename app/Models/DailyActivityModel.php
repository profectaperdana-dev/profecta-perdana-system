<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyActivityModel extends Model
{
    use HasFactory;

    protected $table = 'daily_activities';

    public function userBy()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }
}
