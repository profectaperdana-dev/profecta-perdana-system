<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountSubModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'account_subs';

    public function account()
    {
        return $this->belongsTo(AccountModel::class, 'account_id', 'id')->withTrashed();
    }

    public function accountSubTypes()
    {
        return $this->hasMany(AccountSubTypeModel::class, 'account_sub_id', 'id')->withTrashed();
    }
}
