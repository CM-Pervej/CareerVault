@extends('layouts.admin.app')

@section('title', $businessModel->name . ' | CareerVault')
@section('page_title', 'Business Models / Details')

@section('content')
<div class="space-y-5 p-4 sm:p-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold">{{ $businessModel->name }}</h1>
            <p class="mt-1 text-sm text-base-content/60">Business model details and audit information.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.business-models.index') }}" class="btn btn-ghost btn-sm">Back</a>
            <a href="{{ route('admin.business-models.edit', $businessModel) }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
        </div>
    </div>

    <section class="rounded-xl border border-base-300 bg-base-100 p-5 sm:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm text-base-content/60">Business model</p>
                <h2 class="mt-1 text-xl font-bold">{{ $businessModel->name }}</h2>
                <code class="mt-2 inline-block text-sm">{{ $businessModel->slug }}</code>
            </div>

            <span class="badge {{ $businessModel->is_active ? 'badge-success' : 'badge-ghost' }}">
                {{ $businessModel->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        <div class="mt-6 border-t border-base-300 pt-5">
            <h3 class="font-semibold">Description</h3>
            <p class="mt-2 whitespace-pre-line text-sm text-base-content/80">{{ $businessModel->description ?: 'No description provided.' }}</p>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 border-t border-base-300 pt-5 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <p class="text-xs text-base-content/60">Sort order</p>
                <p class="mt-1 font-medium">{{ $businessModel->sort_order }}</p>
            </div>
            <div>
                <p class="text-xs text-base-content/60">Created by</p>
                <p class="mt-1 font-medium">{{ $businessModel->creator?->name ?? 'Unknown' }}</p>
            </div>
            <div>
                <p class="text-xs text-base-content/60">Last updated by</p>
                <p class="mt-1 font-medium">{{ $businessModel->updater?->name ?? 'Unknown' }}</p>
            </div>
            <div>
                <p class="text-xs text-base-content/60">Created at</p>
                <p class="mt-1 font-medium">{{ $businessModel->created_at?->format('d M Y, h:i A') }}</p>
            </div>
            <div>
                <p class="text-xs text-base-content/60">Last updated</p>
                <p class="mt-1 font-medium">{{ $businessModel->updated_at?->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </section>
</div>
@endsection