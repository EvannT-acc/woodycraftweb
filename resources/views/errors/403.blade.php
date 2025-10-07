<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Accès refusé</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            background-color: #0f0f0f;
            color: #e5e7eb;
            font-family: 'Figtree', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }
        .lock {
            font-size: 4rem;
            color: #38bdf8;
            animation: lock 1.2s ease-in-out infinite alternate;
        }
        @keyframes lock {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-10deg); }
        }
        h1 {
            margin-top: 1rem;
            font-size: 2.5rem;
            color: #38bdf8;
        }
        p {
            color: #94a3b8;
            margin-bottom: 2rem;
        }
        a {
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
    <div class="lock">🔒</div>
    <h1>403 - Accès refusé</h1>
    <p>Vous n’avez pas l’autorisation d’accéder à cette page.</p>
    <a href="{{ route('dashboard') }}">Retour au tableau de bord</a>
</body>
</html>
