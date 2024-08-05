<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VacationModel extends Model
{
    use HasFactory;
    protected $table = 'vacations';
    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
    ];

    public function userBy()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function employeeBy()
    {
        return $this->hasOne(EmployeeModel::class, 'id', 'user_id')->withTrashed();
    }
}
