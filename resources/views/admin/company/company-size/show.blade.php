@extends('layouts.admin.app')

@section('title', $companySize->name . ' | CareerVault')
@section('page_title', 'Company Sizes / Details')

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
                    <span>Details</span>
                </div>

                <h1 class="text-2xl font-bold tracking-tight">{{ $companySize->name }}</h1>
                <p class="mt-1 text-sm text-base-content/60">
                    Detailed information about this company-size category.
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.company-sizes.index') }}" class="btn btn-ghost btn-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back
                </a>

                <a href="{{ route('admin.company-sizes.edit', $companySize) }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-pen"></i>
                    Edit
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

        {{-- Overview --}}
        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <div class="flex flex-col gap-4 border-b border-base-300 p-5 sm:flex-row sm:items-center">
                <div class="grid size-16 shrink-0 place-items-center rounded-2xl bg-primary/10 text-primary">
                    <i class="fa-solid fa-users text-2xl"></i>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-bold">{{ $companySize->name }}</h2>

                        @if ($companySize->is_active)
                            <span class="badge badge-success badge-outline">Active</span>
                        @else
                            <span class="badge badge-ghost">Inactive</span>
                        @endif
                    </div>

                    <p class="mt-1 text-sm text-base-content/60">{{ $companySize->slug }}</p>

                    @if ($companySize->description)
                        <p class="mt-3 max-w-3xl text-sm leading-6 text-base-content/80">
                            {{ $companySize->description }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 divide-y divide-base-300 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                <div class="p-5">
                    <p class="text-sm text-base-content/60">Minimum Employees</p>
                    <p class="mt-2 text-2xl font-bold">{{ number_format($companySize->min_employees) }}</p>
                </div>

                <div class="p-5">
                    <p class="text-sm text-base-content/60">Maximum Employees</p>
                    <p class="mt-2 text-2xl font-bold">
                        {{ $companySize->max_employees === null ? 'No limit' : number_format($companySize->max_employees) }}
                    </p>
                </div>

                <div class="p-5">
                    <p class="text-sm text-base-content/60">Associated Companies</p>
                    <p class="mt-2 text-2xl font-bold">{{ number_format($companySize->companies_count) }}</p>
                </div>
            </div>
        </section>

        {{-- Record Information --}}
        <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <div class="border-b border-base-300 px-5 py-4">
                <h2 class="font-semibold">Record Information</h2>
            </div>

            <dl class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2 xl:grid-cols-3">
                <div>
                    <dt class="text-sm text-base-content/60">Sort Order</dt>
                    <dd class="mt-1 font-medium">{{ $companySize->sort_order }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Created By</dt>
                    <dd class="mt-1 font-medium">{{ $companySize->createdBy?->name ?? 'Unknown' }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Updated By</dt>
                    <dd class="mt-1 font-medium">{{ $companySize->updatedBy?->name ?? 'Unknown' }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Created At</dt>
                    <dd class="mt-1 font-medium">{{ $companySize->created_at?->format('M d, Y h:i A') ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Last Updated</dt>
                    <dd class="mt-1 font-medium">{{ $companySize->updated_at?->format('M d, Y h:i A') ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Record ID</dt>
                    <dd class="mt-1 font-medium">{{ $companySize->id }}</dd>
                </div>
            </dl>
        </section>

        {{-- Actions --}}
        <section class="flex flex-col gap-3 rounded-xl border border-base-300 bg-base-100 p-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold">Manage Company Size</h2>
                <p class="mt-1 text-sm text-base-content/60">
                    Remove this category from the active list by moving it to trash.
                </p>
            </div>

            <form method="POST"
                  action="{{ route('admin.company-sizes.destroy', $companySize) }}"
                  onsubmit="return confirm('Move this company size to trash?')">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-error btn-outline btn-sm">
                    <i class="fa-solid fa-trash-can"></i>
                    Move to Trash
                </button>
            </form>
        </section>
    </div>
@endsection