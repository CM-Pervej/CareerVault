@extends('layouts.admin.app')

@section('title', 'Company Sizes Trash | CareerVault')
@section('page_title', 'Company Sizes / Trash')

@section('content')
    <div class="space-y-5 p-4 sm:p-6">
        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm text-base-content/50">
                    <a href="{{ route('admin.company-sizes.index') }}" class="hover:text-primary">
                        Company Sizes
                    </a>
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                    <span class="text-error">Trash</span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight text-error">Company Sizes Trash</h1>
                <p class="mt-1 text-sm text-base-content/60">
                    Review deleted categories, restore them, or permanently remove eligible records.
                </p>
            </div>

            <a href="{{ route('admin.company-sizes.index') }}" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Company Sizes
            </a>
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
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-base-content/60">Total in Trash</p>
                    <span class="grid size-10 place-items-center rounded-lg bg-error/10 text-error">
                        <i class="fa-solid fa-trash-can"></i>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold">{{ number_format($stats['totalTrashed']) }}</p>
            </div>

            <div class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-base-content/60">Deleted Today</p>
                    <span class="grid size-10 place-items-center rounded-lg bg-warning/10 text-warning">
                        <i class="fa-solid fa-calendar-day"></i>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold">{{ number_format($stats['deletedToday']) }}</p>
            </div>

            <div class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-base-content/60">Deleted This Week</p>
                    <span class="grid size-10 place-items-center rounded-lg bg-info/10 text-info">
                        <i class="fa-solid fa-calendar-week"></i>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold">{{ number_format($stats['deletedThisWeek']) }}</p>
            </div>
        </div>

        {{-- Filters --}}
        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <div class="border-b border-base-300 px-4 py-3">
                <h2 class="font-semibold">Filter Deleted Records</h2>
            </div>

            <form method="GET" action="{{ route('admin.company-sizes.trash') }}"
                  class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label for="search" class="mb-1.5 block text-sm font-medium">Search</label>
                    <input id="search" type="search" name="search" value="{{ $search }}"
                           placeholder="Name, slug, or description..."
                           class="input input-bordered w-full">
                </div>

                <div>
                    <label for="deleted_by" class="mb-1.5 block text-sm font-medium">Deleted By</label>
                    <select id="deleted_by" name="deleted_by" class="select select-bordered w-full">
                        <option value="">All users</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" @selected((string) $deletedBy === (string) $user->id)>
                                {{ $user->name }}{{ $user->role ? ' (' . $user->role . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="deleted_period" class="mb-1.5 block text-sm font-medium">Deletion Period</label>
                    <select id="deleted_period" name="deleted_period" class="select select-bordered w-full">
                        <option value="">Any time</option>
                        <option value="today" @selected($deletedPeriod === 'today')>Today</option>
                        <option value="yesterday" @selected($deletedPeriod === 'yesterday')>Yesterday</option>
                        <option value="last_7_days" @selected($deletedPeriod === 'last_7_days')>Last 7 days</option>
                        <option value="last_30_days" @selected($deletedPeriod === 'last_30_days')>Last 30 days</option>
                        <option value="last_3_months" @selected($deletedPeriod === 'last_3_months')>Last 3 months</option>
                        <option value="this_year" @selected($deletedPeriod === 'this_year')>This year</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <i class="fa-solid fa-filter"></i>
                        Apply
                    </button>

                    <a href="{{ route('admin.company-sizes.trash') }}" class="btn btn-ghost">
                        Reset
                    </a>
                </div>
            </form>
        </section>

        {{-- Trash Table --}}
        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <div class="flex flex-col gap-2 border-b border-base-300 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-5">
                <div>
                    <h2 class="font-semibold">Deleted Company Sizes</h2>
                    <p class="mt-1 text-xs text-base-content/50">
                        Showing {{ $companySizes->firstItem() ?? 0 }}–{{ $companySizes->lastItem() ?? 0 }}
                        of {{ $companySizes->total() }} records
                    </p>
                </div>
                <span class="badge badge-error badge-outline">Trash</span>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="text-xs uppercase text-base-content/60">
                            <th>Company Size</th>
                            <th>Employee Range</th>
                            <th>Companies</th>
                            <th>Deleted By</th>
                            <th>Deleted At</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($companySizes as $companySize)
                            <tr>
                                <td class="min-w-48">
                                    <p class="font-semibold">{{ $companySize->name }}</p>
                                    <p class="mt-1 text-xs text-base-content/50">{{ $companySize->slug }}</p>
                                </td>

                                <td class="whitespace-nowrap">
                                    {{ number_format($companySize->min_employees) }}
                                    –
                                    {{ $companySize->max_employees === null ? 'Unlimited' : number_format($companySize->max_employees) }}
                                </td>

                                <td>
                                    <span class="badge badge-ghost">{{ number_format($companySize->companies_count) }}</span>
                                </td>

                                <td class="whitespace-nowrap text-sm">
                                    @if ($companySize->deletedBy)
                                        <span class="font-medium">{{ $companySize->deletedBy->name }}</span>
                                        @if ($companySize->deletedBy->role)
                                            <p class="mt-1 text-xs text-base-content/50">
                                                {{ $companySize->deletedBy->role }}
                                            </p>
                                        @endif
                                    @else
                                        <span class="text-base-content/50">Unknown</span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap text-sm">
                                    {{ $companySize->deleted_at?->format('M d, Y') }}
                                    <p class="mt-1 text-xs text-base-content/50">
                                        {{ $companySize->deleted_at?->format('h:i A') }}
                                    </p>
                                </td>

                                <td>
                                    <div class="flex justify-end gap-1">
                                        <form method="POST"
                                              action="{{ route('admin.company-sizes.restore', $companySize) }}"
                                              onsubmit="return confirm('Restore this company size?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-ghost btn-sm btn-square text-success"
                                                    title="Restore" aria-label="Restore">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('admin.company-sizes.force-delete', $companySize) }}"
                                              onsubmit="return confirm('Permanently delete this company size? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-ghost btn-sm btn-square text-error"
                                                    title="Permanently delete" aria-label="Permanently delete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-14 text-center">
                                    <div class="mx-auto flex max-w-sm flex-col items-center">
                                        <span class="grid size-14 place-items-center rounded-2xl bg-base-200 text-base-content/40">
                                            <i class="fa-solid fa-trash-can text-xl"></i>
                                        </span>
                                        <h3 class="mt-4 font-semibold">Trash is empty</h3>
                                        <p class="mt-1 text-sm text-base-content/60">
                                            No deleted company sizes match your filters.
                                        </p>
                                        <a href="{{ route('admin.company-sizes.index') }}" class="btn btn-outline btn-sm mt-4">
                                            View Company Sizes
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