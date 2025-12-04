// ============================================
// GESTION DES INTERACTIONS AVEC LA CARTE
// ============================================

// Données des zones
const zoneData = {
    'Zone 1': {
        title: 'Zone 1 - Point d\'intérêt nord',
        description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cette zone représente le quartier nord du village, connu pour ses paysages magnifiques et son atmosphère paisible. Vous y trouverez de nombreux points d\'intérêt culturels et historiques.',
        color: '#DDEB9D'
    },
    'Zone 2': {
        title: 'Zone 2 - Place centrale',
        description: 'Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. La place centrale est le cœur battant du village, où se déroulent les événements communautaires et les rassemblements. C\'est un lieu de rencontre et d\'échange privilégié.',
        color: '#A0C878'
    },
    'Zone 3': {
        title: 'Zone 3 - Quartier est',
        description: 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris. Le quartier est est réputé pour son dynamisme et son innovation. C\'est ici que se trouvent les espaces de coworking et les ateliers créatifs du village.',
        color: '#27667B'
    },
    'Zone 4': {
        title: 'Zone 4 - Point spécial',
        description: 'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum. Cette zone spéciale abrite des installations uniques et des projets expérimentaux. C\'est un espace dédié à l\'apprentissage et à la découverte.',
        color: '#A0C878'
    }
};

// Fonction appelée lors du clic sur une zone
function handleMapClick(zoneName) {
    const infoSection = document.getElementById('info-section');
    const infoContent = document.getElementById('info-content');
    const data = zoneData[zoneName];

    if (data) {
        infoContent.innerHTML = `
            <strong style="color: ${data.color}; font-size: 1.2rem; display: block; margin-bottom: 0.5rem;">
                ${data.title}
            </strong>
            ${data.description}
        `;

        infoSection.classList.add('active');
        infoSection.style.animation = 'none';
        setTimeout(() => {
            infoSection.style.animation = 'pulse 0.5s ease';
        }, 10);

        infoSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        console.log(`✨ Zone cliquée: ${zoneName}`);
    }
}

// Animation de pulsation
const pulseStyle = document.createElement('style');
pulseStyle.textContent = `
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.02); }
        100% { transform: scale(1); }
    }
`;
document.head.appendChild(pulseStyle);

// ============================================
// GESTION DU TOOLTIP
// ============================================

document.addEventListener('DOMContentLoaded', () => {
    const areas = document.querySelectorAll('area');
    const tooltip = document.getElementById('map-tooltip');
    const mapContainer = document.querySelector('.map-container');

    areas.forEach(area => {
        area.addEventListener('mouseenter', (e) => {
            const title = area.getAttribute('title');
            tooltip.textContent = title;
            tooltip.classList.add('active');
        });

        area.addEventListener('mousemove', (e) => {
            const rect = mapContainer.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            tooltip.style.left = (x + 15) + 'px';
            tooltip.style.top = (y + 15) + 'px';
        });

        area.addEventListener('mouseleave', () => {
            tooltip.classList.remove('active');
        });

        area.addEventListener('mouseenter', () => {
            area.style.cursor = 'pointer';
        });
    });
});

// ============================================
// EFFET DE ZOOM
// ============================================

const mapImage = document.getElementById('village-map-image');
let isZoomed = false;

if (mapImage) {
    mapImage.addEventListener('dblclick', (e) => {
        e.preventDefault();
        if (!isZoomed) {
            mapImage.style.transform = 'scale(1.5)';
            mapImage.style.cursor = 'zoom-out';
            isZoomed = true;
        } else {
            mapImage.style.transform = 'scale(1)';
            mapImage.style.cursor = 'zoom-in';
            isZoomed = false;
        }
    });
}

// ============================================
// OVERLAYS AVEC FLOU SUR LES ZONES
// ============================================

