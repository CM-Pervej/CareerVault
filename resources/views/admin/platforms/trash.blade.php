@extends('layouts.admin.app')

@section('title', 'Platform Trash | CareerVault')
@section('page_title', 'Trash')

@section('content')
<div class="space-y-3">
    {{-- Header --}}
    <header class="relative overflow-hidden sm:mb-6">
        <div class="relative flex flex-col gap-6 p-6 sm:p-0 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex items-start gap-4">                
                <div>
                    <div class="flex gap-5 size-11 shrink-0 items-center rounded-lg bg-error/10">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                            <i class="fa-solid fa-trash-can"></i>
                        </div>
                        <h1 class="cv-admin-title text-3xl sm:text-4xl whitespace-nowrap">Platforms Trash</h1>
                    </div>

                    <p class="mt-2 max-w-2xl text-sm leading-6 text-base-content/65 text-center sm:text-left">Deleted platforms remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records.</p>

                    <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-2">
                        <span class="badge badge-error badge-outline gap-1">
                            <i class="fa-solid fa-trash text-[10px]"></i> Soft Deleted
                        </span>

                        <span class="badge badge-warning badge-outline gap-1">
                            <i class="fa-solid fa-lock text-[10px]"></i> Super Admin Access
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.platforms.index') }}" class="btn btn-sm btn-ghost gap-2">
                <i class="fa-solid fa-arrow-left"></i> Back to Platforms
            </a>
        </div>
    </header>

    {{-- Summary --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-base-content/50">
                        Deleted Platforms
                    </p>
                    <p class="mt-1 text-2xl font-black">
                        {{ $platforms->total() }}
                    </p>
                </div>

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-base-content/50">
                        Recovery
                    </p>
                    <p class="mt-1 text-sm font-bold">
                        Super Admin Only
                    </p>
                    <p class="text-xs text-base-content/50">
                        Restore & permanent deletion
                    </p>
                </div>

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-warning/10 text-warning">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Trash Table --}}
    <div class="overflow-hidden rounded-2xl border border-base-300 bg-base-100 shadow-sm">

        {{-- Table Header --}}
        <div class="flex flex-col gap-3 border-b border-base-300 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 font-black">
                    <i class="fa-solid fa-clock-rotate-left text-base-content/50"></i>
                    Deleted Platforms
                </div>

                <p class="mt-0.5 text-xs text-base-content/50">
                    Deleted platforms remain recoverable until permanently removed.
                </p>
            </div>

            @if($platforms->total())
                <span class="badge badge-error badge-outline gap-1.5">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                    {{ $platforms->total() }} {{ Str::plural('item', $platforms->total()) }}
                </span>
            @endif
        </div>

        @if($platforms->count())

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-base-200/50 text-xs uppercase tracking-wider text-base-content/50">
                            <th>Platform</th>
                            <th>Job Type</th>
                            <th>Business Model</th>
                            <th>Deleted</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($platforms as $platform)
                            <tr class="hover:bg-base-200/40">

                                {{-- Platform --}}
                                <td>
                                    <div class="flex min-w-[250px] items-center gap-3">

                                        @if($platform->logo)
                                            <img
                                                src="{{ asset($platform->logo) }}"
                                                alt="{{ $platform->name }}"
                                                class="h-11 w-11 shrink-0 rounded-xl border border-base-300 bg-base-200 object-contain p-1"
                                            >
                                        @else
                                            <div
                                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-base-300 bg-base-200"
                                                style="{{ $platform->color ? 'color: '.$platform->color : '' }}"
                                            >
                                                <i class="{{ $platform->icon ?: 'fa-solid fa-globe' }}"></i>
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <div class="truncate font-bold">
                                                {{ $platform->name }}
                                            </div>

                                            @if($platform->official_name)
                                                <div class="mt-0.5 max-w-[300px] truncate text-xs text-base-content/50">
                                                    {{ $platform->official_name }}
                                                </div>
                                            @endif

                                            <div class="mt-1 text-[11px] text-base-content/40">
                                                {{ $platform->slug }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Job Type --}}
                                <td>
                                    @if($platform->job_type)
                                        <span class="badge badge-ghost badge-sm">
                                            {{ $platform->job_type }}
                                        </span>
                                    @else
                                        <span class="text-xs text-base-content/40">—</span>
                                    @endif
                                </td>

                                {{-- Business Model --}}
                                <td>
                                    @if($platform->business_model)
                                        <span class="badge badge-ghost badge-sm">
                                            {{ $platform->business_model }}
                                        </span>
                                    @else
                                        <span class="text-xs text-base-content/40">—</span>
                                    @endif
                                </td>

                                {{-- Deleted At --}}
                                <td>
                                    @if($platform->deleted_at)
                                        <div class="whitespace-nowrap">
                                            <div class="text-sm font-semibold">
                                                {{ $platform->deleted_at->format('d M Y') }}
                                            </div>

                                            <div class="text-xs text-base-content/50">
                                                {{ $platform->deleted_at->diffForHumans() }}
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-base-content/40">
                                            —
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td>
                                    <div class="flex justify-end gap-2">

                                        {{-- Restore --}}
                                        @can('restore', $platform)
                                            <button
                                                type="button"
                                                class="btn btn-success btn-sm btn-outline gap-2"
                                                title="Restore platform"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platforms.restore', $platform->slug) }}"
                                                data-action-method="PATCH"
                                                data-action-type="success"
                                                data-action-title="Restore Platform"
                                                data-action-description="Restore “{{ $platform->name }}” and bring its platform data back from trash."
                                                data-action-confirm="Restore Platform"
                                            >
                                                <i class="fa-solid fa-rotate-left"></i>
                                                <span class="hidden lg:inline">Restore</span>
                                            </button>
                                        @endcan

                                        {{-- Permanent Delete --}}
                                        @can('forceDelete', $platform)
                                            <button
                                                type="button"
                                                class="btn btn-error btn-sm btn-outline gap-2"
                                                title="Delete permanently"
                                                data-admin-action
                                                data-action-url="{{ route('admin.platforms.force-delete', $platform->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Permanently Delete Platform"
                                                data-action-description="Permanently delete “{{ $platform->name }}”. This will permanently remove the platform and its owned data. This action cannot be undone."
                                                data-action-confirm="Delete Permanently"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span class="hidden lg:inline">Delete Forever</span>
                                            </button>
                                        @endcan

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($platforms->hasPages())
                <div class="border-t border-base-300 px-5 py-4">
                    {{ $platforms->links() }}
                </div>
            @endif

        @else

            {{-- Empty State --}}
            <div class="px-6 py-20 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-base-200 text-base-content/40">
                    <i class="fa-solid fa-trash-can text-xl"></i>
                </div>

                <h3 class="mt-5 text-lg font-black">
                    Platform trash is empty
                </h3>

                <p class="mx-auto mt-1 max-w-md text-sm text-base-content/55">
                    Platforms moved to trash will appear here and can be restored
                    by a super administrator.
                </p>

                <a
                    href="{{ route('admin.platforms.index') }}"
                    class="btn btn-primary btn-sm mt-6 gap-2"
                >
                    <i class="fa-solid fa-layer-group"></i>
                    View Platforms
                </a>
            </div>

        @endif
    </div>

</div>
@endsection