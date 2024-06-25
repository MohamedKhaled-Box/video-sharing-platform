<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user(): BelongsTo
    {
        return $this->belongsTo(user::class);
    }
    public function convertedvideos(): HasMany
    {
        return $this->hasMany(Convertedvideo::class);
    }
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function views(): HasMany
    {
        return $this->hasMany(View::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'video_user', 'video_id', 'user_id')->withTimestamps()->withPivot('id');
    }
}
