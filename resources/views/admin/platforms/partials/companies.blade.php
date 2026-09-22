<div class="rounded-3xl border border-base-300 bg-base-100 p-6 shadow-sm">
    <h2 class="text-xl font-black">Linked Companies</h2>
    <p class="text-sm text-base-content/50">Companies associated with this platform.</p>
</div>

@forelse($platform->companies as $company)
    <div class="card-soft rounded-2xl border border-base-300 bg-base-100 p-5 shadow-sm flex items-center gap-4">
        <div class="flex size-12 items-center justify-center rounded-xl bg-base-200 font-black">{{ strtoupper(substr($company->name,0,1)) }}</div>

        <div class="min-w-0 flex-1">
            <p class="font-black">{{ $company->name }}</p>
            <p class="truncate text-sm text-base-content/45">{{ $company->pivot->url?:'No URL recorded' }}</p>
        </div>

        @if($company->pivot->url)
            <a href="{{ $company->pivot->url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">Open</a>
        @endif
    </div>
@empty
    <div class="rounded-3xl border border-dashed border-base-300 bg-base-100 p-14 text-center">
        <i class="fa-solid fa-building text-3xl text-base-content/30"></i>
        <h3 class="mt-4 text-xl font-black">No linked companies</h3>
    </div>
@endforelse