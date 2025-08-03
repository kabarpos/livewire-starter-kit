<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component {
    use WithFileUploads;

    public $avatar;

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(): void
    {
        $this->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $avatarPath = $this->avatar->store('avatars', 'public');
        $user->update(['avatar' => $avatarPath]);

        // Reset avatar input
        $this->avatar = null;

        // Dispatch event
        $this->dispatch('avatar-updated');

        session()->flash('avatar-status', 'Avatar berhasil diperbarui!');
    }

    /**
     * Remove the user's avatar.
     */
    public function removeAvatar(): void
    {
        $user = Auth::user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
            
            $this->dispatch('avatar-updated');
            session()->flash('avatar-status', 'Avatar berhasil dihapus!');
        }
    }
}; ?>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 shadow-sm">
    <div class="flex items-center justify-between mb-3">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Foto Profil') }}</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Kelola foto profil Anda') }}</p>
        </div>
    </div>
    
    <!-- Current Avatar Display -->
    <div class="flex flex-col sm:flex-row sm:items-start gap-4 mb-4">
        <div class="relative flex-shrink-0">
            @if(auth()->user()->avatar)
                <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Current Avatar" class="w-20 h-20 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-600 shadow-md">
                <div class="absolute -bottom-1 -right-1 bg-green-500 w-6 h-6 rounded-full border-2 border-white dark:border-gray-800 flex items-center justify-center">
                    <flux:icon.check class="w-3 h-3 text-white" />
                </div>
            @else
               
            @endif
        </div>
        
        <div class="flex-1 min-w-0">
            @if(auth()->user()->avatar)
                <div class="space-y-1">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Foto profil aktif') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Foto ini akan ditampilkan di profil Anda') }}</p>
                    </div>
                    <flux:button wire:click="removeAvatar" variant="danger" size="xs" class="inline-flex items-center gap-1">
                        <span class="text-white flex items-center gap-1 px-2.5 py-0.5 rounded-full">
                        <flux:icon.trash class="w-3 h-3" />
                        {{ __('Hapus') }}
                        </span>
                    </flux:button>

                </div>
            @endif
        </div>
    </div>

    <!-- Avatar Upload Form -->
    @if(!auth()->user()->avatar)
    <form wire:submit="updateAvatar" class="space-y-3">
        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-2.5 text-center hover:border-blue-400 dark:hover:border-blue-500 transition-colors">
            <div class="space-y-1.5">
                <div class="mx-auto w-7 h-7 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                    <flux:icon.camera class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <label for="avatar-upload" class="cursor-pointer">
                        <span class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">{{ __('Klik untuk unggah') }}</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400"> {{ __('atau seret file ke sini') }}</span>
                    </label>
                    <input id="avatar-upload" type="file" wire:model="avatar" accept="image/*" class="sr-only">
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('PNG, JPG, GIF hingga 2MB') }}</p>
            </div>
        </div>
        
        @error('avatar') 
            <div class="flex items-center gap-2 text-red-600 dark:text-red-400 text-sm">
                <flux:icon.exclamation-circle class="w-4 h-4" />
                <span>{{ $message }}</span>
            </div>
        @enderror
        
        @if ($avatar)
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-2">
                   
                <div class="text-center mx-auto">
                        <p class="text-sm font-medium text-blue-900 dark:text-blue-100">{{ __('Foto baru siap diunggah') }}</p>
                        <p class="text-xs text-blue-700 dark:text-blue-300">{{ __('Klik simpan untuk menerapkan perubahan') }}</p>
                    
                    <div class="flex items-center gap-2 mt-3 justify-center">
                    <flux:button type="submit" variant="primary" size="xs" wire:loading.attr="disabled" class="flex items-center justify-center gap-1 h-8">
                        <span wire:loading.remove wire:target="updateAvatar" class="flex items-center gap-1">
                            <flux:icon.check class="w-2.5 h-2.5" />
                            {{ __('Simpan') }}
                        </span>
                        <span wire:loading wire:target="updateAvatar" class="flex items-center gap-1.5">
                            <flux:icon.arrow-path class="w-2.5 h-2.5 animate-spin" />
                            {{ __('Menyimpan...') }}
                        </span>
                    </flux:button>
                    <flux:button wire:click="$set('avatar', null)" variant="danger" size="xs" class="flex items-center justify-center gap-1 h-8">
                          <span class="flex items-center gap-1">
                        <flux:icon.x-mark class="w-2.5 h-2.5" />
                        {{ __('Batal') }}
                        </span>
                    </flux:button>
                </div>


                </div>
                
            </div>
        @endif
    </form>
    @endif

    <!-- Status Messages -->
    @if (session('avatar-status'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-2 flex items-center gap-1.5">
            <div class="w-5 h-5 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                <flux:icon.check class="w-2.5 h-2.5 text-green-600 dark:text-green-400" />
            </div>
            <p class="text-sm text-green-800 dark:text-green-200">{{ session('avatar-status') }}</p>
        </div>
    @endif
</div>