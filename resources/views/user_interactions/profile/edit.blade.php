<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <div id="message"
                        class="hidden fixed top-[15%] left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg flex items-center space-x-4 z-50">
                    </div>
                    @include('user_interactions.profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('/js/count_character.js') }}"></script>
</x-app-layout>
