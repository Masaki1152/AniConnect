<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Main') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                    <p>メイン画面</p>
                </div>
                <div class="notifications">
                    <h2>お知らせ</h2>
                    <ul>
                        @foreach ($notifications as $notification)
                            <li>{{ $notification->title }}</li>
                            <li>{{ $notification->created_at->format('Y/m/d H:i') }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
