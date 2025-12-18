@extends('dashboard.layout.admin', ['title' => t('Profile')])

@section('css')
@endsection

@section('content')

<!-- Start Content-->
@include('dashboard.layout.shared/page-title', ['subtitle' => 'Profile', 'title' => 'Profile'])
 <div class="grid grid-cols-12 md:grid--cols-2 gap-4 mb-4">
        <x-card
            class="col-span-12 md:col-span-5 xl:col-span-4 xxl:col-span-3 p-5 flex flex-col items-start py-8  border-t-4 border-t-primary-500">

            <div class="flex flex-col items-center w-full gap-2">
                <img class="w-24 h-24 p-1 rounded-full ring-4 ring-secondary-300 dark:ring-gray-300"
                    src="{{ auth()->user()->image_url }}" alt="Bordered avatar">

                <h4 class="text-xl  text-gray-900 dark:text-gray-100 font-medium mt-4">{{ Auth::user()->name }}</h4>
                <p class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-200">manager</p>
                <p class="text-gray-600 dark:text-gray-400 mt-1 text-sm">{{ Auth::user()->email }}</p>
            </div>

            <hr class="h-px w-full my-5 bg-gray-200 border-0 dark:bg-gray-700">
        </x-card>

        <x-card class="col-span-12 md:col-span-7 xl:col-span-8 xxl:col-span-9 px-0 p">
            <div x-data="{ selectedIndex: 0 }" class="border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap px-3 -mb-px text-sm font-medium text-center" id="userProfileTab"
                    data-tabs-toggle="#userProfileTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button x-bind:class="selectedIndex == 0 ? 'border-b-primary-400 text-primary-500' : ''"
                            class="inline-block p-3 border-b-2 rounded-t-lg text-gray-500 hover:border-primary-300 dark:hover:text-gray-300"
                            id="profile-tab" data-tabs-target="#profile" type="button" role="tab"
                            aria-controls="profile" aria-selected="false" x-on:click="selectedIndex = 0">
                            <i class="fa-solid fa-user mr-2"></i>
                            Profile
                        </button>
                    </li>

                    <li class="mr-2" role="presentation">
                        <button x-bind:class="selectedIndex == 1 ? 'border-b-primary-400 text-primary-500' : ''"
                            class="inline-block p-3 border-b-2 rounded-t-lg text-gray-500 hover:border-primary-300 dark:hover:text-gray-300"
                            id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab"
                            aria-controls="dashboard" aria-selected="false" x-on:click="selectedIndex = 1">
                            <i class="fa-solid fa-lock mr-2"></i>
                            Security
                        </button>
                    </li>
                </ul>
            </div>

            <div id="userProfileTabContent">
                <div class="hidden px-6 py-5" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        {{-- @include('dashboard.profile.sections.update-profile-information-form') --}}
                        @livewire('profile.update-profile-information')
                </div>

                <div class="hidden px-6 py-5" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
                        <div class="mt-10 sm:mt-0">
                            @livewire('profile.update-password')
                            {{-- @include('dashboard.profile.sections.update-password-form') --}}
                        </div>

                        <hr class="h-px w-full my-8 bg-gray-200 border-0 dark:bg-gray-700">
                        @livewire('profile.logout-other-sessions')
                    {{-- @include('dashboard.profile.sections.logout-other-browser-sessions-form') --}}
                </div>
            </div>
        </x-card>
    </div>
@endsection

@section('script')
{{-- @vite(['resources/js/pages/dashboard.js']) --}}
@endsection
