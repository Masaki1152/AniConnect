<div id="login-dialog" class="fixed inset-0 bg-gray-900 bg-opacity-70 hidden items-center justify-center z-50">
    <div
        class="bg-baseColor rounded-[1.875vw] sm:rounded-xl relative w-[70vw] md:w-[420px] 
                max-w-[420px] aspect-[420/315] flex flex-col items-center">
        <div class="flex items-center justify-end w-full py-[1.875vw] pr-[1.875vw] sm:py-3 sm:pr-3">
            <button id="close-login-dialog">
                <x-atom.svg.close-button class="h-[4.375vw] w-[4.375vw] sm:h-7 sm:w-7" />
            </button>
        </div>
        <div class="w-[calc(388/420*100%)] h-0.5 bg-mainColor mb-[1.875vw] sm:mb-3"></div>
        <div class="flex items-center justify-center gap-3">
            <x-atom.svg.ani-connect-logo class="w-[6.25vw] sm:w-10 h-auto" />
            <x-atom.svg.ani-connect-character class="h-[5.625vw] sm:h-9 w-auto" />
        </div>
        <div class="text-[3.125vw] sm:text-xl font-bold text-textColor text-center my-[1.875vw] sm:my-3">
            {{ __('messages.login_appeal_title') }}</div>
        <p class="text-[1.875vw] sm:text-xs text-textColor text-center mb-[3.75vw] sm:mb-6"></p>
        <div class="flex flex-col items-center gap-[1.875vw] sm:gap-3 mb-[4.375px] sm:mb-7 w-full">
            <a href="{{ route('login') }}"
                class="w-full max-w-[45vw] sm:max-w-[288px] aspect-[288/42] flex items-center justify-center rounded-[1.875vw] sm:rounded-xl bg-mainColor hover:bg-mainColorHover text-white text-[2.5vw] sm:text-base font-bold transition-colors duration-200">
                {{ __('common.do_login') }}
            </a>
            <a href="{{ route('register') }}"
                class="w-full max-w-[45vw] sm:max-w-[288px] aspect-[288/42] flex items-center justify-center rounded-[1.875vw] sm:rounded-xl bg-accentColor hover:bg-accentColorHover text-white text-[2.5vw] sm:text-base font-bold transition-colors duration-200">
                {{ __('common.do_member_registration') }}
            </a>
        </div>
    </div>
</div>
