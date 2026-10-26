
{{-- Platform Pages --}}
@php
    $activePages = $platform->platformPages->where('is_active', true)->values();

    $pageCount = $activePages->count();
    $validPages = 0;

    $pages = $activePages->map(function ($page, $i) use (&$validPages, $platform) {
        $url = $page->url;
        $hasUrl = filled($url);
        $scheme = $hasUrl ? strtolower((string) parse_url($url, PHP_URL_SCHEME)) : null;
        $safeUrl = $hasUrl && in_array($scheme, ['http', 'https'], true);
        $host = $safeUrl ? (parse_url($url, PHP_URL_HOST) ?: $url) : null;

        if ($safeUrl) {
            $validPages++;
        }

        $name = trim((string) $page->name);
        $initials = collect(preg_split('/\s+/', $name) ?: [])
            ->filter()
            ->take(2)
            ->map(fn ($word) => mb_substr($word, 0, 1))
            ->implode('');

        $linkedAt = $page->created_at ? \Illuminate\Support\Carbon::parse($page->created_at) : null;

        $status = $safeUrl ? 'Valid URL' : ($hasUrl ? 'Invalid URL' : 'No URL recorded');

        $search = mb_strtolower(implode(' ', array_filter([
            $name, $page->page_type, $page->short_desc, $host, $url, $status,
        ])));

        return (object) [
            'id' => 'cv-page-'.$i,
            'index' => $i,
            'model' => $page,
            'name' => $name,
            'url' => $url,
            'hasUrl' => $hasUrl,
            'safeUrl' => $safeUrl,
            'scheme' => $scheme,
            'host' => $host,
            'initials' => $initials ?: 'PG',
            'hue' => crc32($name) % 360,
            'type' => $page->page_type,
            'description' => $page->short_desc,
            'status' => $status,
            'state' => $safeUrl ? 'valid' : ($hasUrl ? 'invalid' : 'none'),
            'linkedAt' => $linkedAt,
            'search' => $search,
            'detailRoute' => route('admin.platform-pages.index', ['platform' => $platform->slug]),
        ];
    });

    $attentionCount = $pageCount - $validPages;
    $validPercentage = $pageCount ? round($validPages / $pageCount * 100) : 0;
    $hasDates = $pages->contains(fn ($page) => $page->linkedAt);
    $manageRoute = route('admin.platform-pages.index', ['platform' => $platform->slug]);
@endphp

