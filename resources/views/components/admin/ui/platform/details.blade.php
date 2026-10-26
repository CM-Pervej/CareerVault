@props([
    'title' => null,
    'type' => null,
    'color' => null,
    'icon' => null,
    'parentName' => null,
    'active' => null,
    'access' => null,
    'bangla' => null,
])

<div class="min-w-0 flex-1">
    <div class="mb-2 flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-base-content/40 whitespace-nowrap">
        <i class="fa-solid fa-circle-info"></i> {{ $title }}
    </div>

    <div class="flex flex-wrap items-center gap-2">
        {{-- Type --}}
        @php
            $pageTypeIcon = match(Str::lower($type ?? '')) {
                'career' => 'fa-solid fa-briefcase',
                'jobs' => 'fa-solid fa-briefcase',
                'job' => 'fa-solid fa-briefcase',
                'company' => 'fa-solid fa-building',
                'profile' => 'fa-solid fa-user',
                'social' => 'fa-solid fa-share-nodes',
                'community' => 'fa-solid fa-users',
                'support' => 'fa-solid fa-life-ring',
                'help' => 'fa-solid fa-circle-question',
                'blog' => 'fa-solid fa-newspaper',
                'resource' => 'fa-solid fa-book-open',
                default => 'fa-solid fa-file-lines',
            };
        @endphp

        <div class="flex flex-wrap items-center gap-2 rounded-2xl px-1" style="border: 1px solid {{ $color }}">
            <div class="relative flex size-6 shrink-0 items-center justify-center overflow-hidden rounded-lg">
                @if($icon)
                    <i class="{{ $icon }}"
                    @if($color)
                        style="color: {{ $color }}"
                    @endif>
                    </i>
                @else
                    <i class="fa-solid fa-file-lines text-xl text-primary"></i>
                @endif
            </div>
            <span class="text-sm font-medium" style="color: {{ $color }}">{{ $parentName }}</span>
        </div>

        <span class="badge badge-sm badge-outline gap-1.5">
            <i class="{{ $pageTypeIcon }}"></i> {{ $type ?: 'General' }}
        </span>

        {{-- Status --}}
        @if($active)
            <span class="badge badge-sm badge-success gap-1.5">
                <i class="fa-solid fa-circle-check"></i> Active
            </span>
        @else
            <span class="badge badge-sm badge-warning gap-1.5">
                <i class="fa-solid fa-circle-exclamation"></i> Inactive
            </span>
        @endif

        <span class="badge badge-sm badge-outline gap-1.5">
            <i class="fa-solid fa-lock"></i> {{ $access }}
        </span>

        <span>
            @if($bangla)
                <span class="badge badge-success badge-sm gap-1">
                    <i class="fa-solid fa-flag"></i> Bangladesh
                </span>
            @else
                <span class="badge badge-sm badge-outline text-base-content/40">International</span>
            @endif
        </span>
    </div>
</div>