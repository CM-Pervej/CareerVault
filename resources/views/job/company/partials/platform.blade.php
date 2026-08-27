@php
    $selectedPlatformIds = old(
        'platform_ids',
        $company?->platforms->pluck('id')->toArray() ?? []
    );

    $existingPlatformUrls = [];

    if ($company?->platforms) {
        foreach ($company->platforms as $platform) {
            $existingPlatformUrls[$platform->id] = $platform->pivot->url;
        }
    }

    $oldPlatformUrls = old('platform_urls', []);
    $platformUrls = array_replace($existingPlatformUrls, $oldPlatformUrls);
@endphp

<div class="card bg-base-100 shadow-xl border border-base-300/60 rounded-none sm:rounded-2xl"
    x-data="companyPlatforms({
        platforms: @js(
            $platforms->map(fn ($platform) => [
                'id' => (string) $platform->id,
                'name' => $platform->name,
                'icon' => $platform->icon,
                'color' => $platform->color,
            ])->values()
        ),

        selectedPlatforms: @js(
            array_map('strval', $selectedPlatformIds)
        ),

        platformUrls: @js(
            collect($platformUrls)->mapWithKeys(
                fn ($url, $id) => [(string) $id => $url]
            )->all()
        )
    })">
    <div class="card-body p-6 lg:p-8">
        {{-- HEADER --}}
        <div class="flex items-start justify-between gap-4 pb-6 border-b border-base-300/60">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/15 to-primary/5 flex items-center justify-center ring-1 ring-primary/10">
                    <i class="fa-solid fa-share-nodes text-primary text-lg"></i>
                </div>

                <div>
                    <h2 class="card-title text-base-content text-lg">Platforms</h2>
                    <p class="text-sm text-base-content/60 mt-0.5">Add the company's profiles on social and professional platforms.</p>
                </div>
            </div>

            <div class="badge badge-ghost badge-sm gap-1.5 hidden sm:flex">
                <i class="fa-solid fa-circle-info text-[10px]"></i> Step 6 of 6
            </div>
        </div>

        {{-- PLATFORM SELECTOR --}}
        <div class="mt-6">
            <div class="form-control">
                <label class="label pb-1.5">
                    <span class="label-text font-medium text-base-content/80 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-base-content/30 text-xs"></i> Platforms
                    </span>

                    <span class="label-text-alt">
                        <span class="badge badge-primary badge-sm font-medium" x-show="selectedPlatforms.length > 0" x-text="selectedPlatforms.length + ' selected'" x-cloak></span>
                    </span>
                </label>

                <div class="relative" @click.outside="platformOpen = false">
                    {{-- SELECTED PLATFORM BADGES --}}
                    <button type="button" @click="platformOpen = !platformOpen" class="input input-bordered w-full h-auto min-h-12 flex flex-wrap items-center gap-1.5 py-2 cursor-pointer @error('platform_ids') input-error @enderror" :class="platformOpen && 'input-primary'">
                        <template x-if="selectedPlatforms.length === 0">
                            <span class="text-base-content/40">Select platforms…</span>
                        </template>

                        <template x-for="id in selectedPlatforms" :key="'selected-platform-' + id">
                            <span class="badge badge-neutral gap-1.5 pl-2.5 pr-1.5 py-3 font-normal">
                                <i :class="platformIcon(id)" :style="'color:' + platformColor(id)"></i>
                                <span x-text="platformLabel(id)"></span>
                                <span role="button" tabindex="0" @click.stop="removePlatform(id)" class="btn btn-ghost btn-circle btn-xs -mr-1 hover:bg-base-content/20">
                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                </span>
                            </span>
                        </template>
                        <i class="fa-solid fa-chevron-down text-base-content/30 text-xs ml-auto transition-transform" :class="platformOpen && 'rotate-180'"></i>
                    </button>

                    {{-- DROPDOWN --}}
                    <div x-show="platformOpen" x-cloak x-transition.origin.top class="absolute z-30 mt-2 w-full bg-base-100 rounded-xl border border-base-300 shadow-xl overflow-hidden">
                        {{-- SEARCH --}}
                        <div class="p-2 border-b border-base-300">
                            <label class="input input-sm input-bordered flex items-center gap-2 w-full">
                                <i class="fa-solid fa-magnifying-glass text-base-content/30 text-xs"></i>
                                <input type="text" x-model="platformSearch" placeholder="Search platforms…" class="grow" @click.stop>
                            </label>
                        </div>

                        {{-- ACTIONS --}}
                        <div class="flex items-center justify-between px-3 py-1.5 border-b border-base-300 bg-base-200/50">
                            <button type="button" @click.stop="selectAllFiltered" class="text-xs font-medium text-primary hover:underline">Select visible</button>
                            <button type="button" @click.stop="clearPlatforms" class="text-xs font-medium text-base-content/50 hover:underline">Clear</button>
                        </div>

                        {{-- PLATFORM LIST --}}
                        <ul class="max-h-56 overflow-y-auto py-1">
                            <template x-for="platform in filteredPlatforms" :key="platform.id">
                                <li>
                                    <label class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-base-200 transition-colors"
                                        @click.prevent="togglePlatform(platform.id)">
                                        <input type="checkbox" class="checkbox checkbox-sm checkbox-primary" :checked="selectedPlatforms.includes(platform.id)">
                                        <span class="w-6 text-center">
                                            <i :class="platform.icon" :style="'color:' + platform.color"></i>
                                        </span>
                                        <span class="text-sm flex-1" x-text="platform.name"></span>
                                        <i x-show="selectedPlatforms.includes(platform.id)" class="fa-solid fa-check text-primary text-xs"></i>
                                    </label>
                                </li>
                            </template>

                            <li x-show="filteredPlatforms.length === 0" x-cloak class="px-3 py-6 text-center text-sm text-base-content/40">No platforms found.</li>
                        </ul>
                    </div>
                </div>

                {{-- HIDDEN PLATFORM IDS --}}
                <template x-for="id in selectedPlatforms" :key="'platform-input-' + id">
                    <input type="hidden" name="platform_ids[]" :value="id">
                </template>

                <label class="label pt-1.5">
                    <span class="label-text-alt text-base-content/50 text-wrap">Search and select the platforms where this company has a presence.</span>
                </label>

                @error('platform_ids')
                    <label class="label pt-0">
                        <span class="label-text-alt text-error flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                        </span>
                    </label>
                @enderror

                @error('platform_ids.*')
                    <label class="label pt-0">
                        <span class="label-text-alt text-error flex items-center gap-1">
                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                        </span>
                    </label>
                @enderror
            </div>
        </div>

        {{-- SELECTED PLATFORM URLS --}}
        <div x-show="selectedPlatforms.length > 0" x-cloak class="mt-6">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-7 h-7 rounded-lg bg-base-200 flex items-center justify-center">
                    <i class="fa-solid fa-link text-base-content/50 text-xs"></i>
                </div>

                <div>
                    <h3 class="font-medium text-sm">Platform URLs</h3>
                    <p class="text-xs text-base-content/50">Add the company's profile URL for each selected platform.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <template x-for="id in selectedPlatforms" :key="'platform-url-' + id">
                    <div class="form-control">
                        <label class="label pb-1.5">
                            <span class="label-text font-medium flex items-center gap-2">
                                <span class="w-5 text-center">
                                    <i :class="platformIcon(id)" :style="'color:' + platformColor(id)"></i>
                                </span>

                                <span x-text="platformLabel(id)"></span>
                            </span>
                        </label>

                        <label class="input input-bordered flex items-center gap-2 w-full" :class="urlError(id) && 'input-error'">
                            <i class="fa-solid fa-link text-base-content/30 text-xs"></i>
                            <input type="url" class="grow text-sm" :name="'platform_urls[' + id + ']'" x-model="platformUrls[id]" :placeholder="platformPlaceholder(id)">
                        </label>

                        {{-- URL ERROR --}}
                        <template x-if="urlError(id)">
                            <label class="label pt-1">
                                <span class="label-text-alt text-error flex items-center gap-1" x-text="urlError(id)"></span>
                            </label>
                        </template>

                        @foreach ($platforms as $platform)
                            @error("platform_urls.{$platform->id}")
                                <template x-if="id === '{{ $platform->id }}'">
                                    <label class="label pt-1">
                                        <span class="label-text-alt text-error flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }}
                                        </span>
                                    </label>
                                </template>
                            @enderror
                        @endforeach
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

