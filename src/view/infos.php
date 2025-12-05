<div id="infos-container" style="padding: 20px; max-width: 1000px; margin: 0 auto;">
    <h2 style="color: #DDEB9D; text-align: center; margin-bottom: 20px;">Informations du Village</h2>
    
    <div style="display: flex; gap: 30px; align-items: flex-start;">
        <!-- Contenu principal -->
        <div style="flex: 1;">
    <div style="margin-bottom: 20px;">
        <h3 style="color: #A0C878; margin-bottom: 10px;">Section 1 - Introduction</h3>
        <p style="line-height: 1.8; text-align: justify;">
            Bienvenue dans notre aventure Nuit de l'info 2025 ! Ici, Luis-Junior, Tristan et Laurent t’invitent à découvrir une map interactive pleine de défis. 
            Entre mini-jeux et énigmes, tu devras avancer intelligemment pour atteindre la victoire, tout en restant écologique et malin.
        </p>
    </div>
    
    <div style="margin-bottom: 20px;">
        <h3 style="color: #A0C878; margin-bottom: 10px;">Section 2 - Développement</h3>
        <p style="line-height: 1.8; text-align: justify;">
            La map interactive est remplie de zones à explorer, de mini-jeux et de défis logiques. Chaque choix que tu fais impacte ton parcours et te rapproche — ou t’éloigne — de la victoire.
        </p>
        <p style="line-height: 1.8; text-align: justify;">
            Le code tourne entièrement sur PHP, pour que tout reste léger, rapide et écologique.
        </p>
    </div>
    
    <div style="margin-bottom: 20px;">
        <h3 style="color: #A0C878; margin-bottom: 10px;">Section 3 - L’équipe</h3>
        <p style="line-height: 1.8; text-align: justify;">
            Dans l’équipe : un vice-champion, un futur président… et Tristan. Pas besoin de plus pour retourner la Nuit !  
            Ensemble, ils ont conçu les énigmes et mini-jeux pour que le parcours soit fun, stimulant et interactif.
        </p>
    </div>
    
    <div style="margin-bottom: 20px;">
        <h3 style="color: #A0C878; margin-bottom: 10px;">Section 4 - Bonus : le Chatbot abruti</h3>
        <p style="line-height: 1.8; text-align: justify;">
            En plus du jeu principal, tu peux tester notre chatbot abruti ! Ce n’est pas nécessaire pour avancer dans la map, mais il te propose un défi supplémentaire.  
            Arriveras-tu à le comprendre et à résoudre ses petites énigmes ? Un vrai bonus pour les plus curieux !
        </p>
    </div>
    
    <div style="margin-bottom: 20px;">
        <h3 style="color: #A0C878; margin-bottom: 10px;">Section 5 - Conclusion</h3>
        <p style="line-height: 1.8; text-align: justify;">
            Prépare-toi à explorer, résoudre des énigmes et découvrir tous les secrets de la map interactive. Chaque victoire te rapproche du secret final.  
            L’aventure commence maintenant !
        </p>
    </div>
