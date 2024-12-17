<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Http\Requests\NotificationRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

    //お知らせ新規投稿作成画面を表示する
    public function create()
    {
        return view('admin.notifications.create');
    }

    // 新しく記述したお知らせを保存する
    public function store(Notification $notification, NotificationRequest $request)
    {
        $input_notification = $request['notification'];
        //$input_categories = $request->work_review['categories_array'];
        //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
        //画像ファイルが送られた時だけ処理が実行される
        if ($request->file('images')) {
            $counter = 1;
            foreach ($request->file('images') as $image) {
                $image_url = Cloudinary::upload($image->getRealPath(), [
                    'transformation' => [
                        'width' => 800,
                        'height' => 600,
                        'crop' => 'pad',
                        'background' => 'white',
                    ]
                ])->getSecurePath();
                $input_notification += ["image$counter" => $image_url];
                $counter++;
            }
        }
        $notification->fill($input_notification)->save();
        // カテゴリーとの中間テーブルにデータを保存
        //$notification->categories()->attach($input_categories);
        return redirect()->route('admin.notifications.show', ['notification_id' => $notification->id])->with('status', '新しいお知らせを作成しました');
    }

    // お知らせ編集画面を表示する
    public function edit($notification_id)
    {
        $notification = Notification::find($notification_id);
        return view('admin.notifications.edit')->with(['notification' => $notification]);
    }

    // お知らせの編集を保存する
    public function update(Notification $notification, NotificationRequest $request, $notification_id)
    {
        $input_notification = $request['notification'];
        //$input_categories = $request->work_review['categories_array'];

        // 保存する画像のPathの配列
        $image_paths = [];
        // 削除されていない既存画像がある場合のみ以下の処理を実行
        if ($request['remainedImages'][0]) {
            // JSON文字列をデコードしてPHP配列に変換
            $remained_images = json_decode($request['remainedImages'][0], true);
            // Pathの配列に削除されていない画像のPathを追加
            foreach ($remained_images as $remained_image) {
                array_push($image_paths, $remained_image['url']);
            }
        }
        // 削除された既存画像がある場合のみ以下の処理を実行
        if ($request['removedImages'][0]) {
            // JSON文字列をデコードしてPHP配列に変換
            $removed_images = json_decode($request['removedImages'][0], true);
            // 削除された画像のPathをCloudinaryから削除
            foreach ($removed_images as $removed_image) {
                // Cloudinaryに登録した画像のURLからpublic_idを取得する
                $public_id = $this->extractPublicIdFromUrl($removed_image['url']);
                Cloudinary::destroy($public_id);
            }
        }
        //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入
        //画像ファイルが送られた時だけ処理が実行される
        if ($request->file('images')) {
            foreach ($request->file('images') as $image) {
                $image_path = Cloudinary::upload($image->getRealPath(), [
                    'transformation' => [
                        'width' => 800,
                        'height' => 600,
                        'crop' => 'pad',
                        'background' => 'white',
                    ]
                ])->getSecurePath();
                array_push($image_paths, $image_path);
            }
        }
        // $imagePathのうち、Pathのないものにはnullを代入
        $vacantElementNum = 4 - count($image_paths);
        for ($counter = 0; $counter < $vacantElementNum; $counter++) {
            array_push($image_paths, NULL);
        }
        $counter = 1;
        // 今回保存するPathをDBのImageカラムに代入する
        foreach ($image_paths as $imagePath) {
            $input_notification["image$counter"] = $imagePath;
            $counter++;
        }
        // 編集の対象となるデータを取得
        $target_notification = Notification::find($notification_id);
        $target_notification->fill($input_notification)->save();
        // カテゴリーとの中間テーブルにデータを保存
        // 中間テーブルへの紐づけと解除を行うsyncメソッドを使用
        //$target_notification->categories()->sync($input_categories);
        return redirect()->route('admin.notifications.show', ['notification_id' => $target_notification->id])->with('status', 'お知らせを編集しました');
    }

    // お知らせを削除する
    public function delete($notification_id)
    {
        // 削除の対象となるデータを取得
        $target_notification = Notification::find($notification_id);
        // 削除する投稿の画像も削除する処理
        for ($counter = 1; $counter < 5; $counter++) {
            $removed_image_path = $target_notification->{'image' . $counter};
            // DBのimageの中身がnullであれば処理をスキップする
            if (is_null($removed_image_path)) {
                break;
            }
            $public_id = $this->extractPublicIdFromUrl($removed_image_path);
            Cloudinary::destroy($public_id);
        }
        // データの削除
        $target_notification->delete();
        return redirect()->route('admin.notifications.index')->with('status', 'お知らせを削除しました');
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

    // Cloudinaryにある画像のURLからpublic_Idを取得する
    public function extractPublicIdFromUrl($url)
    {
        // URLの中からpublic_idを抽出するための正規表現
        $pattern = '/upload\/(?:v\d+\/)?([^\.]+)\./';

        if (preg_match($pattern, $url, $matches)) {
            // 抽出されたpublic_id
            return $matches[1];
        }
        // 該当しない場合はnull
        return null;
    }
}
