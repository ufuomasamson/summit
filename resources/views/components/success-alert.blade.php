{{-- Ally Bank Design System: status-success #28A745, primary #49786B --}}
@if(Session::has('success'))
<div x-data="{ show: true }" 
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="mb-4 animate-slide-up">
    <div class="glass-effect rounded-2xl border border-[#28A745]/30 overflow-hidden shadow-medium hover-lift">
        <div class="flex items-start p-4 gap-4">
            <!-- Success Icon with Animated Glow -->
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#28A745] to-[#1E8836] flex items-center justify-center shadow-lg relative">
                    <div class="absolute inset-0 bg-[#28A745]/20 rounded-xl animate-pulse"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#FFFFFF] relative z-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
            </div>

            <!-- Alert Content -->
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-bold text-gray-900 mb-1 font-display">Success!</h4>
                <p class="text-sm text-gray-900 leading-relaxed">{{ Session::get('success') }}</p>
            </div>

            <!-- Dismiss Button -->
            <button @click="show = false" 
                    class="flex-shrink-0 p-1.5 rounded-lg hover:bg-[#28A745]/10 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#BFC9C9] group-hover:text-[#FFFFFF] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6 6 18"/>
                    <path d="m6 6 12 12"/>
                </svg>
            </button>
        </div>

        <!-- Animated Progress Bar -->
        <div class="h-1 bg-[#28A745]/10">
            <div class="h-full bg-gradient-to-r from-[#28A745] to-[#48C762] animate-progress" 
                 style="animation: progress 5s linear forwards;"
                 @animationend="show = false"></div>
        </div>
    </div>
</div>

<style>
@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>
@endif