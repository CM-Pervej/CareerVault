@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl bg-base-200 flex items-center justify-center text-xl">
                <i class="fa-solid fa-map-location-dot text-primary"></i>
            </div>

            <div>
                <div class="text-sm opacity-50">
                    {{ $country->name }}
                </div>

                <h1 class="text-2xl font-bold">
                    {{ $state->name }}
                </h1>

                <div class="text-sm opacity-50">
                    {{ $state->type ?: 'Administrative subdivision' }}
                </div>
            </div>

        </div>

        <div class="flex gap-2">

            <a
                href="{{ route('admin.countries.states.edit', [$country, $state]) }}"
                class="btn btn-primary"
            >
                <i class="fa-solid fa-pen"></i>
                Edit
            </a>

            <a
                href="{{ route('admin.countries.states.index', $country) }}"
                class="btn btn-ghost"
            >
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>

        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body p-5">
                <div class="text-sm opacity-50">Country</div>
                <div class="font-bold">{{ $country->name }}</div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body p-5">
                <div class="text-sm opacity-50">Type</div>
                <div class="font-bold">{{ $state->type ?: '—' }}</div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body p-5">

                <div class="text-sm opacity-50">Cities</div>

                <div class="text-2xl font-bold">
                    {{ $state->cities_count }}
                </div>

            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2">

            <div class="card bg-base-100 border border-base-300 shadow-sm">

                <div class="card-body">

                    <h2 class="card-title text-base">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                        State / Division Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">

                        <div>
                            <div class="text-xs opacity-50">Name</div>
                            <div class="font-semibold mt-1">
                                {{ $state->name }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Country</div>
                            <div class="font-semibold mt-1">
                                {{ $country->name }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Type</div>
                            <div class="font-semibold mt-1">
                                {{ $state->type ?: '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Code</div>
                            <div class="font-mono text-sm mt-1">
                                {{ $state->code ?: '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Slug</div>
                            <div class="font-mono text-sm mt-1">
                                {{ $state->slug }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Sort Order</div>
                            <div class="font-semibold mt-1">
                                {{ $state->sort_order }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div>

            <div class="card bg-base-100 border border-base-300 shadow-sm">

                <div class="card-body">

                    <h2 class="card-title text-base">
                        <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                        Record
                    </h2>

                    <div class="space-y-4 mt-3">

                        <div>
                            <div class="text-xs opacity-50">Status</div>

                            <div class="mt-1">
                                @if($state->is_active)
                                    <span class="badge badge-success">
                                        Active
                                    </span>
                                @else
                                    <span class="badge badge-ghost">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Created</div>
                            <div class="text-sm font-medium mt-1">
                                {{ $state->created_at?->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Updated</div>
                            <div class="text-sm font-medium mt-1">
                                {{ $state->updated_at?->format('d M Y, h:i A') }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection