<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __('こちらは管理画面です') }}
                </div>
                <div class="notification p-6 text-gray-900">
                    <a href="{{ route('admin.notifications.index') }}">お知らせ一覧（管理者用）へ</a>
                </div>
                <div class="work p-6 text-gray-900">
                    <a href="{{ route('admin.works.index') }}">作品一覧（管理者用）へ</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
