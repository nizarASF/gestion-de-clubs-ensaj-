/* Soumet automatiquement le formulaire d'année au changement (JS vanilla). */
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('team-year-form');
    const select = document.getElementById('year');
    if (form && select) {
        select.addEventListener('change', function () {
            form.submit();
        });
    }
});
