<div id="snake-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
    <h2 id="snake-title">Snake Game</h2>
    <p id="snake-instructions">Suivez les flèches pour guider votre serpent et mangez les pommes rouges !</p>
    <canvas id="snake-canvas" width="300" height="300" style="border: 2px solid #DDEB9D; margin: 20px 0;"></canvas>
    <div id="snake-score" style="font-size: 1.2rem; font-weight: bold; margin: 10px 0;">Score : 0</div>
    <div id="snake-controls" style="display: flex; gap: 10px; margin-top: 10px;">
        <button id="snake-start" class="btn btn-success">Start</button>
        <button id="snake-reset" class="btn btn-secondary">Reset</button>
    </div>
</div>
<script src="public/js/snake.js" defer></script>

