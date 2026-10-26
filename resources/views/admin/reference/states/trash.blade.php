@extends('layouts.admin.app')

@section('title','State Trash')
@section('page_title', 'State / Trash')

@section('content')
    <div class="space-y-3 p-4 sm:p-6">
        {{-- Header --}}
        <x-admin.ui.page-header 
            title="States / Divisions Trash"
            title-color="text-error"
            title-bg-color="bg-error/10 text-error"
            description="Restore or permanently delete removed states and divisions." 
            icon="fa-solid fa-trash-can"
            :back-route="route('admin.states.index')"
            back-text="Back to States"
        />

        <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-2">
            <span class="badge badge-error badge-outline gap-1">
                <i class="fa-solid fa-trash text-[10px]"></i> Soft Deleted
            </span>

            <span class="badge badge-warning badge-outline gap-1">
                <i class="fa-solid fa-lock text-[10px]"></i> Super Admin Access
            </span>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
            @if($states->count())
                {{-- ========================= Desktop Table ====================== --}}
                <div class="hidden overflow-x-auto md:block bg-base-100">
                    <table class="table w-full table-zebra table-compact">

                        <thead>
                            <tr class="bg-base-200/50 text-xs uppercase tracking-wider text-base-content/50">
                                <th>State / Division</th> <th>Country</th> <th>Type</th> <th>Cities</th> <th>Deleted AT</th> <th>Deleted BY</th> <th class="text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($states as $state)
                                <tr class="hover:bg-base-200/40">
                                    <td>
                                        <a href="{{ route('admin.states.show',$state) }}" class="font-semibold"> {{ $state->name }} </a>
                                        <div class="text-xs text-base-content/50"> {{ $state->slug }} </div>
                                    </td>

                                    <td> {{ $state->country->name }} </td>
                                    <td> {{ $state->type ?: '—' }} </td>
                                    <td> {{ $state->cities_count }} </td>

                                    <td>
                                        <div class="flex items-start gap-2">
                                            <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                                <i class="fa-solid fa-clock text-xs"></i>
                                            </div>
        
                                            <div class="whitespace-nowrap">
                                                <div class="text-sm font-bold">
                                                    {{ $state->deleted_at?->format('d M Y, h:i A') }}
                                                </div>
        
                                                <div class="text-xs text-base-content/50">
                                                    {{ $state->deleted_at?->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Deleted By --}}
                                    <td class="py-4">
                                        @if($state->deletedBy)
                                            <div class="flex items-center gap-2.5">
                                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                                    <i class="fa-solid fa-user-shield text-xs"></i>
                                                </div>
        
                                                <div class="min-w-0">
                                                    <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $state->deletedBy->name }}">
                                                        {{ $state->deletedBy->name }}
                                                    </div>
        
                                                    <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                                        {{ str_replace('_', ' ', $state->deletedBy->role) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 text-xs text-base-content/35">
                                                <i class="fa-solid fa-user-slash"></i>
                                                <span>Unknown user</span>
                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="flex justify-end gap-1">
                                            @if ($state->cities_count === 0)
                                                <button
                                                    type="button"
                                                    class="btn btn-success btn-sm btn-outline gap-2"
                                                    title="Restore state"
                                                    data-admin-action
                                                    data-action-url="{{ route('admin.states.restore', $state->slug) }}"
                                                    data-action-method="PATCH"
                                                    data-action-type="success"
                                                    data-action-title="Restore Country"
                                                    data-action-description="Restore “{{ $state->name }}” and return it to the active states."
                                                    data-action-icon="fa-solid fa-rotate-left"
                                                    data-action-confirm-icon="fa-solid fa-rotate-left"
                                                    data-action-confirm-text="Restore State"
                                                >
                                                    <i class="fa-solid fa-rotate-left"></i>
        
                                                    <span class="hidden lg:inline">Restore</span>
                                                </button>
                                            @endif

                                            <button
                                                type="button"
                                                class="btn btn-error btn-sm btn-outline gap-2"
                                                title="Delete permanently"
                                                data-admin-action
                                                data-action-url="{{ route('admin.states.force-delete', $state->slug) }}"
                                                data-action-method="DELETE"
                                                data-action-type="danger"
                                                data-action-title="Delete State Permanently"
                                                data-action-description="Permanently delete “{{ $state->name }}”. This action cannot be undone."
                                                data-action-icon="fa-solid fa-triangle-exclamation"
                                                data-action-confirm-icon="fa-solid fa-trash-can"
                                                data-action-confirm-text="Delete Forever"
                                            >
                                                <i class="fa-solid fa-trash-can"></i>

                                                <span class="hidden lg:inline whitespace-nowrap">Delete Forever</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ========================= Mobile Table ====================== --}}
                <div class="divide-y divide-base-300 md:hidden">
                    @foreach($states as $state)
                        <div class="space-y-4 p-4">
                            {{-- State Header --}}
                            <div class="flex items-start gap-3">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-error/10 text-error">
                                    <i class="fa-solid fa-map-location-dot text-lg"></i>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('admin.states.show',$state) }}" class="break-words font-bold text-base-content"> {{ $state->name }} </a>
                                    <div class="mt-1 break-all text-xs text-base-content/50"> {{ $state->slug }} </div>

                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        <span class="badge badge-error badge-outline badge-sm">
                                            <i class="fa-solid fa-trash-can mr-1 text-[9px]"></i> Deleted
                                        </span>

                                        @if($state->cities_count > 0)
                                            <span class="badge badge-warning badge-outline badge-sm">
                                                <i class="fa-solid fa-city mr-1 text-[9px]"></i> {{ $state->cities_count }} {{ $state->cities_count === 1 ? 'City' : 'Cities' }}
                                            </span>
                                        @else
                                            <span class="badge badge-success badge-outline badge-sm">
                                                <i class="fa-solid fa-rotate-left mr-1 text-[9px]"></i> Restorable
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- State Information --}}
                            <div class="grid grid-cols-2 gap-3 rounded-xl border border-base-300 bg-base-200/30 p-3">
                                <div class="min-w-0">
                                    <div class="mb-1 flex items-center gap-1.5 text-xs text-base-content/50">
                                        <i class="fa-solid fa-flag"></i> Country
                                    </div>
                                    <div class="break-words text-sm font-semibold"> {{ $state->country?->name ?? 'Unknown Country' }} </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="mb-1 flex items-center gap-1.5 text-xs text-base-content/50">
                                        <i class="fa-solid fa-layer-group"></i> Type
                                    </div>
                                    <div class="break-words text-sm font-semibold"> {{ $state->type ?: '—' }} </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="mb-1 flex items-center gap-1.5 text-xs text-base-content/50">
                                        <i class="fa-solid fa-clock"></i> Deleted At
                                    </div>
                                    <div class="text-sm font-semibold"> {{ $state->deleted_at?->format('d M Y') ?? '—' }} </div>
                                    <div class="mt-0.5 text-xs text-base-content/50"> {{ $state->deleted_at?->format('h:i A') }} </div>
                                    <div class="mt-1 text-xs text-error/80"> {{ $state->deleted_at?->diffForHumans() }} </div>
                                </div>

                                <div class="min-w-0">
                                    <div class="mb-1 flex items-center gap-1.5 text-xs text-base-content/50">
                                        <i class="fa-solid fa-user-shield"></i> Deleted By
                                    </div>

                                    @if($state->deletedBy)
                                        <div class="break-words text-sm font-semibold"> {{ $state->deletedBy->name }} </div>
                                        <div class="mt-1 text-[10px] font-semibold uppercase tracking-wider text-base-content/40"> {{ str_replace('_', ' ', $state->deletedBy->role) }} </div>
                                    @else
                                        <div class="text-sm text-base-content/40">
                                            <i class="fa-solid fa-user-slash mr-1"></i> Unknown user
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col gap-2 sm:flex-row">
                                @if ($state->cities_count === 0)
                                    <button
                                        type="button"
                                        class="btn btn-success btn-outline min-h-11 flex-1 gap-2"
                                        data-admin-action
                                        data-action-url="{{ route('admin.states.restore', $state->slug) }}"
                                        data-action-method="PATCH"
                                        data-action-type="success"
                                        data-action-title="Restore State"
                                        data-action-description="Restore “{{ $state->name }}” and return it to the active states."
                                        data-action-icon="fa-solid fa-rotate-left"
                                        data-action-confirm-icon="fa-solid fa-rotate-left"
                                        data-action-confirm-text="Restore State"
                                    >
                                        <i class="fa-solid fa-rotate-left"></i> Restore State
                                    </button>
                                @endif

                                <button
                                    type="button"
                                    class="btn btn-error btn-outline min-h-11 flex-1 gap-2"
                                    data-admin-action
                                    data-action-url="{{ route('admin.states.force-delete', $state->slug) }}"
                                    data-action-method="DELETE"
                                    data-action-type="danger"
                                    data-action-title="Delete State Permanently"
                                    data-action-description="Permanently delete “{{ $state->name }}” and its associated cities. This action cannot be undone."
                                    data-action-icon="fa-solid fa-triangle-exclamation"
                                    data-action-confirm-icon="fa-solid fa-trash-can"
                                    data-action-confirm-text="Delete Forever"
                                >
                                    <i class="fa-solid fa-trash-can"></i> Delete Forever
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                @if($states->hasPages())
                    <div class="border-t border-base-300 p-4">
                        {{ $states->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="px-8 py-20 text-center">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-base-200 text-base-content/35">
                        <i class="fa-solid fa-trash-can text-3xl"></i>
                    </div>

                    <x-admin.ui.empty-state
                        title="No deleted states"
                        description="The trash is currently empty. Deleted states will appear here and can be restored while they remain in the trash."
                        :action-route="route('admin.states.index')"
                        action-text="View States"
                    />
                </div>
            @endif
        </div>
    </div>
@endsection
