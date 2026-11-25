/*
 * Lightbox de la galerie : clic sur une miniature = agrandissement,
 * Échap ou clic en dehors = fermeture. Insertion via textContent uniquement.
 */
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('[data-lightbox-src]');
    if (buttons.length === 0) {
        return;
    }

    const overlay = document.createElement('div');
    overlay.className = 'lightbox';
    overlay.hidden = true;
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-label', 'Aperçu du média');

    const closeButton = document.createElement('button');
    closeButton.className = 'lightbox__close';
    closeButton.type = 'button';
    closeButton.textContent = '×';
    closeButton.setAttribute('aria-label', 'Fermer');

    const content = document.createElement('img');
    content.alt = 'Média agrandi';
    overlay.append(closeButton, content);
    document.body.appendChild(overlay);

    function close() {
        overlay.hidden = true;
        content.removeAttribute('src');
    }

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            content.src = button.getAttribute('data-lightbox-src');
            overlay.hidden = false;
            closeButton.focus();
        });
    });

    closeButton.addEventListener('click', close);
    overlay.addEventListener('click', function (event) {
        if (event.target === overlay) {
            close();
        }
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && ! overlay.hidden) {
            close();
        }
    });
});
