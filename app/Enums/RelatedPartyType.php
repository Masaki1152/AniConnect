<?php

namespace App\Enums;

enum RelatedPartyType: string
{
    case Creator = 'creator';
    case Singer = 'singer';

    public function relatedPartyName(): string
    {
        return match ($this) {
            self::Creator => 'creator',
            self::Singer => 'singer'
        };
    }

    public function targetTableName(): string
    {
        return match ($this) {
            self::Creator => 'creators',
            self::Singer => 'singers'
        };
    }

    public function targetColumn(): string
    {
        return match ($this) {
            self::Creator => 'creator_id',
            self::Singer => 'singer_id'
        };
    }

    public function localizedText(): string
    {
        return match ($this) {
            self::Creator => 'common.creator',
            self::Singer => 'common.singer'
        };
    }
}
