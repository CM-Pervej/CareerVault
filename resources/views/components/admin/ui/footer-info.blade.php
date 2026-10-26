@props([
    'fileName' => null,
    'description' => 'This page represents an official platform destination maintained in CareerVault.',
])

@if($fileName->isNotEmpty())
    <div class="flex flex-col gap-3 py-4 sm:py-2 text-xs text-base-content/50 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-success"></i>
            <span> {!! $description !!} </span>
        </div>

        <div class="flex items-center gap-2">
            <i class="fa-solid fa-circle-info"></i> CareerVault Platform Directory
        </div>
    </div>
@endif