function createZoneOverlays() {
    const mapContainer = document.querySelector('.map-container');
    const areas = document.querySelectorAll('area');
    const mapImage = document.getElementById('village-map-image');

    if (!mapImage || !mapImage.complete) {
        console.log('⏳ Image pas encore chargée');
        return;
    }

    console.log('🎨 Création des overlays avec flou...');

    areas.forEach((area, index) => {
        const shape = area.getAttribute('shape');
        const coords = area.getAttribute('coords').split(',').map(Number);

        let left, top, width, height;

        if (shape === 'rect') {
            left = coords[0];
            top = coords[1];
            width = coords[2] - coords[0];
            height = coords[3] - coords[1];
        } else if (shape === 'circle') {
            const radius = coords[2];
            left = coords[0] - radius;
            top = coords[1] - radius;
            width = radius * 2;
            height = radius * 2;
        } else if (shape === 'poly') {
            let minX = Math.min(...coords.filter((_, i) => i % 2 === 0));
            let maxX = Math.max(...coords.filter((_, i) => i % 2 === 0));
            let minY = Math.min(...coords.filter((_, i) => i % 2 === 1));
            let maxY = Math.max(...coords.filter((_, i) => i % 2 === 1));
            left = minX;
            top = minY;
            width = maxX - minX;
            height = maxY - minY;
        }

        // Canvas pour le flou
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        // Obtenir les dimensions réelles de l'image affichée
        const imgRect = mapImage.getBoundingClientRect();
        const containerRect = mapContainer.getBoundingClientRect();

        // Calculer l'offset de l'image par rapport au conteneur
        const offsetX = imgRect.left - containerRect.left;
        const offsetY = imgRect.top - containerRect.top;

        // Calculer le ratio entre l'image affichée et l'image naturelle
        const scaleX = imgRect.width / mapImage.naturalWidth;
        const scaleY = imgRect.height / mapImage.naturalHeight;

        // Log de debug détaillé
        if (index === 0) {
            console.log('📐 Debug complet:', {
                'Image naturelle': `${mapImage.naturalWidth}x${mapImage.naturalHeight}`,
                'Image affichée': `${imgRect.width.toFixed(1)}x${imgRect.height.toFixed(1)}`,
                'Image position page': `left:${imgRect.left.toFixed(1)}, top:${imgRect.top.toFixed(1)}`,
                'Container position page': `left:${containerRect.left.toFixed(1)}, top:${containerRect.top.toFixed(1)}`,
                'Offset calculé': `${offsetX.toFixed(1)}, ${offsetY.toFixed(1)}`,
                'Ratio': `${scaleX.toFixed(3)}x${scaleY.toFixed(3)}`,
                'Coords originales': `${left},${top} ${width}x${height}`,
                'Coords finales': `${(left * scaleX + offsetX).toFixed(1)},${(top * scaleY + offsetY).toFixed(1)}`
            });
            console.log('🔍 Vérification: La zone devrait être à', {
                'X calculé': (left * scaleX + offsetX).toFixed(1),
                'Y calculé': (top * scaleY + offsetY).toFixed(1),
                'Largeur': (width * scaleX).toFixed(1),
                'Hauteur': (height * scaleY).toFixed(1)
            });
        }

        // Ajuster les coordonnées selon le ratio ET l'offset
        const scaledLeft = left * scaleX + offsetX;
        const scaledTop = top * scaleY + offsetY;
        const scaledWidth = width * scaleX;
        const scaledHeight = height * scaleY;

        // Dimensions exactes de la zone affichée
        canvas.width = scaledWidth;
        canvas.height = scaledHeight;
        canvas.style.position = 'absolute';
        canvas.style.left = scaledLeft + 'px';
        canvas.style.top = scaledTop + 'px';
        canvas.style.pointerEvents = 'none';
        canvas.style.opacity = '0';
        canvas.style.transition = 'opacity 0.4s ease';
        canvas.style.zIndex = '5';
        canvas.style.filter = 'blur(2px)';

        if (shape === 'circle') {
            canvas.style.borderRadius = '50%';
        }

        try {
            // Dessiner exactement la portion d'image correspondante
            ctx.drawImage(
                mapImage,
                left, top, width, height,           // Source: coordonnées dans l'image naturelle
                0, 0, scaledWidth, scaledHeight     // Destination: taille affichée
            );
        } catch (e) {
            console.error('Erreur canvas:', e);
        }

        // Overlay coloré
        const overlay = document.createElement('div');
        overlay.className = 'zone-overlay';
        overlay.style.position = 'absolute';
        overlay.style.left = scaledLeft + 'px';
        overlay.style.top = scaledTop + 'px';
        overlay.style.width = scaledWidth + 'px';
        overlay.style.height = scaledHeight + 'px';
        overlay.style.pointerEvents = 'none';
        overlay.style.transition = 'all 0.4s ease';
        overlay.style.opacity = '0';
        overlay.style.border = '3px solid ' + Object.values(zoneData)[index].color;
        overlay.style.boxShadow = '0 0 25px ' + Object.values(zoneData)[index].color;
        overlay.style.zIndex = '10';

        if (shape === 'circle') {
            overlay.style.borderRadius = '50%';
        }

        mapContainer.appendChild(canvas);
        mapContainer.appendChild(overlay);

        // Afficher au survol
        area.addEventListener('mouseenter', () => {
            canvas.style.opacity = '0.9';
            overlay.style.opacity = '1';
            overlay.style.backgroundColor = Object.values(zoneData)[index].color + '30';
            overlay.style.boxShadow = '0 0 40px ' + Object.values(zoneData)[index].color;
            overlay.style.transform = 'scale(1.05)';
        });

        area.addEventListener('mouseleave', () => {
            canvas.style.opacity = '0';
            overlay.style.opacity = '0';
            overlay.style.transform = 'scale(1)';
        });
    });

    console.log('✅ Overlays créés avec flou !');
}

