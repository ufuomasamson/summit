@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<style>
    /* Ally Bank Color Design System — Light Theme */
    
    .bg-pattern {
        background-color: #F9F9F9;
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid #E0E5E5;
    }
    
    .glass-header {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(30px);
        border-bottom: 1px solid #E0E5E5;
    }
    
    .gradient-primary {
        background: linear-gradient(135deg, #49786B 0%, #396154 100%);
    }
    
    .gradient-gold {
        background: linear-gradient(135deg, #3e4e83 0%, #c89e3a 100%);
    }
    
    .status-pill {
        padding: 0.375rem 1rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.025em;
        text-transform: uppercase;
    }
    
    .table-row-hover {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .table-row-hover:hover {
        background: rgba(73, 120, 107, 0.04);
        transform: translateX(4px);
    }
    
    .icon-badge {
        width: 2.75rem;
        height: 2.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        background: linear-gradient(135deg, rgba(73, 120, 107, 0.08) 0%, rgba(73, 120, 107, 0.03) 100%);
        border: 1px solid rgba(73, 120, 107, 0.12);
    }
    
    .stat-card {
        background: #FFFFFF;
        border: 1px solid #E0E5E5;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        border-color: rgba(73, 120, 107, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px -6px rgba(73, 120, 107, 0.15);
    }
    
    .search-input {
        background: #FFFFFF;
        border: 1px solid #E0E5E5;
        color: #0D1B23;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        background: #FFFFFF;
        border-color: #49786B;
        box-shadow: 0 0 0 3px rgba(73, 120, 107, 0.1);
        outline: none;
    }
    
    .search-input::placeholder {
        color: #7A7A7A;
    }
    
    .modal-overlay {
        background: rgba(13, 27, 35, 0.5);
        backdrop-filter: blur(8px);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #49786B 0%, #396154 100%);
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(73, 120, 107, 0.3);
    }
    
    .btn-outline-primary {
        background: transparent;
        color: #49786B;
        border: 1.5px solid rgba(73, 120, 107, 0.3);
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background: rgba(73, 120, 107, 0.06);
        border-color: rgba(73, 120, 107, 0.5);
        color: #396154;
    }
    
    .duration-badge {
        background: rgba(235, 188, 70, 0.08);
        color: #a37e2e;
        border: 1px solid rgba(235, 188, 70, 0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 600;
    }
</style>

<div class="min-h-screen bg-pattern" x-data="{
    showFilterModal: false,
    showExportModal: false,
    searchQuery: '',
    statusFilter: 'all',
    sortOrder: 'desc',
    perPage: 25,
    exportFormat: 'csv',
    exportMethod: 'download',
    isExporting: false,
    
    formatDuration(months) {
        if (!months) return 'N/A';
        months = parseInt(months);
        
        if (months < 12) {
            return months + (months === 1 ? ' Month' : ' Months');
        }
        
        const years = Math.floor(months / 12);
        const remainingMonths = months % 12;
        
        let result = years + (years === 1 ? ' Year' : ' Years');
        if (remainingMonths > 0) {
            result += ' ' + remainingMonths + (remainingMonths === 1 ? ' Month' : ' Months');
        }
        
        return result;
    },
    
    getStatusColor(status) {
        const colors = {
            'Processed': { bg: 'bg-success/10', text: 'text-success-dark', border: 'border-success/30' },
            'Pending': { bg: 'bg-alert/10', text: 'text-alert-dark', border: 'border-alert/30' },
            'yes': { bg: 'bg-primary-500/10', text: 'text-primary-500', border: 'border-primary-500/30' },
            'No': { bg: 'bg-danger/10', text: 'text-danger', border: 'border-danger/30' }
        };
        return colors[status] || { bg: 'bg-gray-500/10', text: 'text-[#7A7A7A]', border: 'border-gray-500/30' };
    },
    
    exportLoans() {
        if (!this.exportFormat || !this.exportMethod) {
            alert('Please select both file format and delivery method');
            return;
        }
        this.isExporting = true;
        this.$refs.exportForm.submit();
    }
}">

    <!-- Header Section -->
    <div class="glass-header sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <!-- Title & Breadcrumbs -->
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center border border-primary-200">
                            <i data-lucide="receipt-text" class="w-5 h-5 text-primary-500"></i>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-display font-bold text-slate-900">Loan History</h1>
                    </div>
                    <nav class="flex items-center space-x-2 text-sm">
                        <a href="{{ route('dashboard') }}" class="text-[#7A7A7A] hover:text-primary-600 transition-colors">Dashboard</a>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300"></i>
                        <span class="text-[#4A4A4A] font-medium">Loan History</span>
                    </nav>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex items-center space-x-3">
                    <button @click="showFilterModal = true" class="btn-outline-primary px-5 py-2.5 rounded-xl flex items-center space-x-2">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                        <span>Filter</span>
                    </button>
                    <button @click="showExportModal = true" class="btn-primary px-5 py-2.5 rounded-xl flex items-center space-x-2">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span>Export</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @php
                $totalLoans = $loans->count();
                $totalAmount = $loans->sum('amount');
                $pendingLoans = $loans->where('active', 'Pending')->count();
                $approvedLoans = $loans->where('active', 'Processed')->count();
            @endphp
            
            <div class="stat-card rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#7A7A7A] text-sm font-medium mb-1">Total Applications</p>
                        <h3 class="text-3xl font-display font-bold text-slate-900">{{ $totalLoans }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-primary-50 flex items-center justify-center border border-primary-200">
                        <i data-lucide="file-text" class="w-7 h-7 text-primary-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#7A7A7A] text-sm font-medium mb-1">Total Amount</p>
                        <h3 class="text-3xl font-display font-bold text-slate-900">{{ Auth::user()->currency }}{{ number_format($totalAmount, 0) }}</h3>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-primary-50 flex items-center justify-center border border-primary-200">
                        <i data-lucide="dollar-sign" class="w-7 h-7 text-primary-500"></i>
                    </div>
                </div>
            </div>
            
            <div class="stat-card rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[#7A7A7A] text-sm font-medium mb-1">Status Overview</p>
                        <div class="flex items-center space-x-3 mt-2">
                            <span class="text-sm font-semibold text-alert-dark">{{ $pendingLoans }} Pending</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-sm font-semibold text-success-dark">{{ $approvedLoans }} Approved</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 rounded-2xl bg-champagne-50 flex items-center justify-center border border-champagne-200">
                        <i data-lucide="pie-chart" class="w-7 h-7 text-champagne-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
                </div>
                <input 
                    type="text" 
                    x-model="searchQuery"
                    placeholder="Search by loan purpose, amount, or type..." 
                    class="search-input w-full pl-12 pr-4 py-3.5 rounded-xl placeholder-slate-400 text-sm">
            </div>
        </div>

        <!-- Loans Table -->
        @if($loans->count() > 0)
        <div class="glass-card rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#7A7A7A] uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#7A7A7A] uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#7A7A7A] uppercase tracking-wider">Purpose</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#7A7A7A] uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#7A7A7A] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-[#7A7A7A] uppercase tracking-wider">Date Applied</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/30">
                        @foreach($loans as $loan)
                        <tr class="table-row-hover">
                            <td class="px-6 py-4">
                                <div class="icon-badge">
                                    <i data-lucide="landmark" class="w-5 h-5 text-primary-500"></i>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-900 font-semibold text-base">{{ Auth::user()->currency }}{{ number_format($loan->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-[#4A4A4A] text-sm truncate" title="{{ $loan->loan_purpose ?? 'N/A' }}">
                                        {{ Str::limit($loan->loan_purpose ?? 'N/A', 50) }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="duration-badge" x-text="formatDuration({{ $loan->duration ?? 0 }})"></span>
                            </td>
                            <td class="px-6 py-4">
                                <span 
                                    class="status-pill border"
                                    :class="getStatusColor('{{ $loan->active }}').bg + ' ' + getStatusColor('{{ $loan->active }}').text + ' ' + getStatusColor('{{ $loan->active }}').border">
                                    {{ $loan->active }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-[#4A4A4A] text-sm font-medium">{{ \Carbon\Carbon::parse($loan->created_at)->format('M d, Y') }}</p>
                                    <p class="text-slate-400 text-xs">{{ \Carbon\Carbon::parse($loan->created_at)->format('h:i A') }}</p>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="glass-card rounded-2xl p-12 text-center">
            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-slate-100 flex items-center justify-center border border-slate-200">
                <i data-lucide="file-question" class="w-10 h-10 text-slate-400"></i>
            </div>
            <h3 class="text-xl font-display font-bold text-slate-900 mb-2">No Loan Applications Found</h3>
            <p class="text-[#7A7A7A] mb-6 max-w-md mx-auto">You haven't applied for any loans yet. Start your loan application to access flexible financing options.</p>
            <a href="{{ route('loan') }}" class="btn-primary inline-flex items-center space-x-2 px-6 py-3 rounded-xl">
                <i data-lucide="plus-circle" class="w-5 h-5"></i>
                <span>Apply for a Loan</span>
            </a>
        </div>
        @endif

    </div>

    <!-- Filter Modal -->
    <div 
        x-show="showFilterModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click="showFilterModal = false" class="fixed inset-0 modal-overlay"></div>
            
            <div 
                x-show="showFilterModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative glass-card rounded-2xl p-6 w-full max-w-md z-10">
                
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-display font-bold text-slate-900">Filter Loans</h3>
                    <button @click="showFilterModal = false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-[#7A7A7A]"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-[#4A4A4A] mb-2">Date Range</label>
                        <input type="text" placeholder="Select date range" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A4A4A] mb-2">Status</label>
                        <select x-model="statusFilter" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
                            <option value="all">All statuses</option>
                            <option value="Processed">Processed</option>
                            <option value="Pending">Pending</option>
                            <option value="yes">Active</option>
                            <option value="No">Rejected</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A4A4A] mb-2">Sort by</label>
                        <select x-model="sortOrder" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
                            <option value="desc">Newest first</option>
                            <option value="asc">Oldest first</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#4A4A4A] mb-2">Items per page</label>
                        <select x-model="perPage" class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6">
                    <button @click="showFilterModal = false" class="flex-1 btn-outline-primary px-4 py-2.5 rounded-xl text-sm">
                        Cancel
                    </button>
                    <button @click="showFilterModal = false" class="flex-1 btn-primary px-4 py-2.5 rounded-xl text-sm">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div 
        x-show="showExportModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen px-4">
            <div @click="showExportModal = false" class="fixed inset-0 modal-overlay"></div>
            
            <div 
                x-show="showExportModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative glass-card rounded-2xl p-6 w-full max-w-md z-10">
                
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-display font-bold text-slate-900">Export Loans</h3>
                    <button @click="showExportModal = false" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-[#7A7A7A]"></i>
                    </button>
                </div>

                <form action="{{ route('exportloans') }}" method="POST" x-ref="exportForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#4A4A4A] mb-2">File Format</label>
                            <select name="format" x-model="exportFormat" required class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
                                <option value="">Select file type</option>
                                <option value="csv">CSV</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#4A4A4A] mb-2">Delivery Method</label>
                            <select name="method" x-model="exportMethod" required class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
                                <option value="">How do you want to receive this file?</option>
                                <option value="download">Download file</option>
                                <option value="email">Send file to email</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button 
                            type="submit"
                            :disabled="isExporting"
                            class="w-full btn-primary px-4 py-3 rounded-xl text-sm flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!isExporting">
                            <i data-lucide="download" class="w-4 h-4"></i>
                        </template>
                        <template x-if="isExporting">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </template>
                            <span x-text="isExporting ? 'Exporting...' : 'Export Loans'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // Initialize Lucide icons
    lucide.createIcons();
    
    // Re-initialize icons when Alpine updates the DOM
    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            setTimeout(() => lucide.createIcons(), 0);
        });
    });
</script>

@endsection

