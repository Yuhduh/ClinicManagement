@php
    $title = $active === 'users' ? 'User Management Dashboard' : 'System Overview';
    $metrics = $metrics ?? [];
    $users = $users ?? collect();
    $editingUser = $editingUser ?? null;
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-4xl font-bold tracking-tight text-slate-900">{{ $title }}</h1>
            <p class="text-slate-600">Role-based access control and workforce administration.</p>
        </div>
        <a href="{{ route('admin.users') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">User Management</a>
    </div>

    <div class="grid gap-4 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Users</p>
            <p class="mt-2 text-4xl font-bold text-slate-900">{{ $metrics['total_users'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Doctors</p>
            <p class="mt-2 text-4xl font-bold text-slate-900">{{ $metrics['total_doctors'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Receptionists</p>
            <p class="mt-2 text-4xl font-bold text-slate-900">{{ $metrics['total_receptionists'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Active</p>
            <p class="mt-2 text-4xl font-bold text-slate-900">{{ $metrics['active_users'] ?? 0 }}</p>
        </div>
    </div>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Manage System Users (CRUD)</h2>
        <form method="POST" action="{{ $editingUser ? route('admin.users.update', $editingUser) : route('admin.users.store') }}" class="mt-4 space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
            @csrf
            @if ($editingUser)
                @method('PUT')
            @endif

            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                <input type="text" name="first_name" value="{{ old('first_name', $editingUser?->first_name) }}" placeholder="First Name" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="last_name" value="{{ old('last_name', $editingUser?->last_name) }}" placeholder="Last Name" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="text" name="middle_initial" value="{{ old('middle_initial', $editingUser?->middle_initial) }}" placeholder="Middle Initial" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <input type="email" name="email" value="{{ old('email', $editingUser?->email) }}" placeholder="Email" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
                <select name="role" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400">
                    @foreach (['admin', 'doctor', 'receptionist'] as $role)
                        <option value="{{ $role }}" @selected(old('role', $editingUser?->role ?? 'receptionist') === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                <select name="is_active" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400">
                    <option value="1" @selected(old('is_active', $editingUser?->is_active ?? true))>Active</option>
                    <option value="0" @selected(old('is_active', $editingUser?->is_active ?? true) === false || old('is_active', $editingUser?->is_active ?? true) === '0')>Inactive</option>
                </select>
                <input type="password" name="password" placeholder="{{ $editingUser ? 'Leave blank to keep password' : 'Password' }}" class="rounded-xl border-slate-200 text-sm focus:border-blue-400 focus:ring-blue-400" />
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">{{ $editingUser ? 'Update User' : 'Save User' }}</button>
                @if ($editingUser)
                    <a href="{{ route('admin.users') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                @endif
            </div>
        </form>

        <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-slate-600">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">Role</th>
                        <th class="px-4 py-3 font-medium">RBAC Toggle</th>
                        <th class="px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3 text-slate-900">{{ $user->display_name }}</td>
                            <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full px-2 py-1 text-xs font-medium {{ $user->role === 'admin' ? 'bg-slate-200 text-slate-700' : ($user->role === 'doctor' ? 'bg-blue-100 text-blue-700' : 'bg-violet-100 text-violet-700') }}">{{ ucfirst($user->role) }}</span>
                                    <span class="rounded-full px-2 py-1 text-xs font-medium {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-600">{{ $user->is_active ? 'Enabled' : 'Disabled' }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                <div class="flex items-center gap-2 text-sm">
                                    <a href="{{ route('admin.users', ['edit_user' => $user->id]) }}" class="text-blue-600 hover:text-blue-700">Edit</a>
                                    <button 
                                        type="button"
                                        data-confirm-delete 
                                        data-confirm-modal="delete-admin-user-{{ $user->id }}"
                                        data-confirm-message="Are you sure you want to delete {{ $user->display_name }}? This action cannot be undone."
                                        class="text-rose-600 hover:text-rose-700"
                                    >
                                        Delete
                                    </button>
                                    <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user) }}" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <x-confirm-modal 
                                        :id="'delete-admin-user-' . $user->id"
                                        title="Delete User"
                                        :message="'Are you sure you want to delete ' . $user->display_name . '? This action cannot be undone.'"
                                        confirmText="Delete"
                                        cancelText="Cancel"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-semibold text-slate-900">Create/Edit User (Modal Preview)</h2>
        <p class="mt-3 text-sm text-slate-500">Use the form above to create or edit users with role-based access control.</p>
    </section>
</div>
