
{{-- Official Presence: searchable, filterable, expandable grid --}}
@php
    $connectedPlatforms = $platform->connectedPlatforms->values();
    $manageRoute = route('admin.platform-connections.edit', $platform);

    $items = $connectedPlatforms->map(function ($connected, $i) {
        $url = $connected->pivot->account_url;
        $hasUrl = filled($url);
        $scheme = $hasUrl ? strtolower((string) parse_url($url, PHP_URL_SCHEME)) : null;
        $isSafe = $hasUrl && in_array($scheme, ['http', 'https'], true);
        $host = $isSafe ? (parse_url($url, PHP_URL_HOST) ?: $url) : null;

        $linkedAt = ($connected->pivot->created_at ?? null) ? \Illuminate\Support\Carbon::parse($connected->pivot->created_at) : null;

        $initials = collect(preg_split('/\s+/', trim($connected->name)) ?: [])
            ->filter()
            ->take(2)
            ->map(fn ($word) => mb_substr($word, 0, 1))
            ->implode('');

        $status = $isSafe ? 'Valid URL' : ($hasUrl ? 'Invalid URL' : 'No URL recorded');

        return (object) [
            'id' => 'cv-presence-'.$i,
            'index' => $i,
            'model' => $connected,
            'name' => $connected->name,
            'url' => $url,
            'hasUrl' => $hasUrl,
            'isSafe' => $isSafe,
            'scheme' => $scheme,
            'host' => $host,
            'initials' => $initials ?: 'PL',
            'hue' => crc32($connected->name) % 360,
            'route' => route('admin.platforms.show', $connected),
            'status' => $status,
            'state' => $isSafe ? 'valid' : ($hasUrl ? 'invalid' : 'none'),
            'linkedAt' => $linkedAt,
            'search' => mb_strtolower($connected->name.' '.$host.' '.$url.' '.$status),
        ];
    });

    $total = $items->count();
    $validCount = $items->where('isSafe', true)->count();
    $attentionCount = $total - $validCount;
    $validPercentage = $total ? round($validCount / $total * 100) : 0;
    $hasDates = $items->contains(fn ($item) => $item->linkedAt);
@endphp

