@props([
    'countries' => collect(),
    'states' => collect(),
    'cities' => collect(),
    'selectedCountries' => [],
    'selectedStates' => [],
    'existingPlaces' => [],
])

@php
    $countryOptions = collect($countries)->map(fn ($item) => [
        'id' => (string) $item->id,
        'name' => $item->name,
    ])->values()->all();

    $stateOptions = collect($states)->map(fn ($item) => [
        'id' => (string) $item->id,
        'name' => $item->name,
        'country_id' => (string) $item->country_id,
    ])->values()->all();

    $cityOptions = collect($cities)->map(fn ($item) => [
        'id' => (string) $item->id,
        'name' => $item->name,
        'country_id' => (string) ($item->country_id ?? ''),
        'state_id' => (string) ($item->state_id ?? ''),
    ])->values()->all();

    $selectedCountries = collect($selectedCountries)->map(fn ($id) => (string) $id)->values()->all();
    $selectedStates = collect($selectedStates)->map(fn ($id) => (string) $id)->values()->all();

    $placeOptions = collect($existingPlaces)->mapWithKeys(function ($place) {
        $cityId = data_get($place, 'city_id');

        return $cityId ? [(string) $cityId => (string) data_get($place, 'place', '')] : [];
    })->all();

    $oldCountries = old('country_ids', $selectedCountries);
    $oldStates = old('state_ids', $selectedStates);
    $oldPlaces = old('company_places');
@endphp

<div
    class="space-y-5"
    x-data="companyLocationSelect({
        countries: @js($countryOptions),
        states: @js($stateOptions),
        cities: @js($cityOptions),
        selectedCountries: @js($oldCountries),
        selectedStates: @js($oldStates),
        existingPlaces: @js($placeOptions),
        oldPlaces: @js($oldPlaces ?? [])
    })"
