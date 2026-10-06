@extends('layouts.admin.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div
                class="w-14 h-14 rounded-2xl bg-base-200 flex items-center justify-center text-xl"
                @if($industry->color)
                    style="color: {{ $industry->color }}"
                @endif
            >
                <i class="{{ $industry->icon ?: 'fa-solid fa-layer-group' }}"></i>
            </div>

            <div>
                <div class="text-sm opacity-50">
                    {{ $industry->parent?->name ?? 'Top-level Industry' }}
                </div>

                <h1 class="text-2xl font-bold">
                    {{ $industry->name }}
                </h1>

                <div class="text-sm opacity-50">
                    {{ $industry->slug }}
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.industries.edit', $industry) }}" class="btn btn-primary">
                <i class="fa-solid fa-pen"></i>
                Edit
            </a>

            <a href="{{ route('admin.industries.index') }}" class="btn btn-ghost">
                <i class="fa-solid fa-arrow-left"></i>
                Back
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body p-5">
                <div class="text-sm opacity-50">Type</div>
                <div class="font-bold">
                    {{ $industry->parent ? 'Sub-industry' : 'Top-level Industry' }}
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body p-5">
                <div class="text-sm opacity-50">Sub-industries</div>
                <div class="text-2xl font-bold">
                    {{ $industry->children->count() }}
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300 shadow-sm">
            <div class="card-body p-5">
                <div class="text-sm opacity-50">Status</div>

                <div>
                    @if($industry->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-ghost">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <div class="lg:col-span-2 space-y-5">

            <div class="card bg-base-100 border border-base-300 shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-base">
                        <i class="fa-solid fa-circle-info text-primary"></i>
                        Industry Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">

                        <div>
                            <div class="text-xs opacity-50">Name</div>
                            <div class="font-semibold mt-1">{{ $industry->name }}</div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Parent Industry</div>
                            <div class="font-semibold mt-1">
                                {{ $industry->parent?->name ?? 'None' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Slug</div>
                            <div class="font-mono text-sm mt-1">{{ $industry->slug }}</div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Sort Order</div>
                            <div class="font-semibold mt-1">{{ $industry->sort_order }}</div>
                        </div>

                    </div>

                    @if($industry->description)
                        <div class="divider"></div>

                        <div>
                            <div class="text-xs opacity-50 mb-2">Description</div>
                            <div class="text-sm leading-6">
                                {{ $industry->description }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($industry->children->count())
                <div class="card bg-base-100 border border-base-300 shadow-sm">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <h2 class="card-title text-base">
                                <i class="fa-solid fa-sitemap text-primary"></i>
                                Sub-industries
                            </h2>

                            <span class="badge badge-outline">
                                {{ $industry->children->count() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-3">
                            @foreach($industry->children as $child)
                                <a
                                    href="{{ route('admin.industries.show', $child) }}"
                                    class="flex items-center gap-3 p-3 rounded-xl bg-base-200 hover:bg-base-300 transition"
                                >
                                    <i class="{{ $child->icon ?: 'fa-solid fa-layer-group' }}"
                                       @if($child->color) style="color: {{ $child->color }}" @endif></i>

                                    <div class="min-w-0">
                                        <div class="font-semibold truncate">
                                            {{ $child->name }}
                                        </div>

                                        <div class="text-xs opacity-50">
                                            {{ $child->is_active ? 'Active' : 'Inactive' }}
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="space-y-5">

            <div class="card bg-base-100 border border-base-300 shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-base">
                        <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                        Record
                    </h2>

                    <div class="space-y-4 mt-2">
                        <div>
                            <div class="text-xs opacity-50">Created</div>
                            <div class="text-sm font-medium mt-1">
                                {{ $industry->created_at?->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs opacity-50">Updated</div>
                            <div class="text-sm font-medium mt-1">
                                {{ $industry->updated_at?->format('d M Y, h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection