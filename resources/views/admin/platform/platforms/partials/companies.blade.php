{{-- Linked Companies: searchable, filterable, expandable grid --}}
@php
    $showRoute = \Illuminate\Support\Facades\Route::has('admin.companies.show');
    $platformName = $platform->name ?? 'this platform';

    $items = $platform->companies->values()->map(function ($company, $i) use ($showRoute) {
        $url = $company->pivot->url;
        $hasUrl = filled($url);
        $scheme = $hasUrl ? strtolower((string) parse_url($url, PHP_URL_SCHEME)) : null;
        $isSafe = $hasUrl && in_array($scheme, ['http', 'https'], true);
        $initials = collect(preg_split('/\s+/', trim($company->name)) ?: [])
            ->filter()->take(2)->map(fn ($w) => mb_substr($w, 0, 1))->implode('');
        $linkedAt = ($company->pivot->created_at ?? null) ? \Illuminate\Support\Carbon::parse($company->pivot->created_at) : null;
        $host = $isSafe ? (parse_url($url, PHP_URL_HOST) ?: $url) : null;
        $status = $isSafe ? 'Valid URL' : ($hasUrl ? 'Invalid URL' : 'No URL recorded');

        return (object) [
            'id'       => 'cv-row-'.$i,
            'index'    => $i,
            'model'    => $company,
            'name'     => $company->name,
            'url'      => $url,
            'hasUrl'   => $hasUrl,
            'isSafe'   => $isSafe,
            'scheme'   => $scheme,
            'host'     => $host,
            'initials' => $initials ?: 'CO',
            'hue'      => crc32($company->name) % 360,
            'route'    => $showRoute ? route('admin.companies.show', $company) : null,
            'status'   => $status,
            'state'    => $isSafe ? 'valid' : ($hasUrl ? 'invalid' : 'none'),
            'linkedAt' => $linkedAt,
            'search'   => mb_strtolower($company->name.' '.$host.' '.$status),
        ];
    });

    $total = $items->count();
    $linked = $items->where('isSafe', true)->count();
    $attention = $total - $linked;
    $pct = $total ? round($linked / $total * 100) : 0;
    $hasDates = $items->contains(fn ($c) => $c->linkedAt);
@endphp

