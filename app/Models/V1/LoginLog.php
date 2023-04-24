<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'email' ,
        'ip_address',
        'user_agent',
        'is_success',
        'x_forwarded_for'
    ];
}
