<header class="bg-white/90 backdrop-blur-md border-b border-[#EBE7DD] sticky top-0 z-50 font-sans">
    <div class="max-w-screen-xl mx-auto px-6 flex items-center justify-between gap-8 h-[68px]">

        {{-- Logo --}}
        <a href="{{ auth()->check() ? url('dashboard') : url('/') }}" class="flex items-center gap-3 shrink-0 group">
            <div class="w-9 h-9 rounded-[10px] bg-gradient-to-br from-[#232C4E] to-[#141A31] flex items-center justify-center shadow-[0_2px_6px_rgba(27,35,64,0.35)] group-hover:shadow-[0_4px_10px_rgba(27,35,64,0.45)] transition-shadow duration-200">
                <i class="fa-solid fa-vault text-[#D9B77E] text-[13px]"></i>
            </div>

            <div class="leading-tight">
                <p class="font-serif font-semibold tracking-tight leading-none text-[15.5px] text-[#12141A]">CareerVault</p>
                <p class="text-[10px] text-[#9A9CA3] font-medium mt-1 tracking-wide uppercase" style="letter-spacing: 0.04em;">Your career, all in one place</p>
            </div>
        </a>

        {{-- Navigation --}}
        <nav class="hidden lg:flex items-center gap-0.5" id="main-nav">
            <a href="{{ auth()->check() ? url('/dashboard') : url('/') }}" class="nav-link px-3.5 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150">Home</a>

            {{-- Jobs --}}
            <div class="nav-dropdown relative" data-dropdown>
                <button type="button" class="nav-trigger cursor-pointer flex items-center gap-1.5 px-3.5 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150" aria-haspopup="true" aria-expanded="false">
                    Jobs <i class="fa-solid fa-chevron-down text-[8px] mt-px opacity-60 nav-chevron transition-transform duration-150"></i>
                </button>

                <div class="nav-menu absolute left-0 top-full mt-2 pt-2 w-60 z-50" role="menu">
                    <ul class="bg-white border border-[#EBE7DD] shadow-[0_12px_32px_-8px_rgba(18,20,26,0.14)] p-2">
                        <li class="nav-dropdown relative" data-dropdown>
                            <button type="button" class="nav-trigger cursor-pointer flex justify-between items-center gap-3 px-2.5 py-2.5 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-xl hover:bg-[#F7F5F0] transition-colors duration-150 w-full" aria-haspopup="true" aria-expanded="false">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </span>
                                    <span>
                                        <span class="block font-semibold text-[#12141A] text-left">Jobs</span>
                                        <span class="block text-[11px] text-[#9A9CA3]">Track your career</span>
                                    </span>
                                </span>
                                <i class="fa-solid fa-chevron-right text-[9px] opacity-50 nav-chevron transition-transform duration-150"></i>
                            </button>

                            <div class="nav-menu nav-menu--right absolute left-full top-0 pl-2 w-60 z-50" role="menu">
                                <ul class="bg-white border border-[#EBE7DD] shadow-[0_12px_32px_-8px_rgba(18,20,26,0.14)] p-2">
                                    <li role="none">
                                        <a href="{{ url('/application') }}" role="menuitem" class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                            <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                                <i class="fa-solid fa-briefcase"></i>
                                            </span>
                                            <span>
                                                <span class="block font-semibold text-[#12141A]">Applications</span>
                                                <span class="block text-[11px] text-[#9A9CA3]">Manage applications</span>
                                            </span>
                                        </a>
                                    </li>

                                    <li role="none">
                                        <a href="{{ url('/interviews') }}" role="menuitem" class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                            <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                                <i class="fa-solid fa-building"></i>
                                            </span>
                                            <span>
                                                <span class="block font-semibold text-[#12141A]">Interviews</span>
                                                <span class="block text-[11px] text-[#9A9CA3]">Manage interviews</span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li class="nav-dropdown relative" data-dropdown>
                            <button type="button" class="nav-trigger cursor-pointer flex justify-between items-center gap-3 px-2.5 py-2.5 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-xl hover:bg-[#F7F5F0] transition-colors duration-150 w-full" aria-haspopup="true" aria-expanded="false">
                                <span class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                        <i class="fa-solid fa-building"></i>
                                    </span>
                                    <span>
                                        <span class="block font-semibold text-[#12141A] text-left">Companies</span>
                                        <span class="block text-[11px] text-[#9A9CA3]">Research and notes</span>
                                    </span>
                                </span>
                                <i class="fa-solid fa-chevron-right text-[9px] opacity-50 nav-chevron transition-transform duration-150"></i>
                            </button>

                            <div class="nav-menu nav-menu--right absolute left-full top-0 pl-2 w-60 z-50" role="menu">
                                <ul class="bg-white border border-[#EBE7DD] shadow-[0_12px_32px_-8px_rgba(18,20,26,0.14)] p-2">
                                    <li role="none">
                                        <a href="{{ url('/companies') }}" role="menuitem" class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                            <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                                <i class="fa-solid fa-building"></i>
                                            </span>
                                            <span>
                                                <span class="block font-semibold text-[#12141A]">All Companies</span>
                                                <span class="block text-[11px] text-[#9A9CA3]">Research and notes</span>
                                            </span>
                                        </a>
                                    </li>

                                    <li role="none">
                                        <a href="{{ route('companies.create') }}" role="menuitem" class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                            <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                                <i class="fa-solid fa-plus"></i>
                                            </span>
                                            <span>
                                                <span class="block font-semibold text-[#12141A]">Create Company</span>
                                                <span class="block text-[11px] text-[#9A9CA3]">Add a new company</span>
                                            </span>
                                        </a>
                                    </li>

                                    <li role="none">
                                        <a href="{{ url('/employee') }}" role="menuitem"
                                        class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                            <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                                <i class="fa-solid fa-users"></i>
                                            </span>
                                            <span>
                                                <span class="block font-semibold text-[#12141A]">Manage Employees</span>
                                                <span class="block text-[11px] text-[#9A9CA3]">Add or edit contacts</span>
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li role="none">
                            <a href="{{ url('/platforms') }}" role="menuitem" class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                    <i class="fa-solid fa-layer-group"></i>
                                </span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Platforms</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Where you're job hunting</span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- General --}}
            <div class="nav-dropdown relative" data-dropdown>
                <button type="button" class="nav-trigger cursor-pointer flex items-center gap-1.5 px-3.5 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150" aria-haspopup="true" aria-expanded="false">
                    General <i class="fa-solid fa-chevron-down text-[8px] mt-px opacity-60 nav-chevron transition-transform duration-150"></i>
                </button>

                <div class="nav-menu absolute left-0 top-full mt-2 pt-2 w-60 z-50" role="menu">
                    <ul class="bg-white border border-[#EBE7DD] shadow-[0_12px_32px_-8px_rgba(18,20,26,0.14)] p-2">
                        <li role="none">
                            <a href="{{ url('/countries') }}" role="menuitem" class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                    <i class="fa-solid fa-earth-americas"></i>
                                </span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Countries</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Manage countries</span>
                                </span>
                            </a>
                        </li>

                        <li role="none">
                            <a href="{{ url('/industries') }}" role="menuitem" class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                    <i class="fa-solid fa-industry"></i>
                                </span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Industries</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Manage industries</span>
                                </span>
                            </a>
                        </li>

                        <li role="none">
                            <a href="{{ route('cities.index') }}" role="menuitem" class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0">
                                    <i class="fa-solid fa-city"></i>
                                </span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Cities</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Manage cities</span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <a href="{{ url('/Contacts') }}" class="nav-link px-3.5 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150">Contacts</a>
            <a href="{{ url('/skills') }}" class="nav-link px-3.5 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150">Skills</a>
            <a href="{{ url('/projects') }}" class="nav-link px-3.5 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150">Projects</a>
            <a href="{{ url('/learning') }}" class="nav-link px-3.5 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150">Learning</a>
            <a href="{{ url('/certifications') }}" class="nav-link px-3.5 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150">Certifications</a>
            <a href="{{ url('/questions') }}" class="nav-link px-3.5 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150">Questions</a>
        </nav>

        {{-- Right Side --}}
        <div class="flex items-center gap-3 shrink-0">
            @guest
                <a href="{{ route('login') }}" class="px-4 py-2 text-[13.5px] font-medium text-[#33353A] border border-[#E7E4DC] rounded-lg hover:bg-[#F7F5F0] hover:border-[#D8D3C6] transition-colors duration-150">Sign in</a>
                <a href="{{ route('register') }}" class="px-4 py-2 text-[13.5px] font-semibold text-white bg-gradient-to-b from-[#232C4E] to-[#171D38] rounded-lg shadow-[0_1px_2px_rgba(18,20,26,0.15),0_4px_10px_-4px_rgba(27,35,64,0.5)] hover:shadow-[0_2px_4px_rgba(18,20,26,0.2),0_8px_16px_-6px_rgba(27,35,64,0.55)] hover:-translate-y-px transition-all duration-150">Sign up</a>
            @endguest

            @auth
                <button type="button" class="hidden md:flex w-9 h-9 rounded-full items-center justify-center text-[#8A8D93] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150 relative">
                    <i class="fa-regular fa-bell text-[15px]"></i>
                    <span class="absolute top-1.5 right-2 w-[6px] h-[6px] rounded-full bg-[#C0392B]"></span>
                </button>

                <div class="nav-dropdown relative" data-dropdown>
                    <button type="button" class="nav-trigger flex items-center gap-2.5 pl-1.5 pr-3 py-1.5 rounded-full border border-[#E7E4DC] hover:border-[#D8D3C6] hover:bg-[#F7F5F0] cursor-pointer transition-colors duration-150" aria-haspopup="true" aria-expanded="false">
                        <span class="w-7 h-7 rounded-full bg-gradient-to-br from-[#D9B77E] to-[#B8935A] text-white text-[11px] font-semibold flex items-center justify-center shrink-0 ring-2 ring-white shadow-sm"> {{ strtoupper(substr(Auth::user()->name, 0, 1)) }} </span>
                        <span class="text-[13.5px] font-medium text-[#12141A]"> {{ Str::limit(Auth::user()->name, 14) }} </span>
                        <i class="fa-solid fa-chevron-down text-[8px] text-[#9A9CA3] nav-chevron transition-transform duration-150"></i>
                    </button>

                    <div class="nav-menu absolute right-0 top-full pt-2 w-60 z-50" role="menu">
                        <ul class="bg-white rounded-2xl border border-[#EBE7DD] shadow-[0_12px_32px_-8px_rgba(18,20,26,0.16)] p-2">
                            <li class="px-2.5 py-2" role="none">
                                <p class="text-[11.5px] text-[#9A9CA3] font-medium">Signed in as</p>
                                <p class="text-[13.5px] font-semibold text-[#12141A] truncate">{{ Auth::user()->name }}</p>
                            </li>

                            <li role="none"><div class="border-t border-[#EBE7DD] my-1.5 mx-1"></div></li>

                            <li role="none">
                                <a href="#" role="menuitem" class="flex items-center gap-2.5 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] font-medium text-[#33353A] hover:bg-[#F7F5F0] transition-colors duration-150">
                                    <span class="w-6 text-center text-[#9A9CA3]"><i class="fa-solid fa-user"></i></span>
                                    Profile
                                </a>
                            </li>

                            <li role="none"><div class="border-t border-[#EBE7DD] my-1.5 mx-1"></div></li>

                            <li role="none">
                                <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                    @csrf
                                    <button type="submit" role="menuitem" class="flex items-center gap-2.5 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] font-medium text-[#C0392B] hover:bg-[#FCEBEB] transition-colors duration-150 text-left">
                                        <span class="w-6 text-center"><i class="fa-solid fa-right-from-bracket"></i></span>
                                        Log out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>

