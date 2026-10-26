@extends('layouts.admin.app')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

<x-admin.reference.page-header
    title="{{ $state->name }}"
    description="State / Division details."
    icon="fa-solid fa-map"
    :back-route="route('admin.states.index')"
/>

        <div class="flex gap-2">
            <a
                href="{{ route('admin.states.edit', $state) }}"
                class="btn btn-primary"
            >
                <i class="fa-solid fa-pen"></i>
                Edit
            </a>

            <a
                href="{{ route('admin.states.index') }}"
                class="btn btn-ghost"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">

                <h2 class="font-bold text-lg mb-4">
                    <i class="fa-solid fa-circle-info mr-2"></i>
                    Details
                </h2>

                <div class="space-y-4">

                    <div>
                        <div class="text-sm text-base-content/50">Country</div>

                        <a
                            href="{{ route('admin.countries.show', $state->country) }}"
                            class="font-semibold link link-hover"
                        >
                            {{ $state->country->name }}
                        </a>
                    </div>

                    <div>
                        <div class="text-sm text-base-content/50">Type</div>
                        <div class="font-semibold">
                            {{ $state->type ?: 'Not specified' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-sm text-base-content/50">Code</div>
                        <div class="font-semibold">
                            {{ $state->code ?: 'Not specified' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-sm text-base-content/50">Slug</div>
                        <div class="font-semibold">
                            {{ $state->slug }}
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body">

                <h2 class="font-bold text-lg mb-4">
                    <i class="fa-solid fa-city mr-2"></i>
                    Cities
                </h2>

                <div class="text-4xl font-bold">
                    {{ $state->cities_count }}
                </div>

                <p class="text-base-content/60">
                    cities associated with this State / Division
                </p>

                @if($state->cities_count > 0)
                    <a
                        href="{{ route('admin.cities.index', ['state' => $state->slug]) }}"
                        class="btn btn-outline btn-sm mt-4"
                    >
                        View Cities
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                @endif

            </div>
        </div>

    </div>

    <div class="card bg-base-100 border border-base-300 shadow-sm">
        <div class="card-body">

            <h2 class="font-bold text-lg mb-4">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i>
                Record Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-sm">

                <div>
                    <div class="text-base-content/50">Created</div>
                    <div class="font-medium">
                        {{ $state->created_at?->format('d M Y, h:i A') ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-base-content/50">Last Updated</div>
                    <div class="font-medium">
                        {{ $state->updated_at?->format('d M Y, h:i A') ?? '—' }}
                    </div>
                </div>

                <div>
                    <div class="text-base-content/50">Sort Order</div>
                    <div class="font-medium">
                        {{ $state->sort_order }}
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>

@endsection