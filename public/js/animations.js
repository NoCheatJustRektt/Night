// ============================================
// TYPEWRITER
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

const typewriterElement = document.getElementById('typewriter');

function typeWriter() {
    const currentPhrase = phrases[phraseIndex];

    if (!isDeleting && charIndex < currentPhrase.length) {
        typewriterElement.textContent = currentPhrase.substring(0, charIndex + 1);
        charIndex++;
        typingSpeed = 100;
    } else if (isDeleting && charIndex > 0) {
        typewriterElement.textContent = currentPhrase.substring(0, charIndex - 1);
        charIndex--;
        typingSpeed = 50;
    } else if (!isDeleting && charIndex === currentPhrase.length) {
        typingSpeed = 2000;
        isDeleting = true;
    } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        phraseIndex = (phraseIndex + 1) % phrases.length;
        typingSpeed = 500;
    }

    setTimeout(() => requestAnimationFrame(typeWriter), typingSpeed);
}

document.addEventListener('DOMContentLoaded', () => setTimeout(typeWriter, 1000));

// ============================================
// PARTICULES
// ============================================
const canvas = document.getElementById('particles');
const ctx = canvas.getContext('2d');

function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

class Particle {
    constructor() {
        this.reset(true);
    }

    reset(initial = false) {
        this.x = Math.random() * canvas.width;
        this.y = initial ? Math.random() * canvas.height : -10;
        this.speed = Math.random() * 1 + 0.5;
        this.size = Math.random() * 2 + 1;
        this.opacity = Math.random() * 0.5 + 0.2;
        const colors = ['rgba(221,235,157,', 'rgba(160,200,120,', 'rgba(39,102,123,'];
        this.color = colors[Math.floor(Math.random() * colors.length)];
    }

    update() {
        this.y += this.speed;
        this.x += Math.sin(this.y * 0.01) * 0.5;
        if (this.y > canvas.height) this.reset();
    }

    draw() {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fillStyle = this.color + this.opacity + ')';
        ctx.shadowBlur = 3;
        ctx.shadowColor = this.color + this.opacity + ')';
        ctx.fill();
    }
}

const particlesArray = Array.from({ length: 50 }, () => new Particle());

function animateParticles() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    for (const p of particlesArray) {
        p.update();
        p.draw();
    }

    // Lignes entre particules proches
    const maxDist = 80;
    for (let i = 0; i < particlesArray.length; i++) {
        const p1 = particlesArray[i];
        for (let j = i + 1; j < particlesArray.length; j++) {
            const p2 = particlesArray[j];
            const dx = p1.x - p2.x;
            const dy = p1.y - p2.y;
            const distSq = dx*dx + dy*dy;
            if (distSq < maxDist*maxDist) {
                ctx.strokeStyle = `rgba(221,235,157,${0.08*(1-Math.sqrt(distSq)/maxDist)})`;
                ctx.lineWidth = 0.5;
                ctx.beginPath();
                ctx.moveTo(p1.x, p1.y);
                ctx.lineTo(p2.x, p2.y);
                ctx.stroke();
            }
        }
    }

    requestAnimationFrame(animateParticles);
}
animateParticles();

// ============================================
// PARALLAXE AU SCROLL
// ============================================
const heroSection = document.querySelector('.hero-section');
window.addEventListener('scroll', () => {
    if (heroSection) heroSection.style.transform = `translateY(${window.pageYOffset * 0.5}px)`;
});

// ============================================
// MOUSEMOVE SUR CARDS
// ============================================
const featureCards = document.querySelectorAll('.feature-card');
featureCards.forEach(card => {
    card.addEventListener('mousemove', e => {
        const rect = card.getBoundingClientRect();
        const rotateX = (e.clientY - rect.top - rect.height/2) / 10;
        const rotateY = (rect.width/2 - (e.clientX - rect.left)) / 10;
        card.style.transform = `translateY(-15px) scale(1.05) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
    });
    card.addEventListener('mouseleave', () => card.style.transform = '');
});

// ============================================
// RIPPLE CTA
// ============================================
const ctaButton = document.querySelector('.cta-button');
if (ctaButton) {
    ctaButton.addEventListener('click', e => {
        e.preventDefault();
        const ripple = document.createElement('span');
        const rect = ctaButton.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        ripple.style.cssText = `
            position:absolute;
            width:${size}px;
            height:${size}px;
            left:${e.clientX - rect.left - size/2}px;
            top:${e.clientY - rect.top - size/2}px;
            background:rgba(255,255,255,0.6);
            border-radius:50%;
            pointer-events:none;
            transform:scale(0);
            animation:ripple 0.6s ease-out;
        `;
        ctaButton.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    });
}

// CSS Ripple
const style = document.createElement('style');
style.textContent = `@keyframes ripple{to{transform:scale(4);opacity:0;}}`;
document.head.appendChild(style);

// ============================================
// OBSERVER
// ============================================
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.style.animation = 'slideInFromBottom 0.8s ease-out forwards';
    });
}, { threshold: 0.1, rootMargin: '0px 0px -100px 0px' });
featureCards.forEach(card => observer.observe(card));

console.log('🚀 Animations optimisées chargées !');
