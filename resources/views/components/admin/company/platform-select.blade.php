@props([
    'platforms' => collect(),
    'selected' => [],
    'existing' => [],
])

@php
    $platformOptions = collect($platforms)->map(fn ($platform) => [
        'id' => (string) $platform->id,
        'name' => $platform->name,
        'icon' => $platform->icon ?: 'fa-solid fa-globe',
        'color' => $platform->color ?: '#64748b',
    ])->values()->all();

    $selected = collect($selected)->map(fn ($id) => (string) $id)->values()->all();

    $profiles = collect($existing)->mapWithKeys(function ($profile, $id) {
        return [(string) $id => [
            'profile_url' => data_get($profile, 'profile_url', ''),
            'is_verified' => (bool) data_get($profile, 'is_verified', false),
            'last_verified_at' => data_get($profile, 'last_verified_at')
                ? \Illuminate\Support\Carbon::parse(data_get($profile, 'last_verified_at'))->format('Y-m-d')
                : '',
        ]];
    })->all();

    $profiles = old('platform_urls', $profiles);
    $verified = old('platform_is_verified', collect($existing)->mapWithKeys(
        fn ($profile, $id) => [(string) $id => (bool) data_get($profile, 'is_verified', false)]
    )->all());
    $verifiedDates = old('platform_last_verified_at', collect($existing)->mapWithKeys(
        fn ($profile, $id) => [(string) $id => data_get($profile, 'last_verified_at')
            ? \Illuminate\Support\Carbon::parse(data_get($profile, 'last_verified_at'))->format('Y-m-d')
            : '']
    )->all());
@endphp

<div
    class="space-y-4"
    x-data="companyPlatformSelect({
        platforms: @js($platformOptions),
        selected: @js(old('platform_ids', $selected)),
        urls: @js($profiles),
        verified: @js($verified),
        verifiedDates: @js($verifiedDates)
    })"
    @click.outside="open = false"
