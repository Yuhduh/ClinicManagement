<x-app-layout>
    <section class="space-y-5 rounded-2xl bg-[#edf2f9] p-5">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-[28px] font-semibold leading-tight text-[#172033]">User Management</h1>
                <p class="text-sm text-[#4a5f7d]">Manage system users and access control</p>
            </div>
            <a href="{{ route('users.create') }}" class="rounded-lg bg-[#2463eb] px-4 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]">Create User</a>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('users.index') }}" class="flex gap-2">
            <input 
                type="text" 
                name="search" 
                value="{{ $search ?? '' }}" 
                placeholder="Search by name, email, or role..." 
                class="flex-1 rounded-lg border border-[#d7deea] bg-[#f8fbff] px-4 py-2 text-[17px] text-[#334155] focus:border-[#8fb4ff] focus:ring-2 focus:ring-[#d9e7ff]"
            />
            <button 
                type="submit" 
                class="rounded-lg bg-[#2463eb] px-6 py-2 text-sm font-medium text-white hover:bg-[#1f54c9]"
            >
                Search
            </button>
            @if($search)
                <a 
                    href="{{ route('users.index') }}" 
                    class="rounded-lg border border-[#d7deea] bg-white px-6 py-2 text-sm font-medium text-[#4a5f7d] hover:bg-[#f8fbff]"
                >
                    Clear
                </a>
            @endif
        </form>

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
                    @forelse ($users as $user)
                        <tr class="odd:bg-white even:bg-[#fbfdff]">
                            <td class="px-4 py-2.5 text-[#172033]">{{ $user->display_name }}</td>
                            <td class="px-4 py-2.5 text-[#4a5f7d]">{{ $user->email }}</td>
                            <td class="px-4 py-2.5 capitalize text-[#4a5f7d]">{{ $user->role }}</td>
                            <td class="px-4 py-2.5">
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-medium {{ $user->is_active ? 'bg-[#dff6ea] text-[#198754]' : 'bg-[#fde8e8] text-[#dc3545]' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                            </td>
                            <td class="px-4 py-2.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('users.edit', $user) }}" class="rounded-lg border border-[#d7deea] bg-white px-3 py-1.5 text-sm font-medium text-[#4a5f7d] hover:bg-[#f8fbff]">View</a>
                                    <a href="{{ route('users.edit', $user) }}" class="rounded-lg border border-[#b8cef8] bg-[#eef4ff] px-3 py-1.5 text-sm font-medium text-[#2463eb] hover:bg-[#e1ebff]">Edit</a>
                                    <button 
                                        type="button"
                                        data-confirm-delete 
                                        data-confirm-modal="delete-user-{{ $user->id }}"
                                        data-confirm-message="Are you sure you want to delete {{ $user->display_name }}? This action cannot be undone."
                                        class="rounded-lg border border-[#f5c2c7] bg-[#fde8e8] px-3 py-1.5 text-sm font-medium text-[#dc3545] hover:bg-[#fbd5d9]"
                                    >
                                        Delete
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('users.destroy', $user) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- Confirmation Modal for each user -->
                        <x-confirm-modal 
                            :id="'delete-user-' . $user->id"
                            title="Delete User"
                            :message="'Are you sure you want to delete ' . $user->display_name . '? This action cannot be undone.'"
                            confirmText="Delete"
                            cancelText="Cancel"
                        />
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-[#4a5f7d]">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </section>
</x-app-layout>
