<header
    x-data="{ mobileOpen: false, mobileSection: null, scrolled: false, search: false, cmdk: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8);
             window.addEventListener('keydown', (e) => { if ((e.metaKey || e.ctrlKey) && e.key === 'k') { e.preventDefault(); cmdk = true; } if (e.key === 'Escape') { cmdk = false; } });"
    :class="scrolled ? 'shadow-[0_1px_0_rgba(15,17,23,0.06),0_12px_28px_-18px_rgba(15,17,23,0.22)]' : 'shadow-none'"
    class="bg-white/85 backdrop-blur-xl border-b border-[#EBE7DD] sticky top-0 z-50 font-sans transition-shadow duration-200"
>
    <div class="container mx-auto px-4 sm:px-6 lg:px-10 flex items-center gap-3 lg:gap-6 h-[60px] lg:h-[64px]">

        {{-- Logo --}}
        <a href="{{ auth()->check() ? url('dashboard') : url('/') }}" class="flex items-center gap-2.5 shrink-0 group">
            <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-[10px] bg-gradient-to-br from-[#232C4E] to-[#0F1428] flex items-center justify-center shadow-[0_2px_6px_rgba(15,17,23,0.35)] group-hover:shadow-[0_4px_12px_rgba(15,17,23,0.45)] transition-shadow duration-200">
                <i class="fa-solid fa-vault text-[#D9B77E] text-[12px] lg:text-[13px]"></i>
            </div>
            <div class="leading-tight hidden sm:block">
                <p class="font-serif font-semibold tracking-tight leading-none text-[15px] lg:text-[16px] text-[#12141A]">CareerVault</p>
                <p class="text-[10px] text-[#9A9CA3] font-medium mt-0.5 tracking-wide uppercase" style="letter-spacing:0.05em">Career OS</p>
            </div>
        </a>

        {{-- Desktop Navigation --}}
        <nav class="hidden lg:flex items-center gap-0.5" id="main-nav">
            <a href="{{ auth()->check() ? url('/dashboard') : url('/') }}" class="nav-link px-3 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150">Home</a>

            {{-- Jobs --}}
            <div class="nav-dropdown relative" data-dropdown>
                <button type="button" class="nav-trigger cursor-pointer flex items-center gap-1.5 px-3 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150" aria-haspopup="true" aria-expanded="false">
                    Jobs <i class="fa-solid fa-chevron-down text-[8px] mt-px opacity-60 nav-chevron transition-transform duration-150"></i>
                </button>
                <div class="nav-menu absolute left-0 top-full mt-2 pt-2 w-72 z-50" role="menu">
                    <ul class="bg-white border border-[#EBE7DD] shadow-[0_16px_36px_-12px_rgba(15,17,23,0.18)] rounded-2xl p-2">
                        <li class="px-3 pt-2 pb-1.5 text-[10.5px] font-semibold text-[#9A9CA3] uppercase tracking-wide" style="letter-spacing:0.05em">Pipeline</li>
                        <li role="none">
                            <a href="{{ url('/application') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-briefcase"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Applications</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Track every application in one board</span>
                                </span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ url('/interviews') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-comments"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Interviews</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Schedule, prep, and log feedback</span>
                                </span>
                            </a>
                        </li>
                        <li role="none"><div class="border-t border-[#EBE7DD] my-1.5 mx-1"></div></li>
                        <li role="none">
                            <a href="{{ url('/questions') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-circle-question"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Interview questions</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Your personal question bank</span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Network --}}
            <div class="nav-dropdown relative" data-dropdown>
                <button type="button" class="nav-trigger cursor-pointer flex items-center gap-1.5 px-3 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150" aria-haspopup="true" aria-expanded="false">
                    Network <i class="fa-solid fa-chevron-down text-[8px] mt-px opacity-60 nav-chevron transition-transform duration-150"></i>
                </button>
                <div class="nav-menu absolute left-0 top-full mt-2 pt-2 w-72 z-50" role="menu">
                    <ul class="bg-white border border-[#EBE7DD] shadow-[0_16px_36px_-12px_rgba(15,17,23,0.18)] rounded-2xl p-2">
                        <li class="px-3 pt-2 pb-1.5 text-[10.5px] font-semibold text-[#9A9CA3] uppercase tracking-wide" style="letter-spacing:0.05em">Research</li>
                        <li role="none">
                            <a href="{{ url('/companies') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-building"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Companies</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Notes, culture, and comp research</span>
                                </span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ route('platforms.index') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-share-nodes"></i></span>
                                <span class="font-semibold text-[#12141A]">Job platforms</span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ url('/Contacts') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-address-book"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Contacts</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Recruiters, referrals, and hiring managers</span>
                                </span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ url('/employee') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-users"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Employees</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">People tied to a company</span>
                                </span>
                            </a>
                        </li>
                        <li role="none"><div class="border-t border-[#EBE7DD] my-1.5 mx-1"></div></li>
                        <li role="none">
                            <a href="{{ route('companies.create') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon menu-icon--accent"><i class="fa-solid fa-plus"></i></span>
                                <span class="font-semibold text-[#12141A]">Add a company</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Growth --}}
            <div class="nav-dropdown relative" data-dropdown>
                <button type="button" class="nav-trigger cursor-pointer flex items-center gap-1.5 px-3 py-2 text-[13.5px] font-medium text-[#54565D] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150" aria-haspopup="true" aria-expanded="false">
                    Growth <i class="fa-solid fa-chevron-down text-[8px] mt-px opacity-60 nav-chevron transition-transform duration-150"></i>
                </button>
                <div class="nav-menu absolute left-0 top-full mt-2 pt-2 w-72 z-50" role="menu">
                    <ul class="bg-white border border-[#EBE7DD] shadow-[0_16px_36px_-12px_rgba(15,17,23,0.18)] rounded-2xl p-2">
                        <li role="none">
                            <a href="{{ url('/skills') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-star"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Skills</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">What you bring to the table</span>
                                </span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ url('/projects') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-diagram-project"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Projects</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Portfolio pieces worth citing</span>
                                </span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ url('/learning') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-graduation-cap"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Learning</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Courses and study logs</span>
                                </span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ url('/certifications') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-certificate"></i></span>
                                <span>
                                    <span class="block font-semibold text-[#12141A]">Certifications</span>
                                    <span class="block text-[11px] text-[#9A9CA3]">Credentials, dates, and renewals</span>
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Settings / reference data --}}
            <div class="nav-dropdown relative" data-dropdown>
                <button type="button" class="nav-trigger cursor-pointer flex items-center justify-center w-9 h-9 text-[#9A9CA3] hover:text-[#12141A] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150" aria-haspopup="true" aria-expanded="false" title="Reference data">
                    <i class="fa-solid fa-sliders text-[13px]"></i>
                </button>
                <div class="nav-menu absolute left-0 top-full mt-2 pt-2 w-64 z-50" role="menu">
                    <ul class="bg-white border border-[#EBE7DD] shadow-[0_16px_36px_-12px_rgba(15,17,23,0.18)] rounded-2xl p-2">
                        <li class="px-3 pt-2 pb-1.5 text-[10.5px] font-semibold text-[#9A9CA3] uppercase tracking-wide" style="letter-spacing:0.05em">Reference data</li>
                        {{-- <li role="none">
                            <a href="{{ url('/platforms') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-layer-group"></i></span>
                                <span class="font-semibold text-[#12141A]">Job platforms</span>
                            </a>
                        </li> --}}
                        <li role="none">
                            <a href="{{ route('platforms.index') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-share-nodes"></i></span>
                                <span class="font-semibold text-[#12141A]">Job platforms</span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ url('/countries') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-earth-americas"></i></span>
                                <span class="font-semibold text-[#12141A]">Countries</span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ route('cities.index') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-city"></i></span>
                                <span class="font-semibold text-[#12141A]">Cities</span>
                            </a>
                        </li>
                        <li role="none">
                            <a href="{{ url('/industries') }}" role="menuitem" class="menu-item">
                                <span class="menu-icon"><i class="fa-solid fa-industry"></i></span>
                                <span class="font-semibold text-[#12141A]">Industries</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Search (desktop, opens command palette) --}}
        <button type="button" @click="cmdk = true" class="hidden md:flex items-center gap-2.5 flex-1 max-w-[280px] ml-1 px-3 py-2 text-[13px] text-[#9A9CA3] bg-[#F7F5F0] border border-[#EBE7DD] rounded-lg hover:border-[#D8D3C6] hover:bg-white transition-colors duration-150">
            <i class="fa-solid fa-magnifying-glass text-[12px]"></i>
            <span class="flex-1 text-left truncate">Search everything…</span>
            <kbd class="hidden lg:inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[10px] font-semibold text-[#9A9CA3] bg-white border border-[#EBE7DD] rounded-md">⌘K</kbd>
        </button>

        <div class="flex-1"></div>

        {{-- Right Side --}}
        <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
            @guest
                <a href="{{ route('login') }}" class="hidden sm:inline-flex px-4 py-2 text-[13.5px] font-medium text-[#33353A] border border-[#E7E4DC] rounded-lg hover:bg-[#F7F5F0] hover:border-[#D8D3C6] transition-colors duration-150">Sign in</a>
                <a href="{{ route('register') }}" class="hidden sm:inline-flex px-4 py-2 text-[13.5px] font-semibold text-white bg-gradient-to-b from-[#232C4E] to-[#171D38] rounded-lg shadow-[0_1px_2px_rgba(15,17,23,0.15),0_4px_10px_-4px_rgba(27,35,64,0.5)] hover:shadow-[0_2px_4px_rgba(15,17,23,0.2),0_8px_16px_-6px_rgba(27,35,64,0.55)] hover:-translate-y-px transition-all duration-150">Start free</a>
            @endguest

            @auth
                {{-- Mobile search trigger --}}
                <button type="button" @click="cmdk = true" class="md:hidden w-9 h-9 rounded-lg flex items-center justify-center text-[#8A8D93] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">
                    <i class="fa-solid fa-magnifying-glass text-[14px]"></i>
                </button>

                {{-- Quick add --}}
                <div class="nav-dropdown relative hidden sm:block" data-dropdown>
                    <button type="button" class="nav-trigger cursor-pointer flex items-center gap-1.5 pl-3 pr-2.5 py-2 text-[13px] font-semibold text-white bg-gradient-to-b from-[#232C4E] to-[#171D38] rounded-lg shadow-[0_1px_2px_rgba(15,17,23,0.15),0_3px_8px_-3px_rgba(27,35,64,0.5)] hover:-translate-y-px transition-transform duration-150" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-plus text-[10px]"></i> New <i class="fa-solid fa-chevron-down text-[8px] opacity-70 nav-chevron transition-transform duration-150"></i>
                    </button>
                    <div class="nav-menu absolute right-0 top-full mt-2 pt-2 w-60 z-50" role="menu">
                        <ul class="bg-white border border-[#EBE7DD] shadow-[0_16px_36px_-12px_rgba(15,17,23,0.18)] rounded-2xl p-2">
                            <li role="none">
                                <a href="{{ url('/application') }}#new" role="menuitem" class="menu-item">
                                    <span class="menu-icon"><i class="fa-solid fa-briefcase"></i></span>
                                    <span class="font-semibold text-[#12141A]">Application</span>
                                </a>
                            </li>
                            <li role="none">
                                <a href="{{ url('/interviews') }}#new" role="menuitem" class="menu-item">
                                    <span class="menu-icon"><i class="fa-solid fa-comments"></i></span>
                                    <span class="font-semibold text-[#12141A]">Interview</span>
                                </a>
                            </li>
                            <li role="none">
                                <a href="{{ route('companies.create') }}" role="menuitem" class="menu-item">
                                    <span class="menu-icon"><i class="fa-solid fa-building"></i></span>
                                    <span class="font-semibold text-[#12141A]">Company</span>
                                </a>
                            </li>
                            <li role="none">
                                <a href="{{ url('/Contacts') }}#new" role="menuitem" class="menu-item">
                                    <span class="menu-icon"><i class="fa-solid fa-address-book"></i></span>
                                    <span class="font-semibold text-[#12141A]">Contact</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Notifications --}}
                <div class="nav-dropdown relative" data-dropdown>
                    <button type="button" class="nav-trigger cursor-pointer w-9 h-9 rounded-lg flex items-center justify-center text-[#8A8D93] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150 relative" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-regular fa-bell text-[15px]"></i>
                        <span class="absolute top-1.5 right-2 w-[6px] h-[6px] rounded-full bg-[#C0392B] ring-2 ring-white"></span>
                    </button>
                    <div class="nav-menu absolute right-0 top-full mt-2 pt-2 w-80 z-50" role="menu">
                        <div class="bg-white border border-[#EBE7DD] shadow-[0_16px_36px_-12px_rgba(15,17,23,0.18)] rounded-2xl overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-[#EBE7DD]">
                                <p class="text-[13.5px] font-semibold text-[#12141A]">Notifications</p>
                                <button type="button" class="text-[11.5px] font-medium text-[#8A6F3F] hover:text-[#12141A]">Mark all read</button>
                            </div>
                            <ul class="max-h-80 overflow-y-auto">
                                <li class="flex gap-3 px-4 py-3 border-b border-[#F1EEE6] hover:bg-[#F7F5F0] transition-colors duration-150">
                                    <span class="w-8 h-8 rounded-full bg-[#EAF3EC] text-[#2F7D4F] flex items-center justify-center text-[12px] shrink-0 mt-0.5"><i class="fa-solid fa-calendar-check"></i></span>
                                    <span>
                                        <span class="block text-[13px] text-[#12141A]"><span class="font-semibold">Interview reminder</span> — Acme Corp, tomorrow at 10:00 AM</span>
                                        <span class="block text-[11px] text-[#9A9CA3] mt-0.5">2 hours ago</span>
                                    </span>
                                </li>
                                <li class="flex gap-3 px-4 py-3 border-b border-[#F1EEE6] hover:bg-[#F7F5F0] transition-colors duration-150">
                                    <span class="w-8 h-8 rounded-full bg-[#F1EEE6] text-[#8A6F3F] flex items-center justify-center text-[12px] shrink-0 mt-0.5"><i class="fa-solid fa-briefcase"></i></span>
                                    <span>
                                        <span class="block text-[13px] text-[#12141A]">Application to <span class="font-semibold">Northwind Labs</span> moved to Offer</span>
                                        <span class="block text-[11px] text-[#9A9CA3] mt-0.5">Yesterday</span>
                                    </span>
                                </li>
                                <li class="flex gap-3 px-4 py-3 hover:bg-[#F7F5F0] transition-colors duration-150">
                                    <span class="w-8 h-8 rounded-full bg-[#FCEBEB] text-[#C0392B] flex items-center justify-center text-[12px] shrink-0 mt-0.5"><i class="fa-solid fa-clock"></i></span>
                                    <span>
                                        <span class="block text-[13px] text-[#12141A]">Follow up with <span class="font-semibold">Sarah Kim</span> is overdue</span>
                                        <span class="block text-[11px] text-[#9A9CA3] mt-0.5">3 days ago</span>
                                    </span>
                                </li>
                            </ul>
                            <a href="#" class="block text-center py-2.5 text-[12.5px] font-semibold text-[#8A6F3F] hover:bg-[#F7F5F0] border-t border-[#EBE7DD] transition-colors duration-150">View all notifications</a>
                        </div>
                    </div>
                </div>

                {{-- Desktop avatar dropdown --}}
                <div class="nav-dropdown relative hidden lg:block" data-dropdown>
                    <button type="button" class="nav-trigger flex items-center gap-2 pl-1.5 pr-2.5 py-1.5 rounded-full border border-[#E7E4DC] hover:border-[#D8D3C6] hover:bg-[#F7F5F0] cursor-pointer transition-colors duration-150" aria-haspopup="true" aria-expanded="false">
                        <span class="w-7 h-7 rounded-full bg-gradient-to-br from-[#D9B77E] to-[#B8935A] text-white text-[11px] font-semibold flex items-center justify-center shrink-0 ring-2 ring-white shadow-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <span class="text-[13.5px] font-medium text-[#12141A] max-w-[110px] truncate">{{ Str::limit(Auth::user()->name, 14) }}</span>
                        <i class="fa-solid fa-chevron-down text-[8px] text-[#9A9CA3] nav-chevron transition-transform duration-150"></i>
                    </button>

                    <div class="nav-menu absolute right-0 top-full pt-2 w-64 z-50" role="menu">
                        <ul class="bg-white rounded-2xl border border-[#EBE7DD] shadow-[0_16px_36px_-12px_rgba(15,17,23,0.18)] p-2">
                            <li class="px-2.5 py-2.5" role="none">
                                <p class="text-[11.5px] text-[#9A9CA3] font-medium">Signed in as</p>
                                <p class="text-[13.5px] font-semibold text-[#12141A] truncate">{{ Auth::user()->name }}</p>
                                <span class="inline-flex items-center gap-1 mt-1.5 px-2 py-0.5 rounded-full bg-[#F1EEE6] text-[#8A6F3F] text-[10.5px] font-semibold">
                                    <i class="fa-solid fa-bolt text-[9px]"></i> Free plan
                                </span>
                            </li>
                            <li role="none"><div class="border-t border-[#EBE7DD] my-1.5 mx-1"></div></li>
                            <li role="none">
                                <a href="#" role="menuitem" class="menu-item menu-item--flat">
                                    <span class="w-6 text-center text-[#9A9CA3]"><i class="fa-solid fa-user"></i></span>
                                    Profile
                                </a>
                            </li>
                            <li role="none">
                                <a href="#" role="menuitem" class="menu-item menu-item--flat">
                                    <span class="w-6 text-center text-[#9A9CA3]"><i class="fa-solid fa-gear"></i></span>
                                    Account settings
                                </a>
                            </li>
                            <li role="none">
                                <a href="#" role="menuitem" class="menu-item menu-item--flat">
                                    <span class="w-6 text-center text-[#9A9CA3]"><i class="fa-solid fa-credit-card"></i></span>
                                    Billing &amp; plan
                                </a>
                            </li>
                            <li role="none">
                                <a href="#" role="menuitem" class="menu-item menu-item--flat">
                                    <span class="w-6 text-center text-[#9A9CA3]"><i class="fa-regular fa-circle-question"></i></span>
                                    Help &amp; support
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

                {{-- Mobile avatar (opens drawer) --}}
                <button type="button" @click="mobileOpen = true" class="lg:hidden w-8 h-8 rounded-full bg-gradient-to-br from-[#D9B77E] to-[#B8935A] text-white text-[11px] font-semibold flex items-center justify-center shrink-0 ring-2 ring-white shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </button>
            @endauth

            {{-- Mobile menu trigger --}}
            <button type="button" @click="mobileOpen = true" class="lg:hidden w-9 h-9 rounded-lg flex items-center justify-center text-[#33353A] hover:bg-[#F7F5F0] border border-[#E7E4DC] transition-colors duration-150" aria-label="Open menu">
                <i class="fa-solid fa-bars text-[14px]"></i>
            </button>
        </div>
    </div>

    {{-- Command palette / search overlay --}}
    <template x-teleport="body">
        <div x-show="cmdk" x-cloak @keydown.escape.window="cmdk = false" class="fixed inset-0 z-[70]" style="display:none;">
            <div x-show="cmdk" x-transition:enter="transition-opacity ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="cmdk = false" class="absolute inset-0 bg-[#0F1428]/45 backdrop-blur-[2px]"></div>
            <div x-show="cmdk" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2 scale-[0.98]" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="relative mx-auto mt-[12vh] w-[92%] max-w-xl">
                <div class="bg-white rounded-2xl border border-[#EBE7DD] shadow-[0_24px_60px_-12px_rgba(15,17,23,0.35)] overflow-hidden">
                    <div class="flex items-center gap-3 px-4 h-14 border-b border-[#EBE7DD]">
                        <i class="fa-solid fa-magnifying-glass text-[#9A9CA3] text-[14px]"></i>
                        <input x-ref="cmdkInput" x-effect="cmdk && $nextTick(() => $refs.cmdkInput.focus())" type="text" placeholder="Search applications, companies, contacts…" class="flex-1 outline-none text-[14px] text-[#12141A] placeholder:text-[#9A9CA3] bg-transparent">
                        <kbd class="px-1.5 py-0.5 text-[10px] font-semibold text-[#9A9CA3] bg-[#F7F5F0] border border-[#EBE7DD] rounded-md">ESC</kbd>
                    </div>
                    <div class="p-2 max-h-[50vh] overflow-y-auto">
                        <p class="px-3 pt-2 pb-1.5 text-[10.5px] font-semibold text-[#9A9CA3] uppercase tracking-wide" style="letter-spacing:0.05em">Quick links</p>
                        <a href="{{ url('/application') }}" class="menu-item menu-item--flat"><span class="w-6 text-center text-[#9A9CA3]"><i class="fa-solid fa-briefcase"></i></span> Applications</a>
                        <a href="{{ url('/companies') }}" class="menu-item menu-item--flat"><span class="w-6 text-center text-[#9A9CA3]"><i class="fa-solid fa-building"></i></span> Companies</a>
                        <a href="{{ url('/interviews') }}" class="menu-item menu-item--flat"><span class="w-6 text-center text-[#9A9CA3]"><i class="fa-solid fa-comments"></i></span> Interviews</a>
                        <a href="{{ url('/Contacts') }}" class="menu-item menu-item--flat"><span class="w-6 text-center text-[#9A9CA3]"><i class="fa-solid fa-address-book"></i></span> Contacts</a>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Mobile off-canvas menu --}}
    <template x-teleport="body">
    <div x-show="mobileOpen" x-cloak @keydown.escape.window="mobileOpen = false" class="lg:hidden fixed inset-0 z-[60]" style="display: none;">
        <div x-show="mobileOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileOpen = false" class="absolute inset-0 bg-[#12141A]/40 backdrop-blur-[2px]"></div>
        <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="absolute right-0 top-0 h-full w-[86%] max-w-[340px] bg-white shadow-[-12px_0_32px_-8px_rgba(15,17,23,0.25)] flex flex-col">
            <div class="flex items-center justify-between px-5 h-[60px] border-b border-[#EBE7DD] shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-[10px] bg-gradient-to-br from-[#232C4E] to-[#0F1428] flex items-center justify-center">
                        <i class="fa-solid fa-vault text-[#D9B77E] text-[12px]"></i>
                    </div>
                    <p class="font-serif font-semibold text-[14.5px] text-[#12141A]">CareerVault</p>
                </div>
                <button type="button" @click="mobileOpen = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-[#8A8D93] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150" aria-label="Close menu">
                    <i class="fa-solid fa-xmark text-[15px]"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-3 py-3">
                @auth
                    <div class="flex items-center gap-3 px-2.5 py-3 mb-2 rounded-xl bg-[#F7F5F0]">
                        <span class="w-9 h-9 rounded-full bg-gradient-to-br from-[#D9B77E] to-[#B8935A] text-white text-[12px] font-semibold flex items-center justify-center shrink-0 ring-2 ring-white shadow-sm">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[13.5px] font-semibold text-[#12141A] truncate">{{ Auth::user()->name }}</p>
                            <span class="inline-flex items-center gap-1 mt-0.5 px-1.5 py-0.5 rounded-full bg-white text-[#8A6F3F] text-[10px] font-semibold border border-[#EBE7DD]"><i class="fa-solid fa-bolt text-[8px]"></i> Free plan</span>
                        </div>
                    </div>

                    <button type="button" @click="cmdk = true; mobileOpen = false" class="flex items-center gap-3 w-full mb-2 rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#54565D] bg-[#F7F5F0] border border-[#EBE7DD]">
                        <i class="fa-solid fa-magnifying-glass text-[12px]"></i> Search everything…
                    </button>
                @endauth

                <a href="{{ auth()->check() ? url('/dashboard') : url('/') }}" class="flex items-center gap-3 w-full rounded-xl px-2.5 py-3 text-[14px] font-semibold text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">
                    <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0"><i class="fa-solid fa-house"></i></span>
                    Dashboard
                </a>

                {{-- Jobs accordion --}}
                <div class="border-t border-[#EBE7DD] mt-1 pt-1">
                    <button type="button" @click="mobileSection = (mobileSection === 'jobs' ? null : 'jobs')" class="flex items-center justify-between w-full rounded-xl px-2.5 py-3 text-[14px] font-semibold text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0"><i class="fa-solid fa-briefcase"></i></span>
                            Jobs
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-[#9A9CA3] transition-transform duration-150" :class="mobileSection === 'jobs' && 'rotate-180'"></i>
                    </button>
                    <div x-show="mobileSection === 'jobs'" x-collapse.duration.150ms class="pl-[44px] pb-1">
                        <a href="{{ url('/application') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Applications</a>
                        <a href="{{ url('/interviews') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Interviews</a>
                        <a href="{{ url('/questions') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Interview questions</a>
                    </div>
                </div>

                {{-- Network accordion --}}
                <div class="border-t border-[#EBE7DD] pt-1">
                    <button type="button" @click="mobileSection = (mobileSection === 'network' ? null : 'network')" class="flex items-center justify-between w-full rounded-xl px-2.5 py-3 text-[14px] font-semibold text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0"><i class="fa-solid fa-building"></i></span>
                            Network
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-[#9A9CA3] transition-transform duration-150" :class="mobileSection === 'network' && 'rotate-180'"></i>
                    </button>
                    <div x-show="mobileSection === 'network'" x-collapse.duration.150ms class="pl-[44px] pb-1">
                        <a href="{{ url('/companies') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Companies</a>
                        <a href="{{ url('/platforms') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Job platforms</a>
                        <a href="{{ url('/Contacts') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Contacts</a>
                        <a href="{{ url('/employee') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Employees</a>
                    </div>
                </div>

                {{-- Growth accordion --}}
                <div class="border-t border-[#EBE7DD] pt-1">
                    <button type="button" @click="mobileSection = (mobileSection === 'growth' ? null : 'growth')" class="flex items-center justify-between w-full rounded-xl px-2.5 py-3 text-[14px] font-semibold text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0"><i class="fa-solid fa-graduation-cap"></i></span>
                            Growth
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-[#9A9CA3] transition-transform duration-150" :class="mobileSection === 'growth' && 'rotate-180'"></i>
                    </button>
                    <div x-show="mobileSection === 'growth'" x-collapse.duration.150ms class="pl-[44px] pb-1">
                        <a href="{{ url('/skills') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Skills</a>
                        <a href="{{ url('/projects') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Projects</a>
                        <a href="{{ url('/learning') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Learning</a>
                        <a href="{{ url('/certifications') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Certifications</a>
                    </div>
                </div>

                {{-- Reference data accordion --}}
                <div class="border-t border-[#EBE7DD] pt-1">
                    <button type="button" @click="mobileSection = (mobileSection === 'ref' ? null : 'ref')" class="flex items-center justify-between w-full rounded-xl px-2.5 py-3 text-[14px] font-semibold text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">
                        <span class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-[10px] bg-[#F1EEE6] flex items-center justify-center text-[#8A6F3F] text-[13px] shrink-0"><i class="fa-solid fa-sliders"></i></span>
                            Reference data
                        </span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-[#9A9CA3] transition-transform duration-150" :class="mobileSection === 'ref' && 'rotate-180'"></i>
                    </button>
                    <div x-show="mobileSection === 'ref'" x-collapse.duration.150ms class="pl-[44px] pb-1">
                        <a href="{{ route('platforms.index') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Social platforms</a>
                        <a href="{{ url('/countries') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Countries</a>
                        <a href="{{ route('cities.index') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Cities</a>
                        <a href="{{ url('/industries') }}" class="block rounded-lg px-2.5 py-2.5 text-[13.5px] text-[#54565D] hover:text-[#12141A] hover:bg-[#F7F5F0] transition-colors duration-150">Industries</a>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#EBE7DD] p-3 shrink-0">
                @guest
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('register') }}" class="w-full text-center px-4 py-2.5 text-[13.5px] font-semibold text-white bg-gradient-to-b from-[#232C4E] to-[#171D38] rounded-lg shadow-[0_1px_2px_rgba(15,17,23,0.15),0_4px_10px_-4px_rgba(27,35,64,0.5)] transition-all duration-150">Start free</a>
                        <a href="{{ route('login') }}" class="w-full text-center px-4 py-2.5 text-[13.5px] font-medium text-[#33353A] border border-[#E7E4DC] rounded-lg hover:bg-[#F7F5F0] transition-colors duration-150">Sign in</a>
                    </div>
                @endguest

                @auth
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center justify-center gap-2.5 w-full rounded-lg px-4 py-2.5 text-[13.5px] font-medium text-[#C0392B] hover:bg-[#FCEBEB] border border-[#F1D6D6] transition-colors duration-150">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            Log out
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
    </template>
</header>

<style>
    [x-cloak] { display: none !important; }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        border-radius: 0.75rem;
        padding: 0.55rem 0.65rem;
        font-size: 13.5px;
        color: #33353A;
        transition: background-color 150ms ease;
    }
    .menu-item:hover { background-color: #F7F5F0; }
    .menu-item--flat { padding: 0.65rem 0.65rem; font-weight: 500; color: #33353A; }
    .menu-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 0.625rem;
        background-color: #F1EEE6;
        color: #8A6F3F;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }
    .menu-icon--accent { background-color: #232C4E; color: #D9B77E; }

    /* Desktop dropdown menus: hidden by default, revealed by JS toggling
       .is-open, with a CSS fallback (:focus-within) for no-JS / keyboard use. */
    .nav-menu {
        opacity: 0;
        visibility: hidden;
        transform: translateY(4px);
        transition: opacity 140ms ease, transform 140ms ease, visibility 140ms;
        pointer-events: none;
    }
    .nav-dropdown > .nav-menu.is-open,
    .nav-dropdown:focus-within > .nav-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        pointer-events: auto;
    }
    .nav-trigger[aria-expanded="true"] .nav-chevron {
        transform: rotate(180deg);
    }
</style>

<script>
    (function () {
        var OPEN_DELAY = 60;
        var CLOSE_DELAY = 220;

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

            function closeAllSiblings() {
                document.querySelectorAll('.nav-menu.is-open').forEach(function (m) {
                    if (m !== menu) m.classList.remove('is-open');
                });
                document.querySelectorAll('[aria-expanded="true"]').forEach(function (t) {
                    if (t !== trigger) t.setAttribute('aria-expanded', 'false');
                });
            }

            function open() {
                clearTimers();
                closeAllSiblings();
                menu.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }

            function close() {
                clearTimers();
                menu.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            }

            function scheduleOpen() {
                clearTimers();
                openTimer = setTimeout(open, OPEN_DELAY);
            }

            function scheduleClose() {
                clearTimers();
                closeTimer = setTimeout(close, CLOSE_DELAY);
            }

            root.addEventListener('mouseenter', scheduleOpen);
            root.addEventListener('mouseleave', scheduleClose);

            trigger.addEventListener('focus', open);
            root.addEventListener('focusout', function (e) {
                if (!root.contains(e.relatedTarget)) scheduleClose();
            });

            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                if (menu.classList.contains('is-open')) close(); else open();
            });

            root.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { close(); trigger.focus(); }
            });
        });

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