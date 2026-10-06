@extends('layouts.admin.app')

@section('title', $platformGroup->name)
@section('page_title', 'Groups / ' . $platformGroup->name)

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
        <div class="relative overflow-hidden" style="background: radial-gradient(circle at 15% 20%, {{ $platformGroup->platform->color ?: '#6366f1' }} 0%, transparent 34%), radial-gradient(circle at 85% 0%, rgba(255,255,255,.18) 0%, transparent 30%), linear-gradient(135deg, {{ $platformGroup->platform->color ?: '#4f46e5' }} 0%, #111827 100%);">
            @if($platformGroup->cover_image)
                <img src="{{ Storage::url($platformGroup->cover_image) }}" alt="{{ $platformGroup->name }}" class="absolute inset-0 size-full opacity-30">
            @elseif($platformGroup->platform->cover_image)
                <img src="{{ Storage::url($platformGroup->platform->cover_image) }}" alt="{{ $platformGroup->platform->name }}" class="absolute inset-0 size-full object-cover opacity-30">
            @endif

            <div class="absolute inset-0 bg-black/10"></div>

            {{-- Top navigation --}}
            <div class="relative flex items-center justify-between gap-4 px-5 py-4 sm:px-7">
                <div class="flex min-w-0 items-center gap-2 text-sm text-white/70">
                    <a href="{{ route('admin.platforms.index') }}" class="transition hover:text-white">Platforms</a>
                    <i class="fa-solid fa-chevron-right shrink-0 text-[10px] text-white/40"></i>
                    <a href="{{ route('admin.platform-groups.index', ['platform' => $platform->slug]) }}" class="truncate transition hover:text-white"> {{ $platform->name }} </a>
                    <i class="fa-solid fa-chevron-right shrink-0 text-[10px] text-white/40"></i>
                    <span class="hidden truncate font-semibold text-white sm:inline"> {{ $platformGroup->name }} </span>
                </div>

                {{-- Actions --}}
                <div class="flex shrink-0 items-center gap-2">
                    @if(!$platformGroup->trashed())
                        <a href="{{ route('admin.platform-groups.edit', $platformGroup) }}" class="btn btn-sm border-0 bg-white/15 text-white shadow-none backdrop-blur-md hover:bg-white/25">
                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                    @endif

                    @if($platformGroup->trashed())
                        <a href="{{ route('admin.platform-groups.trash') }}" class="btn btn-warning btn-outline btn-sm gap-2">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                            <span class="hidden sm:inline">Trash</span>
                        </a>
                    @endif

                    <a href="{{ route('admin.platform-groups.index') }}" class="btn btn-sm border-0 bg-white/15 text-white shadow-none backdrop-blur-md hover:bg-white/25">
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
                        <div class="flex size-20 items-center justify-center overflow-hidden rounded-2xl border border-white/10 bg-white shadow-xl sm:size-24">
                            @if($platformGroup->logo)
                                <img src="{{ Storage::url($platformGroup->logo) }}" alt="{{ $platformGroup->name }}" class="size-full object-cover">
                            @elseif($platform->icon)
                                <i class="{{ $platform->icon }} text-5xl sm:text-6xl" style="color: {{ $accentColor }}"></i>
                            @else
                                <span class="text-3xl font-black sm:text-4xl" style="color: {{ $accentColor }}">
                                    {{ strtoupper(substr($platform->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        @if ($platformGroup->trashed())                            
                            <span class="absolute -bottom-1.5 -right-1.5 flex size-6 items-center justify-center rounded-full border-[3px] border-[#101216] bg-warning">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </span>
                        @elseif(!$platformGroup->trashed())
                            <span class="absolute -bottom-1.5 -right-1.5 flex size-6 items-center justify-center rounded-full border-[3px] border-[#101216] {{ $platformGroup->is_active ? 'bg-emerald-500' : 'bg-base-content/30' }}">
                                <i class="fa-solid {{ $platformGroup->is_active ? 'fa-check' : 'fa-pause' }} text-[9px] text-white"></i>
                            </span>
                        @endif
                    </div>

                    {{-- Identity text --}}
                    <div class="min-w-0 flex-1 text-white">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h1 class="text-2xl font-black tracking-tight sm:text-3xl">
                                    {{ $platformGroup->name }}
                                </h1>

                                @if($platformGroup->trashed())                                
                                    <span class="badge badge-warning badge-outline">
                                        <i class="fa-solid fa-trash-can text-xs"></i> Trash
                                    </span>
                                @elseif($platformGroup->is_active)
                                    <span class="badge badge-success badge-outline">
                                        <i class="fa-solid fa-circle-check"></i> Active
                                    </span>
                                @else
                                    <span class="badge badge-ghost">
                                        <i class="fa-solid fa-circle-pause"></i> Inactive
                                    </span>
                                @endif
                            </div>

                            <div class="text-sm font-medium text-white/60">
                                <span> {{ $platform->name }} </span>
                            </div>
                        </div>

                        @if($platformGroup->short_desc)
                            <p class="mt-3 text-justify text-sm text-white/75"> {{ $platformGroup->short_desc }} </p>
                        @else
                            <p class="mt-3 text-justify text-sm text-white/75">Platform group information and management details.</p>
                        @endif

                        <div class="mt-5 flex flex-wrap gap-2">
                            <a href="{{ route('admin.platform-groups.index', ['platform' => $platform->slug]) }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur transition hover:bg-white/20">
                                <i class="fa-solid fa-layer-group text-[11px]"></i> {{ $platform->name }}
                            </a>

                            @if($platformGroup->group_type)
                                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                    <i class="fa-solid fa-users text-[11px]"></i> {{ $platformGroup->group_type }}
                                </span>
                            @endif

                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                <i class="fa-solid fa-lock-open text-[11px]"></i> {{ $accessLabel }}
                            </span>

                            @if($platformGroup->is_bangladesh_focused)
                                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-semibold text-white/80 backdrop-blur">
                                    <i class="fa-solid fa-location-dot text-[11px]"></i> Bangladesh Focused
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== Main Content ====================== --}}
    <div x-data="{expanded: false, canExpand: false,
            checkHeight() {
                this.$nextTick(() => {
                    const content = this.$refs.descriptionContent;

                    if (!content) {
                        this.canExpand = false;
                        return;
                    }

                    this.canExpand = content.scrollHeight > content.clientHeight;
                });
            }
        }"
        x-init="checkHeight()"
        @resize.window="checkHeight()"
        class="grid gap-3 lg:grid-cols-[minmax(0,2fr)_minmax(320px,1fr)] sm:px-6"
    >
        {{-- ================= Main Column ======================= --}}
        <div class="order-1 space-y-3">
            {{-- Description --}}
            <section class="bg-base-100 sm:rounded-sm sm:border sm:border-gray-300">
                <div class="border-b border-base-300 px-5 py-2 sm:py-4">
                    <div class="flex items-center justify-center gap-2 sm:justify-start">
                        <i class="fa-solid fa-align-left text-primary"></i>
                        <h2 class="font-bold">Description</h2>
                    </div>
                </div>

                <div class="px-4 sm:px-5">
                    @if($platformGroup->description)
                        <div class="max-w-4xl">
                            <div x-ref="descriptionContent" class="overflow-hidden text-sm text-base-content/75" :class="expanded ? '' : 'max-h-[200px]'">
                                @foreach(preg_split("/\r\n\s*\r\n|\r\s*\r\s*|\n\s*\n/", trim($platformGroup->description)) as $paragraph)
                                    @if(trim($paragraph))
                                        <p class="whitespace-pre-line">
                                            {{ trim($paragraph) }}
                                        </p>
                                    @endif
                                @endforeach
                            </div>

                            <button x-show="canExpand" x-cloak type="button" @click="expanded = !expanded" class="mt-3 inline-flex items-center gap-1.5 text-sm font-medium text-primary transition-colors hover:text-primary/80">
                                <span x-text="expanded ? 'Read Less' : 'Read More'"></span>
                                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="expanded ? 'rotate-180' : ''"></i>
                            </button>
                        </div>
                    @elseif($platformGroup->short_desc)
                        <div class="max-w-4xl whitespace-pre-line text-sm leading-7 text-base-content/75"> {{ $platformGroup->short_desc }} </div>
                    @else
                        <div class="flex items-center gap-3 rounded-xl border border-dashed border-base-300 p-4">
                            <i class="fa-regular fa-file-lines text-base-content/30"></i>
                            <p class="text-sm text-base-content/50">No description has been added.</p>
                        </div>
                    @endif
                </div>
            </section>

            {{-- ================= DESKTOP ONLY, Collapsed → Platform stays below Description ================== --}}
            <template x-if="!expanded">
                <section class="hidden border border-gray-300 bg-base-100 p-5 sm:rounded-sm lg:block">
                    <div class="flex items-center gap-3">
                        <div class="grid size-11 shrink-0 place-items-center overflow-hidden rounded-xl bg-base-200">
                            @if($platform->logo)
                                <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-2">
                            @elseif($platform->icon)
                                <i class="{{ $platform->icon }} text-4xl" style="color:{{ $platform->color ?: '#6366f1' }}"></i>
                            @else
                                <span class="text-4xl font-black" style="color:{{ $platform->color ?: '#4f46e5' }}"> {{ strtoupper(substr($platform->name, 0, 1)) }} </span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs text-base-content/50">Belongs to</p>
                            <a href="{{ route('admin.platforms.show', ['platform' => $platform->slug]) }}" class="font-bold hover:opacity-80" style="color:{{ $platform->color ?: '#6366f1' }}"> {{ $platform->name }} </a>
                        </div>
                    </div>

                    <a href="{{ route('admin.platforms.show', ['platform' => $platform->slug]) }}" class="btn btn-outline btn-sm mt-4 w-full gap-2">
                        View Platform <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </section>
            </template>
        </div>

        {{-- ======================= Sidebar ======================== --}}
        <aside class="order-2 space-y-3 py-1 sm:p-0">
            {{-- ================= Group Details ================= --}}
            <section class="bg-base-100 sm:rounded-sm sm:border sm:border-gray-300">
                <div class="border-b border-base-300 px-5 py-2 sm:py-4">
                    <div class="flex items-center justify-center gap-2 sm:justify-start">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                        <h2 class="font-bold">Group Details</h2>
                    </div>
                </div>

                @php
                    $metadata = [
                        [
                            'label' => 'Type', 'value' => $platformGroup->group_type ?: 'Not Specified',
                        ], [
                            'label' => 'Access', 'value' => $accessLabel,
                        ], [
                            'label' => 'Bangladesh Focus', 'value' => $platformGroup->is_bangladesh_focused ? 'Focused' : 'International', 'sp' => $platformGroup->is_bangladesh_focused ? 'badge badge-success badge-outline' : 'badge badge-primary badge-outline', 'icon' => '<i class="fa-solid fa-flag"></i>',
                        ], [
                            'label' => 'Sort Order', 'value' => $platformGroup->sort_order,
                        ], [
                            'label' => 'Group ID', 'value' => $platformGroup->id,
                        ], [
                            'label' => 'Platform ID', 'value' => $platform->id,
                        ],
                    ];
                @endphp

                <div class="divide-y divide-base-300">
                    {{-- Platform --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Platform</span>

                        <a href="{{ route('admin.platforms.show', ['platform' => $platform->slug]) }}" style="--platform-color: {{ $platform->color ?: '#6366f1' }}" class="inline-flex items-center gap-1.5 rounded-lg border px-2 py-1 text-right text-sm font-bold text-[var(--platform-color)] transition-colors hover:!bg-[var(--platform-color)] hover:!text-white">
                            <i class="fa-solid fa-layer-group text-[11px]"></i> {{ $platform->name }}
                        </a>
                    </div>

                    {{-- Page URL --}}
                    <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                        <span class="text-sm text-base-content/60">Page</span>
                        <a href="{{ $platformGroup->url }}" target="_blank" rel="noopener noreferrer" style="--platform-color: {{ $platform->color ?: '#6366f1' }}" class="inline-flex max-w-[70%] items-center gap-1.5 rounded-lg border px-2 py-1 text-right text-sm font-bold text-[var(--platform-color)] transition-colors hover:!bg-[var(--platform-color)] hover:!text-white">
                            <i class="fa-solid fa-arrow-up-right-from-square text-[11px]"></i> {{ $platformGroup->name }}
                        </a>
                    </div>

                    @foreach($metadata as $item)
                        <div class="info-row flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-base-content/60"> {{ $item['label'] }} </span>

                            <span class="text-right text-sm font-semibold {{ $item['sp'] ?? '' }}">
                                {!! $item['icon'] ?? '' !!} {{ $item['value'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>


            {{-- =================== DESKTOP ONLY, Expanded → Platform stays immediately below Details ===================== --}}
            <template x-if="expanded">
                <section class="hidden border border-gray-300 bg-base-100 p-5 sm:rounded-sm lg:block">
                    <div class="flex items-center gap-3">
                        <div class="grid size-11 shrink-0 place-items-center overflow-hidden rounded-xl bg-base-200">
                            @if($platform->logo)
                                <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-2">
                            @elseif($platform->icon)
                                <i class="{{ $platform->icon }} text-4xl" style="color:{{ $platform->color ?: '#6366f1' }}"></i>
                            @else
                                <span class="text-4xl font-black" style="color:{{ $platform->color ?: '#4f46e5' }}"> {{ strtoupper(substr($platform->name, 0, 1)) }} </span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs text-base-content/50">Belongs to</p>
                            <a href="{{ route('admin.platforms.show', ['platform' => $platform->slug]) }}" class="font-bold hover:opacity-80" style="color:{{ $platform->color ?: '#6366f1' }}"> {{ $platform->name }} </a>
                        </div>
                    </div>

                    <a href="{{ route('admin.platforms.show', ['platform' => $platform->slug]) }}" class="btn btn-outline btn-sm mt-4 w-full gap-2">
                        View Platform <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </section>
            </template>

            {{-- ==================== MOBILE ONLY, Platform ALWAYS comes immediately after Page Details ===================== --}}
            <section class="border border-gray-300 bg-base-100 p-5 sm:rounded-sm lg:hidden">
                <div class="flex items-center gap-3">
                    <div class="grid size-11 shrink-0 place-items-center overflow-hidden rounded-xl bg-base-200">
                        @if($platform->logo)
                            <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-2">
                        @elseif($platform->icon)
                            <i class="{{ $platform->icon }} text-4xl" style="color:{{ $platform->color ?: '#6366f1' }}"></i>
                        @else
                            <span class="text-4xl font-black" style="color:{{ $platform->color ?: '#4f46e5' }}"> {{ strtoupper(substr($platform->name, 0, 1)) }} </span>
                        @endif
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs text-base-content/50">Belongs to</p>
                        <a href="{{ route('admin.platforms.show', ['platform' => $platform->slug]) }}" class="font-bold hover:opacity-80" style="color:{{ $platform->color ?: '#6366f1' }}"> {{ $platform->name }} </a>
                    </div>
                </div>

                <a href="{{ route('admin.platforms.show', ['platform' => $platform->slug]) }}" class="btn btn-outline btn-sm mt-4 w-full gap-2">
                    View Platform <i class="fa-solid fa-arrow-right"></i>
                </a>
            </section>
        </aside>
    </div>

    {{-- History --}}
    <section class="sm:px-6">
        <div class="bg-base-100">
            <div class="border-b border-base-300 sm:border-gray-300 px-5 sm:py-4">
                <div class="flex items-center justify-center gap-2 sm:justify-start">
                    <i class="fa-solid fa-circle-info text-primary"></i>
                    <h2 class="font-bold">History</h2>
                </div>
            </div>
    
            @php
                $history = [
                    [
                        'label' => 'Status', 'value' => $platformGroup->trashed() ? 'Trashed' : ($platformGroup->is_active ? 'Active' : 'Inactive'), 'cl' => $platformGroup->trashed() ? 'badge badge-warning badge-outline font-semibold' : ($platformGroup->is_active ? 'badge badge-success badge-outline font-semibold' : 'badge badge-ghost font-semibold'),
                    ], [
                        'label' => 'Created At', 'value' => $platformGroup->created_at->format('M d, Y - H:i A'), 'cl' => 'font-semibold text-blue-700',
                    ], [
                        'label' => 'Updated At', 'value' => $platformGroup->updated_at?->format('M d, Y - H:i A') ?? 'Unknown', 'cl' => 'text-green-700 font-semibold',
                    ], 
                    ...($platformGroup->trashed() ? [
                        [
                            'label' => 'Deleted At', 'value' => $platformGroup->deleted_at?->format('M d, Y - H:i A') ?? 'Unknown', 'cl' => 'text-warning font-semibold',
                        ], 
                    ] : []),
                ];
                $historyRight = [
                    [
                        'label' => 'Last Verified', 'value' => $platformGroup->last_verified_at ? $platformGroup->last_verified_at->format('M d, Y - H:i A') : 'Not verified', 'cl' => 'font-semibold',
                    ], [
                        'label' => 'Created By', 'value' => $platformGroup->createdBy?->name ?? 'Unknown User', 'subvalue' => $platformGroup->createdBy ? str_replace('_', ' ', $platformGroup->createdBy->role) : null, 'cl' => 'font-black text-blue-700',
                    ], [
                        'label' => 'Updated By', 'value' => $platformGroup->updatedBy?->name ?? 'Unknown User', 'cl' => 'text-green-700 font-black', 'subvalue' => $platformGroup->updatedBy ? str_replace('_', ' ', $platformGroup->updatedBy->role) : null,
                    ],
                    ...($platformGroup->trashed() ? [
                        [
                            'label' => 'Deleted By', 'value' => $platformGroup->deletedBy?->name ?? 'Unknown User', 'cl' => 'text-warning font-black', 'subvalue' => $platformGroup->deletedBy ? str_replace('_', ' ', $platformGroup->deletedBy->role) : null,
                        ],
                    ] : []),
                ];
            @endphp
    
            <div class="flex flex-col sm:flex-row justify-between sm:gap-5 py-2 sm:py-5">
                <div class="flex-1 w-full sm:border sm:border-gray-300 p-5 grid grid-cols-1 gap-2">
                    @foreach($history as $item)
                        <div class="info-row flex items-center justify-between gap-4">
                            <span class="text-sm text-base-content/60"> {{ $item['label'] }} </span>
        
                            <div class="min-w-0 text-right">
                                <div class="text-sm {{ $item['cl'] ?? '' }}"> {{ $item['value'] }} </div>
        
                                @if(!empty($item['subvalue']))
                                    <div class="mt-0.5 text-[9px] font-semibold uppercase tracking-wider text-blue-500"> {{ $item['subvalue'] }} </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border border-gray-300"></div>
                <div class="flex-1 w-full sm:border sm:border-gray-300 p-5 grid grid-cols-1 gap-2">
                    @foreach($historyRight as $item)
                        <div class="info-row flex items-center justify-between gap-4">
                            <span class="text-sm text-base-content/60"> {{ $item['label'] }} </span>
        
                            <div class="min-w-0 text-right">
                                <div class="text-sm {{ $item['cl'] ?? '' }}"> 
                                    {{ $item['value'] }} 
                                
                                    @if(!empty($item['subvalue']))
                                        {{-- <span class="mt-0.5 ml-1 text-[9px] font-bold uppercase tracking-wider text-black"> ({{ $item['subvalue'] }}) </span> --}}
                                        <span class="mt-0.5 ml-1 text-[9px] font-bold tracking-wider uppercase
                                            {{ strtolower($item['subvalue']) === 'super admin' ? 'text-blue-600' : (strtolower($item['subvalue']) === 'admin' ? 'text-green-600' : (strtolower($item['subvalue']) === 'user' ? 'text-red-600' : 'text-black')) }}">
                                            ({{ $item['subvalue'] }})
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ================= Active Page Danger Zone ==================== --}}
    @if(!$platformGroup->trashed())
        <section class="border border-error/20 bg-error/5 p-5">
            <div class="flex flex-col gap-1">
                <div class="flex justify-between items-center gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-error"></i>
                        <h2 class="font-bold text-error">Delete this group</h2>
                    </div>

                    <div>
                        @can('delete',$platformGroup)
                            <button
                                type="button"
                                class="btn btn-error btn-outline btn-sm gap-2"
                                title="Move group to trash"
                                data-admin-action
                                data-action-url="{{ route('admin.platform-groups.destroy',$platformGroup->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Move Platform Group to Trash"
                                data-action-description="Move “{{ $platformGroup->name }}” to trash. You can restore it later from the Trash page."
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

                <p class="mt-1 text-sm text-base-content/60">The group will be moved to trash. Its content can still be restored later.</p>
            </div>
        </section>
    @endif

    {{-- =================== Trash Warning ======================= --}}
    @if($platformGroup->trashed())
        <section class="border border-warning/30 bg-warning/10 p-5">
            <div class="flex flex-col gap-1">
                <div class="flex justify-between items-center gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-trash-can text-warning"></i>
                        <h2 class="font-bold text-warning">This group is in Trash</h2>
                    </div>
                    <div class="flex shrink-0 flex-wrap gap-2">
                        @can('restore', $platformGroup)
                            <button
                                type="button"
                                class="btn btn-success gap-2"
                                title="Restore group"
                                data-admin-action
                                data-action-url="{{ route('admin.platform-groups.restore', $platformGroup->slug) }}"
                                data-action-method="PATCH"
                                data-action-type="success"
                                data-action-title="Restore Platform Group"
                                data-action-description="Restore “{{ $platformGroup->name }}” and return it to the active platform groups."
                                data-action-icon="fa-solid fa-rotate-left"
                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                data-action-confirm-text="Restore Group"
                            >
                                <i class="fa-solid fa-rotate-left"></i>
                                <span class="hidden lg:inline">Restore</span>
                            </button>
                        @endcan

                        @can('forceDelete', $platformGroup)
                            <button
                                type="button"
                                class="btn btn-error gap-2"
                                title="Delete permanently"
                                data-admin-action
                                data-action-url="{{ route('admin.platform-groups.force-delete', $platformGroup->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Delete Platform Group Permanently"
                                data-action-description="Permanently delete “{{ $platformGroup->name }}”. This action cannot be undone."
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

                <p class="mt-1 text-sm leading-6 text-base-content/65 text-justify">This group is no longer part of the active platform directory, but its content is still available to authorized administrators.</p>

                @if($platformGroup->deleted_at)
                    <p class="mt-1 text-xs text-base-content/50">Deleted {{ $platformGroup->deleted_at->format('M d, Y \a\t H:i') }} </p>
                @endif
            </div>
        </section>
    @endif
</div>
@endsection