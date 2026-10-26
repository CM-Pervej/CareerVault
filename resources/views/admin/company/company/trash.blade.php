
@extends('layouts.admin.app')

@section('title', 'Companies Trash | CareerVault')
@section('page_title', 'Companies / Trash')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-base-content/60">Company Directory / Trash</p>
            <h1 class="text-2xl font-bold text-error">Trashed Companies</h1>
            <p class="mt-1 text-sm text-base-content/60">Restore companies or permanently remove them.</p>
        </div>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-ghost">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Companies
        </a>
    </div>

    <div class="rounded-xl border border-base-300 bg-base-100 p-4 sm:p-5">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-sm text-base-content/60">Companies in trash</p>
                <p class="mt-1 text-2xl font-bold tabular-nums">{{ number_format($trashCount) }}</p>
            </div>
            <div class="grid h-12 w-12 place-items-center rounded-xl bg-error/10 text-error">
                <i class="fa-solid fa-trash-can text-xl"></i>
            </div>
        </div>
    </div>

    <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100">
        <form method="GET" action="{{ route('admin.companies.trash') }}" class="flex flex-col gap-3 border-b border-base-300 p-4 sm:flex-row sm:items-end sm:p-5">
            <label class="form-control flex-1">
                <span class="label-text mb-1">Search trashed companies</span>
                <input type="search" name="search" value="{{ $search }}" class="input input-bordered w-full" placeholder="Company name or legal name...">
            </label>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass mr-2"></i> Search</button>
                <a href="{{ route('admin.companies.trash') }}" class="btn btn-ghost">Reset</a>
            </div>
        </form>

        <div class="flex items-center justify-between gap-3 px-4 py-3 sm:px-5">
            <h2 class="font-semibold">Trash</h2>
            <span class="text-sm text-base-content/60">{{ $companies->total() }} result(s)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Company Type</th>
                        <th>Deleted By</th>
                        <th>Deleted At</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td class="min-w-56">
                                <div class="flex items-center gap-3">
                                    @if($company->logo)
                                        <img src="{{ Storage::disk('public')->url($company->logo) }}" alt="" class="h-10 w-10 shrink-0 rounded-lg border border-base-300 object-contain p-1">
                                    @else
                                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-lg bg-base-200 text-base-content/50"><i class="fa-solid fa-building"></i></div>
                                    @endif
                                    <div>
                                        <p class="font-semibold">{{ $company->name }}</p>
                                        @if($company->legal_name)
                                            <p class="text-xs text-base-content/60">{{ $company->legal_name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $company->companyType?->name ?? '—' }}</td>
                            <td>{{ $company->deleter?->name ?? '—' }}</td>
                            <td class="whitespace-nowrap">{{ $company->deleted_at?->format('M d, Y H:i') ?? '—' }}</td>
                            <td>
                                <div class="flex justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.companies.restore', $company->id) }}" onsubmit="return confirm('Restore this company?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline btn-success">
                                            <i class="fa-solid fa-rotate-left mr-1"></i> Restore
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.companies.force-delete', $company->id) }}" onsubmit="return confirm('Permanently delete this company? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-error">
                                            <i class="fa-solid fa-trash-can mr-1"></i> Delete Permanently
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="py-12 text-center">
                                    <i class="fa-solid fa-trash-can-arrow-up mb-3 text-3xl text-base-content/30"></i>
                                    <p class="font-semibold">Trash is empty</p>
                                    <p class="mt-1 text-sm text-base-content/60">Deleted companies will appear here.</p>
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
