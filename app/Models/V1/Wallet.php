<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
        'slug'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function walletTransactions() {
        return $this->hasMany(WalletTransaction::class);
    }
}
