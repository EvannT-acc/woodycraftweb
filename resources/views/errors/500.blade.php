<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Erreur interne du serveur</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            background: radial-gradient(circle at 50% 50%, #0f172a, #0a0f1a 80%);
            color: #e5e7eb;
            font-family: 'Figtree', sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .glitch {
            position: relative;
            color: #38bdf8;
            font-size: 5rem;
            font-weight: bold;
            animation: glitch 1s infinite;
        }
        @keyframes glitch {
            0% { text-shadow: 2px 0 red, -2px 0 blue; }
            20% { text-shadow: -2px 0 red, 2px 0 blue; }
            40% { text-shadow: 2px 0 blue, -2px 0 red; }
            60% { text-shadow: -2px 0 blue, 2px 0 red; }
            80% { text-shadow: 1px 0 red, -1px 0 blue; }
            100% { text-shadow: 0 0 red, 0 0 blue; }
        }
        p {
            margin-top: 1rem;
            color: #94a3b8;
            text-align: center;
        }
        a {
            margin-top: 2rem;
            display: inline-block;
            background: #38bdf8;
            color: #0f172a;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }
        a:hover {
            background: #0ea5e9;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="glitch">500</div>
    <p>Oups... Une erreur interne est survenue sur le serveur.</p>
    <a href="{{ route('dashboard') }}">Retour à l’accueil</a>
</body>
</html>
