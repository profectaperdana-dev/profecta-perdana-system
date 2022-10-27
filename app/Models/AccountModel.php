<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'accounts';

    public function accountSubs()
    {
        return $this->hasMany(AccountSubModel::class, 'account_id', 'id')->withTrashed();
    }
}
