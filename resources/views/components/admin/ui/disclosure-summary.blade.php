@props([
    'title' => null,
    'description' => null,
    'icon'  => null,
])

<summary class="flex items-center justify-between gap-3 p-4 hover:bg-base-200/40">
    <span class="flex min-w-0 items-center gap-2.5">
        <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-primary/10 text-primary">
            <i class="{{ $icon }} text-xs"></i>
        </span>

        <span class="min-w-0">
            <span class="block text-sm font-semibold">{{ $title }}</span>
            <span class="block truncate text-xs text-base-content/50">{{ $description }}</span>
        </span>
    </span>

    <i class="fa-solid fa-chevron-down cv-chevron text-xs text-base-content/50"></i>
</summary>