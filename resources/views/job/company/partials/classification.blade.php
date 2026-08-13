@php
    $selectedCountries = old(
        'country_ids',
        $company?->countries->pluck('id')->toArray() ?? []
    );

    $selectedCities = old(
        'city_ids',
        $company?->cities->pluck('id')->toArray() ?? []
    );

    $selectedIndustries = old(
        'industry_ids',
        $company?->industries->pluck('id')->toArray() ?? []
    );
@endphp


<div
    class="card bg-base-100 shadow-xl border border-base-300/60 rounded-2xl"
    x-data="companyClassification({
        countries: @js(
            $countries->map(fn ($country) => [
                'id' => (string) $country->id,
                'name' => $country->name,
            ])->values()
        ),

        cities: @js(
            ($cities ?? collect())->map(fn ($city) => [
                'id' => (string) $city->id,
                'country_id' => (string) $city->country_id,
                'name' => $city->name,
            ])->values()
        ),

        industries: @js(
            $industries->map(fn ($industry) => [
                'id' => (string) $industry->id,
                'name' => $industry->name,
            ])->values()
        ),

        selectedCountries: @js(
            array_map('strval', $selectedCountries)
        ),

        selectedCities: @js(
            array_map('strval', $selectedCities)
        ),

        selectedIndustries: @js(
            array_map('strval', $selectedIndustries)
        ),

        citiesUrl: @js(route('companies.cities'))
    })"
