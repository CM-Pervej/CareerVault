<div class="rounded-3xl border border-base-300 bg-base-100 p-6 shadow-sm flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-xl font-black">Official Presence</h2>
        <p class="text-sm text-base-content/50">Official accounts across other platforms.</p>
    </div>
    <a href="{{ route('admin.platform-connections.edit',$platform) }}" class="btn btn-primary">Manage Presence</a>
</div>

@forelse($platform->connectedPlatforms as $connected)
    <div class="card-soft rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm flex items-center gap-4">
        <div class="flex size-14 items-center justify-center rounded-2xl bg-base-200 overflow-hidden">
            @if($connected->logo)
                <img src="{{ Storage::url($connected->logo) }}" alt="{{ $connected->name }}" class="size-full object-contain p-2">
            @elseif($connected->icon)
                <i class="{{ $connected->icon }} text-5xl" style="{{ $connected->color ? 'color:'.$connected->color : '' }}"></i>
            @else
                <span class="text-xl font-black" style="color: {{ $connected->color ?: '#4f46e5' }}">{{ strtoupper(substr($connected->name,0,1)) }}</span>
            @endif
        </div>

        <div class="min-w-0 flex-1">
            <a href="{{ route('admin.platforms.show',$connected) }}" class="font-black hover:text-primary">{{ $connected->name }}</a>
            <p class="truncate text-sm text-base-content/45">{{ $connected->pivot->account_url?:'No profile URL' }}</p>
        </div>

        @if($connected->pivot->account_url)
            <a href="{{ $connected->pivot->account_url }}" target="_blank" rel="noopener" class="btn btn-circle btn-ghost">
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
            </a>
        @endif
    </div>
@empty
    <div class="rounded-3xl border border-dashed border-base-300 bg-base-100 p-14 text-center">
        <i class="fa-solid fa-share-nodes text-3xl text-base-content/30"></i>
        <h3 class="mt-4 text-xl font-black">No official presence recorded</h3>
    </div>
@endforelse