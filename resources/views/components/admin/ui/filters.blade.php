@props([
    'action',
    'method' => 'GET',
    'resetRoute' => null,
])

<form action="{{ $action }}" method="{{ $method }}" {{ $attributes->merge(['class' => 'card bg-base-100 border border-base-300 shadow-sm']) }}>
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3"> {{ $slot }} </div>

        <div class="flex flex-wrap gap-2 mt-3">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-filter"></i> Filter
            </button>

            @if($resetRoute && request()->hasAny(['search', 'country', 'type', 'status', 'capital']))
                <a href="{{ $resetRoute }}" class="btn btn-ghost">Clear</a>
            @endif
        </div>
    </div>
</form>