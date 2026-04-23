<x-app-layout>
    <section class="rounded-2xl bg-[#edf2f9] p-6">
        <div class="rounded-2xl border border-[#d8e0eb] bg-white p-6 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
        <h1 class="text-[40px] font-semibold leading-tight text-[#172033]">Edit User</h1>
        <form method="POST" action="{{ route('users.update', $user) }}" class="mt-5 grid gap-4 md:grid-cols-2">
            @csrf
            @method('PUT')
            <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Full name" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[24px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
            <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[24px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />
            <select name="role" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[24px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]">
                @foreach (['admin', 'doctor', 'receptionist'] as $role)
                    <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
            <select name="is_active" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[24px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]">
                <option value="1" @selected((string) old('is_active', $user->is_active ? '1' : '0') === '1')>Active</option>
                <option value="0" @selected((string) old('is_active', $user->is_active ? '1' : '0') === '0')>Inactive</option>
            </select>
            <input type="password" name="password" placeholder="Leave blank to keep current password" class="rounded-xl border border-[#d7deea] bg-[#f8fbff] px-3 py-2.5 text-[24px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]" />

            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="rounded-xl bg-[#2463eb] px-5 py-2.5 text-[24px] font-medium text-white hover:bg-[#1f54c9]">Update User</button>
                <a href="{{ route('users.index') }}" class="rounded-xl border border-[#d7deea] bg-white px-5 py-2.5 text-[24px] font-medium text-[#4a5f7d] hover:bg-[#f8fbff]">Cancel</a>
            </div>
        </form>
        </div>
    </section>
</x-app-layout>
