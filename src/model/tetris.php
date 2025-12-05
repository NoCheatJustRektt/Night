<?php
$GRID_WIDTH = 10;
$GRID_HEIGHT = 20;
$CELL_SIZE = 30;
?>
<!doctype html>
<html lang="fr">
<?php $level = $_SESSION['level'] ?? 1; $idx = min($level, 7); ?>
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
            top: 50%;
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
        const GRID_WIDTH = <?php echo $GRID_WIDTH; ?>;
        const GRID_HEIGHT = <?php echo $GRID_HEIGHT; ?>;
        const CELL_SIZE = <?php echo $CELL_SIZE; ?>;
        const messages = [
            "Résistez à l’obsolescence ! Un ordinateur qui fonctionne encore mérite une seconde vie numérique.",
            "Réappropriez-vous vos outils : moins de clics vers les géants, plus d’idées locales et durables.",
            "Le numérique responsable, ce n’est pas une option — c’est une révolution du bon sens.",
            "Un seul clic pour désinstaller la dépendance numérique. Prêt à essayer ?",
            "Sauvez vos octets : hébergez local, partagez éthique, codez responsable.",
            "Astérix avait sa potion magique, l’École a le NIRD : autonomie, solidarité, écologie.",
            "Libérez vos données ! Elles n’ont pas besoin de traverser l’Atlantique pour être utiles.",
            "Réparer, réutiliser, résister : les trois R du numérique libre et durable.",
            "Chaque licence libre installée est un petit pas pour l’école, un grand pas pour la souveraineté numérique.",
            "Transformez vos salles info en laboratoires d’indépendance technologique.",
            "Aujourd’hui, tu sauvegardes dans le cloud… demain, dans le nuage libre de ton établissement !",
            "Agir pour un numérique durable, c’est aussi enseigner la liberté digitale aux générations futures.",
            "Pas besoin de silice magique : juste un peu d’imagination et de logiciels libres.",
            "Moins de pubs, plus de pédagogie. L’école numérique ne doit rien vendre, sauf la connaissance.",
            "Le futur du numérique s’écrit en open source — et en équipe !",
            "Lancer un défi NIRD : pouvez-vous enseigner une journée sans Big Tech ?",
            "Chaque octet responsable compte. Le village NIRD vous attend pour résister ensemble.",
            "Le code peut aussi être poétique — surtout quand il sert la liberté collective.",
            "Investissez dans l’autonomie numérique avant qu’elle devienne une denrée rare.",
            "Votre école a déjà tout pour être un village gaulois du numérique : il ne manque que votre potion NIRD !",
            "Rejoignez la résistance numérique avec le NIRD : pour un futur où chaque clic compte !"
        ];

        function getRandomMessage() {
            return messages[Math.floor(Math.random() * messages.length)];
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
    const canvas = document.getElementById("tetris");
    const ctx = canvas.getContext("2d");

    let grid = Array.from({ length: GRID_HEIGHT }, () => Array(GRID_WIDTH).fill(0));
    let linesClearedTotal = 0;
    let score = 0;

    const pieces = [
        [[1,1,1,1,1]], // Long piece
        [[1,1,1,1]],   // I
        [[1,1],[1,1]], // O
        [[0,1,0],[1,1,1]], // T
        [[1,1,0],[0,1,1]], // S
        [[0,1,1],[1,1,0]], // Z
        [[1,0,0],[1,1,1]], // L
        [[0,0,1],[1,1,1]]  // J
    ];

    function newPiece() {
        const shape = pieces[Math.floor(Math.random() * pieces.length)];
        return { shape: shape.map(r => [...r]), x: Math.floor(GRID_WIDTH/2 - shape[0].length/2), y: 0 };
    }

    let piece = newPiece();
    let lastFall = 0;
    const SPEED = 500;

    const pieceImages = {};
    const imageSources = {
        I: "/images/microsoft/update.jpeg",
        O: "/images/microsoft/server.png",
        T: "/images/microsoft/usb.png",
        S: "/images/microsoft/candy-crush.jpg",
        Z: "/images/microsoft/menu.jpg",
        L: "/images/microsoft/laptop-on-fire.png",
        J: "/images/microsoft/buy-office.jpg",
    };

    for (const type in imageSources) {
        const img = new Image();
        img.src = imageSources[type];
        pieceImages[type] = img;
    }



    function drawGrid() {
        ctx.clearRect(0,0,canvas.width,canvas.height);
        for(let y=0; y<GRID_HEIGHT; y++)
            for(let x=0; x<GRID_WIDTH; x++)
                if(grid[y][x]) {
                    ctx.fillStyle="black";
                    ctx.fillRect(x*CELL_SIZE,y*CELL_SIZE,CELL_SIZE,CELL_SIZE);
                }
        for(let y=0; y<piece.shape.length; y++)
            for(let x=0; x<piece.shape[y].length; x++)
                if(piece.shape[y][x]) {
                    ctx.fillStyle="red";
                    //ctx.drawImage(pieceImages[piece.type], (piece.x+x)*CELL_SIZE, (piece.y+y)*CELL_SIZE, CELL_SIZE, CELL_SIZE);
                    ctx.fillRect((piece.x+x)*CELL_SIZE,(piece.y+y)*CELL_SIZE,CELL_SIZE,CELL_SIZE);
                }
    }

    function collide(px, py, shape=piece.shape) {
        for(let y=0; y<shape.length; y++)
            for(let x=0; x<shape[y].length; x++)
                if(shape[y][x]) {
                    if(py+y>=GRID_HEIGHT || px+x<0 || px+x>=GRID_WIDTH || grid[py+y][px+x]) return true;
                }
        return false;
    }

    function mergePiece() {
        for(let y=0; y<piece.shape.length; y++)
            for(let x=0; x<piece.shape[y].length; x++)
                if(piece.shape[y][x]) grid[piece.y+y][piece.x+x]=1;
    }

    function clearLines() {
        let removed = 0;
        for(let y=GRID_HEIGHT-1; y>=0; y--) {
            if(grid[y].every(v => v)) {
                grid.splice(y,1);
                grid.unshift(Array(GRID_WIDTH).fill(0));
                removed++;
                y++;
            }
            if (linesClearedTotal%3===0 && linesClearedTotal>0) {
                //message random nird
                showPopup()
            }
        }
        if(removed>0) {
            linesClearedTotal += removed;
            // Score rules
            if(removed===1) score +=50;
            else if(removed===2) score +=150;
            else if(removed>=3) score +=250;

            document.getElementById("score").innerText = score;
            document.getElementById("lines").innerText = linesClearedTotal;
        }

        // Win condition
        if(score >= 1000) {
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
        grid = Array.from({ length: GRID_HEIGHT }, () => Array(GRID_WIDTH).fill(0));
        linesClearedTotal = 0;
        score = 0;
        document.getElementById("score").innerText = score;
        document.getElementById("lines").innerText = linesClearedTotal;
        piece = newPiece();
        lastFall = 0;
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
                    window.location.reload();
                    return;
                }
            }
            lastFall=timestamp;
        }
        drawGrid();
        if (GAME_LOOP_RUNNING)
            requestAnimationFrame(gameLoop);
    }

    resetGame()
    drawGrid()
</script>

</body>
</html>
