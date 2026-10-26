@props([
    'icon' => null,
    'title' => 'No records found.',
    'description' => null,
    'actionRoute' => null,
    'actionText' => 'Add New',
])

<div>
    <div class="text-4xl mb-3 opacity-30">
        <i class="{{ $icon }}"></i>
    </div>
    
    <div class="mt-5 text-lg font-black"> {{ $title }} </div>

    @if($description)
        <div class="mx-auto mt-1 max-w-md text-sm text-base-content/50"> {{ $description }} </div>
    @endif

    @if($actionRoute)
        <a href="{{ $actionRoute }}" class="btn btn-primary mt-5 gap-2">
            <i class="fa-solid fa-plus"></i> {{ $actionText }}
        </a>
    @endif
</div>