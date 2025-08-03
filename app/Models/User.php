<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Post;
use App\Models\Follow;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Filters\V1\QueryFilter;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function posts(): HasMany {
        return $this->hasMany(Post::class);
    }

        // Users that this user follows
    // public function followings(): BelongsToMany
    // {
    //     return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
    // }

    // // Users that follow this user
    // public function followers(): BelongsToMany
    // {
    //     return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    // }


    public function follower(): HasMany {
        return $this->hasMany(Follow::class);
    }

    public function following(): HasMany {
        return $this->hasMany(Follow::class);
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany {
        return $this->hasMany(Like::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters)
    {
        return $filters->apply($builder);
    }
}
