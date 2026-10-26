@props([
    'title' => null,
    'titleColor' => null,
    'titleBgColor' => null,
    'description' => null,
    'icon' => 'fa-solid fa-layer-group',
    'createRoute' => null,
    'createText' => 'Add New',
    'trashRoute' => null,
    'trashCount' => 0,
    'backRoute' => null,
    'backText' => null,
])

<header  class="relative overflow-hidden bg-white">
    <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="flex justify-center sm:justify-start gap-3 shrink-0 items-center rounded-lg">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $titleBgColor }}">
                    <i class="{{ $icon }}"></i>
                </div>
                <h1 class="cv-admin-title text-3xl sm:text-4xl whitespace-nowrap {{ $titleColor }}">{{ $title }}</h1>
            </div>

            <p class="mt-2 max-w-2xl text-sm leading-6 text-base-content/65 text-center sm:text-left">{{ $description }}</p>
        </div>
        
        <div class="flex w-full gap-2 sm:w-auto">
            @if($backRoute)
                <a href="{{ $backRoute }}" class="btn btn-outline gap-2 w-full" aria-label="Back">
                    <i class="fa-solid fa-arrow-left"></i> {{ $backText }}
                </a>
            @endif

            @if($trashRoute)
                <a href="{{ $trashRoute }}" class="btn btn-ghost min-w-0 flex-1 gap-2 border border-base-300 sm:flex-none sm:border-transparent">
                    <i class="fa-solid fa-trash-can"></i> Trash

                    @if ($trashCount > 0)
                        <span class="badge badge-error badge-sm"> {{ $trashCount }} </span>
                    @endif
                </a>
            @endif
    
            @if($createRoute)
                <a href="{{ $createRoute }}" class="btn btn-primary min-w-0 flex-1 gap-2 whitespace-nowrap sm:flex-none">
                    <i class="fa-solid fa-plus"></i> {{ $createText }}
                </a>
            @endif
        </div>
    </div>
</header>