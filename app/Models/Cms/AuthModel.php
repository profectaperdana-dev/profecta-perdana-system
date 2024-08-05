<?php

namespace App\Models\Cms;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthModel extends Model
{
    use HasFactory;
    protected $connection = "cms_mysql";
    protected $table = "live_chat_keys";

    public function userBy()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
