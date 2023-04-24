<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'card_id',
        'code',
        'slug',
        'expired_at'
    ];

    public function card() {
        return $this->belongsTo(Card::class);
    }
    
    public function cardTransactions() {
        return $this->hasMany(CardTransaction::class);
    }
}
