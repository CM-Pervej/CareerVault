@php
    /*
    |--------------------------------------------------------------------------
    | Breadcrumb Trail + Auto Page Title
    |--------------------------------------------------------------------------
    | Mirrors the sidebar's route groupings, split into [group, section, page]:
    | - group   top-level nav group (Overview, Directory, Reference Data, ...)
    | - section the expandable item within the group, if any (Platforms)
    | - page    the most specific label for the current route
    |
    | The breadcrumb only ever shows "group / section" (never the page), and
    | the page heading falls back to $page/$section/$group automatically, so
    | a subpage that forgets to set @section('page_title', ...) still shows
    | its own name instead of a leftover "Dashboard".
    */

    [$group, $section, $page] = match (true) {
        request()->routeIs('admin.platform-connections.*') => ['Directory', 'Platforms', 'Connections'],
        request()->routeIs('admin.platform-pages.*') => ['Directory', 'Platforms', 'Pages'],
        request()->routeIs('admin.platform-groups.*') => ['Directory', 'Platforms', 'Groups'],
        request()->routeIs('admin.platform-communities.*') => ['Directory', 'Platforms', 'Communities'],
        request()->routeIs('admin.platforms.*') => ['Directory', 'Platforms', 'Platforms'],
        request()->routeIs('admin.countries.*') => ['Reference Data', null, 'Countries'],
        request()->routeIs('admin.cities.*') => ['Reference Data', null, 'Cities'],
        request()->routeIs('admin.industries.*') => ['Reference Data', null, 'Industries'],
        request()->routeIs('admin.users.*') => ['People', null, 'Users'],
        request()->routeIs('admin.activity-logs.*') => ['System', null, 'Activity Log'],
        request()->routeIs('admin.settings.*') => ['System', null, 'Settings'],
        request()->routeIs('admin.dashboard') => ['Overview', null, 'Dashboard'],
        default => ['Overview', null, null],
    };

    $crumb = implode(' / ', array_filter([$group, $section]));
    $autoTitle = $page ?? $section ?? $group ?? 'Dashboard';
@endphp

<header class="cv-admin-topbar sticky top-0 z-30 h-16 border-b border-[var(--cv-line)] bg-white backdrop-blur">
    <div class="flex h-full items-center justify-between gap-4 px-4 sm:px-6">

        {{-- Left --}}
        <div class="flex min-w-0 items-center gap-3">
            <button @click="sidebarOpen=true" class="lg:hidden">
                <i class="fa-solid fa-bars text-3xl"></i>
            </button>

            <div class="min-w-0">
                <div class="cv-admin-label sm:mb-0.5 truncate text-[var(--cv-brass-dark)]/70">{{ $crumb }}</div>
                <h1 class="cv-admin-title truncate text-xl text-[var(--cv-ink)]">
                    @yield('page_title', $autoTitle)
                </h1>
            </div>
        </div>

        {{-- Right --}}
        <div class="flex items-center gap-1.5 sm:gap-2">

            {{-- Ledger date stamp --}}
            <div class="cv-admin-mono mr-2 hidden flex-col items-end leading-tight text-[var(--cv-ink)]/45 md:flex">
                <span class="text-[11px]">{{ now()->format('d M Y') }}</span>
                <span class="text-[10px]">{{ now()->format('H:i') }}</span>
            </div>

            {{-- Search --}}
            <div class="relative hidden sm:block" x-data="{ focused: false }">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-[var(--cv-ink)]/35"></i>
                <input
                    type="text"
                    placeholder="Search records"
                    @focus="focused = true" @blur="focused = false"
                    class="h-9 w-40 rounded-lg border border-[var(--cv-line)] bg-white/60 pl-8 pr-12 text-xs text-[var(--cv-ink)] outline-none transition-all placeholder:text-[var(--cv-ink)]/35 focus:w-64 focus:border-[var(--cv-brass)]/50 focus:bg-white focus:ring-2 focus:ring-[var(--cv-brass)]/15"
                >
                <kbd class="kbd kbd-xs absolute right-2 top-1/2 hidden -translate-y-1/2 border-[var(--cv-line)] bg-transparent text-[var(--cv-ink)]/40 lg:inline-flex" x-show="!focused">⌘K</kbd>
            </div>

            {{-- Notifications --}}
            <div class="dropdown dropdown-end">
                <button tabindex="0" class="btn btn-ghost btn-circle btn-sm text-[var(--cv-ink)]/60 hover:bg-[var(--cv-ink)]/5">
                    <div class="indicator">
                        <i class="fa-regular fa-bell"></i>
                    </div>
                </button>

                <div tabindex="0" class="menu dropdown-content z-[60] mt-2 w-72 rounded-xl border border-[var(--cv-line)] bg-white p-4 shadow-xl">
                    <div class="cv-admin-label mb-3 text-[var(--cv-ink)]/40">Notifications</div>
                    <div class="flex flex-col items-center gap-2 py-6 text-center">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[var(--cv-ink)]/5 text-[var(--cv-ink)]/30">
                            <i class="fa-regular fa-bell-slash text-sm"></i>
                        </span>
                        <p class="text-xs text-[var(--cv-ink)]/50">You're all caught up.</p>
                    </div>
                </div>
            </div>

            {{-- User --}}
            <div class="dropdown dropdown-end">
                <button tabindex="0" class="btn btn-ghost h-10 min-h-10 gap-2 rounded-xl px-2 hover:bg-[var(--cv-ink)]/5">
                    <div class="avatar placeholder">
                        <div class="w-8 rounded-full border border-[var(--cv-brass)]/40 bg-[var(--cv-ink)] text-[var(--cv-brass-soft)]">
                            <span class="text-xs font-bold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                    </div>

                    <div class="hidden text-left md:block">
                        <div class="max-w-32 truncate text-xs font-semibold text-[var(--cv-ink)]">{{ auth()->user()->name }}</div>
                        <div class="cv-admin-mono text-[9px] uppercase text-[var(--cv-ink)]/40">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                    </div>

                    <i class="fa-solid fa-chevron-down hidden text-[9px] text-[var(--cv-ink)]/40 md:block"></i>
                </button>

                <ul tabindex="0" class="menu dropdown-content z-[60] mt-2 w-56 rounded-xl border border-[var(--cv-line)] bg-white p-2 shadow-xl">
                    <li class="menu-title px-3 py-2">
                        <span class="cv-admin-label text-[var(--cv-ink)]/40">Account</span>
                    </li>

                    <li>
                        <a href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-arrow-up-right-from-square w-4"></i>
                            User Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="/">
                            <i class="fa-solid fa-globe w-4"></i>
                            View CareerVault
                        </a>
                    </li>

                    <div class="my-1 border-t border-[var(--cv-line)]"></div>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left">
                                <i class="fa-solid fa-right-from-bracket w-4"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>