<section class="rounded-3xl border border-base-300 bg-base-100 p-5 sm:p-6 shadow-sm" data-linked-companies>
    <div class="mb-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <h2 class="text-lg sm:text-xl font-black">Linked companies</h2>
                <p class="mt-1 text-sm text-base-content/50">Companies connected to {{ $platformName }}.</p>
            </div>
            <span class="badge badge-ghost badge-sm shrink-0">{{ $total }} {{ \Illuminate\Support\Str::plural('company', $total) }}</span>
        </div>

        @if($total > 0)
            <div class="mt-4 flex items-center gap-3">
                <div class="flex h-1.5 flex-1 overflow-hidden rounded-full bg-base-200" role="img" aria-label="{{ $linked }} of {{ $total }} companies have a valid URL">
                    <span class="bg-success" style="width: {{ $pct }}%"></span>
                </div>
                <span class="shrink-0 text-xs text-base-content/55">{{ $linked }} of {{ $total }} have a valid URL</span>
            </div>
        @endif
    </div>

    @if($total > 0)
        {{-- Toolbar --}}
        <div class="mb-4 flex flex-col gap-3">
            <div class="flex flex-col gap-2 sm:flex-row">
                <label class="input input-bordered flex h-10 flex-1 items-center gap-2 rounded-xl">
                    <i class="fa-solid fa-magnifying-glass text-base-content/40" aria-hidden="true"></i>
                    <input type="search" class="grow" placeholder="Search by name, domain or status" aria-label="Search linked companies" autocomplete="off" data-cv-search>
                    <button type="button" class="btn btn-ghost btn-circle btn-xs hidden" aria-label="Clear search" data-cv-clear><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                    <kbd class="kbd kbd-sm hidden sm:inline-flex" data-cv-kbd>/</kbd>
                </label>

                @if($total > 1)
                    <select class="select select-bordered h-10 min-h-0 rounded-xl text-sm" aria-label="Sort companies" data-cv-sort>
                        <option value="default">Sort: default</option>
                        <option value="az">Name A to Z</option>
                        <option value="za">Name Z to A</option>
                        @if($hasDates)<option value="recent">Recently linked</option>@endif
                        @if($attention > 0)<option value="attention">Needs attention first</option>@endif
                    </select>
                    <button type="button" class="btn btn-outline h-10 min-h-0 rounded-xl" data-cv-toggle-all>
                        <i class="fa-solid fa-angles-down" aria-hidden="true"></i><span>Expand all</span>
                    </button>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filter by link status">
                @foreach([['all', 'All', $total], ['valid', 'Valid URL', $linked], ['attention', 'Needs attention', $attention]] as [$key, $label, $count])
                    @continue($key === 'attention' && $attention === 0)
                    <button type="button" data-cv-chip="{{ $key }}" aria-pressed="{{ $key === 'all' ? 'true' : 'false' }}"
                            class="btn btn-xs h-7 rounded-full border-base-300 bg-base-100 font-semibold aria-pressed:border-base-content aria-pressed:bg-base-content aria-pressed:text-base-100">
                        {{ $label }} <span class="opacity-60">{{ $count }}</span>
                    </button>
                @endforeach
                <span class="ml-auto text-xs text-base-content/50" role="status" aria-live="polite" data-cv-count></span>
            </div>
        </div>

        {{-- Grid: 1 column on mobile, 2 on desktop. An open card spans the full row. --}}
        <ul class="grid grid-cols-1 items-start gap-3 sm:grid-cols-3" role="list" data-cv-list>
            @foreach($items as $c)
                <li class="group rounded-2xl border border-base-300 bg-base-100 transition-colors hover:border-base-content/25 data-[open=true]:border-primary/40 data-[open=true]:bg-base-200/40 sm:data-[open=true]:col-span-2 sm:data-[open=true]:row-span-3 motion-reduce:transition-none"
                    data-cv-row data-open="false" data-index="{{ $c->index }}" data-name="{{ mb_strtolower($c->name) }}"
                    data-state="{{ $c->state }}" data-linked="{{ $c->linkedAt?->timestamp ?? 0 }}" data-search="{{ $c->search }}"
                    style="--h: {{ $c->hue }}">

                    <h3>
                        <button type="button" id="{{ $c->id }}-btn" aria-expanded="false" aria-controls="{{ $c->id }}-body" data-cv-btn
                                class="flex w-full items-center gap-3 rounded-2xl p-3 text-left outline-none focus-visible:ring-2 focus-visible:ring-primary">
                            <span class="relative flex size-11 shrink-0 items-center justify-center rounded-xl bg-base-200">
                                @if(!empty($c->model->logo))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($c->model->logo) }}" alt="" loading="lazy" class="size-8 rounded-lg object-contain">
                                @elseif(!empty($c->model->icon))
                                    <i class="{{ $c->model->icon }} text-lg" aria-hidden="true"></i>
                                @else
                                    <span class="flex size-full items-center justify-center rounded-xl bg-[oklch(0.55_0.13_var(--h))] text-sm font-black uppercase text-white">{{ $c->initials }}</span>
                                @endif
                                <span class="absolute -bottom-0.5 -right-0.5 size-3 rounded-full ring-2 ring-base-100 {{ $c->isSafe ? 'bg-success' : ($c->hasUrl ? 'bg-error' : 'bg-warning') }}" role="img" aria-label="{{ $c->status }}"></span>
                            </span>

                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-bold">{{ $c->name }}</span>
                                <span class="mt-0.5 block truncate text-xs {{ $c->isSafe ? 'text-base-content/55' : ($c->hasUrl ? 'text-error' : 'text-warning') }}">{{ $c->host ?? $c->status }}</span>
                            </span>

                            <i class="fa-solid fa-chevron-down shrink-0 text-xs text-base-content/40 transition-transform duration-300 group-data-[open=true]:rotate-180 motion-reduce:transition-none" aria-hidden="true"></i>
                        </button>
                    </h3>

                    <div id="{{ $c->id }}-body" role="region" aria-labelledby="{{ $c->id }}-btn" inert
                         class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out group-data-[open=true]:grid-rows-[1fr] motion-reduce:transition-none">
                        <div class="overflow-hidden">
                            <div class="px-4 pb-4 sm:pl-[4.5rem]">
                                <dl class="grid gap-x-1 gap-y-3 text-sm sm:grid-cols-3">
                                    <div class="sm:col-span-3">
                                        <dt class="text-xs text-base-content/50">Linked URL</dt>
                                        <dd class="mt-0.5 break-all">
                                            @if($c->isSafe) 
                                                <span class="text-xs text-blue-500 font-semibold">{{ $c->url }}</span>
                                            @elseif($c->hasUrl) <span class="text-error">Only http and https links can be opened.</span>
                                            @else <span class="text-base-content/50">None yet. Add a URL to this link to open it from here.</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-base-content/50">Status</dt>
                                        <dd class="mt-0.5 flex items-center gap-1.5">
                                            <span class="size-2 rounded-full {{ $c->isSafe ? 'bg-success' : ($c->hasUrl ? 'bg-error' : 'bg-warning') }}"></span>{{ $c->status }}
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-base-content/50">Connection</dt>
                                        <dd class="mt-0.5 text-sm {{ $c->scheme === 'http' ? 'text-warning' : '' }}">
                                            @if($c->scheme === 'https') Encrypted (HTTPS)
                                            @elseif($c->scheme === 'http') Not encrypted (HTTP)
                                            @else <span class="text-base-content/50">Not available</span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-xs text-base-content/50">Linked</dt>
                                        <dd class="mt-0.5" @if($c->linkedAt) title="{{ $c->linkedAt->toDayDateTimeString() }}" @endif>
                                            {!! $c->linkedAt ? e($c->linkedAt->diffForHumans()) : '<span class="text-base-content/50">Date not recorded</span>' !!}
                                        </dd>
                                    </div>
                                </dl>

                                @if($c->isSafe || $c->route)
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @if($c->isSafe)
                                            <a href="{{ $c->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm rounded-xl">
                                                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> Open {{ $c->host }}
                                            </a>
                                        @endif
                                        @if($c->route)
                                            <a href="{{ $c->route }}" class="btn btn-ghost btn-sm rounded-xl">
                                                <i class="fa-solid fa-building" aria-hidden="true"></i> View company
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- No results --}}
        <div class="hidden rounded-2xl border border-dashed border-base-300 px-5 py-10 text-center" data-cv-empty>
            <div class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-base-200">
                <i class="fa-solid fa-magnifying-glass text-xl text-base-content/35" aria-hidden="true"></i>
            </div>
            <h3 class="mt-3 font-bold">No companies found</h3>
            <p class="mt-1 text-sm text-base-content/50">Nothing matches <span class="font-semibold" data-cv-empty-q></span>. Try a different word or clear the filters.</p>
            <button type="button" class="btn btn-sm btn-outline mt-4 rounded-xl" data-cv-reset>Clear filters</button>
        </div>
    @else
        <div class="rounded-2xl border border-dashed border-base-300 px-5 py-10 text-center">
            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200">
                <i class="fa-solid fa-building text-2xl text-base-content/35" aria-hidden="true"></i>
            </div>
            <h3 class="mt-4 font-bold">No linked companies yet</h3>
            <p class="mt-1 text-sm text-base-content/50">Link a company to this platform and it will show up here.</p>
        </div>
    @endif
</section>