/*
 * Aperçu des images avant envoi, réutilisable sur tous les formulaires :
 * <input type="file" data-preview> + <img data-preview-target> (optionnel).
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            let target = document.querySelector('[data-preview-target="' + input.name + '"]');
            if (!target) {
                target = document.createElement('img');
                target.className = 'image-preview';
                target.setAttribute('data-preview-target', input.name);
                input.closest('.form-row').appendChild(target);
            }
            const file = input.files && input.files[0];
            if (!file || !file.type.startsWith('image/')) {
                target.removeAttribute('src');
                return;
            }
            if (target.dataset.objectUrl) {
                URL.revokeObjectURL(target.dataset.objectUrl);
            }
            const url = URL.createObjectURL(file);
            target.dataset.objectUrl = url;
            target.src = url;
        });
    });
});
