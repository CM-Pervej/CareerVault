@extends('layouts.admin.app')

@section('title', $platformGroup->name)

@push('styles') <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"> <style>
.info-row {transition: background-color .15s ease;}
.info-row:hover {background-color: hsl(var(--b2) / .5);}
.action-card {transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;}
.action-card:hover {transform: translateY(-2px); box-shadow: 0 12px 30px rgba(0,0,0,.06);} </style>
@endpush

@section('content')
@php
$platform = $platformGroup->platform;

    $accessLabel = match($platformGroup->access_type) {
        'public' => 'Public',
        'members_only' => 'Members Only',
        'private' => 'Private',
        default => ucfirst(str_replace('_', ' ', $platformGroup->access_type)),
    };
@endphp

<div class="space-y-6">

    {{-- ======================= Hero =========================== --}}
    <section class="overflow-hidden border border-base-300 bg-base-100 shadow-sm">
        <div class="relative overflow-hidden" style="background: radial-gradient(circle at 15% 20%, {{ $platform->color ?: '#6366f1' }} 0%, transparent 34%), radial-gradient(circle at 85% 0%, rgba(255,255,255,.18) 0%, transparent 30%), linear-gradient(135deg, {{ $platform->color ?: '#4f46e5' }} 0%, #111827 100%);">

            @if($platformGroup->cover_image)
                <img src="{{ $platformGroup->cover_image }}" alt="{{ $platformGroup->name }}" class="absolute inset-0 size-full object-cover opacity-30">
            @endif

            <div class="absolute inset-0 bg-black/10"></div>

            {{-- Top navigation --}}
            <div class="relative flex items-center justify-between gap-4 px-5 py-4 sm:px-7">
                <div class="flex min-w-0 items-center gap-2 text-sm text-white/70">
                    <a href="{{ route('admin.platforms.index') }}" class="transition hover:text-white">
                        Platforms
                    </a>

                    <i class="fa-solid fa-chevron-right shrink-0 text-[10px] text-white/40"></i>

                    <a href="{{ route('admin.platform-groups.index', ['platform' => $platform->slug]) }}" class="truncate transition hover:text-white">
                        {{ $platform->name }} Groups
                    </a>

                    <i class="fa-solid fa-chevron-right shrink-0 text-[10px] text-white/40"></i>

                    <span class="hidden truncate font-semibold text-white sm:inline">
                        {{ $platformGroup->name }}
                    </span>
                </div>

                {{-- Actions --}}
                <div class="flex shrink-0 items-center gap-2">
                    @can('update', $platformGroup)
                        <a href="{{ route('admin.platform-groups.edit', $platformGroup) }}" class="btn btn-sm border-0 bg-white/15 text-white shadow-none backdrop-blur-md hover:bg-white/25">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                    @endcan

                    <a href="{{ route('admin.platform-groups.index', ['platform' => $platform->slug]) }}" class="btn btn-sm border-0 bg-white/15 text-white shadow-none backdrop-blur-md hover:bg-white/25">
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
                            @if($platformGroup->logo)
                                <img src="{{ $platformGroup->logo }}" alt="{{ $platformGroup->name }}" class="size-full object-contain p-3">
                            @else
                                <i class="fa-solid fa-users text-6xl text-base-content/30"></i>
                            @endif
                        </div>

                        <span class="absolute -bottom-2 -right-2 flex size-8 items-center justify-center rounded-full border-4 border-white/20 bg-white shadow-lg">
                            @if($platformGroup->is_active)
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
                                <h1 class="text-2xl font-black tracking-tight sm:text-3xl">
                                    {{ $platformGroup->name }}
                                </h1>

                                @if($platformGroup->is_active)
                                    <span class="badge badge-success badge-outline">
                                        <i class="fa-solid fa-circle-check"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-ghost">
                                        <i class="fa-solid fa-circle-pause"></i>
                                        Inactive
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm font-medium text-white/60">
                                <span>{{ $platform->name }}</span>
                            </div>
                        </div>

                        @if($platformGroup->short_desc)
                            <p class="mt-4 max-w-3xl text-justify text-sm leading-6 text-white/75 sm:text-base">
                                {{ $platformGroup->short_desc }}
                            </p>
                        @else
                            <p class="mt-4 max-w-3xl text-justify text-sm leading-6 text-white/75 sm:text-base">
                                Platform group information and management details.
                            </p>
                        @endif

                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('admin.platform-groups.index', ['platform' => $platform->slug]) }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur transition hover:bg-white/20">
                                <i class="fa-solid fa-layer-group text-[11px]"></i>
                                {{ $platform->name }}
                            </a>

                            @if($platformGroup->group_type)
                                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                    <i class="fa-solid fa-users text-[11px]"></i>
                                    {{ $platformGroup->group_type }}
                                </span>
                            @endif

                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                <i class="fa-solid fa-lock-open text-[11px]"></i>
                                {{ $accessLabel }}
                            </span>

                            @if($platformGroup->is_bangladesh_focused)
                                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                    <i class="fa-solid fa-location-dot text-[11px]"></i>
                                    Bangladesh Focused
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== Main Content ====================== --}}
    <div class="grid gap-3 lg:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)]">

        {{-- ================= Main Column ======================= --}}
        <div class="space-y-3 p-1 sm:p-0">

            {{-- Official Group URL --}}
            @if($platformGroup->url)
                <a href="{{ $platformGroup->url }}" target="_blank" rel="noopener noreferrer" class="action-card block border border-primary/20 bg-primary/5 p-5 sm:rounded-r-lg">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex min-w-0 items-start gap-4">
                            <div class="grid size-11 shrink-0 place-items-center rounded-xl bg-primary text-primary-content">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </div>

                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wider text-primary">
                                    Official Group
                                </p>

                                <p class="mt-1 break-all font-semibold">
                                    {{ $platformGroup->url }}
                                </p>
                            </div>
                        </div>

                        <i class="fa-solid fa-chevron-right shrink-0 text-primary"></i>
                    </div>
                </a>
            @endif

            {{-- Description --}}
            <section class="border border-base-300 bg-base-100 shadow-sm sm:rounded-r-lg">
                <div class="border-b border-base-300 px-5 py-2 sm:py-4">
                    <div class="flex items-center justify-center gap-2 sm:justify-start">
                        <i class="fa-solid fa-align-left text-primary"></i>
                        <h2 class="font-bold">Description</h2>
                    </div>
                </div>

                <div class="p-2 sm:p-5">
                    @if($platformGroup->description)
                        <div class="whitespace-pre-line text-justify text-sm leading-7 text-base-content/75">
                            {{ $platformGroup->description }}
                        </div>
                    @elseif($platformGroup->short_desc)
                        <div class="whitespace-pre-line text-justify text-sm leading-7 text-base-content/75">
                            {{ $platformGroup->short_desc }}
                        </div>
                    @else
                        <div class="flex items-center gap-3 rounded-xl border border-dashed border-base-300 p-4">
                            <i class="fa-regular fa-file-lines text-base-content/30"></i>
                            <p class="text-sm text-base-content/50">
                                No description has been added.
                            </p>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Media --}}
            @if($platformGroup->logo || $platformGroup->cover_image)
                <section class="border border-base-300 bg-base-100 shadow-sm sm:rounded-r-lg">
                    <div class="border-b border-base-300 px-5 py-4">
                        <div class="flex items-center justify-center gap-2 sm:justify-start">
                            <i class="fa-solid fa-images text-primary"></i>
                            <h2 class="font-bold">Media</h2>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2">
                        @if($platformGroup->logo)
                            <div>
                                <p class="mb-3 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                    Logo
                                </p>

                                <div class="flex h-44 items-center justify-center overflow-hidden rounded-2xl border border-base-300 bg-base-200">
                                    <img src="{{ $platformGroup->logo }}" alt="{{ $platformGroup->name }} logo" class="max-h-full max-w-full object-contain p-4">
                                </div>
                            </div>
                        @endif

                        @if($platformGroup->cover_image)
                            <div>
                                <p class="mb-3 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                    Cover Image
                                </p>

                                <div class="h-44 overflow-hidden rounded-2xl border border-base-300 bg-base-200">
                                    <img src="{{ $platformGroup->cover_image }}" alt="{{ $platformGroup->name }} cover image" class="h-full w-full object-cover">
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
            @endif
        </div>

        {{-- ======================= Sidebar ======================== --}}
        <aside class="space-y-3 p-1 sm:p-0">

            {{-- Group Details --}}
            <section class="border border-base-300 bg-base-100 shadow-sm sm:rounded-lg">
                <div class="border-b border-base-300 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                        <h2 class="font-bold">Group Details</h2>
                    </div>
                </div>

                <div class="divide-y divide-base-300">

                    {{-- Platform --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Platform</span>

                        <a href="{{ route('admin.platform-groups.index', ['platform' => $platform->slug]) }}" class="text-right text-sm font-bold hover:text-primary" style="color: {{$platform->color}}">
                            {{ $platform->name }}
                        </a>
                    </div>

                    {{-- Group Type --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Type</span>

                        <span class="text-right text-sm font-semibold">
                            {{ $platformGroup->group_type ?: 'Not specified' }}
                        </span>
                    </div>

                    {{-- Access --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Access</span>

                        <span class="text-right text-sm font-semibold">
                            {{ $accessLabel }}
                        </span>
                    </div>

                    {{-- Bangladesh Focus --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Bangladesh Focus</span>

                        <div class="text-right">
                            @if($platformGroup->is_bangladesh_focused)
                                <span class="badge badge-success badge-outline">
                                    <i class="fa-solid fa-flag"></i>
                                    Focused
                                </span>
                            @else
                                <span class="text-sm font-semibold text-base-content/50">
                                    General
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Status</span>

                        @if($platformGroup->is_active)
                            <span class="badge badge-success badge-outline">
                                Active
                            </span>
                        @else
                            <span class="badge badge-ghost">
                                Inactive
                            </span>
                        @endif
                    </div>

                    {{-- Sort Order --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Sort Order</span>

                        <span class="font-mono text-sm font-semibold">
                            {{ $platformGroup->sort_order }}
                        </span>
                    </div>
                </div>
            </section>

            {{-- Platform --}}
            <section class="border border-base-300 bg-base-100 p-5 shadow-sm sm:rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="grid size-11 shrink-0 place-items-center overflow-hidden rounded-xl bg-base-200">
                        @if($platform->logo)
                            <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-2">
                        @elseif($platform->icon)
                            <i class="{{ $platform->icon }} text-4xl" style="color: {{$platform->color}}"></i>
                        @else
                            <span class="text-4xl font-black" style="color: {{ $platform->color ?: '#4f46e5' }}">
                                {{ strtoupper(substr($platform->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs text-base-content/50">Belongs to</p>

                        <a href="{{ route('admin.platform-groups.index', ['platform' => $platform->slug]) }}" class="font-bold hover:text-primary" style="color: {{$platform->color}}">
                            {{ $platform->name }}
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.platform-groups.index', ['platform' => $platform->slug]) }}" class="btn btn-outline btn-sm mt-4 w-full gap-2">
                    View Groups
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </section>
        </aside>
    </div>

    {{-- ================= Verification & Audit ==================== --}}
    <section class="border border-base-300 bg-base-100 shadow-sm sm:rounded-lg">
        <div class="border-b border-base-300 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="grid size-10 place-items-center rounded-xl bg-warning/10 text-warning">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>

                <div>
                    <h2 class="font-bold">Verification & History</h2>
                    <p class="text-sm text-base-content/60">
                        Verification and record history for this group.
                    </p>
                </div>
            </div>
        </div>

        <div class="divide-y divide-base-300">

            {{-- Last Verified --}}
            <div class="flex flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-medium">Last Verified</p>
                    <p class="text-xs text-base-content/50">
                        When the group information was last checked.
                    </p>
                </div>

                <div class="text-sm font-medium sm:text-right">
                    @if($platformGroup->last_verified_at)
                        {{ $platformGroup->last_verified_at->format('M d, Y · h:i A') }}
                    @else
                        <span class="text-base-content/40">
                            Not verified yet
                        </span>
                    @endif
                </div>
            </div>

            {{-- Created --}}
            <div class="flex flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-medium">Created</p>
                    <p class="text-xs text-base-content/50">
                        Original record creation.
                    </p>
                </div>

                <div class="text-sm sm:text-right">
                    <p class="font-medium">
                        {{ $platformGroup->created_at?->format('M d, Y · h:i A') ?? '—' }}
                    </p>

                    @if($platformGroup->createdBy)
                        <p class="text-xs text-base-content/50">
                            by {{ $platformGroup->createdBy->name }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Updated --}}
            <div class="flex flex-col gap-2 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="font-medium">Last Updated</p>
                    <p class="text-xs text-base-content/50">
                        Most recent record modification.
                    </p>
                </div>

                <div class="text-sm sm:text-right">
                    <p class="font-medium">
                        {{ $platformGroup->updated_at?->format('M d, Y · h:i A') ?? '—' }}
                    </p>

                    @if($platformGroup->updatedBy)
                        <p class="text-xs text-base-content/50">
                            by {{ $platformGroup->updatedBy->name }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ================= Technical Details ======================= --}}
    <section class="border border-base-300 bg-base-100 shadow-sm sm:rounded-lg">
        <div class="border-b border-base-300 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="grid size-10 place-items-center rounded-xl bg-base-200 text-base-content/60">
                    <i class="fa-solid fa-code"></i>
                </div>

                <div>
                    <h2 class="font-bold">Record Details</h2>
                    <p class="text-sm text-base-content/60">
                        Internal identifiers used by CareerVault.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-px bg-base-300 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-base-100 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                    Group ID
                </p>

                <p class="mt-2 font-mono text-sm font-semibold">
                    #{{ $platformGroup->id }}
                </p>
            </div>

            <div class="bg-base-100 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                    Slug
                </p>

                <p class="mt-2 break-all font-mono text-sm font-semibold">
                    {{ $platformGroup->slug }}
                </p>
            </div>

            <div class="bg-base-100 p-5">
                <p class="text-xs font-semibold uppercase tracking-wide text-base-content/50">
                    Platform ID
                </p>

                <p class="mt-2 font-mono text-sm font-semibold">
                    #{{ $platformGroup->platform_id }}
                </p>
            </div>
        </div>
    </section>

    {{-- ================= Active Group Danger Zone ==================== --}}
    @can('delete', $platformGroup)
        <section class="border border-error/20 bg-error/5 p-5">
            <div class="flex flex-col gap-1">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-error"></i>
                        <h2 class="font-bold text-error">Delete this group</h2>
                    </div>

                    <div>
                        <button
                            type="button"
                            class="btn btn-error btn-outline btn-sm gap-2"
                            title="Move group to trash"
                            data-admin-action
                            data-action-url="{{ route('admin.platform-groups.destroy', $platformGroup) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Move Group to Trash"
                            data-action-description="Move “{{ $platformGroup->name }}” to trash. You can restore it later."
                            data-action-confirm="Move to Trash"
                            data-action-icon="fa-solid fa-trash-can"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Move to Trash"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Move to Trash</span>
                        </button>
                    </div>
                </div>

                <p class="mt-1 text-sm text-base-content/60">
                    The group will be moved to trash. Its content can still be restored later.
                </p>
            </div>
        </section>
    @endcan

</div>

@endsection
