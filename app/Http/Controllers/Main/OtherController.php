<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;

class OtherController extends Controller
{
    // プライバシーポリシーを表示する
    public function show()
    {
        return view('main.privacy_policy.show');
    }

    // 利用規約を表示する
    public function showTerms()
    {
        return view('main.terms.show');
    }
}
