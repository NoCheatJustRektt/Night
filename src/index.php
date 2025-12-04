<?php
session_start();
if (!isset($_SESSION['game']['tetris'])) {
    $_SESSION['game']['tetris'] = false;
    $_SESSION['game']['snake'] = false;
    $_SESSION['game']['pacman'] = false;
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
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS de secours si le fichier externe ne charge pas */
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #143D60 0%, #27667B 50%, #143D60 100%);
            color: #DDEB9D;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1400px;
            margin: 0 auto;
        }
        .page-header {
            text-align: center;
            padding: 2rem 0;
        }
        .page-header h1 {
            color: #DDEB9D;
            font-size: 2.5rem;
            text-shadow: 0 0 20px rgba(221, 235, 157, 0.5);
        }
        .context-section, .map-section, .info-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(221, 235, 157, 0.2);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .context-section h2, .map-legend h3, .info-section h3 {
            color: #DDEB9D;
            margin-bottom: 1rem;
        }
        .context-section p, .info-section p {
            color: rgba(221, 235, 157, 0.9);
            line-height: 1.8;
        }
        .map-container {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        #village-map-image {
            width: 100%;
            height: auto;
            display: block;
        }
        .map-legend ul {
            list-style: none;
            padding: 0;
        }
        .map-legend li {
            color: rgba(221, 235, 157, 0.9);
            padding: 0.5rem 0;
        }
        .legend-marker {
            display: inline-block;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .legend-marker.zone1 { background: #DDEB9D; }
        .legend-marker.zone2 { background: #A0C878; }
        .legend-marker.zone3 { background: #27667B; }
        .legend-marker.zone4 { background: linear-gradient(135deg, #A0C878, #DDEB9D); }
        .debug-info {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid red;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 10px;
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Particules d'arrière-plan -->
    <canvas id="particles"></canvas>
    
    <div class="container">
        <!-- Titre principal -->
        <header class="page-header">
            <h1 class="glitch" data-text="Carte du Village">Carte du Village</h1>
            <p class="header-subtitle"><span id="typewriter"></span><span class="cursor-sub">|</span></p>
        </header>

        <!-- Zone de contexte -->
        <section class="context-section">
            <h2>Explorez No Fork Village</h2>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            </p>
            <p>
                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. 
                Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.
            </p>
        </section>

        <!-- Image Map Interactive -->
        <section class="map-section">
            <div class="map-container">
                <img src="../images/map1.jpg" 
                     alt="Carte interactive du village" 
                     usemap="#village-map"
                     id="village-map-image"
                     onerror="this.style.border='3px solid red'; this.alt='❌ Image non trouvée: ../images/map1.jpg';">
                
                <map name="village-map">
                    
                    <area shape="rect" 
                          coords="300,50,700,200" 
                          href="#zone1" 
                          alt="Zone 1"
                          title="Zone 1 - Cliquez pour explorer"
                          onclick="handleMapClick('Zone 1'); return false;">
                    
                    
                    <area shape="circle" 
                          coords="400,300,80" 
                          href="#zone2" 
                          alt="Zone 2"
                          title="Zone 2 - Place centrale"
                          onclick="handleMapClick('Zone 2'); return false;">
                    
                    
                    <area shape="rect" 
                          coords="450,300,850,550" 
                          href="#Centre Ville" 
                          alt="Centre Ville"
                          title="Centre ville"
                          onclick="handleMapClick('Centre ville'); return false;">
                    
                    
                    <area shape="circle" 
                          coords="250,600,150" 
                          href="#zone4" 
                          alt="Zone 4"
                          title="Dojo"
                          onclick="handleMapClick('Dojo'); return false;">
                </map>
                
                <!-- Indicateur de zone survolée -->
                <div id="map-tooltip" class="map-tooltip"></div>
            </div>
            
            <!-- Légende -->
            <div class="map-legend">
                <h3>🗺️ Zones Interactives</h3>
                <ul>
                    <li><span class="legend-marker zone1"></span> Zone 1 - Point d'intérêt nord</li>
                    <li><span class="legend-marker zone2"></span> Zone 2 - Place centrale</li>
                    <li><span class="legend-marker zone3"></span> Zone 3 - Centre ville</li>
                    <li><span class="legend-marker zone4"></span> Zone 4 - Dojo</li>
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

    <script src="animations.js"></script>
    <script src="map-interactions.js"></script>
    
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
    </script>
</body>
</html>
