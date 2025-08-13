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
}
