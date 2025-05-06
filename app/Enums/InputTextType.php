<?php

namespace App\Enums;

enum InputTextType: string
{
    case Title = 'title';
    case Scene = 'scene';
    case Name = 'name';
    case Copyright = 'copyright';
    case Term = 'term';
    case OfficialSiteLink = 'official_site_link';
    case WikiLink = 'wiki_link';
    case TwitterLink = 'twitter_link';

    public function className(): string
    {
        return match ($this) {
            self::Title => 'title',
            self::Scene => 'scene',
            self::Name => 'name',
            self::Copyright => 'copyright',
            self::Term => 'term',
            self::OfficialSiteLink => 'official_site_link',
            self::WikiLink => 'wiki_link',
            self::TwitterLink => 'twitter_link'
        };
    }

    public function targetColumn(): string
    {
        return match ($this) {
            self::Title => 'post_title',
            self::Scene => 'scene',
            self::Name => 'name',
            self::Copyright => 'copyright',
            self::Term => 'term',
            self::OfficialSiteLink => 'official_site_link',
            self::WikiLink => 'wiki_link',
            self::TwitterLink => 'twitter_link'
        };
    }

    public function localizedText(): string
    {
        return match ($this) {
            self::Title => 'common.title',
            self::Scene => 'common.scene',
            self::Name => 'common.name',
            self::Copyright => 'common.copyright',
            self::Term => 'common.term',
            self::OfficialSiteLink => 'common.official_site_link',
            self::WikiLink => 'common.wiki_link',
            self::TwitterLink => 'common.twitter_link'
        };
    }
}
