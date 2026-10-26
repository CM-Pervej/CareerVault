
@extends('layouts.admin.app')

@section('title', $companyType->name . ' | CareerVault')
@section('page_title', 'Company Types / Details')

@section('content')
    <div class="space-y-5 p-4 sm:p-6">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold">{{ $companyType->name }}</h1>
                <p class="mt-1 text-sm text-base-content/60">Company type details and audit information.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.company-types.index') }}" class="btn btn-ghost btn-sm">Back</a>
                <a href="{{ route('admin.company-types.edit', $companyType) }}" class="btn btn-primary btn-sm">Edit</a>
            </div>
        </div>

        <section class="rounded-xl border border-base-300 bg-base-100 shadow-sm">
            <div class="flex flex-col gap-4 border-b border-base-300 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-base-content/60">Company Type</p>
                    <h2 class="mt-1 text-2xl font-bold">{{ $companyType->name }}</h2>
                    <code class="mt-2 inline-block text-sm">{{ $companyType->slug }}</code>
                </div>

                <span class="badge {{ $companyType->is_active ? 'badge-success' : 'badge-ghost' }} badge-lg">
                    {{ $companyType->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <dl class="grid gap-5 p-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm text-base-content/60">Description</dt>
                    <dd class="mt-1 whitespace-pre-line">{{ $companyType->description ?: 'No description provided.' }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Sort Order</dt>
                    <dd class="mt-1 font-medium">{{ $companyType->sort_order }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Created By</dt>
                    <dd class="mt-1">{{ $companyType->createdBy?->name ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Updated By</dt>
                    <dd class="mt-1">{{ $companyType->updatedBy?->name ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Created At</dt>
                    <dd class="mt-1">{{ $companyType->created_at?->format('M d, Y h:i A') ?? '—' }}</dd>
                </div>

                <div>
                    <dt class="text-sm text-base-content/60">Last Updated</dt>
                    <dd class="mt-1">{{ $companyType->updated_at?->format('M d, Y h:i A') ?? '—' }}</dd>
                </div>
            </dl>
        </section>

        <div class="flex justify-end">
            <form method="POST"
                action="{{ route('admin.company-types.destroy', $companyType) }}"
                onsubmit="return confirm('Move this company type to trash?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-error btn-outline btn-sm">Move to Trash</button>
            </form>
        </div>
    </div>
@endsection
