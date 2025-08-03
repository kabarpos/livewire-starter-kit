<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your profile information')">
        <div class="w-full mx-auto">
            <!-- 2 Column Layout for Desktop, 1 Column for Mobile -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Avatar Upload Component -->
                    <livewire:settings.profile.avatar-upload />
                    
                    <!-- Email Verification Component -->
                    <livewire:settings.profile.email-verification />
                </div>
                
                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Profile Information Form Component -->
                    <livewire:settings.profile.information-form />
                </div>
            </div>
            
            <!-- Danger Zone - Full Width -->
            <div class="pt-8 mt-8 rounded-2xl">
                <div class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-950/50 dark:to-red-900/30 border border-red-200 dark:border-red-800/50 rounded-2xl p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-full flex items-center justify-center">
                                <flux:icon.exclamation-triangle class="w-6 h-6 text-red-600 dark:text-red-400" />
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-red-900 dark:text-red-100">{{ __('Zona Berbahaya') }}</h3>
                                <p class="text-sm text-red-700 dark:text-red-300">{{ __('Tindakan berikut tidak dapat dibatalkan.') }}</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <!-- Delete User Form -->
                            <livewire:settings.delete-user-form />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-settings.layout>
</section>
