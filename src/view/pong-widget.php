<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pong Game</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/pong.css">
    <script src="public/js/pong.js" defer></script>
</head>
<body>
    <div id="pong-container">
        <h2>Pong Game</h2>
        <p>
Dans notre aventure, Android te met un petit handicap : certaines applis sont imposées et tu ne peux pas les supprimer. Elles restent là, prennent un peu de place et t’empêchent d’avoir totalement la main sur ton téléphone. À toi maintenant de relever le défi et de reprendre le contrôle pour avancer !        </p>
        <p>Contrôlez la raquette gauche avec ↑ et ↓. Score à gauche : joueur, score à droite : IA.</p>
        <canvas id="pong-canvas" width="600" height="400"></canvas>
        <div id="pong-score">0 - 0</div>
        <div id="pong-controls">
            <button id="pong-start">Start</button>
            <button id="pong-reset">Reset</button>
        </div>
    </div>
</body>
</html>
