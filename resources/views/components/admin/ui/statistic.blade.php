@props([
    'title' => null,
    'titleIcon' => null,
    'mainIcon' => null,
    'mainIconColor' => null,
    'sp' => null,
    'total' => null,
    'totalDescription' => null,
    'lastIcon' => null,
])

<div class="sm:card border border-base-300 bg-base-100 shadow-sm">
    <div class="card-body p-5">
        <div class="flex items-start justify-between gap-0.5">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                    <i class="{{ $titleIcon }}"></i> {{ $title}}
                </div>
                @if ($total)
                    <div class="mt-2 text-3xl font-black">
                        {{ number_format($total) }}
                    </div>
                @else
                    <div class="mt-2 flex items-center gap-0.5">
                        <p class="text-xl font-black whitespace-nowrap">{{ $sp }}</p>

                        <span class="badge badge-warning badge-outline font-bold">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                        </span>
                    </div>
                @endif  
            </div>
            <div class="flex size-10 items-center justify-center rounded-lg {{ $mainIconColor }}">
                <i class="{{ $mainIcon }}"></i>
            </div>
        </div>
        <div class="flex items-center gap-1.5 text-xs text-base-content/50">
            <i class="{{ $lastIcon }}"></i> {{ $totalDescription }}
        </div>
    </div>
</div>