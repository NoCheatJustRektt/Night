# 🗺️ Guide de Configuration de la Carte Interactive

## Vue d'ensemble
Ce guide explique comment personnaliser les zones cliquables de la carte interactive de No Fork Village.

## Structure de la Carte

### Fichiers principaux
- **index.php** - Structure HTML avec la balise `<map>`
- **map-interactions.js** - Logique JavaScript pour les interactions
- **style.css** - Styles visuels
- **images/map1.jpg** - Image de la carte

## Comment Ajouter/Modifier des Zones Cliquables

### 1. Définir les coordonnées de la zone

Dans `index.php`, trouvez la balise `<map name="village-map">`. Vous pouvez ajouter différents types de zones :

#### Zone Rectangulaire
```html
<area shape="rect" 
      coords="x1,y1,x2,y2" 
      href="#zone-name" 
      alt="Description"
      title="Titre affiché au survol"
      onclick="handleMapClick('Nom de la Zone'); return false;">
```
- `x1,y1` = coin supérieur gauche
- `x2,y2` = coin inférieur droit

#### Zone Circulaire
```html
<area shape="circle" 
      coords="x,y,rayon" 
      href="#zone-name" 
      alt="Description"
      title="Titre affiché au survol"
      onclick="handleMapClick('Nom de la Zone'); return false;">
```
- `x,y` = centre du cercle
- `rayon` = rayon en pixels

#### Zone Polygonale (forme personnalisée)
```html
<area shape="poly" 
      coords="x1,y1,x2,y2,x3,y3,x4,y4,..." 
      href="#zone-name" 
      alt="Description"
      title="Titre affiché au survol"
      onclick="handleMapClick('Nom de la Zone'); return false;">
```
- Liste de paires x,y pour chaque point du polygone

### 2. Ajouter les données de la zone

Dans `map-interactions.js`, ajoutez les informations de votre zone dans l'objet `zoneData` :

```javascript
const zoneData = {
    'Nom de la Zone': {
        title: 'Titre complet de la zone',
        description: 'Description détaillée qui s\'affichera lors du clic',
        color: '#DDEB9D' // Couleur en hexadécimal
    }
};
```

### 3. Mettre à jour la légende

Dans `index.php`, ajoutez un élément dans la liste de la légende :

```html
<li>
    <span class="legend-marker zone-custom"></span> 
    Nom de la Zone - Description courte
</li>
```

Puis dans `style.css`, définissez la couleur du marqueur :

```css
.legend-marker.zone-custom {
    background: #DDEB9D; /* Votre couleur */
}
```

## Outils pour Trouver les Coordonnées

### Méthode 1 : Utiliser un éditeur d'image map en ligne
- [Image Map Generator](https://www.image-map.net/)
- Uploadez votre image
- Dessinez les zones
- Copiez le code HTML généré

### Méthode 2 : Utiliser les outils de développement du navigateur
1. Ouvrez la page dans le navigateur
2. Ouvrez les DevTools (F12)
3. Dans la console, tapez :
```javascript
document.getElementById('village-map-image').addEventListener('click', (e) => {
    const rect = e.target.getBoundingClientRect();
    const x = Math.round(e.clientX - rect.left);
    const y = Math.round(e.clientY - rect.top);
    console.log(`Coordonnées: ${x}, ${y}`);
});
```
4. Cliquez sur l'image pour obtenir les coordonnées

## Palette de Couleurs du Thème

Utilisez ces couleurs pour rester cohérent avec le design :

- **#143D60** - Bleu foncé (fond principal)
- **#27667B** - Bleu-vert (accents)
- **#A0C878** - Vert clair (highlights)
- **#DDEB9D** - Vert pastel (néon/lueur)

## Exemple Complet

### Ajouter une nouvelle zone "Bibliothèque"

1. **Dans index.php** (dans la balise `<map>`) :
```html
<area shape="rect" 
      coords="300,200,450,300" 
      href="#bibliotheque" 
      alt="Bibliothèque"
      title="Bibliothèque du Village"
      onclick="handleMapClick('Bibliothèque'); return false;">
```

2. **Dans map-interactions.js** (dans `zoneData`) :
```javascript
'Bibliothèque': {
    title: 'Bibliothèque du Village',
    description: 'Un espace de savoir et de partage. La bibliothèque contient des milliers de ressources pour apprendre et grandir ensemble.',
    color: '#A0C878'
}
```

3. **Dans index.php** (dans la légende) :
```html
<li>
    <span class="legend-marker bibliotheque"></span> 
    Bibliothèque - Centre de ressources
</li>
```

4. **Dans style.css** :
```css
.legend-marker.bibliotheque {
    background: #A0C878;
}
```

## Fonctionnalités Interactives

### Effets Visuels Automatiques
- ✨ **Tooltip** au survol des zones
- 🎯 **Marqueurs animés** sur chaque zone
- 💫 **Overlay de surbrillance** au survol
- 🌊 **Pulsation périodique** toutes les 10 secondes
- 🔍 **Zoom** au double-clic sur l'image

### Personnalisation des Animations

Pour modifier la vitesse de pulsation des marqueurs, dans `map-interactions.js` :
```javascript
// Changer 10000 (10 secondes) par la valeur souhaitée en millisecondes
setInterval(pulseZones, 10000);
```

## Conseils

1. **Testez vos coordonnées** : Assurez-vous que les zones ne se chevauchent pas
2. **Nommez clairement** : Utilisez des noms descriptifs pour les zones
3. **Couleurs contrastées** : Choisissez des couleurs différentes pour chaque zone
4. **Descriptions utiles** : Écrivez des descriptions informatives et engageantes
5. **Responsive** : Les coordonnées sont en pixels absolus, testez sur différentes tailles d'écran

## Dépannage

### La zone ne réagit pas au clic
- Vérifiez que le nom dans `onclick="handleMapClick('...')"` correspond exactement au nom dans `zoneData`
- Assurez-vous que `return false;` est présent dans l'attribut onclick

### Les coordonnées sont incorrectes
- Utilisez un outil de génération d'image map pour obtenir les bonnes coordonnées
- Vérifiez que l'image n'est pas redimensionnée par le CSS

### Le tooltip ne s'affiche pas
- Vérifiez que l'attribut `title` est bien renseigné
- Assurez-vous que `map-interactions.js` est bien chargé

## Support

Pour toute question ou problème, consultez la documentation JavaScript dans les commentaires de `map-interactions.js`.