<section class="rounded-3xl border border-base-300 bg-base-100 p-4 shadow-sm sm:p-6" data-linked-companies>
    {{-- Header --}}
    <div class="mb-5">
        <div class="flex items-start justify-between gap-3">
            <div class="flex min-w-0 items-start gap-3">
                <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                    <i class="fa-solid fa-file-lines text-lg" aria-hidden="true"></i>
                </div>

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-lg font-black tracking-tight sm:text-xl">Platform Pages</h2>
                        <span class="badge badge-ghost badge-sm">{{ $pageCount }} active</span>
                    </div>
                    <p class="mt-1 text-sm text-base-content/50">Official pages, social profiles, and resources associated with this platform.</p>
                </div>
            </div>

            <a href="{{ $manageRoute }}" class="btn btn-primary btn-sm shrink-0 gap-2 rounded-xl max-sm:btn-square" aria-label="Manage platform pages">
                <i class="fa-solid fa-sliders" aria-hidden="true"></i>
                <span class="hidden sm:inline">Manage</span>
            </a>
        </div>

        @if($pageCount > 0)
            {{-- Summary --}}
            <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-base-300 bg-base-200/30 p-3">
                    <p class="text-xs text-base-content/55">Active pages</p>
                    <p class="mt-1 text-2xl font-black tabular-nums">{{ $pageCount }}</p>
                </div>

                <div class="rounded-2xl border border-success/20 bg-success/5 p-3">
                    <p class="text-xs text-base-content/55">Valid URLs</p>
                    <p class="mt-1 text-2xl font-black tabular-nums text-success">{{ $validPages }}</p>
                </div>

                <div class="col-span-2 rounded-2xl border border-base-300 bg-base-200/30 p-3 sm:col-span-1">
                    <p class="text-xs text-base-content/55">Needs attention</p>
                    <p class="mt-1 text-2xl font-black tabular-nums {{ $attentionCount ? 'text-warning' : 'text-base-content' }}"> {{ $attentionCount }} </p>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <div class="flex h-1.5 flex-1 overflow-hidden rounded-full bg-base-200" role="img" aria-label="{{ $validPages }} of {{ $pageCount }} pages have valid URLs">
                    <span class="rounded-full bg-success transition-[width] duration-300 motion-reduce:transition-none" style="width: {{ $validPercentage }}%"></span>
                </div>
                <span class="shrink-0 text-xs text-base-content/55">{{ $validPercentage }}% valid</span>
            </div>
        @endif
    </div>

    @if($pageCount > 0)
        {{-- Search, sorting, and expansion controls --}}
        <div class="mb-4 space-y-3">
            <div class="flex flex-col gap-2 sm:flex-row">
                <label class="input input-bordered flex h-11 min-w-0 flex-1 items-center gap-2 rounded-xl">
                    <i class="fa-solid fa-magnifying-glass text-base-content/40" aria-hidden="true"></i>
                    <input type="search" class="grow" placeholder="Search pages, types, domains..." aria-label="Search platform pages" autocomplete="off" data-cv-search>

                    <button type="button" class="btn btn-ghost btn-circle btn-xs hidden" aria-label="Clear search" data-cv-clear>
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                    <kbd class="kbd kbd-sm hidden sm:inline-flex">/</kbd>
                </label>

                @if($pageCount > 1)
                    <select class="select select-bordered h-11 min-h-0 w-full rounded-xl sm:w-auto" aria-label="Sort platform pages" data-cv-sort>
                        <option value="default">Default order</option>
                        <option value="az">Name A–Z</option>
                        <option value="za">Name Z–A</option>

                        @if($hasDates)
                            <option value="recent">Recently added</option>
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
            <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filter pages by URL status">
                @foreach([
                    ['all', 'All pages', $pageCount],
                    ['valid', 'Valid URL', $validPages],
                    ['attention', 'Needs attention', $attentionCount],
                ] as [$key, $label, $count])
                    @continue($key === 'attention' && $attentionCount === 0)

                    <button type="button" data-cv-chip="{{ $key }}" aria-pressed="{{ $key === 'all' ? 'true' : 'false' }}" class="btn btn-xs h-8 rounded-full border-base-300 bg-base-100 font-semibold aria-pressed:border-base-content aria-pressed:bg-base-content aria-pressed:text-base-100">
                        {{ $label }}
                        <span class="opacity-60">{{ $count }}</span>
                    </button>
                @endforeach

                <span class="ml-auto text-xs text-base-content/50" role="status" aria-live="polite" data-cv-count></span>
            </div>
        </div>

        {{-- Expandable page cards --}}
        <ul class="grid grid-cols-1 items-start gap-3 sm:grid-cols-2"
            role="list"
            data-cv-list>

            @foreach($pages as $p)
                <li class="group min-w-0 rounded-2xl border border-base-300 bg-base-100 transition-[border-color,background-color] duration-200 hover:border-base-content/25 data-[open=true]:border-primary/40 data-[open=true]:bg-base-200/30 sm:data-[open=true]:col-span-2 motion-reduce:transition-none" data-cv-row data-open="false" data-index="{{ $p->index }}" data-name="{{ mb_strtolower($p->name) }}" data-state="{{ $p->state }}" data-linked="{{ $p->linkedAt?->timestamp ?? 0 }}" data-search="{{ $p->search }}">
                    <h3>
                        <button type="button" id="{{ $p->id }}-btn" aria-expanded="false" aria-controls="{{ $p->id }}-body" data-cv-btn class="flex w-full min-w-0 items-center gap-3 rounded-2xl p-3 text-left outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary sm:p-4">
                            {{-- Page logo or fallback --}}
                            <span class="relative flex size-12 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-base-300/70 bg-base-200/60">
                                @if(filled($p->model->logo))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($p->model->logo) }}" alt="" loading="lazy" class="max-h-9 max-w-9 object-contain">
                                @else
                                    <span class="flex size-full items-center justify-center rounded-xl bg-primary/10 px-1 text-center text-xs font-black text-primary"> {{ $p->initials }} </span>
                                @endif

                                <span class="absolute bottom-0.5 right-0.5 size-2.5 rounded-full ring-2 ring-base-100 {{ $p->safeUrl ? 'bg-success' : ($p->hasUrl ? 'bg-error' : 'bg-warning') }}" role="img" aria-label="{{ $p->status }}"></span>
                            </span>

                            {{-- Name, type, and domain --}}
                            <span class="min-w-0 flex-1">
                                <span class="block break-words text-sm font-bold [overflow-wrap:anywhere]"> {{ $p->name }} </span>

                                <span class="mt-1 flex min-w-0 flex-wrap items-center gap-1.5">
                                    @if($p->type)
                                        <span class="max-w-full truncate rounded-full bg-base-200 px-2 py-0.5 text-[10px] font-semibold"> {{ $p->type }} </span>
                                    @endif

                                    <span class="min-w-0 break-all text-xs {{ $p->safeUrl ? 'text-base-content/55' : ($p->hasUrl ? 'text-error' : 'text-warning') }} [overflow-wrap:anywhere]"> {{ $p->host ?? $p->status }} </span>
                                </span>
                            </span>

                            <i class="fa-solid fa-chevron-down shrink-0 text-xs text-base-content/45 transition-transform duration-300 group-data-[open=true]:rotate-180 motion-reduce:transition-none"
                               aria-hidden="true"></i>
                        </button>
                    </h3>

                    {{-- Expanded details --}}
                    <div id="{{ $p->id }}-body" role="region" aria-labelledby="{{ $p->id }}-btn" inert class="grid grid-rows-[0fr] transition-[grid-template-rows] duration-300 ease-out group-data-[open=true]:grid-rows-[1fr] motion-reduce:transition-none">
                        <div class="overflow-hidden">
                            <div class="border-t border-base-300/70 px-4 pb-4 pt-4 sm:px-5">
                                <dl class="grid min-w-0 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                    {{-- Page name --}}
                                    <div class="min-w-0">
                                        <dt class="text-xs font-medium text-base-content/50">Page name</dt>
                                        <dd class="mt-1 break-words text-sm font-semibold [overflow-wrap:anywhere]"> {{ $p->name }} </dd>
                                    </div>

                                    {{-- Page type --}}
                                    <div class="min-w-0">
                                        <dt class="text-xs font-medium text-base-content/50">Page type</dt>
                                        <dd class="mt-1 break-words text-sm"> {{ $p->type ?: 'Not specified' }} </dd>
                                    </div>

                                    {{-- URL status --}}
                                    <div class="min-w-0">
                                        <dt class="text-xs font-medium text-base-content/50">URL status</dt>
                                        <dd class="mt-1 flex items-center gap-2 text-sm font-medium">
                                            <span class="size-2 shrink-0 rounded-full {{ $p->safeUrl ? 'bg-success' : ($p->hasUrl ? 'bg-error' : 'bg-warning') }}"></span> {{ $p->status }} 
                                        </dd>
                                    </div>

                                    {{-- URL --}}
                                    <div class="min-w-0 sm:col-span-2 lg:col-span-3">
                                        <dt class="text-xs font-medium text-base-content/50">Official page URL</dt>
                                        <dd class="mt-1 min-w-0">
                                            @if($p->safeUrl)
                                                <a href="{{ $p->url }}" target="_blank" rel="noopener noreferrer" class="block break-all text-sm font-medium text-primary hover:underline [overflow-wrap:anywhere]"> {{ $p->url }} </a>
                                            @elseif($p->hasUrl)
                                                <p class="break-words text-sm text-error [overflow-wrap:anywhere]">This URL cannot be opened because it does not use HTTP or HTTPS.</p>
                                            @else
                                                <p class="text-sm text-base-content/50">No page URL has been recorded.</p>
                                            @endif
                                        </dd>
                                    </div>

                                    {{-- Description --}}
                                    <div class="min-w-0 sm:col-span-2 lg:col-span-3">
                                        <dt class="text-xs font-medium text-base-content/50">Description</dt>
                                        <dd class="mt-1 whitespace-pre-line break-words text-sm leading-6 text-base-content/75 [overflow-wrap:anywhere]"> {{ $p->description ?: 'No description available.' }} </dd>
                                    </div>

                                    {{-- Added date --}}
                                    <div class="min-w-0">
                                        <dt class="text-xs font-medium text-base-content/50">Added</dt>
                                        <dd class="mt-1 text-sm"
                                            @if($p->linkedAt) title="{{ $p->linkedAt->toDayDateTimeString() }}" @endif>
                                            @if($p->linkedAt)
                                                {{ $p->linkedAt->diffForHumans() }}
                                            @else
                                                <span class="text-base-content/50">Date not recorded</span>
                                            @endif
                                        </dd>
                                    </div>

                                    {{-- Security --}}
                                    <div class="min-w-0">
                                        <dt class="text-xs font-medium text-base-content/50">Connection security</dt>
                                        <dd class="mt-1 text-sm">
                                            @if($p->scheme === 'https')
                                                <span class="text-success">
                                                    <i class="fa-solid fa-lock mr-1" aria-hidden="true"></i> HTTPS
                                                </span>
                                            @elseif($p->scheme === 'http')
                                                <span class="text-warning">
                                                    <i class="fa-solid fa-lock-open mr-1" aria-hidden="true"></i> HTTP — not encrypted
                                                </span>
                                            @else
                                                <span class="text-base-content/50">Not available</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>

                                {{-- Actions --}}
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @if($p->safeUrl)
                                        <a href="{{ $p->url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm max-w-full rounded-xl">
                                            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                            <span>Open page</span>
                                        </a>
                                    @endif

                                    <a href="{{ $manageRoute }}" class="btn btn-ghost btn-sm rounded-xl">
                                        <i class="fa-solid fa-sliders" aria-hidden="true"></i> Manage pages
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        {{-- Search/filter empty state --}}
        <div class="hidden rounded-2xl border border-dashed border-base-300 px-5 py-10 text-center" data-cv-empty>
            <div class="mx-auto flex size-12 items-center justify-center rounded-2xl bg-base-200">
                <i class="fa-solid fa-magnifying-glass text-xl text-base-content/35" aria-hidden="true"></i>
            </div>

            <h3 class="mt-3 font-bold">No pages found</h3>
            <p class="mt-1 break-words text-sm text-base-content/50">
                Nothing matches <span class="font-semibold" data-cv-empty-q></span>. Try another search or clear the filters.
            </p>

            <button type="button" class="btn btn-outline btn-sm mt-4 rounded-xl" data-cv-reset>Clear filters</button>
        </div>
    @else
        {{-- Initial empty state --}}
        <div class="rounded-2xl border border-dashed border-base-300 px-5 py-10 text-center">
            <div class="mx-auto flex size-14 items-center justify-center rounded-2xl bg-base-200 text-base-content/40">
                <i class="fa-solid fa-file-circle-plus text-2xl" aria-hidden="true"></i>
            </div>

            <h3 class="mt-4 font-bold">No active platform pages</h3>
            <p class="mx-auto mt-1 max-w-sm text-sm leading-6 text-base-content/55">Add official pages, social profiles, or useful resources to make them accessible from this platform.</p>

            <a href="{{ route('admin.platform-pages.create', ['platform' => $platform->slug]) }}"
               class="btn btn-primary btn-sm mt-4 gap-2 rounded-xl">
                <i class="fa-solid fa-plus" aria-hidden="true"></i> Add first page
            </a>
        </div>
    @endif
</section>