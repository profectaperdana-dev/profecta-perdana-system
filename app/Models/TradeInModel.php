<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeInModel extends Model
{
    use HasFactory;
    protected $table = 'trade_ins';

    public function tradeBy()
    {
        return $this->hasOne(User::class, 'id', 'createdBy')->withTrashed();
    }
    public function tradeInDetailBy()
    {
        return $this->hasMany(TradeInDetailModel::class, 'trade_in_id');
    }
}
