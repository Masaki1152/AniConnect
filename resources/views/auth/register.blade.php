<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-atom.input-label for="name" :value="__('Name Max15')" />
            <x-atom.text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', '')"
                required autofocus autocomplete="name" data-max-length="15" data-counter-id="nameCharacterCount"
                oninput="countCharacter(this)" />
            <p id="nameCharacterCount" class="mt-1 text-sm text-gray-500"></p>
            <x-atom.input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-atom.input-label for="email" :value="__('Email')" />
            <x-atom.text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-atom.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-atom.input-label for="password" :value="__('Password')" />
            <p class="text-sm text-gray-600 mt-1">パスワードには少なくとも1つの大文字を含む半角英数字を使用してください。</p>
            <x-atom.text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-atom.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-atom.input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-atom.text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-atom.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Age -->
        <div class="mt-4">
            <x-atom.input-label for="age" :value="__('Age')" />
            <x-atom.text-input id="age" class="block mt-1 w-full" type="number" min="0" name="age"
                :value="old('age')" required autocomplete="age" />
            <x-atom.input-error :messages="$errors->get('age')" class="mt-2" />
        </div>

        <!-- Sex -->
        <div class="mt-4">
            <x-atom.input-label for="sex" :value="__('Sex')" />
            <x-atom.radio-input id="male" name="sex" value="男性" :checked="old('sex') === 'male'" label="男性" />
            <x-atom.radio-input id="female" name="sex" value="女性" :checked="old('sex') === 'female'" label="女性" />
            <x-atom.input-error :messages="$errors->get('sex')" class="mt-2" />
        </div>

        <!-- Introduction -->
        <div class="mt-4">
            <x-atom.input-label for="introduction" :value="__('Introduction Max200')" />
            <textarea id="introduction"
                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                type="text" name="introduction" :value="old('introduction')" rows="4" required autofocus
                autocomplete="introduction" data-max-length="200" data-counter-id="introductionCharacterCount"
                oninput="countCharacter(this)">{{ old('introduction', '') }}</textarea>
            <p id="introductionCharacterCount" class="mt-1 text-sm text-gray-500"></p>
            <x-atom.input-error :messages="$errors->get('introduction')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-atom.primary-button class="ms-4">
                {{ __('Register') }}
            </x-atom.primary-button>
        </div>
    </form>
</x-guest-layout>
