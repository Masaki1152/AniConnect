<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // 参照させたいnotificationsを指定
    protected $table = 'notifications';

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
