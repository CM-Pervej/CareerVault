{{-- Countries --}}
@if($company->countries->isNotEmpty())
<div id="section-countries" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-globe text-sm"></i>
                </div>
                <h2 class="text-lg font-semibold">Countries</h2>
            </div>
            <span class="badge badge-ghost badge-sm font-medium">{{ $company->countries->count() }}</span>
        </div>

        <div class="flex flex-wrap gap-2">
            @foreach($company->countries as $country)
            <div class="tooltip" data-tip="{{ $country->name }}">
                <img src="https://flagcdn.com/w80/{{ strtolower($country->iso_code) }}.png" alt="{{ $country->name }} flag" loading="lazy" class="size-max object-cover rounded border border-base-300 hover:scale-110 transition-transform">
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif