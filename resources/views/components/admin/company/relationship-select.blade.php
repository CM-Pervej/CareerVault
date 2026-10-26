@props([
    'name',
    'label',
    'items' => collect(),
    'selected' => [],
    'placeholder' => 'Select options',
    'searchPlaceholder' => 'Search...',
    'icon' => 'fa-solid fa-tags',
    'help' => null,
])

@php
    $selected = collect($selected)->map(fn ($id) => (string) $id)->values()->all();

    $options = collect($items)->map(fn ($item) => [
        'id' => (string) data_get($item, 'id'),
        'name' => (string) data_get($item, 'name'),
    ])->values()->all();
@endphp

<div
    class="space-y-2"
    x-data="companyRelationshipSelect({
        items: @js($options),
        selected: @js($selected)
    })"
    @click.outside="open = false"
>
    <label class="label py-0">
        <span class="label-text font-semibold">{{ $label }}</span>
    </label>

    <div class="relative">
        <button type="button"
            class="flex min-h-11 w-full items-center justify-between gap-3 rounded-lg border border-base-300 bg-base-100 px-3 py-2 text-left transition hover:border-primary/60 focus:outline-none focus:ring-2 focus:ring-primary/20"
            @click="open = !open"
            :aria-expanded="open.toString()">
            <span class="flex min-w-0 items-center gap-2">
                <i class="{{ $icon }} w-4 shrink-0 text-base-content/50"></i>
                <span class="truncate text-sm" x-text="selected.length ? `${selected.length} selected` : @js($placeholder)"></span>
            </span>
            <i class="fa-solid fa-chevron-down text-xs text-base-content/50 transition-transform"
                :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-cloak x-show="open"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            class="absolute z-40 mt-2 w-full overflow-hidden rounded-xl border border-base-300 bg-base-100 shadow-xl">

            <div class="border-b border-base-300 p-3">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs text-base-content/40"></i>
                    <input type="search" x-model="search"
                        class="input input-sm w-full pl-8"
                        placeholder="{{ $searchPlaceholder }}">
                </div>

                <div class="mt-2 flex items-center justify-between text-xs">
                    <button type="button" class="link link-primary no-underline"
                        @click="selectVisible()">Select visible</button>
                    <button type="button" class="link link-error no-underline"
                        @click="clear()">Clear all</button>
                </div>
            </div>

            <div class="max-h-56 overflow-y-auto p-2">
                <template x-if="filtered.length === 0">
                    <p class="px-3 py-6 text-center text-sm text-base-content/50">No matching options found.</p>
                </template>

                <template x-for="item in filtered" :key="item.id">
                    <label class="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm transition hover:bg-base-200/70">
                        <input type="checkbox" class="checkbox checkbox-primary checkbox-sm"
                            :checked="selected.includes(item.id)"
                            @change="toggle(item.id)">
                        <span class="min-w-0 flex-1 truncate" x-text="item.name"></span>
                        <i x-show="selected.includes(item.id)"
                            class="fa-solid fa-check text-xs text-primary"></i>
                    </label>
                </template>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-2" x-show="selected.length" x-cloak>
        <template x-for="id in selected" :key="id">
            <span class="inline-flex max-w-full items-center gap-2 rounded-full border border-primary/20 bg-primary/10 py-1 pl-3 pr-1.5 text-xs text-primary">
                <span class="max-w-48 truncate" x-text="labelFor(id)"></span>
                <button type="button" class="rounded-full p-1 transition hover:bg-primary/15"
                    @click="remove(id)" :aria-label="'Remove ' + labelFor(id)">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </span>
        </template>
    </div>

    <template x-for="id in selected" :key="'input-' + id">
        <input type="hidden" name="{{ $name }}[]" :value="id">
    </template>

    @if ($help)
        <p class="text-xs text-base-content/50">{{ $help }}</p>
    @endif
</div>

@once
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('companyRelationshipSelect', ({ items = [], selected = [] }) => ({
                items: items.map(item => ({ ...item, id: String(item.id) })),
                selected: selected.map(String),
                open: false,
                search: '',

                get filtered() {
                    const query = this.search.trim().toLowerCase();

                    return this.items.filter(item =>
                        item.name.toLowerCase().includes(query)
                    );
                },

                labelFor(id) {
                    return this.items.find(item => item.id === String(id))?.name ?? id;
                },

                toggle(id) {
                    id = String(id);

                    this.selected = this.selected.includes(id)
                        ? this.selected.filter(value => value !== id)
                        : [...this.selected, id];
                },

                remove(id) {
                    this.selected = this.selected.filter(value => value !== String(id));
                },

                selectVisible() {
                    this.selected = [...new Set([
                        ...this.selected,
                        ...this.filtered.map(item => item.id)
                    ])];
                },

                clear() {
                    this.selected = [];
                    this.search = '';
                }
            }));
        });
    </script>
@endonce