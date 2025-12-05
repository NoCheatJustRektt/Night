const canvasPong = document.getElementById("pong-canvas");
const ctxPong = canvasPong.getContext("2d");

const width = canvasPong.width;
const height = canvasPong.height;

let playerY = height/2 - 50;
let aiY = height/2 - 50;
const paddleWidth = 10;
const paddleHeight = 100;
let ball = { x: width/2, y: height/2, radius: 8, dx: 4, dy: 4 };
let playerScore = 0;
let aiScore = 0;
let gameInterval = null;
let gameStarted = false;
const speed = 5;

// --------------------
// Dessiner tout
// --------------------
function draw() {
    ctxPong.fillStyle = "#000";
    ctxPong.fillRect(0,0,width,height);

    // raquette joueur
    ctxPong.fillStyle = "#4c79ff";
    ctxPong.fillRect(10, playerY, paddleWidth, paddleHeight);

    // raquette IA
    ctxPong.fillStyle = "#ffdd57";
    ctxPong.fillRect(width - 20, aiY, paddleWidth, paddleHeight);

    // balle
    ctxPong.beginPath();
    ctxPong.arc(ball.x, ball.y, ball.radius, 0, Math.PI*2);
    ctxPong.fillStyle = "white";
    ctxPong.fill();
    ctxPong.closePath();

    // score
    document.getElementById("pong-score").textContent = `${playerScore} - ${aiScore}`;
}

// --------------------
// Mise à jour
// --------------------
function update() {
    if (!gameStarted) return;

    // mouvement balle
    ball.x += ball.dx;
    ball.y += ball.dy;

    // collision murs haut/bas
    if(ball.y + ball.radius > height || ball.y - ball.radius < 0) ball.dy *= -1;

    // collision raquettes
    if(ball.x - ball.radius < 20 && ball.y > playerY && ball.y < playerY + paddleHeight) ball.dx *= -1;
    if(ball.x + ball.radius > width - 20 && ball.y > aiY && ball.y < aiY + paddleHeight) ball.dx *= -1;

    // marque un point
    if(ball.x - ball.radius < 0) { 
        aiScore++; 
        resetBall(); 
    }
    if(ball.x + ball.radius > width) { 
        playerScore++; 
        resetBall(); 
    }

    // condition de victoire/défaite
    if(playerScore >= 3) {
        //alert("🎉 Vous avez gagné !");
        
        // Mettre à jour la session et la couleur sur la carte
        fetch('public/api/updateGameStatus.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                game: 'pong',
                color: 'green'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Statut du jeu mis à jour:', data);
                // Mettre à jour visuellement la légende sur la page
                updateLegendColor('zone3', 'green');
            } else {
                console.error('❌ Erreur lors de la mise à jour:', data.error);
            }
        })
        .catch(error => {
            console.error('❌ Erreur réseau:', error);
        });
        
        endGame();
        return;
    }
    if(aiScore >= 3) {
        alert("💀 Vous avez perdu !");
        endGame();
        return;
    }

    // IA simple
    if(ball.y < aiY + paddleHeight/2) aiY -= speed/1.5;
    if(ball.y > aiY + paddleHeight/2) aiY += speed/1.5;

    draw();
}

// --------------------
// Fin du jeu
// --------------------
function endGame() {
    gameStarted = false;
    clearInterval(gameInterval);
    gameInterval = null;
}


// --------------------
// Reset balle
// --------------------
function resetBall() {
    ball.x = width/2;
    ball.y = height/2;
    ball.dx *= -1;
    ball.dy = 4 * (Math.random() > 0.5 ? 1 : -1);
}

// --------------------
// Contrôles joueur
// --------------------
window.addEventListener("keydown", e => {
    switch(e.key){
        case "ArrowUp": 
            playerY -= speed; 
            if(playerY < 0) playerY = 0; 
            e.preventDefault(); // bloque le scroll
            break;
        case "ArrowDown": 
            playerY += speed; 
            if(playerY + paddleHeight > height) playerY = height - paddleHeight; 
            e.preventDefault(); // bloque le scroll
            break;
    }
});


// --------------------
// Boutons
// --------------------
document.getElementById("pong-start").addEventListener("click", () => {
    if(!gameStarted){
        gameStarted = true;
        if(gameInterval === null) gameInterval = setInterval(update, 16); // ~60fps
    }
});

document.getElementById("pong-reset").addEventListener("click", () => {
    playerY = height/2 - paddleHeight/2;
    aiY = height/2 - paddleHeight/2;
    playerScore = 0;
    aiScore = 0;
    resetBall();
    draw();
});

// --------------------
// Init
// --------------------
draw();

// Fonction pour mettre à jour la couleur dans la légende
function updateLegendColor(zoneClass, color) {
    const legendMarker = document.querySelector(`.legend-marker.${zoneClass}`);
    if (legendMarker) {
        legendMarker.style.background = color;
        console.log(`🎨 Couleur de ${zoneClass} mise à jour en ${color}`);
    }
}


