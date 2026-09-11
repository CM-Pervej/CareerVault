@extends('layouts.admin.app')

@section('title','Platform Connections')

@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="breadcrumbs text-sm">
                <ul>
                    <li>Directory</li>
                    <li>Platform Connections</li>
                </ul>
            </div>

            <h1 class="mt-2 text-2xl font-black tracking-tight">
                Platform Connections
            </h1>

            <p class="mt-1 text-sm text-base-content/60">
                Manage official cross-platform presence for your platform directory.
            </p>
        </div>

        <a
            href="{{ route('admin.platform-connections.create') }}"
            class="btn btn-primary"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="size-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 4v16m8-8H4"
                />
            </svg>

            Add Connections
        </a>
    </div>

    {{-- Stats --}}
    @php
        $totalPlatforms = $platforms->count();
        $platformsWithConnections = $platforms->where('connected_platforms_count', '>', 0)->count();
        $totalConnections = $platforms->sum('connected_platforms_count');
    @endphp

    <div class="grid gap-4 sm:grid-cols-3">

        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body p-5">
                <div class="text-xs font-bold uppercase tracking-wider text-base-content/50">
                    Platforms
                </div>

                <div class="mt-1 text-3xl font-black">
                    {{ $totalPlatforms }}
                </div>

                <div class="mt-1 text-xs text-base-content/50">
                    In the directory
                </div>
            </div>
        </div>

        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body p-5">
                <div class="text-xs font-bold uppercase tracking-wider text-base-content/50">
                    Connected
                </div>

                <div class="mt-1 text-3xl font-black">
                    {{ $platformsWithConnections }}
                </div>

                <div class="mt-1 text-xs text-base-content/50">
                    Platforms with official presence
                </div>
            </div>
        </div>

        <div class="card border border-base-300 bg-base-100 shadow-sm">
            <div class="card-body p-5">
                <div class="text-xs font-bold uppercase tracking-wider text-base-content/50">
                    Total Connections
                </div>

                <div class="mt-1 text-3xl font-black">
                    {{ $totalConnections }}
                </div>

                <div class="mt-1 text-xs text-base-content/50">
                    Official cross-platform accounts
                </div>
            </div>
        </div>

    </div>

    {{-- Platform list --}}
    <div class="overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-sm">

        <div class="border-b border-base-300 px-5 py-4">
            <div class="font-bold">
                Platform Directory
            </div>

            <div class="text-sm text-base-content/50">
                Select a platform to manage all of its official connections.
            </div>
        </div>

        <div class="divide-y divide-base-300">

            @forelse($platforms as $platform)

                <div class="group p-4 transition hover:bg-base-200/40 sm:p-5">

                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center">

                        {{-- Platform --}}
                        <div class="flex min-w-0 items-center gap-4 xl:w-80">

                            <div
                                class="flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-base-300 bg-base-200"
                                @if($platform->color)
                                    style="background-color: {{ $platform->color }}12"
                                @endif
                            >
                                @if($platform->logo)
                                    <img
                                        src="{{ Storage::url($platform->logo) }}"
                                        alt="{{ $platform->name }}"
                                        class="size-full object-contain"
                                    >
                                @elseif($platform->icon)
                                    <i class="{{ $platform->icon }} text-xl"></i>
                                @else
                                    <span class="text-lg font-black">
                                        {{ Str::upper(Str::substr($platform->name,0,1)) }}
                                    </span>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="truncate font-black">
                                    {{ $platform->name }}
                                </div>

                                <div class="truncate text-xs text-base-content/50">
                                    {{ $platform->slug }}
                                </div>

                                @if(!$platform->is_active)
                                    <span class="badge badge-warning badge-sm mt-1">
                                        Inactive
                                    </span>
                                @endif
                            </div>

                        </div>

                        {{-- Connected platforms --}}
                        <div class="min-w-0 flex-1">

                            @if($platform->connectedPlatforms->isNotEmpty())

                                <div class="flex flex-wrap gap-2">

                                    @foreach($platform->connectedPlatforms as $connectedPlatform)

                                        <div class="badge badge-lg gap-2 border-base-300 bg-base-200 px-3">

                                            <div class="flex size-5 items-center justify-center overflow-hidden rounded-md">
                                                @if($connectedPlatform->logo)
                                                    <img
                                                        src="{{ Storage::url($connectedPlatform->logo) }}"
                                                        alt="{{ $connectedPlatform->name }}"
                                                        class="size-full object-contain"
                                                    >
                                                @else
                                                    <span class="text-[10px] font-black">
                                                        {{ Str::upper(Str::substr($connectedPlatform->name,0,1)) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <span>
                                                {{ $connectedPlatform->name }}
                                            </span>

                                        </div>

                                    @endforeach

                                </div>

                                <div class="mt-2 text-xs text-base-content/50">
                                    {{ $platform->connected_platforms_count }}
                                    {{ Str::plural('connection', $platform->connected_platforms_count) }}
                                </div>

                            @else

                                <div class="text-sm text-base-content/40">
                                    No connections configured yet.
                                </div>

                            @endif

                        </div>

                        {{-- Action --}}
                        <div class="flex shrink-0 gap-2 xl:justify-end">

                            <a
                                href="{{ route('admin.platform-connections.edit', $platform) }}"
                                class="btn btn-sm {{ $platform->connected_platforms_count ? 'btn-outline' : 'btn-primary' }}"
                            >
                                {{ $platform->connected_platforms_count ? 'Manage' : 'Add Connections' }}
                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="py-20 text-center">

                    <div class="text-lg font-black">
                        No platforms found
                    </div>

                    <p class="mt-1 text-sm text-base-content/50">
                        Add platforms first before creating connections.
                    </p>

                    <a
                        href="{{ route('admin.platforms.index') }}"
                        class="btn btn-outline mt-5"
                    >
                        Platform Directory
                    </a>

                </div>

            @endforelse

        </div>

    </div>

</div>
@endsection