@extends('layouts.admin.app')

@section('title','User Trash | CareerVault')
@section('page_title','User Trash')

@section('content')
<div class="mx-auto max-w-7xl space-y-5">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">

        <div>
            <div class="cv-admin-label mb-2 text-error">
                People / Users / Trash
            </div>

            <h1 class="cv-admin-title text-3xl sm:text-4xl">
                User Trash
            </h1>

            <p class="mt-1 text-sm text-base-content/60">
                Recover deleted users or permanently remove them from CareerVault.
            </p>
        </div>

        <div class="flex gap-2 self-start sm:self-auto">

            <a
                href="{{ route('admin.users.index') }}"
                class="btn btn-ghost gap-2"
            >
                <i class="fa-solid fa-arrow-left"></i>
                All Users
            </a>

        </div>

    </div>


    {{-- Warning --}}
    <div class="alert alert-warning shadow-sm">

        <i class="fa-solid fa-triangle-exclamation text-lg"></i>

        <div>
            <h3 class="font-semibold">
                About deleted users
            </h3>

            <p class="text-sm opacity-80">
                Restored users can sign in normally. Permanently deleted users
                cannot be recovered.
            </p>
        </div>

    </div>


    {{-- Summary --}}
    <div class="grid gap-4 sm:grid-cols-2">

        <div class="rounded-xl border border-base-300 bg-base-100 p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="cv-admin-label opacity-50">
                        Deleted Users
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $users->total() }}
                    </p>
                </div>

                <div class="grid h-11 w-11 place-items-center rounded-xl bg-error/10 text-error">
                    <i class="fa-solid fa-trash-can"></i>
                </div>

            </div>

        </div>


        <div class="rounded-xl border border-base-300 bg-base-100 p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="cv-admin-label opacity-50">
                        Current Page
                    </p>

                    <p class="mt-2 text-3xl font-bold">
                        {{ $users->count() }}
                    </p>
                </div>

                <div class="grid h-11 w-11 place-items-center rounded-xl bg-warning/10 text-warning">
                    <i class="fa-solid fa-layer-group"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- Desktop Table --}}
    <div class="hidden overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm lg:block">

        <div class="overflow-x-auto">

            <table class="table">

                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Deleted</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr class="hover">

                            <td>

                                <div class="flex items-center gap-3">

                                    <div class="cv-avatar h-10 w-10 bg-error/10 text-error">
                                        {{ strtoupper(substr($user->name,0,1)) }}
                                    </div>

                                    <div>
                                        <div class="font-semibold">
                                            {{ $user->name }}
                                        </div>

                                        <div class="text-xs opacity-50">
                                            {{ $user->email }}
                                        </div>
                                    </div>

                                </div>

                            </td>


                            <td>

                                @if($user->role === 'super_admin')

                                    <span class="badge badge-error gap-1">
                                        <i class="fa-solid fa-crown text-[10px]"></i>
                                        Super Admin
                                    </span>

                                @elseif($user->role === 'admin')

                                    <span class="badge badge-warning gap-1">
                                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                                        Admin
                                    </span>

                                @else

                                    <span class="badge badge-ghost">
                                        User
                                    </span>

                                @endif

                            </td>


                            <td>

                                <span class="badge badge-error badge-outline">
                                    Deleted
                                </span>

                            </td>


                            <td>

                                <div class="text-sm">
                                    {{ $user->deleted_at?->format('M d, Y') }}
                                </div>

                                <div class="text-xs opacity-50">
                                    {{ $user->deleted_at?->format('h:i A') }}
                                </div>

                            </td>


                            <td>

                                <div class="flex justify-end gap-2">

                                    @can('restore',$user)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.restore',$user) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-success btn-outline gap-2"
                                            >
                                                <i class="fa-solid fa-rotate-left"></i>
                                                Restore
                                            </button>
                                        </form>

                                    @endcan


                                    @can('forceDelete',$user)

                                        <form
                                            method="POST"
                                            action="{{ route('admin.users.force-delete',$user) }}"
                                            onsubmit="return confirm('Permanently delete {{ addslashes($user->name) }}? This action cannot be undone.')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-error btn-outline gap-2"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>
                                                Delete Forever
                                            </button>
                                        </form>

                                    @endcan

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5">

                                <div class="flex flex-col items-center justify-center py-16 text-center">

                                    <div class="grid h-16 w-16 place-items-center rounded-2xl bg-success/10 text-2xl text-success">
                                        <i class="fa-solid fa-trash-can-arrow-up"></i>
                                    </div>

                                    <h3 class="mt-4 text-lg font-bold">
                                        Trash is empty
                                    </h3>

                                    <p class="mt-1 max-w-md text-sm opacity-50">
                                        Deleted users will appear here and can be restored
                                        by a super administrator.
                                    </p>

                                    <a
                                        href="{{ route('admin.users.index') }}"
                                        class="btn btn-primary mt-5 gap-2"
                                    >
                                        <i class="fa-solid fa-users"></i>
                                        View Users
                                    </a>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Mobile Cards --}}
    <div class="space-y-3 lg:hidden">

        @forelse($users as $user)

            <div class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm">

                <div class="flex items-start justify-between gap-3">

                    <div class="flex min-w-0 items-center gap-3">

                        <div class="cv-avatar h-11 w-11 shrink-0 bg-error/10 text-error">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>

                        <div class="min-w-0">

                            <h3 class="truncate font-semibold">
                                {{ $user->name }}
                            </h3>

                            <p class="truncate text-xs opacity-50">
                                {{ $user->email }}
                            </p>

                        </div>

                    </div>

                    <span class="badge badge-error badge-outline shrink-0">
                        Deleted
                    </span>

                </div>


                <div class="mt-4 grid grid-cols-2 gap-3 border-y border-base-300 py-3">

                    <div>
                        <p class="cv-admin-label opacity-40">
                            Role
                        </p>

                        <p class="mt-1 text-sm font-medium">
                            {{ str_replace('_',' ',ucwords($user->role)) }}
                        </p>
                    </div>

                    <div>
                        <p class="cv-admin-label opacity-40">
                            Deleted
                        </p>

                        <p class="mt-1 text-sm font-medium">
                            {{ $user->deleted_at?->format('M d, Y') }}
                        </p>
                    </div>

                </div>


                <div class="mt-3 grid grid-cols-2 gap-2">

                    @can('restore',$user)

                        <form
                            method="POST"
                            action="{{ route('admin.users.restore',$user) }}"
                        >
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-sm btn-success btn-outline gap-2"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                                Restore
                            </button>
                        </form>

                    @endcan


                    @can('forceDelete',$user)

                        <form
                            method="POST"
                            action="{{ route('admin.users.force-delete',$user) }}"
                            onsubmit="return confirm('Permanently delete {{ addslashes($user->name) }}? This action cannot be undone.')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-sm btn-error btn-outline gap-2"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                                Delete Forever
                            </button>
                        </form>

                    @endcan

                </div>

            </div>

        @empty

            <div class="rounded-xl border border-base-300 bg-base-100 px-5 py-16 text-center shadow-sm">

                <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-success/10 text-2xl text-success">
                    <i class="fa-solid fa-trash-can-arrow-up"></i>
                </div>

                <h3 class="mt-4 text-lg font-bold">
                    Trash is empty
                </h3>

                <p class="mt-1 text-sm opacity-50">
                    There are no deleted users.
                </p>

            </div>

        @endforelse

    </div>


    {{-- Pagination --}}
    @if($users->hasPages())

        <div class="pt-1">
            {{ $users->links() }}
        </div>

    @endif

</div>
@endsection