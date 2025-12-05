<?php
session_start();

header('Content-Type: application/json');

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Récupérer les données JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['game']) || !isset($input['color'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

$game = $input['game'];
$color = $input['color'];

// Valider le nom du jeu
$validGames = ['tetris', 'snake', 'pacman', 'info'];
if (!in_array($game, $validGames)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid game name']);
    exit;
}

// Initialiser la structure si nécessaire
if (!isset($_SESSION['game']) || !is_array($_SESSION['game'])) {
    $_SESSION['game'] = [];
}

if (!isset($_SESSION['game'][$game]) || !is_array($_SESSION['game'][$game])) {
    $_SESSION['game'][$game] = ['state' => false, 'color' => 'red'];
}

// Mettre à jour la couleur et l'état
$_SESSION['game'][$game]['color'] = $color;
$_SESSION['game'][$game]['state'] = true;

echo json_encode([
    'success' => true,
    'game' => $game,
    'color' => $color,
    'state' => $_SESSION['game'][$game]['state']
]);
