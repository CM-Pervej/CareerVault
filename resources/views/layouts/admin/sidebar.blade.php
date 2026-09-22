@php
    /*
    |--------------------------------------------------------------------------
    | Active Navigation States
    |--------------------------------------------------------------------------
    */

    $platformsActive = request()->routeIs(
        'admin.platforms.*',
        'admin.platform-connections.*',
        'admin.platform-pages.*',
        'admin.platform-groups.*',
        'admin.platform-communities.*'
    );

    $referenceDataActive = request()->routeIs(
        'admin.countries.*',
        'admin.cities.*',
        'admin.industries.*'
    );

    $peopleActive = request()->routeIs('admin.users.*');

    $systemActive = request()->routeIs(
        'admin.activity-logs.*',
        'admin.settings.*'
    );

    $env = app()->environment();
@endphp

<aside
    class="cv-admin-sidebar cv-admin-scrollbar fixed inset-y-0 left-0 z-50 flex flex-col overflow-y-auto bg-[var(--cv-navy)] text-white/70 -translate-x-full transition-transform duration-200 lg:translate-x-0"
    :class="{'translate-x-0': sidebarOpen}"
>
    {{-- Brand --}}
    <div class="flex h-20 shrink-0 items-center gap-3 border-b border-white/[0.06] px-5">
        <a href="{{ route('admin.dashboard') }}" class="flex min-w-0 flex-1 items-center gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-[var(--cv-brass)]/40 bg-white/[0.03] text-[var(--cv-brass)]">
                <svg viewBox="0 0 40 40" class="h-6 w-6">
                    <circle cx="20" cy="20" r="16.5" fill="none" stroke="currentColor" stroke-width="1.3" opacity=".5"/>
                    <circle cx="20" cy="20" r="10.5" fill="none" stroke="currentColor" stroke-width="1.3"/>
                    <circle cx="20" cy="20" r="2.2" fill="currentColor"/>
                    <line x1="20" y1="2.5" x2="20" y2="6.5" stroke="currentColor" stroke-width="1.5"/>
                    <line x1="20" y1="33.5" x2="20" y2="37.5" stroke="currentColor" stroke-width="1.5"/>
                    <line x1="2.5" y1="20" x2="6.5" y2="20" stroke="currentColor" stroke-width="1.5"/>
                    <line x1="33.5" y1="20" x2="37.5" y2="20" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            </span>

            <div class="min-w-0">
                <div class="cv-admin-title truncate text-lg text-white">CareerVault</div>
                <div class="cv-admin-label mt-0.5 text-[var(--cv-brass)]/70">Administration</div>
            </div>
        </a>

        <button type="button" @click="sidebarOpen=false" class="btn btn-ghost btn-sm text-white/60 hover:bg-white/10 hover:text-white lg:hidden" aria-label="Close sidebar">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    @if($env !== 'production')
        <div class="mx-5 mt-4 flex items-center gap-2 rounded-md border border-amber-400/25 bg-amber-400/[0.07] px-3 py-1.5">
            <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-amber-400"></span>
            <span class="cv-admin-label text-amber-300/90">{{ ucfirst($env) }} environment</span>
        </div>
    @endif

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-5">
        {{-- ==========================================================
                                  Overview
        =========================================================== --}}
        <div class="mb-7">
            <div class="cv-admin-label mb-2 px-3 text-white/30">Overview</div>

            <a
                href="{{ route('admin.dashboard') }}"
                @if(request()->routeIs('admin.dashboard')) aria-current="page" @endif
                class="cv-nav-link {{ request()->routeIs('admin.dashboard') ? 'cv-nav-link--active' : '' }}"
            >
                <span class="flex w-5 justify-center"><i class="fa-solid fa-chart-pie text-xs"></i></span>
                <span>Dashboard</span>
            </a>
        </div>

        {{-- ==========================================================
                                 Directory
        =========================================================== --}}
        <div class="mb-7">
            <div class="cv-admin-label mb-2 px-3 text-white/30">Directory</div>

            {{-- ------------------------------------------------------
                                Platforms
            ------------------------------------------------------- --}}
            <div
                x-data="{ open: {{ $platformsActive ? 'true' : 'false' }}, pinned: {{ $platformsActive ? 'true' : 'false' }} }"
                class="mb-1"
                @mouseenter="open = true" @mouseleave="open = pinned" @focusin="open = true"
                @focusout="if (!$event.currentTarget.contains($event.relatedTarget)) open = pinned"
            >
                <div class="flex items-center rounded-lg {{ $platformsActive ? 'cv-nav-link--active' : '' }}">
                    <a href="{{ route('admin.platforms.index') }}" class="cv-nav-link min-w-0 flex-1 !rounded-r-none">
                        <span class="flex w-5 shrink-0 justify-center"><i class="fa-solid fa-layer-group text-xs"></i></span>
                        <span class="truncate">Platforms</span>
                    </a>

                    <button type="button" @click="pinned = !pinned; open = pinned" class="mr-1.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-white/35 transition hover:bg-white/10 hover:text-white" :aria-expanded="open" aria-label="Toggle platform navigation">
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                </div>

                <div class="cv-submenu"
                    x-cloak x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1">
                    <a href="{{ route('admin.platform-connections.index') }}" class="cv-subnav-link {{ request()->routeIs('admin.platform-connections.*') ? 'cv-subnav-link--active' : '' }}">
                        <span class="flex w-4 justify-center"><i class="fa-solid fa-link text-[11px]"></i></span>
                        <span>Connections</span>
                    </a>

                    @if(Route::has('admin.platform-pages.index'))
                        <a href="{{ route('admin.platform-pages.index') }}" class="cv-subnav-link {{ request()->routeIs('admin.platform-pages.*') ? 'cv-subnav-link--active' : '' }}">
                            <span class="flex w-4 justify-center"><i class="fa-solid fa-file-lines text-[11px]"></i></span>
                            <span>Pages</span>
                        </a>
                    @endif

                    @if(Route::has('admin.platform-groups.index'))
                        <a href="{{ route('admin.platform-groups.index') }}" class="cv-subnav-link {{ request()->routeIs('admin.platform-groups.*') ? 'cv-subnav-link--active' : '' }}">
                            <span class="flex w-4 justify-center"><i class="fa-solid fa-user-group text-[11px]"></i></span>
                            <span>Groups</span>
                        </a>
                    @endif

                    @if(Route::has('admin.platform-communities.index'))
                        <a href="{{ route('admin.platform-communities.index') }}" class="cv-subnav-link {{ request()->routeIs('admin.platform-communities.*') ? 'cv-subnav-link--active' : '' }}">
                            <span class="flex w-4 justify-center"><i class="fa-solid fa-comments text-[11px]"></i></span>
                            <span>Communities</span>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Companies --}}
            <a href="#" class="cv-nav-link mb-1">
                <span class="flex w-5 justify-center"><i class="fa-solid fa-building text-xs"></i></span>
                <span>Companies</span>
            </a>

            {{-- Locations --}}
            <a href="#" class="cv-nav-link">
                <span class="flex w-5 justify-center"><i class="fa-solid fa-location-dot text-xs"></i></span>
                <span>Locations</span>
            </a>
        </div>

        {{-- ==========================================================
                                Reference Data
        =========================================================== --}}
        <div class="mb-7">
            <div class="cv-admin-label mb-2 px-3 text-white/30">Reference Data</div>

            <div
                x-data="{ open: {{ $referenceDataActive ? 'true' : 'false' }}, pinned: {{ $referenceDataActive ? 'true' : 'false' }} }"
                class="mb-1"
                @mouseenter="open = true" @mouseleave="open = pinned" @focusin="open = true"
                @focusout="if (!$event.currentTarget.contains($event.relatedTarget)) open = pinned"
            >
                <div class="flex items-center rounded-lg {{ $referenceDataActive ? 'cv-nav-link--active' : '' }}">
                    <button type="button" @click="pinned = !pinned; open = pinned" class="cv-nav-link min-w-0 flex-1 !rounded-r-none text-left">
                        <span class="flex w-5 shrink-0 justify-center"><i class="fa-solid fa-database text-xs"></i></span>
                        <span class="truncate">Reference Data</span>
                    </button>

                    <button type="button" @click="pinned = !pinned; open = pinned" class="mr-1.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-white/35 transition hover:bg-white/10 hover:text-white" :aria-expanded="open" aria-label="Toggle reference data navigation">
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                </div>

                <div class="cv-submenu"
                    x-cloak x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1">
                    <a href="#" class="cv-subnav-link {{ request()->routeIs('admin.countries.*') ? 'cv-subnav-link--active' : '' }}">
                        <span class="flex w-4 justify-center"><i class="fa-solid fa-earth-asia text-[11px]"></i></span>
                        <span>Countries</span>
                    </a>
                    <a href="#" class="cv-subnav-link {{ request()->routeIs('admin.cities.*') ? 'cv-subnav-link--active' : '' }}">
                        <span class="flex w-4 justify-center"><i class="fa-solid fa-city text-[11px]"></i></span>
                        <span>Cities</span>
                    </a>
                    <a href="#" class="cv-subnav-link {{ request()->routeIs('admin.industries.*') ? 'cv-subnav-link--active' : '' }}">
                        <span class="flex w-4 justify-center"><i class="fa-solid fa-tags text-[11px]"></i></span>
                        <span>Industries</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ==========================================================
                                  People
        =========================================================== --}}
        <div class="mb-7">
            <div class="cv-admin-label mb-2 px-3 text-white/30">People</div>

            <a href="{{ route('admin.users.index') }}" class="cv-nav-link {{ $peopleActive ? 'cv-nav-link--active' : '' }}">
                <span class="flex w-5 shrink-0 justify-center"><i class="fa-solid fa-users text-xs"></i></span>
                <span class="truncate">Users</span>

                @if($trashedUsersCount > 0)
                    <span class="ml-auto flex items-center gap-1.5 text-[11px] text-rose-300/90">
                        <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>
                        {{ $trashedUsersCount }} trashed
                    </span>
                @endif
            </a>
        </div>

        {{-- ==========================================================
                                      System
        =========================================================== --}}
        <div>
            <div class="cv-admin-label mb-2 px-3 text-white/30">System</div>

            <div
                x-data="{ open: {{ $systemActive ? 'true' : 'false' }}, pinned: {{ $systemActive ? 'true' : 'false' }} }"
                class="mb-1"
                @mouseenter="open = true" @mouseleave="open = pinned" @focusin="open = true"
                @focusout="if (!$event.currentTarget.contains($event.relatedTarget)) open = pinned"
            >
                <div class="flex items-center rounded-lg {{ $systemActive ? 'cv-nav-link--active' : '' }}">
                    <button type="button" @click="pinned = !pinned; open = pinned" class="cv-nav-link min-w-0 flex-1 !rounded-r-none text-left">
                        <span class="flex w-5 shrink-0 justify-center"><i class="fa-solid fa-sliders text-xs"></i></span>
                        <span class="truncate">System</span>
                    </button>

                    <button type="button" @click="pinned = !pinned; open = pinned" class="mr-1.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-white/35 transition hover:bg-white/10 hover:text-white" :aria-expanded="open" aria-label="Toggle system navigation">
                        <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="{'rotate-180': open}"></i>
                    </button>
                </div>

                <div class="cv-submenu"
                    x-cloak x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1">
                    <a href="#" class="cv-subnav-link {{ request()->routeIs('admin.activity-logs.*') ? 'cv-subnav-link--active' : '' }}">
                        <span class="flex w-4 justify-center"><i class="fa-solid fa-clock-rotate-left text-[11px]"></i></span>
                        <span>Activity Log</span>
                    </a>
                    <a href="#" class="cv-subnav-link {{ request()->routeIs('admin.settings.*') ? 'cv-subnav-link--active' : '' }}">
                        <span class="flex w-4 justify-center"><i class="fa-solid fa-gear text-[11px]"></i></span>
                        <span>Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- System status --}}
    <div class="mx-4 mb-3 flex items-center gap-2 border-t border-white/[0.06] px-1 pt-4">
        <span class="relative flex h-2 w-2">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-40"></span>
            <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
        </span>
        <span class="cv-admin-label text-white/35">All systems normal</span>
    </div>

    {{-- Admin Identity --}}
    <div class="border-t border-white/[0.06] p-3">
        <div class="flex items-center gap-3 rounded-xl bg-white/[0.04] p-3">
            <div class="avatar placeholder">
                <div class="w-9 rounded-full border border-[var(--cv-brass)]/40 bg-white/5 text-[var(--cv-brass)]">
                    <span class="text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                </div>
            </div>

            <div class="min-w-0 flex-1">
                <div class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</div>
                <div class="cv-admin-mono truncate text-[10px] uppercase text-white/40">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
            </div>
        </div>
    </div>
