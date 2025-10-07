<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WoodyCraftWeb</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-200 relative overflow-hidden">

    <!-- Fond animé -->
    <div class="absolute inset-0 -z-10 bg-gradient-to-br from-gray-900 via-slate-800 to-gray-900 animate-gradient"></div>
    <canvas id="bgCanvas" class="absolute inset-0 -z-10"></canvas>

    <!-- Navigation simplifiée -->
    <div class="p-6 text-right max-w-7xl mx-auto">
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="text-accent hover:text-blue-400 font-semibold transition">Tableau de bord</a>
            @else
                <a href="{{ route('login') }}" class="text-accent hover:text-blue-400 font-semibold transition">Connexion</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-accent hover:text-blue-400 font-semibold transition">Inscription</a>
                @endif
            @endauth
        @endif
    </div>

    <!-- Contenu principal -->
    <div class="flex flex-col items-center justify-center min-h-screen text-center px-6">
        <h1 class="text-5xl font-extrabold text-accent mb-6 tracking-wide">Bienvenue sur WoodyCraftWeb</h1>
        <p class="text-gray-400 text-lg max-w-2xl mb-8">
            Découvrez nos puzzles artisanaux inspirés de mondes fantastiques, d’aventures et de science-fiction.
        </p>

        <div class="space-x-4">
            @auth
                <a href="{{ route('dashboard') }}" 
                   class="bg-accent text-gray-900 px-6 py-3 rounded-lg font-semibold hover:bg-blue-400 transition shadow-soft">
                    Accéder au tableau de bord
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="bg-accent text-gray-900 px-6 py-3 rounded-lg font-semibold hover:bg-blue-400 transition shadow-soft">
                    Se connecter
                </a>
                <a href="{{ route('register') }}" 
                   class="bg-gray-800 border border-accent text-accent px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                    S'inscrire
                </a>
            @endauth
        </div>
    </div>

    <!-- Animation légère -->
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
