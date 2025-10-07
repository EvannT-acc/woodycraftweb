<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page non trouvée</title>
    @vite(['resources/css/app.css'])
    <style>
        body {
            background: #0f0f0f;
            color: #e5e7eb;
            font-family: 'Figtree', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }
        h1 {
            font-size: 3rem;
            color: #38bdf8;
            margin-bottom: 0.5rem;
        }
        p {
            color: #94a3b8;
            margin-bottom: 2rem;
        }
        canvas {
            background: #1e293b;
            border: 2px solid #334155;
            border-radius: 10px;
        }
        .btn-home {
            margin-top: 2rem;
            background: #38bdf8;
            color: #0f172a;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background: #0ea5e9;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <h1>404</h1>
    <p>Oups ! Cette page n’existe pas...</p>
    <canvas id="gameCanvas" width="600" height="200"></canvas>
    <a href="{{ route('dashboard') }}" class="btn-home">Retour à l’accueil</a>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');

        let dino = { x: 50, y: 150, width: 40, height: 40, dy: 0, gravity: 0.6, jumpPower: -12, grounded: true };
        let cactus = { x: 600, y: 160, width: 20, height: 40, speed: 6 };
        let score = 0;
        let gameOver = false;

        document.addEventListener('keydown', e => {
            if (e.code === 'Space' && dino.grounded && !gameOver) {
                dino.dy = dino.jumpPower;
                dino.grounded = false;
            } else if (e.code === 'Space' && gameOver) {
                resetGame();
            }
        });

        function resetGame() {
            cactus.x = 600;
            score = 0;
            gameOver = false;
            dino.y = 150;
            dino.dy = 0;
            dino.grounded = true;
            loop();
        }

        function update() {
            if (gameOver) return;

            // Gravité du dino
            dino.y += dino.dy;
            dino.dy += dino.gravity;
            if (dino.y >= 150) {
                dino.y = 150;
                dino.dy = 0;
                dino.grounded = true;
            }

            // Mouvement cactus
            cactus.x -= cactus.speed;
            if (cactus.x + cactus.width < 0) {
                cactus.x = 600 + Math.random() * 200;
                score++;
                cactus.speed += 0.1;
            }

            // Collision
            if (
                dino.x < cactus.x + cactus.width &&
                dino.x + dino.width > cactus.x &&
                dino.y + dino.height > cactus.y
            ) {
                gameOver = true;
            }
        }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#38bdf8";
            ctx.fillRect(dino.x, dino.y, dino.width, dino.height);

            ctx.fillStyle = "#94a3b8";
            ctx.fillRect(cactus.x, cactus.y, cactus.width, cactus.height);

            ctx.fillStyle = "#e5e7eb";
            ctx.font = "16px Figtree";
            ctx.fillText("Score : " + score, 10, 20);

            if (gameOver) {
                ctx.fillStyle = "#f87171";
                ctx.font = "28px Figtree";
                ctx.fillText("💀 Game Over 💀", 200, 100);
                ctx.font = "14px Figtree";
                ctx.fillText("Appuie sur ESPACE pour rejouer", 190, 130);
            }
        }

        function loop() {
            update();
            draw();
            if (!gameOver) requestAnimationFrame(loop);
        }

        loop();
    </script>
</body>
</html>
