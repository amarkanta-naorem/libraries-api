<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = ['phone','code','expires_at','used'];

    public function user()
    {
        return $this->belongsTo(User::class, 'phone', 'phone');
    }
}
