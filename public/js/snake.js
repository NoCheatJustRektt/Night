const canvas = document.getElementById("snake-canvas");
const ctx = canvas.getContext("2d");

const grid = 15;
const rows = canvas.height / grid;
const cols = canvas.width / grid;

let snake, food, dx, dy, nextDx, nextDy, score, gameOver, gameStarted;
let gameInterval = null;

// --------------------
// Initialisation du jeu
// --------------------
function initGame() {
    snake = [{ x: 5, y: 5 }];
    dx = 1; dy = 0;
    nextDx = dx; nextDy = dy;
    score = 0;
    gameOver = false;
    gameStarted = false;
    spawnFood();
    document.getElementById("snake-score").textContent = "Score : 0";
    draw(); // affichage initial
}

// --------------------
// Dessiner une case
// --------------------
function drawCell(x, y, color) {
    ctx.fillStyle = color;
    ctx.fillRect(x * grid, y * grid, grid - 1, grid - 1);
}

// --------------------
// Génération de la pomme
// --------------------
function spawnFood() {
    let newFood;
    do {
        newFood = { x: Math.floor(Math.random() * cols), y: Math.floor(Math.random() * rows) };
    } while (snake.some(p => p.x === newFood.x && p.y === newFood.y));
    food = newFood;
}

// --------------------
// Dessiner tout le canvas
// --------------------
function draw() {
    ctx.fillStyle = "#111";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    if (!gameStarted) {
        // message "Click to Start" centré
        ctx.fillStyle = "white";
        ctx.font = "bold 18px Arial";
        ctx.textAlign = "center";
        ctx.textBaseline = "middle";
        ctx.fillText("Click START to play!", canvas.width / 2, canvas.height / 2);
        return;
    }

    // Dessiner le serpent
    snake.forEach((p, i) => drawCell(p.x, p.y, i === 0 ? "#ffdd57" : "#4c79ff"));

    // Dessiner la pomme
    drawApple(food.x, food.y);
}

// Fonction pour dessiner une pomme
function drawApple(x, y) {
    const targetSize = grid; // la taille d'une case
    const offsetX = x * grid;
    const offsetY = y * grid;

    const pathData = "M788.1 340.9c-5.8 4.5-108.2 62.2-108.2 190.5 0 148.4 130.3 200.9 134.2 202.2-.6 3.2-20.7 71.9-68.7 141.9-42.8 61.6-87.5 123.1-155.5 123.1s-85.5-39.5-164-39.5c-76.5 0-103.7 40.8-165.9 40.8s-105.6-57-155.5-127C46.7 790.7 0 663 0 541.8c0-194.4 126.4-297.5 250.8-297.5 66.1 0 121.2 43.4 162.7 43.4 39.5 0 101.1-46 176.3-46 28.5 0 130.9 2.6 198.3 99.2zm-234-181.5c31.1-36.9 53.1-88.1 53.1-139.3 0-7.1-.6-14.3-1.9-20.1-50.6 1.9-110.8 33.7-147.1 75.8-28.5 32.4-55.1 83.6-55.1 135.5 0 7.8 1.3 15.6 1.9 18.1 3.2.6 8.4 1.3 13.6 1.3 45.4 0 102.5-30.4 135.5-71.3z";
    const path = new Path2D(pathData);

    // calcul de l’échelle pour rentrer dans une case
    const scaleFactor = targetSize / 800; // 800 ≈ largeur originale du path SVG

    ctx.save();
    ctx.translate(offsetX, offsetY);       // position case
    ctx.scale(scaleFactor, scaleFactor);   // réduction
    ctx.fillStyle = "#ff0000";             // rouge
    ctx.fill(path);
    ctx.restore();
}

// --------------------
// Mise à jour du jeu
// --------------------
function update() {
    if (!gameStarted || gameOver) return;

    dx = nextDx;
    dy = nextDy;
    const head = { x: snake[0].x + dx, y: snake[0].y + dy };

    // collision murs
    if (head.x < 0 || head.x >= cols || head.y < 0 || head.y >= rows) {
        gameOver = true;
        drawGameOver();
        return;
    }

    // collision corps
    if (snake.some(p => p.x === head.x && p.y === head.y)) {
        gameOver = true;
        drawGameOver();
        return;
    }

    snake.unshift(head);

    if (head.x === food.x && head.y === food.y) {
        score++;
        document.getElementById("snake-score").textContent = "Score : " + score;
        spawnFood();

        // Victoire
        if (score >= 10) {
            gameOver = true;
            drawWin();
            return;
        }

    } else {
        snake.pop();
    }


    draw();
}

// --------------------
// Affichage Victoire
// --------------------
function drawWin() {
    draw();
    ctx.fillStyle = "rgba(0,0,0,0.6)";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "lime";
    ctx.font = "bold 22px Arial";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText("YOU WIN!", canvas.width / 2, canvas.height / 2);
}


// --------------------
// Affichage Game Over
// --------------------
function drawGameOver() {
    draw();
    ctx.fillStyle = "rgba(0,0,0,0.6)";
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "white";
    ctx.font = "bold 20px Arial";
    ctx.textAlign = "center";
    ctx.textBaseline = "middle";
    ctx.fillText("GAME OVER", canvas.width / 2, canvas.height / 2);
}

// --------------------
// Contrôles
// --------------------
window.addEventListener("keydown", e => {
    switch (e.key) {
        case "ArrowUp": if (dy === 0) { nextDx = 0; nextDy = -1; } break;
        case "ArrowDown": if (dy === 0) { nextDx = 0; nextDy = 1; } break;
        case "ArrowLeft": if (dx === 0) { nextDx = -1; nextDy = 0; } break;
        case "ArrowRight": if (dx === 0) { nextDx = 1; nextDy = 0; } break;
        case "Enter": if (gameOver) initGame(); break;
    }
});

// --------------------
// Boutons
// --------------------
document.getElementById("snake-start").addEventListener("click", () => {
    if (!gameStarted) {
        gameStarted = true;
        if (gameInterval === null) gameInterval = setInterval(update, 150);
    }
});

document.getElementById("snake-reset").addEventListener("click", () => {
    initGame();
});

initGame();
