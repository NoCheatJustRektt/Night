const chatbotWidget = document.getElementById('chatbot-widget');
const openBtn = document.getElementById('chatbot-open-btn');
const toggleBtn = document.getElementById('chatbot-toggle');
const messages = document.getElementById('chatbot-messages');
const inputField = document.getElementById('chatbot-input');
const sendBtn = document.getElementById('chatbot-send');

// Affichage / masquage du chatbot
openBtn.onclick = () => chatbotWidget.style.display = 'flex';
toggleBtn.onclick = () => chatbotWidget.style.display = 'none';

// Fonction pour ajouter un message dans le chat
function addMessage(text, sender) {
    const msg = document.createElement('div');
    msg.className = `chatbot-msg ${sender}`;
    msg.innerHTML = marked.parse(text); // Parse Markdown
    messages.appendChild(msg);

    // Scroll en bas après rendu
    requestAnimationFrame(() => {
        messages.scrollTop = messages.scrollHeight;
    });
}

// Fonction pour envoyer le message
async function sendMessage() {
    const userMessage = inputField.value.trim();
    if (!userMessage) return;

    // Ajout message utilisateur
    addMessage(userMessage, 'user');

    // Désactiver input et bouton + spinner
    inputField.disabled = true;
    sendBtn.classList.add('disabled', 'loading');

    try {
        const response = await fetch('public/api/chatbot.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ prompt: userMessage })
        });

        const data = await response.json();

        const botReply = data.reply || 'Pas de réponse.';
        addMessage(botReply, 'bot');

    } catch (error) {
        console.error(error);
        addMessage("Erreur : impossible de contacter le serveur.", 'bot');
    } finally {
        // Réactiver input et bouton + enlever spinner
        inputField.disabled = false;
        sendBtn.classList.remove('disabled', 'loading');
        inputField.value = '';
        inputField.focus();
    }
}





// Événements
sendBtn.onclick = sendMessage;

inputField.addEventListener('keydown', e => {
    if (e.key === 'Enter') sendMessage();
});