// Attendre le chargement de l'image
if (mapImage) {
    mapImage.addEventListener('load', () => {
        console.log('✅ Image chargée');
        createZoneOverlays();
    });

    if (mapImage.complete) {
        console.log('✅ Image déjà chargée');
        createZoneOverlays();
    }
}

// ============================================
// MARQUEURS VISUELS
// ============================================

function addZoneMarkers() {
    const mapContainer = document.querySelector('.map-container');
    const areas = document.querySelectorAll('area');
    const mapImage = document.getElementById('village-map-image');

    if (!mapImage) return;

    // Obtenir les dimensions réelles de l'image affichée
    const imgRect = mapImage.getBoundingClientRect();
    const containerRect = mapContainer.getBoundingClientRect();

    // Calculer l'offset de l'image par rapport au conteneur
    const offsetX = imgRect.left - containerRect.left;
    const offsetY = imgRect.top - containerRect.top;

    // Calculer le ratio de redimensionnement
    const scaleX = imgRect.width / mapImage.naturalWidth;
    const scaleY = imgRect.height / mapImage.naturalHeight;

    areas.forEach((area, index) => {
        const shape = area.getAttribute('shape');
        const coords = area.getAttribute('coords').split(',').map(Number);

        const marker = document.createElement('div');
        marker.className = 'zone-marker';
        marker.style.position = 'absolute';
        marker.style.width = '12px';
        marker.style.height = '12px';
        marker.style.borderRadius = '50%';
        marker.style.backgroundColor = Object.values(zoneData)[index].color;
        marker.style.boxShadow = '0 0 15px ' + Object.values(zoneData)[index].color;
        marker.style.pointerEvents = 'none';
        marker.style.animation = 'markerPulse 2s ease-in-out infinite';
        marker.style.animationDelay = (index * 0.3) + 's';
        marker.style.zIndex = '50';

        // Calculer la position du centre avec le ratio ET l'offset
        if (shape === 'rect') {
            const centerX = ((coords[0] + coords[2]) / 2) * scaleX + offsetX;
            const centerY = ((coords[1] + coords[3]) / 2) * scaleY + offsetY;
            marker.style.left = (centerX - 6) + 'px';
            marker.style.top = (centerY - 6) + 'px';
        } else if (shape === 'circle') {
            const centerX = coords[0] * scaleX + offsetX;
            const centerY = coords[1] * scaleY + offsetY;
            marker.style.left = (centerX - 6) + 'px';
            marker.style.top = (centerY - 6) + 'px';
        } else if (shape === 'poly') {
            let sumX = 0, sumY = 0, count = 0;
            for (let i = 0; i < coords.length; i += 2) {
                sumX += coords[i];
                sumY += coords[i + 1];
                count++;
            }
            const centerX = (sumX / count) * scaleX + offsetX;
            const centerY = (sumY / count) * scaleY + offsetY;
            marker.style.left = (centerX - 6) + 'px';
            marker.style.top = (centerY - 6) + 'px';
        }

        mapContainer.appendChild(marker);
    });
}

const markerStyle = document.createElement('style');
markerStyle.textContent = `
    @keyframes markerPulse {
        0%, 100% {
            transform: scale(1);
            opacity: 0.8;
        }
        50% {
            transform: scale(1.5);
            opacity: 1;
        }
    }
`;
document.head.appendChild(markerStyle);

if (mapImage) {
    mapImage.addEventListener('load', () => {
        addZoneMarkers();
    });

    if (mapImage.complete) {
        addZoneMarkers();
    }
}

console.log('🗺️ Interactions de carte chargées !');
console.log('💡 Effet de flou : canvas flou sur chaque zone au survol');
