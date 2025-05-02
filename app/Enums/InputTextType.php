<?php

namespace App\Enums;

enum InputTextType: string
{
    case Title = 'title';
    case Scene = 'scene';

    public function className(): string
    {
        return match ($this) {
            self::Title => 'title',
            self::Scene => 'scene'
        };
    }

    public function targetColumn(): string
    {
        return match ($this) {
            self::Title => 'post_title',
            self::Scene => 'scene'
        };
    }

    public function localizedText(): string
    {
        return match ($this) {
            self::Title => 'common.title',
            self::Scene => 'common.scene'
        };
    }
}