>
    {{-- Countries --}}
    <div class="space-y-2">
        <label class="label py-0">
            <span class="label-text font-semibold">Countries</span>
        </label>

        <div class="relative" @click.outside="countryOpen = false">
            <button type="button"
                class="flex min-h-11 w-full items-center justify-between gap-3 rounded-lg border border-base-300 bg-base-100 px-3 py-2 text-left"
                @click="countryOpen = !countryOpen">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-earth-americas text-base-content/50"></i>
                    <span class="text-sm" x-text="selectedCountries.length ? `${selectedCountries.length} countries selected` : 'Select countries'"></span>
                </span>
                <i class="fa-solid fa-chevron-down text-xs transition-transform"
                    :class="{ 'rotate-180': countryOpen }"></i>
            </button>

            <div x-cloak x-show="countryOpen" x-transition
                class="absolute z-40 mt-2 w-full overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-xl">
                <div class="border-b border-base-300 p-3">
                    <input type="search" x-model="countrySearch"
                        class="input input-sm w-full" placeholder="Search countries...">
                    <div class="mt-2 flex justify-between text-xs">
                        <button type="button" class="link link-primary no-underline" @click="selectAllCountries()">Select visible</button>
                        <button type="button" class="link link-error no-underline" @click="clearCountries()">Clear</button>
                    </div>
                </div>

                <div class="max-h-52 overflow-y-auto p-2">
                    <template x-for="country in filteredCountries" :key="country.id">
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm hover:bg-base-200/70">
                            <input type="checkbox" class="checkbox checkbox-primary checkbox-sm"
                                :checked="selectedCountries.includes(country.id)"
                                @change="toggleCountry(country.id)">
                            <span x-text="country.name"></span>
                        </label>
                    </template>
                    <p x-show="filteredCountries.length === 0" class="p-4 text-center text-sm text-base-content/50">No countries found.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2" x-show="selectedCountries.length" x-cloak>
            <template x-for="id in selectedCountries" :key="id">
                <span class="inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs text-primary">
                    <span x-text="countryName(id)"></span>
                    <button type="button" @click="toggleCountry(id)" aria-label="Remove country">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </span>
            </template>
        </div>

        <template x-for="id in selectedCountries" :key="'country-input-' + id">
            <input type="hidden" name="country_ids[]" :value="id">
        </template>
    </div>

    {{-- States --}}
    <div class="space-y-2">
        <label class="label py-0">
            <span class="label-text font-semibold">States / Divisions</span>
        </label>

        <div class="relative" @click.outside="stateOpen = false">
            <button type="button"
                class="flex min-h-11 w-full items-center justify-between gap-3 rounded-lg border border-base-300 bg-base-100 px-3 py-2 text-left disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="selectedCountries.length === 0"
                @click="stateOpen = !stateOpen">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-map text-base-content/50"></i>
                    <span class="text-sm" x-text="selectedCountries.length === 0 ? 'Select countries first' : selectedStates.length ? `${selectedStates.length} states selected` : 'Select states'"></span>
                </span>
                <i class="fa-solid fa-chevron-down text-xs transition-transform"
                    :class="{ 'rotate-180': stateOpen }"></i>
            </button>

            <div x-cloak x-show="stateOpen" x-transition
                class="absolute z-30 mt-2 w-full overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-xl">
                <div class="border-b border-base-300 p-3">
                    <input type="search" x-model="stateSearch"
                        class="input input-sm w-full" placeholder="Search states...">
                    <div class="mt-2 flex justify-between text-xs">
                        <button type="button" class="link link-primary no-underline" @click="selectAllStates()">Select visible</button>
                        <button type="button" class="link link-error no-underline" @click="clearStates()">Clear</button>
                    </div>
                </div>

                <div class="max-h-52 overflow-y-auto p-2">
                    <template x-for="state in filteredStates" :key="state.id">
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm hover:bg-base-200/70">
                            <input type="checkbox" class="checkbox checkbox-primary checkbox-sm"
                                :checked="selectedStates.includes(state.id)"
                                @change="toggleState(state.id)">
                            <span x-text="state.name"></span>
                        </label>
                    </template>
                    <p x-show="filteredStates.length === 0" class="p-4 text-center text-sm text-base-content/50">No states found for the selected countries.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2" x-show="selectedStates.length" x-cloak>
            <template x-for="id in selectedStates" :key="id">
                <span class="inline-flex items-center gap-2 rounded-full bg-secondary/10 px-3 py-1 text-xs text-secondary">
                    <span x-text="stateName(id)"></span>
                    <button type="button" @click="toggleState(id)" aria-label="Remove state">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </span>
            </template>
        </div>

        <template x-for="id in selectedStates" :key="'state-input-' + id">
            <input type="hidden" name="state_ids[]" :value="id">
        </template>
    </div>

    {{-- Cities and places --}}
    <div class="space-y-2">
        <label class="label py-0">
            <span class="label-text font-semibold">Cities / Places</span>
        </label>

        <div class="relative" @click.outside="cityOpen = false">
            <button type="button"
                class="flex min-h-11 w-full items-center justify-between gap-3 rounded-lg border border-base-300 bg-base-100 px-3 py-2 text-left disabled:cursor-not-allowed disabled:opacity-50"
                :disabled="selectedCountries.length === 0 || selectedStates.length === 0"
                @click="cityOpen = !cityOpen">
                <span class="flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-base-content/50"></i>
                    <span class="text-sm" x-text="selectedStates.length === 0 ? 'Select states first' : selectedCities.length ? `${selectedCities.length} cities selected` : 'Select cities'"></span>
                </span>
                <i class="fa-solid fa-chevron-down text-xs transition-transform"
                    :class="{ 'rotate-180': cityOpen }"></i>
            </button>

            <div x-cloak x-show="cityOpen" x-transition
                class="absolute z-20 mt-2 w-full overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-xl">
                <div class="border-b border-base-300 p-3">
                    <input type="search" x-model="citySearch"
                        class="input input-sm w-full" placeholder="Search cities...">
                    <div class="mt-2 flex justify-between text-xs">
                        <button type="button" class="link link-primary no-underline" @click="selectAllCities()">Select visible</button>
                        <button type="button" class="link link-error no-underline" @click="clearCities()">Clear</button>
                    </div>
                </div>

                <div class="max-h-52 overflow-y-auto p-2">
                    <template x-for="city in filteredCities" :key="city.id">
                        <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm hover:bg-base-200/70">
                            <input type="checkbox" class="checkbox checkbox-primary checkbox-sm"
                                :checked="selectedCities.includes(city.id)"
                                @change="toggleCity(city.id)">
                            <span x-text="city.name"></span>
                        </label>
                    </template>
                    <p x-show="filteredCities.length === 0" class="p-4 text-center text-sm text-base-content/50">No cities found for the selected states.</p>
                </div>
            </div>
        </div>

        <div class="space-y-3" x-show="selectedCities.length" x-cloak>
            <template x-for="id in selectedCities" :key="id">
                <div class="grid gap-3 rounded-xl border border-base-300 bg-base-200/30 p-3 sm:grid-cols-[minmax(0,1fr)_minmax(0,1.4fr)] sm:items-center">
                    <div class="flex min-w-0 items-center gap-2 text-sm font-medium">
                        <i class="fa-solid fa-location-dot text-primary"></i>
                        <span class="truncate" x-text="cityName(id)"></span>
                    </div>

                    <div>
                        <input type="text" class="input input-sm w-full"
                            :name="`company_places[${id}][place]`"
                            x-model="places[id]"
                            placeholder="Office, branch, area (optional)">
                        <input type="hidden" :name="`company_places[${id}][city_id]`" :value="id">
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('companyLocationSelect', (config) => ({
                countries: config.countries ?? [],
                states: config.states ?? [],
                cities: config.cities ?? [],
                selectedCountries: (config.selectedCountries ?? []).map(String),
                selectedStates: (config.selectedStates ?? []).map(String),
                selectedCities: [],
                places: {},
                countryOpen: false,
                stateOpen: false,
                cityOpen: false,
                countrySearch: '',
                stateSearch: '',
                citySearch: '',

                init() {
                    const oldPlaces = config.oldPlaces ?? {};
                    const existingPlaces = config.existingPlaces ?? {};

                    Object.entries(existingPlaces).forEach(([id, place]) => {
                        this.places[String(id)] = place;
                    });

                    if (Array.isArray(oldPlaces)) {
                        oldPlaces.forEach(place => {
                            if (place?.city_id) {
                                this.places[String(place.city_id)] = place.place ?? '';
                            }
                        });
                    } else {
                        Object.entries(oldPlaces).forEach(([id, place]) => {
                            this.places[String(place?.city_id ?? id)] = place?.place ?? '';
                        });
                    }

                    this.selectedCities = Object.keys(this.places).filter(id =>
                        this.cities.some(city => city.id === id) &&
                        this.cityMatchesSelections(this.cities.find(city => city.id === id))
                    );

                    this.selectedStates = this.selectedStates.filter(id =>
                        this.states.some(state => state.id === id && this.selectedCountries.includes(state.country_id))
                    );
                },

                get filteredCountries() {
                    const query = this.countrySearch.trim().toLowerCase();
                    return this.countries.filter(item => item.name.toLowerCase().includes(query));
                },

                get filteredStates() {
                    const query = this.stateSearch.trim().toLowerCase();
                    return this.states.filter(item =>
                        this.selectedCountries.includes(item.country_id) &&
                        item.name.toLowerCase().includes(query)
                    );
                },

                get filteredCities() {
                    const query = this.citySearch.trim().toLowerCase();
                    return this.cities.filter(item =>
                        this.cityMatchesSelections(item) &&
                        item.name.toLowerCase().includes(query)
                    );
                },

                cityMatchesSelections(city) {
                    if (!city) return false;

                    const countryMatch = !city.country_id || this.selectedCountries.includes(city.country_id);
                    return countryMatch && this.selectedStates.includes(city.state_id);
                },

                countryName(id) {
                    return this.countries.find(item => item.id === String(id))?.name ?? id;
                },

                stateName(id) {
                    return this.states.find(item => item.id === String(id))?.name ?? id;
                },

                cityName(id) {
                    return this.cities.find(item => item.id === String(id))?.name ?? id;
                },

                toggleCountry(id) {
                    id = String(id);
                    this.selectedCountries = this.selectedCountries.includes(id)
                        ? this.selectedCountries.filter(value => value !== id)
                        : [...this.selectedCountries, id];

                    this.selectedStates = this.selectedStates.filter(stateId =>
                        this.selectedCountries.includes(
                            this.states.find(state => state.id === stateId)?.country_id
                        )
                    );

                    this.syncCities();
                },

                selectAllCountries() {
                    this.selectedCountries = [...new Set([
                        ...this.selectedCountries,
                        ...this.filteredCountries.map(item => item.id)
                    ])];
                },

                clearCountries() {
                    this.selectedCountries = [];
                    this.selectedStates = [];
                    this.selectedCities = [];
                    this.countrySearch = '';
                },

                toggleState(id) {
                    id = String(id);
                    this.selectedStates = this.selectedStates.includes(id)
                        ? this.selectedStates.filter(value => value !== id)
                        : [...this.selectedStates, id];

                    this.syncCities();
                },

                selectAllStates() {
                    this.selectedStates = [...new Set([
                        ...this.selectedStates,
                        ...this.filteredStates.map(item => item.id)
                    ])];

                    this.syncCities();
                },

                clearStates() {
                    this.selectedStates = [];
                    this.selectedCities = [];
                    this.stateSearch = '';
                },

                toggleCity(id) {
                    id = String(id);

                    if (this.selectedCities.includes(id)) {
                        this.selectedCities = this.selectedCities.filter(value => value !== id);
                    } else {
                        this.selectedCities.push(id);
                    }
                },

                selectAllCities() {
                    this.selectedCities = [...new Set([
                        ...this.selectedCities,
                        ...this.filteredCities.map(item => item.id)
                    ])];

                    this.selectedCities.forEach(id => {
                        if (this.places[id] === undefined) this.places[id] = '';
                    });
                },

                clearCities() {
                    this.selectedCities = [];
                    this.citySearch = '';
                },

                syncCities() {
                    this.selectedCities = this.selectedCities.filter(id => {
                        const city = this.cities.find(item => item.id === id);
                        return this.cityMatchesSelections(city);
                    });
                }
            }));
        });
    </script>
@endonce