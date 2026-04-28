<section class="rounded-2xl border border-[#d8e0eb] bg-white p-4 shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
    <div class="mb-3 flex items-center justify-between">
        <h2 class="text-lg font-medium text-[#172033]">Recent Staff Accounts</h2>
        <a href="{{ route('users.index') }}" class="rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">Manage Users</a>
    </div>

    <div class="overflow-x-auto rounded-xl border border-[#d8e0eb]">
        <table class="min-w-full divide-y divide-[#d8e0eb] text-sm">
            <thead class="bg-[#f8fbff] text-left text-[#4a5f7d]">
                <tr>
                    <th class="px-4 py-2.5 font-small">Name</th>
                    <th class="px-4 py-2.5 font-small">Email</th>
                    <th class="px-4 py-2.5 font-small">Role</th>
                    <th class="px-4 py-2.5 font-small">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#d8e0eb] bg-white">
                @forelse (($recentUsers ?? collect()) as $user)
                    <tr class="odd:bg-white even:bg-[#fbfdff]">
                        <td class="px-4 py-2.5 text-[#172033]">{{ $user->display_name }}</td>
                        <td class="px-4 py-2.5 text-[#4a5f7d]">{{ $user->email }}</td>
                        <td class="px-4 py-2.5 capitalize text-[#4a5f7d]">{{ $user->role }}</td>
                        <td class="px-4 py-2.5">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $user->is_active ? 'bg-[#dff6ea] text-[#198754]' : 'bg-[#fde8e8] text-[#dc3545]' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
