@extends('layouts.admin.app')

@section('title', $platformPage->name.' | Platform Pages | CareerVault')
@section('page_title', $platformPage->platform->name . ' / Page')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        .info-row {transition: background-color .15s ease;}
        .info-row:hover {background-color: hsl(var(--b2) / .5);}
        .action-card {transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;}
        .action-card:hover {transform: translateY(-2px); box-shadow: 0 12px 30px rgba(0,0,0,.06);}
    </style>
@endpush

@section('content')

<div class="space-y-6">
    {{-- =======================Hero=========================== --}}
    <section class="overflow-hidden border border-base-300 bg-base-100 shadow-sm">
        {{-- Hero --}}
        <div class="relative overflow-hidden" style="background: radial-gradient(circle at 15% 20%, {{ $platformPage->platform->color ?: '#6366f1' }} 0%, transparent 34%), radial-gradient(circle at 85% 0%, rgba(255,255,255,.18) 0%, transparent 30%), linear-gradient(135deg, {{ $platformPage->platform->color ?: '#4f46e5' }} 0%, #111827 100%);">
            @if($platformPage->platform->cover_image)
                <img src="{{ Storage::url($platformPage->platform->cover_image) }}" alt="{{ $platformPage->platform->name }}" class="absolute inset-0 size-full object-cover opacity-30">
            @endif

            <div class="absolute inset-0 bg-black/10"></div>

            {{-- Top navigation --}}
            <div class="relative flex items-center justify-between px-5 py-4 sm:px-7">
                <div class="flex min-w-0 items-center gap-2 text-sm text-white/70">
                    <a href="{{ route('admin.platforms.index') }}" class="transition hover:text-white">Platforms</a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-white/40"></i>
                    <a href="{{ route('admin.platforms.show', $platformPage->platform) }}" class="transition hover:text-white"> {{ $platformPage->platform->name }} </a>
                    <i class="fa-solid fa-chevron-right text-[10px] text-white/40"></i>
                    <span class="truncate font-semibold text-white"> {{ $platformPage->name }} </span>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2">
                    @if(!$platformPage->trashed())
                        <a href="{{ route('admin.platform-pages.edit', $platformPage) }}" class="btn btn-sm border-0 bg-white/15 text-white shadow-none backdrop-blur-md hover:bg-white/25">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                    @endif

                    @if($platformPage->trashed())
                        <a href="{{ route('admin.platform-pages.trash') }}" class="btn btn-warning btn-outline btn-sm gap-2">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                            <span class="hidden sm:inline">Trash</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.platform-pages.index') }}" class="btn btn-sm border-0 bg-white/15 text-white shadow-none backdrop-blur-md hover:bg-white/25">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span class="hidden sm:inline">Back</span>
                    </a>
                </div>
            </div>

            {{-- Identity --}}
            <div class="relative px-5 pb-8 pt-8 sm:px-8 sm:pb-10 sm:pt-10">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                    {{-- Logo --}}
                    <div class="relative w-fit shrink-0 self-start">
                        <div class="flex size-24 items-center justify-center overflow-hidden rounded-3xl border border-white/20 bg-white shadow-2xl sm:size-28">
                            @if($platformPage->platform->logo)
                                <img src="{{ Storage::url($platformPage->platform->logo) }}" alt="{{ $platformPage->platform->name }}" class="size-full object-contain p-3">
                            @elseif($platformPage->platform->icon)
                                    <i class="{{ $platformPage->platform->icon }} text-7xl" style="{{ $platformPage->platform->color ? 'color:'.$platformPage->platform->color : '' }}"></i>
                            @else
                                <span class="text-4xl font-black" style="color: {{ $platformPage->platform->color ?: '#4f46e5' }}">
                                    {{ strtoupper(substr($platformPage->platform->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <span class="absolute -bottom-2 -right-2 flex size-8 items-center justify-center rounded-full border-4 border-white/20 bg-white shadow-lg">
                            @if($platformPage->is_active)
                                <i class="fa-solid fa-check text-xs text-success"></i>
                            @else
                                <i class="fa-solid fa-pause text-xs text-error"></i>
                            @endif
                        </span>
                    </div>

                    {{-- Identity text --}}
                    <div class="min-w-0 flex-1 text-white">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h1 class="text-2xl font-black tracking-tight sm:text-3xl"> {{ $platformPage->name }} </h1>

                                @if($platformPage->trashed())
                                    <sup class="badge badge-warning">
                                        <i class="fa-solid fa-trash-can"></i> Trashed
                                    </sup>
                                @elseif($platformPage->is_active)
                                    <sup class="badge badge-success badge-outline">
                                        <i class="fa-solid fa-circle-check"></i> Active
                                    </sup>
                                @else
                                    <sup class="badge badge-ghost">
                                        <i class="fa-solid fa-circle-pause"></i> Inactive
                                    </sup>
                                @endif
                            </div>

                            <div class="text-sm font-medium text-white/60">
                                <span> {{ $platformPage->platform->name }} </span>
        
                                @if($platformPage->platform->official_name)
                                    <span> / {{ $platformPage->platform->official_name }} </span>
                                @endif
                            </div>
                        </div>

                        @if($platformPage->short_desc)
                            <p class="mt-4 max-w-3xl text-sm leading-6 text-white/75 sm:text-base text-justify"> {{ $platformPage->short_desc }} </p>
                        @endif

                        <div class="mt-5 flex flex-wrap gap-2">
                            @if($platformPage->platform->is_bangladesh_focused)
                                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                    <i class="fa-solid fa-location-dot text-[11px]"></i> Bangladesh focused
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                    <i class="fa-solid fa-location-dot text-[11px]"></i> International
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================Main Content====================== --}}
    <div class="grid gap-3 lg:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">
        {{-- =================Main Column======================= --}}
        <div class="space-y-3 p-1 sm:p-0">
            {{-- Official URL --}}
            @if($platformPage->url)
                <a href="{{ $platformPage->url }}" target="_blank" rel="noopener noreferrer" class="action-card block sm:rounded-r-lg border border-primary/20 bg-primary/5 p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex min-w-0 items-start gap-4">
                            <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary text-primary-content">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wider text-primary">Official Page</p>
                                <p class="mt-1 break-all font-semibold"> {{ $platformPage->url }} </p>
                            </div>
                        </div>

                        <i class="fa-solid fa-chevron-right shrink-0 text-primary"></i>
                    </div>
                </a>
            @endif

            {{-- Description --}}
            <section class="sm:rounded-r-lg border border-base-300 bg-base-100 shadow-sm text-center sm:text-start">
                <div class="border-b border-base-300 px-5 py-2 sm:py-4">
                    <div class="flex items-center justify-center sm:justify-start gap-2">
                        <i class="fa-solid fa-align-left text-primary"></i>
                        <h2 class="font-bold">About This Page</h2>
                    </div>
                </div>

                <div class="p-2 sm:p-5">
                    @if($platformPage->description)
                        <div class="whitespace-pre-line text-sm leading-7 text-base-content/75"> {{ $platformPage->description }} </div>
                    @else
                        <div class="flex items-center gap-3 rounded-xl border border-dashed border-base-300 p-4">
                            <i class="fa-regular fa-file-lines text-base-content/30"></i>
                            <p class="text-sm text-base-content/50"> No detailed description has been added. </p>
                        </div>
                    @endif
                </div>
            </section>
        </div>

        {{-- =======================Sidebar======================== --}}
        <aside class="space-y-3 p-1 sm:p-0">
            {{-- Page Details --}}
            <section class="sm:rounded-lg border border-base-300 bg-base-100 shadow-sm">
                <div class="border-b border-base-300 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                        <h2 class="font-bold">Page Details</h2>
                    </div>
                </div>

                <div class="divide-y divide-base-300">
                    {{-- Platform --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Platform</span>
                        <a href="{{ route('admin.platforms.show', $platformPage->platform) }}" class="text-right text-sm font-bold hover:text-primary" style="color: {{$platformPage->platform->color}}"> {{ $platformPage->platform->name }} </a>
                    </div>

                    {{-- Type --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Type</span>
                        <span class="text-right text-sm font-semibold"> {{ $platformPage->page_type ?: 'Not specified' }} </span>
                    </div>

                    {{-- Sort Order --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Sort Order</span>
                        <span class="font-mono text-sm font-semibold"> {{ $platformPage->sort_order }} </span>
                    </div>

                    {{-- Status --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Status</span>

                        @if($platformPage->trashed())
                            <span class="badge badge-warning badge-outline">Trashed</span>
                        @elseif($platformPage->is_active)
                            <span class="badge badge-success badge-outline">Active</span>
                        @else
                            <span class="badge badge-ghost">Inactive</span>
                        @endif
                    </div>

                    {{-- Last Verified --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Last Verified</span>
                        <span class="text-right text-sm font-semibold"> {{ $platformPage->last_verified_at?->format('M d, Y H:i') ?? 'Not verified' }} </span>
                    </div>

                    {{-- Created --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Created</span>
                        <span class="text-right text-sm font-semibold"> {{ $platformPage->created_at->format('M d, Y') }} </span>
                    </div>

                    {{-- Deleted --}}
                    @if($platformPage->trashed())
                        <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-base-content/60">Deleted</span>
                            <span class="text-right text-sm font-semibold text-warning"> {{ $platformPage->deleted_at?->format('M d, Y H:i') ?? 'Unknown' }} </span>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Platform --}}
            <section class="sm:rounded-lg border border-base-300 bg-base-100 p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="grid size-11 place-items-center rounded-xl bg-base-200">
                        @if($platformPage->platform?->logo)
                            <img src="{{ Storage::url($platformPage->platform->logo) }}" alt="{{ $platformPage->platform->name }}" class="size-full object-contain p-2">
                        @elseif($platformPage->platform->icon)
                                <i class="{{ $platformPage->platform?->icon ?: 'fa-solid fa-file-lines' }} text-4xl" style="color: {{$platformPage->platform->color}}"></i>
                        @else
                            <span class="text-4xl font-black" style="color: {{ $platformPage->platform->color ?: '#4f46e5' }}">
                                {{ strtoupper(substr($platformPage->platform->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <div>
                        <p class="text-xs text-base-content/50">Belongs to</p>
                        <a href="{{ route('admin.platforms.show', $platformPage->platform) }}" class="font-bold hover:text-primary" style="color: {{$platformPage->platform->color}}"> {{ $platformPage->platform->name }} </a>
                    </div>
                </div>

                <a href="{{ route('admin.platforms.show', $platformPage->platform) }}" class="btn btn-outline btn-sm mt-4 w-full gap-2">
                    View Platform <i class="fa-solid fa-arrow-right"></i>
                </a>
            </section>
        </aside>
    </div>

    {{-- ================= Active Page Danger Zone ==================== --}}
    @if(!$platformPage->trashed())
        <section class="border border-error/20 bg-error/5 p-5">
            <div class="flex flex-col gap-1">
                <div class="flex justify-between items-center gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-error"></i>
                        <h2 class="font-bold text-error">Delete this page</h2>
                    </div>

                    <div>
                        @can('delete',$platformPage)
                            <button
                                type="button"
                                class="btn btn-error btn-outline btn-sm gap-2"
                                title="Move page to trash"
                                data-admin-action
                                data-action-url="{{ route('admin.platform-pages.destroy',$platformPage->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Move Platform Page to Trash"
                                data-action-description="Move “{{ $platformPage->name }}” to trash. You can restore it later from the Trash page."
                                data-action-icon="fa-solid fa-trash-can"
                                data-action-confirm-icon="fa-solid fa-trash-can"
                                data-action-confirm-text="Move to Trash"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                                <span>Move to Trash</span>
                            </button>
                        @endcan
                    </div>
                </div>

                <p class="mt-1 text-sm text-base-content/60">The page will be moved to trash. Its content can still be restored later.</p>
            </div>
        </section>
    @endif

    {{-- =================== Trash Warning ======================= --}}
    @if($platformPage->trashed())
        <section class="border border-warning/30 bg-warning/10 p-5">
            <div class="flex flex-col gap-1">
                <div class="flex justify-between items-center gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-trash-can text-warning"></i>
                        <h2 class="font-bold text-warning">This page is in Trash</h2>
                    </div>
                    <div class="flex shrink-0 flex-wrap gap-2">
                        @can('restore', $platformPage)
                            <button
                                type="button"
                                class="btn btn-success gap-2"
                                title="Restore page"
                                data-admin-action
                                data-action-url="{{ route('admin.platform-pages.restore', $platformPage->slug) }}"
                                data-action-method="PATCH"
                                data-action-type="success"
                                data-action-title="Restore Platform Page"
                                data-action-description="Restore “{{ $platformPage->name }}” and return it to the active platform pages."
                                data-action-icon="fa-solid fa-rotate-left"
                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                data-action-confirm-text="Restore Page"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                                <span class="hidden lg:inline">Restore</span>
                            </button>
                        @endcan

                        @can('forceDelete', $platformPage)
                            <button
                                type="button"
                                class="btn btn-error gap-2"
                                title="Delete permanently"
                                data-admin-action
                                data-action-url="{{ route('admin.platform-pages.force-delete', $platformPage->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Delete Platform Page Permanently"
                                data-action-description="Permanently delete “{{ $platformPage->name }}”. This action cannot be undone."
                                data-action-icon="fa-solid fa-triangle-exclamation"
                                data-action-confirm-icon="fa-solid fa-trash-can"
                                data-action-confirm-text="Delete Forever"
                            >
                                <i class="fa-solid fa-trash-can"></i>
                                <span class="hidden lg:inline whitespace-nowrap">Delete Forever</span>
                            </button>
                        @endcan
                    </div>
                </div>

                <p class="mt-1 text-sm leading-6 text-base-content/65 text-justify">This page is no longer part of the active platform directory, but its content is still available to authorized administrators.</p>

                @if($platformPage->deleted_at)
                    <p class="mt-1 text-xs text-base-content/50">Deleted {{ $platformPage->deleted_at->format('M d, Y \a\t H:i') }} </p>
                @endif
            </div>
        </section>
    @endif
</div>
@endsection