
@extends('layouts.admin.app')

@section('title', 'Ownership Types Trash | CareerVault')
@section('page_title', 'Ownership Types / Trash')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-error">Ownership Types Trash</h1>
            <p class="mt-1 text-sm text-base-content/60">
                Restore deleted records or permanently remove them.
            </p>
        </div>

        <a href="{{ route('admin.ownership-types.index') }}" class="btn btn-ghost btn-sm">
            ← Back to ownership types
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
        <form method="GET" action="{{ route('admin.ownership-types.trash') }}"
            class="flex flex-col gap-3 border-b border-base-300 p-4 sm:flex-row sm:items-end">
            <div class="flex-1">
                <label for="search" class="mb-1 block text-sm font-medium">
                    Search deleted records
                </label>
                <input id="search" name="search" value="{{ request('search') }}"
                    class="input input-bordered input-sm w-full"
                    placeholder="Name, slug, description">
            </div>

            <button class="btn btn-primary btn-sm" type="submit">Search</button>
            <a class="btn btn-ghost btn-sm" href="{{ route('admin.ownership-types.trash') }}">
                Reset
            </a>
        </form>

        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Deleted at</th>
                        <th>Deleted by</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ownershipTypes as $ownershipType)
                        <tr>
                            <td>
                                <div class="font-semibold">{{ $ownershipType->name }}</div>
                                <div class="max-w-sm truncate text-xs text-base-content/60">
                                    {{ $ownershipType->description ?: 'No description' }}
                                </div>
                            </td>

                            <td>
                                <code class="text-xs">{{ $ownershipType->slug }}</code>
                            </td>

                            <td class="whitespace-nowrap">
                                {{ $ownershipType->deleted_at?->format('M d, Y H:i') ?? '—' }}
                            </td>

                            <td>{{ $ownershipType->deleter?->name ?? 'Unknown' }}</td>

                            <td>
                                <div class="flex justify-end gap-1">
                                    <form method="POST"
                                        action="{{ route('admin.ownership-types.restore', $ownershipType->id) }}"
                                        onsubmit="return confirm('Restore this ownership type?')">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-ghost btn-xs" type="submit">
                                            Restore
                                        </button>
                                    </form>

                                    <form method="POST"
                                        action="{{ route('admin.ownership-types.force-delete', $ownershipType->id) }}"
                                        onsubmit="return confirm('Permanently delete this ownership type? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-ghost btn-xs text-error" type="submit">
                                            Delete forever
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-base-content/60">
                                Trash is empty.
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
