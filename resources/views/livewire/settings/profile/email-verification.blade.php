<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<div>
    @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! auth()->user()->hasVerifiedEmail())
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-800 rounded-full flex items-center justify-center">
                        <flux:icon.exclamation-triangle class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                    </div>
                </div>
                
                <div class="flex-1 space-y-4">
                    <div>
                        <h3 class="text-lg font-semibold text-amber-900 dark:text-amber-100">{{ __('Email Belum Diverifikasi') }}</h3>
                        <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                            {{ __('Alamat email Anda belum diverifikasi. Silakan periksa kotak masuk email Anda untuk tautan verifikasi.') }}
                        </p>
                    </div>

                    <div class="bg-white dark:bg-amber-900/30 rounded-lg p-4 border border-amber-200 dark:border-amber-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-100 dark:bg-amber-800 rounded-full flex items-center justify-center">
                                    <flux:icon.envelope class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-amber-900 dark:text-amber-100">{{ __('Tidak menerima email?') }}</p>
                                    <p class="text-xs text-amber-700 dark:text-amber-300">{{ __('Kami dapat mengirim ulang email verifikasi') }}</p>
                                </div>
                            </div>
                            
                            <form wire:submit="sendVerification">
                                <flux:button 
                                    type="submit" 
                                    variant="primary" 
                                    size="sm" 
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-medium px-4 py-2 rounded-lg transition-colors focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:opacity-50"
                                >
                                    <span wire:loading.remove wire:target="sendVerification" class="inline-flex items-center gap-2">
                                        <flux:icon.paper-airplane class="w-4 h-4" />
                                        {{ __('Kirim Ulang') }}
                                    </span>
                                    <span wire:loading wire:target="sendVerification" class="inline-flex items-center gap-2">
                                        <flux:icon.arrow-path class="w-4 h-4 animate-spin" />
                                        {{ __('Mengirim...') }}
                                    </span>
                                </flux:button>
                            </form>
                        </div>
                    </div>

                    @if (session('verification-status'))
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                                <flux:icon.check class="w-4 h-4 text-green-600 dark:text-green-400" />
                            </div>
                            <p class="text-sm text-green-800 dark:text-green-200">{{ session('verification-status') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center">
                    <flux:icon.check-circle class="w-5 h-5 text-green-600 dark:text-green-400" />
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-green-900 dark:text-green-100">{{ __('Email Terverifikasi') }}</h3>
                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                        {{ __('Alamat email Anda telah berhasil diverifikasi.') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>