<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // 参照させたいworksを指定
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // WorkReviewに対するリレーション 1対1の関係
    public function workreview()
    {
        return $this->belongsTo(WorkReview::class);
    }

    // いいねをしたWorkReviewに対するリレーション 多対多の関係
    public function workreviews()
    {
        return $this->belongsToMany(WorkReview::class);
    }

    // いいねをしたCharacterPostに対するリレーション 多対多の関係
    public function characterPosts()
    {
        return $this->belongsToMany(CharacterPost::class, 'character_posts_users', 'user_id', 'character_post_id');
    }
}
