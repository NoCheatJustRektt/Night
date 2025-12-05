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
        <?php
        $msgs = [
            1 => "Résistez à l’obsolescence ! Votre Mac ou iPhone peut encore briller : offrez-lui une seconde vie numérique.",
            2 => "Réappropriez-vous vos outils Apple : moins de dépendance aux apps payantes, plus d’idées locales et durables.",
            3 => "Le numérique responsable sur iOS et macOS, ce n’est pas une option — c’est une révolution du bon sens.",
            4 => "Un seul geste pour réduire la dépendance aux géants du cloud. Prêt à essayer iCloud de manière responsable ?",
            5 => "Sauvez vos données : stockez localement sur votre Mac, partagez éthique, codez responsable sur Swift.",
            6 => "Astérix avait sa potion magique, Apple a ses outils : autonomie, créativité, sécurité.",
            7 => "Libérez vos données ! Elles n’ont pas besoin de quitter votre iPhone ou Mac pour être utiles.",
            8 => "Réparer, réutiliser, résister : les trois R du numérique durable… même avec un MacBook.",
            9 => "Chaque app open source installée sur macOS est un petit pas pour l’utilisateur, un grand pas pour la souveraineté numérique.",
            10 => "Transformez vos espaces Apple en laboratoires d’indépendance technologique.",
            11 => "Aujourd’hui, vous sauvegardez dans iCloud… demain, dans votre nuage local et libre !",
            12 => "Agir pour un numérique durable sur Apple, c’est aussi enseigner la liberté digitale aux générations futures.",
            13 => "Pas besoin de puce magique : juste un peu d’imagination et des apps libres sur votre iPhone ou Mac.",
            14 => "Moins de pubs dans vos apps, plus de pédagogie. Votre iPad peut servir à apprendre, pas à consommer.",
            15 => "Le futur du numérique sur Apple s’écrit en open source — et en équipe !",
            16 => "Lancer un défi Apple responsable : pouvez-vous passer une journée sans apps Big Tech ?",
            17 => "Chaque octet responsable compte. Le village numérique Apple vous attend pour résister ensemble.",
            18 => "Le code Swift peut aussi être poétique — surtout quand il sert la liberté collective.",
            19 => "Investissez dans l’autonomie numérique avant qu’elle devienne une denrée rare… même sur vos appareils Apple.",
            20 => "Votre Mac ou iPad a déjà tout pour être un outil du numérique durable : il ne manque que votre touche responsable !",
            21 => "Rejoignez la résistance numérique Apple : pour un futur où chaque clic sur votre iPhone compte !",
        ];
        $level = $_SESSION['level'] ?? 1;
        $idx = min($level, 7);
        ?>
        <p>Contrôlez la raquette gauche avec ↑ et ↓. Score à gauche : joueur, score à droite : IA.</p>
        
        <div style="display: flex; gap: 20px; align-items: flex-start; justify-content: center; margin-top: 10px;">
            <!-- Zone de jeu principale -->
            <div style="flex-shrink: 0;">
                <canvas id="pong-canvas" width="600" height="400"></canvas>
                <div id="pong-score">0 - 0</div>
                <div id="pong-controls">
                    <button id="pong-start">Start</button>
                    <button id="pong-reset">Reset</button>
                </div>
            </div>
            

        </div>
    </div>
                <!-- Message box sur le côté -->
            <div class="message-box" style="
                width: 250px;
                padding: 15px;
                background: rgba(221, 235, 157, 0.1);
                border: 2px solid #DDEB9D;
                border-radius: 10px;
                color: #DDEB9D;
                text-align: left;
                line-height: 1.6;
                font-size: 13px;
                height: fit-content;
            ">
                <strong style="display: block; margin-bottom: 10px; color: #A0C878;">💡 Le saviez-vous ?</strong>
                <span id="rotating-message"><?php echo nl2br(htmlspecialchars($msgs[$idx])); ?></span>
            </div>
    <script>
        // Attendre que le DOM soit complètement chargé
        (function() {
            // Messages pour rotation
            const messages = <?php echo json_encode(array_values($msgs)); ?>;
            let currentIndex = <?php echo ($idx - 1); ?>;
            
            console.log('🔄 Rotation des messages initialisée avec', messages.length, 'messages');
            
            // Fonction pour changer le message
            function rotateMessage() {
                const messageElement = document.getElementById('rotating-message');
                
                if (!messageElement) {
                    console.error('❌ Élément rotating-message non trouvé');
                    return;
                }
                
                currentIndex = (currentIndex + 1) % messages.length;
                console.log('📝 Changement de message:', currentIndex);
                
                // Effet de transition
                messageElement.style.opacity = '0';
                
                setTimeout(() => {
                    messageElement.innerHTML = messages[currentIndex].replace(/\n/g, '<br>');
                    messageElement.style.opacity = '1';
                }, 300);
            }
            
            // Attendre un peu que tout soit chargé
            setTimeout(() => {
                const messageElement = document.getElementById('rotating-message');
                if (messageElement) {
                    // Style de transition
                    messageElement.style.transition = 'opacity 0.3s ease';
                    
                    // Rotation toutes les 5 secondes
                    setInterval(rotateMessage, 5000);
                    console.log('✅ Rotation des messages activée');
                } else {
                    console.error('❌ Impossible de trouver l\'élément rotating-message');
                }
            }, 100);
        })();
    </script>
</body>
</html>
