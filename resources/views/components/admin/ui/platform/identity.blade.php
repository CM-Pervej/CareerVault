@props([
    'logo' => null,
    'parentLogo'=> null,
    'icon' => null,
    'color' => null,
    'name' => null,
    'parentName' => null,
    'active' => null,
    'fileShow' => null,
    'shortDesc' => null,
])

<div class="flex min-w-0 items-center gap-4 xl:w-[360px]">
    {{-- Page Icon --}}
    <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-base-300 shadow-sm">
        @if($logo)
            <img src="{{ Storage::url($logo) }}" alt="{{ $name }}" class="size-full object-contain">
        @elseif($parentLogo)
            <img src="{{ Storage::url($parentLogo) }}" alt="{{ $parentName }}" class="size-full object-contain">
        @elseif($icon)
            <i class="{{ $icon }}"
                @if($color)
                    style="color: {{ $color }}"
                @endif>
            </i>
        @else
            <i class="fa-solid fa-file-lines text-xl text-primary"></i>
        @endif

        {{-- Status Indicator --}}
        <span class="absolute bottom-1 right-1 size-2.5 rounded-full border-2 border-base-100 {{ $active ? 'bg-success' : 'bg-warning' }}" title="{{ $active ? 'Active' : 'Inactive' }}"></span>
    </div>

    {{-- Page Information --}}
    <div class="min-w-0">
        <div class="flex items-center gap-2 w-62">
            <a href="{{ $fileShow }}" class="truncate font-black"> {{ $name }} </a>

            @if($active)
                <span class="tooltip" data-tip="Active page">
                    <i class="fa-solid fa-circle-check text-xs text-success"></i>
                </span>
            @else
                <span class="tooltip" data-tip="Inactive page">
                    <i class="fa-solid fa-circle-exclamation text-xs text-warning"></i>
                </span>
            @endif
        </div>

        @if($shortDesc)
            <div class="mt-1.5 line-clamp-1 text-xs text-base-content/50"> {{ $shortDesc }} </div>
        @elseif($parentName)
            <div class="mt-0.5 flex items-center gap-1.5 truncate text-xs text-base-content/45">
                <i class="{{ $icon }} text-[9px]"></i> {{ $parentName ?? 'Unassigned platform' }}
            </div>
        @endif
    </div>
</div>