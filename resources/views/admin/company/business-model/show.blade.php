
@extends('layouts.admin.app')

@section('title', $businessModel->name . ' | CareerVault')
@section('page_title', 'Business Models / ' . $businessModel->name)

@section('content')
    <div class="space-y-6">
        {{-- Overview --}}
        <section class="overflow-hidden border border-base-300 bg-base-100 shadow-sm">
            <header class="flex flex-col gap-5 border-b border-base-300/80 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div class="flex min-w-0 flex-1 flex-col items-center gap-4 sm:flex-row">
                    <div class="grid size-16 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-primary/15 to-primary/5 text-primary ring-1 ring-primary/10 shadow-sm">
                        <i class="fa-solid fa-building-circle-check text-2xl"></i>
                    </div>

                    <div class="min-w-0 flex-1 text-center sm:text-start">
                        <div class="relative mx-auto w-fit sm:mx-0">
                            <h2 class="text-xl font-bold tracking-tight text-base-content sm:text-2xl">
                                {{ $businessModel->name }}
                            </h2>
                            <span class="absolute -right-7 top-1/2 flex size-5 -translate-y-1/2 items-center justify-center rounded-full ring-2 ring-base-100 {{ $businessModel->is_active ? 'bg-emerald-500' : 'bg-base-content/40' }}">
                                <i class="fa-solid {{ $businessModel->is_active ? 'fa-check' : 'fa-pause' }} text-[9px] text-white"></i>
                            </span>
                        </div>

                        <p class="mt-1 text-sm text-base-content/60">{{ $businessModel->slug }}</p>

                        @if ($businessModel->description)
                            <p class="mt-1 max-w-3xl text-sm leading-6 text-base-content/65">
                                {{ $businessModel->description }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex w-full shrink-0 flex-wrap gap-2 sm:w-auto">
                    <a href="{{ route('admin.business-models.index') }}" class="btn btn-ghost flex-1 border border-base-300/70 sm:btn-sm sm:flex-none">
                        <i class="fa-solid fa-arrow-left"></i> Back
                    </a>

                    <a href="{{ route('admin.business-models.edit', $businessModel) }}" class="btn btn-primary flex-1 shadow-sm shadow-primary/20 sm:btn-sm sm:flex-none">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                </div>
            </header>

            <div class="grid grid-cols-2 divide-y divide-base-300 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                <div class="p-5">
                    <p class="text-sm text-base-content/60">Sort Order</p>
                    <p class="mt-2 text-2xl font-bold">{{ number_format($businessModel->sort_order) }}</p>
                </div>

                <div class="p-5">
                    <p class="text-sm text-base-content/60">Status</p>
                    <p class="mt-2 text-2xl font-bold">
                        {{ $businessModel->is_active ? 'Active' : 'Inactive' }}
                    </p>
                </div>

                <div class="col-span-2 p-5 sm:col-span-1">
                    <p class="text-sm text-base-content/60">Associated Companies</p>
                    <p class="mt-2 text-2xl font-bold">{{ number_format($businessModel->companies_count ?? 0) }}</p>
                </div>
            </div>
        </section>

        {{-- History --}}
        <section class="sm:px-6">
            <div class="bg-base-100">
                <div class="border-b border-base-300 px-5 py-4 sm:border-gray-300">
                    <div class="flex items-center justify-center gap-2 sm:justify-start">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                        <h2 class="font-bold">History</h2>
                    </div>
                </div>

                @php
                    $history = [
                        [
                            'label' => 'Status',
                            'value' => $businessModel->trashed() ? 'Trashed' : ($businessModel->is_active ? 'Active' : 'Inactive'),
                            'cl' => $businessModel->trashed() ? 'badge badge-warning badge-outline font-semibold' : ($businessModel->is_active ? 'badge badge-success badge-outline font-semibold' : 'badge badge-ghost font-semibold'),
                        ],
                        [
                            'label' => 'Sort Order',
                            'value' => number_format($businessModel->sort_order),
                            'cl' => 'font-semibold',
                        ],
                        [
                            'label' => 'Created At',
                            'value' => $businessModel->created_at?->format('M d, Y - H:i A') ?? 'Unknown',
                            'cl' => 'font-semibold text-blue-700',
                        ],
                        [
                            'label' => 'Updated At',
                            'value' => $businessModel->updated_at?->format('M d, Y - H:i A') ?? 'Unknown',
                            'cl' => 'text-green-700 font-semibold',
                        ],
                        ...($businessModel->trashed() ? [
                            [
                                'label' => 'Deleted At',
                                'value' => $businessModel->deleted_at?->format('M d, Y - H:i A') ?? 'Unknown',
                                'cl' => 'text-warning font-semibold',
                            ],
                        ] : []),
                    ];

                    $historyRight = [
                        [
                            'label' => 'Created By',
                            'value' => $businessModel->creator?->name ?? 'Unknown User',
                            'subvalue' => $businessModel->creator ? str_replace('_', ' ', $businessModel->creator->role) : null,
                            'cl' => 'font-black text-blue-700',
                        ],
                        [
                            'label' => 'Updated By',
                            'value' => $businessModel->updater?->name ?? 'Unknown User',
                            'subvalue' => $businessModel->updater ? str_replace('_', ' ', $businessModel->updater->role) : null,
                            'cl' => 'text-green-700 font-black',
                        ],
                        ...($businessModel->trashed() ? [
                            [
                                'label' => 'Deleted By',
                                'value' => $businessModel->deletedBy?->name ?? 'Unknown User',
                                'subvalue' => $businessModel->deletedBy ? str_replace('_', ' ', $businessModel->deletedBy->role) : null,
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

                                    @if (!empty($item['subvalue']))
                                        <div class="mt-0.5 text-[9px] font-semibold uppercase tracking-wider text-blue-500">
                                            {{ $item['subvalue'] }}
                                        </div>
                                    @endif
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
                                            <span class="mt-0.5 ml-1 text-[9px] font-bold uppercase tracking-wider
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

        {{-- Active Page Danger Zone --}}
        @if (!$businessModel->trashed())
            <section class="border border-error/20 bg-error/5 p-5">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-error"></i>
                            <h2 class="font-bold text-error">Delete this page</h2>
                        </div>

                        <div>
                            @can('delete', $businessModel)
                                <button
                                    type="button"
                                    class="btn btn-error btn-outline btn-sm gap-2"
                                    title="Move page to trash"
                                    data-admin-action
                                    data-action-url="{{ route('admin.business-models.destroy', $businessModel->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Move Business Model to Trash"
                                    data-action-description="Move “{{ $businessModel->name }}” to trash. You can restore it later from the Trash page."
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

        {{-- Trash Warning --}}
        @if ($businessModel->trashed())
            <section class="border border-warning/30 bg-warning/10 p-5">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-trash-can text-warning"></i>
                            <h2 class="font-bold text-warning">This page is in Trash</h2>
                        </div>

                        <div class="flex shrink-0 flex-wrap gap-2">
                            @can('restore', $businessModel)
                                <button
                                    type="button"
                                    class="btn btn-success gap-2"
                                    title="Restore page"
                                    data-admin-action
                                    data-action-url="{{ route('admin.business-models.restore', $businessModel->slug) }}"
                                    data-action-method="PATCH"
                                    data-action-type="success"
                                    data-action-title="Restore Business Model"
                                    data-action-description="Restore “{{ $businessModel->name }}” and return it to the active business models."
                                    data-action-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-text="Restore Page"
                                >
                                    <i class="fa-solid fa-rotate-left"></i>
                                    <span class="hidden lg:inline">Restore</span>
                                </button>
                            @endcan

                            @can('forceDelete', $businessModel)
                                <button
                                    type="button"
                                    class="btn btn-error gap-2"
                                    title="Delete permanently"
                                    data-admin-action
                                    data-action-url="{{ route('admin.business-models.force-delete', $businessModel->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Delete Business Model Permanently"
                                    data-action-description="Permanently delete “{{ $businessModel->name }}”. This action cannot be undone."
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

                    <p class="mt-1 text-justify text-sm leading-6 text-base-content/65">
                        This page is no longer part of the active platform directory, but its content is still available to authorized administrators.
                    </p>

                    @if ($businessModel->deleted_at)
                        <p class="mt-1 text-xs text-base-content/50">
                            Deleted {{ $businessModel->deleted_at->format('M d, Y \a\t H:i') }}
                        </p>
                    @endif
                </div>
            </section>
        @endif
    </div>
@endsection