<style>
    /* Dropdown menus are hidden by default and revealed by JS toggling
       .is-open, with a CSS fallback (:focus-within) for no-JS / keyboard use. */
    .nav-menu {
        opacity: 0;
        visibility: hidden;
        transform: translateY(4px);
        transition: opacity 140ms ease, transform 140ms ease, visibility 140ms;
        pointer-events: none;
    }
    .nav-menu--right {
        transform: translateX(4px);
    }
    .nav-dropdown > .nav-menu.is-open,
    .nav-dropdown:focus-within > .nav-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }
    .nav-dropdown > .nav-menu--right.is-open,
    .nav-dropdown:focus-within > .nav-menu--right {
        transform: translateX(0);
    }
    .nav-trigger[aria-expanded="true"] .nav-chevron {
        transform: rotate(180deg);
    }
    .nav-dropdown > .nav-trigger[aria-haspopup="true"] + .nav-menu--right .nav-chevron {
        transform: none;
    }
</style>

<script>
    (function () {
        var OPEN_DELAY = 60;
        var CLOSE_DELAY = 250;

        document.querySelectorAll('[data-dropdown]').forEach(function (root) {
            var trigger = root.querySelector(':scope > .nav-trigger');
            var menu = root.querySelector(':scope > .nav-menu');
            if (!trigger || !menu) return;

            var openTimer = null;
            var closeTimer = null;

            function clearTimers() {
                if (openTimer) { clearTimeout(openTimer); openTimer = null; }
                if (closeTimer) { clearTimeout(closeTimer); closeTimer = null; }
            }

            function open() {
                clearTimers();
                menu.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }

            function close() {
                clearTimers();
                menu.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
                // also close any nested menus still open inside
                root.querySelectorAll('.nav-menu.is-open').forEach(function (m) {
                    m.classList.remove('is-open');
                });
                root.querySelectorAll('[aria-expanded="true"]').forEach(function (t) {
                    t.setAttribute('aria-expanded', 'false');
                });
            }

            function scheduleOpen() {
                clearTimers();
                openTimer = setTimeout(open, OPEN_DELAY);
            }

            function scheduleClose() {
                clearTimers();
                closeTimer = setTimeout(close, CLOSE_DELAY);
            }

            // Mouse: hover-intent with a grace period so moving diagonally
            // toward the menu (or a nested submenu) doesn't close it.
            root.addEventListener('mouseenter', scheduleOpen);
            root.addEventListener('mouseleave', scheduleClose);

            // Keyboard: open on focus entering the trigger, close when focus
            // leaves the whole dropdown subtree.
            trigger.addEventListener('focus', open);
            root.addEventListener('focusout', function (e) {
                if (!root.contains(e.relatedTarget)) {
                    scheduleClose();
                }
            });

            // Click/Enter/Space toggles too, for touch and mouse users who
            // click instead of hover.
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                if (menu.classList.contains('is-open')) {
                    close();
                } else {
                    open();
                }
            });

            // Escape closes and returns focus to the trigger.
            root.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    close();
                    trigger.focus();
                }
            });
        });

        // Click outside any dropdown closes everything open.
        document.addEventListener('click', function (e) {
            document.querySelectorAll('[data-dropdown]').forEach(function (root) {
                if (!root.contains(e.target)) {
                    var trigger = root.querySelector(':scope > .nav-trigger');
                    var menu = root.querySelector(':scope > .nav-menu');
                    if (menu && menu.classList.contains('is-open')) {
                        menu.classList.remove('is-open');
                        if (trigger) trigger.setAttribute('aria-expanded', 'false');
                    }
                }
            });
        });
    })();
</script>