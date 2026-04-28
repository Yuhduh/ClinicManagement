<x-guest-layout>
    <div class="w-full max-w-xl rounded-3xl border border-slate-200 bg-white/95 p-8 shadow-2xl shadow-slate-300/40 backdrop-blur">
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-3xl bg-sky-50 shadow-lg shadow-sky-200/60 ring-1 ring-sky-100">
                <x-application-logo class="text-4xl text-sky-600" />
            </div>
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">Clinic Management System</h1>
            <p class="mt-2 text-base text-slate-600">Sign in to manage your clinic operations.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label for="role" :value="__('Role')" />
                <select id="role" name="role" required class="mt-1 block w-full rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400">
                    <option value="">Select your role</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                    <option value="doctor" @selected(old('role') === 'doctor')>Doctor</option>
                    <option value="receptionist" @selected(old('role') === 'receptionist')>Receptionist</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-1 block w-full rounded-xl" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="mt-1 block w-full rounded-xl" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between pt-2">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2 text-sm text-slate-600">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-slate-600 underline hover:text-slate-900" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                Sign In
            </button>
        </form>
    </div>
</x-guest-layout>
