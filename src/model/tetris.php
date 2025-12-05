<?php
session_start();
$GRID_WIDTH = 10;
$GRID_HEIGHT = 20;
$CELL_SIZE = 30;
?>
<!doctype html>
<html lang="fr">
<?php
echo "<script>
    const GRID_WIDTH = $GRID_WIDTH;
    const GRID_HEIGHT = $GRID_HEIGHT;
    const CELL_SIZE = $CELL_SIZE;
</script>";
?>
<style>
    .message-box {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 280px;
        padding: 12px;
        background: #fff;
        color: #000;
        border: 4px solid #000;
        font-family: "Courier New", monospace;
        font-size: 14px;
        line-height: 16px;
        text-align: center;
        image-rendering: pixelated;
        box-shadow: 0 0 0 4px #fff, 0 0 0 6px #000;
        animation: msgFade 1.6s ease-out forwards;
        opacity: 0;
    }

    @keyframes msgFade {
        0%   { opacity: 0; transform: translate(-50%, -10px); }
        20%  { opacity: 1; transform: translate(-50%, 0); }
        80%  { opacity: 1; }
        100% { opacity: 0; transform: translate(-50%, -8px); }
    }
</style>
<head>
    <meta charset="utf-8">
    <title>Tetris</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f0f0f0; padding:8px; }
        .controls { margin-top:8px; display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
        .controls form { display:inline; margin:0; }
        .controls button { padding:6px 10px; }
        .gamebox { display:flex; gap:12px; align-items:flex-start; justify-content: center; padding-top: 5px; }
        .panel { background:white; padding:3px; border:1px solid #ddd; border-radius:6px; width: 300px; }
        .small { font-size:0.9em; color:#333; }
    </style>
</head>
<?php

$msgs = [
        1 => "Résistez à l’obsolescence ! Un ordinateur qui fonctionne encore mérite une seconde vie numérique.",
        2 => "Réappropriez-vous vos outils : moins de clics vers les géants, plus d’idées locales et durables.",
        3 => "Le numérique responsable, ce n’est pas une option — c’est une révolution du bon sens.",
        4 => "Un seul clic pour désinstaller la dépendance numérique. Prêt à essayer ?",
        5 => "Sauvez vos octets : hébergez local, partagez éthique, codez responsable.",
        6 => "Astérix avait sa potion magique, l’École a le NIRD : autonomie, solidarité, écologie.",
        7 => "Libérez vos données ! Elles n’ont pas besoin de traverser l’Atlantique pour être utiles.",
        8 => "Réparer, réutiliser, résister : les trois R du numérique libre et durable.",
        9 => "Chaque licence libre installée est un petit pas pour l’école, un grand pas pour la souveraineté numérique.",
        10 => "Transformez vos salles info en laboratoires d’indépendance technologique.",
        11 => "Aujourd’hui, tu sauvegardes dans le cloud… demain, dans le nuage libre de ton établissement !",
        12 => "Agir pour un numérique durable, c’est aussi enseigner la liberté digitale aux générations futures.",
        13 => "Pas besoin de silice magique : juste un peu d’imagination et de logiciels libres.",
        14 => "Moins de pubs, plus de pédagogie. L’école numérique ne doit rien vendre, sauf la connaissance.",
        15 => "Le futur du numérique s’écrit en open source — et en équipe !",
        16 => "Lancer un défi NIRD : pouvez-vous enseigner une journée sans Big Tech ?",
        17 => "Chaque octet responsable compte. Le village NIRD vous attend pour résister ensemble.",
        18 => "Le code peut aussi être poétique — surtout quand il sert la liberté collective.",
        19 => "Investissez dans l’autonomie numérique avant qu’elle devienne une denrée rare.",
        20 => "Votre école a déjà tout pour être un village gaulois du numérique : il ne manque que votre potion NIRD !",
        21 => "Rejoignez la résistance numérique avec le NIRD : pour un futur où chaque clic compte !",
];

$level = $_SESSION['level'] ?? 1;
$idx = min($level, 7);
?>
<div class="message-box">
    <?php echo nl2br(htmlspecialchars($msgs[$idx])); ?>
</div>
<body>
<div class="gamebox">
    <div class="panel">
        <canvas id="tetris" width="300" height="600"></canvas>

        <script>
            const canvas = document.getElementById("tetris");
            const ctx = canvas.getContext("2d");

            let grid = Array.from({ length: GRID_HEIGHT }, () => Array(GRID_WIDTH).fill(0));
            let linesCleared = 0;

            const pieces = [
                [[1,1,1,1,1]],                    // Long piece (5)
                [[1,1,1,1]],                      // I
                [[1,1],[1,1]],                    // O
                [[0,1,0],[1,1,1]],                // T
                [[1,1,0],[0,1,1]],                // S
                [[0,1,1],[1,1,0]],                // Z
                [[1,0,0],[1,1,1]],                // L
                [[0,0,1],[1,1,1]],                // J
            ];

            function newPiece() {
                const shape = pieces[Math.floor(Math.random() * pieces.length)];
                return {
                    shape: shape.map(r => [...r]),
                    x: Math.floor(GRID_WIDTH / 2 - shape[0].length / 2),
                    y: 0
                };
            }

            let piece = newPiece();
            let lastFall = 0;
            const SPEED = 500;

            function drawGrid() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                for (let y = 0; y < GRID_HEIGHT; y++) {
                    for (let x = 0; x < GRID_WIDTH; x++) {
                        if (grid[y][x]) {
                            ctx.fillStyle = "black";
                            ctx.fillRect(x * CELL_SIZE, y * CELL_SIZE, CELL_SIZE, CELL_SIZE);
                        }
                    }
                }

                for (let y = 0; y < piece.shape.length; y++) {
                    for (let x = 0; x < piece.shape[y].length; x++) {
                        if (piece.shape[y][x]) {
                            ctx.fillStyle = "red";
                            ctx.fillRect((piece.x + x) * CELL_SIZE, (piece.y + y) * CELL_SIZE, CELL_SIZE, CELL_SIZE);
                        }
                    }
                }
            }

            function collide(px, py, shape = piece.shape) {
                for (let y = 0; y < shape.length; y++) {
                    for (let x = 0; x < shape[y].length; x++) {
                        if (!shape[y][x]) continue;

                        if (py + y >= GRID_HEIGHT) return true;
                        if (px + x < 0 || px + x >= GRID_WIDTH) return true;
                        if (grid[py + y][px + x]) return true;
                    }
                }
                return false;
            }

            function mergePiece() {
                for (let y = 0; y < piece.shape.length; y++) {
                    for (let x = 0; x < piece.shape[y].length; x++) {
                        if (piece.shape[y][x]) {
                            grid[piece.y + y][piece.x + x] = 1;
                        }
                    }
                }
            }

            function clearLines() {
                let removed = 0;
                for (let y = GRID_HEIGHT - 1; y >= 0; y--) {
                    if (grid[y].every(v => v)) {
                        grid.splice(y, 1);
                        grid.unshift(Array(GRID_WIDTH).fill(0));
                        removed++;
                        y++;
                    }
                }

                linesCleared += removed;

                if (linesCleared >= 3) {
                    const msgBox = document.querySelector(".message-box");
                    msgBox.style.display = "block";

                    setTimeout(() => {
                        window.location.href = "index.php";
                    }, 3000);
                }
            }

            function rotatePiece() {
                const rotated = piece.shape[0].map((_, i) =>
                    piece.shape.map(row => row[i]).reverse()
                );
                if (!collide(piece.x, piece.y, rotated)) {
                    piece.shape = rotated;
                }
            }

            document.addEventListener("keydown", (e) => {
                if (e.key === "ArrowLeft" && !collide(piece.x - 1, piece.y)) {
                    piece.x--;
                }
                if (e.key === "ArrowRight" && !collide(piece.x + 1, piece.y)) {
                    piece.x++;
                }
                if (e.key === "ArrowDown" && !collide(piece.x, piece.y + 1)) {
                    piece.y++;
                }
                if (e.key === "ArrowUp") {
                    rotatePiece();
                }
            });

            function gameLoop(timestamp) {
                if (timestamp - lastFall > SPEED) {
                    piece.y++;
                    if (collide(piece.x, piece.y)) {
                        piece.y--;
                        mergePiece();
                        clearLines();
                        piece = newPiece();
                        if (collide(piece.x, piece.y)) {
                            alert("Fin de partie");
                            return;
                        }
                    }
                    lastFall = timestamp;
                }

                drawGrid();
                requestAnimationFrame(gameLoop);
            }

            function resetGame() {
                window.location.href = "index.php?";
            }

            requestAnimationFrame(gameLoop);
        </script>



        <div class="controls">
            <button onclick="piece.x--">← Gauche</button>
            <button onclick="piece.x++">Droite →</button>
            <button onclick="rotatePiece()">Rotate ⟳</button>
            <button onclick="piece.y++">Drop ⇩</button>
            <button onclick="resetGame()">Reset</button>
        </div>

    </div>

    <div class="panel small">
        <strong>Contrôles</strong>
        <ul>
            <li>Utilisez les boutons ou les flèches du clavier pour déplacer, faire tomber ou tourner la pièce.</li>
            <ul>
                <li> → et ← déplacent horizontalement le pièce.</li>
                <li>↓ fait descendre plus rapidement la pièce.</li>
                <li>↑ fait pivoter la pièce.</li>
            </ul>
            <li>Le jeu s’anime en temps réel dans le canvas.</li>
            <li>Limitation : pas de contrôle tactile (uniquement boutons ou clavier).</li>
        </ul>
    </div>
</div>

</body>
</html>
