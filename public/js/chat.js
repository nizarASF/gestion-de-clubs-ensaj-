/*
 * Messagerie : envoi sans rechargement, rafraîchissement toutes les 5 s,
 * pause quand l'onglet est caché, défilement automatique.
 * Tout le texte dynamique passe par textContent (jamais innerHTML).
 */
document.addEventListener('DOMContentLoaded', function () {
    const chat = document.getElementById('chat');
    if (! chat) {
        return;
    }
    const list = document.getElementById('chat-messages');
    const form = document.getElementById('chat-form');
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = tokenMeta ? tokenMeta.getAttribute('content') : '';
    let lastId = parseInt(list.dataset.lastId || '0', 10);
    let timer = null;

    function scrollToBottom() {
        list.scrollTop = list.scrollHeight;
    }

    function render(message) {
        const empty = list.querySelector('.chat__empty');
        if (empty) {
            empty.remove();
        }
        const bubble = document.createElement('article');
        bubble.className = 'bubble';
        if (message.mine) {
            bubble.classList.add('bubble--mine');
        }
        if (message.unread) {
            bubble.classList.add('bubble--unread');
        }

        const head = document.createElement('div');
        head.className = 'bubble__head';
        const author = document.createElement('strong');
        author.textContent = message.author || '';
        const date = document.createElement('span');
        date.textContent = message.datetime || '';
        head.append(author, date);
        if (message.type) {
            const badge = document.createElement('span');
            badge.className = 'badge badge--info';
            badge.textContent = message.type;
            head.appendChild(badge);
        }
        if (message.unread) {
            const badge = document.createElement('span');
            badge.className = 'badge badge--pending';
            badge.textContent = 'Non lu';
            head.appendChild(badge);
        }

        const body = document.createElement('div');
        body.className = 'bubble__body';
        body.textContent = message.body || '';
        bubble.append(head, body);
        list.appendChild(bubble);
        lastId = Math.max(lastId, parseInt(message.id, 10) || 0);
        scrollToBottom();
    }

    function refresh() {
        fetch(chat.dataset.fetchUrl + '?after=' + lastId, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(function (response) { return response.ok ? response.json() : []; })
            .then(function (messages) {
                messages.forEach(render);
            })
            .catch(function () { /* site local : on réessaiera au prochain intervalle */ });
    }

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        const bodyField = form.querySelector('[name="body"]');
        const typeField = form.querySelector('[name="type"]');
        if (! bodyField.value.trim()) {
            return;
        }
        const payload = { body: bodyField.value };
        if (typeField) {
            payload.type = typeField.value;
        }
        fetch(chat.dataset.storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        })
            .then(function (response) {
                if (! response.ok) {
                    throw new Error('Envoi impossible');
                }
                return response.json();
            })
            .then(function (message) {
                message.unread = false;
                render(message);
                bodyField.value = '';
            })
            .catch(function () {
                const error = document.createElement('p');
                error.className = 'form-error';
                error.textContent = "Le message n'a pas pu être envoyé.";
                form.appendChild(error);
            });
    });

    function start() {
        if (timer === null) {
            timer = window.setInterval(refresh, 5000);
        }
    }
    function stop() {
        if (timer !== null) {
            window.clearInterval(timer);
            timer = null;
        }
    }
    document.addEventListener('visibilitychange', function () {
        if (document.hidden) {
            stop();
        } else {
            refresh();
            start();
        }
    });

    scrollToBottom();
    start();
});
