<div id="infos-container" style="padding: 20px; max-width: 1000px; margin: 0 auto;">
    <h2 style="color: #DDEB9D; text-align: center; margin-bottom: 20px;">Informations du Village</h2>
    
    <div style="display: flex; gap: 30px; align-items: flex-start;">
        <!-- Contenu principal -->
        <div style="flex: 1;">
            <div style="margin-bottom: 20px;">
                <h3 style="color: #A0C878; margin-bottom: 10px;">Section 1 - Introduction</h3>
                <p style="line-height: 1.8; text-align: justify;">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure 
                    dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non 
                    proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
            
            <div style="margin-bottom: 20px;">
                <h3 style="color: #A0C878; margin-bottom: 10px;">Section 2 - Développement</h3>
                <p style="line-height: 1.8; text-align: justify;">
                    Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque 
                    ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia 
                    voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.
                </p>
                <p style="line-height: 1.8; text-align: justify;">
                    Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi 
                    tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem 
                    ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?
                </p>
            </div>
            
            <div style="margin-bottom: 20px;">
                <h3 style="color: #A0C878; margin-bottom: 10px;">Section 3 - Communauté</h3>
                <p style="line-height: 1.8; text-align: justify;">
                    At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos 
                    dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt 
                    mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.
                </p>
                <p style="line-height: 1.8; text-align: justify;">
                    Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, 
                    omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum 
                    necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae.
                </p>
            </div>
            
            <div style="margin-bottom: 20px;">
                <h3 style="color: #A0C878; margin-bottom: 10px;">Section 4 - Ressources</h3>
                <p style="line-height: 1.8; text-align: justify;">
                    Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis 
                    doloribus asperiores repellat. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut 
                    labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida.
                </p>
            </div>
            
            <div style="margin-bottom: 20px;">
                <h3 style="color: #A0C878; margin-bottom: 10px;">Section 5 - Conclusion</h3>
                <p style="line-height: 1.8; text-align: justify;">
                    Risus commodo viverra maecenas accumsan lacus vel facilisis. Duis convallis convallis tellus id interdum velit laoreet id 
                    donec. Nulla facilisi cras fermentum odio eu feugiat pretium nibh ipsum. Sed blandit libero volutpat sed cras ornare arcu 
                    dui vivamus. Sagittis orci a scelerisque purus semper eget duis at tellus.
                </p>
                <p style="line-height: 1.8; text-align: justify;">
                    Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum mattis ullamcorper 
                    velit sed ullamcorper morbi tincidunt ornare massa eget. Dictum varius duis at consectetur lorem donec massa sapien faucibus.
                </p>
            </div>
        </div>
        
        <!-- Message box sur le côté -->
        <?php
        $msgs = [
            1 => "Résistez à l'obsolescence ! Votre Mac ou iPhone peut encore briller : offrez-lui une seconde vie numérique.",
            2 => "Réappropriez-vous vos outils Apple : moins de dépendance aux apps payantes, plus d'idées locales et durables.",
            3 => "Le numérique responsable sur iOS et macOS, ce n'est pas une option — c'est une révolution du bon sens.",
            4 => "Un seul geste pour réduire la dépendance aux géants du cloud. Prêt à essayer iCloud de manière responsable ?",
            5 => "Sauvez vos données : stockez localement sur votre Mac, partagez éthique, codez responsable sur Swift.",
            6 => "Astérix avait sa potion magique, Apple a ses outils : autonomie, créativité, sécurité.",
            7 => "Libérez vos données ! Elles n'ont pas besoin de quitter votre iPhone ou Mac pour être utiles.",
            8 => "Réparer, réutiliser, résister : les trois R du numérique durable… même avec un MacBook.",
            9 => "Chaque app open source installée sur macOS est un petit pas pour l'utilisateur, un grand pas pour la souveraineté numérique.",
            10 => "Transformez vos espaces Apple en laboratoires d'indépendance technologique.",
            11 => "Aujourd'hui, vous sauvegardez dans iCloud… demain, dans votre nuage local et libre !",
            12 => "Agir pour un numérique durable sur Apple, c'est aussi enseigner la liberté digitale aux générations futures.",
            13 => "Pas besoin de puce magique : juste un peu d'imagination et des apps libres sur votre iPhone ou Mac.",
            14 => "Moins de pubs dans vos apps, plus de pédagogie. Votre iPad peut servir à apprendre, pas à consommer.",
            15 => "Le futur du numérique sur Apple s'écrit en open source — et en équipe !",
            16 => "Lancer un défi Apple responsable : pouvez-vous passer une journée sans apps Big Tech ?",
            17 => "Chaque octet responsable compte. Le village numérique Apple vous attend pour résister ensemble.",
            18 => "Le code Swift peut aussi être poétique — surtout quand il sert la liberté collective.",
            19 => "Investissez dans l'autonomie numérique avant qu'elle devienne une denrée rare… même sur vos appareils Apple.",
            20 => "Votre Mac ou iPad a déjà tout pour être un outil du numérique durable : il ne manque que votre touche responsable !",
            21 => "Rejoignez la résistance numérique Apple : pour un futur où chaque clic sur votre iPhone compte !",
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