<section class="rounded-3xl border border-base-300 bg-base-100 p-4 shadow-sm sm:p-6" data-linked-companies>
    {{-- Header --}}
    <div class="mb-5">
        <div class="flex items-start justify-between gap-3">
            <div class="flex min-w-0 items-start gap-3">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                    <i class="fa-solid fa-share-nodes text-lg" aria-hidden="true"></i>
                </div>

                <div class="min-w-0">
                    <h2 class="text-lg font-black tracking-tight sm:text-xl">Official Presence</h2>
                    <p class="mt-1 text-sm text-base-content/50">Official accounts associated with {{ $platform->name }}. </p>
                </div>
            </div>

            <a href="{{ $manageRoute }}" class="btn btn-primary btn-sm shrink-0 gap-2 rounded-xl max-sm:btn-square" aria-label="Manage official presence">
                <i class="fa-solid fa-sliders" aria-hidden="true"></i>
                <span class="hidden sm:inline">Manage</span>
            </a>
        </div>

        {{-- Connection summary --}}
        @if($total > 0)
            <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-base-300 bg-base-200/30 p-3">
                    <p class="text-xs text-base-content/55">Total connections</p>
                    <p class="mt-1 text-2xl font-black tabular-nums">{{ $total }}</p>
                </div>

                <div class="rounded-2xl border border-success/20 bg-success/5 p-3">
                    <p class="text-xs text-base-content/55">Valid URLs</p>
                    <p class="mt-1 text-2xl font-black tabular-nums text-success">{{ $validCount }}</p>
                </div>

                <div class="col-span-2 rounded-2xl border border-base-300 bg-base-200/30 p-3 sm:col-span-1">
                    <p class="text-xs text-base-content/55">Needs attention</p>
                    <p class="mt-1 text-2xl font-black tabular-nums {{ $attentionCount ? 'text-warning' : 'text-base-content' }}"> {{ $attentionCount }} </p>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <div class="flex h-1.5 flex-1 overflow-hidden rounded-full bg-base-200" role="img" aria-label="{{ $validCount }} of {{ $total }} connections have valid URLs">
                    <span class="rounded-full bg-success transition-[width] duration-300 motion-reduce:transition-none" style="width: {{ $validPercentage }}%"></span>
                </div>
                <span class="shrink-0 text-xs text-base-content/55"> {{ $validPercentage }}% valid </span>
            </div>
        @endif
    </div>

    @if($total > 0)
        {{-- Search, sorting and expansion controls --}}
        <div class="mb-4 space-y-3">
            <div class="flex flex-col gap-2 sm:flex-row">
                <label class="input input-bordered flex h-11 min-w-0 flex-1 items-center gap-2 rounded-xl">
                    <i class="fa-solid fa-magnifying-glass text-base-content/40" aria-hidden="true"></i>
                    <input type="search" class="grow" placeholder="Search platform, domain or status" aria-label="Search official presence" autocomplete="off" data-cv-search>

                    <button type="button" class="btn btn-ghost btn-circle btn-xs hidden" aria-label="Clear search" data-cv-clear>
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                    <kbd class="kbd kbd-sm hidden sm:inline-flex" data-cv-kbd>/</kbd>
                </label>

                @if($total > 1)
                    <select class="select select-bordered h-11 min-h-0 w-full rounded-xl sm:w-auto" aria-label="Sort official presence" data-cv-sort>
                        <option value="default">Default order</option>
                        <option value="az">Name A–Z</option>
                        <option value="za">Name Z–A</option>

                        @if($hasDates)
                            <option value="recent">Recently linked</option>
                        @endif

                        @if($attentionCount > 0)
                            <option value="attention">Needs attention first</option>
                        @endif
                    </select>

                    <button type="button" class="btn btn-outline h-11 min-h-0 shrink-0 gap-2 rounded-xl" data-cv-toggle-all>
                        <i class="fa-solid fa-angles-down" aria-hidden="true"></i>
                        <span>Expand all</span>
                    </button>
                @endif
            </div>

            {{-- URL status filters --}}
            <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filter connections by URL status">
                @foreach([
                    ['all', 'All', $total],
                    ['valid', 'Valid URL', $validCount],
                    ['attention', 'Needs attention', $attentionCount],
                ] as [$key, $label, $count])
                    @continue($key === 'attention' && $attentionCount === 0)

                    <button type="button" data-cv-chip="{{ $key }}" aria-pressed="{{ $key === 'all' ? 'true' : 'false' }}" class="btn btn-xs h-8 rounded-full border-base-300 bg-base-100 font-semibold aria-pressed:border-base-content aria-pressed:bg-base-content aria-pressed:text-base-100">
                        {{ $label }} <span class="opacity-60">{{ $count }}</span>
                    </button>
                @endforeach

                <span class="ml-auto text-xs text-base-content/50" role="status" aria-live="polite" data-cv-count></span>
            </div>
        </div>

        {{-- Expandable platform cards --}}
        <ul class="grid grid-cols-1 items-start gap-3 sm:grid-cols-3 lg:grid-cols-3" role="list" data-cv-list>
            @foreach($items as $c)
                <li class="group min-w-0 rounded-2xl border border-base-300 bg-base-100 transition-[border-color,background-color] duration-200 hover:border-base-content/25 data-[open=true]:border-primary/40 data-[open=true]:bg-base-200/30 sm:data-[open=true]:col-span-2 lg:data-[open=true]:col-span-2 sm:data-[open=true]:row-span-3 motion-reduce:transition-none" data-cv-row data-open="false" data-index="{{ $c->index }}" data-name="{{ mb_strtolower($c->name) }}" data-state="{{ $c->state }}" data-linked="{{ $c->linkedAt?->timestamp ?? 0 }}" data-search="{{ $c->search }}" style="--h: {{ $c->hue }}">
                    <h3>
                        <button type="button" id="{{ $c->id }}-btn" aria-expanded="false" aria-controls="{{ $c->id }}-body" data-cv-btn class="flex w-full min-w-0 items-center gap-3 rounded-2xl p-3 text-left outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary sm:p-4">
                            {{-- Logo / icon --}}
                            <span class="relative flex size-12 shrink-0 items-center justify-center rounded-xl border border-base-300/70 bg-base-200/60">
                                @if($c->model->logo)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($c->model->logo) }}" alt="" loading="lazy" class="size-8 object-contain">
                                @elseif($c->model->icon)
                                    <i class="{{ $c->model->icon }} text-xl" style="color: {{ $c->model->color ?: 'currentColor' }}" aria-hidden="true"></i>
                                @else
                                    <span class="flex size-full items-center justify-center rounded-xl bg-[oklch(0.55_0.13_var(--h))] text-sm font-black uppercase text-white"> {{ $c->initials }} </span>
                                @endif

                                <span class="absolute -bottom-1 -right-1 size-3 rounded-full ring-2 ring-base-100 {{ $c->isSafe ? 'bg-success' : ($c->hasUrl ? 'bg-error' : 'bg-warning') }}" role="img" aria-label="{{ $c->status }}"></span>
                            </span>

                            {{-- Platform name and URL summary --}}
                            <span class="min-w-0 flex-1">
                                <span class="block break-words text-sm font-bold [overflow-wrap:anywhere]"> {{ $c->name }} </span>
                                <span class="mt-1 block break-all text-xs {{ $c->isSafe ? 'text-base-content/55' : ($c->hasUrl ? 'text-error' : 'text-warning') }}"> {{ $c->host ?? $c->status }} </span>
                            </span>

                            <span class="flex shrink-0 items-center gap-2">
                                <i class="fa-solid fa-chevron-down shrink-0 text-xs text-base-content/45 transition-transform duration-300 group-data-[open=true]:rotate-180 motion-reduce:transition-none" aria-hidden="true"></i>
                            </span>
                        </button>
                    </h3>

                    {{-- Animated expandable details --}}
                    <div id="{{ $c->id }}-body" role="region" aria-labelledby="{{ $c->id }}-btn" inert class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out group-data-[open=true]:grid-rows-[1fr] motion-reduce:transition-none">
                        <div class="overflow-hidden">
                            <div class="border-t border-base-300/70 px-4 pb-4 pt-4 sm:px-5">
                                <dl class="grid min-w-0 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    {{-- Official account URL --}}
                                    <div class="min-w-0 sm:col-span-2 lg:col-span-3">
                                        <dt class="text-xs font-medium text-base-content/50">Official account URL</dt>
                                        <dd class="mt-1 min-w-0">
                                            @if($c->isSafe)
                                                <a href="{{ $c->url }}" target="_blank" rel="noopener noreferrer" class="block break-all text-sm font-medium text-primary hover:underline [overflow-wrap:anywhere]"> {{ $c->url }} </a>
                                            @elseif($c->hasUrl)
                                                <p class="break-words text-sm text-error [overflow-wrap:anywhere]">This URL cannot be opened because it does not use HTTP or HTTPS.</p>
                                            @else
                                                <p class="text-sm text-base-content/50">No account URL has been recorded yet.</p>
                                            @endif
                                        </dd>
                                    </div>

                                    {{-- URL status --}}
                                    <div class="min-w-0">
                                        <dt class="text-xs text-base-content/50">URL status</dt>
                                        <dd class="mt-1 flex items-center gap-2 text-sm font-medium">
                                            <span class="size-2 shrink-0 rounded-full {{ $c->isSafe ? 'bg-success' : ($c->hasUrl ? 'bg-error' : 'bg-warning') }}"></span> {{ $c->status }}
                                        </dd>
                                    </div>

                                    {{-- Connection security --}}
                                    <div class="min-w-0">
                                        <dt class="text-xs text-base-content/50">Connection security</dt>
                                        <dd class="mt-1 text-sm">
                                            @if($c->scheme === 'https')
                                                <span class="text-success">
                                                    <i class="fa-solid fa-lock mr-1" aria-hidden="true"></i> HTTPS
                                                </span>
                                            @elseif($c->scheme === 'http')
                                                <span class="text-warning">
                                                    <i class="fa-solid fa-lock-open mr-1" aria-hidden="true"></i> HTTP — not encrypted
                                                </span>
                                            @else
                                                <span class="text-base-content/50">Not available</span>
                                            @endif
                                        </dd>
                                    </div>

                                    {{-- Linked date --}}
                                    <div class="min-w-0">
                                        <dt class="text-xs text-base-content/50">Connected</dt>
                                        <dd class="mt-1 text-sm"
                                            @if($c->linkedAt) title="{{ $c->linkedAt->toDayDateTimeString() }}" @endif>
                                            @if($c->linkedAt)
                                                {{ $c->linkedAt->diffForHumans() }}
                                            @else
                                                <span class="text-base-content/50">Date not recorded</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>

                                {{-- Actions --}}
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @if($c->isSafe)
                                        <a href="{{ $c->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm max-w-full rounded-xl">
                                            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                            <span>Open account</span>
                                        </a>
                                    @endif

                                    <a href="{{ $c->route }}" class="btn btn-ghost btn-sm rounded-xl">
                                        <i class="fa-solid fa-globe" aria-hidden="true"></i> View platform
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- No search results --}}
        <div class="hidden rounded-2xl border border-dashed border-base-300 px-5 py-10 text-center"
             data-cv-empty>
            <div class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-base-200">
                <i class="fa-solid fa-magnifying-glass text-xl text-base-content/35" aria-hidden="true"></i>
            </div>
            <h3 class="mt-3 font-bold">No connections found</h3>
            <p class="mt-1 break-words text-sm text-base-content/50">
                Nothing matches <span class="font-semibold" data-cv-empty-q></span>. Try another search or clear the filters.
            </p>
            <button type="button" class="btn btn-outline btn-sm mt-4 rounded-xl" data-cv-reset>Clear filters</button>
        </div>

    @else
        {{-- Initial empty state --}}
        <div class="rounded-2xl border border-dashed border-base-300 px-5 py-10 text-center">
            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200 text-base-content/40">
                <i class="fa-solid fa-share-nodes text-2xl" aria-hidden="true"></i>
            </div>
            <h3 class="mt-4 font-bold">No connections yet</h3>
            <p class="mx-auto mt-1 max-w-sm text-sm leading-6 text-base-content/50">Add official accounts associated with {{ $platform->name }} to display them here.</p>
            <a href="{{ $manageRoute }}" class="btn btn-primary btn-sm mt-4 rounded-xl">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add connection
            </a>
        </div>
    @endif
</section>