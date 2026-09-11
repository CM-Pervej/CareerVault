@extends('layouts.admin.app')

@section('title',"{$user->name} | CareerVault")
@section('page_title','User Details')

@section('content')
<div class="mx-auto max-w-6xl space-y-5">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">

        <div class="flex items-center gap-4">

            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-2xl font-bold text-primary">
                {{ strtoupper(substr($user->name,0,1)) }}
            </div>

            <div class="min-w-0">
                <div class="cv-admin-label mb-1 text-primary">
                    People / Users / Profile
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="cv-admin-title text-3xl sm:text-4xl">
                        {{ $user->name }}
                    </h1>

                    @if($user->role==='super_admin')
                        <span class="badge badge-error gap-1">
                            <i class="fa-solid fa-crown text-xs"></i>
                            Super Admin
                        </span>
                    @elseif($user->role==='admin')
                        <span class="badge badge-warning gap-1">
                            <i class="fa-solid fa-shield-halved text-xs"></i>
                            Admin
                        </span>
                    @else
                        <span class="badge badge-ghost">
                            User
                        </span>
                    @endif
                </div>

                <p class="mt-1 text-sm text-base-content/50">
                    {{ $user->email }}
                </p>
            </div>

        </div>

        <div class="flex flex-wrap gap-2">

            @can('update',$user)
                <a
                    href="{{ route('admin.users.edit',$user) }}"
                    class="btn btn-primary gap-2"
                >
                    <i class="fa-solid fa-pen"></i>
                    Edit User
                </a>
            @endcan

            <a
                href="{{ route('admin.users.index') }}"
                class="btn btn-ghost gap-2"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Users
            </a>

        </div>

    </div>

    {{-- Account Status --}}
    <div class="rounded-2xl border border-base-300 bg-base-100 p-5">

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-4">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl
                    {{ $user->status==='active' ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
                    <i class="fa-solid {{ $user->status==='active' ? 'fa-user-check' : 'fa-user-slash' }}"></i>
                </div>

                <div>
                    <div class="font-bold">
                        Account {{ ucfirst($user->status) }}
                    </div>

                    <div class="text-sm text-base-content/50">
                        {{ $user->status==='active'
                            ? 'This account is currently allowed to access CareerVault.'
                            : 'This account is currently prevented from logging in.'
                        }}
                    </div>
                </div>

            </div>

            <div>
                @if($user->status==='active')
                    <span class="badge badge-success badge-outline gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-success"></span>
                        Active
                    </span>
                @else
                    <span class="badge badge-error badge-outline gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-error"></span>
                        Inactive
                    </span>
                @endif
            </div>

        </div>

    </div>

    {{-- Main Information --}}
    <div class="grid gap-5 lg:grid-cols-3">

        {{-- Identity --}}
        <div class="rounded-2xl border border-base-300 bg-base-100 lg:col-span-2">

            <div class="border-b border-base-300 px-5 py-4">
                <div class="cv-admin-label text-primary">
                    Identity
                </div>

                <h2 class="mt-1 text-lg font-bold">
                    Account information
                </h2>
            </div>

            <div class="grid gap-5 p-5 sm:grid-cols-2">

                <div>
                    <div class="cv-admin-label opacity-40">
                        Full Name
                    </div>

                    <div class="mt-2 font-semibold">
                        {{ $user->name }}
                    </div>
                </div>

                <div>
                    <div class="cv-admin-label opacity-40">
                        Email Address
                    </div>

                    <div class="mt-2 break-all font-medium">
                        {{ $user->email }}
                    </div>
                </div>

                <div>
                    <div class="cv-admin-label opacity-40">
                        Profile Slug
                    </div>

                    <div class="cv-admin-mono mt-2 text-sm">
                        {{ $user->slug ?: '—' }}
                    </div>
                </div>

                <div>
                    <div class="cv-admin-label opacity-40">
                        User ID
                    </div>

                    <div class="cv-admin-mono mt-2 text-sm">
                        #{{ $user->id }}
                    </div>
                </div>

            </div>

        </div>

        {{-- Access --}}
        <div class="rounded-2xl border border-base-300 bg-base-100">

            <div class="border-b border-base-300 px-5 py-4">
                <div class="cv-admin-label text-warning">
                    Security
                </div>

                <h2 class="mt-1 text-lg font-bold">
                    Access level
                </h2>
            </div>

            <div class="space-y-5 p-5">

                <div>
                    <div class="cv-admin-label opacity-40">
                        Role
                    </div>

                    <div class="mt-2">
                        @if($user->role==='super_admin')
                            <span class="badge badge-error gap-1">
                                <i class="fa-solid fa-crown text-xs"></i>
                                Super Admin
                            </span>
                        @elseif($user->role==='admin')
                            <span class="badge badge-warning gap-1">
                                <i class="fa-solid fa-shield-halved text-xs"></i>
                                Admin
                            </span>
                        @else
                            <span class="badge badge-ghost">
                                User
                            </span>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="cv-admin-label opacity-40">
                        Account Status
                    </div>

                    <div class="mt-2">
                        @if($user->status==='active')
                            <span class="badge badge-success badge-outline">
                                Active
                            </span>
                        @else
                            <span class="badge badge-error badge-outline">
                                Inactive
                            </span>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>

    {{-- Activity --}}
    <div class="rounded-2xl border border-base-300 bg-base-100">

        <div class="border-b border-base-300 px-5 py-4">
            <div class="cv-admin-label text-secondary">
                Activity
            </div>

            <h2 class="mt-1 text-lg font-bold">
                Account timeline
            </h2>
        </div>

        <div class="grid gap-5 p-5 sm:grid-cols-3">

            <div class="rounded-xl bg-base-200/60 p-4">
                <div class="flex items-center gap-2 text-base-content/40">
                    <i class="fa-solid fa-user-plus"></i>
                    <span class="cv-admin-label">
                        Registered
                    </span>
                </div>

                <div class="mt-3 font-semibold">
                    {{ $user->created_at->format('d M Y') }}
                </div>

                <div class="mt-1 text-xs text-base-content/40">
                    {{ $user->created_at->format('h:i A') }}
                    ·
                    {{ $user->created_at->diffForHumans() }}
                </div>
            </div>

            <div class="rounded-xl bg-base-200/60 p-4">
                <div class="flex items-center gap-2 text-base-content/40">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span class="cv-admin-label">
                        Last Login
                    </span>
                </div>

                @if($user->last_login_at)

                    <div class="mt-3 font-semibold">
                        {{ $user->last_login_at->format('d M Y') }}
                    </div>

                    <div class="mt-1 text-xs text-base-content/40">
                        {{ $user->last_login_at->format('h:i A') }}
                        ·
                        {{ $user->last_login_at->diffForHumans() }}
                    </div>

                @else

                    <div class="mt-3 font-semibold">
                        Never
                    </div>

                    <div class="mt-1 text-xs text-base-content/40">
                        No successful login recorded.
                    </div>

                @endif

            </div>

            <div class="rounded-xl bg-base-200/60 p-4">
                <div class="flex items-center gap-2 text-base-content/40">
                    <i class="fa-solid fa-clock"></i>
                    <span class="cv-admin-label">
                        Updated
                    </span>
                </div>

                <div class="mt-3 font-semibold">
                    {{ $user->updated_at->format('d M Y') }}
                </div>

                <div class="mt-1 text-xs text-base-content/40">
                    {{ $user->updated_at->format('h:i A') }}
                    ·
                    {{ $user->updated_at->diffForHumans() }}
                </div>
            </div>

        </div>

    </div>

    {{-- Danger Zone --}}
    @can('delete',$user)

        <div class="rounded-2xl border border-error/20 bg-error/[0.03]">

            <div class="border-b border-error/10 px-5 py-4">
                <div class="cv-admin-label text-error">
                    Danger Zone
                </div>

                <h2 class="mt-1 text-lg font-bold">
                    Account removal
                </h2>
            </div>

            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <div class="font-semibold">
                        Move this account to trash
                    </div>

                    <p class="mt-1 text-sm text-base-content/50">
                        The account will be soft deleted and can later be restored by a super administrator.
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.users.destroy',$user) }}"
                    onsubmit="return confirm('Are you sure you want to move {{ addslashes($user->name) }} to trash?')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-error btn-outline gap-2"
                    >
                        <i class="fa-regular fa-trash-can"></i>
                        Move to Trash
                    </button>
                </form>

            </div>

        </div>

    @endcan

</div>
@endsection