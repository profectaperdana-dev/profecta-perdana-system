<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\AdditionLeaveDetailsModel;

class AdditionLeaveModel extends Model
{
    use HasFactory;
    protected $table = 'leave_additions';
    protected $fillable = ['addition', 'date', 'remark'];

    public function userBy()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function detailBy()
    {
        return $this->hasMany(AdditionLeaveDetailsModel::class, 'leave_addition_id');
    }
}
