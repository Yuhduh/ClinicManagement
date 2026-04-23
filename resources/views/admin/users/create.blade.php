<x-app-layout>
    <section class="rounded-2xl bg-[#edf2f9] p-6">
        <div class="rounded-2xl border border-[#d8e0eb] bg-white p-6 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
        <h1 class="text-[30px] font-semibold leading-tight text-[#172033]">Create User</h1>
        <form method="POST" action="{{ route('users.store') }}" class="mt-5 grid gap-4 md:grid-cols-2">
            @csrf
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Full Name" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[17px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[17px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
            <select name="role" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[17px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]">
                @foreach (['admin', 'doctor', 'receptionist'] as $role)
                    <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            <select name="is_active" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[17px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <input type="password" name="password" placeholder="Password" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[17px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />

            <div class="md:col-span-2 flex justify-end gap-2">
                <a href="{{ route('users.index') }}" class="rounded-xl border border-[#d7deea] bg-white px-5 py-2.5 text-[17px] font-medium text-[#4a5f7d] hover:bg-[#f8fbff]">Cancel</a>
                <button type="submit" class="rounded-xl bg-[#2463eb] px-5 py-2.5 text-[17px] font-medium text-white hover:bg-[#1f54c9]">Save User</button>
            </div>
        </form>
        </div>
    </section>
</x-app-layout>
