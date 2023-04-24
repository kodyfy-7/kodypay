<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_detail_id',
        'amount',
        'status',
        'slug'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function cardDetail() {
        return $this->belongsTo(CardDetail::class);
    }
}
