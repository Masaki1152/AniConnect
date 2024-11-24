<?php

namespace App\Http\Controllers;

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
        return view('profile.index', [
            'user' => $user
        ]);
    }

    // 編集画面の表示
    public function edit(Request $request): View
    {
        return view('profile.edit', [
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
        // 古い画像はCloudinaryから削除する
        if ($currentImage = $request->user()->image) {
            $public_id = $this->extractPublicIdFromUrl($currentImage);
            Cloudinary::destroy($public_id);
        }

        // 画像があればCloudinaryに保存する、なければnullを代入
        $path = null;
        if ($request->hasFile('image')) {
            $path = Cloudinary::upload($request['image']->getRealPath())->getSecurePath();
            $request->user()->image = $path;
        } else {
            $request->user()->image = $path;
        }

        $request->user()->save();

        return Redirect::route('profile.index');
    }

    // パスワード更新ページの表示
    public function editPassword()
    {
        return view('profile.edit-password');
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

        return redirect()->route('profile.index');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
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
