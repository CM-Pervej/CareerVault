<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Admin | CareerVault')</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <script src="https://kit.fontawesome.com/8e69038194.js" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Manrope:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            /* Vault / ledger token system */
            --cv-navy: #141A22;
            --cv-navy-2: #1B222C;
            --cv-brass: #B08D3E;
            --cv-brass-soft: #D9BD79;
            --cv-brass-dark: #8A6B28;
            --cv-paper: #F7F5F0;
            --cv-ink: #1B2027;
            --cv-line: #E6E2D8;
            --cv-sidebar-w: 260px;
        }

        body { font-family: 'Manrope', sans-serif; background: var(--cv-paper); }
        .cv-admin-title { font-family: 'Fraunces', serif; font-weight: 600; letter-spacing: -.02em; }
        .cv-admin-mono { font-family: 'JetBrains Mono', monospace; }
        .cv-admin-label { font-family: 'JetBrains Mono', monospace; font-size: .64rem; letter-spacing: .12em; text-transform: uppercase; }

        .cv-admin-scrollbar::-webkit-scrollbar { width: 5px; }
        .cv-admin-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .cv-admin-scrollbar::-webkit-scrollbar-thumb { background: rgb(255 255 255 / .12); border-radius: 999px; }
        .cv-admin-scrollbar::-webkit-scrollbar-thumb:hover { background: rgb(255 255 255 / .22); }

        .cv-admin-sidebar { width: var(--cv-sidebar-w); }
        .cv-admin-content { margin-left: var(--cv-sidebar-w); }
        .cv-admin-topbar { left: var(--cv-sidebar-w); }

        @media (max-width: 1023px) {
            .cv-admin-sidebar { width: 260px; }
            .cv-admin-content { margin-left: 0; }
            .cv-admin-topbar { left: 0; }
        }

        /* Ledger-stamp notices, replacing default alert boxes */
        .cv-notice {
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid var(--cv-line);
            background: #fff;
            padding: 0.85rem 1rem 0.85rem 1.1rem;
        }
        .cv-notice::before {
            content: "";
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            border-radius: 999px 0 0 999px;
        }
        .cv-notice--success::before { background: #3F7A5A; }
        .cv-notice--error::before { background: #B44B3F; }
        .cv-notice--success i { color: #3F7A5A; }
        .cv-notice--error i { color: #B44B3F; }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-[var(--cv-paper)] text-[var(--cv-ink)]" x-data="{sidebarOpen:false}">
    <div class="min-h-screen">
        @include('layouts.admin.sidebar')

        <div x-show="sidebarOpen" x-cloak x-transition.opacity @click="sidebarOpen=false" class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden"></div>

        <div class="cv-admin-content flex min-h-screen flex-col">
            @include('layouts.admin.topbar')

            <main class="flex-1 bg-white">
                @if(session('success'))
                    <div class="cv-notice cv-notice--success mb-5">
                        <i class="fa-solid fa-circle-check mt-0.5 text-sm"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="cv-notice cv-notice--error mb-5">
                        <i class="fa-solid fa-circle-exclamation mt-0.5 text-sm"></i>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            @include('layouts.admin.footer')
        </div>
    </div>

    @include('components.admin.action-modal')

    @stack('scripts')

    {{-- to check all borders --}}
    {{-- <script>
        document.querySelectorAll('*').forEach(element=>{
        element.style.outline='1px solid red';
    });
    </script> --}}
</body>
</html>