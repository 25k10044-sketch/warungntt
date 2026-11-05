<x-app-layout>
    <x-slot name="header">
        <h2 class="font-serif text-2xl font-bold text-[#1F1A17]">
            Profil saya
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-[#eadfca] bg-white p-5 shadow-sm sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="rounded-2xl border border-[#eadfca] bg-white p-5 shadow-sm sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm sm:p-8">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
