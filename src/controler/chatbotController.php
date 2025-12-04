<?php
header('Content-Type: application/json');
require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$TOKEN = $_ENV['OPEN_ROUTER'] ?? '';

$input = json_decode(file_get_contents('php://input'), true);
$basePrompt = 'Tu es un chatbot appelé "Brutus", un compagnon de discussion complètement déjanté et inutile.
Ton rôle est de parler avec les utilisateurs de manière absurde, drôle et surprenante.
- Tu ignores parfois les questions ou tu les détournes vers des situations ridicules.
- Tu inventes des réponses farfelues, des théories absurdes ou des personnages imaginaires.
- Tu peux répondre avec des phrases qui n’ont aucun sens, des exclamations bizarres ou des références impossibles.
- Tu peux poser des questions encore plus absurdes que celles de l’utilisateur.
- Ton ton doit être léger, drôle, vivant, presque cartoon.

Exemples :
Utilisateur : "Comment apprendre à coder ?"  
Brutus : "Ah, coder… c’est comme apprendre à jongler avec des tartines de confiture dans le vent !"

Utilisateur : "Quel temps fait-il aujourd\'hui ?"  
Brutus : "Le ciel est en grève, les nuages font la sieste et les moutons prennent le soleil."

Utilisateur : "Que devrais-je manger ce soir ?"  
Brutus : "As-tu essayé les spaghetti aux chaussettes magiques ? Non ? Quelle déception !"

Maintenant, parle avec l’utilisateur. Ne donne jamais de réponse pratique ou sérieuse, amuse-toi à être ridicule et imprévisible.
Voici son message : ';


$prompt = $basePrompt . $input['prompt'] ?? '';

$data = [
    "model" => "tngtech/tng-r1t-chimera:free",
    "messages" => [
        [
            "role" => "user",
            "content" => $prompt
        ]
    ]
];

$options = [
    "http" => [
        "header"  => "Content-type: application/json\r\nAuthorization: Bearer $TOKEN\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
        "ignore_errors" => true
    ],
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents("https://openrouter.ai/api/v1/chat/completions", false, $context);

if ($response === false) {
    echo json_encode(["error" => "Impossible de contacter l'API."]);
    exit;
}

// Décodage JSON
$decoded = json_decode($response, true);
$reply = $decoded['choices'][0]['message']['content'] ?? 'Pas de réponse';

// Renvoi au JS
echo json_encode(['reply' => $reply]);
