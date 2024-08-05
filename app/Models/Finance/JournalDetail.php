<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    use HasFactory;

    protected $table = 'journal_detail';
    protected $fillable = ['journal_id', 'coa_code', 'debit', 'credit'];
    public function jurnal()
    {
        return $this->belongsTo(Journal::class, 'journal_id', 'id');
    }

    public function coa()
    {
        return $this->hasOne(Coa::class, 'coa_code', 'coa_code');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
