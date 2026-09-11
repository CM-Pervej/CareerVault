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
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .cv-admin-title{font-family:'Fraunces',serif;font-weight:600;letter-spacing:-.02em}
        .cv-admin-mono{font-family:'JetBrains Mono',monospace}
        .cv-admin-label{font-family:'JetBrains Mono',monospace;font-size:.64rem;letter-spacing:.14em;text-transform:uppercase}
        .cv-admin-scrollbar::-webkit-scrollbar{width:5px}
        .cv-admin-scrollbar::-webkit-scrollbar-track{background:transparent}
        .cv-admin-scrollbar::-webkit-scrollbar-thumb{background:hsl(var(--bc)/.12);border-radius:999px}
        .cv-admin-scrollbar::-webkit-scrollbar-thumb:hover{background:hsl(var(--bc)/.25)}
        .cv-admin-sidebar{width:260px}
        .cv-admin-content{margin-left:260px}
        .cv-admin-topbar{left:260px}
        @media(max-width:1023px){
            .cv-admin-sidebar{width:280px}
            .cv-admin-content{margin-left:0}
            .cv-admin-topbar{left:0}
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen bg-base-200 text-base-content" x-data="{sidebarOpen:false}">

    <div class="min-h-screen">
        @include('layouts.admin.sidebar')

        <div
            x-show="sidebarOpen"
            x-cloak
            x-transition.opacity
            @click="sidebarOpen=false"
            class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm lg:hidden">
        </div>

        <div class="cv-admin-content min-h-screen flex flex-col">
            @include('layouts.admin.topbar')

            <main class="flex-1 px-4 py-5 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="alert alert-success mb-5 shadow-sm">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error mb-5 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            @include('layouts.admin.footer')
        </div>
    </div>

    @stack('scripts')
</body>
</html>