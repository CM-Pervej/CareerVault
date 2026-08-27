@php
    $filterId = $id ?? 'databaseSearch';
    $filterUrl = $url ?? url()->current();
    $filterPlaceholder = $placeholder ?? 'Search...';
    $filterValue = $value ?? request('search', '');
    $filterLoadingId = $loadingId ?? null;
    $filterShortcutId = $shortcutId ?? null;
@endphp

<div class="relative w-full sm:w-72">
    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>

    <input
        type="text" autocomplete="off" class="w-full rounded-sm border-0 bg-white pl-9 pr-9 py-2.5 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-white/70 shadow-sm"
        id="{{ $filterId }}"
        data-database-search
        data-url="{{ $filterUrl }}"
        value="{{ $filterValue }}"
        placeholder="{{ $filterPlaceholder }}">

    @if($filterLoadingId)
        <span 
            class="hidden absolute right-3 top-1/2 -translate-y-1/2"
            id="{{ $filterLoadingId }}">
            <i class="fa-solid fa-spinner fa-spin text-indigo-500 text-sm"></i>
        </span>
    @endif

    @if(request('search'))
        <a 
            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs" 
            href="{{ $filterUrl }}" 
            title="Clear search">
            <i class="fa-solid fa-xmark"></i>
        </a>
    @elseif($filterShortcutId)
        <kbd 
            class="hidden md:inline-flex absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-medium text-slate-400 border border-slate-200 rounded px-1.5 py-0.5 bg-white" 
            id="{{ $filterShortcutId }}">
            /
        </kbd>
    @endif
</div>