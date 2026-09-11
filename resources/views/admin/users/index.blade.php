@extends('layouts.admin.app')

@section('title','Users | CareerVault')
@section('page_title','Users')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="cv-admin-label text-primary mb-2">People / Directory</div>
            <h1 class="cv-admin-title text-3xl sm:text-4xl">Users</h1>
            <p class="mt-1 text-sm text-base-content/60">
                Manage CareerVault accounts, roles, and access status.
            </p>
        </div>
        
        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('admin.users.trash') }}"
                class="btn btn-ghost gap-2"
            >
                <i class="fa-solid fa-trash-can"></i>
                Trash
            </a>

            <a
                href="{{ route('admin.users.create') }}"
                class="btn btn-primary gap-2"
            >
                <i class="fa-solid fa-user-plus"></i>
                Add User
            </a>

        </div>
    </div>

    {{-- Summary --}}
    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50">Total</span>
                <i class="fa-solid fa-users text-primary"></i>
            </div>
            <div class="mt-2 text-2xl font-bold">{{ number_format(\App\Models\User::count()) }}</div>
            <div class="mt-1 text-xs text-base-content/50">Registered accounts</div>
        </div>

        <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50">Active</span>
                <i class="fa-solid fa-circle-check text-success"></i>
            </div>
            <div class="mt-2 text-2xl font-bold">
                {{ number_format(\App\Models\User::where('status','active')->count()) }}
            </div>
            <div class="mt-1 text-xs text-base-content/50">Currently enabled</div>
        </div>

        <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50">Admins</span>
                <i class="fa-solid fa-shield-halved text-warning"></i>
            </div>
            <div class="mt-2 text-2xl font-bold">
                {{ number_format(\App\Models\User::whereIn('role',['admin','super_admin'])->count()) }}
            </div>
            <div class="mt-1 text-xs text-base-content/50">Administrative accounts</div>
        </div>

        <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50">Inactive</span>
                <i class="fa-solid fa-user-slash text-error"></i>
            </div>
            <div class="mt-2 text-2xl font-bold">
                {{ number_format(\App\Models\User::where('status','inactive')->count()) }}
            </div>
            <div class="mt-1 text-xs text-base-content/50">Restricted accounts</div>
        </div>

        {{-- Trash --}}
        <a href="{{ route('admin.users.trash') }}" class="group rounded-2xl border border-base-300 bg-base-100 p-4 transition hover:border-error/30 hover:bg-error/[0.03]">
            <div class="flex items-center justify-between">
                <span class="cv-admin-label opacity-50 group-hover:text-error group-hover:opacity-100">Trash</span>
                <i class="fa-solid fa-trash-can text-error"></i>
            </div>
            <div class="mt-2 text-2xl font-bold">
                {{ number_format(\App\Models\User::onlyTrashed()->count()) }}
            </div>
            <div class="mt-1 text-xs text-base-content/50">Deleted accounts</div>
        </a>
    </div>

    {{-- Filters --}}
    <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="grid gap-3 md:grid-cols-12">

                <div class="md:col-span-6">
                    <label class="cv-admin-label mb-2 block opacity-50">Search</label>
                    <label class="input input-bordered flex items-center gap-2 w-full">
                        <i class="fa-solid fa-magnifying-glass opacity-40"></i>
                        <input
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Search name or email..."
                            class="grow"
                        >
                    </label>
                </div>

                <div class="md:col-span-2">
                    <label class="cv-admin-label mb-2 block opacity-50">Role</label>
                    <select name="role" class="select select-bordered w-full">
                        <option value="">All roles</option>
                        <option value="user" @selected($role==='user')>User</option>
                        <option value="admin" @selected($role==='admin')>Admin</option>
                        <option value="super_admin" @selected($role==='super_admin')>Super Admin</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="cv-admin-label mb-2 block opacity-50">Status</label>
                    <select name="status" class="select select-bordered w-full">
                        <option value="">All status</option>
                        <option value="active" @selected($status==='active')>Active</option>
                        <option value="inactive" @selected($status==='inactive')>Inactive</option>
                    </select>
                </div>

                <div class="flex items-end gap-2 md:col-span-2">
                    <button class="btn btn-primary flex-1">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    @if($search || $role || $status)
                        <a
                            href="{{ route('admin.users.index') }}"
                            class="btn btn-ghost btn-square"
                            title="Clear filters"
                        >
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>

    {{-- Results --}}
    <div class="rounded-2xl border border-base-300 bg-base-100 overflow-hidden">

        {{-- Table Header --}}
        <div class="flex flex-col gap-2 border-b border-base-300 px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-bold">User Directory</h2>
                <p class="text-xs text-base-content/50">
                    Showing {{ $users->firstItem() ?? 0 }}
                    – {{ $users->lastItem() ?? 0 }}
                    of {{ $users->total() }} results
                </p>
            </div>

            @if($search || $role || $status)
                <div class="flex flex-wrap gap-2">
                    @if($search)
                        <span class="badge badge-ghost gap-1">
                            Search: {{ $search }}
                        </span>
                    @endif

                    @if($role)
                        <span class="badge badge-ghost gap-1">
                            Role: {{ str_replace('_',' ',$role) }}
                        </span>
                    @endif

                    @if($status)
                        <span class="badge badge-ghost gap-1">
                            Status: {{ $status }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        {{-- Desktop Table --}}
        <div class="hidden overflow-x-auto lg:block">
            <table class="table cv-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Joined</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr class="cv-row">

                            {{-- User --}}
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="cv-avatar bg-primary/10 text-primary">
                                        {{ strtoupper(substr($user->name,0,1)) }}
                                    </div>

                                    <div class="min-w-0">
                                        <a
                                            href="{{ route('admin.users.show',$user) }}"
                                            class="cv-name-link block truncate max-w-[220px]"
                                        >
                                            {{ $user->name }}
                                        </a>

                                        <div class="text-xs text-base-content/50 truncate max-w-[240px]">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Role --}}
                            <td>
                                @if($user->role==='super_admin')
                                    <span class="badge badge-error badge-sm gap-1">
                                        <i class="fa-solid fa-crown text-[9px]"></i>
                                        Super Admin
                                    </span>
                                @elseif($user->role==='admin')
                                    <span class="badge badge-warning badge-sm gap-1">
                                        <i class="fa-solid fa-shield-halved text-[9px]"></i>
                                        Admin
                                    </span>
                                @else
                                    <span class="badge badge-ghost badge-sm">
                                        User
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td>
                                @if($user->status==='active')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-success">
                                        <span class="h-1.5 w-1.5 rounded-full bg-success"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-error">
                                        <span class="h-1.5 w-1.5 rounded-full bg-error"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            {{-- Last Login --}}
                            <td>
                                @if($user->last_login_at)
                                    <div class="text-sm">
                                        {{ $user->last_login_at->diffForHumans() }}
                                    </div>
                                    <div class="text-xs text-base-content/40">
                                        {{ $user->last_login_at->format('d M Y, h:i A') }}
                                    </div>
                                @else
                                    <span class="text-xs text-base-content/40">
                                        Never
                                    </span>
                                @endif
                            </td>

                            {{-- Joined --}}
                            <td>
                                <div class="text-sm">
                                    {{ $user->created_at->format('d M Y') }}
                                </div>
                                <div class="text-xs text-base-content/40">
                                    {{ $user->created_at->diffForHumans() }}
                                </div>
                            </td>

                            {{-- Actions --}}
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a
                                        href="{{ route('admin.users.show',$user) }}"
                                        class="btn btn-ghost btn-sm btn-square"
                                        title="View"
                                    >
                                        <i class="fa-regular fa-eye"></i>
                                    </a>

                                    @can('update',$user)
                                        <a
                                            href="{{ route('admin.users.edit',$user) }}"
                                            class="btn btn-ghost btn-sm btn-square"
                                            title="Edit"
                                        >
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    @endcan

                                    @can('delete',$user)
                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.destroy',$user) }}"
                                            onsubmit="return confirm('Move this user to trash?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-ghost btn-sm btn-square text-error"
                                                title="Delete"
                                            >
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="py-16 text-center">
                                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-base-200">
                                        <i class="fa-solid fa-users-slash text-xl opacity-40"></i>
                                    </div>

                                    <h3 class="font-bold">No users found</h3>

                                    <p class="mt-1 text-sm text-base-content/50">
                                        Try changing your search or filters.
                                    </p>

                                    @if($search || $role || $status)
                                        <a
                                            href="{{ route('admin.users.index') }}"
                                            class="btn btn-ghost btn-sm mt-4"
                                        >
                                            Clear filters
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="divide-y divide-base-300 lg:hidden">
            @forelse($users as $user)
                <div class="p-4">

                    <div class="flex items-start justify-between gap-3">

                        <div class="flex min-w-0 items-center gap-3">
                            <div class="cv-avatar bg-primary/10 text-primary">
                                {{ strtoupper(substr($user->name,0,1)) }}
                            </div>

                            <div class="min-w-0">
                                <a
                                    href="{{ route('admin.users.show',$user) }}"
                                    class="font-semibold hover:underline"
                                >
                                    {{ $user->name }}
                                </a>

                                <div class="truncate text-xs text-base-content/50">
                                    {{ $user->email }}
                                </div>
                            </div>
                        </div>

                        <div class="dropdown dropdown-end">
                            <button class="btn btn-ghost btn-sm btn-square">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>

                            <ul class="menu dropdown-content z-20 mt-1 w-40 rounded-xl border border-base-300 bg-base-100 p-2 shadow-xl">
                                <li>
                                    <a href="{{ route('admin.users.show',$user) }}">
                                        <i class="fa-regular fa-eye"></i>
                                        View
                                    </a>
                                </li>

                                @can('update',$user)
                                    <li>
                                        <a href="{{ route('admin.users.edit',$user) }}">
                                            <i class="fa-solid fa-pen"></i>
                                            Edit
                                        </a>
                                    </li>
                                @endcan

                                @can('delete',$user)
                                    <li>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.destroy',$user) }}"
                                            onsubmit="return confirm('Move this user to trash?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="w-full text-left text-error">
                                                <i class="fa-regular fa-trash-can"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </li>
                                @endcan
                            </ul>
                        </div>

                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        @if($user->role==='super_admin')
                            <span class="badge badge-error badge-sm">Super Admin</span>
                        @elseif($user->role==='admin')
                            <span class="badge badge-warning badge-sm">Admin</span>
                        @else
                            <span class="badge badge-ghost badge-sm">User</span>
                        @endif

                        @if($user->status==='active')
                            <span class="badge badge-success badge-outline badge-sm">Active</span>
                        @else
                            <span class="badge badge-error badge-outline badge-sm">Inactive</span>
                        @endif
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3 text-xs">
                        <div>
                            <div class="cv-admin-label opacity-40">Last login</div>
                            <div class="mt-1">
                                {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                            </div>
                        </div>

                        <div>
                            <div class="cv-admin-label opacity-40">Joined</div>
                            <div class="mt-1">
                                {{ $user->created_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>

                </div>
            @empty
                <div class="py-16 text-center">
                    <i class="fa-solid fa-users-slash text-2xl opacity-30"></i>
                    <h3 class="mt-3 font-bold">No users found</h3>
                    <p class="mt-1 text-sm text-base-content/50">
                        Try changing your search or filters.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="border-t border-base-300 px-4 py-4">
                {{ $users->links() }}
            </div>
        @endif

    </div>

</div>
@endsection