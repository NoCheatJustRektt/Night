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
    drawCell(food.x, food.y, "#ff0000");
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
    } else {
        snake.pop();
    }

    draw();
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
