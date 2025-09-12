<footer class="bg-mainColor text-subTextColor text-sm lg:text-base font-semibold py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row justify-between gap-4">
            <div class="flex flex-col lg:flex-row gap-2 lg:gap-8 ml-0 lg:ml-8">
                <a href="{{ route('privacy_policy.show') }}" class="hover:underline">{{ __('common.privacy_policy') }}</a>
                <a href="{{ route('terms.show') }}" class="hover:underline">{{ __('common.terms') }}</a>
                <a href="/contact" class="hover:underline">{{ __('common.contact') }}</a>
            </div>
            <p class="text-right lg:mr-12">{{ __('common.right') }}</p>
        </div>
    </div>
</footer>
