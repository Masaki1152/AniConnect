<?php

namespace App\Enums;

enum LoginPromptActionType: string
{
    case Like = 'いいね';
    case Comment = 'コメント';
    case Interested = '気になる';

    public function label(): string
    {
        return match ($this) {
            self::Like => 'いいねする',
            self::Comment => 'コメントする',
            self::Interested => '気になる登録する',
        };
    }
}
