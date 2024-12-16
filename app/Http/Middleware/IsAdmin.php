<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // ログインユーザーが管理者でなければ403エラーを返す
        if (!Auth::user()->is_admin) {
            abort(403, 'このページにアクセスする権限がありません。');
        }

        return $next($request);
    }
}
