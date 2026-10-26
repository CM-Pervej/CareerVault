@extends('layouts.admin.app')

@section('content')

<div class="space-y-3 p-4 sm:p-6">
    {{-- Header --}}
    <x-admin.ui.page-header
        title="States / Divisions"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage first-level administrative divisions."
        icon="fa-solid fa-map"
        :create-route="route('admin.states.create')"
        create-text="Add State / Division"
        :trash-route="route('admin.states.trash')"
        :trash-count="$trashedStatesCount"
    />

    {{-- Filter --}}
    <x-admin.ui.filters :action="route('admin.states.index')" :reset-route="route('admin.states.index')">
        {{-- Search --}}
        <x-admin.form.input 
            name="search" placeholder="Search name, slug or code..." :value="request('search')" class="xl:col-span-2"/>

            {{-- Country --}}
        <x-admin.form.select name="country" :value="request('country')">
            <option value="">All Countries</option>
            @foreach($countries as $country)
                <option value="{{ $country->slug }}" @selected(request('country') === $country->slug)>
                    {{ $country->name }}
                </option>
            @endforeach
        </x-admin.form.select>

        {{-- Types --}}
        <x-admin.form.select name="type" :value="request('type')">
            <option value="">All Types</option>
            @foreach($types as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>
                    {{ $type }}
                </option>
            @endforeach
        </x-admin.form.select>

        {{-- Status --}}
        <x-admin.form.select name="status" :value="request('status')">
            <option value="">All Status</option>
            <option value="1" @selected(request('status') === '1')>Active</option>
            <option value="0" @selected(request('status') === '0')>Inactive</option>
        </x-admin.form.select>
    </x-admin.ui.filters>

    <div class="hidden md:block card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>State / Division</th> <th>Country</th> <th>Type</th> <th>Code</th> <th>Cities</th> <th>Status</th> <th class="text-right">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($states as $state)
                        <tr class="hover">
                            <td>
                                <a href="{{ route('admin.states.show',$state) }}" class="font-semibold"> {{ $state->name }} </a>
                                <div class="text-xs text-base-content/50"> {{ $state->slug }} </div>
                            </td>

                            <td>
                                <a href="{{ route('admin.countries.show', $state->country) }}" class="link link-hover font-medium"> {{ $state->country->name }} </a>
                            </td>

                            <td>
                                @if($state->type)
                                    <span class="badge badge-outline"> {{ $state->type }} </span>
                                @else
                                    <span class="text-base-content/40">—</span>
                                @endif
                            </td>

                            <td> {{ $state->code ?: '—' }} </td>

                            <td>
                                <span class="badge badge-ghost"> {{ $state->cities_count }} </span>
                            </td>

                            <td>
                                @if($state->is_active)
                                    <span class="badge badge-success badge-sm">Active</span>
                                @else
                                    <span class="badge badge-error badge-sm">Inactive</span>
                                @endif
                            </td>

                            <td class="text-right">
                                <div class="hidden sm:flex shrink-0 items-center gap-2 xl:justify-end">
                                    <a href="{{ route('admin.states.show', $state) }}" class="btn btn-sm btn-outline gap-2">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>

                                    <div class="dropdown dropdown-center dropdown-left">
                                        <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>

                                        <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                            <li>
                                                <a href="{{ route('admin.states.show', $state) }}">
                                                    <i class="fa-solid fa-eye"></i> View state
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{ route('admin.states.edit', $state) }}">
                                                    <i class="fa-solid fa-pen-to-square"></i> Edit state
                                                </a>
                                            </li>

                                            <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                                <span>Danger zone</span>
                                            </li>

                                            <li>
                                                {{-- @can('delete',$country) --}}
                                                    <button
                                                        type="button"
                                                        class="text-error flex items-center gap-2 hover:bg-error/10"
                                                        title="Move country to trash"
                                                        data-admin-action
                                                        data-action-url="{{ route('admin.states.destroy',$state->id) }}"
                                                        data-action-method="DELETE"
                                                        data-action-type="danger"
                                                        data-action-title="Move Country to Trash"
                                                        data-action-description="Move <strong>“{{ $state->name }}”</strong> to trash. You can restore it later from the Trash state."
                                                        data-action-icon="fa-solid fa-trash-can"
                                                        data-action-confirm-icon="fa-solid fa-trash-can"
                                                        data-action-confirm-text="Move to Trash"
                                                    >
                                                        <i class="fa-solid fa-trash-can"></i>
                                                        <span> Move to Trash</span>
                                                    </button>
                                                {{-- @endcan --}}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.ui.empty-state
                                    icon="fa-solid fa-map-location-dot"
                                    title="No states or divisions found."
                                    description="Try changing your filters or add a new one."
                                    :action-route="route('admin.states.create')"
                                    action-text="Add State / Division"
                                />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile --}}
    <div class="md:hidden space-y-3">
        @forelse($states as $state)
            <x-admin.ui.mobile-card
                :title="$state->name"
                :subtitle="$state->country->name"
                :status="$state->is_active ? 'Active' : 'Inactive'"
                :status-type="$state->is_active ? 'success' : 'error'"
                icon="fa-solid fa-map"
            >
                <x-slot:details>
                    <div>
                        <div class="text-base-content/50">Type</div>
                        <div class="font-medium"> {{ $state->type ?: '—' }} </div>
                    </div>

                    <div>
                        <div class="text-base-content/50">Cities</div>
                        <div class="font-medium"> {{ $state->cities_count }} </div>
                    </div>

                    <div>
                        <div class="text-base-content/50">Code</div>
                        <div class="font-medium"> {{ $state->code ?: '—' }} </div>
                    </div>

                    <div>
                        <div class="text-base-content/50">Slug</div>
                        <div class="font-medium truncate"> {{ $state->slug }} </div>
                    </div>
                </x-slot>

                <x-slot:actions>
                    <a href="{{ route('admin.states.show', $state) }}" class="btn btn-sm btn-outline gap-2">
                        <i class="fa-solid fa-eye"></i> View
                    </a>

                    <div class="dropdown dropdown-center dropdown-left">
                        <button tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="Page actions">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>

                        <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-56 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                            <li>
                                <a href="{{ route('admin.states.show', $state) }}">
                                    <i class="fa-solid fa-eye"></i> View state
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.states.edit', $state) }}">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit state
                                </a>
                            </li>

                            <li class="menu-title mt-1 px-3 py-1 text-[10px] uppercase tracking-wider">
                                <span>Danger zone</span>
                            </li>

                            <li>
                                 @if($state->cities_count === 0)
                                    <button
                                        type="button"
                                        class="text-error flex items-center gap-2 hover:bg-error/10"
                                        title="Move country to trash"
                                        data-admin-action
                                        data-action-url="{{ route('admin.states.destroy',$state->id) }}"
                                        data-action-method="DELETE"
                                        data-action-type="danger"
                                        data-action-title="Move Country to Trash"
                                        data-action-description="Move <strong>“{{ $state->name }}”</strong> to trash. You can restore it later from the Trash state."
                                        data-action-icon="fa-solid fa-trash-can"
                                        data-action-confirm-icon="fa-solid fa-trash-can"
                                        data-action-confirm-text="Move to Trash"
                                    >
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span> Move to Trash</span>
                                    </button>
                                @endif
                            </li>
                        </ul>
                    </div>
                </x-slot>
            </x-admin.ui.mobile-card>

        @empty

            <div class="card bg-base-100 border border-base-300 shadow-sm">
                <x-admin.ui.empty-state
                    icon="fa-solid fa-map-location-dot"
                    title="No states or divisions found."
                    description="Try changing your filters or add a new one."
                    :action-route="route('admin.states.create')"
                    action-text="Add State / Division"
                />
            </div>
        @endforelse
    </div>

    @if($states->hasPages())
        <div> {{ $states->links() }} </div>
    @endif
</div>
@endsection