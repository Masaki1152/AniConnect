<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationController extends Controller
{
    use SoftDeletes;

    // お知らせ一覧の表示
    public function index(Request $request, Notification $notification)
    {
        return view('admin.notifications.index')->with([
            'notifications' => $notification->get(),
        ]);
    }

    // お知らせ詳細の表示
    public function show($notification_id)
    {
        $notification = Notification::find($notification_id);
        return view('admin.notifications.show')->with([
            'notification' => $notification
        ]);
    }
}
