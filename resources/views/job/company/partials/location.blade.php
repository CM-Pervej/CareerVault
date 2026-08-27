{{-- Office Locations --}}
<div class="card-body">
    <div class="flex items-center justify-between mb-4 gap-2">
        <div class="flex items-center gap-2 min-w-0">
            <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <i class="fa-solid fa-location-dot text-sm"></i>
            </div>
            <h2 class="text-lg font-semibold truncate">Office Locations</h2>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
            @if(!empty($company->address))
                <span class="badge badge-ghost badge-sm font-medium">{{ count($company->address) }}</span>
                <button type="button" class="btn btn-ghost btn-xs no-print" data-action="copy-all-addresses" title="Copy all addresses">
                    <i class="fa-solid fa-copy text-xs"></i> <span class="hidden sm:inline">Copy all</span>
                </button>
            @endif
        </div>
    </div>

    @if(!empty($company->address))
        <div class="grid sm:grid-cols-2 gap-3">
            @foreach($company->address as $address)
            <div class="rounded-lg border border-base-300 bg-base-200/40 p-4 hover:border-primary/40 transition-colors">
                <div class="flex items-start justify-between gap-2 mb-2">
                    <span class="badge {{ $colorFor($address['address_type']) }} badge-outline badge-sm capitalize">{{ $address['address_type'] }}</span>
                    <div class="flex items-center gap-1 no-print">
                        <button type="button" class="copy-btn btn btn-ghost btn-xs btn-circle" data-copy="{{ $address['address'] }}" title="Copy address" aria-label="Copy address">
                            <i class="fa-solid fa-copy text-xs"></i>
                        </button>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address['address']) }}" target="_blank" rel="noopener" class="btn btn-ghost btn-xs btn-circle" title="View on map" aria-label="View address on map">
                            <i class="fa-solid fa-map-location-dot text-xs"></i>
                        </a>
                    </div>
                </div>
                <div class="text-sm text-base-content/80 leading-relaxed whitespace-pre-line">{{ $address['address'] }}</div>
            </div>
            @endforeach
        </div>
    @else
        <div class="flex flex-col items-center justify-center text-center py-8 text-base-content/40">
            <i class="fa-solid fa-map-location-dot text-2xl mb-2"></i>
            <p class="text-sm">No office locations on file</p>
        </div>
    @endif
</div>