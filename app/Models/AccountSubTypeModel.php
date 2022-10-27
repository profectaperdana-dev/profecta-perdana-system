<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountSubTypeModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'account_sub_types';

    public function accountSub()
    {
        return $this->belongsTo(AccountSubModel::class, 'account_sub_id', 'id')->withTrashed();
    }
}
