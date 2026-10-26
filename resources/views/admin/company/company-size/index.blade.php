@extends('layouts.admin.app')

@section('title', 'Company Sizes | CareerVault')
@section('page_title', 'Company Sizes')

@section('content')
    <div class="space-y-5 p-4 sm:p-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-base-content">Company Sizes</h1>
                <p class="mt-1 text-sm text-base-content/60">
                    Manage employee ranges used to classify companies.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.company-sizes.trash') }}" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-trash-can"></i>
                    Trash
                    @if ($stats['trashedCompanySizes'] > 0)
                        <span class="badge badge-error badge-sm">{{ $stats['trashedCompanySizes'] }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.company-sizes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus"></i>
                    Add Company Size
                </a>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div role="alert" class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div role="alert" class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Statistics --}}
        <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
            <div class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-base-content/60">Total Sizes</p>
                    <span class="grid size-10 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fa-solid fa-users"></i>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold">{{ number_format($stats['totalCompanySizes']) }}</p>
            </div>

            <div class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-base-content/60">Active</p>
                    <span class="grid size-10 place-items-center rounded-lg bg-success/10 text-success">
                        <i class="fa-solid fa-circle-check"></i>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold">{{ number_format($stats['activeCompanySizes']) }}</p>
            </div>

            <div class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-base-content/60">Inactive</p>
                    <span class="grid size-10 place-items-center rounded-lg bg-warning/10 text-warning">
                        <i class="fa-solid fa-circle-pause"></i>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold">{{ number_format($stats['inactiveCompanySizes']) }}</p>
            </div>

            <div class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-base-content/60">In Trash</p>
                    <span class="grid size-10 place-items-center rounded-lg bg-error/10 text-error">
                        <i class="fa-solid fa-trash-can"></i>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold">{{ number_format($stats['trashedCompanySizes']) }}</p>
            </div>
        </div>

        {{-- Filters --}}
        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <div class="border-b border-base-300 px-4 py-3">
                <h2 class="font-semibold">Filter Company Sizes</h2>
            </div>

            <form method="GET" action="{{ route('admin.company-sizes.index') }}"
                  class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="sm:col-span-2">
                    <label for="search" class="mb-1.5 block text-sm font-medium">Search</label>
                    <input id="search" type="search" name="search" value="{{ $search }}"
                           placeholder="Search name, slug, or description..."
                           class="input input-bordered w-full">
                </div>

                <div>
                    <label for="status" class="mb-1.5 block text-sm font-medium">Status</label>
                    <select id="status" name="status" class="select select-bordered w-full">
                        <option value="">All statuses</option>
                        <option value="active" @selected($status === 'active')>Active</option>
                        <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="fa-solid fa-filter"></i>
                        Apply
                    </button>

                    <a href="{{ route('admin.company-sizes.index') }}" class="btn btn-ghost">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        {{-- Table --}}
        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <div class="flex flex-col gap-2 border-b border-base-300 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                <div>
                    <h2 class="font-semibold">All Company Sizes</h2>
                    <p class="mt-1 text-xs text-base-content/50">
                        Showing {{ $companySizes->firstItem() ?? 0 }}–{{ $companySizes->lastItem() ?? 0 }}
                        of {{ $companySizes->total() }} records
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="text-xs uppercase text-base-content/60">
                            <th>Company Size</th>
                            <th>Employee Range</th>
                            <th>Companies</th>
                            <th>Status</th>
                            <th>Sort</th>
                            <th>Updated By</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($companySizes as $companySize)
                            <tr>
                                <td class="min-w-52">
                                    <a href="{{ route('admin.company-sizes.show', $companySize) }}"
                                       class="font-semibold text-base-content hover:text-primary">
                                        {{ $companySize->name }}
                                    </a>
                                    <p class="mt-1 text-xs text-base-content/50">{{ $companySize->slug }}</p>
                                    @if ($companySize->description)
                                        <p class="mt-1 max-w-xs truncate text-xs text-base-content/60">
                                            {{ $companySize->description }}
                                        </p>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap">
                                    <span class="font-medium">{{ number_format($companySize->min_employees) }}</span>
                                    <span class="text-base-content/50">–</span>
                                    <span class="font-medium">
                                        {{ $companySize->max_employees === null ? 'Unlimited' : number_format($companySize->max_employees) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="badge badge-ghost">
                                        {{ number_format($companySize->companies_count) }}
                                    </span>
                                </td>

                                <td>
                                    @if ($companySize->is_active)
                                        <span class="badge badge-success badge-outline">Active</span>
                                    @else
                                        <span class="badge badge-ghost">Inactive</span>
                                    @endif
                                </td>

                                <td>{{ $companySize->sort_order }}</td>

                                <td class="whitespace-nowrap text-sm">
                                    {{ $companySize->updatedBy?->name ?? '—' }}
                                </td>

                                <td>
                                    <div class="flex justify-end gap-1">
                                        <a href="{{ route('admin.company-sizes.show', $companySize) }}"
                                           class="btn btn-ghost btn-sm btn-square"
                                           title="View details" aria-label="View details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.company-sizes.edit', $companySize) }}"
                                           class="btn btn-ghost btn-sm btn-square"
                                           title="Edit" aria-label="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.company-sizes.destroy', $companySize) }}"
                                              onsubmit="return confirm('Move this company size to trash?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="btn btn-ghost btn-sm btn-square text-error"
                                                    title="Move to trash" aria-label="Move to trash">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-14 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <span class="grid size-14 place-items-center rounded-2xl bg-base-200 text-base-content/40">
                                            <i class="fa-solid fa-users text-xl"></i>
                                        </span>
                                        <h3 class="mt-4 font-semibold">No company sizes found</h3>
                                        <p class="mt-1 text-sm text-base-content/60">
                                            Try changing your filters or create a new company size.
                                        </p>
                                        <a href="{{ route('admin.company-sizes.create') }}" class="btn btn-primary btn-sm mt-4">
                                            <i class="fa-solid fa-plus"></i>
                                            Add Company Size
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($companySizes->hasPages())
                <div class="border-t border-base-300 p-4">
                    {{ $companySizes->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection