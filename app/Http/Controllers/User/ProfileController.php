<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProfileController extends Controller
{
    use SoftDeletes;

    public function index()
    {
        // 現在認証しているユーザーを取得
        $user = auth()->user();
        return view('user_interactions.users.show', [
            'user' => $user
        ]);
    }

    // 編集画面の表示
    public function edit(Request $request): View
    {
        return view('user_interactions.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // プロフィール情報の更新
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }
        // プロフィール画像の編集
        // 元々ファイルがあり、さらにそのファイルを変更する場合
        if ($request->user()->image && $request->hasFile('image')) {
            // 既存のファイルパスの削除
            $currentImage = $request->user()->image;
            $public_id = $this->extractPublicIdFromUrl($currentImage);
            Cloudinary::destroy($public_id);
            // 新しいファイルの追加
            $path = Cloudinary::upload($request['image']->getRealPath())->getSecurePath();
            $request->user()->image = $path;
        } elseif ($request->hasFile('image')) {
            // 元々ファイルがなく、ファイルの変更がある場合
            // 新しいファイルの追加
            $path = Cloudinary::upload($request['image']->getRealPath())->getSecurePath();
            $request->user()->image = $path;
        } elseif ($request->user()->image && $request['existingImage'] == null) {
            // 元々ファイルがあり、画像が削除された場合
            // 既存のファイルパスの削除
            $currentImage = $request->user()->image;
            $public_id = $this->extractPublicIdFromUrl($currentImage);
            Cloudinary::destroy($public_id);
            $request->user()->image = null;
            // 元々ファイルがないor元々ファイルがある場合で、ファイルの変更がない場合は何もしない
        }

        $request->user()->save();
        $message = __('profile.updated');

        return Redirect::route('profile.index')->with('status', $message);
    }

    // パスワード更新ページの表示
    public function editPassword()
    {
        return view('user_interactions.profile.edit-password');
    }

    // パスワード更新の処理
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'confirmed',
                // 大文字必須、半角英数字のみ
                'regex:/^(?=.*[A-Z])[a-zA-Z0-9]+$/',
                Rules\Password::defaults()
            ],
        ], [
            'password.regex' => 'パスワードには少なくとも1つの大文字を含む半角英数字を使用してください。',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);
        $message = __('password_updated');

        return redirect()->route('profile.index')->with('status', $message);
    }

    // アカウント削除確認ページの表示
    public function confirmDelete()
    {
        return view('user_interactions.profile.delete-account');
    }

    // アカウント削除の処理
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $message = __('account_deleted');

        return Redirect::to('/register')->with('status', $message);
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
