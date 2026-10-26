
@extends('layouts.admin.app')

@section('title', $ownershipType->name . ' | CareerVault')
@section('page_title', 'Ownership Type Details')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <a href="{{ route('admin.ownership-types.index') }}" class="link link-hover text-sm">
                ← Ownership Types
            </a>
            <h1 class="mt-2 text-2xl font-bold">{{ $ownershipType->name }}</h1>
            <p class="mt-1 text-sm text-base-content/60">
                <code>{{ $ownershipType->slug }}</code>
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.ownership-types.edit', $ownershipType) }}"
                class="btn btn-primary btn-sm">Edit</a>
            <a href="{{ route('admin.ownership-types.index') }}" class="btn btn-ghost btn-sm">
                Back
            </a>
        </div>
    </div>

    <section class="rounded-xl border border-base-300 bg-base-100 p-4 shadow-sm sm:p-6">
        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-sm text-base-content/60">Status</dt>
                <dd class="mt-1">
                    <span class="badge {{ $ownershipType->is_active ? 'badge-success' : 'badge-ghost' }}">
                        {{ $ownershipType->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </dd>
            </div>

            <div>
                <dt class="text-sm text-base-content/60">Sort order</dt>
                <dd class="mt-1 font-medium">{{ $ownershipType->sort_order }}</dd>
            </div>

            <div class="sm:col-span-2">
                <dt class="text-sm text-base-content/60">Description</dt>
                <dd class="mt-1 whitespace-pre-line">
                    {{ $ownershipType->description ?: 'No description provided.' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm text-base-content/60">Created at</dt>
                <dd class="mt-1">
                    {{ $ownershipType->created_at?->format('M d, Y H:i') ?? '—' }}
                </dd>
                <dd class="text-xs text-base-content/60">
                    By {{ $ownershipType->creator?->name ?? 'Unknown' }}
                </dd>
            </div>

            <div>
                <dt class="text-sm text-base-content/60">Last updated</dt>
                <dd class="mt-1">
                    {{ $ownershipType->updated_at?->format('M d, Y H:i') ?? '—' }}
                </dd>
                <dd class="text-xs text-base-content/60">
                    By {{ $ownershipType->updater?->name ?? 'Unknown' }}
                </dd>
            </div>
        </dl>
    </section>
</div>
@endsection
