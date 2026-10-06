@extends('layouts.admin.app')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li><a href="{{ route('admin.cities.index') }}">Cities</a></li>
                    <li>{{ $city->name }}</li>
                </ul>
            </div>

            <div class="mt-1 flex flex-wrap items-center gap-2">
                <h1 class="text-2xl font-bold">{{ $city->name }}</h1>

                @if($city->is_capital)
                    <span class="badge badge-warning">
                        <i class="fa-solid fa-star mr-1 text-[10px]"></i>
                        Capital
                    </span>
                @endif

                @if($city->is_active)
                    <span class="badge badge-success">Active</span>
                @else
                    <span class="badge badge-ghost">Inactive</span>
                @endif
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.cities.edit', $city) }}"
               class="btn btn-primary btn-sm">
                <i class="fa-solid fa-pen"></i>
                Edit
            </a>

            <a href="{{ route('admin.cities.index') }}"
               class="btn btn-ghost btn-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-base">
                    <i class="fa-solid fa-location-dot text-primary"></i>
                    Location
                </h2>

                <div class="mt-3 space-y-3">
                    <div>
                        <div class="text-xs text-base-content/50">Country</div>
                        <div class="font-semibold">
                            {{ $city->country?->name ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-base-content/50">State / Division</div>
                        <div class="font-semibold">
                            {{ $city->state?->name ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-base-content/50">City</div>
                        <div class="font-semibold">
                            {{ $city->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title text-base">
                    <i class="fa-solid fa-circle-info text-primary"></i>
                    Details
                </h2>

                <div class="mt-3 space-y-3">
                    <div>
                        <div class="text-xs text-base-content/50">Slug</div>
                        <div class="font-mono text-sm">{{ $city->slug }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-base-content/50">Code</div>
                        <div class="font-semibold">{{ $city->code ?: '—' }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-base-content/50">Sort Order</div>
                        <div class="font-semibold">{{ $city->sort_order }}</div>
                    </div>

                    <div>
                        <div class="text-xs text-base-content/50">Created</div>
                        <div class="font-semibold">
                            {{ $city->created_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-base-content/50">Last Updated</div>
                        <div class="font-semibold">
                            {{ $city->updated_at?->format('d M Y, h:i A') ?? '—' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection