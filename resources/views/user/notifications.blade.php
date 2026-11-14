@extends('layouts.bankapp')

@section('title', 'Notifications')

@section('content')
<div x-data="{ selectedType: 'all' }">
    <!-- Page Header -->
    <div class="mb-5">
        <h1 class="text-lg font-semibold text-[#0D1B23] mb-1">Notifications</h1>
        <p class="text-sm text-[#7A7A7A]">Stay updated with your account activities</p>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-[#DFE5E5] border border-[#E0E5E5] rounded-lg p-1 mb-5 inline-flex">
        <button @click="selectedType = 'all'" 
                :class="{ 'bg-primary-500 text-white': selectedType === 'all', 'text-[#7A7A7A] hover:text-[#0D1B23]': selectedType !== 'all' }"
                class="px-4 py-1.5 rounded-md transition-colors font-medium text-sm">
            All
        </button>
        <button @click="selectedType = 'unread'" 
                :class="{ 'bg-primary-500 text-white': selectedType === 'unread', 'text-[#7A7A7A] hover:text-[#0D1B23]': selectedType !== 'unread' }"
                class="px-4 py-1.5 rounded-md transition-colors font-medium text-sm">
            Unread
        </button>
        <button @click="selectedType = 'transfer'" 
                :class="{ 'bg-primary-500 text-white': selectedType === 'transfer', 'text-[#7A7A7A] hover:text-[#0D1B23]': selectedType !== 'transfer' }"
                class="px-4 py-1.5 rounded-md transition-colors font-medium text-sm">
            Transfers
        </button>
        <button @click="selectedType = 'deposit'" 
                :class="{ 'bg-primary-500 text-white': selectedType === 'deposit', 'text-[#7A7A7A] hover:text-[#0D1B23]': selectedType !== 'deposit' }"
                class="px-4 py-1.5 rounded-md transition-colors font-medium text-sm">
            Deposits
        </button>
    </div>

    <!-- Notifications List -->
    <div class="space-y-3">
        @forelse($notifications as $notification)
            <div x-show="selectedType === 'all' || 
                        (selectedType === 'unread' && {{ $notification->read_at ? 'false' : 'true' }}) ||
                        (selectedType === '{{ $notification->type }}')"
                 class="bg-white border border-[#E0E5E5] rounded-xl p-4 hover:bg-[#DFE5E5]/20 hover:border-[#C8D1D1] transition-colors shadow-sm {{ !$notification->read_at ? 'border-primary-500/30' : '' }}">
                <div class="flex items-start space-x-3">
                    <!-- Icon -->
                    <div class="w-9 h-9 rounded-lg flex-shrink-0 flex items-center justify-center
                                @if($notification->type === 'transfer' || $notification->type === 'deposit') bg-primary-500/10
                                @elseif($notification->type === 'password_change' || $notification->type === 'login') bg-[#3e4e83]/10
                                @elseif($notification->type === 'card') bg-blue-500/10
                                @elseif($notification->type === 'withdrawal') bg-orange-500/10
                                @else bg-[#7A7A7A]/10
                                @endif">
                        <i data-lucide="{{ $notification->icon }}" class="w-4 h-4 text-[#4A4A4A]"></i>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3 mb-1">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-semibold text-[#0D1B23]">{{ $notification->title }}</h3>
                                    @if(!$notification->read_at)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-primary-500/15 border border-primary-500/25 text-[10px] font-semibold text-primary-600">
                                            New
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-[#7A7A7A] mt-0.5">{{ $notification->message }}</p>
                                
                                <!-- Additional Data -->
                                @if($notification->data && count($notification->data) > 0)
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @if(isset($notification->data['amount']))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded bg-primary-500/10 border border-primary-500/20">
                                                <i data-lucide="banknote" class="w-3 h-3 mr-1 text-primary-600"></i>
                                                <span class="text-xs font-medium text-primary-600">
                                                    {{ $notification->data['currency'] ?? '' }}{{ $notification->data['amount'] }}
                                                </span>
                                            </span>
                                        @endif
                                        
                                        @if(isset($notification->data['status']))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded 
                                                        @if($notification->data['status'] === 'successful') bg-[#28A745]/10 border border-[#28A745]/20
                                                        @elseif($notification->data['status'] === 'pending') bg-[#3e4e83]/10 border border-[#3e4e83]/20
                                                        @else bg-[#7A7A7A]/10 border border-[#7A7A7A]/20
                                                        @endif">
                                                <span class="text-xs font-medium
                                                            @if($notification->data['status'] === 'successful') text-[#28A745]
                                                            @elseif($notification->data['status'] === 'pending') text-[#3e4e83]
                                                            @else text-[#7A7A7A]
                                                            @endif">
                                                    {{ ucfirst($notification->data['status']) }}
                                                </span>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center space-x-1">
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="p-1.5 rounded-lg hover:bg-[#DFE5E5] transition-colors"
                                                title="Mark as read">
                                            <i data-lucide="check" class="w-4 h-4 text-[#7A7A7A] hover:text-primary-500"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Delete this notification?')"
                                            class="p-1.5 rounded-lg hover:bg-[#D55672]/10 transition-colors"
                                            title="Delete">
                                        <i data-lucide="trash-2" class="w-4 h-4 text-[#7A7A7A] hover:text-[#D55672]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Timestamp -->
                        <div class="flex items-center space-x-3 text-xs text-[#7A7A7A] mt-2">
                            <div class="flex items-center">
                                <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                            <div class="flex items-center">
                                <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                {{ $notification->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-8 text-center shadow-sm">
                <div class="w-12 h-12 rounded-lg bg-[#DFE5E5] flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="bell-off" class="w-6 h-6 text-[#7A7A7A]"></i>
                </div>
                <h3 class="text-sm font-semibold text-[#0D1B23] mb-1">No Notifications</h3>
                <p class="text-sm text-[#7A7A7A]">You're all caught up! Check back later for updates.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection

