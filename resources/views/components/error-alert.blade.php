{{-- Ally Bank Design System: status-error #D55672 --}}
<div>
    @if ($errors->any())
    <div x-data="{ show: true }" 
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="mb-4 animate-slide-up">
        <div class="glass-effect rounded-2xl border border-[#D55672]/30 overflow-hidden shadow-medium hover-lift">
            <div class="flex items-start p-4 gap-4">
                <!-- Error Icon -->
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#D55672] to-[#B8465F] flex items-center justify-center shadow-lg relative">
                        <div class="absolute inset-0 bg-[#D55672]/20 rounded-xl animate-pulse"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FFFFFF] relative z-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v4"/>
                            <path d="M12 16h.01"/>
                        </svg>
                    </div>
                </div>

                <!-- Alert Content -->
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-gray-900 mb-2 font-display">Validation Error</h4>
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-gray-700 leading-relaxed flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-[#D55672] mr-2 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 6 6 18"/>
                                    <path d="m6 6 12 12"/>
                                </svg>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Dismiss Button -->
                <button @click="show = false" 
                        class="flex-shrink-0 p-1.5 rounded-lg hover:bg-[#D55672]/10 transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#BFC9C9] group-hover:text-[#FFFFFF] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Animated Progress Bar -->
            <div class="h-1 bg-[#D55672]/10">
                <div class="h-full bg-gradient-to-r from-[#D55672] to-[#DD7A90] animate-progress" 
                     style="animation: progress 10s linear forwards;"
                     @animationend="show = false"></div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>