@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ ticketCategory: 'general' }" class="space-y-5">
    
    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />
    <x-error-alert />

    <!-- Page Header -->
    <div>
        <h1 class="text-xl font-bold text-[#0D1B23] mb-1">Customer Support</h1>
        <p class="text-sm text-[#7A7A7A]">Submit a ticket and our team will respond shortly.</p>
    </div>

    <!-- Support Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="bg-white border border-[#E0E5E5] rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-primary-500/15 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clock" class="w-4 h-4 text-primary-500"></i>
                </div>
                <div>
                    <p class="text-[10px] text-[#7A7A7A] uppercase tracking-wider font-semibold">Response Time</p>
                    <p class="text-sm font-bold text-[#0D1B23]">< 2 Hours</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#E0E5E5] rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-primary-500/15 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="users" class="w-4 h-4 text-primary-500"></i>
                </div>
                <div>
                    <p class="text-[10px] text-[#7A7A7A] uppercase tracking-wider font-semibold">Support Team</p>
                    <p class="text-sm font-bold text-[#0D1B23]">24/7 Available</p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#E0E5E5] rounded-lg p-4">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-primary-500/15 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="message-circle" class="w-4 h-4 text-primary-500"></i>
                </div>
                <div>
                    <p class="text-[10px] text-[#7A7A7A] uppercase tracking-wider font-semibold">Satisfaction</p>
                    <p class="text-sm font-bold text-[#0D1B23]">98.5%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Ticket Form -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-4 md:p-6">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 rounded-lg bg-primary-600 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="headphones" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-[#0D1B23]">Create a Support Ticket</h2>
                    <p class="text-xs text-[#7A7A7A]">Fill in the details below and we'll get back to you</p>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('enquiry') }}" method="post" class="space-y-5">
                @csrf

                <!-- Ticket Category -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-[#4A4A4A] flex items-center">
                        <i data-lucide="tag" class="w-3.5 h-3.5 mr-1.5 text-[#7A7A7A]"></i>
                        Ticket Category
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <button type="button" 
                                @click="ticketCategory = 'general'"
                                :class="ticketCategory === 'general' ? 'bg-primary-500/15 border-forest-500/50 text-primary-400' : 'bg-white border-[#E0E5E5] hover:border-[#C8D1D1] text-[#7A7A7A]'"
                                class="py-2.5 px-3 rounded-lg border transition-colors text-center">
                            <i data-lucide="help-circle" class="w-5 h-5 mx-auto mb-1.5"></i>
                            <p class="text-xs font-medium">General</p>
                        </button>
                        <button type="button" 
                                @click="ticketCategory = 'account'"
                                :class="ticketCategory === 'account' ? 'bg-primary-500/15 border-forest-500/50 text-primary-400' : 'bg-white border-[#E0E5E5] hover:border-[#C8D1D1] text-[#7A7A7A]'"
                                class="py-2.5 px-3 rounded-lg border transition-colors text-center">
                            <i data-lucide="user" class="w-5 h-5 mx-auto mb-1.5"></i>
                            <p class="text-xs font-medium">Account</p>
                        </button>
                        <button type="button" 
                                @click="ticketCategory = 'transaction'"
                                :class="ticketCategory === 'transaction' ? 'bg-primary-500/15 border-forest-500/50 text-primary-400' : 'bg-white border-[#E0E5E5] hover:border-[#C8D1D1] text-[#7A7A7A]'"
                                class="py-2.5 px-3 rounded-lg border transition-colors text-center">
                            <i data-lucide="arrow-left-right" class="w-5 h-5 mx-auto mb-1.5"></i>
                            <p class="text-xs font-medium">Transaction</p>
                        </button>
                        <button type="button" 
                                @click="ticketCategory = 'technical'"
                                :class="ticketCategory === 'technical' ? 'bg-primary-500/15 border-forest-500/50 text-primary-400' : 'bg-white border-[#E0E5E5] hover:border-[#C8D1D1] text-[#7A7A7A]'"
                                class="py-2.5 px-3 rounded-lg border transition-colors text-center">
                            <i data-lucide="settings" class="w-5 h-5 mx-auto mb-1.5"></i>
                            <p class="text-xs font-medium">Technical</p>
                        </button>
                    </div>
                    <input type="hidden" name="category" :value="ticketCategory">
                </div>

                <!-- Subject -->
                <div class="space-y-2">
                    <label for="subject" class="text-sm font-semibold text-[#4A4A4A]">Subject</label>
                    <input type="text" 
                           id="subject"
                           name="subject" 
                           placeholder="Brief description of your issue" 
                           required
                           class="w-full px-3 md:px-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] text-sm placeholder-gray-500 focus:outline-none focus:border-primary-500/50 focus:ring-1 focus:ring-forest-500/20 transition-colors">
                </div>

                <!-- Message -->
                <div class="space-y-2">
                    <label for="message" class="text-sm font-semibold text-[#4A4A4A]">Message</label>
                    <textarea id="message"
                              name="message" 
                              rows="5" 
                              placeholder="Provide detailed information about your request or issue. Include relevant transaction IDs, dates, or error messages if applicable."
                              required
                              class="w-full px-3 md:px-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] text-sm placeholder-gray-500 focus:outline-none focus:border-primary-500/50 focus:ring-1 focus:ring-forest-500/20 transition-colors resize-none"></textarea>
                    <p class="text-xs text-[#7A7A7A] flex items-center">
                        <i data-lucide="info" class="w-3 h-3 mr-1"></i>
                        Be as detailed as possible to help us assist you faster
                    </p>
                </div>

                <!-- Priority Level -->
                <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
                    <div class="flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="alert-circle" class="w-4 h-4 text-[#3e4e83]"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-[#0D1B23] mb-0.5">Priority Level</h4>
                            <p class="text-xs text-[#7A7A7A] mb-3">Select the urgency of your request</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="priority" value="low" checked class="peer sr-only">
                                    <div class="px-3 py-2 rounded-lg bg-white border border-[#E0E5E5] peer-checked:bg-emerald-500/15 peer-checked:border-emerald-500/40 transition-colors text-center">
                                        <p class="text-xs font-medium text-[#4A4A4A] peer-checked:text-emerald-300">Low</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="priority" value="medium" class="peer sr-only">
                                    <div class="px-3 py-2 rounded-lg bg-white border border-[#E0E5E5] peer-checked:bg-amber-500/15 peer-checked:border-amber-500/40 transition-colors text-center">
                                        <p class="text-xs font-medium text-[#4A4A4A]">Medium</p>
                                    </div>
                                </label>
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="priority" value="high" class="peer sr-only">
                                    <div class="px-3 py-2 rounded-lg bg-white border border-[#E0E5E5] peer-checked:bg-red-500/15 peer-checked:border-red-500/40 transition-colors text-center">
                                        <p class="text-xs font-medium text-[#4A4A4A]">High</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full px-6 py-3.5 rounded-lg bg-primary-600 hover:bg-primary-500 transition-colors font-semibold text-sm text-white flex items-center justify-center space-x-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        <span>Submit Ticket</span>
                    </button>
                    <p class="text-xs text-center text-[#7A7A7A] mt-2">
                        We typically respond within 2 hours during business hours
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Support Channels -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        <div class="bg-white border border-[#E0E5E5] rounded-lg p-5 text-center hover:bg-[#DFE5E5] hover:border-[#C8D1D1] transition-colors">
            <div class="w-10 h-10 rounded-lg bg-primary-500/15 flex items-center justify-center mx-auto mb-3">
                <i data-lucide="mail" class="w-5 h-5 text-primary-500"></i>
            </div>
            <h3 class="font-semibold text-sm mb-1 text-[#0D1B23]">Email Support</h3>
            <p class="text-xs text-[#7A7A7A] mb-2">Get help via email</p>
            <a href="mailto:{{ $settings->contact_email ?? 'support@bank.com' }}" class="text-xs text-primary-500 hover:text-primary-400 font-medium">
                {{ $settings->contact_email ?? 'support@bank.com' }}
            </a>
        </div>

        <div class="bg-white border border-[#E0E5E5] rounded-lg p-5 text-center hover:bg-[#DFE5E5] hover:border-[#C8D1D1] transition-colors">
            <div class="w-10 h-10 rounded-lg bg-primary-500/15 flex items-center justify-center mx-auto mb-3">
                <i data-lucide="phone" class="w-5 h-5 text-primary-500"></i>
            </div>
            <h3 class="font-semibold text-sm mb-1 text-[#0D1B23]">Phone Support</h3>
            <p class="text-xs text-[#7A7A7A] mb-2">Talk to us directly</p>
            <a href="tel:{{ $settings->phone ?? '+1-800-BANK' }}" class="text-xs text-primary-500 hover:text-primary-400 font-medium">
                {{ $settings->phone ?? '+1 (800) BANK-HELP' }}
            </a>
        </div>

        <div class="bg-white border border-[#E0E5E5] rounded-lg p-5 text-center hover:bg-[#DFE5E5] hover:border-[#C8D1D1] transition-colors">
            <div class="w-10 h-10 rounded-lg bg-primary-500/15 flex items-center justify-center mx-auto mb-3">
                <i data-lucide="message-circle" class="w-5 h-5 text-primary-500"></i>
            </div>
            <h3 class="font-semibold text-sm mb-1 text-[#0D1B23]">Live Chat</h3>
            <p class="text-xs text-[#7A7A7A] mb-2">Chat with our team</p>
            <button class="text-xs text-primary-500 hover:text-primary-400 font-medium">
                Start Chat
            </button>
        </div>
    </div>

    <!-- FAQ Quick Links -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-[#0D1B23] mb-0.5">Frequently Asked Questions</h3>
                <p class="text-xs text-[#7A7A7A]">Find quick answers to common questions</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-primary-500/15 flex items-center justify-center flex-shrink-0">
                <i data-lucide="help-circle" class="w-4 h-4 text-primary-500"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <a href="#" class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3.5 hover:bg-[#DFE5E5] hover:border-[#C8D1D1] transition-colors group">
                <div class="flex items-start space-x-3">
                    <i data-lucide="arrow-right-left" class="w-4 h-4 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-medium text-[#0D1B23] mb-0.5 group-hover:text-primary-400 transition-colors">How do I make a transfer?</h4>
                        <p class="text-xs text-[#7A7A7A]">Learn about wire, local, and international transfers</p>
                    </div>
                </div>
            </a>

            <a href="#" class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3.5 hover:bg-[#DFE5E5] hover:border-[#C8D1D1] transition-colors group">
                <div class="flex items-start space-x-3">
                    <i data-lucide="shield" class="w-4 h-4 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-medium text-[#0D1B23] mb-0.5 group-hover:text-primary-400 transition-colors">How secure is my account?</h4>
                        <p class="text-xs text-[#7A7A7A]">Learn about our security measures</p>
                    </div>
                </div>
            </a>

            <a href="#" class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3.5 hover:bg-[#DFE5E5] hover:border-[#C8D1D1] transition-colors group">
                <div class="flex items-start space-x-3">
                    <i data-lucide="credit-card" class="w-4 h-4 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-medium text-[#0D1B23] mb-0.5 group-hover:text-primary-400 transition-colors">How do I apply for a card?</h4>
                        <p class="text-xs text-[#7A7A7A]">Get your virtual debit or credit card</p>
                    </div>
                </div>
            </a>

            <a href="#" class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3.5 hover:bg-[#DFE5E5] hover:border-[#C8D1D1] transition-colors group">
                <div class="flex items-start space-x-3">
                    <i data-lucide="clock" class="w-4 h-4 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-medium text-[#0D1B23] mb-0.5 group-hover:text-primary-400 transition-colors">Transaction processing times?</h4>
                        <p class="text-xs text-[#7A7A7A]">Understand when funds will arrive</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

@endsection

