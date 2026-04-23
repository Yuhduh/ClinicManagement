<x-app-layout>
    <section class="space-y-5 rounded-2xl bg-[#edf2f9] p-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-[28px] font-semibold leading-tight text-[#172033]">User Management</h1>
                <p class="text-sm text-[#4a5f7d]">Manage system users and access control</p>
            </div>
            <a href="{{ route('users.create') }}" class="rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">Create User</a>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-[#d8e0eb] bg-white shadow-[0_2px_8px_rgba(15,23,42,0.05)]">
            <table class="min-w-full divide-y divide-[#d8e0eb] text-sm">
                <thead class="bg-[#f8fbff] text-left text-[#4a5f7d]">
                    <tr>
                        <th class="px-4 py-2.5 font-medium">Name</th>
                        <th class="px-4 py-2.5 font-medium">Email</th>
                        <th class="px-4 py-2.5 font-medium">Role</th>
                        <th class="px-4 py-2.5 font-medium">Status</th>
                        <th class="px-4 py-2.5 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#d8e0eb] bg-white">
                    @foreach ($users as $user)
                        <tr class="odd:bg-white even:bg-[#fbfdff]">
                            <td class="px-4 py-2.5 text-[#172033]">{{ $user->name }}</td>
                            <td class="px-4 py-2.5 text-[#4a5f7d]">{{ $user->email }}</td>
                            <td class="px-4 py-2.5 capitalize text-[#4a5f7d]">{{ $user->role }}</td>
                            <td class="px-4 py-2.5">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $user->is_active ? 'bg-[#dff6ea] text-[#198754]' : 'bg-[#fde8e8] text-[#dc3545]' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="rounded-lg border border-[#d7deea] bg-white px-3 py-1.5 text-sm font-medium text-[#4a5f7d] hover:bg-[#f8fbff]">View</a>
                                    <a href="{{ route('users.edit', $user) }}" class="rounded-lg border border-[#b8cef8] bg-[#eef4ff] px-3 py-1.5 text-sm font-medium text-[#2463eb] hover:bg-[#e1ebff]">Edit</a>
                                    <form method="POST" action="{{ route('users.destroy', $user) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-[#f5c2c7] bg-[#fde8e8] px-3 py-1.5 text-sm font-medium text-[#dc3545] hover:bg-[#fbd5d9]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </section>
</x-app-layout>
