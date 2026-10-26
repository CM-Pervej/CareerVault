
@extends('layouts.admin.app')

@section('title', 'Company Types Trash | CareerVault')
@section('page_title', 'Company Types / Trash')

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
                <h1 class="text-xl font-bold text-error">Company Types Trash</h1>
                <p class="mt-1 text-sm text-base-content/60">
                    Restore removed company types or permanently delete them.
                </p>
            </div>

            <a href="{{ route('admin.company-types.index') }}" class="btn btn-outline btn-sm">
                Back to Company Types
            </a>
        </div>

        <div class="stat w-full rounded-xl border border-base-300 bg-base-100 shadow-sm sm:max-w-xs">
            <div class="stat-title">Items in Trash</div>
            <div class="stat-value text-2xl text-error">{{ $trashedCompanyTypesCount }}</div>
        </div>

        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <form method="GET" action="{{ route('admin.company-types.trash') }}"
                class="grid gap-3 border-b border-base-300 p-4 sm:grid-cols-2 xl:grid-cols-[1fr_220px_auto_auto]">
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search trashed company types..."
                    class="input input-bordered w-full"
                >

                <select name="deleted_period" class="select select-bordered w-full">
                    <option value="">Any deletion date</option>
                    <option value="today" @selected(request('deleted_period') === 'today')>Today</option>
                    <option value="yesterday" @selected(request('deleted_period') === 'yesterday')>Yesterday</option>
                    <option value="last_7_days" @selected(request('deleted_period') === 'last_7_days')>Last 7 days</option>
                    <option value="last_30_days" @selected(request('deleted_period') === 'last_30_days')>Last 30 days</option>
                    <option value="last_3_months" @selected(request('deleted_period') === 'last_3_months')>Last 3 months</option>
                    <option value="this_year" @selected(request('deleted_period') === 'this_year')>This year</option>
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.company-types.trash') }}" class="btn btn-ghost">Reset</a>
            </form>

            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Company Type</th>
                            <th>Slug</th>
                            <th>Deleted By</th>
                            <th>Deleted At</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companyTypes as $item)
                            <tr>
                                <td class="min-w-44">
                                    <span class="font-semibold">{{ $item->name }}</span>
                                    @if ($item->description)
                                        <p class="mt-1 max-w-sm truncate text-xs text-base-content/60">
                                            {{ $item->description }}
                                        </p>
                                    @endif
                                </td>

                                <td><code class="text-xs">{{ $item->slug }}</code></td>
                                <td>{{ $item->deletedBy?->name ?? '—' }}</td>
                                <td class="whitespace-nowrap">
                                    {{ $item->deleted_at?->format('M d, Y h:i A') ?? '—' }}
                                </td>

                                <td>
                                    <div class="flex justify-end gap-2">
                                        <form method="POST"
                                            action="{{ route('admin.company-types.restore', $item->id) }}"
                                            onsubmit="return confirm('Restore this company type?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-outline btn-xs">
                                                Restore
                                            </button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('admin.company-types.force-delete', $item->id) }}"
                                            onsubmit="return confirm('Permanently delete this company type? This cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-error btn-outline btn-xs">
                                                Delete Forever
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-base-content/60">
                                    The trash is empty.
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
