<aside
    class="cv-admin-sidebar cv-admin-scrollbar fixed inset-y-0 left-0 z-50 flex -translate-x-full flex-col overflow-y-auto border-r border-base-300 bg-base-100 transition-transform duration-200 lg:translate-x-0"
    :class="{'translate-x-0':sidebarOpen}">

    {{-- Brand --}}
    <div class="flex h-20 shrink-0 items-center border-b border-base-300 px-5">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-neutral text-neutral-content shadow-sm">
                <i class="fa-solid fa-vault text-sm"></i>
            </div>
            <div class="min-w-0">
                <div class="cv-admin-title truncate text-xl">CareerVault</div>
                <div class="cv-admin-label mt-0.5 opacity-45">Administration</div>
            </div>
        </a>

        <button
            @click="sidebarOpen=false"
            class="btn btn-ghost btn-sm ml-auto lg:hidden">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-5">

        {{-- Overview --}}
        <div class="mb-6">
            <div class="cv-admin-label mb-2 px-3 opacity-40">Overview</div>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
               {{ request()->routeIs('admin.dashboard') ? 'bg-neutral text-neutral-content shadow-sm' : 'hover:bg-base-200' }}">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-chart-pie text-xs"></i>
                </span>
                <span>Dashboard</span>
            </a>
        </div>

        {{-- Directory --}}
        <div class="mb-6">
            <div class="cv-admin-label mb-2 px-3 opacity-40">Directory</div>

            <a href="{{ route('admin.platforms.index') }}"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                </span>
                <span>Platforms</span>
            </a>

            <a href="{{ route('admin.platform-connections.index') }}"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-link text-xs"></i>
                </span>
                <span>Platform Connections</span>
            </a>

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-building text-xs"></i>
                </span>
                <span>Companies</span>
            </a>

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-location-dot text-xs"></i>
                </span>
                <span>Locations</span>
            </a>
        </div>

        {{-- Reference Data --}}
        <div class="mb-6">
            <div class="cv-admin-label mb-2 px-3 opacity-40">Reference Data</div>

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-earth-asia text-xs"></i>
                </span>
                <span>Countries</span>
            </a>

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-city text-xs"></i>
                </span>
                <span>Cities</span>
            </a>

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-tags text-xs"></i>
                </span>
                <span>Industries</span>
            </a>
        </div>

        {{-- People --}}
        <div class="mb-6">
            <div class="cv-admin-label mb-2 px-3 opacity-40">People</div>
{{-- 
            <a href="{{ route('admin.users.index') }}"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-users text-xs"></i>
                </span>
                <span>Users</span>
            </a> --}}
            <a
                href="{{ route('admin.users.index') }}"
                class="..."
            >
                <i class="fa-solid fa-users"></i>
                <span>Users</span>

@if($trashedUsersCount>0)
    <span class="badge badge-error badge-sm ml-auto">
        {{ $trashedUsersCount }}
    </span>
@endif
            </a>
        </div>

        {{-- System --}}
        <div>
            <div class="cv-admin-label mb-2 px-3 opacity-40">System</div>

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                </span>
                <span>Activity Log</span>
            </a>

            <a href="#"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition hover:bg-base-200">
                <span class="flex w-5 justify-center">
                    <i class="fa-solid fa-gear text-xs"></i>
                </span>
                <span>Settings</span>
            </a>
        </div>
    </nav>

    {{-- Admin identity --}}
    <div class="border-t border-base-300 p-3">
        <div class="flex items-center gap-3 rounded-xl bg-base-200 p-3">
            <div class="avatar placeholder">
                <div class="w-9 rounded-full bg-neutral text-neutral-content">
                    <span class="text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </span>
                </div>
            </div>

            <div class="min-w-0 flex-1">
                <div class="truncate text-sm font-semibold">
                    {{ auth()->user()->name }}
                </div>
                <div class="cv-admin-mono truncate text-[10px] uppercase opacity-45">
                    {{ str_replace('_',' ',auth()->user()->role) }}
                </div>
            </div>
        </div>
    </div>
</aside>