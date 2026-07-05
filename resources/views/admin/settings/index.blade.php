@extends('appAdminLayout.layout')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')

    @php
        $admin = $admin ?? (Auth::user() ?? (object) ['name' => 'Admin', 'email' => 'admin@example.com']);
        $store = $store ?? [
            'name' => config('app.name', 'Optio'),
            'currency' => 'USD',
            'support_email' => 'support@example.com',
            'timezone' => 'Africa/Casablanca',
        ];
        $notifications = $notifications ?? [
            'new_order' => true,
            'low_stock' => true,
            'new_customer' => false,
            'weekly_summary' => true,
        ];

        // Reopen whichever tab was just submitted (works for both old-input redirects
        // and validation-error redirects, since 'tab' rides along as a hidden field).
        $activeTab = old('tab', 'general');
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Tab rail -->
        <div class="lg:col-span-1">
            <nav class="bg-white border border-[#19140010] rounded-2xl p-2 space-y-1" id="settings-tabs">
                <button type="button" data-tab="general"
                    class="settings-tab w-full text-left flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ $activeTab === 'general' ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    General
                </button>
                <button type="button" data-tab="store"
                    class="settings-tab w-full text-left flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ $activeTab === 'store' ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 9.5L12 3l9 6.5M5 10v9a1 1 0 001 1h12a1 1 0 001-1v-9" />
                    </svg>
                    Store Details
                </button>
                <button type="button" data-tab="notifications"
                    class="settings-tab w-full text-left flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ $activeTab === 'notifications' ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Notifications
                </button>
                <button type="button" data-tab="security"
                    class="settings-tab w-full text-left flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all {{ $activeTab === 'security' ? 'bg-black text-white' : 'text-gray-500 hover:text-[#1b1b18] hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3l8 4v5c0 5-3.5 8.5-8 9-4.5-.5-8-4-8-9V7l8-4z" />
                    </svg>
                    Security
                </button>
            </nav>
        </div>

        <!-- Tab panels -->
        <div class="lg:col-span-3 space-y-6">

            <!-- General -->
            <section data-panel="general"
                class="settings-panel bg-white border border-[#19140010] rounded-2xl p-7 {{ $activeTab === 'general' ? '' : 'hidden' }}">
                <h2 class="text-sm font-semibold text-[#1b1b18] mb-1">Your profile</h2>
                <p class="text-xs text-gray-400 mb-6">This is how you appear to other admins.</p>

                <form method="POST" action="{{ route('admin.settings.profile.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tab" value="general">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-lg font-bold text-[#1b1b18]">
                            {{ Str::upper(Str::substr($admin->name ?? 'A', 0, 1)) }}
                        </div>
                        <button type="button"
                            class="text-xs font-semibold text-gray-600 bg-gray-50 hover:bg-gray-100 border border-[#19140010] rounded-xl px-3.5 py-2 transition-colors">
                            Change photo
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-medium text-gray-500 mb-1.5 block">Full name</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name ?? '') }}"
                                class="w-full text-sm bg-white border border-[#19140010] rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 mb-1.5 block">Email address</label>
                            <input type="email" name="email" value="{{ old('email', $admin->email ?? '') }}"
                                class="w-full text-sm bg-white border border-[#19140010] rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="text-xs font-semibold text-white bg-[#1b1b18] hover:bg-black rounded-xl px-4 py-2.5 transition-colors">
                            Save changes
                        </button>
                    </div>
                </form>
            </section>

            <!-- Store details -->
            <section data-panel="store"
                class="settings-panel bg-white border border-[#19140010] rounded-2xl p-7 {{ $activeTab === 'store' ? '' : 'hidden' }}">
                <h2 class="text-sm font-semibold text-[#1b1b18] mb-1">Store details</h2>
                <p class="text-xs text-gray-400 mb-6">Information used across invoices, emails, and the storefront.</p>

                <form method="POST" action="{{ route('admin.settings.store.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tab" value="store">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="text-xs font-medium text-gray-500 mb-1.5 block">Store name</label>
                            <input type="text" name="name" value="{{ old('name', $store['name']) }}"
                                class="w-full text-sm bg-white border border-[#19140010] rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 mb-1.5 block">Support email</label>
                            <input type="email" name="support_email"
                                value="{{ old('support_email', $store['support_email']) }}"
                                class="w-full text-sm bg-white border border-[#19140010] rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 mb-1.5 block">Currency</label>
                            <select name="currency"
                                class="w-full text-sm bg-white border border-[#19140010] rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                                @foreach (['USD', 'EUR', 'GBP', 'MAD'] as $currency)
                                    <option value="{{ $currency }}" @selected(old('currency', $store['currency']) === $currency)>{{ $currency }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-gray-500 mb-1.5 block">Timezone</label>
                            <input type="text" name="timezone" value="{{ old('timezone', $store['timezone']) }}"
                                class="w-full text-sm bg-white border border-[#19140010] rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="text-xs font-semibold text-white bg-[#1b1b18] hover:bg-black rounded-xl px-4 py-2.5 transition-colors">
                            Save changes
                        </button>
                    </div>
                </form>
            </section>

            <!-- Notifications -->
            <section data-panel="notifications"
                class="settings-panel bg-white border border-[#19140010] rounded-2xl p-7 {{ $activeTab === 'notifications' ? '' : 'hidden' }}">
                <h2 class="text-sm font-semibold text-[#1b1b18] mb-1">Notification preferences</h2>
                <p class="text-xs text-gray-400 mb-6">Choose what you get notified about by email.</p>

                <form method="POST" action="{{ route('admin.settings.notifications.update') }}"
                    class="divide-y divide-[#19140010]">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tab" value="notifications">

                    @foreach ([
            'new_order' => ['New order placed', 'Get notified the moment a customer checks out.'],
            'low_stock' => ['Low stock alerts', 'Heads up when a product drops below its threshold.'],
            'new_customer' => ['New customer signup', 'Know when someone new joins your store.'],
            'weekly_summary' => ['Weekly performance summary', 'A digest of revenue, orders, and top products.'],
        ] as $key => $copy)
                        <label class="flex items-center justify-between gap-4 py-4 cursor-pointer">
                            <div>
                                <p class="text-xs font-semibold text-[#1b1b18]">{{ $copy[0] }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $copy[1] }}</p>
                            </div>
                            <input type="checkbox" name="notifications[{{ $key }}]" value="1"
                                @checked($notifications[$key] ?? false) class="peer sr-only">
                            <span
                                class="relative inline-flex h-5 w-9 shrink-0 items-center rounded-full bg-gray-200 peer-checked:bg-[#1b1b18] transition-colors after:absolute after:left-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-transform peer-checked:after:translate-x-4"
                                onclick="this.previousElementSibling.checked = !this.previousElementSibling.checked; this.classList.toggle('bg-[#1b1b18]'); this.classList.toggle('after:translate-x-4');">
                            </span>
                        </label>
                    @endforeach

                    <div class="flex justify-end pt-5">
                        <button type="submit"
                            class="text-xs font-semibold text-white bg-[#1b1b18] hover:bg-black rounded-xl px-4 py-2.5 transition-colors">
                            Save preferences
                        </button>
                    </div>
                </form>
            </section>

            <!-- Security -->
            <section data-panel="security"
                class="settings-panel bg-white border border-[#19140010] rounded-2xl p-7 {{ $activeTab === 'security' ? '' : 'hidden' }}">
                <h2 class="text-sm font-semibold text-[#1b1b18] mb-1">Password</h2>
                <p class="text-xs text-gray-400 mb-6">Use a long, unique password to keep your account secure.</p>

                <form method="POST" action="{{ route('admin.settings.password.update') }}" class="space-y-5 max-w-md">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tab" value="security">
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">Current password</label>
                        <input type="password" name="current_password"
                            class="w-full text-sm bg-white border border-[#19140010] rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">New password</label>
                        <input type="password" name="password"
                            class="w-full text-sm bg-white border border-[#19140010] rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-gray-500 mb-1.5 block">Confirm new password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full text-sm bg-white border border-[#19140010] rounded-xl px-3.5 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300">
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit"
                            class="text-xs font-semibold text-white bg-[#1b1b18] hover:bg-black rounded-xl px-4 py-2.5 transition-colors">
                            Update password
                        </button>
                    </div>
                </form>

                <hr class="border-[#19140010] my-7">

                <h2 class="text-sm font-semibold text-rose-600 mb-1">Danger zone</h2>
                <p class="text-xs text-gray-400 mb-4">These actions are irreversible — proceed with care.</p>
                <button type="button"
                    class="text-xs font-semibold text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-xl px-4 py-2.5 transition-colors">
                    Delete account
                </button>
            </section>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.settings-tab').forEach((btn) => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.settings-tab').forEach((b) => {
                    b.classList.remove('bg-black', 'text-white');
                    b.classList.add('text-gray-500');
                });
                btn.classList.add('bg-black', 'text-white');
                btn.classList.remove('text-gray-500');

                const target = btn.dataset.tab;
                document.querySelectorAll('.settings-panel').forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.panel !== target);
                });
            });
        });
    </script>
@endpush
