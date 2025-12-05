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
            1 => "Résistez à l'obsolescence ! Votre smartphone ou tablette Android peut encore briller : offrez-lui une seconde vie numérique.",
            2 => "Réappropriez-vous vos outils Android : moins de dépendance aux apps payantes, plus d'idées locales et durables.",
            3 => "Le numérique responsable sur Android n'est pas une option — c'est une révolution du bon sens.",
            4 => "Un seul geste pour réduire la dépendance aux services centralisés. Prêt à tester un cloud local ou F-Droid ?",
            5 => "Sauvez vos données : stockez localement sur votre appareil, partagez éthique, codez responsable sur Android.",
            6 => "Astérix avait sa potion magique, Android a ses outils : autonomie, créativité, sécurité.",
            7 => "Libérez vos données ! Elles n'ont pas besoin de traverser le globe pour être utiles sur votre téléphone.",
            8 => "Réparer, réutiliser, résister : les trois R du numérique libre et durable… même avec votre smartphone Android.",
            9 => "Chaque app open source installée via F-Droid est un petit pas pour l'utilisateur, un grand pas pour la souveraineté numérique.",
            10 => "Transformez vos appareils Android en laboratoires d'indépendance technologique.",
            11 => "Aujourd'hui, vous sauvegardez sur le cloud… demain, dans votre nuage local et libre !",
            12 => "Agir pour un numérique durable sur Android, c'est aussi enseigner la liberté digitale aux générations futures.",
            13 => "Pas besoin de magie : juste un peu d'imagination et des apps libres sur votre appareil.",
            14 => "Moins de pubs, plus de pédagogie. Votre smartphone peut servir à apprendre, pas à consommer.",
            15 => "Le futur du numérique sur Android s'écrit en open source — et en équipe !",
            16 => "Lancer un défi Android responsable : pouvez-vous passer une journée sans Google Play Services ?",
            17 => "Chaque octet responsable compte. Le village NIRD vous attend pour résister ensemble.",
            18 => "Le code peut aussi être poétique — surtout quand il sert la liberté collective sur Android.",
            19 => "Investissez dans l'autonomie numérique avant qu'elle devienne une denrée rare… même sur vos appareils Android.",
            20 => "Votre smartphone ou tablette Android a déjà tout pour être un outil du numérique durable : il ne manque que votre touche NIRD !",
            21 => "Rejoignez la résistance numérique avec Android et le NIRD : pour un futur où chaque clic compte !",
        ];

        $level = $_SESSION['level'] ?? 1;
        $idx = min($level, 7);
        ?>
        <p>
Dans notre aventure, Android te met un petit handicap : certaines applis sont imposées et tu ne peux pas les supprimer. Elles restent là, prennent un peu de place et t’empêchent d’avoir totalement la main sur ton téléphone. À toi maintenant de relever le défi et de reprendre le contrôle pour avancer !        </p>
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
