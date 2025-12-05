<?php
$gridTetris_WIDTH_php = 10;
$gridTetris_HEIGHT_php = 20;
$CELL_SIZE = 30;
?>
<!doctype html>
<html lang="fr">
<?php $level = $_SESSION['level'] ?? 1; $idx = min($level, 7); ?>
<script>
    const gridTetris_WIDTH = <?= $gridTetris_WIDTH_php ?>;
    const gridTetris_HEIGHT = <?= $gridTetris_HEIGHT_php ?>;
    const CELL_SIZE = <?= $CELL_SIZE ?>;
</script>
<head>
    <meta charset="utf-8">
    <title>Tetris</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f0f0f0; padding:8px; }
        .controls { margin-top:8px; display:flex; gap:6px; flex-wrap:wrap; align-items:center;     justify-content: center;}
        .controls button { padding:6px 10px; }
        .gamebox { display:flex; gap:12px; align-items:flex-start; justify-content:center; padding-top:5px; }
        .panel { background:white; padding:8px; border:1px solid #ddd; border-radius:6px; }
        .small { font-size:0.9em; color:#333;  }
        #tetris { border:1px solid #ccc; margin: 0 auto; display: block;}

        .message-box {
            position: absolute;
            top: 5%;
            left: 50%;
            transform: translate(-50%, -50%);
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
            opacity: 0;
            transition: display 0.5s ease-in-out;
        }

        .message-box.show {
            animation: popupFade 4.5s forwards;
        }

        @keyframes popupFade {
            0%   { opacity: 0; transform: translate(-50%, -60%); }
            10%  { opacity: 1; transform: translate(-45%, -50%); }
            30%  {  transform: translate(-50%, -50%); }
            50%  {  transform: translate(-45%, -50%); }
            70%  { transform: translate(-50%, -50%); }
            90%  { opacity: 1; transform: translate(-45%, -50%); }
            100% { opacity: 0; transform: translate(-50%, -40%); }
        }
    </style>
    <script>

        const messagesTetris = [
            "Résistez aux mises à jour forcées : un PC qui fonctionne n’a pas besoin d’un redémarrage surprise.",
            "Réappropriez-vous vos machines : moins de clics vers Microsoft, plus de contrôle pour vous.",
            "Le numérique responsable, c’est aussi savoir dire non à la télémétrie obligatoire.",
            "Un seul clic pour désinstaller la dépendance Windows. À vous d’essayer.",
            "Sauvez vos performances : moins de services Microsoft, plus d’air pour votre processeur.",
            "Astérix avait sa potion magique ; Microsoft a… ses écrans bleus.",
            "Libérez vos données ! Pas besoin qu’elles traversent l’Atlantique vers les serveurs Microsoft.",
            "Réparer, réutiliser, résister : surtout face à l’obsolescence programmée des systèmes propriétaires.",
            "Chaque logiciel libre installé, c’est un pas de moins vers l’abonnement forcé.",
            "Transformez votre ordinateur en espace de liberté — et pas seulement en vitrine pour Edge.",
            "Aujourd’hui vous sauvegardez sur OneDrive… demain sur un espace que vous contrôlez.",
            "Agir pour un numérique durable, c’est aussi apprendre sans être suivi par Microsoft.",
            "Pas besoin de magie : juste moins de bloatware préinstallé.",
            "Moins de pubs dans le menu démarrer, plus de place pour la vraie productivité.",
            "Le futur du numérique s’écrit en open source — pas en licences annuelles.",
            "Nouveau défi : travailler une journée sans qu’une fenêtre « Microsoft recommande… » apparaisse.",
            "Chaque clic responsable compte — surtout ceux qui désactivent la télémétrie.",
            "Le code peut être poétique… sauf quand il est compilé pour devenir une publicité pour Copilot.",
            "Investissez dans votre autonomie avant que tout ne devienne un abonnement obligatoire.",
            "Votre PC peut déjà être libre : il suffit de lui enlever ses chaînes Microsoft.",
            "Rejoignez la résistance numérique : ensemble contre les mises à jour qui s’installent toutes seules."
        ];

        function getRandomMessage() {
            return messagesTetris[Math.floor(Math.random() * messagesTetris.length)];
        }
    </script>
</head>
</div>
<body >

<div class="gamebox">
    <div class="panel">
        <canvas id="tetris" width="300" height="600"></canvas>
        <div class="controls">
            <button onclick="moveLeft()">← Gauche</button>
            <button onclick="moveRight()">Droite →</button>
            <button onclick="rotatePiece()">Rotate ⟳</button>
            <button onclick="moveDown()">Drop ⇩</button>
            <button onclick="resetGame()">Reset</button>
            <button onclick="GAME_LOOP_RUNNING = true; requestAnimationFrame(gameLoop)">Launch game</button>
        </div>
    </div>
    <div class="small">
        <div><strong>            Score : </strong> <span id="score">0</span></div>
        <div><strong>Score à atteindre : </strong> <span>1 000</span></div>
        <div><strong>           Lignes : </strong> <span id="lines">0</span></div>
        <br>
        <div>

            Contrôles :
            <ul>
                <li>Flèche Gauche : Déplacer à gauche</li>
                <li>Flèche Droite : Déplacer à droite</li>
                <li>Flèche Haut : Tourner la pièce sens horaire</li>
                <li>Flèche Bas : Tourner le piece sens antihoraire</li>
                <li>Espace : Faire tomber la pièce</li>
            </ul>
        </div>
    </div>
</div>

<div class="message-box" id="popup"></div>

<script>
    let GAME_LOOP_RUNNING = true;
    const canvasTetris = document.getElementById("tetris");
    const ctxTetris = canvasTetris.getContext("2d");

    let gridTetris = Array.from({ length: gridTetris_HEIGHT }, () => Array(gridTetris_WIDTH).fill(0));
    let linesClearedTotal = 0;
    let scoreTetris = 0;

    const pieces = [
        { type: 0, shape: [[1,1,1,1]] }, // I
        { type: 1, shape: [[1,1],[1,1]] }, // O
        { type: 2, shape: [[0,1,0],[1,1,1]] }, // T
        { type: 3, shape: [[1,1,0],[0,1,1]] }, // S
        { type: 4, shape: [[0,1,1],[1,1,0]] }, // Z
        { type: 5, shape: [[1,0,0],[1,1,1]] }, // L
        { type: 6, shape: [[0,0,1],[1,1,1]] }  // J
    ];

    function newPiece() {
        let p = pieces[Math.floor(Math.random() * pieces.length)];
        return {
            type: p.type,
            shape: p.shape.map(r => [...r]),
            x: Math.floor(gridTetris_WIDTH/2 - p.shape[0].length/2),
            y: 0
        };
    }

    let piece = newPiece();
    let lastFall = 0;
    const SPEED = 500;

    const pieceImages = {};
    const imageSources = {
        0: "/images/microsoft/update.jpeg",// I
        1: "/images/microsoft/server.png", // O
        2: "/images/microsoft/usb.png", // T
        3: "/images/microsoft/candy-crush.jpg",// S
        4: "/images/microsoft/menu.jpg",// Z
        5: "/images/microsoft/laptop-on-fire.png",// L
        6: "/images/microsoft/buy-office.jpg",// J
    };

    for (const type in imageSources) {
        const img = new Image();
        img.src = imageSources[type];
        pieceImages[type] = img;
    }

    function drawgridTetris() {
        ctxTetris.clearRect(0, 0, canvasTetris.width, canvasTetris.height);
        for(let y=0; y<gridTetris_HEIGHT; y++)
            for(let x=0; x<gridTetris_WIDTH; x++)
                if(gridTetris[y][x]) {
                    ctxTetris.fillStyle="black";
                    ctxTetris.fillRect(x*CELL_SIZE,y*CELL_SIZE,CELL_SIZE,CELL_SIZE);
                }
        for(let y=0; y<piece.shape.length; y++)
            for(let x=0; x<piece.shape[y].length; x++)
                if(piece.shape[y][x]) {
                    //ctx.fillStyle="red";
                    ctxTetris.fillRect((piece.x+x)*CELL_SIZE,(piece.y+y)*CELL_SIZE,CELL_SIZE,CELL_SIZE);
                    ctxTetris.drawImage(pieceImages[piece.type], (piece.x+x)*CELL_SIZE, (piece.y+y)*CELL_SIZE, CELL_SIZE, CELL_SIZE);
                }
    }

    function collide(px, py, shape=piece.shape) {
        for(let y=0; y<shape.length; y++)
            for(let x=0; x<shape[y].length; x++)
                if(shape[y][x]) {
                    if(py+y>=gridTetris_HEIGHT || px+x<0 || px+x>=gridTetris_WIDTH || gridTetris[py+y][px+x]) return true;
                }
        return false;
    }

    function mergePiece() {
        for(let y=0; y<piece.shape.length; y++)
            for(let x=0; x<piece.shape[y].length; x++)
                if(piece.shape[y][x]) gridTetris[piece.y+y][piece.x+x]=1;
    }

    function clearLines() {
        let removed = 0;
        for(let y=gridTetris_HEIGHT-1; y>=0; y--) {
            if(gridTetris[y].every(v => v)) {
                gridTetris.splice(y,1);
                gridTetris.unshift(Array(gridTetris_WIDTH).fill(0));
                removed++;
                y++;
            }
            if (linesClearedTotal%3===0 && linesClearedTotal>0) {
                showPopup(getRandomMessage());
            }
        }
        if(removed>0) {
            linesClearedTotal += removed;
            if(removed===1) scoreTetris +=50;
            else if(removed===2) scoreTetris +=150;
            else if(removed>=3) scoreTetris +=250;
            document.getElementById("score").innerText = scoreTetris;
            document.getElementById("lines").innerText = linesClearedTotal;
        }
        // Win condition
        if(scoreTetris >= 50) {
            showPopup("Bravo vous avez vaincu microsoft.", 5000);
        }
    }

    function showPopup(text, duration = 5000) {
        const popup = document.getElementById("popup");
        popup.innerText = text;
        popup.classList.add("show");
        setTimeout(() => {
            popup.classList.remove("show");
        }, duration);
    }


    function rotatePiece(nb = 1) {
        let rotated;
        if (nb === -1) {
            rotated = piece.shape[0].map((_, i) => piece.shape.map(row => row[i])).reverse();
        } else {
            rotated = piece.shape[0].map((_, i) => piece.shape.map(row => row[i]).reverse());
        }
        if (!collide(piece.x, piece.y, rotated)) {
            piece.shape = rotated;
        }
    }


    function moveLeft(){ if(!collide(piece.x-1,piece.y)) piece.x--; }
    function moveRight(){ if(!collide(piece.x+1,piece.y)) piece.x++; }
    function moveDown(){ if(!collide(piece.x,piece.y+1)) piece.y++; }
    function resetGame(){
        GAME_LOOP_RUNNING = false;
        gridTetris = Array.from({ length: gridTetris_HEIGHT }, () => Array(gridTetris_WIDTH).fill(0));
        linesClearedTotal = 0;
        scoreTetris = 0;
        document.getElementById("score").innerText = scoreTetris;
        document.getElementById("lines").innerText = linesClearedTotal;
        piece = newPiece();
        lastFall = 0;
        let intervalId = setInterval(() => {
            if (!GAME_LOOP_RUNNING) {
                clearInterval(intervalId);
                return;
            }
            showPopup(getRandomMessage());
        }, 1000);
    }

    document.addEventListener("keydown",(e)=>{
        if(e.key==="ArrowLeft") moveLeft();
        if(e.key==="ArrowRight") moveRight();
        if(e.key==="ArrowDown") rotatePiece(-1);
        if(e.key==="ArrowUp") rotatePiece();
        if(e.key===" ") {
            moveDown();
        }
    });

    function gameLoop(timestamp) {
        if(timestamp - lastFall > SPEED) {
            piece.y++;
            if(collide(piece.x,piece.y)) {
                piece.y--;
                mergePiece();
                clearLines();
                piece = newPiece();
                if(collide(piece.x,piece.y)) {
                    alert("Fin de partie");
                    resetGame()
                }
            }
            lastFall=timestamp;
        }
        drawgridTetris();
        if (GAME_LOOP_RUNNING)
            requestAnimationFrame(gameLoop);
    }

    resetGame()
    drawgridTetris()
</script>

</body>
</html>
