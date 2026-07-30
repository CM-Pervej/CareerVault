<header class="bg-white border-b border-[#E7E4DC] sticky top-0 z-50 font-sans">
    <div class="max-w-screen-xl mx-auto px-6 py-3.5 flex items-center justify-between gap-8">

        {{-- Logo --}}
        @auth
            <a href="{{ url('dashboard') }}" class="flex items-center gap-2.5 shrink-0">
                <div class="w-9 h-9 rounded-xl bg-[#1B2340] flex items-center justify-center">
                    <i class="fa-solid fa-vault text-white text-sm"></i>
                </div>

                <div class="leading-tight">
                    <p class="font-serif font-semibold tracking-tight leading-none text-[15px] text-[#12141A]">
                        CareerVault
                    </p>
                    <p class="text-[10px] text-[#8A8D93] font-medium mt-0.5">
                        Your career, all in one place
                    </p>
                </div>
            </a>
        @else
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0">
                <div class="w-9 h-9 rounded-xl bg-[#1B2340] flex items-center justify-center">
                    <i class="fa-solid fa-vault text-white text-sm"></i>
                </div>

                <div class="leading-tight">
                    <p class="font-serif font-semibold tracking-tight leading-none text-[15px] text-[#12141A]">
                        CareerVault
                    </p>
                    <p class="text-[10px] text-[#8A8D93] font-medium mt-0.5">
                        Your career, all in one place
                    </p>
                </div>
            </a>
        @endauth

        {{-- Navigation --}}
        <nav class="hidden lg:flex items-center gap-1">

            {{-- <a href="{{ url('/') }}"
               class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                Home
            </a> --}}

            @auth
                <a href="{{ url('/dashboard') }}"
                class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                    Home
                </a>
            @else
                <a href="{{ url('/') }}"
                class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                    Home
                </a>
            @endauth

            <div class="dropdown dropdown-hover">
                <label tabindex="0"
                       class="cursor-pointer flex items-center gap-1 px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                    Jobs
                    <i class="fa-solid fa-chevron-down text-[9px] mt-px"></i>
                </label>

                <ul tabindex="0"
                    class="dropdown-content mt-0 bg-white rounded-2xl border border-[#E7E4DC] shadow-lg w-64 p-1.5 z-50">

                    <li>
                        <a href="{{ url('/jobs') }}"
                           class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F6F5F1] transition-colors">
                            <span class="w-7 h-7 rounded-lg bg-[#F6F5F1] flex items-center justify-center text-[#6B6F76] text-xs shrink-0">
                                <i class="fa-solid fa-briefcase"></i>
                            </span>
                            <span>
                                <span class="block font-medium text-[#12141A]">Jobs</span>
                                <span class="block text-[11.5px] text-[#8A8D93]">Track every application</span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/companies') }}"
                           class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F6F5F1] transition-colors">
                            <span class="w-7 h-7 rounded-lg bg-[#F6F5F1] flex items-center justify-center text-[#6B6F76] text-xs shrink-0">
                                <i class="fa-solid fa-building"></i>
                            </span>
                            <span>
                                <span class="block font-medium text-[#12141A]">Companies</span>
                                <span class="block text-[11.5px] text-[#8A8D93]">Research and notes</span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/platforms') }}"
                           class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F6F5F1] transition-colors">
                            <span class="w-7 h-7 rounded-lg bg-[#F6F5F1] flex items-center justify-center text-[#6B6F76] text-xs shrink-0">
                                <i class="fa-solid fa-layer-group"></i>
                            </span>
                            <span>
                                <span class="block font-medium text-[#12141A]">Platforms</span>
                                <span class="block text-[11.5px] text-[#8A8D93]">Where you're job hunting</span>
                            </span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="dropdown dropdown-hover">
                <label tabindex="0"
                       class="cursor-pointer flex items-center gap-1 px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                    General
                    <i class="fa-solid fa-chevron-down text-[9px] mt-px"></i>
                </label>

                <ul tabindex="0"
                    class="dropdown-content mt-0 bg-white rounded-2xl border border-[#E7E4DC] shadow-lg w-64 p-1.5 z-50">

                    <li>
                        <a href="{{ url('/countries') }}"
                           class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F6F5F1] transition-colors">
                            <span class="w-7 h-7 rounded-lg bg-[#F6F5F1] flex items-center justify-center text-[#6B6F76] text-xs shrink-0">
                                <i class="fa-solid fa-briefcase"></i>
                            </span>
                            <span>
                                <span class="block font-medium text-[#12141A]">Country</span>
                                <span class="block text-[11.5px] text-[#8A8D93]">Manage countries</span>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/industries') }}"
                           class="flex items-center gap-3 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F6F5F1] transition-colors">
                            <span class="w-7 h-7 rounded-lg bg-[#F6F5F1] flex items-center justify-center text-[#6B6F76] text-xs shrink-0">
                                <i class="fa-solid fa-building"></i>
                            </span>
                            <span>
                                <span class="block font-medium text-[#12141A]">Industries</span>
                                <span class="block text-[11.5px] text-[#8A8D93]">Manage industries</span>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>

            {{-- <a href="{{ route('companies.inde', $company) }}"
               class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                Contacts
            </a> --}}

            <a href="{{ url('/interviews') }}"
               class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                Interviews
            </a>

            <a href="{{ url('/skills') }}"
               class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                Skills
            </a>

            <a href="{{ url('/projects') }}"
               class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                Projects
            </a>

            <a href="{{ url('/learning') }}"
               class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                Learning
            </a>

            <a href="{{ url('/certifications') }}"
               class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                Certifications
            </a>

            <a href="{{ url('/questions') }}"
               class="px-3 py-2 text-[13.5px] font-medium text-[#494C54] hover:text-[#12141A] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                Questions
            </a>

        </nav>

        {{-- Right Side --}}
        <div class="flex items-center gap-2.5 shrink-0">

            @guest

                <a href="{{ route('login') }}"
                   class="px-4 py-2 text-[13.5px] font-medium text-[#33353A] border border-[#E7E4DC] rounded-lg hover:bg-[#F6F5F1] transition-colors">
                    Sign in
                </a>

                <a href="{{ route('register') }}"
                   class="px-4 py-2 text-[13.5px] font-medium text-white bg-[#1B2340] rounded-lg hover:bg-[#12141A] transition-colors">
                    Sign up
                </a>

            @endguest

            @auth

                <div class="dropdown dropdown-end">

                    <label tabindex="0"
                           class="flex items-center gap-2 pl-1.5 pr-3 py-1.5 rounded-full border border-[#E7E4DC] hover:bg-[#F6F5F1] cursor-pointer transition-colors">

                        <span class="w-7 h-7 rounded-full bg-[#B8935A] text-white text-[11px] font-semibold flex items-center justify-center shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>

                        <span class="text-[13.5px] font-medium text-[#12141A]">
                            {{ Str::limit(Auth::user()->name, 14) }}
                        </span>

                        <i class="fa-solid fa-chevron-down text-[9px] text-[#8A8D93]"></i>

                    </label>

                    <ul tabindex="0"
                        class="dropdown-content mt-2 bg-white rounded-2xl border border-[#E7E4DC] shadow-lg w-60 p-1.5 z-50">

                        {{-- <li>
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-2.5 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F6F5F1] transition-colors">
                                <span class="w-6 text-center text-[#8A8D93]"><i class="fa-solid fa-gauge"></i></span>
                                Dashboard
                            </a>
                        </li> --}}

                        <li>
                            <a href="#"
                               class="flex items-center gap-2.5 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#33353A] hover:bg-[#F6F5F1] transition-colors">
                                <span class="w-6 text-center text-[#8A8D93]"><i class="fa-solid fa-user"></i></span>
                                Profile
                            </a>
                        </li>

                        <li><div class="border-t border-[#E7E4DC] my-1.5 mx-1"></div></li>

                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="block w-full">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-2.5 w-full rounded-xl px-2.5 py-2.5 text-[13.5px] text-[#C0392B] hover:bg-[#FCEBEB] transition-colors text-left">
                                    <span class="w-6 text-center"><i class="fa-solid fa-right-from-bracket"></i></span>
                                    Log out
                                </button>
                            </form>
                        </li>

                    </ul>
                </div>
            @endauth

        </div>
    </div>
</header>