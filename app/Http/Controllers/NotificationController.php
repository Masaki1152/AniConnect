<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationCategory;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // お知らせ一覧の表示
    public function index(Request $request, Notification $notifications, NotificationCategory $category)
    {
        // クリックされたカテゴリーidを取得
        $categoryIds = $request->filled('checkedCategories')
            ? ($request->input('checkedCategories'))
            : [];
        // 検索キーワードがあれば取得
        $search = $request->input('search', '');
        // キーワードに部分一致するお知らせを取得
        $notifications = $notifications->fetchNotifications($search, $categoryIds);
        // 検索結果の件数を取得
        $totalResults = $notifications->total();

        // カテゴリー検索で選択されたカテゴリーをまとめる
        $selectedCategories = [];
        // カテゴリーの情報を取得する
        foreach ($categoryIds as $categoryId) {
            $category = NotificationCategory::find($categoryId);
            array_push($selectedCategories, $category->name);
        }

        return view('admin.notifications.index')->with([
            'notifications' => $notifications,
            'categories' => $category->get(),
            'totalResults' => $totalResults,
            'search' => $search,
            'selectedCategories' => $selectedCategories
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

    // お知らせにいいねを行う
    public function like($notification_id)
    {
        // お知らせが見つからない場合の処理
        $notification = Notification::find($notification_id);
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }
        // 現在ログインしているユーザーが既にいいねしていればtrueを返す
        $isLiked = $notification->users()->where('user_id', Auth::id())->exists();
        if ($isLiked) {
            // 既にいいねしている場合
            $notification->users()->detach(Auth::id());
            $status = 'unliked';
            $message = 'いいねを解除しました';
        } else {
            // 初めてのいいねの場合
            $notification->users()->attach(Auth::id());
            $status = 'liked';
            $message = 'いいねしました';
        }
        // いいねしたユーザー数の取得
        $count = count($notification->users()->pluck('notification_id')->toArray());
        return response()->json(['status' => $status, 'like_user' => $count, 'message' => $message]);
    }
}
