#!/usr/bin/env bash
# Démarrage "plug and play" : .env, clé, base, lien storage, migrations, seed initial.
set -euo pipefail

cd /var/www/html

# 1) Environnement : on ne touche jamais à un .env déjà présent.
if [ ! -f .env ]; then
    cp .env.example .env
    # On aligne le .env généré sur la base du conteneur (les variables Compose
    # restent prioritaires, mais le fichier reflète ainsi la configuration réelle).
    sed -i \
        -e "s/^DB_HOST=.*/DB_HOST=${DB_HOST:-db}/" \
        -e "s/^DB_PORT=.*/DB_PORT=${DB_PORT:-3306}/" \
        -e "s/^DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE:-ensaj_clubs}/" \
        -e "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME:-ensaj}/" \
        -e "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD:-secret}/" \
        .env
    echo "Fichier .env créé depuis .env.example."
fi
if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

# 2) Attente de MariaDB (la healthcheck de Compose ne suffit pas à garantir les droits).
db_ping() {
    php -r '
        $host = getenv("DB_HOST") ?: "db";
        $port = getenv("DB_PORT") ?: "3306";
        $name = getenv("DB_DATABASE") ?: "ensaj_clubs";
        $user = getenv("DB_USERNAME") ?: "ensaj";
        $pass = getenv("DB_PASSWORD") ?: "secret";
        try {
            new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
            exit(0);
        } catch (Throwable $e) {
            exit(1);
        }
    '
}
until db_ping; do
    echo "En attente de la base ${DB_HOST:-db}:${DB_PORT:-3306}..."
    sleep 2
done
echo "Base de données prête."

# 3) Stockage public, migrations et seed UNIQUEMENT si la base est vide.
php artisan storage:link || true
php artisan migrate --force

CLUBS=$(php -r '
    $host = getenv("DB_HOST") ?: "db";
    $port = getenv("DB_PORT") ?: "3306";
    $name = getenv("DB_DATABASE") ?: "ensaj_clubs";
    $user = getenv("DB_USERNAME") ?: "ensaj";
    $pass = getenv("DB_PASSWORD") ?: "secret";
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$name}", $user, $pass);
    echo $pdo->query("SELECT COUNT(*) FROM clubs")->fetchColumn();
')
if [ "$CLUBS" = "0" ]; then
    php artisan db:seed --force
    echo "Base vide : données de démonstration insérées."
else
    echo "Base déjà peuplée (${CLUBS} clubs) : seed ignoré."
fi

# 4) Apache au premier plan : les logs restent visibles avec `docker compose logs`.
exec "$@"
