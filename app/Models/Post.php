<?php

namespace App\Models;

use App\Models\User;
use Spatie\MediaLibrary\HasMedia;
use App\Http\Filters\V1\QueryFilter;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        "title",
        "description",
        "user_id",
        "media_url"
    ];

    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class);
    }

    public function likes(): HasMany {
        return $this->hasMany(Like::class);
    }

    public function scopeFilter(Builder $builder, QueryFilter $filters) {
        return $filters->apply($builder);
    }
}
