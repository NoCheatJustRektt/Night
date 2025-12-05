// ============================================
// TYPEWRITER
// ============================================
const phrases = [
    "Bienvenue dans ton village numérique ✨",
    "Apprends, partage, grandis ensemble 🌱",
    "Une communauté bienveillante t'attend 🤝",
    "Ton espace cosy pour progresser 💫"
];
let phraseIndex = 0, charIndex = 0, isDeleting = false, typingSpeed = 100;

const typewriterElement = document.getElementById('typewriter');

function typeWriter() {
    const el = document.getElementById('typewriter');
    const phrase = phrases[phraseIndex];
    if (!isDeleting && charIndex < phrase.length) el.textContent = phrase.substring(0, ++charIndex);
    else if (isDeleting && charIndex > 0) el.textContent = phrase.substring(0, --charIndex);

    if (!isDeleting && charIndex === phrase.length) { isDeleting = true; typingSpeed = 2000; }
    else if (isDeleting && charIndex === 0) { isDeleting = false; phraseIndex=(phraseIndex+1)%phrases.length; typingSpeed=500; }
    else typingSpeed = isDeleting ? 50 : 100;

    setTimeout(() => requestAnimationFrame(typeWriter), typingSpeed);
}
document.addEventListener('DOMContentLoaded', ()=>setTimeout(typeWriter, 1000));

// ============================================
// PARTICULES
// ============================================
const canvas = document.getElementById('particles');
const ctx = canvas.getContext('2d');
function resizeCanvas(){ canvas.width=window.innerWidth; canvas.height=window.innerHeight; }
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

class Particle {
    constructor(){ this.reset(true); }
    reset(randomY=false){
        this.x=Math.random()*canvas.width;
        this.y=randomY?Math.random()*canvas.height:-10;
        this.speed=Math.random()*1+0.5;
        this.size=Math.random()*2+1;
        this.opacity=Math.random()*0.5+0.2;
        const colors=['rgba(221,235,157,','rgba(160,200,120,','rgba(39,102,123,'];
        this.color=colors[Math.floor(Math.random()*colors.length)];
    }
    update(){ this.y+=this.speed; this.x+=Math.sin(this.y*0.01)*0.5; if(this.y>canvas.height) this.reset(); }
    draw(){ ctx.beginPath(); ctx.arc(this.x,this.y,this.size,0,Math.PI*2); ctx.fillStyle=this.color+this.opacity+')'; ctx.shadowBlur=10; ctx.shadowColor=this.color+this.opacity+')'; ctx.fill(); }
}
const particles=Array.from({length:50},()=>new Particle());

function animateParticles(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
    particles.forEach(p=>{p.update();p.draw();});
    // Connexions optimisées : seulement voisins proches dans tableau (économie CPU)
    for(let i=0;i<particles.length;i++){
        const pi=particles[i];
        for(let j=i+1;j<i+5 && j<particles.length;j++){ // max 4 voisins
            const pj=particles[j];
            const dx=pi.x-pj.x, dy=pi.y-pj.y, dist=dx*dx+dy*dy;
            if(dist<80*80){ ctx.beginPath(); ctx.strokeStyle=`rgba(221,235,157,${0.1*(1-dist/(80*80))})`; ctx.lineWidth=0.5; ctx.moveTo(pi.x,pi.y); ctx.lineTo(pj.x,pj.y); ctx.stroke(); }
        }
    }
    requestAnimationFrame(animateParticles);
}
animateParticles();

// ============================================
// PARALLAXE
// ============================================
const hero=document.querySelector('.hero-section'); let lastScroll=0;
window.addEventListener('scroll',()=>{ lastScroll=window.pageYOffset; });
function parallaxLoop(){ if(hero) hero.style.transform=`translateY(${lastScroll*0.5}px)`; requestAnimationFrame(parallaxLoop); }
parallaxLoop();

// ============================================
// FEATURE CARDS 3D
// ============================================
document.querySelectorAll('.feature-card').forEach(card=>{
    let throttle=false;
    card.addEventListener('mousemove',e=>{
        if(throttle) return; throttle=true;
        window.requestAnimationFrame(()=>{
            const rect=card.getBoundingClientRect(), cx=rect.width/2, cy=rect.height/2;
            const rotateX=(e.clientY-rect.top-cy)/10, rotateY=(cx-(e.clientX-rect.left))/10;
            card.style.transform=`translateY(-15px) scale(1.05) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            throttle=false;
        });
    });
    card.addEventListener('mouseleave',()=>card.style.transform='');
});

// ============================================
// RIPPLE CTA
// ============================================
const cta=document.querySelector('.cta-button');
if(cta){ cta.addEventListener('click',e=>{
    e.preventDefault();
    const ripple=document.createElement('span'), rect=cta.getBoundingClientRect(), size=Math.max(rect.width,rect.height);
    ripple.style.cssText=`width:${size}px;height:${size}px;left:${e.clientX-rect.left-size/2}px;top:${e.clientY-rect.top-size/2}px;position:absolute;border-radius:50%;background:rgba(255,255,255,0.6);transform:scale(0);animation:ripple 0.4s ease-out;pointer-events:none;`;
    cta.appendChild(ripple); setTimeout(()=>ripple.remove(),400);
})}
const style=document.createElement('style'); style.textContent=`@keyframes ripple{to{transform:scale(4);opacity:0;}}`; document.head.appendChild(style);

// ============================================
// CURSEUR PERSONNALISÉ
// ============================================
const cursor=document.createElement('div');
cursor.style.cssText='width:20px;height:20px;border:2px solid rgba(221,235,157,0.5);border-radius:50%;position:fixed;pointer-events:none;z-index:9999;transition:transform 0.2s ease;display:none;';
document.body.appendChild(cursor);
let mouseX=0, mouseY=0;
document.addEventListener('mousemove',e=>{ cursor.style.display='block'; mouseX=e.clientX; mouseY=e.clientY; });
function cursorLoop(){ cursor.style.transform=`translate3d(${mouseX-10}px,${mouseY-10}px,0)`; requestAnimationFrame(cursorLoop); }
cursorLoop();
document.querySelectorAll('a,button,.feature-card').forEach(el=>{
    el.addEventListener('mouseenter',()=>cursor.style.transform=`translate3d(${mouseX-10}px,${mouseY-10}px,0) scale(1.5)`);
    el.addEventListener('mouseleave',()=>cursor.style.transform=`translate3d(${mouseX-10}px,${mouseY-10}px,0) scale(1)`);
});
