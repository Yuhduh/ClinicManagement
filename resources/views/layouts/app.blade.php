<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#f3f6fb] text-slate-800">
        @auth
            <aside class="fixed left-0 top-0 h-screen w-64 border-r border-[#dbe3ee] bg-[#f8fbff]">
                <div class="flex h-full flex-col">
                    <div class="border-b border-[#dbe3ee] px-4 py-4">
                        <div class="flex items-start gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#2463eb] text-white shadow-sm">
                                <x-application-logo class="text-lg" />
                            </div>
                            <div>
                                <p class="text-[22px] font-semibold leading-6 tracking-tight text-[#111827]">CMS</p>
                                <p class="mt-0.5 text-[11px] text-[#7184a0]">Clinic Management</p>
                            </div>
                        </div>
                    </div>

                    <nav class="space-y-1.5 px-3 py-3 text-sm">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#e7f0ff] text-[#2463eb]' : 'text-[#4a5f7d] hover:bg-[#eef4ff]' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 13.5V20h6v-6.5H4Zm0-9V11h6V4.5H4Zm10 0V9h6V4.5h-6Zm0 8V20h6v-7.5h-6Z" /></svg>
                            <span>Dashboard</span>
                        </a>

                        @if (in_array(auth()->user()->role, ['doctor', 'receptionist'], true))
                            <a href="{{ route('appointments.index') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('appointments.*') ? 'bg-[#e7f0ff] text-[#2463eb]' : 'text-[#4a5f7d] hover:bg-[#eef4ff]' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3.75V6m8-2.25V6M4.5 10.5h15M6 5.25h12A1.5 1.5 0 0119.5 6.75v11.5A1.5 1.5 0 0118 19.75H6a1.5 1.5 0 01-1.5-1.5V6.75A1.5 1.5 0 016 5.25z" /></svg>
                                <span>Appointments</span>
                            </a>
                            <a href="{{ route('patients.index') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('patients.*') ? 'bg-[#e7f0ff] text-[#2463eb]' : 'text-[#4a5f7d] hover:bg-[#eef4ff]' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 7a3 3 0 11-6 0 3 3 0 016 0Zm-9 11a6 6 0 1112 0H6Z" /></svg>
                                <span>Patients</span>
                            </a>
                        @endif

                        @if (auth()->user()->role === 'doctor')
                            <a href="{{ route('prescriptions.index') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('prescriptions.*') ? 'bg-[#e7f0ff] text-[#2463eb]' : 'text-[#4a5f7d] hover:bg-[#eef4ff]' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M7.5 3.75h7.379a1.5 1.5 0 011.06.44l3.871 3.87a1.5 1.5 0 01.44 1.06V19.5A1.5 1.5 0 0120.75 21h-13.5a1.5 1.5 0 01-1.5-1.5v-15A1.5 1.5 0 017.5 3.75z" /></svg>
                                <span>Prescriptions</span>
                            </a>
                            <a href="{{ route('records.index') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('records.*') ? 'bg-[#e7f0ff] text-[#2463eb]' : 'text-[#4a5f7d] hover:bg-[#eef4ff]' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7.5 3.75h6.879a1.5 1.5 0 011.06.44l3.371 3.371a1.5 1.5 0 01.44 1.06V20.25a1.5 1.5 0 01-1.5 1.5H7.5a1.5 1.5 0 01-1.5-1.5v-15a1.5 1.5 0 011.5-1.5zm2.25 8.25h4.5m-4.5 3h4.5" /></svg>
                                <span>Medical Records</span>
                            </a>
                        @endif

                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('users.index') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('users.*') ? 'bg-[#e7f0ff] text-[#2463eb]' : 'text-[#4a5f7d] hover:bg-[#eef4ff]' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 1115 0m-2.25-6a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" /></svg>
                                <span>User Management</span>
                            </a>
                            <a href="{{ route('reports.index') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 font-medium transition {{ request()->routeIs('reports.*') ? 'bg-[#e7f0ff] text-[#2463eb]' : 'text-[#4a5f7d] hover:bg-[#eef4ff]' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 19.5h16.5M7.5 16.5V9m4.5 7.5V6m4.5 10.5V12" /></svg>
                                <span>Reports &amp; Analytics</span>
                            </a>
                        @endif
                    </nav>

                    <div class="mt-auto border-t border-[#dbe3ee] p-3">
                        <div class="mb-2.5 flex items-center gap-2.5">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#2463eb] text-xs font-semibold text-white">{{ strtoupper(substr(auth()->user()->first_name, 0, 1)) . strtoupper(substr(auth()->user()->last_name ?? '', 0, 1)) }}</div>
                            <div>
                                <p class="text-sm font-medium text-[#111827]">{{ auth()->user()->display_name }}</p>
                                <p class="text-xs capitalize text-[#7184a0]">{{ auth()->user()->role }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="w-full rounded-lg border border-[#d7deea] bg-white px-3 py-1.5 text-sm font-medium text-[#111827] hover:bg-[#f8fbff]">Sign Out</button>
                        </form>
                    </div>
                </div>
            </aside>

            <main class="ml-64 min-h-screen bg-[#f3f6fb]">
                <div class="border-b border-[#dbe3ee] bg-[#f8fbff] px-6 py-3">
                    <div class="flex items-center justify-between gap-4">
                        <label class="relative block w-full max-w-[720px]">
                            <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-[#8aa0bf]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-4.3-4.3m1.8-5.2a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </span>
                            <input type="text" placeholder="{{ auth()->user()->role === 'admin' ? 'Search users...' : 'Search patients, appointments...' }}" class="w-full rounded-xl border border-[#d7deea] bg-white py-2 pl-10 pr-3 text-sm text-[#334155] placeholder:text-[#94a3b8] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
                        </label>
                        <p class="text-sm text-[#4e6483]">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                </div>

                <div class="mx-auto w-full max-w-7xl overflow-y-auto p-5">
                    {{ $slot }}
                </div>
            </main>
        @else
            {{ $slot }}
        @endauth

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Handle confirm delete buttons
                document.querySelectorAll('[data-confirm-delete]').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const modalId = this.getAttribute('data-confirm-modal');
                        const formId = 'delete-form-' + modalId.split('-').pop();
                        const form = document.getElementById(formId);
                        
                        // Store form for submission
                        window.pendingFormSubmit = { form: form };
                        
                        // Show modal
                        window.dispatchEvent(new CustomEvent('open-modal', { detail: modalId }));
                    });
                });

                // Handle confirm button clicks in modals
                document.querySelectorAll('[id$="-confirm"]').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const modalId = this.id.replace('-confirm', '');
                        
                        if (window.pendingFormSubmit && window.pendingFormSubmit.form) {
                            window.pendingFormSubmit.form.submit();
                        }
                        
                        window.dispatchEvent(new CustomEvent('close-modal', { detail: modalId }));
                    });
                });
            });
        </script>
    </body>
</html>