</aside>

<style>
    [x-cloak] { display: none !important; }

    .cv-nav-link {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        border-radius: 0.5rem;
        padding: 0.65rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: rgb(255 255 255 / 0.65);
        transition: background-color .15s ease, color .15s ease;
    }
    .cv-nav-link:hover { background-color: rgb(255 255 255 / 0.06); color: #fff; }
    .cv-nav-link--active {
        background-color: color-mix(in srgb, var(--cv-brass) 14%, transparent);
        color: var(--cv-brass-soft);
    }
    .cv-nav-link--active::before {
        content: "";
        position: absolute;
        left: -0.5rem;
        top: 0.35rem;
        bottom: 0.35rem;
        width: 3px;
        border-radius: 999px;
        background: var(--cv-brass);
    }

    .cv-submenu { margin-left: 1.15rem; margin-top: 0.25rem; padding-left: 0.85rem; border-left: 1px solid rgb(255 255 255 / 0.08); }
    .cv-subnav-link {
        display: flex; align-items: center; gap: 0.7rem; border-radius: 0.4rem;
        padding: 0.5rem 0.6rem; font-size: 0.8rem; font-weight: 500;
        color: rgb(255 255 255 / 0.45); transition: background-color .15s ease, color .15s ease;
    }
    .cv-subnav-link:hover { background-color: rgb(255 255 255 / 0.06); color: #fff; }
    .cv-subnav-link--active { background-color: rgb(255 255 255 / 0.08); color: var(--cv-brass-soft); }
</style>