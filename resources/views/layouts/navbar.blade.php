<header class="bg-base-100 border-b border-base-200 sticky top-0 z-50">
    <div class="max-w-screen-xl mx-auto px-5 py-3 flex items-center justify-between gap-4">

        {{-- ── Brand ── --}}
        <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0">
            <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center">
                <i class="fa-solid fa-vault text-primary-content text-sm"></i>
            </div>
            <div class="leading-tight">
                <p class="text-base font-bold tracking-tight leading-none">LifeVault</p>
                <p class="text-[10px] text-base-content/40 font-medium">Your life, all in one place</p>
            </div>
        </a>

        {{-- ── Desktop nav ── --}}
        <nav class="hidden lg:flex items-center gap-1">
            @php
                $navLinks = [
                    ['label' => 'Home',                 'url' => url('/'),                    'icon' => 'fa-house'],
                    ['label' => 'Jobs',                 'url' => url('/jobs'),                'icon' => 'fa-briefcase'],
                    ['label' => 'Companies',            'url' => route('careers.index'),      'icon' => 'fa-building'],
                    ['label' => 'Platforms',            'url' => url('/platforms'),           'icon' => 'fa-globe'],
                    ['label' => 'Contacts',             'url' => url('/contacts'),            'icon' => 'fa-address-book'],
                    ['label' => 'Interviews',           'url' => url('/interviews'),          'icon' => 'fa-comments'],
                    ['label' => 'Skills',               'url' => url('/skills'),              'icon' => 'fa-star'],
                    ['label' => 'Projects',             'url' => url('/projects'),            'icon' => 'fa-folder'],
                    ['label' => 'Learning',             'url' => url('/learning'),            'icon' => 'fa-graduation-cap'],
                    ['label' => 'Certifications',       'url' => url('/certifications'),      'icon' => 'fa-graduation-cap'],
                    ['label' => 'Questions',            'url' => url('/questions'),           'icon' => 'fa-question-circle'],
                ];
            @endphp

            @foreach ($navLinks as $link)
                @php $active = request()->url() === $link['url']; @endphp
                <a href="{{ $link['url'] }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors
                          {{ $active
                              ? 'bg-primary text-primary-content'
                              : 'text-base-content/60 hover:text-base-content hover:bg-base-200' }}">
                    <i class="fa-solid {{ $link['icon'] }} text-xs"></i>
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- ── Right side ── --}}
        <div class="flex items-center gap-2 shrink-0">

            {{-- About / Contact as ghost links --}}
            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ url('/about') }}"
                   class="px-3 py-1.5 rounded-lg text-sm font-medium text-base-content/50 hover:text-base-content hover:bg-base-200 transition-colors">
                    About
                </a>
                <a href="{{ url('/contact') }}"
                   class="px-3 py-1.5 rounded-lg text-sm font-medium text-base-content/50 hover:text-base-content hover:bg-base-200 transition-colors">
                    Contact
                </a>
            </div>

            {{-- Add application CTA --}}
            <a href="{{ url('/jobs/create') }}"
               class="hidden sm:inline-flex btn btn-primary btn-sm gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Add application
            </a>

            {{-- Mobile hamburger --}}
            <button id="lv-menu-btn" onclick="toggleMenu()"
                class="lg:hidden btn btn-ghost btn-sm btn-square">
                <i id="lv-menu-icon" class="fa-solid fa-bars"></i>
            </button>
        </div>
    </div>

    {{-- ── Mobile dropdown menu ── --}}
    <div id="lv-mobile-menu"
         class="lg:hidden hidden border-t border-base-200 bg-base-100 px-4 py-3">
        <nav class="flex flex-col gap-1">
            @foreach ($navLinks as $link)
                @php $active = request()->url() === $link['url']; @endphp
                <a href="{{ $link['url'] }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ $active
                              ? 'bg-primary text-primary-content'
                              : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                    <i class="fa-solid {{ $link['icon'] }} text-xs w-4 text-center"></i>
                    {{ $link['label'] }}
                </a>
            @endforeach
            <div class="divider my-1"></div>
            <a href="{{ url('/about') }}"   class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-base-content/60 hover:bg-base-200">About</a>
            <a href="{{ url('/contact') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-base-content/60 hover:bg-base-200">Contact</a>
            <a href="{{ url('/jobs/create') }}" class="btn btn-primary btn-sm mt-2 gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Add application
            </a>
        </nav>
    </div>
</header>

<script>
    function toggleMenu() {
        const menu = document.getElementById('lv-mobile-menu');
        const icon = document.getElementById('lv-menu-icon');
        const open = menu.classList.toggle('hidden');
        icon.className = open
            ? 'fa-solid fa-bars'
            : 'fa-solid fa-xmark';
    }

    // Close mobile menu on outside click
    document.addEventListener('click', function (e) {
        const menu = document.getElementById('lv-mobile-menu');
        const btn  = document.getElementById('lv-menu-btn');
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.add('hidden');
            document.getElementById('lv-menu-icon').className = 'fa-solid fa-bars';
        }
    });
</script>