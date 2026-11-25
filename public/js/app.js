/*
 * JavaScript vanilla — Menu burger, fermeture des messages flash
 * et vérification simple de la confirmation de mot de passe.
 * Aucune dépendance externe.
 */
document.addEventListener('DOMContentLoaded', function () {
    // Menu burger (affiché sous 768 px).
    const toggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.site-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', function () {
            nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', nav.classList.contains('is-open') ? 'true' : 'false');
        });
    }

    // Fermeture manuelle des messages flash.
    document.querySelectorAll('[data-dismiss-alert]').forEach(function (button) {
        button.addEventListener('click', function () {
            const alert = button.closest('.alert');
            if (alert) {
                alert.remove();
            }
        });
    });

    // Contrôle côté client : les deux mots de passe doivent être identiques.
    // La validation serveur (FormRequest) reste la seule validation de référence.
    document.querySelectorAll('form[data-password-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const password = form.querySelector('input[name="password"]');
            const confirmation = form.querySelector('input[name="password_confirmation"]');
            if (password && confirmation && password.value !== confirmation.value) {
                let error = form.querySelector('[data-password-error]');
                if (!error) {
                    error = document.createElement('p');
                    error.className = 'form-error';
                    error.setAttribute('data-password-error', '');
                    confirmation.closest('.form-row').appendChild(error);
                }
                error.textContent = 'Les deux mots de passe ne correspondent pas.';
                event.preventDefault();
            }
        });
    });
});
