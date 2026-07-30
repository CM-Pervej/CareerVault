{{-- Requires Alpine.js in your layout <head>:
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

@php
    $selectedCountries = old(
        'country_ids',
        $company?->countries->pluck('id')->toArray() ?? []
    );

    $selectedIndustries = old(
        'industry_ids',
        $company?->industries->pluck('id')->toArray() ?? []
    );
@endphp

<div class="card bg-base-100 shadow-xl border border-base-300/60 rounded-2xl">
    <div class="card-body p-6 lg:p-8">
        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 pb-6 border-b border-base-300/60">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/15 to-primary/5 flex items-center justify-center ring-1 ring-primary/10">
                    <i class="fa-solid fa-tags text-primary text-lg"></i>
                </div>
                <div>
                    <h2 class="card-title text-base-content text-lg">Classification</h2>
                    <p class="text-sm text-base-content/60 mt-0.5">Select the countries and industries associated with this company.</p>
                </div>
            </div>

            <div class="badge badge-ghost badge-sm gap-1.5 hidden sm:flex">
                <i class="fa-solid fa-circle-info text-[10px]"></i>Step 2 of 6
            </div>
        </div>

        {{-- Fields --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-6 gap-y-6">
            {{-- Countries --}}
            <div class="form-control"
                x-data="tagPicker({
                    name: 'country_ids',
                    label: 'Countries',
                    selected: {{ json_encode(array_map('strval', $selectedCountries)) }},
                    items: {{ $countries->map(fn ($i) => ['id' => (string) $i->id, 'name' => $i->name])->values() }}
                })"
                @click.outside="open = false">

                <label class="label pb-1.5">
                    <span class="label-text font-medium text-base-content/80 flex items-center gap-2"> <i class="fa-solid fa-earth-americas text-base-content/30 text-xs"></i> Countries </span>
                    <span class="label-text-alt">
                        <span class="badge badge-primary badge-sm font-medium" x-show="selected.length > 0" x-text="selected.length + ' selected'" x-cloak></span>
                    </span>
                </label>

                <div class="relative">
                    <button type="button" @click="open = !open" class="input input-bordered w-full h-auto min-h-12 flex flex-wrap items-center gap-1.5 py-2 cursor-pointer @error('country_ids') input-error @enderror" :class="open && 'input-primary'">
                        <template x-if="selected.length === 0"> 
                            <span class="text-base-content/40">Select countries…</span> 
                        </template>

                        <template x-for="id in selected" :key="id">
                            <span class="badge badge-neutral gap-1.5 pl-3 pr-1.5 py-3 font-normal">
                                <span x-text="label(id)"></span>
                                <button type="button" @click.stop="remove(id)" class="btn btn-ghost btn-circle btn-xs -mr-1 hover:bg-base-content/20"> <i class="fa-solid fa-xmark text-[10px]"></i> </button>
                            </span>
                        </template>

                        <i class="fa-solid fa-chevron-down text-base-content/30 text-xs ml-auto transition-transform" :class="open && 'rotate-180'"></i>
                    </button>

                    <div x-show="open" x-cloak x-transition.origin.top class="absolute z-20 mt-2 w-full bg-base-100 rounded-xl border border-base-300 shadow-xl overflow-hidden">
                        <div class="p-2 border-b border-base-300 flex items-center gap-2">
                            <label class="input input-sm input-bordered flex items-center gap-2 w-full">
                                <i class="fa-solid fa-magnifying-glass text-base-content/30 text-xs"></i>
                                <input type="text" x-model="search" placeholder="Search countries…" class="grow" @click.stop>
                            </label>
                        </div>

                        <div class="flex items-center justify-between px-3 py-1.5 border-b border-base-300 bg-base-200/50">
                            <button type="button" @click.stop="selectAll" class="text-xs font-medium text-primary hover:underline">Select all</button>
                            <button type="button" @click.stop="clearAll" class="text-xs font-medium text-base-content/50 hover:underline">Clear</button>
                        </div>

                        <ul class="max-h-56 overflow-y-auto py-1">
                            <template x-for="item in filtered" :key="item.id">
                                <li>
                                    <label class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-base-200 transition-colors" @click.prevent="toggle(item.id)">
                                        <input type="checkbox" class="checkbox checkbox-sm checkbox-primary" :checked="selected.includes(item.id)" @click.stop="toggle(item.id)">
                                        <span class="text-sm" x-text="item.name"></span>
                                    </label>
                                </li>
                            </template>

                            <li x-show="filtered.length === 0" x-cloak class="px-3 py-6 text-center text-sm text-base-content/40">No matches found.</li>
                        </ul>
                    </div>
                </div>

                <template x-for="id in selected" :key="id"> 
                    <input type="hidden" name="country_ids[]" :value="id"> 
                </template>

                <label class="label pt-1.5"> 
                    <span class="label-text-alt text-base-content/50">Type to search, click to toggle a selection.</span> 
                </label>

                @error('country_ids')
                    <label class="label pt-0">
                        <span class="label-text-alt text-error flex items-center gap-1"> <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }} </span>
                    </label>
                @enderror
            </div>

            {{-- Industries --}}
            <div class="form-control"
                x-data="tagPicker({
                    name: 'industry_ids',
                    label: 'Industries',
                    selected: {{ json_encode(array_map('strval', $selectedIndustries)) }},
                    items: {{ $industries->map(fn ($i) => ['id' => (string) $i->id, 'name' => $i->name])->values() }}
                })"
                @click.outside="open = false">

                <label class="label pb-1.5">
                    <span class="label-text font-medium text-base-content/80 flex items-center gap-2"> 
                        <i class="fa-solid fa-industry text-base-content/30 text-xs"></i> Industries 
                    </span>
                    <span class="label-text-alt">
                        <span class="badge badge-primary badge-sm font-medium" x-show="selected.length > 0" x-text="selected.length + ' selected'" x-cloak></span>
                    </span>
                </label>

                <div class="relative">
                    <button type="button" @click="open = !open" class="input input-bordered w-full h-auto min-h-12 flex flex-wrap items-center gap-1.5 py-2 cursor-pointer @error('industry_ids') input-error @enderror" :class="open && 'input-primary'">
                        <template x-if="selected.length === 0"> 
                            <span class="text-base-content/40">Select industries…</span> 
                        </template>

                        <template x-for="id in selected" :key="id">
                            <span class="badge badge-neutral gap-1.5 pl-3 pr-1.5 py-3 font-normal">
                                <span x-text="label(id)"></span>
                                <button type="button" @click.stop="remove(id)" class="btn btn-ghost btn-circle btn-xs -mr-1 hover:bg-base-content/20"> <i class="fa-solid fa-xmark text-[10px]"></i> </button>
                            </span>
                        </template>

                        <i class="fa-solid fa-chevron-down text-base-content/30 text-xs ml-auto transition-transform" :class="open && 'rotate-180'"></i>
                    </button>

                    <div x-show="open" x-cloak x-transition.origin.top class="absolute z-20 mt-2 w-full bg-base-100 rounded-xl border border-base-300 shadow-xl overflow-hidden">
                        <div class="p-2 border-b border-base-300 flex items-center gap-2">
                            <label class="input input-sm input-bordered flex items-center gap-2 w-full">
                                <i class="fa-solid fa-magnifying-glass text-base-content/30 text-xs"></i>
                                <input type="text" x-model="search" placeholder="Search industries…" class="grow" @click.stop>
                            </label>
                        </div>

                        <div class="flex items-center justify-between px-3 py-1.5 border-b border-base-300 bg-base-200/50">
                            <button type="button" @click.stop="selectAll" class="text-xs font-medium text-primary hover:underline">Select all</button>
                            <button type="button" @click.stop="clearAll" class="text-xs font-medium text-base-content/50 hover:underline">Clear</button>
                        </div>

                        <ul class="max-h-56 overflow-y-auto py-1">
                            <template x-for="item in filtered" :key="item.id">
                                <li>
                                    <label class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-base-200 transition-colors" @click.prevent="toggle(item.id)">
                                        <input type="checkbox" class="checkbox checkbox-sm checkbox-primary" :checked="selected.includes(item.id)" @click.stop="toggle(item.id)">
                                        <span class="text-sm" x-text="item.name"></span>
                                    </label>
                                </li>
                            </template>

                            <li x-show="filtered.length === 0" x-cloak class="px-3 py-6 text-center text-sm text-base-content/40">No matches found.</li>
                        </ul>
                    </div>
                </div>

                <template x-for="id in selected" :key="id"> 
                    <input type="hidden" name="industry_ids[]" :value="id"> 
                </template>

                <label class="label pt-1.5"> 
                    <span class="label-text-alt text-base-content/50">Type to search, click to toggle a selection.</span> 
                </label>

                @error('industry_ids')
                    <label class="label pt-0">
                        <span class="label-text-alt text-error flex items-center gap-1"> <i class="fa-solid fa-circle-exclamation text-[11px]"></i> {{ $message }} </span>
                    </label>
                @enderror
            </div>
        </div>
    </div>
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tagPicker', ({ name, label, selected, items }) => ({
                open: false,
                search: '',
                selected: selected,
                items: items,
                name: name,
                label(id) {
                    const item = this.items.find(i => i.id === String(id));
                    return item ? item.name : id;
                },
                get filtered() {
                    return this.items.filter(i => i.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                toggle(id) {
                    id = String(id);
                    this.selected = this.selected.includes(id)
                        ? this.selected.filter(i => i !== id)
                        : [...this.selected, id];
                },
                remove(id) {
                    this.selected = this.selected.filter(i => i !== String(id));
                },
                selectAll() {
                    this.selected = [...new Set([...this.selected, ...this.filtered.map(i => i.id)])];
                },
                clearAll() {
                    this.selected = [];
                }
            }));
        });
    </script>
@endonce