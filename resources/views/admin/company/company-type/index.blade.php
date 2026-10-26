
@extends('layouts.admin.app')

@section('title', 'Company Types | CareerVault')
@section('page_title', 'Company Types')

@section('content')
    <div class="space-y-5 p-4 sm:p-6">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold">Company Types</h1>
                <p class="mt-1 text-sm text-base-content/60">
                    Manage business organization categories.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.company-types.trash') }}" class="btn btn-outline btn-sm">
                    <icon></icon> Trash
                    <span class="badge badge-neutral">{{ $trashedCompanyTypesCount }}</span>
                </a>
                <a href="{{ route('admin.company-types.create') }}" class="btn btn-primary btn-sm">
                    + Add Company Type
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
            <div class="stat rounded-xl border border-base-300 bg-base-100 shadow-sm">
                <div class="stat-title">Total</div>
                <div class="stat-value text-2xl">{{ $totalCompanyTypes }}</div>
            </div>
            <div class="stat rounded-xl border border-base-300 bg-base-100 shadow-sm">
                <div class="stat-title">Active</div>
                <div class="stat-value text-2xl text-success">{{ $activeCompanyTypes }}</div>
            </div>
            <div class="stat rounded-xl border border-base-300 bg-base-100 shadow-sm">
                <div class="stat-title">Inactive</div>
                <div class="stat-value text-2xl text-warning">{{ $inactiveCompanyTypes }}</div>
            </div>
            <div class="stat rounded-xl border border-base-300 bg-base-100 shadow-sm">
                <div class="stat-title">In Trash</div>
                <div class="stat-value text-2xl text-error">{{ $trashedCompanyTypesCount }}</div>
            </div>
        </div>

        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <form method="GET" action="{{ route('admin.company-types.index') }}"
                class="grid gap-3 border-b border-base-300 p-4 sm:grid-cols-2 xl:grid-cols-[1fr_200px_auto_auto]">
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search name, slug or description..."
                    class="input input-bordered w-full"
                >

                <select name="status" class="select select-bordered w-full">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.company-types.index') }}" class="btn btn-ghost">Reset</a>
            </form>

            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Sort Order</th>
                            <th>Updated By</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companyTypes as $item)
                            <tr>
                                <td class="min-w-48">
                                    <a href="{{ route('admin.company-types.show', $item) }}"
                                        class="font-semibold hover:text-primary">
                                        {{ $item->name }}
                                    </a>
                                    @if ($item->description)
                                        <p class="mt-1 max-w-sm truncate text-xs text-base-content/60">
                                            {{ $item->description }}
                                        </p>
                                    @endif
                                </td>
                                <td>
                                    <code class="text-xs">{{ $item->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-ghost' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>{{ $item->sort_order }}</td>
                                <td>{{ $item->updatedBy?->name ?? '—' }}</td>
                                <td>
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.company-types.show', $item) }}"
                                            class="btn btn-ghost btn-xs">View</a>
                                        <a href="{{ route('admin.company-types.edit', $item) }}"
                                            class="btn btn-ghost btn-xs">Edit</a>

                                        <form method="POST"
                                            action="{{ route('admin.company-types.destroy', $item) }}"
                                            onsubmit="return confirm('Move this company type to trash?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs text-error">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-base-content/60">
                                    No company types found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-base-300 p-4">
                {{ $companyTypes->links() }}
            </div>
        </section>
    </div>
@endsection