>
    <div class="relative">
        <button type="button"
            class="flex min-h-11 w-full items-center justify-between gap-3 rounded-lg border border-base-300 bg-base-100 px-3 py-2 text-left transition hover:border-primary/60"
            @click="open = !open">
            <span class="flex min-w-0 items-center gap-2">
                <i class="fa-solid fa-globe text-base-content/50"></i>
                <span class="truncate text-sm" x-text="selected.length ? `${selected.length} platforms selected` : 'Select platforms'"></span>
            </span>
            <i class="fa-solid fa-chevron-down text-xs transition-transform"
                :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-cloak x-show="open" x-transition
            class="absolute z-40 mt-2 w-full overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-xl">
            <div class="border-b border-base-300 p-3">
                <input type="search" x-model="search"
                    class="input input-sm w-full" placeholder="Search platforms...">
                <div class="mt-2 flex justify-between text-xs">
                    <button type="button" class="link link-primary no-underline" @click="selectVisible()">Select visible</button>
                    <button type="button" class="link link-error no-underline" @click="clear()">Clear all</button>
                </div>
            </div>

            <div class="max-h-60 overflow-y-auto p-2">
                <template x-for="platform in filtered" :key="platform.id">
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2.5 transition hover:bg-base-200/70">
                        <input type="checkbox" class="checkbox checkbox-primary checkbox-sm"
                            :checked="selected.includes(platform.id)"
                            @change="toggle(platform.id)">
                        <span class="flex size-8 shrink-0 items-center justify-center rounded-lg"
                            :style="`background-color: ${platform.color}18; color: ${platform.color}`">
                            <i :class="platform.icon"></i>
                        </span>
                        <span class="min-w-0 flex-1 truncate text-sm font-medium" x-text="platform.name"></span>
                        <i x-show="selected.includes(platform.id)" class="fa-solid fa-check text-xs text-primary"></i>
                    </label>
                </template>

                <p x-show="filtered.length === 0" class="p-5 text-center text-sm text-base-content/50">No platforms found.</p>
            </div>
        </div>
    </div>

    <template x-for="id in selected" :key="'platform-id-' + id">
        <input type="hidden" name="platform_ids[]" :value="id">
    </template>

    <div x-show="selected.length" x-cloak class="space-y-3">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h4 class="text-sm font-semibold">Platform profiles</h4>
            <p class="text-xs text-base-content/50">Add the company profile and verification details for each platform.</p>
        </div>

        <template x-for="id in selected" :key="'profile-' + id">
            <section class="overflow-hidden rounded-xl border border-base-300 bg-base-100">
                <div class="flex items-center gap-3 border-b border-base-300 bg-base-200/30 px-3 py-3 sm:px-4">
                    <span class="flex size-9 shrink-0 items-center justify-center rounded-lg"
                        :style="`background-color: ${platformFor(id)?.color ?? '#64748b'}18; color: ${platformFor(id)?.color ?? '#64748b'}`">
                        <i :class="platformFor(id)?.icon ?? 'fa-solid fa-globe'"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold" x-text="platformFor(id)?.name ?? 'Platform'"></p>
                        <p class="text-xs text-base-content/50">Company profile details</p>
                    </div>
                    <button type="button" class="btn btn-ghost btn-sm btn-square text-base-content/50"
                        @click="toggle(id)" aria-label="Remove platform">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <div class="grid gap-4 p-3 sm:grid-cols-2 sm:p-4">
                    <div class="space-y-1.5 sm:col-span-2">
                        <label class="label py-0">
                            <span class="label-text text-xs font-semibold">Profile URL</span>
                        </label>
                        <input type="url" class="input input-bordered input-sm w-full"
                            :name="`platform_urls[${id}]`"
                            x-model="urls[id]"
                            placeholder="https://example.com/company/profile">
                    </div>

                    <div class="flex items-center justify-between gap-3 rounded-lg border border-base-300 px-3 py-2.5">
                        <div>
                            <p class="text-sm font-medium">Verified</p>
                            <p class="text-xs text-base-content/50">Mark this profile as verified</p>
                        </div>
                        <input type="checkbox" class="toggle toggle-primary toggle-sm"
                            :checked="verified[id] === true || verified[id] === 1 || verified[id] === '1' || verified[id] === 'on'"
                            @change="verified[id] = $event.target.checked">
                        <input type="hidden" :name="`platform_is_verified[${id}]`"
                            :value="verified[id] ? 1 : 0">
                    </div>

                    <div class="space-y-1.5">
                        <label class="label py-0">
                            <span class="label-text text-xs font-semibold">Last verified date</span>
                        </label>
                        <input type="date" class="input input-bordered input-sm w-full"
                            :name="`platform_last_verified_at[${id}]`"
                            x-model="verifiedDates[id]">
                    </div>
                </div>
            </section>
        </template>
    </div>
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('companyPlatformSelect', (config) => ({
                platforms: (config.platforms ?? []).map(item => ({
                    ...item,
                    id: String(item.id)
                })),
                selected: (config.selected ?? []).map(String),
                urls: Object.fromEntries(
                    Object.entries(config.urls ?? {}).map(([id, value]) => [
                        String(id),
                        typeof value === 'object' ? (value?.profile_url ?? '') : (value ?? '')
                    ])
                ),
                verified: Object.fromEntries(
                    Object.entries(config.verified ?? {}).map(([id, value]) => [String(id), value])
                ),
                verifiedDates: Object.fromEntries(
                    Object.entries(config.verifiedDates ?? {}).map(([id, value]) => [String(id), value ?? ''])
                ),
                open: false,
                search: '',

                init() {
                    this.selected.forEach(id => this.prepare(id));
                },

                get filtered() {
                    const query = this.search.trim().toLowerCase();
                    return this.platforms.filter(item => item.name.toLowerCase().includes(query));
                },

                platformFor(id) {
                    return this.platforms.find(item => item.id === String(id));
                },

                prepare(id) {
                    id = String(id);
                    if (this.urls[id] === undefined) this.urls[id] = '';
                    if (this.verified[id] === undefined) this.verified[id] = false;
                    if (this.verifiedDates[id] === undefined) this.verifiedDates[id] = '';
                },

                toggle(id) {
                    id = String(id);

                    if (this.selected.includes(id)) {
                        this.selected = this.selected.filter(value => value !== id);
                    } else {
                        this.selected.push(id);
                        this.prepare(id);
                    }
                },

                selectVisible() {
                    this.filtered.forEach(item => {
                        if (!this.selected.includes(item.id)) this.selected.push(item.id);
                        this.prepare(item.id);
                    });
                },

                clear() {
                    this.selected = [];
                    this.search = '';
                }
            }));
        });
    </script>
@endonce