<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Company Career Sites')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/8e69038194.js" crossorigin="anonymous"></script>

    {{-- alpine.js  --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        .cv-title{font-family:'Fraunces',serif;font-weight:600;letter-spacing:-.01em}
        .cv-eyebrow{font-family:'JetBrains Mono',monospace;font-size:.68rem;letter-spacing:.2em;opacity:.5;text-transform:uppercase}
        .cv-mono{font-family:'JetBrains Mono',monospace}
        .cv-avatar{width:2.25rem;height:2.25rem;border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'Fraunces',serif;font-style:italic;font-weight:600;font-size:1rem;flex-shrink:0}
        .cv-row{transition:background-color .12s}
        .cv-row:hover{background-color:hsl(var(--b2))}
        .cv-row td{vertical-align:middle}
        .cv-name-link{font-weight:600;text-decoration:none}
        .cv-name-link:hover{text-decoration:underline}
        .cv-tag{font-family:'JetBrains Mono',monospace;font-size:.66rem;letter-spacing:.02em}

                .cv-label{font-family:'JetBrains Mono',monospace;font-size:.66rem;letter-spacing:.14em;opacity:.5;text-transform:uppercase}
        .cv-tile{border:1px solid hsl(var(--bc)/.15);border-radius:.75rem;padding:1rem;transition:border-color .12s,background-color .12s}
        .cv-tile:hover{border-color:hsl(var(--p)/.5);background-color:hsl(var(--b2))}
        .cv-table thead th{font-family:'JetBrains Mono',monospace;font-size:.66rem;letter-spacing:.14em;opacity:.5;text-transform:uppercase;font-weight:500}
        .cv-table td{font-size:.9rem}
    </style>
</head>
<body class="bg-gray-100">
 
    @include('layouts.navlink')

    <main class="container mx-auto py-5 px-14 min-h-screen bg-base-200">
        @yield('content')
    </main>

    @include('layouts.footer')

</body>
</html>