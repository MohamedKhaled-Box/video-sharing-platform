<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
    public function alerts(): HasOne
    {
        return $this->hasOne(Alert::class);
    }

    public function videoInHistory(): BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'video_user', 'user_id', 'video_id')->withTimestamps()->withPivot('id');
    }

    public function isAdmin()
    {
        return $this->administration_level > 0 ? true : false;
    }
    public function isSuperAdmin()
    {
        return $this->administration_level > 1 ? true : false;
    }
    public function views()
    {
        return $this->hasMany(View::class);
    }
}