</div>

        
        <!-- Message box sur le côté -->
        <?php
        $msgs = [
            1 => "Résistez à l'obsolescence ! Votre matériel peut encore briller : offrez-lui une seconde vie numérique.",
            2 => "Réappropriez-vous vos outils : moins de dépendance aux géants, plus d'idées locales et durables.",
            3 => "Le numérique responsable n'est pas une option — c'est une révolution du bon sens.",
            4 => "Un seul geste pour réduire la dépendance aux services centralisés. Prêt à tester un cloud local ?",
            5 => "Sauvez vos données : stockez localement, partagez éthique, codez responsable.",
            6 => "Astérix avait sa potion magique, le NIRD a ses outils : autonomie, créativité, sécurité.",
            7 => "Libérez vos données ! Elles n'ont pas besoin de traverser le globe pour être utiles.",
            8 => "Réparer, réutiliser, résister : les trois R du numérique libre et durable.",
            9 => "Chaque logiciel libre installé est un petit pas pour l'utilisateur, un grand pas pour la souveraineté numérique.",
            10 => "Transformez vos espaces numériques en laboratoires d'indépendance technologique.",
            11 => "Aujourd'hui, vous sauvegardez dans le cloud… demain, dans votre nuage local et libre !",
            12 => "Agir pour un numérique durable, c'est aussi enseigner la liberté digitale aux générations futures.",
            13 => "Pas besoin de magie : juste un peu d'imagination et des logiciels libres.",
            14 => "Moins de pubs, plus de pédagogie. Le numérique ne doit rien vendre, sauf la connaissance.",
            15 => "Le futur du numérique s'écrit en open source — et en équipe !",
            16 => "Lancer un défi NIRD : pouvez-vous passer une journée sans les services centralisés ?",
            17 => "Chaque octet responsable compte. Le village NIRD vous attend pour résister ensemble.",
            18 => "Le code peut aussi être poétique — surtout quand il sert la liberté collective.",
            19 => "Investissez dans l'autonomie numérique avant qu'elle devienne une denrée rare.",
            20 => "Votre matériel a déjà tout pour être un outil du numérique durable : il ne manque que votre touche NIRD !",
            21 => "Rejoignez la résistance numérique avec le NIRD : pour un futur où chaque clic compte !",
        ];


        $level = $_SESSION['level'] ?? 1;
        $idx = min($level, 7);
        ?>
        <div class="message-box" style="
            width: 280px;
            flex-shrink: 0;
            padding: 20px;
            background: rgba(221, 235, 157, 0.1);
            border: 2px solid #DDEB9D;
            border-radius: 10px;
            color: #DDEB9D;
            text-align: left;
            line-height: 1.6;
            font-size: 14px;
            height: fit-content;
            position: sticky;
            top: 20px;
        ">
            <strong style="display: block; margin-bottom: 10px; color: #A0C878;">💡 Le saviez-vous ?</strong>
            <span id="rotating-message-infos"><?php echo nl2br(htmlspecialchars($msgs[$idx])); ?></span>
        </div>
    </div>
</div>

<script>
    // Mettre à jour la session et la couleur sur la carte
    fetch('public/api/updateGameStatus.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            game: 'info',
            color: 'green'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Statut du jeu mis à jour:', data);
            // Mettre à jour visuellement la légende sur la page
            updateLegendColor('zone1', 'green');
        } else {
            console.error('❌ Erreur lors de la mise à jour:', data.error);
        }
    })
    .catch(error => {
        console.error('❌ Erreur réseau:', error);
    });
    
    // Rotation des messages
    (function() {
        const messages = <?php echo json_encode(array_values($msgs)); ?>;
        let currentIndex = <?php echo ($idx - 1); ?>;
        
        console.log('ℹ️ Rotation des messages Infos initialisée avec', messages.length, 'messages');
        
        function rotateMessage() {
            const messageElement = document.getElementById('rotating-message-infos');
            
            if (!messageElement) {
                console.error('❌ Élément rotating-message-infos non trouvé');
                return;
            }
            
            currentIndex = (currentIndex + 1) % messages.length;
            console.log('📝 Changement de message Infos:', currentIndex);
            
            messageElement.style.opacity = '0';
            
            setTimeout(() => {
                messageElement.innerHTML = messages[currentIndex].replace(/\n/g, '<br>');
                messageElement.style.opacity = '1';
            }, 300);
        }
        
        setTimeout(() => {
            const messageElement = document.getElementById('rotating-message-infos');
            if (messageElement) {
                messageElement.style.transition = 'opacity 0.3s ease';
                setInterval(rotateMessage, 5000);
                console.log('✅ Rotation des messages Infos activée');
            } else {
                console.error('❌ Impossible de trouver l\'élément rotating-message-infos');
            }
        }, 100);
    })();
</script>