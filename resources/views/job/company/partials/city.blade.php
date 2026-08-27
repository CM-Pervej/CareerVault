{{-- Cities --}}
@if($company->cities->isNotEmpty())
<div id="section-cities" class="card bg-base-100 shadow-sm border border-base-300 scroll-mt-20">
    <div class="card-body">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-city text-sm"></i>
                </div>
                <h2 class="text-lg font-semibold">Cities</h2>
            </div>
            <span class="badge badge-ghost badge-sm font-medium">{{ $company->cities->count() }}</span>
        </div>

        <div class="flex flex-wrap gap-3">
            @foreach($company->countries as $country)
                @php $countryCities = $citiesByCountry->get($country->id, collect()); @endphp
                @if($countryCities->isNotEmpty())
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        @if($country->iso_code)
                            <img src="https://flagcdn.com/w40/{{ strtolower($country->iso_code) }}.png" alt="{{ $country->name }} flag" loading="lazy" class="w-5 h-3.5 object-cover rounded-sm border border-base-300">
                        @endif
                        <span class="text-sm font-semibold text-base-content/80">{{ $country->name }}</span>
                        <span class="badge badge-ghost badge-xs">{{ $countryCities->count() }}</span>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($countryCities as $city)
                            <span class="badge badge-primary badge-outline">
                                <i class="fa-solid fa-location-dot text-[10px] mr-1"></i>{{ $city->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endif