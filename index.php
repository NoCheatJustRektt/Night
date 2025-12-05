<?php
session_start();
if (!isset($_SESSION['game']) || !is_array($_SESSION['game'])) {
    $_SESSION['game'] = [];
}
if (!isset($_SESSION['game']['info']) || !is_array($_SESSION['game']['info'])) {
    $_SESSION['game']['info'] = ['state' => false, 'color' => 'red'];
}
if (!isset($_SESSION['game']['tetris']) || !is_array($_SESSION['game']['tetris'])) {
    $_SESSION['game']['tetris'] = ['state' => false, 'color' => 'red'];
}
if (!isset($_SESSION['game']['snake']) || !is_array($_SESSION['game']['snake'])) {
    $_SESSION['game']['snake'] = ['state' => false, 'color' => 'red'];
}
if (!isset($_SESSION['game']['pacman']) || !is_array($_SESSION['game']['pacman'])) {
    $_SESSION['game']['pacman'] = ['state' => false, 'color' => 'red'];
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Fork Village - Carte Interactive</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>


    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="public/css/chatbot.css">
    <script src="public/js/chatbot.js" defer></script>
    
</head>
<body>

    <!-- Particules d'arrière-plan -->
    <canvas id="particles"></canvas>
    
    <div class="container" id="container1">
        <!-- Titre principal -->
        <header class="page-header">
            <h1 class="glitch" data-text="Carte du Village">Carte du Village</h1>
            <p class="header-subtitle"><span id="typewriter"></span><span class="cursor-sub">|</span></p>
        </header>
        <?php include "src/view/chatbot-widget.php"; ?>
        <!-- Zone de contexte -->
        <section class="context-section">
            <h2>Explorez No Fork Village</h2>
            <p>
                Bienvenue à No Fork Village, un lieu où l'aventure et la découverte vous attendent à chaque coin de rue. 
                Utilisez la carte interactive ci-dessous pour explorer les différentes zones du village. 
                Cliquez sur les zones pour en savoir plus et interagir avec des jeux passionnants intégrés directement dans la carte.
            </p>
        </section>

        <!-- Image Map Interactive -->
        <section class="map-section">
            <div class="map-container">
                <img src="images/map1.jpg" 
                     alt="Carte interactive du village" 
                     usemap="#village-map"
                     id="village-map-image"
                     onerror="this.style.border='3px solid red'; this.alt='❌ Image non trouvée: ../images/map1.jpg';">
                
                <map name="village-map">
                    
                    <area shape="rect" 
                          coords="360,30,660,170" 
                          href="#infos" 
                          alt="infos"
                          title="Informations"
                          onclick="openGameModal('src/view/infos.php', 'Zone 1 - Informations'); return false;">
                    
                    
                    <area shape="circle" 
                          coords="250,170,80" 
                          href="#Port" 
                          alt="Port"
                          title="Port"
                          onclick="openGameModal('src/view/snake-widget.php', 'Zone 2 - Snake'); return false;">
                    
                    
                    <area shape="rect" 
                          coords="350,230,750,550" 
                          href="#Centre Ville" 
                          alt="Centre Ville"
                          title="Centre ville"
                          onclick="openGameModal('src/view/pong-widget.php', 'Zone 3 - Pong'); return false;">
                    
                    
                    <area shape="circle" 
                          coords="200,470,150" 
                          href="#Dojo" 
                          alt="Dojo"
                          title="Dojo"
                          onclick="openGameModal('src/model/tetris.php', 'Zone 4 - Tetris'); return false;">
                </map>
                
                <!-- Indicateur de zone survolée -->
                <div id="map-tooltip" class="map-tooltip"></div>
            </div>
            
            <!-- Légende -->
            <div class="map-legend">
                <h3>🗺️ Zones Interactives</h3>
                <ul>
                    <li><span class="legend-marker zone1" style="background: <?=$_SESSION['game']['info']['color']?>"></span> Zone d'information</li>
                    <li><span class="legend-marker zone2" style="background: <?=$_SESSION['game']['tetris']['color']?>"></span> Port</li>
                    <li><span class="legend-marker zone3" style="background: <?=$_SESSION['game']['snake']['color']?>"></span> Centre ville</li>
                    <li><span class="legend-marker zone4" style="background: <?=$_SESSION['game']['pacman']['color']?>"></span> Dojo</li>
                </ul>
                <p class="legend-hint">💡 Survolez et cliquez sur les zones de la carte pour les explorer</p>
            </div>
        </section>

        <!-- Zone d'information dynamique -->
        <section class="info-section" id="info-section">
            <h3>Informations</h3>
            <p id="info-content">Cliquez sur une zone de la carte pour afficher ses informations.</p>
        </section>
    </div>

    <script src="public/js/animations.js"></script>
    <script src="public/js/map-interactions.js"></script>
    
    <script>
        // Debug: Vérifier si les scripts se chargent
        console.log('✅ Script inline chargé');
        console.log('📁 Chemin actuel:', window.location.href);
        
        // Fonction de secours si map-interactions.js ne charge pas
        if (typeof handleMapClick === 'undefined') {
            console.warn('⚠️ map-interactions.js non chargé, utilisation de la fonction de secours');
            window.handleMapClick = function(zoneName) {
                const infoContent = document.getElementById('info-content');
                infoContent.innerHTML = `<strong>Zone cliquée: ${zoneName}</strong><br>Les scripts externes ne sont pas chargés. Vérifiez les chemins.`;
                alert('Zone cliquée: ' + zoneName);
            };
        }
        
        // Vérifier le chargement de l'image
        const img = document.getElementById('village-map-image');
        img.addEventListener('load', function() {
            console.log('✅ Image chargée avec succès');
        });
        img.addEventListener('error', function() {
            console.error('❌ Erreur de chargement de l\'image');
            this.style.minHeight = '400px';
            this.style.background = 'rgba(255,0,0,0.2)';
            this.style.display = 'flex';
            this.style.alignItems = 'center';
            this.style.justifyContent = 'center';
        });


        function openGameModal(filePath, modalTitle = 'Contenu') {

            const modal = new bootstrap.Modal(document.getElementById('gameModal'));
            document.getElementById('gameModalLabel').textContent = modalTitle;

            // Loader
            document.getElementById('gameModalBody').innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
            `;

            modal.show();

            // Chargement du fichier
            fetch(filePath)
                .then(response => {
                    if (!response.ok) throw new Error('Erreur de chargement');
                    return response.text();
                })
                .then(html => {

                    const container = document.getElementById('gameModalBody');
                    container.innerHTML = html;

                    // ⚠️ Extraire et exécuter les <script> contenus dans le fichier
                    const scripts = container.querySelectorAll("script");
                    scripts.forEach((oldScript) => {
                        const newScript = document.createElement("script");

                        if (oldScript.src) {
                            newScript.src = oldScript.src; // Script externe
                        } else {
                            newScript.textContent = oldScript.innerHTML; // Script inline
                        }

                        document.body.appendChild(newScript);
                        oldScript.remove();
                    });
                })
                .catch(error => {
                    document.getElementById('gameModalBody').innerHTML = `
                        <div class="alert alert-danger" role="alert">
                            ❌ Erreur lors du chargement: ${error.message}
                        </div>
                    `;
                });
        }
        
        // Fonction pour ajuster les coordonnées de la map
        function rescaleImageMap() {
            const img = document.getElementById('village-map-image');
            const map = document.querySelector('map[name="village-map"]');
            
            if (!img || !map || !img.complete) return;
            
            const areas = map.querySelectorAll('area');
            const scaleX = img.offsetWidth / img.naturalWidth;
            const scaleY = img.offsetHeight / img.naturalHeight;
            
            console.log('🔧 Rescaling map:', {
                'Natural': `${img.naturalWidth}x${img.naturalHeight}`,
                'Displayed': `${img.offsetWidth}x${img.offsetHeight}`,
                'Scale': `${scaleX.toFixed(3)}x${scaleY.toFixed(3)}`
            });
            
            areas.forEach(area => {
                const originalCoords = area.dataset.originalCoords || area.getAttribute('coords');
                
                if (!area.dataset.originalCoords) {
                    area.dataset.originalCoords = originalCoords;
                }
                
                const coords = originalCoords.split(',').map(Number);
                const scaledCoords = coords.map((coord, index) => {
                    return Math.round(coord * (index % 2 === 0 ? scaleX : scaleY));
                });
                
                area.setAttribute('coords', scaledCoords.join(','));
            });
        }
        
        // Appeler au chargement de l'image
        const mapImg = document.getElementById('village-map-image');
        if (mapImg) {
            mapImg.addEventListener('load', rescaleImageMap);
            if (mapImg.complete) rescaleImageMap();
        }
        
        window.addEventListener('resize', rescaleImageMap);
    </script>


    <!-- Modal générique pour les jeux et contenus -->
    <div class="modal fade" id="gameModal" tabindex="-1" aria-labelledby="gameModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="background: rgba(20, 61, 96, 0.95); border: 2px solid #DDEB9D;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(221, 235, 157, 0.3);">
                    <h5 class="modal-title" id="gameModalLabel" style="color: #DDEB9D;">Contenu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="gameModalBody" style="color: #DDEB9D; display: flex; justify-content: center; align-items: center; min-height: 300px;">
                    <div class="text-center">
                        <div class="spinner-border text-light" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Rafraîchir la page quand la modal est fermée
        const gameModalElement = document.getElementById('gameModal');
        if (gameModalElement) {
            gameModalElement.addEventListener('hidden.bs.modal', function () {
                console.log('🔄 Modal fermée, rafraîchissement de la page...');
                location.reload();
            });
        }
    </script>

    <div class="container2" id="container2" style="display: none;">
        <img src="images/map.png" />
        <header class="page-header">
            <h1 class="glitch" data-text="Félicitations !">Félicitations !</h1>
            <p class="header-subtitle">Vous avez terminé tous les défis ! 🎉</p>
        </header>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Fonction pour vérifier si toutes les zones sont vertes
        function checkAllZonesComplete() {
            const zones = ['zone1', 'zone2', 'zone3', 'zone4'];
            const allGreen = zones.every(zoneClass => {
                const marker = document.querySelector(`.legend-marker.${zoneClass}`);
                if (!marker) return false;
                const bgColor = marker.style.background.toLowerCase();
                return bgColor === 'green' || bgColor === 'rgb(0, 128, 0)';
            });
            
            if (allGreen) {
                console.log('🎉 Toutes les zones sont complétées !');
                // Faire disparaître container1 et apparaître container2
                document.getElementById('container1').style.display = 'none';
                document.getElementById('container2').style.display = 'block';
            }
        }
        
        // Fonction améliorée updateLegendColor qui vérifie après chaque mise à jour
        function updateLegendColor(zoneClass, color) {
            const legendMarker = document.querySelector(`.legend-marker.${zoneClass}`);
            if (legendMarker) {
                legendMarker.style.background = color;
                console.log(`🎨 Couleur de ${zoneClass} mise à jour en ${color}`);
                
                // Vérifier si toutes les zones sont complètes
                setTimeout(checkAllZonesComplete, 500);
            }
        }
        
        // Vérifier au chargement de la page
        window.addEventListener('load', checkAllZonesComplete);
    </script>
</body>
</html>
