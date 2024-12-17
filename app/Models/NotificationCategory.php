<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationCategory extends Model
{
    use HasFactory;

    // 参照させたいnotification_categoriesを指定
    protected $table = 'notification_categories';

    // Notificationに対するリレーション 多対多の関係
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_category', 'notification_category_id', 'notification_id');
    }
}
