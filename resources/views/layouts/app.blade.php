<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'WoodyCraftWeb') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-900 text-gray-200 relative overflow-x-hidden">
    <!-- Animation de fond -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900 animate-gradient"></div>
    <canvas id="bgCanvas" class="absolute inset-0 -z-10"></canvas>

    <div class="min-h-screen">
        @include('layouts.navigation')

        @if (isset($header))
            <header class="bg-gray-800 shadow-lg border-b border-gray-700">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-gray-100">
                    {{ $header }}
                </div>
            </header>
        @endif

        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        const c = document.getElementById('bgCanvas');
        const ctx = c.getContext('2d');
        c.width = window.innerWidth;
        c.height = window.innerHeight;
        const dots = Array.from({length: 50}, () => ({
            x: Math.random() * c.width,
            y: Math.random() * c.height,
            r: Math.random() * 2 + 1,
            dx: (Math.random() - 0.5) * 0.4,
            dy: (Math.random() - 0.5) * 0.4
        }));
        function animate() {
            ctx.clearRect(0,0,c.width,c.height);
            dots.forEach(d=>{
                ctx.beginPath();
                ctx.arc(d.x,d.y,d.r,0,Math.PI*2);
                ctx.fillStyle = '#38bdf8';
                ctx.fill();
                d.x += d.dx; d.y += d.dy;
                if (d.x<0||d.x>c.width) d.dx*=-1;
                if (d.y<0||d.y>c.height) d.dy*=-1;
            });
            requestAnimationFrame(animate);
        }
        animate();
    </script>
</body>
</html>
