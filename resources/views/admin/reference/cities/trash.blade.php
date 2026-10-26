@extends('layouts.admin.app')

@section('title', 'City Trash | CareerVault')
@section('page_title', 'City / Trash')

@section('content')
<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <x-admin.ui.page-header
        icon="fa-solid fa-trash-can text-lg"    
        title="City Trash"
        title-bg-color="bg-error/10 text-error"
        title-color="text-error"
        description="Deleted cities remain safely stored here until they are restored or permanently removed. Only authorized administrators can manage deleted records."
        :back-route="route('admin.cities.index')"
        backText="Back to Cities"
    />

    <div class="hidden overflow-x-auto rounded-box border border-base-300 bg-base-100 md:block">
        <table class="table">
            <thead>
                <tr>
                    <th>City</th>
                    <th>Country</th>
                    <th>State / Division</th>
                    <th>Deleted AT</th>
                    <th>Deleted BY</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($cities as $city)
                    <tr>
                        <td>
                            <div class="font-semibold">{{ $city->name }}</div>
                            <div class="text-xs text-base-content/50">
                                {{ $city->slug }}
                            </div>
                        </td>

                        <td>{{ $city->country?->name ?? '—' }}</td>

                        <td>{{ $city->state?->name ?? '—' }}</td>
                        
                        {{-- Deleted --}}
                        <td>
                            <div class="flex items-start gap-2">
                                <div class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-error/10 text-error">
                                    <i class="fa-solid fa-clock text-xs"></i>
                                </div>

                                <div class="whitespace-nowrap">
                                    <div class="text-sm font-bold">
                                        {{ $city->deleted_at?->format('d M Y, h:i A') }}
                                    </div>

                                    <div class="text-xs text-base-content/50">
                                        {{ $city->deleted_at?->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Deleted By --}}
                        <td class="py-4">
                            @if($city->deletedBy)
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                        <i class="fa-solid fa-user-shield text-xs"></i>
                                    </div>

                                    <div class="min-w-0">
                                        <div class="max-w-[150px] truncate whitespace-nowrap text-sm font-bold" title="{{ $city->deletedBy->name }}">
                                            {{ $city->deletedBy->name }}
                                        </div>

                                        <div class="mt-0.5 whitespace-nowrap text-[10px] font-semibold uppercase tracking-wider text-base-content/40">
                                            {{ str_replace('_', ' ', $city->deletedBy->role) }}
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

                        {{-- Actions --}}
                        <td>
                            <div class="flex justify-end gap-2">
                                {{-- @can('restore', $page) --}}
                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm btn-outline gap-2"
                                        title="Restore page"
                                        data-admin-action
                                        data-action-url="{{ route('admin.cities.restore', $city->slug) }}"
                                        data-action-method="PATCH"
                                        data-action-type="success"
                                        data-action-title="Restore City"
                                        data-action-description="Restore “{{ $city->name }}” and return it to the active cities."
                                        data-action-icon="fa-solid fa-rotate-left"
                                        data-action-confirm-icon="fa-solid fa-rotate-left"
                                        data-action-confirm-text="Restore City"
                                    >
                                        <i class="fa-solid fa-rotate-left"></i>

                                        <span class="hidden lg:inline">Restore</span>
                                    </button>
                                {{-- @endcan --}}

                                {{-- @can('forceDelete', $city) --}}
                                    <button
                                        type="button"
                                        class="btn btn-error btn-sm btn-outline gap-2"
                                        title="Delete permanently"
                                        data-admin-action
                                        data-action-url="{{ route('admin.cities.force-delete', $city->slug) }}"
                                        data-action-method="DELETE"
                                        data-action-type="danger"
                                        data-action-title="Delete Platform City Permanently"
                                        data-action-description="Permanently delete “{{ $city->name }}”. This action cannot be undone."
                                        data-action-icon="fa-solid fa-triangle-exclamation"
                                        data-action-confirm-icon="fa-solid fa-trash-can"
                                        data-action-confirm-text="Delete Forever"
                                    >
                                        <i class="fa-solid fa-trash-can"></i>

                                        <span class="hidden lg:inline whitespace-nowrap">Delete Forever</span>
                                    </button>
                                {{-- @endcan --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <i class="fa-solid fa-trash-can mb-3 text-3xl text-base-content/30"></i>
                            <div class="font-semibold">Trash is empty</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="grid gap-3 md:hidden">
        @forelse($cities as $city)
            <div class="card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-4">

                    <div>
                        <h3 class="font-bold">{{ $city->name }}</h3>
                        <p class="text-xs text-base-content/50">
                            {{ $city->country?->name ?? '—' }}
                            @if($city->state)
                                · {{ $city->state->name }}
                            @endif
                        </p>
                    </div>

                    <div class="flex justify-end gap-2">
                        {{-- @can('restore', $page) --}}
                            <button
                                type="button"
                                class="btn btn-success btn-sm btn-outline gap-2"
                                title="Restore page"
                                data-admin-action
                                data-action-url="{{ route('admin.cities.restore', $city->slug) }}"
                                data-action-method="PATCH"
                                data-action-type="success"
                                data-action-title="Restore City"
                                data-action-description="Restore “{{ $city->name }}” and return it to the active cities."
                                data-action-icon="fa-solid fa-rotate-left"
                                data-action-confirm-icon="fa-solid fa-rotate-left"
                                data-action-confirm-text="Restore City"
                            >
                                <i class="fa-solid fa-rotate-left"></i>

                                <span class="hidden lg:inline">Restore</span>
                            </button>
                        {{-- @endcan --}}

                        {{-- @can('forceDelete', $city) --}}
                            <button
                                type="button"
                                class="btn btn-error btn-sm btn-outline gap-2"
                                title="Delete permanently"
                                data-admin-action
                                data-action-url="{{ route('admin.cities.force-delete', $city->slug) }}"
                                data-action-method="DELETE"
                                data-action-type="danger"
                                data-action-title="Delete Platform City Permanently"
                                data-action-description="Permanently delete “{{ $city->name }}”. This action cannot be undone."
                                data-action-icon="fa-solid fa-triangle-exclamation"
                                data-action-confirm-icon="fa-solid fa-trash-can"
                                data-action-confirm-text="Delete Forever"
                            >
                                <i class="fa-solid fa-trash-can"></i>

                                <span class="hidden lg:inline whitespace-nowrap">Delete Forever</span>
                            </button>
                        {{-- @endcan --}}
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-box border border-base-300 p-10 text-center">
                <i class="fa-solid fa-trash-can mb-3 text-3xl text-base-content/30"></i>
                <div class="font-semibold">Trash is empty</div>
            </div>
        @endforelse
    </div>

    @if($cities->hasPages())
        <div>
            {{ $cities->links() }}
        </div>
    @endif

</div>
@endsection