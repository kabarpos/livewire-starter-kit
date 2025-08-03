<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $whatsapp = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->whatsapp = Auth::user()->whatsapp ?? '';
    }

    /**
     * Update the profile information.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20', Rule::unique(User::class)->ignore($user->id)],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
        session()->flash('profile-status', 'Informasi profil berhasil diperbarui!');
    }
}; ?>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Informasi Profil') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Perbarui informasi akun dan alamat email Anda') }}</p>
        </div>
    </div>
    
    <form wire:submit="updateProfileInformation" class="space-y-6">
        <!-- Nama Lengkap -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Nama Lengkap') }}
                <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <flux:icon.user class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                </div>
                <flux:input 
                    id="name"
                    wire:model="name" 
                    type="text" 
                    required 
                    class="pl-10 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="{{ __('Masukkan nama lengkap') }}"
                />
            </div>
            @error('name') 
                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm mt-1">
                    <flux:icon.exclamation-circle class="w-4 h-4" />
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Nomor WhatsApp -->
        <div class="space-y-2">
            <label for="whatsapp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Nomor WhatsApp') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <flux:icon.phone class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                </div>
                <flux:input 
                    id="whatsapp"
                    wire:model="whatsapp" 
                    type="text" 
                    class="pl-10 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="+62812345678"
                />
            </div>
            @error('whatsapp') 
                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm mt-1">
                    <flux:icon.exclamation-circle class="w-4 h-4" />
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <!-- Alamat Email -->
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Alamat Email') }}
                <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none z-10">
                    <flux:icon.envelope class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                </div>
                <flux:input 
                    id="email"
                    wire:model="email" 
                    type="email" 
                    required 
                    class="pl-10 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="{{ __('nama@example.com') }}"
                />
            </div>
            @error('email') 
                <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm mt-1">
                    <flux:icon.exclamation-circle class="w-4 h-4" />
                    <span>{{ $message }}</span>
                </div>
            @enderror
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <span class="text-red-500">*</span> {{ __('Wajib diisi') }}
            </div>
            <flux:button 
                type="submit" 
                variant="primary" 
                wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove wire:target="updateProfileInformation" class="inline-flex items-center gap-2">
                    <flux:icon.check class="w-4 h-4" />
                    {{ __('Simpan Perubahan') }}
                </span>
                <span wire:loading wire:target="updateProfileInformation" class="inline-flex items-center gap-2">
                    <flux:icon.arrow-path class="w-4 h-4 animate-spin" />
                    {{ __('Menyimpan...') }}
                </span>
            </flux:button>
        </div>
    </form>

    @if (session('profile-status'))
        <div class="mt-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center gap-3">
            <div class="w-8 h-8 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                <flux:icon.check class="w-4 h-4 text-green-600 dark:text-green-400" />
            </div>
            <p class="text-sm text-green-800 dark:text-green-200">{{ session('profile-status') }}</p>
        </div>
    @endif
</div>