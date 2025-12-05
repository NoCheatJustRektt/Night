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
    const angle = Math.atan2(ball.dy, ball.dx);
    drawAndroidBall(ctxPong, ball.x, ball.y, ball.radius, angle);
    // score
    document.getElementById("pong-score").textContent = `${playerScore} - ${aiScore}`;
}

function drawAndroidBall(ctx, x, y, r, angle) {

  ctx.save();
  ctx.translate(x, y);     // centre
  ctx.rotate(angle);       // rotation
  ctx.translate(-x, -y);

  // ----- sphère verte -----
  const grad = ctx.createRadialGradient(x - r*0.35, y - r*0.35, r*0.1, x, y, r);
  grad.addColorStop(0, '#A4C639');
  grad.addColorStop(0.6, '#8FB22D');
  grad.addColorStop(1, '#7AA21F');

  ctx.beginPath();
  ctx.arc(x, y, r, 0, Math.PI*2);
  ctx.fillStyle = grad;
  ctx.fill();

  // ----- reflet -----
  ctx.beginPath();
  ctx.ellipse(x - r*0.35, y - r*0.35, r*0.55, r*0.35, -0.5, 0, Math.PI*2);
  ctx.fillStyle = 'rgba(255,255,255,0.18)';
  ctx.fill();

  // ----- antennes -----
  ctx.strokeStyle = '#7AA21F';
  ctx.lineWidth = Math.max(2, r*0.12);
  ctx.lineCap = "round";

  // gauche
  ctx.beginPath();
  ctx.moveTo(x - r*0.35, y - r*0.5);
  ctx.lineTo(x - r*0.55, y - r*0.85);
  ctx.stroke();

  // droite
  ctx.beginPath();
  ctx.moveTo(x + r*0.35, y - r*0.5);
  ctx.lineTo(x + r*0.55, y - r*0.85);
  ctx.stroke();

  // tips
  ctx.beginPath();
  ctx.arc(x - r*0.55, y - r*0.85, r*0.12, 0, Math.PI*2);
  ctx.arc(x + r*0.55, y - r*0.85, r*0.12, 0, Math.PI*2);
  ctx.fillStyle = "#7AA21F";
  ctx.fill();

  // ----- yeux -----
  ctx.fillStyle = "#eaf8da";

  ctx.beginPath();
  ctx.ellipse(x - r*0.18, y - r*0.05, r*0.08, r*0.06, 0, 0, Math.PI*2);
  ctx.fill();

  ctx.beginPath();
  ctx.ellipse(x + r*0.18, y - r*0.05, r*0.08, r*0.06, 0, 0, Math.PI*2);
  ctx.fill();

  ctx.restore();
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