>
    <div class="card-body p-6 lg:p-8">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="flex items-start justify-between gap-4 pb-6 border-b border-base-300/60">

            <div class="flex items-center gap-4">

                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/15 to-primary/5 flex items-center justify-center ring-1 ring-primary/10">

                    <i class="fa-solid fa-tags text-primary text-lg"></i>

                </div>

                <div>

                    <h2 class="card-title text-base-content text-lg">
                        Classification
                    </h2>

                    <p class="text-sm text-base-content/60 mt-0.5">
                        Select the countries, cities and industries associated with this company.
                    </p>

                </div>

            </div>


            <div class="badge badge-ghost badge-sm gap-1.5 hidden sm:flex">

                <i class="fa-solid fa-circle-info text-[10px]"></i>

                Step 2 of 6

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FIELDS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-6">


            {{-- ===================================================== --}}
            {{-- COUNTRIES --}}
            {{-- ===================================================== --}}

            <div
                class="form-control"
                @click.outside="countryOpen = false"
            >

                <label class="label pb-1.5">

                    <span class="label-text font-medium text-base-content/80 flex items-center gap-2">

                        <i class="fa-solid fa-earth-americas text-base-content/30 text-xs"></i>

                        Countries

                    </span>


                    <span class="label-text-alt">

                        <span
                            class="badge badge-primary badge-sm font-medium"
                            x-show="selectedCountries.length > 0"
                            x-text="selectedCountries.length + ' selected'"
                            x-cloak
                        ></span>

                    </span>

                </label>


                <div class="relative">

                    {{-- Selected countries --}}
                    <button
                        type="button"
                        @click="countryOpen = !countryOpen"
                        class="input input-bordered w-full h-auto min-h-12 flex flex-wrap items-center gap-1.5 py-2 cursor-pointer @error('country_ids') input-error @enderror"
                        :class="countryOpen && 'input-primary'"
                    >

                        <template x-if="selectedCountries.length === 0">

                            <span class="text-base-content/40">
                                Select countries…
                            </span>

                        </template>


                        <template
                            x-for="id in selectedCountries"
                            :key="id"
                        >

                            <span class="badge badge-neutral gap-1.5 pl-3 pr-1.5 py-3 font-normal">

                                <span x-text="countryLabel(id)"></span>

                                <button
                                    type="button"
                                    @click.stop="removeCountry(id)"
                                    class="btn btn-ghost btn-circle btn-xs -mr-1 hover:bg-base-content/20"
                                >

                                    <i class="fa-solid fa-xmark text-[10px]"></i>

                                </button>

                            </span>

                        </template>


                        <i
                            class="fa-solid fa-chevron-down text-base-content/30 text-xs ml-auto transition-transform"
                            :class="countryOpen && 'rotate-180'"
                        ></i>

                    </button>


                    {{-- Dropdown --}}
                    <div
                        x-show="countryOpen"
                        x-cloak
                        x-transition.origin.top
                        class="absolute z-30 mt-2 w-full bg-base-100 rounded-xl border border-base-300 shadow-xl overflow-hidden"
                    >

                        <div class="p-2 border-b border-base-300">

                            <label class="input input-sm input-bordered flex items-center gap-2 w-full">

                                <i class="fa-solid fa-magnifying-glass text-base-content/30 text-xs"></i>

                                <input
                                    type="text"
                                    x-model="countrySearch"
                                    placeholder="Search countries…"
                                    class="grow"
                                    @click.stop
                                >

                            </label>

                        </div>


                        <div class="flex items-center justify-between px-3 py-1.5 border-b border-base-300 bg-base-200/50">

                            <button
                                type="button"
                                @click.stop="selectAllCountries"
                                class="text-xs font-medium text-primary hover:underline"
                            >
                                Select all
                            </button>

                            <button
                                type="button"
                                @click.stop="clearCountries"
                                class="text-xs font-medium text-base-content/50 hover:underline"
                            >
                                Clear
                            </button>

                        </div>


                        <ul class="max-h-56 overflow-y-auto py-1">

                            <template
                                x-for="country in filteredCountries"
                                :key="country.id"
                            >

                                <li>

                                    <label
                                        class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-base-200 transition-colors"
                                        @click.prevent="toggleCountry(country.id)"
                                    >

                                        <input
                                            type="checkbox"
                                            class="checkbox checkbox-sm checkbox-primary"
                                            :checked="selectedCountries.includes(country.id)"
                                        >

                                        <span
                                            class="text-sm"
                                            x-text="country.name"
                                        ></span>

                                    </label>

                                </li>

                            </template>


                            <li
                                x-show="filteredCountries.length === 0"
                                x-cloak
                                class="px-3 py-6 text-center text-sm text-base-content/40"
                            >
                                No matches found.
                            </li>

                        </ul>

                    </div>

                </div>


                {{-- Hidden country inputs --}}
                <template
                    x-for="id in selectedCountries"
                    :key="'country-input-' + id"
                >

                    <input
                        type="hidden"
                        name="country_ids[]"
                        :value="id"
                    >

                </template>


                <label class="label pt-1.5">

                    <span class="label-text-alt text-base-content/50">
                        Type to search, click to toggle a selection.
                    </span>

                </label>


                @error('country_ids')

                    <label class="label pt-0">

                        <span class="label-text-alt text-error flex items-center gap-1">

                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>

                            {{ $message }}

                        </span>

                    </label>

                @enderror

            </div>



            {{-- ===================================================== --}}
            {{-- CITIES --}}
            {{-- ===================================================== --}}

            <div
                class="form-control"
                @click.outside="cityOpen = false"
            >

                <label class="label pb-1.5">

                    <span class="label-text font-medium text-base-content/80 flex items-center gap-2">

                        <i class="fa-solid fa-city text-base-content/30 text-xs"></i>

                        Cities

                    </span>


                    <span class="label-text-alt">

                        <span
                            class="badge badge-primary badge-sm font-medium"
                            x-show="selectedCities.length > 0"
                            x-text="selectedCities.length + ' selected'"
                            x-cloak
                        ></span>

                    </span>

                </label>


                <div class="relative">

                    {{-- Selected cities --}}
                    <button
                        type="button"
                        @click="cityOpen = !cityOpen"
                        :disabled="selectedCountries.length === 0 || loadingCities"
                        class="input input-bordered w-full h-auto min-h-12 flex flex-wrap items-center gap-1.5 py-2 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed @error('city_ids') input-error @enderror"
                        :class="cityOpen && 'input-primary'"
                    >

                        <template x-if="selectedCities.length === 0">

                            <span
                                class="text-base-content/40"
                                x-text="
                                    loadingCities
                                        ? 'Loading cities…'
                                        : selectedCountries.length === 0
                                            ? 'Select a country first…'
                                            : 'Select cities…'
                                "
                            ></span>

                        </template>


                        <template
                            x-for="id in selectedCities"
                            :key="id"
                        >

                            <span class="badge badge-neutral gap-1.5 pl-3 pr-1.5 py-3 font-normal">

                                <span x-text="cityLabel(id)"></span>

                                <button
                                    type="button"
                                    @click.stop="removeCity(id)"
                                    class="btn btn-ghost btn-circle btn-xs -mr-1 hover:bg-base-content/20"
                                >

                                    <i class="fa-solid fa-xmark text-[10px]"></i>

                                </button>

                            </span>

                        </template>


                        <span
                            x-show="loadingCities"
                            class="loading loading-spinner loading-xs ml-auto"
                        ></span>


                        <i
                            x-show="!loadingCities"
                            class="fa-solid fa-chevron-down text-base-content/30 text-xs ml-auto transition-transform"
                            :class="cityOpen && 'rotate-180'"
                        ></i>

                    </button>


                    {{-- Dropdown --}}
                    <div
                        x-show="cityOpen"
                        x-cloak
                        x-transition.origin.top
                        class="absolute z-30 mt-2 w-full bg-base-100 rounded-xl border border-base-300 shadow-xl overflow-hidden"
                    >

                        <div class="p-2 border-b border-base-300">

                            <label class="input input-sm input-bordered flex items-center gap-2 w-full">

                                <i class="fa-solid fa-magnifying-glass text-base-content/30 text-xs"></i>

                                <input
                                    type="text"
                                    x-model="citySearch"
                                    placeholder="Search cities…"
                                    class="grow"
                                    @click.stop
                                >

                            </label>

                        </div>


                        <div class="flex items-center justify-between px-3 py-1.5 border-b border-base-300 bg-base-200/50">

                            <button
                                type="button"
                                @click.stop="selectAllCities"
                                class="text-xs font-medium text-primary hover:underline"
                            >
                                Select all
                            </button>

                            <button
                                type="button"
                                @click.stop="clearCities"
                                class="text-xs font-medium text-base-content/50 hover:underline"
                            >
                                Clear
                            </button>

                        </div>


                        <ul class="max-h-56 overflow-y-auto py-1">

                            <template
                                x-for="city in filteredCities"
                                :key="city.id"
                            >

                                <li>

                                    <label
                                        class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-base-200 transition-colors"
                                        @click.prevent="toggleCity(city.id)"
                                    >

                                        <input
                                            type="checkbox"
                                            class="checkbox checkbox-sm checkbox-primary"
                                            :checked="selectedCities.includes(city.id)"
                                        >

                                        <span
                                            class="text-sm"
                                            x-text="city.name"
                                        ></span>

                                    </label>

                                </li>

                            </template>


                            <li
                                x-show="filteredCities.length === 0 && !loadingCities"
                                x-cloak
                                class="px-3 py-6 text-center text-sm text-base-content/40"
                            >

                                <template x-if="selectedCountries.length === 0">

                                    <span>
                                        Select a country first.
                                    </span>

                                </template>


                                <template x-if="selectedCountries.length > 0">

                                    <span>
                                        No cities found.
                                    </span>

                                </template>

                            </li>

                        </ul>

                    </div>

                </div>


                {{-- Hidden city inputs --}}
                <template
                    x-for="id in selectedCities"
                    :key="'city-input-' + id"
                >

                    <input
                        type="hidden"
                        name="city_ids[]"
                        :value="id"
                    >

                </template>


                <label class="label pt-1.5">

                    <span class="label-text-alt text-base-content/50">
                        Cities are filtered by selected countries.
                    </span>

                </label>


                @error('city_ids')

                    <label class="label pt-0">

                        <span class="label-text-alt text-error flex items-center gap-1">

                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>

                            {{ $message }}

                        </span>

                    </label>

                @enderror

            </div>



            {{-- ===================================================== --}}
            {{-- INDUSTRIES --}}
            {{-- ===================================================== --}}

            <div
                class="form-control"
                @click.outside="industryOpen = false"
            >

                <label class="label pb-1.5">

                    <span class="label-text font-medium text-base-content/80 flex items-center gap-2">

                        <i class="fa-solid fa-industry text-base-content/30 text-xs"></i>

                        Industries

                    </span>


                    <span class="label-text-alt">

                        <span
                            class="badge badge-primary badge-sm font-medium"
                            x-show="selectedIndustries.length > 0"
                            x-text="selectedIndustries.length + ' selected'"
                            x-cloak
                        ></span>

                    </span>

                </label>


                <div class="relative">

                    <button
                        type="button"
                        @click="industryOpen = !industryOpen"
                        class="input input-bordered w-full h-auto min-h-12 flex flex-wrap items-center gap-1.5 py-2 cursor-pointer @error('industry_ids') input-error @enderror"
                        :class="industryOpen && 'input-primary'"
                    >

                        <template x-if="selectedIndustries.length === 0">

                            <span class="text-base-content/40">
                                Select industries…
                            </span>

                        </template>


                        <template
                            x-for="id in selectedIndustries"
                            :key="id"
                        >

                            <span class="badge badge-neutral gap-1.5 pl-3 pr-1.5 py-3 font-normal">

                                <span x-text="industryLabel(id)"></span>

                                <button
                                    type="button"
                                    @click.stop="removeIndustry(id)"
                                    class="btn btn-ghost btn-circle btn-xs -mr-1 hover:bg-base-content/20"
                                >

                                    <i class="fa-solid fa-xmark text-[10px]"></i>

                                </button>

                            </span>

                        </template>


                        <i
                            class="fa-solid fa-chevron-down text-base-content/30 text-xs ml-auto transition-transform"
                            :class="industryOpen && 'rotate-180'"
                        ></i>

                    </button>


                    <div
                        x-show="industryOpen"
                        x-cloak
                        x-transition.origin.top
                        class="absolute z-30 mt-2 w-full bg-base-100 rounded-xl border border-base-300 shadow-xl overflow-hidden"
                    >

                        <div class="p-2 border-b border-base-300">

                            <label class="input input-sm input-bordered flex items-center gap-2 w-full">

                                <i class="fa-solid fa-magnifying-glass text-base-content/30 text-xs"></i>

                                <input
                                    type="text"
                                    x-model="industrySearch"
                                    placeholder="Search industries…"
                                    class="grow"
                                    @click.stop
                                >

                            </label>

                        </div>


                        <div class="flex items-center justify-between px-3 py-1.5 border-b border-base-300 bg-base-200/50">

                            <button
                                type="button"
                                @click.stop="selectAllIndustries"
                                class="text-xs font-medium text-primary hover:underline"
                            >
                                Select all
                            </button>

                            <button
                                type="button"
                                @click.stop="clearIndustries"
                                class="text-xs font-medium text-base-content/50 hover:underline"
                            >
                                Clear
                            </button>

                        </div>


                        <ul class="max-h-56 overflow-y-auto py-1">

                            <template
                                x-for="industry in filteredIndustries"
                                :key="industry.id"
                            >

                                <li>

                                    <label
                                        class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-base-200 transition-colors"
                                        @click.prevent="toggleIndustry(industry.id)"
                                    >

                                        <input
                                            type="checkbox"
                                            class="checkbox checkbox-sm checkbox-primary"
                                            :checked="selectedIndustries.includes(industry.id)"
                                        >

                                        <span
                                            class="text-sm"
                                            x-text="industry.name"
                                        ></span>

                                    </label>

                                </li>

                            </template>


                            <li
                                x-show="filteredIndustries.length === 0"
                                x-cloak
                                class="px-3 py-6 text-center text-sm text-base-content/40"
                            >
                                No matches found.
                            </li>

                        </ul>

                    </div>

                </div>


                <template
                    x-for="id in selectedIndustries"
                    :key="'industry-input-' + id"
                >

                    <input
                        type="hidden"
                        name="industry_ids[]"
                        :value="id"
                    >

                </template>


                <label class="label pt-1.5">

                    <span class="label-text-alt text-base-content/50">
                        Type to search, click to toggle a selection.
                    </span>

                </label>


                @error('industry_ids')

                    <label class="label pt-0">

                        <span class="label-text-alt text-error flex items-center gap-1">

                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i>

                            {{ $message }}

                        </span>

                    </label>

                @enderror

            </div>

        </div>

    </div>
