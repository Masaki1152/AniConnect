<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:15'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'age' => ['required'],
            'sex' => ['required', 'string'],
            'image' => [],
            'password' => [
                'required',
                Rules\Password::defaults(),
                // 大文字必須、半角英数字のみ
                'regex:/^(?=.*[A-Z])[a-zA-Z0-9]+$/',
                'confirmed',
            ],
            'introduction' => ['required', 'string', 'max:200'],
        ], [
            'password.regex' => 'パスワードには少なくとも1つの大文字を含む半角英数字を使用してください。',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
            'sex' => $request->sex,
            'image' => $request->image,
            'password' => Hash::make($request->password),
            'introduction' => $request->introduction,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
