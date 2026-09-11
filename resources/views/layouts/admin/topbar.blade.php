<header class="cv-admin-topbar sticky top-0 z-30 h-20 border-b border-base-300 bg-base-100/95 backdrop-blur">
    <div class="flex h-full items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

        {{-- Left --}}
        <div class="flex min-w-0 items-center gap-3">
            <button
                @click="sidebarOpen=true"
                class="btn btn-ghost btn-sm lg:hidden">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="min-w-0">
                <div class="cv-admin-label mb-0.5 opacity-40">CareerVault / Admin</div>
                <h1 class="cv-admin-title truncate text-xl sm:text-2xl">
                    @yield('page_title','Dashboard')
                </h1>
            </div>
        </div>

        {{-- Right --}}
        <div class="flex items-center gap-1 sm:gap-2">

            {{-- Search --}}
            <button class="btn btn-ghost btn-sm hidden sm:flex gap-2 px-3">
                <i class="fa-solid fa-magnifying-glass text-xs opacity-60"></i>
                <span class="text-xs opacity-60">Search</span>
                <kbd class="kbd kbd-xs hidden lg:inline-flex">⌘ K</kbd>
            </button>

            {{-- Notifications --}}
            <button class="btn btn-ghost btn-circle btn-sm">
                <div class="indicator">
                    <i class="fa-regular fa-bell"></i>
                    <span class="badge badge-primary badge-xs indicator-item"></span>
                </div>
            </button>

            {{-- User --}}
            <div class="dropdown dropdown-end">
                <button tabindex="0" class="btn btn-ghost h-10 min-h-10 gap-2 rounded-xl px-2">
                    <div class="avatar placeholder">
                        <div class="w-8 rounded-full bg-neutral text-neutral-content">
                            <span class="text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                            </span>
                        </div>
                    </div>

                    <div class="hidden text-left md:block">
                        <div class="max-w-32 truncate text-xs font-semibold">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="cv-admin-mono text-[9px] uppercase opacity-40">
                            {{ str_replace('_',' ',auth()->user()->role) }}
                        </div>
                    </div>

                    <i class="fa-solid fa-chevron-down hidden text-[9px] opacity-40 md:block"></i>
                </button>

                <ul tabindex="0" class="menu dropdown-content z-[60] mt-2 w-56 rounded-xl border border-base-300 bg-base-100 p-2 shadow-xl">
                    <li class="menu-title px-3 py-2">
                        <span class="text-[10px] uppercase tracking-wider opacity-50">
                            Account
                        </span>
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

                    <div class="my-1 border-t border-base-300"></div>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full">
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