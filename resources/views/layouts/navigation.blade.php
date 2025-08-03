<nav x-data="{ open: false }" class="fixed top-0 left-0 w-full z-50 bg-baseColor border-b-2 border-mainColor">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center gap-2">
                    <div class="block">
                        <a href="{{ route('main.index') }}">
                            <x-atom.svg.ani-connect-logo class="h-10 w-auto fill-current" />
                        </a>
                    </div>
                    <div class="hidden sm:block">
                        <a href="{{ route('main.index') }}">
                            <x-atom.svg.ani-connect-character class="h-7 w-auto fill-current" />
                        </a>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="hidden pt-2 hc:flex hc:items-center space-x-8 ml-64">
                    @auth
                        @if (Auth::user()->is_admin === 1)
                            <x-atom.nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('admin.admin_screen') }}
                            </x-atom.nav-link>
                        @endif
                    @endauth
                    <x-atom.nav-link :href="route('works.index')" :active="request()->routeIs('works.index')">
                        {{ __('common.work_list') }}
                    </x-atom.nav-link>
                    <x-atom.nav-link :href="route('characters.index')" :active="request()->routeIs('characters.index')">
                        {{ __('common.character_list') }}
                    </x-atom.nav-link>
                    <x-atom.nav-link :href="route('music.index')" :active="request()->routeIs('music.index')">
                        {{ __('common.music_list') }}
                    </x-atom.nav-link>
                    <x-atom.nav-link :href="route('pilgrimages.index')" :active="request()->routeIs('pilgrimages.index')">
                        {{ __('common.anime_pilgrimage_list') }}
                    </x-atom.nav-link>
                    <x-atom.nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        {{ __('common.registered_member_list') }}
                    </x-atom.nav-link>
                    <!-- TODO: その他は別途実装 -->
                    <x-atom.nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        {{ __('common.other') }}
                    </x-atom.nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden hc:flex hc:items-center hc:ms-6">
                @auth
                    <x-atom.dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <!-- アイコン画像 -->
                                <img src="{{ Auth::user()->image ?? 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png' }}"
                                    alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover mr-1">
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-atom.dropdown-link :href="route('profile.index')">
                                {{ __('common.profile') }}
                            </x-atom.dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-atom.dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('common.log_out') }}
                                </x-atom.dropdown-link>
                            </form>
                        </x-slot>
                    </x-atom.dropdown>
                @else
                    <div class="flex space-x-6">
                        <a href="{{ route('login') }}"
                            class="px-3 py-2 rounded-xl bg-mainColor hover:bg-mainColorHover text-white text-base font-bold transition-colors duration-200">{{ __('common.login') }}</a>
                        <a href="{{ route('register') }}"
                            class="px-3 py-2 rounded-xl bg-accentColor hover:bg-accentColorHover text-white text-base font-bold transition-colors duration-200">{{ __('common.member_registration') }}</a>
                    </div>
                @endauth
            </div>

            <!-- モバイル用ログイン・登録・ハンバーガー -->
            <div class="flex items-center hc:hidden">
                @auth
                    <img src="{{ Auth::user()->image ?? 'https://res.cloudinary.com/dnumegejl/image/upload/v1732628038/No_User_Image_wulbjv.png' }}"
                        alt="{{ Auth::user()->name }}" class="w-10 h-10 rounded-full object-cover mr-1">
                @else
                    <a href="{{ route('login') }}"
                        class="px-3 py-2 rounded-xl bg-mainColor hover:bg-mainColorHover text-white text-sm font-bold transition-colors duration-200">
                        {{ __('common.login') }}
                    </a>
                    <a href="{{ route('register') }}"
                        class="ml-4 px-3 py-2 rounded-xl bg-accentColor hover:bg-accentColorHover text-white text-sm font-bold transition-colors duration-200">
                        {{ __('common.member_registration') }}
                    </a>
                @endauth

                <!-- Hamburger -->
                <button @click="open = ! open"
                    class="ml-2 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden hc:hidden">
        <!-- Responsive Settings Options -->
        <div class="pt-2 pb-2 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-textColor">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-subTextColor">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-atom.responsive-nav-link :href="route('profile.index')">
                        {{ __('common.profile') }}
                    </x-atom.responsive-nav-link>

                    <x-atom.responsive-nav-links />
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-atom.responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('common.log_out') }}
                        </x-atom.responsive-nav-link>
                    </form>
                </div>
            @else
                {{-- 未ログインユーザー向けのレスポンシブメニュー --}}
                <div class="space-y-1">
                    <x-atom.responsive-nav-links />
                </div>
            @endauth
        </div>
    </div>
</nav>
