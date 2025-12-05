// ============================================
// ANIMATION TYPEWRITER DANS LE HEADER
// ============================================
const phrases = [
    "Bienvenue dans ton village numérique ✨",
    "Apprends, partage, grandis ensemble 🌱",
    "Une communauté bienveillante t'attend 🤝",
    "Ton espace cosy pour progresser 💫"
];

let phraseIndex = 0;
let charIndex = 0;
let isDeleting = false;
let typingSpeed = 100;

function typeWriter() {
    const typewriterElement = document.getElementById('typewriter');
    const currentPhrase = phrases[phraseIndex];

    if (!isDeleting && charIndex < currentPhrase.length) {
        // Écriture
        typewriterElement.textContent = currentPhrase.substring(0, charIndex + 1);
        charIndex++;
        typingSpeed = 100;
    } else if (isDeleting && charIndex > 0) {
        // Suppression
        typewriterElement.textContent = currentPhrase.substring(0, charIndex - 1);
        charIndex--;
        typingSpeed = 50;
    } else if (!isDeleting && charIndex === currentPhrase.length) {
        // Pause avant de supprimer
        typingSpeed = 2000;
        isDeleting = true;
    } else if (isDeleting && charIndex === 0) {
        // Passer à la phrase suivante
        isDeleting = false;
        phraseIndex = (phraseIndex + 1) % phrases.length;
        typingSpeed = 500;
    }

    setTimeout(typeWriter, typingSpeed);
}

// Démarrer l'animation typewriter
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(typeWriter, 1000);
});

// ============================================
// ANIMATION DE PARTICULES EN ARRIÈRE-PLAN
// ============================================
const canvas = document.getElementById('particles');
const ctx = canvas.getContext('2d');

// Redimensionner le canvas
function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}

resizeCanvas();
window.addEventListener('resize', resizeCanvas);

// Classe Particule
class Particle {
    constructor() {
        this.reset();
        this.y = Math.random() * canvas.height;
        this.opacity = Math.random() * 0.5 + 0.2;
    }

    reset() {
        this.x = Math.random() * canvas.width;
        this.y = -10;
        this.speed = Math.random() * 1 + 0.5;
        this.size = Math.random() * 2 + 1;
        this.opacity = Math.random() * 0.5 + 0.2;

        // Couleurs de la palette
        const colors = [
            'rgba(221, 235, 157, ', // #DDEB9D
            'rgba(160, 200, 120, ', // #A0C878
            'rgba(39, 102, 123, '   // #27667B
        ];
        this.color = colors[Math.floor(Math.random() * colors.length)];
    }

    update() {
        this.y += this.speed;

        // Oscillation horizontale
        this.x += Math.sin(this.y * 0.01) * 0.5;

        // Réinitialiser si hors écran
        if (this.y > canvas.height) {
            this.reset();
        }
    }

    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = this.color + this.opacity + ')';
        ctx.fill();

        // Effet de lueur
        ctx.shadowBlur = 10;
        ctx.shadowColor = this.color + this.opacity + ')';
    }
}

// Créer les particules
const particlesArray = [];
const numberOfParticles = 100;

for (let i = 0; i < numberOfParticles; i++) {
    particlesArray.push(new Particle());
}

// Animation des particules
function animateParticles() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    particlesArray.forEach(particle => {
        particle.update();
        particle.draw();
    });

    // Dessiner des lignes entre particules proches
    connectParticles();

    requestAnimationFrame(animateParticles);
}

// Connecter les particules proches
function connectParticles() {
    for (let i = 0; i < particlesArray.length; i++) {
        for (let j = i + 1; j < particlesArray.length; j++) {
            const dx = particlesArray[i].x - particlesArray[j].x;
            const dy = particlesArray[i].y - particlesArray[j].y;
            const distance = Math.sqrt(dx * dx + dy * dy);

            if (distance < 100) {
                ctx.beginPath();
                ctx.strokeStyle = `rgba(221, 235, 157, ${0.1 * (1 - distance / 100)})`;
                ctx.lineWidth = 0.5;
                ctx.moveTo(particlesArray[i].x, particlesArray[i].y);
                ctx.lineTo(particlesArray[j].x, particlesArray[j].y);
                ctx.stroke();
            }
        }
    }
}

// Démarrer l'animation des particules
animateParticles();

// ============================================
// EFFET DE PARALLAXE AU SCROLL
// ============================================
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const heroSection = document.querySelector('.hero-section');

    if (heroSection) {
        heroSection.style.transform = `translateY(${scrolled * 0.5}px)`;
    }
});

// ============================================
// EFFET DE SUIVI DE SOURIS SUR LES CARTES
// ============================================
const featureCards = document.querySelectorAll('.feature-card');

featureCards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        const rotateX = (y - centerY) / 10;
        const rotateY = (centerX - x) / 10;

        card.style.transform = `
            translateY(-15px) 
            scale(1.05) 
            rotateX(${rotateX}deg) 
            rotateY(${rotateY}deg)
        `;
    });

    card.addEventListener('mouseleave', () => {
        card.style.transform = '';
    });
});

// ============================================
// EFFET DE RIPPLE AU CLIC SUR LE BOUTON CTA
// ============================================
const ctaButton = document.querySelector('.cta-button');

if (ctaButton) {
    ctaButton.addEventListener('click', (e) => {
        e.preventDefault();

        const ripple = document.createElement('span');
        const rect = ctaButton.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.style.position = 'absolute';
        ripple.style.borderRadius = '50%';
        ripple.style.background = 'rgba(255, 255, 255, 0.6)';
        ripple.style.transform = 'scale(0)';
        ripple.style.animation = 'ripple 0.6s ease-out';
        ripple.style.pointerEvents = 'none';

        ctaButton.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
}

// Animation CSS pour le ripple
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ============================================
// ANIMATION D'APPARITION AU SCROLL
// ============================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'slideInFromBottom 0.8s ease-out forwards';
        }
    });
}, observerOptions);

// Observer les cartes de features
featureCards.forEach(card => {
    observer.observe(card);
});

// ============================================
// EFFET DE CURSEUR PERSONNALISÉ (OPTIONNEL)
// ============================================
const cursor = document.createElement('div');
cursor.style.cssText = `
    width: 20px;
    height: 20px;
    border: 2px solid rgba(221, 235, 157, 0.5);
    border-radius: 50%;
    position: fixed;
    pointer-events: none;
    z-index: 9999;
    transition: transform 0.2s ease;
    display: none;
`;
document.body.appendChild(cursor);

document.addEventListener('mousemove', (e) => {
    cursor.style.display = 'block';
    cursor.style.left = e.clientX - 10 + 'px';
    cursor.style.top = e.clientY - 10 + 'px';
});

// Agrandir le curseur sur les éléments interactifs
const interactiveElements = document.querySelectorAll('a, button, .feature-card');
interactiveElements.forEach(el => {
    el.addEventListener('mouseenter', () => {
        cursor.style.transform = 'scale(1.5)';
        cursor.style.borderColor = 'rgba(160, 200, 120, 0.8)';
    });

    el.addEventListener('mouseleave', () => {
        cursor.style.transform = 'scale(1)';
        cursor.style.borderColor = 'rgba(221, 235, 157, 0.5)';
    });
});

console.log('🚀 Animations futuristes chargées avec succès !');
