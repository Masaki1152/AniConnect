<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // fillを実行するための記述
    protected $fillable = [
        'title',
        'body',
        'image1',
        'image2',
        'image3',
        'image4',
    ];

    // 参照させたいnotificationsを指定
    protected $table = 'notifications';

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // いいねをしたUserに対するリレーション　多対多の関係
    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user', 'notification_id', 'user_id');
    }
}
