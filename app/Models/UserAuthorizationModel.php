<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthorizationModel extends Model
{
    use HasFactory;
    protected $table = 'user_authorizations';

    public function authBy()
    {
        return $this->belongsTo(AuthorizationModel::class, 'auth_id', 'id');
    }

    public function userBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
