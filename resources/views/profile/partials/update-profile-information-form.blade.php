<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name Max15')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" data-max-length="15" data-counter-id="nameCharacterCount"
                oninput="countCharacter(this)" />
            <p id="nameCharacterCount" class="mt-1 text-sm text-gray-500"></p>
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Age -->
        <div class="mt-4">
            <x-input-label for="age" :value="__('Age')" />
            <x-text-input id="age" class="block mt-1 w-full" type="number" min="0" name="age"
                :value="old('age', $user->age)" required autocomplete="age" />
            <x-input-error :messages="$errors->get('age')" class="mt-2" />
        </div>

        <!-- Sex -->
        <div class="mt-4">
            <x-input-label for="sex" :value="__('Sex')" />
            {{ $user->sex }}
        </div>

        <!-- Introduction -->
        <div class="mt-4">
            <x-input-label for="introduction" :value="__('Introduction Max200')" />
            <textarea id="introduction" name="introduction"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                rows="4" required autofocus autocomplete="introduction" data-max-length="200"
                data-counter-id="introductionCharacterCount" oninput="countCharacter(this)">{{ old('introduction', $user->introduction) }}</textarea>
            <p id="introductionCharacterCount" class="mt-1 text-sm text-gray-500"></p>
            <x-input-error :messages="$errors->get('introduction')" class="mt-2" />
        </div>

        <!-- Image -->
        @php
            $existingImagePath = Auth::user()->image;
        @endphp
        <div id="existing_image_path" data-php-variable="{{ $existingImagePath }}"></div>
        <div>
            <x-input-label for="image" :value="__('User_Image')" />
            <label
                class="inline-flex items-center gap-2 cursor-pointer  font-medium text-sm text-blue-500 bg-blue-20 border-2 border-gray-300 rounded-lg py-1 px-2 hover:bg-blue-50 mt-2">
                <input id="image" class="block mt-1 w-full" type="file" name="image"
                    :value="old('image', $user - > image)" style="display:none">画像の選択
            </label>
            <!-- 既存画像のパス -->
            <input type="hidden" name="existingImage" id="existingImage" value="">
            <x-input-error :messages="$errors->get('image')" class="mt-2" />
        </div>
        <!-- 画像トリミング用のモーダルウィンドウ表示 -->
        <div id="crop-modal"
            class="crop-modal hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-4 rounded-lg shadow-lg relative w-full max-w-2xl">
                <div class="overflow-hidden w-full h-auto">
                    <img id="crop-preview" class="crop-preview w-full h-auto object-cover" />
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button id="crop-cancel-button" type="button"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        キャンセル
                    </button>
                    <button id="crop-button" type="button"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        トリミング完了
                    </button>
                </div>
            </div>
        </div>
        <!-- プレビュー画像の表示 -->
        <div id="preview" style="width: 300px;"></div>

        <div class="flex items-center  justify-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
<script src="{{ asset('/js/edit_profile_preview.js') }}"></script>