@once
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('companyPlatforms', ({
            platforms = [],
            selectedPlatforms = [],
            platformUrls = {}
        }) => ({

            // DATA
            platforms: platforms.map(platform => ({
                ...platform,
                id: String(platform.id),
            })),

            selectedPlatforms: selectedPlatforms.map(String),

            platformUrls: Object.fromEntries(
                Object.entries(platformUrls).map(
                    ([id, url]) => [String(id), url ?? '']
                )
            ),

            // UI
            platformOpen: false,
            platformSearch: '',

            // FILTER
            get filteredPlatforms() {
                const search = this.platformSearch.toLowerCase().trim();
                return this.platforms.filter(platform => platform.name.toLowerCase().includes(search));
            },

            // PLATFORM LABEL
            platformLabel(id) {
                const platform = this.platforms.find( platform => platform.id === String(id));
                return platform ? platform.name : id;
            },

            // PLATFORM ICON
            platformIcon(id) {
                const platform = this.platforms.find(platform => platform.id === String(id));
                return platform?.icon || 'fa-solid fa-link';
            },

            // PLATFORM COLOR
            platformColor(id) {
                const platform = this.platforms.find(platform => platform.id === String(id));
                return platform?.color || 'currentColor';
            },

            // PLACEHOLDER
            platformPlaceholder(id) {
                const platform = this.platforms.find(platform => platform.id === String(id));

                if (!platform) {
                    return 'https://example.com/profile';
                }

                return `https://${platform.name.toLowerCase().replace(/\s+/g, '')}.com/...`;
            },

            // URL ERROR
            urlError(id) {
                const url = this.platformUrls[String(id)] ?? '';

                if (!url) {
                    return 'URL is required.';
                }

                return '';
            },

            // TOGGLE
            togglePlatform(id) {
                id = String(id);

                if (this.selectedPlatforms.includes(id)) {
                    this.removePlatform(id);
                    return;
                }

                this.selectedPlatforms = [
                    ...this.selectedPlatforms,
                    id
                ];

                if (!(id in this.platformUrls)) {
                    this.platformUrls[id] = '';
                }
            },

            // REMOVE
            removePlatform(id) {
                id = String(id);

                this.selectedPlatforms =
                    this.selectedPlatforms.filter(
                        platformId => platformId !== id
                    );

                delete this.platformUrls[id];
            },

            // SELECT VISIBLE
            selectAllFiltered() {
                this.filteredPlatforms.forEach(platform => {
                    const id = String(platform.id);

                    if (!this.selectedPlatforms.includes(id)) {
                        this.selectedPlatforms.push(id);
                    }

                    if (!(id in this.platformUrls)) {
                        this.platformUrls[id] = '';
                    }
                });
            },

            // CLEAR
            clearPlatforms() {
                this.selectedPlatforms = [];
                this.platformUrls = {};
            }
        }));
    });
</script>
@endonce