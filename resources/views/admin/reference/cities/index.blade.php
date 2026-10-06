@extends('layouts.admin.app')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li>Reference</li>
                    <li>Cities</li>
                </ul>
            </div>

            <h1 class="mt-1 text-2xl font-bold">Cities</h1>
            <p class="text-sm text-base-content/60">
                Manage cities and their geographic relationships.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.cities.trash') }}" class="btn btn-ghost btn-sm">
                <i class="fa-solid fa-trash-can"></i>
                Trash
            </a>

            <a href="{{ route('admin.cities.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i>
                Add City
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="card border border-base-300 bg-base-100 shadow-sm">
        <div class="card-body p-4">

            <form method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-5">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search city..."
                       class="input input-bordered w-full">

                <select name="country" class="select select-bordered w-full">
                    <option value="">All countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->slug }}"
                            @selected(request('country') === $country->slug)>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>

                <select name="state" class="select select-bordered w-full">
                    <option value="">All states / divisions</option>
                    @foreach($states as $state)
                        <option value="{{ $state->slug }}"
                            @selected(request('state') === $state->slug)>
                            {{ $state->name }} — {{ $state->country->name }}
                        </option>
                    @endforeach
                </select>

                <select name="capital" class="select select-bordered w-full">
                    <option value="">All cities</option>
                    <option value="1" @selected(request('capital') === '1')>
                        Capitals
                    </option>
                    <option value="0" @selected(request('capital') === '0')>
                        Non-capitals
                    </option>
                </select>

                <select name="status" class="select select-bordered w-full">
                    <option value="">All status</option>
                    <option value="1" @selected(request('status') === '1')>
                        Active
                    </option>
                    <option value="0" @selected(request('status') === '0')>
                        Inactive
                    </option>
                </select>

                <div class="flex gap-2 md:col-span-2 xl:col-span-5">
                    <button class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'country', 'state', 'capital', 'status']))
                        <a href="{{ route('admin.cities.index') }}"
                           class="btn btn-ghost btn-sm">
                            Clear
                        </a>
                    @endif
                </div>

            </form>

        </div>
    </div>

    <div class="hidden overflow-x-auto rounded-box border border-base-300 bg-base-100 md:block">
        <table class="table">
            <thead>
                <tr>
                    <th>City</th>
                    <th>Country</th>
                    <th>State / Division</th>
                    <th>Type</th>
                    <th>Status</th>
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
                                @if($city->code)
                                    · {{ $city->code }}
                                @endif
                            </div>
                        </td>

                        <td>
                            {{ $city->country?->name ?? '—' }}
                        </td>

                        <td>
                            {{ $city->state?->name ?? '—' }}
                        </td>

                        <td>
                            @if($city->is_capital)
                                <span class="badge badge-warning badge-sm">
                                    <i class="fa-solid fa-star mr-1 text-[9px]"></i>
                                    Capital
                                </span>
                            @else
                                <span class="text-sm text-base-content/60">
                                    City
                                </span>
                            @endif
                        </td>

                        <td>
                            @if($city->is_active)
                                <span class="badge badge-success badge-sm">Active</span>
                            @else
                                <span class="badge badge-ghost badge-sm">Inactive</span>
                            @endif
                        </td>

                        <td>
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.cities.show', $city) }}"
                                   class="btn btn-ghost btn-xs"
                                   title="View">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.cities.edit', $city) }}"
                                   class="btn btn-ghost btn-xs"
                                   title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.cities.destroy', $city) }}"
                                      onsubmit="return confirm('Move this city to trash?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="btn btn-ghost btn-xs text-error"
                                            title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <i class="fa-solid fa-city mb-3 text-3xl text-base-content/30"></i>
                            <div class="font-semibold">No cities found</div>
                            <div class="text-sm text-base-content/50">
                                Try changing your filters or add a new city.
                            </div>
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

                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <h3 class="font-bold">{{ $city->name }}</h3>
                            <p class="text-xs text-base-content/50">
                                {{ $city->slug }}
                            </p>
                        </div>

                        @if($city->is_active)
                            <span class="badge badge-success badge-sm">Active</span>
                        @else
                            <span class="badge badge-ghost badge-sm">Inactive</span>
                        @endif
                    </div>

                    <div class="mt-2 space-y-1 text-sm">
                        <div>
                            <span class="text-base-content/50">Country:</span>
                            {{ $city->country?->name ?? '—' }}
                        </div>

                        <div>
                            <span class="text-base-content/50">State:</span>
                            {{ $city->state?->name ?? '—' }}
                        </div>

                        @if($city->is_capital)
                            <div>
                                <span class="badge badge-warning badge-sm">
                                    <i class="fa-solid fa-star mr-1 text-[9px]"></i>
                                    Capital
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="mt-3 flex justify-end gap-1">
                        <a href="{{ route('admin.cities.show', $city) }}"
                           class="btn btn-ghost btn-xs">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a href="{{ route('admin.cities.edit', $city) }}"
                           class="btn btn-ghost btn-xs">
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form method="POST"
                              action="{{ route('admin.cities.destroy', $city) }}"
                              onsubmit="return confirm('Move this city to trash?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-ghost btn-xs text-error">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="rounded-box border border-base-300 p-10 text-center">
                <i class="fa-solid fa-city mb-3 text-3xl text-base-content/30"></i>
                <div class="font-semibold">No cities found</div>
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