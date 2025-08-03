<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class follow extends Model
{
    /** @use HasFactory<\Database\Factories\FollowFactory> */
    use HasFactory;

    protected $fillable = [
        "follower_id",
        "following_id",
    ];

    public function followers(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function following(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
