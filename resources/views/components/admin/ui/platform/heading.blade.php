@props([
    'title' => 'Directory',
    'icon' => 'fa-solid fa-file-lines',
    'fileName' => null,
    'singularFile' => null,
    'pluralFile' => null,
    'description' => null,
])

<div class="px-5 py-4">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary">
                <i class="{{ $icon }}"></i>
            </div>
            <span class="whitespace-nowrap">{{ $title }}</span>
        </div>

        @if ($fileName)
            <div class="badge badge-outline gap-1.5">
                <i class="fa-solid fa-file-lines"></i>
                {{ number_format($fileName->total()) }}
                {{ $fileName->total() === 1 ? $singularFile : $pluralFile }}
            </div>
        @endif
    </div>

    <p class="mt-3 text-center sm:text-start text-xs text-base-content/50"> {{ $description }} </p>
</div>