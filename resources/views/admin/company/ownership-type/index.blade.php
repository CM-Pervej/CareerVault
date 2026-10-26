
@extends('layouts.admin.app')

@section('title', 'Ownership Types | CareerVault')
@section('page_title', 'Ownership Types')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-base-content">Ownership Types</h1>
            <p class="mt-1 text-sm text-base-content/60">
                Manage ownership structures used to describe companies.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.ownership-types.trash') }}" class="btn btn-outline btn-sm">
                Trash
            </a>
            <a href="{{ route('admin.ownership-types.create') }}" class="btn btn-primary btn-sm">
                Add ownership type
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach ([
            ['Total', $stats['total']],
            ['Active', $stats['active']],
            ['Inactive', $stats['inactive']],
            ['In trash', $stats['trashed']],
        ] as [$label, $value])
            <div class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm">
                <p class="text-sm text-base-content/60">{{ $label }}</p>
                <p class="mt-1 text-2xl font-bold tabular-nums">
                    {{ number_format($value) }}
                </p>
            </div>
        @endforeach
    </div>

    <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <form method="GET" action="{{ route('admin.ownership-types.index') }}"
            class="grid gap-3 border-b border-base-300 p-4 sm:grid-cols-[minmax(0,1fr)_12rem_auto_auto] sm:items-end">
            <div>
                <label for="search" class="mb-1 block text-sm font-medium">Search</label>
                <input id="search" name="search" value="{{ request('search') }}"
                    class="input input-bordered input-sm w-full"
                    placeholder="Name, slug, description">
            </div>

            <div>
                <label for="status" class="mb-1 block text-sm font-medium">Status</label>
                <select id="status" name="status" class="select select-bordered select-sm w-full">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>
            </div>

            <button class="btn btn-primary btn-sm" type="submit">Filter</button>
            <a class="btn btn-ghost btn-sm" href="{{ route('admin.ownership-types.index') }}">
                Reset
            </a>
        </form>

        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Sort order</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ownershipTypes as $ownershipType)
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $ownershipType->name }}</div>
                                <div class="mt-0.5 max-w-sm truncate text-xs text-base-content/60">
                                    {{ $ownershipType->description ?: 'No description' }}
                                </div>
                            </td>
                            <td>
                                <code class="text-xs">{{ $ownershipType->slug }}</code>
                            </td>
                            <td>
                                <span class="badge {{ $ownershipType->is_active ? 'badge-success' : 'badge-ghost' }}">
                                    {{ $ownershipType->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $ownershipType->sort_order }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.ownership-types.show', $ownershipType) }}"
                                        class="btn btn-ghost btn-xs">View</a>

                                    <a href="{{ route('admin.ownership-types.edit', $ownershipType) }}"
                                        class="btn btn-ghost btn-xs">Edit</a>

                                    <form method="POST"
                                        action="{{ route('admin.ownership-types.destroy', $ownershipType) }}"
                                        onsubmit="return confirm('Move this ownership type to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-error">
                                            Trash
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-base-content/60">
                                No ownership types found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-base-300 p-4">
            {{ $ownershipTypes->links() }}
        </div>
    </section>
</div>
@endsection
