<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizationModel extends Model
{
    use HasFactory;
    protected $table = 'authorizations';

    public function userAuthBy()
    {
        return $this->hasMany(UserAuthorizationModel::class, 'auth_id');
    }
}
