@php
    $record = $company ?? null;

    $selectedIds = fn ($field, $relation) => old(
        $field,
        $record ? $record->{$relation}->pluck('id')->all() : []
    );

    $existingPlaces = $record
        ? $record->companyPlaces->map(fn ($place) => [
            'city_id' => $place->city_id,
            'place' => $place->place,
        ])->all()
        : [];
@endphp

<div class="space-y-3">
    {{-- Business models --}}
    <section class="overflow-visible rounded-xl border border-base-300 bg-base-100 shadow-sm"
        x-data="{ open: {{ old('business_model_ids') !== null ? 'true' : 'false' }} }">
        <button type="button"
            class="flex w-full items-center gap-3 rounded-xl px-4 py-3.5 text-left transition hover:bg-base-200/50"
            @click="open = !open" :aria-expanded="open.toString()">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                <i class="fa-solid fa-diagram-project"></i>
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-sm font-semibold">Business Models</span>
                <span class="mt-0.5 block text-xs text-base-content/55">Select the company's business models</span>
            </span>
            <span class="badge badge-ghost badge-sm">Relationship</span>
            <i class="fa-solid fa-chevron-down text-xs text-base-content/50 transition-transform"
                :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-cloak x-show="open" x-transition
            class="border-t border-base-300 p-4">
            <x-admin.company.relationship-select
                name="business_model_ids"
                label="Business models"
                :items="$businessModels"
                :selected="$selectedIds('business_model_ids', 'businessModels')"
                placeholder="Select business models"
                search-placeholder="Search business models..."
                icon="fa-solid fa-diagram-project"
            />
        </div>
    </section>

    {{-- Industries --}}
    <section class="overflow-visible rounded-xl border border-base-300 bg-base-100 shadow-sm"
        x-data="{ open: {{ old('industry_ids') !== null ? 'true' : 'false' }} }">
        <button type="button"
            class="flex w-full items-center gap-3 rounded-xl px-4 py-3.5 text-left transition hover:bg-base-200/50"
            @click="open = !open" :aria-expanded="open.toString()">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success">
                <i class="fa-solid fa-industry"></i>
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-sm font-semibold">Industries</span>
                <span class="mt-0.5 block text-xs text-base-content/55">Choose the industries associated with this company</span>
            </span>
            <span class="badge badge-ghost badge-sm">Relationship</span>
            <i class="fa-solid fa-chevron-down text-xs text-base-content/50 transition-transform"
                :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-cloak x-show="open" x-transition
            class="border-t border-base-300 p-4">
            <x-admin.company.relationship-select
                name="industry_ids"
                label="Industries"
                :items="$industries"
                :selected="$selectedIds('industry_ids', 'industries')"
                placeholder="Select industries"
                search-placeholder="Search industries..."
                icon="fa-solid fa-industry"
            />
        </div>
    </section>

    {{-- Technologies --}}
    <section class="overflow-visible rounded-xl border border-base-300 bg-base-100 shadow-sm"
        x-data="{ open: {{ old('technology_ids') !== null ? 'true' : 'false' }} }">
        <button type="button"
            class="flex w-full items-center gap-3 rounded-xl px-4 py-3.5 text-left transition hover:bg-base-200/50"
            @click="open = !open" :aria-expanded="open.toString()">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-info/10 text-info">
                <i class="fa-solid fa-microchip"></i>
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-sm font-semibold">Technologies</span>
                <span class="mt-0.5 block text-xs text-base-content/55">Select technologies used by the company</span>
            </span>
            <span class="badge badge-ghost badge-sm">Relationship</span>
            <i class="fa-solid fa-chevron-down text-xs text-base-content/50 transition-transform"
                :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-cloak x-show="open" x-transition
            class="border-t border-base-300 p-4">
            <x-admin.company.relationship-select
                name="technology_ids"
                label="Technologies"
                :items="$technologies"
                :selected="$selectedIds('technology_ids', 'technologies')"
                placeholder="Select technologies"
                search-placeholder="Search technologies..."
                icon="fa-solid fa-microchip"
            />
        </div>
    </section>

    {{-- Locations --}}
    <section class="overflow-visible rounded-xl border border-base-300 bg-base-100 shadow-sm"
        x-data="{ open: {{ old('country_ids') !== null || old('state_ids') !== null || old('company_places') !== null ? 'true' : 'false' }} }">
        <button type="button"
            class="flex w-full items-center gap-3 rounded-xl px-4 py-3.5 text-left transition hover:bg-base-200/50"
            @click="open = !open" :aria-expanded="open.toString()">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-warning/10 text-warning">
                <i class="fa-solid fa-map-location-dot"></i>
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-sm font-semibold">Locations</span>
                <span class="mt-0.5 block text-xs text-base-content/55">Choose countries, then states, then cities and branch locations</span>
            </span>
            <span class="badge badge-ghost badge-sm">Relationship</span>
            <i class="fa-solid fa-chevron-down text-xs text-base-content/50 transition-transform"
                :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-cloak x-show="open" x-transition
            class="border-t border-base-300 p-4">
            <x-admin.company.location-select
                :countries="$countries"
                :states="$states"
                :cities="$cities"
                :selected-countries="$selectedIds('country_ids', 'countries')"
                :selected-states="$selectedIds('state_ids', 'states')"
                :existing-places="old('company_places', $existingPlaces)"
            />
        </div>
    </section>

    {{-- Platforms --}}
    <section class="overflow-visible rounded-xl border border-base-300 bg-base-100 shadow-sm"
        x-data="{ open: {{ old('platform_ids') !== null ? 'true' : 'false' }} }">
        <button type="button"
            class="flex w-full items-center gap-3 rounded-xl px-4 py-3.5 text-left transition hover:bg-base-200/50"
            @click="open = !open" :aria-expanded="open.toString()">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-secondary/10 text-secondary">
                <i class="fa-solid fa-globe"></i>
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-sm font-semibold">Platform Profiles</span>
                <span class="mt-0.5 block text-xs text-base-content/55">Manage profile URLs and verification details</span>
            </span>
            <span class="badge badge-ghost badge-sm">Relationship</span>
            <i class="fa-solid fa-chevron-down text-xs text-base-content/50 transition-transform"
                :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-cloak x-show="open" x-transition
            class="border-t border-base-300 p-4">
            @php
                $platformProfiles = $record
                    ? $record->platforms->mapWithKeys(fn ($platform) => [
                        $platform->id => [
                            'profile_url' => $platform->pivot->profile_url,
                            'is_verified' => $platform->pivot->is_verified,
                            'last_verified_at' => $platform->pivot->last_verified_at,
                        ],
                    ])->all()
                    : [];
            @endphp

            <x-admin.company.platform-select
                :platforms="$platforms"
                :selected="$selectedIds('platform_ids', 'platforms')"
                :existing="$platformProfiles"
            />
        </div>
    </section>
</div>