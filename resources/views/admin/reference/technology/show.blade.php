@extends('layouts.admin.app')

@section('title', $technology->name . ' | CareerVault')
@section('page_title', 'Technologies / View')

@section('content')
<div class="space-y-4 p-4 sm:p-6">

    <x-admin.ui.page-header
        :title="$technology->name"
        description="Technology details and information."
        icon="fa-solid fa-code"
    >
        <x-slot:actions>
            <a href="{{ route('admin.technology.index') }}" class="btn btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>

            <a href="{{ route('admin.technology.edit', $technology) }}" class="btn btn-primary">
                <i class="fa-solid fa-pen"></i>
                Edit
            </a>
        </x-slot:actions>
    </x-admin.ui.page-header>

    <div class="grid gap-4 lg:grid-cols-3">

        {{-- Main --}}
        <section class="cv-section lg:col-span-2">
            <div class="flex items-start gap-4">
                <div
                    class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl text-2xl text-white"
                    style="background-color: {{ $technology->color ?: '#6366F1' }}"
                >
                    <i class="{{ $technology->icon ?: 'fa-solid fa-code' }}"></i>
                </div>

                <div class="min-w-0">
                    <h2 class="text-xl font-bold">{{ $technology->name }}</h2>

                    <p class="mt-1 text-sm text-base-content/50">
                        {{ $technology->slug }}
                    </p>

                    <div class="mt-3 flex flex-wrap gap-2">
                        @if($technology->category)
                            <span class="badge badge-primary badge-soft">
                                {{ $technology->category }}
                            </span>
                        @endif

                        @if($technology->is_active)
                            <span class="badge badge-success badge-soft">Active</span>
                        @else
                            <span class="badge badge-warning badge-soft">Inactive</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($technology->description)
                <div class="mt-6 border-t border-base-300 pt-5">
                    <h3 class="text-sm font-semibold uppercase tracking-wide text-base-content/60">
                        Description
                    </h3>

                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-base-content/80">
                        {{ $technology->description }}
                    </p>
                </div>
            @endif
        </section>

        {{-- Details --}}
        <section class="cv-section">
            <h2 class="text-base font-semibold">Details</h2>

            <dl class="mt-4 space-y-4">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">
                        Category
                    </dt>
                    <dd class="mt-1 text-sm">
                        {{ $technology->category ?: '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">
                        Sort Order
                    </dt>
                    <dd class="mt-1 text-sm">
                        {{ $technology->sort_order }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">
                        Icon
                    </dt>
                    <dd class="mt-1 break-all text-sm">
                        {{ $technology->icon ?: '—' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-base-content/50">
                        Color
                    </dt>
                    <dd class="mt-1 flex items-center gap-2 text-sm">
                        @if($technology->color)
                            <span
                                class="h-5 w-5 rounded border border-base-300"
                                style="background-color: {{ $technology->color }}"
                            ></span>
                            {{ $technology->color }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </dl>
        </section>
    </div>

    {{-- Audit --}}
    <section class="cv-section">
        <h2 class="text-base font-semibold">Audit Information</h2>

        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">Created By</p>
                <p class="mt-1 text-sm">{{ $technology->creator?->name ?? 'System' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">Created At</p>
                <p class="mt-1 text-sm">{{ $technology->created_at?->format('M d, Y H:i') ?? '—' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">Updated By</p>
                <p class="mt-1 text-sm">{{ $technology->updater?->name ?? '—' }}</p>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-base-content/50">Updated At</p>
                <p class="mt-1 text-sm">{{ $technology->updated_at?->format('M d, Y H:i') ?? '—' }}</p>
            </div>
        </div>
    </section>

</div>
@endsection