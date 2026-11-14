
@extends('layouts.admin.app')

@section('title', $techCategory->name . ' | CareerVault')
@section('page_title', 'Technology Categories / ' . $techCategory->name)

@section('content')
<div class="space-y-6">
    {{-- Overview --}}
    <section class="overflow-hidden border border-base-300 bg-base-100 shadow-sm">
        <header class="flex flex-col gap-5 border-b border-base-300/80 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div class="flex min-w-0 flex-1 flex-col items-center gap-4 sm:flex-row">
                <div class="grid size-16 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-primary/15 to-primary/5 text-primary ring-1 ring-primary/10 shadow-sm">
                    <i class="fa-solid fa-layer-group text-2xl"></i>
                </div>
                <div class="min-w-0 flex-1 text-center sm:text-start">
                    <div class="relative mx-auto w-fit sm:mx-0">
                        <h2 class="text-xl font-bold tracking-tight text-base-content sm:text-2xl">{{ $techCategory->name }}</h2>
                        <span class="absolute -right-7 top-1/2 flex size-5 -translate-y-1/2 items-center justify-center rounded-full ring-2 ring-base-100 {{ $techCategory->is_active ? 'bg-emerald-500' : 'bg-base-content/40' }}">
                            <i class="fa-solid {{ $techCategory->is_active ? 'fa-check' : 'fa-pause' }} text-[9px] text-white"></i>
                        </span>
                    </div>
                    <p class="mt-1 text-sm text-base-content/60">{{ $techCategory->slug }}</p>
                    @if ($techCategory->description)
                        <p class="mt-1 max-w-3xl text-sm leading-6 text-base-content/65">{{ $techCategory->description }}</p>
                    @endif
                </div>
            </div>

            <div class="flex w-full shrink-0 flex-wrap gap-2 sm:w-auto">
                <a href="{{ route('admin.tech-categories.index') }}" class="btn btn-ghost flex-1 border border-base-300/70 sm:btn-sm sm:flex-none">
                    <i class="fa-solid fa-arrow-left"></i> Back
                </a>
                <a href="{{ route('admin.tech-categories.edit', $techCategory) }}" class="btn btn-primary flex-1 shadow-sm shadow-primary/20 sm:btn-sm sm:flex-none">
                    <i class="fa-solid fa-pen-to-square"></i> Edit
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 divide-y divide-base-300 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
            <div class="p-5">
                <p class="text-sm text-base-content/60">Sort Order</p>
                <p class="mt-2 text-2xl font-bold">{{ str_pad($techCategory->sort_order, 2, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="p-5">
                <p class="text-sm text-base-content/60">Associated Technologies</p>
                <p class="mt-2 text-2xl font-bold">{{ number_format($techCategory->technologies_count) }}</p>
            </div>
            <div class="p-5">
                <p class="text-sm text-base-content/60">Status</p>
                <p class="mt-2">
                    <span class="badge {{ $techCategory->is_active ? 'badge-success' : 'badge-ghost' }} badge-lg">
                        {{ $techCategory->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>
    </section>

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
                        'label' => 'Status',
                        'value' => $techCategory->trashed() ? 'Trashed' : ($techCategory->is_active ? 'Active' : 'Inactive'),
                        'cl' => $techCategory->trashed() ? 'badge badge-warning badge-outline font-semibold' : ($techCategory->is_active ? 'badge badge-success badge-outline font-semibold' : 'badge badge-ghost font-semibold'),
                    ],
                    [
                        'label' => 'Created At',
                        'value' => $techCategory->created_at?->format('M d, Y - h:i A') ?? 'Unknown',
                        'cl' => 'font-semibold text-blue-700',
                    ],
                    [
                        'label' => 'Updated At',
                        'value' => $techCategory->updated_at?->format('M d, Y - h:i A') ?? 'Unknown',
                        'cl' => 'text-green-700 font-semibold',
                    ],
                    ...($techCategory->trashed() ? [
                        [
                            'label' => 'Deleted At',
                            'value' => $techCategory->deleted_at?->format('M d, Y - h:i A') ?? 'Unknown',
                            'cl' => 'text-warning font-semibold',
                        ],
                    ] : []),
                ];

                $historyRight = [
                    [
                        'label' => 'Created By',
                        'value' => $techCategory->createdBy?->name ?? 'Unknown User',
                        'subvalue' => $techCategory->createdBy?->role ? str_replace('_', ' ', $techCategory->createdBy->role) : null,
                        'cl' => 'font-black text-blue-700',
                    ],
                    [
                        'label' => 'Updated By',
                        'value' => $techCategory->updatedBy?->name ?? 'Unknown User',
                        'subvalue' => $techCategory->updatedBy?->role ? str_replace('_', ' ', $techCategory->updatedBy->role) : null,
                        'cl' => 'text-green-700 font-black',
                    ],
                    ...($techCategory->trashed() ? [
                        [
                            'label' => 'Deleted By',
                            'value' => $techCategory->deletedBy?->name ?? 'Unknown User',
                            'subvalue' => $techCategory->deletedBy?->role ? str_replace('_', ' ', $techCategory->deletedBy->role) : null,
                            'cl' => 'text-warning font-black',
                        ],
                    ] : []),
                ];
            @endphp

            <div class="flex flex-col justify-between py-2 sm:flex-row sm:gap-5 sm:py-5">
                <div class="grid w-full flex-1 grid-cols-1 gap-2 p-5 sm:border sm:border-gray-300">
                    @foreach ($history as $item)
                        <div class="info-row flex items-center justify-between gap-4">
                            <span class="text-sm text-base-content/60">{{ $item['label'] }}</span>
                            <div class="min-w-0 text-right">
                                <div class="text-sm {{ $item['cl'] ?? '' }}">{{ $item['value'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border border-gray-300"></div>
                <div class="grid w-full flex-1 grid-cols-1 gap-2 p-5 sm:border sm:border-gray-300">
                    @foreach ($historyRight as $item)
                        <div class="info-row flex items-center justify-between gap-4">
                            <span class="text-sm text-base-content/60">{{ $item['label'] }}</span>
                            <div class="min-w-0 text-right">
                                <div class="text-sm {{ $item['cl'] ?? '' }}">
                                    {{ $item['value'] }}
                                    @if (!empty($item['subvalue']))
                                        <span class="mt-0.5 ml-1 text-[9px] font-bold tracking-wider uppercase {{ strtolower($item['subvalue']) === 'super admin' ? 'text-blue-600' : (strtolower($item['subvalue']) === 'admin' ? 'text-green-600' : (strtolower($item['subvalue']) === 'user' ? 'text-red-600' : 'text-black')) }}">
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

    {{-- Danger Zone --}}
    @if (!$techCategory->trashed())
        <section class="border border-error/20 bg-error/5 p-5">
            <div class="flex flex-col gap-1">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-error"></i>
                        <h2 class="font-bold text-error">Delete this category</h2>
                    </div>
                    @can('delete', $techCategory)
                        <button
                            type="button"
                            class="btn btn-error btn-outline btn-sm gap-2"
                            data-admin-action
                            data-action-url="{{ route('admin.tech-categories.destroy', $techCategory) }}"
                            data-action-method="DELETE"
                            data-action-type="danger"
                            data-action-title="Move Technology Category to Trash"
                            data-action-description="Move “{{ $techCategory->name }}” to trash. You can restore it later from the Trash page."
                            data-action-icon="fa-solid fa-trash-can"
                            data-action-confirm-icon="fa-solid fa-trash-can"
                            data-action-confirm-text="Move to Trash"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                            <span>Move to Trash</span>
                        </button>
                    @endcan
                </div>
                <p class="mt-1 text-sm text-base-content/60">The category will be moved to trash. Its content can still be restored later.</p>
            </div>
        </section>
    @endif
</div>
@endsection