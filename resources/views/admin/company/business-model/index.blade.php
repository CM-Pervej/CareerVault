@extends('layouts.admin.app')

@section('title', 'Business Models | CareerVault')
@section('page_title', 'Business Models')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold">Business Models</h1>
            <p class="mt-1 text-sm text-base-content/60">Manage the business models used in your company directory.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.business-models.trash') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-trash"></i>
                Trash
                <span class="badge badge-sm">{{ $trashedBusinessModelsCount }}</span>
            </a>
            <a href="{{ route('admin.business-models.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i>
                Add Business Model
            </a>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        <div class="rounded-xl border border-base-300 bg-base-100 p-4">
            <p class="text-sm text-base-content/60">Total</p>
            <p class="mt-1 text-2xl font-bold">{{ number_format($totalBusinessModels) }}</p>
        </div>
        <div class="rounded-xl border border-success/20 bg-success/5 p-4">
            <p class="text-sm text-base-content/60">Active</p>
            <p class="mt-1 text-2xl font-bold text-success">{{ number_format($activeBusinessModels) }}</p>
        </div>
        <div class="rounded-xl border border-warning/20 bg-warning/5 p-4">
            <p class="text-sm text-base-content/60">Inactive</p>
            <p class="mt-1 text-2xl font-bold text-warning">{{ number_format($inactiveBusinessModels) }}</p>
        </div>
        <div class="rounded-xl border border-error/20 bg-error/5 p-4">
            <p class="text-sm text-base-content/60">In Trash</p>
            <p class="mt-1 text-2xl font-bold text-error">{{ number_format($trashedBusinessModelsCount) }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <section class="rounded-xl border border-base-300 bg-base-100 p-4">
        <form method="GET" action="{{ route('admin.business-models.index') }}" class="grid grid-cols-1 items-end gap-3 md:grid-cols-3">
            <div>
                <label for="search" class="mb-1.5 block text-sm font-medium">Search</label>
                <input id="search" type="search" name="search" value="{{ $search }}" class="input input-bordered input-sm w-full" placeholder="Name, slug or description">
            </div>

            <div>
                <label for="status" class="mb-1.5 block text-sm font-medium">Status</label>
                <select id="status" name="status" class="select select-bordered select-sm w-full">
                    <option value="">All statuses</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <a href="{{ route('admin.business-models.index') }}" class="btn btn-ghost btn-sm">Reset</a>
            </div>
        </form>
    </section>

    {{-- Table --}}
    <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100">
        <div class="flex items-center justify-between border-b border-base-300 px-4 py-3">
            <h2 class="font-semibold">All Business Models</h2>
            <span class="text-sm text-base-content/60">{{ $businessModels->total() }} records</span>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th class="w-16">#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($businessModels as $businessModel)
                        <tr>
                            <td>{{ $businessModels->firstItem() + $loop->index }}</td>
                            <td>
                                <a href="{{ route('admin.business-models.show', $businessModel) }}" class="font-semibold link link-hover">
                                    {{ $businessModel->name }}
                                </a>
                                @if ($businessModel->description)
                                    <p class="mt-1 max-w-sm truncate text-xs text-base-content/60">
                                        {{ $businessModel->description }}
                                    </p>
                                @endif
                            </td>
                            <td><code class="text-xs">{{ $businessModel->slug }}</code></td>
                            <td>
                                <span class="badge {{ $businessModel->is_active ? 'badge-success' : 'badge-ghost' }}">
                                    {{ $businessModel->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $businessModel->sort_order }}</td>
                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.business-models.show', $businessModel) }}" class="btn btn-ghost btn-xs" title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.business-models.edit', $businessModel) }}" class="btn btn-ghost btn-xs" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.business-models.destroy', $businessModel) }}" onsubmit="return confirm('Move this business model to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-xs text-error" title="Move to trash">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <i class="fa-solid fa-layer-group mb-3 text-3xl text-base-content/30"></i>
                                <p class="font-medium">No business models found</p>
                                <p class="mt-1 text-sm text-base-content/60">Try changing your filters or create a new record.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($businessModels->hasPages())
            <div class="border-t border-base-300 p-4">
                {{ $businessModels->links() }}
            </div>
        @endif
    </section>
</div>
@endsection