<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'profile_image', 'contact', 'city', 'address'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