</div>


@once
<script>

document.addEventListener('alpine:init', () => {

    Alpine.data('companyClassification', ({
        countries = [],
        cities = [],
        industries = [],
        selectedCountries = [],
        selectedCities = [],
        selectedIndustries = [],
        citiesUrl
    }) => ({

        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        countries: countries.map(country => ({
            ...country,
            id: String(country.id)
        })),

        cities: cities.map(city => ({
            ...city,
            id: String(city.id),
            country_id: String(city.country_id)
        })),

        industries: industries.map(industry => ({
            ...industry,
            id: String(industry.id)
        })),

        selectedCountries: selectedCountries.map(String),

        selectedCities: selectedCities.map(String),

        selectedIndustries: selectedIndustries.map(String),


        /*
        |--------------------------------------------------------------------------
        | UI
        |--------------------------------------------------------------------------
        */

        countryOpen: false,

        cityOpen: false,

        industryOpen: false,

        countrySearch: '',

        citySearch: '',

        industrySearch: '',


        /*
        |--------------------------------------------------------------------------
        | AJAX
        |--------------------------------------------------------------------------
        */

        loadingCities: false,

        abortController: null,

        citiesUrl: citiesUrl,


        /*
        |--------------------------------------------------------------------------
        | Countries
        |--------------------------------------------------------------------------
        */

        get filteredCountries() {

            const search = this.countrySearch
                .toLowerCase()
                .trim();

            return this.countries.filter(country =>
                country.name
                    .toLowerCase()
                    .includes(search)
            );

        },


        countryLabel(id) {

            const country = this.countries.find(
                country => country.id === String(id)
            );

            return country
                ? country.name
                : id;

        },


        toggleCountry(id) {

            id = String(id);

            if (this.selectedCountries.includes(id)) {

                this.selectedCountries =
                    this.selectedCountries.filter(
                        countryId => countryId !== id
                    );

            } else {

                this.selectedCountries = [
                    ...this.selectedCountries,
                    id
                ];

            }

            /*
             * Immediately reload cities.
             */
            this.loadCities();

        },


        removeCountry(id) {

            this.selectedCountries =
                this.selectedCountries.filter(
                    countryId => countryId !== String(id)
                );

            /*
             * Immediately reload cities.
             */
            this.loadCities();

        },


        selectAllCountries() {

            this.selectedCountries = [
                ...new Set([
                    ...this.selectedCountries,
                    ...this.filteredCountries.map(
                        country => country.id
                    )
                ])
            ];

            this.loadCities();

        },


        clearCountries() {

            this.selectedCountries = [];

            this.selectedCities = [];

            this.cities = [];

        },


        /*
        |--------------------------------------------------------------------------
        | Cities
        |--------------------------------------------------------------------------
        */

        get filteredCities() {

            const search = this.citySearch
                .toLowerCase()
                .trim();

            return this.cities.filter(city =>
                city.name
                    .toLowerCase()
                    .includes(search)
            );

        },


        cityLabel(id) {

            const city = this.cities.find(
                city => city.id === String(id)
            );

            return city
                ? city.name
                : id;

        },


        toggleCity(id) {

            id = String(id);

            if (this.selectedCities.includes(id)) {

                this.selectedCities =
                    this.selectedCities.filter(
                        cityId => cityId !== id
                    );

            } else {

                this.selectedCities = [
                    ...this.selectedCities,
                    id
                ];

            }

        },


        removeCity(id) {

            this.selectedCities =
                this.selectedCities.filter(
                    cityId => cityId !== String(id)
                );

        },


        selectAllCities() {

            this.selectedCities = [
                ...new Set([
                    ...this.selectedCities,
                    ...this.filteredCities.map(
                        city => city.id
                    )
                ])
            ];

        },


        clearCities() {

            this.selectedCities = [];

        },


        /*
        |--------------------------------------------------------------------------
        | AJAX city loading
        |--------------------------------------------------------------------------
        */

        async loadCities() {

            /*
             * No countries selected.
             */
            if (this.selectedCountries.length === 0) {

                this.cities = [];

                this.selectedCities = [];

                this.cityOpen = false;

                return;

            }


            /*
             * Cancel previous AJAX request.
             */
            if (this.abortController) {

                this.abortController.abort();

            }

            this.abortController =
                new AbortController();

            this.loadingCities = true;


            try {

                const params = new URLSearchParams();


                this.selectedCountries.forEach(
                    countryId => {

                        params.append(
                            'country_ids[]',
                            countryId
                        );

                    }
                );


                const response = await fetch(
                    `${this.citiesUrl}?${params.toString()}`,
                    {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'
                        },

                        signal:
                            this.abortController.signal
                    }
                );


                if (!response.ok) {

                    throw new Error(
                        'Failed to load cities.'
                    );

                }


                const data =
                    await response.json();


                /*
                 * Replace the actual reactive cities array.
                 */
                this.cities = data.map(city => ({

                    id: String(city.id),

                    country_id:
                        String(city.country_id),

                    name: city.name

                }));


                /*
                 * Remove cities that no longer belong
                 * to the selected countries.
                 */
                const availableCityIds =
                    this.cities.map(city => city.id);


                this.selectedCities =
                    this.selectedCities.filter(
                        cityId =>
                            availableCityIds.includes(cityId)
                    );


                /*
                 * Reset city search.
                 */
                this.citySearch = '';


            } catch (error) {

                /*
                 * Ignore aborted requests.
                 */
                if (error.name !== 'AbortError') {

                    console.error(
                        'Failed to load cities:',
                        error
                    );

                    this.cities = [];

                    this.selectedCities = [];

                }

            } finally {

                this.loadingCities = false;

            }

        },


        /*
        |--------------------------------------------------------------------------
        | Industries
        |--------------------------------------------------------------------------
        */

        get filteredIndustries() {

            const search = this.industrySearch
                .toLowerCase()
                .trim();

            return this.industries.filter(industry =>
                industry.name
                    .toLowerCase()
                    .includes(search)
            );

        },


        industryLabel(id) {

            const industry = this.industries.find(
                industry => industry.id === String(id)
            );

            return industry
                ? industry.name
                : id;

        },


        toggleIndustry(id) {

            id = String(id);

            if (this.selectedIndustries.includes(id)) {

                this.selectedIndustries =
                    this.selectedIndustries.filter(
                        industryId =>
                            industryId !== id
                    );

            } else {

                this.selectedIndustries = [
                    ...this.selectedIndustries,
                    id
                ];

            }

        },


        removeIndustry(id) {

            this.selectedIndustries =
                this.selectedIndustries.filter(
                    industryId =>
                        industryId !== String(id)
                );

        },


        selectAllIndustries() {

            this.selectedIndustries = [
                ...new Set([
                    ...this.selectedIndustries,
                    ...this.filteredIndustries.map(
                        industry => industry.id
                    )
                ])
            ];

        },


        clearIndustries() {

            this.selectedIndustries = [];

        }

    }));

});

</script>
@endonce