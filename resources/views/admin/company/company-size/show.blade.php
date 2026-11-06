@extends('layouts.admin.app')

@section('title', $companySize->name . ' | CareerVault')
@section('page_title', 'Company Sizes / ' . $companySize->name)

@section('content')
    <div class="space-y-6">
        {{-- Overview --}}
        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <header class="flex flex-col gap-4 border-b border-base-300 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-col gap-4 border-b border-base-300 p-5 sm:flex-row sm:items-center">
                    <div class="grid size-16 shrink-0 place-items-center rounded-2xl bg-primary/10 text-primary">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
    
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-xl font-bold">{{ $companySize->name }}</h2>
    
                            @if ($companySize->is_active)
                                <span class="badge badge-success badge-outline">Active</span>
                            @else
                                <span class="badge badge-ghost">Inactive</span>
                            @endif
                        </div>
    
                        <p class="mt-1 text-sm text-base-content/60">{{ $companySize->slug }}</p>
    
                        @if ($companySize->description)
                            <p class="mt-3 max-w-3xl text-sm leading-6 text-base-content/80">
                                {{ $companySize->description }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.company-sizes.index') }}" class="btn btn-ghost btn-sm">
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </a>

                    <a href="{{ route('admin.company-sizes.edit', $companySize) }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-pen"></i>
                        Edit
                    </a>
                </div>
            </header>

            <div class="grid grid-cols-1 divide-y divide-base-300 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                <div class="p-5">
                    <p class="text-sm text-base-content/60">Minimum Employees</p>
                    <p class="mt-2 text-2xl font-bold">{{ number_format($companySize->min_employees) }}</p>
                </div>

                <div class="p-5">
                    <p class="text-sm text-base-content/60">Maximum Employees</p>
                    <p class="mt-2 text-2xl font-bold">
                        {{ $companySize->max_employees === null ? 'No limit' : number_format($companySize->max_employees) }}
                    </p>
                </div>

                <div class="p-5">
                    <p class="text-sm text-base-content/60">Associated Companies</p>
                    <p class="mt-2 text-2xl font-bold">{{ number_format($companySize->companies_count) }}</p>
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
                            'label' => 'Status', 'value' => $companySize->trashed() ? 'Trashed' : ($companySize->is_active ? 'Active' : 'Inactive'), 'cl' => $companySize->trashed() ? 'badge badge-warning badge-outline font-semibold' : ($companySize->is_active ? 'badge badge-success badge-outline font-semibold' : 'badge badge-ghost font-semibold'),
                        ], [
                            'label' => 'Created At', 'value' => $companySize->created_at->format('M d, Y - H:i A'), 'cl' => 'font-semibold text-blue-700',
                        ], [
                            'label' => 'Updated At', 'value' => $companySize->updated_at?->format('M d, Y - H:i A') ?? 'Unknown', 'cl' => 'text-green-700 font-semibold',
                        ], 
                        ...($companySize->trashed() ? [
                            [
                                'label' => 'Deleted At', 'value' => $companySize->deleted_at?->format('M d, Y - H:i A') ?? 'Unknown', 'cl' => 'text-warning font-semibold',
                            ], 
                        ] : []),
                    ];
                    $historyRight = [
                        [
                            'label' => 'Last Verified', 'value' => $companySize->last_verified_at ? $companySize->last_verified_at->format('M d, Y - H:i A') : 'Not verified', 'cl' => 'font-semibold',
                        ], [
                            'label' => 'Created By', 'value' => $companySize->createdBy?->name ?? 'Unknown User', 'subvalue' => $companySize->createdBy ? str_replace('_', ' ', $companySize->createdBy->role) : null, 'cl' => 'font-black text-blue-700',
                        ], [
                            'label' => 'Updated By', 'value' => $companySize->updatedBy?->name ?? 'Unknown User', 'cl' => 'text-green-700 font-black', 'subvalue' => $companySize->updatedBy ? str_replace('_', ' ', $companySize->updatedBy->role) : null,
                        ],
                        ...($companySize->trashed() ? [
                            [
                                'label' => 'Deleted By', 'value' => $companySize->deletedBy?->name ?? 'Unknown User', 'cl' => 'text-warning font-black', 'subvalue' => $companySize->deletedBy ? str_replace('_', ' ', $companySize->deletedBy->role) : null,
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

        {{-- =============== Active Page Danger Zone ================== --}}
        @if(!$companySize->trashed())
            <section class="border border-error/20 bg-error/5 p-5">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-error"></i>
                            <h2 class="font-bold text-error">Delete this page</h2>
                        </div>

                        <div>
                            @can('delete', $companySize)
                                <button
                                    type="button"
                                    class="btn btn-error btn-outline btn-sm gap-2"
                                    title="Move page to trash"
                                    data-admin-action
                                    data-action-url="{{ route('admin.company-sizes.destroy', $companySize->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Move Company Size to Trash"
                                    data-action-description="Move “{{ $companySize->name }}” to trash. You can restore it later from the Trash page."
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
        @if($companySize->trashed())
            <section class="border border-warning/30 bg-warning/10 p-5">
                <div class="flex flex-col gap-1">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-trash-can text-warning"></i>
                            <h2 class="font-bold text-warning">This page is in Trash</h2>
                        </div>
                        <div class="flex shrink-0 flex-wrap gap-2">
                            @can('restore', $companySize)
                                <button
                                    type="button"
                                    class="btn btn-success gap-2"
                                    title="Restore page"
                                    data-admin-action
                                    data-action-url="{{ route('admin.company-sizes.restore', $companySize->slug) }}"
                                    data-action-method="PATCH"
                                    data-action-type="success"
                                    data-action-title="Restore Company Size"
                                    data-action-description="Restore “{{ $companySize->name }}” and return it to the active company sizes."
                                    data-action-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-icon="fa-solid fa-rotate-left"
                                    data-action-confirm-text="Restore Page"
                                >
                                    <i class="fa-solid fa-rotate-left"></i>
                                    <span class="hidden lg:inline">Restore</span>
                                </button>
                            @endcan

                            @can('forceDelete', $companySize)
                                <button
                                    type="button"
                                    class="btn btn-error gap-2"
                                    title="Delete permanently"
                                    data-admin-action
                                    data-action-url="{{ route('admin.company-sizes.force-delete', $companySize->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Delete Company Size Permanently"
                                    data-action-description="Permanently delete “{{ $companySize->name }}”. This action cannot be undone."
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

                    <p class="mt-1 text-justify text-sm leading-6 text-base-content/65">This page is no longer part of the active platform directory, but its content is still available to authorized administrators.</p>

                    @if($companySize->deleted_at)
                        <p class="mt-1 text-xs text-base-content/50">Deleted {{ $companySize->deleted_at->format('M d, Y \a\t H:i') }} </p>
                    @endif
                </div>
            </section>
        @endif
    </div>
@endsection