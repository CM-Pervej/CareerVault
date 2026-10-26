@extends('layouts.admin.app')

@section('title','Platform Connections')

@section('content')
<style>
    /* Connection icon popovers */
    .connection-item{position:relative;}
    .connection-popover{display:none;padding-top:8px;}
    .connection-item.is-open>.connection-popover{display:block;}
    .connection-popover>div{pointer-events:auto;}
    @media (hover:hover) and (pointer:fine){
        .connection-item:hover>.connection-popover,
        .connection-item:focus-within>.connection-popover{display:block;}
    }
    /* Keep popovers above neighboring rows without changing the desktop layout. */
    .connection-item{z-index:1;}
    .connection-item:has(.connection-popover:hover),
    .connection-item:has(.connection-trigger:focus-visible){z-index:50;}
</style>

<div class="space-y-3 p-4 sm:p-6">
    {{-- ========================= HEADER =========================== --}}
    <x-admin.ui.page-header
        icon="fa-brands fa-connectdevelop text-lg"    
        title="Platform Connections"
        title-color="text-primary"
        title-bg-color="bg-primary/10 text-primary"
        description="Manage official cross-platform presence across CareerVault."
        :create-route="route('admin.platform-connections.create')"
        create-text="Add Connection"
    />

    {{-- ===================== OVERVIEW STATS ======================= --}}
    <details class="cv-section overflow-hidden sm:rounded-lg border border-base-300 bg-base-100 shadow-sm" data-section="pageStates" open>
        <x-admin.ui.disclosure-summary 
            title="Statistics"
            icon="fa-solid fa-chart-simple"
            description="Overview of platform pages and their current status."
        />

        {{-- ===================== OVERVIEW STATS ====================== --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-1 sm:p-1 bg-[radial-gradient(circle_at_center,_theme(colors.gray.200),_theme(colors.gray.100))]">
            {{-- Total --}}
            <x-admin.ui.statistic
                title="Platforms"
                title-icon="fa-solid fa-layer-group"
                main-icon="fa-solid fa-globe"
                main-icon-color="bg-info/10 text-info"
                :total="$totalPlatforms"
                total-description="Total directory platforms"
                last-icon="fa-solid fa-database"
            />

            {{-- Connected --}}
            <x-admin.ui.statistic
                title="Connected"
                title-icon="fa-solid fa-link"
                main-icon="fa-solid fa-link"
                main-icon-color="bg-success/10 text-success"
                :total="$platformsWithConnections"
                total-description="Platforms with official presence"
                last-icon="fa-solid fa-circle-check"
            />

            {{-- Connections --}}
            <x-admin.ui.statistic
                title="Connections"
                title-icon="fa-solid fa-arrows-left-right"
                main-icon="fa-solid fa-share-nodes"
                main-icon-color="bg-warning/10 text-warning"
                :total="$totalConnections"
                total-description="Official cross-platform accounts"
                last-icon="fa-solid fa-building-columns"
            />

            {{-- Status --}}
            <div class="card border border-base-300 bg-base-100 shadow-sm">
                <div class="card-body p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-base-content/50">
                                <i class="fa-solid fa-signal"></i> Directory Status
                            </div>

                            <div class="mt-2 flex items-center gap-3">
                                <span class="text-2xl font-black"> {{ number_format($activePlatforms) }} </span>
                                <span class="text-xs font-semibold text-success">Active</span>
                            </div>
                        </div>

                        <div class="flex size-10 items-center justify-center rounded-lg bg-warning/10 text-warning">
                            <i class="fa-solid fa-toggle-on"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 text-xs text-base-content/50">
                        <i class="fa-solid fa-circle-xmark"></i>
                        {{ number_format($inactivePlatforms) }} inactive platforms
                    </div>
                </div>
            </div>
        </div>
    </details>

    {{-- ========================= FILTERS ========================= --}}
    <x-admin.filters.panel
        title="Filter Connections"
        description="Narrow the list by platform, connection status, or activity"
        section="connectionFilters"
        action="{{ route('admin.platform-connections.index') }}"
        clear-url="{{ route('admin.platform-connections.index') }}"
        result-label="platform"
        result-label-plural="platforms"
        summary="Official platform accounts and connections"
        gridClass="grid grid-cols-2 gap-4 sm:grid-cols-5"

        :count="$platforms->count()"
        :total="$totalPlatforms"

        :fields="[
            [
                'type' => 'search',
                'name' => 'search',
                'label' => 'Search',
                'icon' => 'fa-brands fa-searchengin',
                'placeholder' => 'Search platform name or slug...',
                'value' => $search,
                'span' => 'col-span-2',
            ],
            [
                'type' => 'select',
                'name' => 'connection_status',
                'label' => 'Connection',
                'icon' => 'fa-solid fa-link',
                'value' => $connectionStatus,
                'options' => [
                    'all' => 'All platforms',
                    'connected' => 'Connected',
                    'unconnected' => 'No connections',
                ],
            ],
            [
                'type' => 'select',
                'name' => 'activity_status',
                'label' => 'Status',
                'icon' => 'fa-solid fa-power-off',
                'value' => $activityStatus,
                'options' => [
                    'all' => 'All statuses',
                    'active' => 'Active only',
                    'inactive' => 'Inactive only',
                ],
            ],
            [
                'type' => 'select',
                'name' => 'sort',
                'label' => 'Sort',
                'icon' => 'fa-solid fa-arrow-down-a-z',
                'value' => $sort,
                'options' => [
                    'name_asc' => 'Name A–Z',
                    'name_desc' => 'Name Z–A',
                    'connections_desc' => 'Most connections',
                    'connections_asc' => 'Least connections',
                ],
            ],
        ]"

        :active-filters="[
            [
                'show' => filled($search),
                'icon' => 'fa-solid fa-magnifying-glass',
                'label' => 'Search',
                'value' => $search,
                'color' => 'primary',
            ],
            [
                'show' => $connectionStatus !== 'all',
                'icon' => 'fa-solid fa-link',
                'label' => 'Connection',
                'value' => $connectionStatus === 'connected'
                    ? 'Connected'
                    : 'No connections',
                'color' => 'info',
            ],
            [
                'show' => $activityStatus !== 'all',
                'icon' => 'fa-solid fa-power-off',
                'label' => 'Status',
                'value' => ucfirst($activityStatus),
                'color' => 'warning',
            ],
        ]"
    />

    {{-- =================== PLATFORM DIRECTOR ====================== --}}
    <div class="rounded-xl border border-base-300 bg-base-100 shadow-sm">
        {{-- Directory header --}}
        <div class="flex flex-col gap-3 border-b border-base-300 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 font-black">
                    <i class="fa-solid fa-diagram-project text-primary"></i> Platform Directory
                </div>

                <p class="mt-1 text-xs text-base-content/50">Each platform can have official accounts or profiles on other platforms.</p>
            </div>

            <div class="badge badge-outline gap-1.5">
                <i class="fa-solid fa-link"></i>
                {{ number_format($totalConnections) }}
                {{ Str::plural('connection',$totalConnections) }}
            </div>
        </div>

        {{-- Platform rows --}}
        <div class="divide-y divide-base-300">
            @forelse($platforms as $platform)
                <div class="group relative p-4 transition hover:bg-base-200/30 sm:p-5">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center">
                        {{-- ==================== PLATFORM IDENTITY ======================= --}}
                        <div class="flex min-w-0 items-center gap-4 xl:w-80">
                            <div class="relative flex size-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl border border-base-300 bg-base-200 shadow-sm"
                                @if($platform->color)
                                    style="color: {{ $platform->color }}"
                                @endif>

                                @if($platform->logo)
                                    <img src="{{ Storage::url($platform->logo) }}" alt="{{ $platform->name }}" class="size-full object-contain p-2">
                                @elseif($platform->icon)
                                    <i class="{{ $platform->icon }} text-xl"></i>
                                @else
                                    <span class="text-xl font-black"> {{ Str::upper(Str::substr($platform->name,0,1)) }} </span>
                                @endif

                                {{-- Activity indicator --}}
                                <span class="absolute bottom-1 right-1 size-2.5 rounded-full border-2 border-base-100 {{ $platform->is_active ? 'bg-success' : 'bg-warning' }}" title="{{ $platform->is_active ? 'Active' : 'Inactive' }}"></span>
                            </div>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="truncate font-black"> {{ $platform->name }} </span>
                                    @if($platform->is_active)
                                        <span class="tooltip" data-tip="Active platform">
                                            <i class="fa-solid fa-circle-check text-xs text-success"></i>
                                        </span>
                                    @else
                                        <span class="tooltip" data-tip="Inactive platform">
                                            <i class="fa-solid fa-circle-exclamation text-xs text-warning"></i>
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-0.5 flex items-center gap-1.5 truncate text-xs text-base-content/45">
                                    <i class="fa-solid fa-link text-[9px]"></i> {{ $platform->slug }}
                                </div>

                                <div class="mt-2 flex items-center gap-2">
                                    @if($platform->connected_platforms_count > 0)
                                        <span class="badge badge-success badge-sm gap-1">
                                            <i class="fa-solid fa-link"></i> {{ $platform->connected_platforms_count }}
                                        </span>
                                        <span class="text-[11px] text-base-content/45">
                                            {{ Str::plural('connection',$platform->connected_platforms_count) }}
                                        </span>
                                    @else
                                        <span class="badge badge-ghost badge-sm gap-1">
                                            <i class="fa-solid fa-link-slash"></i> None
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ======================= CONNECTION MAP ======================== --}}
                        <div class="min-w-0 flex-1">
                            @if($platform->connectedPlatforms->isNotEmpty())
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-wider text-base-content/50">
                                        <i class="fa-solid fa-share-nodes"></i> Official presence
                                    </div>
                                    <span class="badge badge-ghost badge-sm"> {{ $platform->connectedPlatforms->count() }} </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    @foreach($platform->connectedPlatforms as $connectedPlatform)
                                        @php
                                            $accountUrl = $connectedPlatform->pivot->account_url ?? null;
                                            $connectionId = 'connection-'.$platform->id.'-'.$connectedPlatform->id;
                                        @endphp

                                        <div class="connection-item relative">
                                            <button type="button" class="connection-trigger flex size-11 items-center justify-center rounded-xl border border-base-300 bg-base-100 shadow-sm transition hover:border-primary/50 hover:bg-base-200 hover:shadow-md focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary" aria-label="{{ $connectedPlatform->name }} details" aria-expanded="false" aria-controls="{{ $connectionId }}">
                                                @if($connectedPlatform->logo)
                                                    <img src="{{ Storage::url($connectedPlatform->logo) }}" alt="" loading="lazy" class="size-7 object-contain">
                                                @elseif($connectedPlatform->icon)
                                                    <i class="{{ $connectedPlatform->icon }} text-lg"
                                                        @if($connectedPlatform->color)
                                                            style="color:{{ $connectedPlatform->color }}"
                                                        @endif
                                                        aria-hidden="true"
                                                    ></i>
                                                @else
                                                    <span class="text-sm font-black"> {{ Str::upper(Str::substr($connectedPlatform->name,0,1)) }} </span>
                                                @endif
                                            </button>

                                            <div id="{{ $connectionId }}" class="connection-popover absolute left-0 top-full z-40 hidden w-64 pt-2 sm:left-1/2 sm:-translate-x-1/2" role="region" aria-label="{{ $connectedPlatform->name }} details">
                                                <div class="rounded-2xl border border-base-300 bg-base-100 p-4 shadow-xl">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex size-11 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-base-300 bg-base-200">
                                                            @if($connectedPlatform->logo)
                                                                <img src="{{ Storage::url($connectedPlatform->logo) }}" alt="" class="size-full object-contain p-1">
                                                            @elseif($connectedPlatform->icon)
                                                                <i class="{{ $connectedPlatform->icon }} text-lg"
                                                                    @if($connectedPlatform->color)
                                                                        style="color:{{ $connectedPlatform->color }}"
                                                                    @endif
                                                                ></i>
                                                            @else
                                                                <span class="font-black">{{ Str::upper(Str::substr($connectedPlatform->name,0,1)) }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div class="truncate text-sm font-black">{{ $connectedPlatform->name }}</div>
                                                            <div class="mt-1 flex items-center gap-1.5 text-xs text-base-content/60">
                                                                <span class="size-2 rounded-full {{ $connectedPlatform->is_active ? 'bg-success' : 'bg-warning' }}"></span>
                                                                {{ $connectedPlatform->is_active ? 'Active platform' : 'Inactive platform' }}
                                                            </div>
                                                        </div>
                                                        <button type="button" class="connection-close btn btn-ghost btn-xs btn-square" aria-label="Close details">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </div>

                                                    @if($connectedPlatform->slug)
                                                        <p class="mt-3 truncate text-xs text-base-content/50">
                                                            <i class="fa-solid fa-at mr-1"></i>{{ $connectedPlatform->slug }}
                                                        </p>
                                                    @endif

                                                    @if($accountUrl)
                                                        <a href="{{ $accountUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm mt-3 w-full gap-2">
                                                            Visit official profile <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                                        </a>
                                                    @else
                                                        <p class="mt-3 text-xs text-base-content/50">No official profile URL configured.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <p class="mt-2 text-[11px] text-base-content/40">
                                    <i class="fa-solid fa-circle-info mr-1"></i> Hover over an icon or tap it to see details.
                                </p>
                            @else
                                <div class="flex items-center gap-3 rounded-xl border border-dashed border-base-300 bg-base-200/20 px-4 py-3">
                                    <div class="flex size-9 shrink-0 items-center justify-center rounded-lg bg-base-200 text-base-content/35">
                                        <i class="fa-solid fa-link-slash"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-base-content/60">No connections configured</div>
                                        <div class="mt-0.5 text-[11px] text-base-content/40">Add the platform's official presence elsewhere.</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- ====================== ACTIONS ======================= --}}
                        <div class="w-full shrink-0 xl:w-32">
                            {{-- Mobile: show all actions without the dropdown --}}
                            <div class="grid grid-cols-2 gap-2 xl:hidden">
                                <a href="{{ route('admin.platform-connections.edit',$platform) }}" class="btn btn-sm {{ $platform->connected_platforms_count ? 'btn-outline' : 'btn-primary' }}">
                                    @if($platform->connected_platforms_count)
                                        <i class="fa-solid fa-pen-to-square"></i> Manage
                                    @else
                                        <i class="fa-solid fa-plus"></i> Add
                                    @endif
                                </a>
                                <a href="{{ route('admin.platforms.index',['search'=>$platform->name]) }}" class="btn btn-sm btn-ghost border border-base-300">
                                    <i class="fa-solid fa-layer-group"></i> View platform
                                </a>
                                @if($platform->connected_platforms_count)
                                    <a href="{{ route('admin.platform-connections.edit',$platform) }}" class="btn btn-sm btn-ghost border border-base-300 col-span-2">
                                        <i class="fa-solid fa-list-check"></i> Review connections
                                    </a>
                                @endif
                            </div>

                            {{-- Desktop: preserve the existing compact actions and dropdown --}}
                            <div class="hidden items-center gap-2 xl:flex">
                                <a href="{{ route('admin.platform-connections.edit',$platform) }}" class="btn btn-sm gap-2 {{ $platform->connected_platforms_count ? 'btn-outline' : 'btn-primary' }}">
                                    @if($platform->connected_platforms_count)
                                        <i class="fa-solid fa-pen-to-square"></i> Manage
                                    @else
                                        <i class="fa-solid fa-plus"></i> Add
                                    @endif
                                </a>
                                <div class="dropdown dropdown-end">
                                    <button type="button" tabindex="0" class="btn btn-sm btn-ghost btn-square" aria-label="More platform actions">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul tabindex="0" class="dropdown-content menu z-30 mt-2 w-52 rounded-box border border-base-300 bg-base-100 p-2 shadow-xl">
                                        <li><a href="{{ route('admin.platform-connections.edit',$platform) }}"><i class="fa-solid fa-pen-to-square"></i> Manage connections</a></li>
                                        <li><a href="{{ route('admin.platforms.index',['search'=>$platform->name]) }}"><i class="fa-solid fa-layer-group"></i> View platform</a></li>
                                        @if($platform->connected_platforms_count)
                                            <li><a href="{{ route('admin.platform-connections.edit',$platform) }}"><i class="fa-solid fa-list-check"></i> Review connections</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @empty

                {{-- ======================= EMPTY STATE ========================= --}}
                <div class="px-6 py-20 text-center">
                    <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-base-200 text-base-content/30">
                        <i class="fa-solid fa-diagram-project text-2xl"></i>
                    </div>

                    @if($search || $connectionStatus !== 'all' || $activityStatus !== 'all')
                        <h3 class="mt-5 text-lg font-black">No matching platforms</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">No platforms match your current search and filter combination. Try changing your filters or clearing them.</p>

                        <a href="{{ route('admin.platform-connections.index') }}" class="btn btn-outline mt-5 gap-2">
                            <i class="fa-solid fa-rotate-left"></i> Clear Filters
                        </a>
                    @else
                        <h3 class="mt-5 text-lg font-black">No platforms available</h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-base-content/50">Add platforms to the directory before creating official cross-platform connections.</p>

                        <a href="{{ route('admin.platforms.index') }}" class="btn btn-primary mt-5 gap-2">
                            <i class="fa-solid fa-layer-group"></i> Platform Directory
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    {{-- ========================  FOOTER INFORMATION ============================== --}}
    <x-admin.ui.footer-info
        :file-name="$platforms"
        description="Platform pages represent <strong>official destinations and resources</strong> maintained in CareerVault."
    />
</div>
@push('script')
<script>
(function(){
    function initConnectionPopovers(){
        const items=[...document.querySelectorAll('.connection-item')];
        if(!items.length)return;

        function closeItem(item){
            item.classList.remove('is-open');
            item.querySelector('.connection-trigger')?.setAttribute('aria-expanded','false');
        }
        function closeAll(except=null){
            items.forEach(item=>{if(item!==except)closeItem(item);});
        }

        items.forEach(item=>{
            const trigger=item.querySelector('.connection-trigger');
            const close=item.querySelector('.connection-close');
            if(!trigger || trigger.dataset.popoverBound==='true')return;
            trigger.dataset.popoverBound='true';

            trigger.addEventListener('click',function(event){
                event.preventDefault();
                const wasOpen=item.classList.contains('is-open');
                closeAll(item);
                item.classList.toggle('is-open',!wasOpen);
                trigger.setAttribute('aria-expanded',String(!wasOpen));
            });
            close?.addEventListener('click',function(){closeItem(item);});
        });

        if(document.documentElement.dataset.connectionPopoverBound!=='true'){
            document.documentElement.dataset.connectionPopoverBound='true';
            document.addEventListener('click',function(event){
                if(!event.target.closest('.connection-item'))closeAll();
            });
            document.addEventListener('keydown',function(event){
                if(event.key==='Escape'){
                    closeAll();
                    if(document.activeElement?.matches('.connection-trigger'))document.activeElement.blur();
                }
            });
        }
    }
    if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',initConnectionPopovers);
    else initConnectionPopovers();
})();
</script>
@endpush
@endsection