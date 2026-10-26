
@extends('layouts.admin.app')

@section('title', 'Companies | CareerVault')
@section('page_title', 'Companies')

@section('content')
@php
    $statusLabels = ['active' => 'Active', 'inactive' => 'Inactive', 'draft' => 'Draft', 'archived' => 'Archived'];
    $statusClasses = ['active' => 'badge-success', 'inactive' => 'badge-warning', 'draft' => 'badge-ghost', 'archived' => 'badge-error'];
    $operatingStatuses = ['operating' => 'Operating', 'acquired' => 'Acquired', 'merged' => 'Merged', 'closed' => 'Closed', 'dissolved' => 'Dissolved', 'unknown' => 'Unknown'];
@endphp

<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-base-content/60">CareerVault / Directory</p>
            <h1 class="text-2xl font-bold">Companies</h1>
            <p class="mt-1 text-sm text-base-content/60">Manage company information, classifications, locations, and platform profiles.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.companies.trash') }}" class="btn btn-outline">
                <i class="fa-solid fa-trash-can mr-2"></i> Trash
                <span class="badge badge-sm">{{ $stats['trash'] }}</span>
            </a>
            <a href="{{ route('admin.companies.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus mr-2"></i> Add Company
            </a>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach([
            ['label' => 'Total Companies', 'value' => $stats['total'], 'icon' => 'fa-building', 'tone' => 'text-primary'],
            ['label' => 'Active', 'value' => $stats['active'], 'icon' => 'fa-circle-check', 'tone' => 'text-success'],
            ['label' => 'Verified', 'value' => $stats['verified'], 'icon' => 'fa-shield-halved', 'tone' => 'text-info'],
            ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'fa-star', 'tone' => 'text-warning'],
        ] as $stat)
            <div class="rounded-xl border border-base-300 bg-base-100 p-4">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-sm text-base-content/60">{{ $stat['label'] }}</span>
                    <i class="fa-solid {{ $stat['icon'] }} {{ $stat['tone'] }}"></i>
                </div>
                <div class="mt-2 text-2xl font-bold tabular-nums">{{ number_format($stat['value']) }}</div>
            </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <details class="rounded-xl border border-base-300 bg-base-100" open>
        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-3 font-semibold sm:px-5">
            <span><i class="fa-solid fa-filter mr-2 text-base-content/60"></i>Search and Filters</span>
            <i class="fa-solid fa-chevron-down text-xs text-base-content/50"></i>
        </summary>

        <form method="GET" action="{{ route('admin.companies.index') }}" class="border-t border-base-300 p-4 sm:p-5">
            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <label class="form-control sm:col-span-2">
                    <span class="label-text mb-1">Search</span>
                    <input type="search" name="search" value="{{ $search }}" class="input input-bordered w-full" placeholder="Company name, legal name, tagline, website...">
                </label>

                <label class="form-control">
                    <span class="label-text mb-1">Company Type</span>
                    <select name="company_type_id" class="select select-bordered w-full">
                        <option value="">All types</option>
                        @foreach($companyTypes as $type)
                            <option value="{{ $type->id }}" @selected((string) ($filters['company_type_id'] ?? '') === (string) $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="form-control">
                    <span class="label-text mb-1">Company Size</span>
                    <select name="company_size_id" class="select select-bordered w-full">
                        <option value="">All sizes</option>
                        @foreach($companySizes as $size)
                            <option value="{{ $size->id }}" @selected((string) ($filters['company_size_id'] ?? '') === (string) $size->id)>{{ $size->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="form-control">
                    <span class="label-text mb-1">Ownership</span>
                    <select name="ownership_type_id" class="select select-bordered w-full">
                        <option value="">All ownership types</option>
                        @foreach($ownershipTypes as $type)
                            <option value="{{ $type->id }}" @selected((string) ($filters['ownership_type_id'] ?? '') === (string) $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="form-control">
                    <span class="label-text mb-1">Primary Industry</span>
                    <select name="primary_industry_id" class="select select-bordered w-full">
                        <option value="">All industries</option>
                        @foreach($industries as $industry)
                            <option value="{{ $industry->id }}" @selected((string) ($filters['primary_industry_id'] ?? '') === (string) $industry->id)>{{ $industry->name }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="form-control">
                    <span class="label-text mb-1">Record Status</span>
                    <select name="status" class="select select-bordered w-full">
                        <option value="">All statuses</option>
                        @foreach($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="form-control">
                    <span class="label-text mb-1">Operating Status</span>
                    <select name="operating_status" class="select select-bordered w-full">
                        <option value="">All operating statuses</option>
                        @foreach($operatingStatuses as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['operating_status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>

                <label class="form-control">
                    <span class="label-text mb-1">Verified</span>
                    <select name="verified" class="select select-bordered w-full">
                        <option value="">All companies</option>
                        <option value="1" @selected(($filters['verified'] ?? '') === '1')>Verified only</option>
                        <option value="0" @selected(($filters['verified'] ?? '') === '0')>Not verified</option>
                    </select>
                </label>

                <label class="form-control">
                    <span class="label-text mb-1">Featured</span>
                    <select name="featured" class="select select-bordered w-full">
                        <option value="">All companies</option>
                        <option value="1" @selected(($filters['featured'] ?? '') === '1')>Featured only</option>
                        <option value="0" @selected(($filters['featured'] ?? '') === '0')>Not featured</option>
                    </select>
                </label>
            </div>

            <div class="mt-4 flex flex-wrap justify-end gap-2">
                <a href="{{ route('admin.companies.index') }}" class="btn btn-ghost">Reset</a>
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass mr-2"></i> Apply Filters</button>
            </div>
        </form>
    </details>

    {{-- Table --}}
    <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100">
        <div class="flex flex-col gap-2 border-b border-base-300 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-5">
            <h2 class="font-semibold">Company Directory</h2>
            <span class="text-sm text-base-content/60">{{ $companies->total() }} result(s)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Classification</th>
                        <th>Operating Status</th>
                        <th>Profiles</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td class="min-w-64">
                                <div class="flex items-center gap-3">
                                    @if($company->logo)
                                        <img src="{{ Storage::disk('public')->url($company->logo) }}" alt="" class="h-11 w-11 shrink-0 rounded-lg border border-base-300 object-contain p-1">
                                    @else
                                        <div class="grid h-11 w-11 shrink-0 place-items-center rounded-lg bg-base-200 text-base-content/50">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.companies.show', $company) }}" class="font-semibold hover:text-primary">{{ $company->name }}</a>
                                        @if($company->legal_name && $company->legal_name !== $company->name)
                                            <p class="text-xs text-base-content/60">{{ $company->legal_name }}</p>
                                        @endif
                                        @if($company->website)
                                            <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="block max-w-64 truncate text-xs text-primary">{{ $company->website }}</a>
                                        @endif
                                        <div class="mt-1 flex flex-wrap gap-1">
                                            @if($company->is_verified)
                                                <span class="badge badge-info badge-sm">Verified</span>
                                            @endif
                                            @if($company->is_featured)
                                                <span class="badge badge-warning badge-sm">Featured</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="min-w-44">
                                <div class="font-medium">{{ $company->companyType?->name ?? '—' }}</div>
                                <div class="text-xs text-base-content/60">{{ $company->companySize?->name ?? 'Size not set' }}</div>
                                <div class="text-xs text-base-content/60">{{ $company->primaryIndustry?->name ?? 'Industry not set' }}</div>
                                @if($company->ownershipType)
                                    <div class="text-xs text-base-content/60">{{ $company->ownershipType->name }}</div>
                                @endif
                            </td>

                            <td>
                                @if($company->operating_status)
                                    {{ $operatingStatuses[$company->operating_status] ?? ucfirst($company->operating_status) }}
                                @else
                                    <span class="text-base-content/40">—</span>
                                @endif
                            </td>

                            <td class="min-w-36">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($company->platforms as $platform)
                                        <span class="badge badge-outline badge-sm">{{ $platform->name }}</span>
                                    @empty
                                        <span class="text-xs text-base-content/40">No profiles</span>
                                    @endforelse
                                </div>
                            </td>

                            <td>
                                <span class="badge {{ $statusClasses[$company->status] ?? 'badge-ghost' }}">
                                    {{ $statusLabels[$company->status] ?? ucfirst($company->status) }}
                                </span>
                            </td>

                            <td>
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-ghost btn-sm btn-square" title="View" aria-label="View company">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.companies.edit', $company) }}" class="btn btn-ghost btn-sm btn-square" title="Edit" aria-label="Edit company">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" onsubmit="return confirm('Move this company to trash?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-ghost btn-sm btn-square text-error" title="Move to trash" aria-label="Move company to trash">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="py-12 text-center">
                                    <i class="fa-solid fa-building mb-3 text-3xl text-base-content/30"></i>
                                    <p class="font-semibold">No companies found</p>
                                    <p class="mt-1 text-sm text-base-content/60">Try adjusting your filters or add a company.</p>
                                    <a href="{{ route('admin.companies.create') }}" class="btn btn-primary btn-sm mt-4">Add Company</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($companies->hasPages())
            <div class="border-t border-base-300 p-4">{{ $companies->links() }}</div>
        @endif
    </section>
</div>
@endsection
