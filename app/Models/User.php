<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username', // ✅ Tambahkan ini
        'password',
        'place_birth',
        'date_birth',
        'address',
        'phone_number',
        'school',
        'major',
        'internship_start',
        'internship_end',
        'photo',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_birth' => 'date',
        'internship_start' => 'date',
        'internship_end' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->username)) {
                $base = Str::slug($user->name);
                $username = $base;
                $counter = 1;

                // Pastikan username unik
                while (User::where('username', $username)->exists()) {
                    $username = $base . $counter;
                    $counter++;
                }

                $user->username = $username;
            }
        });
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'user_id');
    }

    public function commentMentions()
    {
        return $this->hasMany(CommentMention::class, 'mentioned_user_id');
    }

}
