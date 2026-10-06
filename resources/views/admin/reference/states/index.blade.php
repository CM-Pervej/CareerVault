@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">

        <div>
            <div class="breadcrumbs text-sm opacity-60">
                <ul>
                    <li>
                        <a href="{{ route('admin.countries.index') }}">
                            Countries
                        </a>
                    </li>
                    <li>{{ $country->name }}</li>
                    <li>States</li>
                </ul>
            </div>

            <h1 class="text-2xl font-bold mt-2">
                {{ $country->name }} States / Divisions
            </h1>

            <p class="text-sm opacity-60 mt-1">
                Manage administrative subdivisions of {{ $country->name }}.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('admin.countries.states.trash', $country) }}"
                class="btn btn-ghost"
            >
                <i class="fa-solid fa-trash-can"></i>
                Trash
            </a>

            <a
                href="{{ route('admin.countries.states.create', $country) }}"
                class="btn btn-primary"
            >
                <i class="fa-solid fa-plus"></i>
                Add State
            </a>

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success mb-5">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-5">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form method="GET" class="card bg-base-100 border border-base-300 shadow-sm mb-5">
        <div class="card-body p-4">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search states..."
                    class="input input-bordered w-full"
                >

                <select name="type" class="select select-bordered w-full">
                    <option value="">All types</option>

                    @foreach($types as $type)
                        <option
                            value="{{ $type }}"
                            @selected(request('type') === $type)
                        >
                            {{ $type }}
                        </option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <select name="status" class="select select-bordered flex-1">
                        <option value="">All statuses</option>
                        <option value="1" @selected(request('status') === '1')>
                            Active
                        </option>
                        <option value="0" @selected(request('status') === '0')>
                            Inactive
                        </option>
                    </select>

                    <button class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i>
                    </button>

                    <a
                        href="{{ route('admin.countries.states.index', $country) }}"
                        class="btn btn-ghost"
                    >
                        Reset
                    </a>
                </div>

            </div>

        </div>
    </form>

    <div class="hidden md:block overflow-x-auto bg-base-100 border border-base-300 rounded-2xl shadow-sm">

        <table class="table">

            <thead>
                <tr>
                    <th>State / Division</th>
                    <th>Code</th>
                    <th>Type</th>
                    <th>Cities</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>

            <tbody>

                @forelse($states as $state)

                    <tr class="hover">

                        <td>
                            <a
                                href="{{ route('admin.countries.states.show', [$country, $state]) }}"
                                class="font-semibold hover:text-primary"
                            >
                                {{ $state->name }}
                            </a>

                            <div class="text-xs opacity-50">
                                {{ $state->slug }}
                            </div>
                        </td>

                        <td>
                            @if($state->code)
                                <span class="badge badge-ghost font-mono">
                                    {{ $state->code }}
                                </span>
                            @else
                                <span class="opacity-40">—</span>
                            @endif
                        </td>

                        <td>
                            {{ $state->type ?: '—' }}
                        </td>

                        <td>
                            <span class="badge badge-outline">
                                {{ $state->cities_count }}
                            </span>
                        </td>

                        <td>
                            @if($state->is_active)
                                <span class="badge badge-success badge-sm">
                                    Active
                                </span>
                            @else
                                <span class="badge badge-ghost badge-sm">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="flex justify-end gap-1">

                                <a
                                    href="{{ route('admin.countries.states.show', [$country, $state]) }}"
                                    class="btn btn-ghost btn-sm"
                                    title="View"
                                >
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a
                                    href="{{ route('admin.countries.states.edit', [$country, $state]) }}"
                                    class="btn btn-ghost btn-sm"
                                    title="Edit"
                                >
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.countries.states.destroy', [$country, $state]) }}"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="btn btn-ghost btn-sm text-error"
                                        title="Move to trash"
                                        onclick="return confirm('Move this state / division to trash?')"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center py-12">

                            <div class="opacity-50">
                                <i class="fa-solid fa-map-location-dot text-3xl mb-3"></i>
                                <p>No states / divisions found.</p>
                            </div>

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="md:hidden space-y-3">

        @forelse($states as $state)

            <div class="card bg-base-100 border border-base-300 shadow-sm">

                <div class="card-body p-4">

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">
                            <a
                                href="{{ route('admin.countries.states.show', [$country, $state]) }}"
                                class="font-bold hover:text-primary"
                            >
                                {{ $state->name }}
                            </a>

                            <div class="text-xs opacity-50">
                                {{ $state->code ?: $state->slug }}
                            </div>
                        </div>

                        @if($state->is_active)
                            <span class="badge badge-success badge-sm">
                                Active
                            </span>
                        @else
                            <span class="badge badge-ghost badge-sm">
                                Inactive
                            </span>
                        @endif

                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-3">

                        <div class="bg-base-200 rounded-lg p-3">
                            <div class="text-xs opacity-50">Type</div>
                            <div class="font-medium mt-1">
                                {{ $state->type ?: '—' }}
                            </div>
                        </div>

                        <div class="bg-base-200 rounded-lg p-3">
                            <div class="text-xs opacity-50">Cities</div>
                            <div class="font-medium mt-1">
                                {{ $state->cities_count }}
                            </div>
                        </div>

                    </div>

                    <div class="flex justify-end gap-1 mt-3">

                        <a
                            href="{{ route('admin.countries.states.show', [$country, $state]) }}"
                            class="btn btn-sm btn-ghost"
                        >
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        <a
                            href="{{ route('admin.countries.states.edit', [$country, $state]) }}"
                            class="btn btn-sm btn-ghost"
                        >
                            <i class="fa-solid fa-pen"></i>
                        </a>

                        <form
                            method="POST"
                            action="{{ route('admin.countries.states.destroy', [$country, $state]) }}"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-sm btn-ghost text-error"
                                onclick="return confirm('Move this state / division to trash?')"
                            >
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        @empty

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body text-center py-12 opacity-50">
                    <i class="fa-solid fa-map-location-dot text-3xl mb-3"></i>
                    <p>No states / divisions found.</p>
                </div>
            </div>

        @endforelse

    </div>

    <div class="mt-5">
        {{ $states->links() }}
    </div>

</div>
@endsection