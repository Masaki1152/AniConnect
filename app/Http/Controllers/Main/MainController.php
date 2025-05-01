<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;


class MainController extends Controller
{
    // メイン画面の表示
    public function index(Notification $notification)
    {
        // 最新のお知らせ５件の取得
        $notifications = $notification->getRecentNotifications();
        return view('main.index', compact('notifications'));
    }
}
