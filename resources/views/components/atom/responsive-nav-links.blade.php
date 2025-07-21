@php
    $links = [
        ['route' => 'works.index', 'label' => __('common.work_list')],
        ['route' => 'characters.index', 'label' => __('common.character_list')],
        ['route' => 'music.index', 'label' => __('common.music_list')],
        ['route' => 'pilgrimages.index', 'label' => __('common.anime_pilgrimage_list')],
        ['route' => 'users.index', 'label' => __('common.registered_member_list')],
        ['route' => 'users.index', 'label' => __('common.other')],
    ];
@endphp

@foreach ($links as $link)
    <x-atom.responsive-nav-link :href="route($link['route'])" :active="request()->routeIs($link['route'])">
        {{ $link['label'] }}
    </x-atom.responsive-nav-link>
@endforeach
