# Ensaj Clubs

Site **local** de présentation des 11 clubs de l'ENSAJ : accueil, fiches clubs, posts, galerie photos/vidéos, « Meet the Team » et messagerie entre étudiants et clubs. Le **BDE** valide toutes les publications.

Technologies : **Laravel 12**, Blade, CSS3 et JavaScript **vanilla** (écrits à la main dans `public/css` et `public/js`), MySQL via Eloquent, `intervention/image` v3 (driver GD) pour les images. Aucun Node, npm, Vite, Tailwind, Bootstrap, jQuery ni framework front externe.

---

## 1. Rôles et droits

| Rôle | Ce qu'il peut faire |
|---|---|
| **Visiteur** (sans compte) | Consulter l'accueil, les clubs, les posts publiés, la galerie et les équipes. |
| **Étudiant** (`student`) | S'inscrire, se connecter, modifier son profil, écrire à un club, consulter ses conversations, recevoir des notifications. |
| **Membre** (`club_member`) | Tout ce que fait un étudiant, plus : posts et fiches de **son** club, médias de galerie, tableau de bord du club, réponse aux messages reçus par son club. |
| **BDE** (`bde`) | Tout administrer : valider/refuser inscriptions, posts, fiches et médias, attribuer les rôles, gérer les clubs, publier des posts, supprimer contenus. Le BDE n'a **pas de club** et ne lit pas les messages des clubs. |

Un compte créé par inscription est toujours `student` + `pending` : la connexion est refusée jusqu'à validation par le BDE.

---

## 2. Prérequis

**Docker (recommandé)** : Docker Engine + Docker Compose (`docker compose`). Aucun PHP, Composer ni XAMPP local.

**XAMPP / PHP local (alternative)** :
- **XAMPP** avec **MySQL/MariaDB démarré** (ou un MySQL local).
- **PHP ≥ 8.2** (8.2 suffit pour Laravel 12), avec les extensions `pdo_mysql`, `gd`, `mbstring`, `fileinfo`, `bcmath`, `zip`.
  - Dans le `php.ini` de XAMPP, vérifier la ligne `extension=gd`.
- **Composer**.
- Pour les vidéos, dans le `php.ini` de XAMPP : `upload_max_filesize=64M` et `post_max_size=70M`, puis **redémarrer Apache**.

Aucune installation Node/npm n'est nécessaire.

---

## 3. Installation

### 3.1 Lancement rapide avec Docker (recommandé)

```bash
git clone <url-du-depot> && cd <dossier-du-projet>
docker compose up --build
```

Ouvrez ensuite **http://127.0.0.1:8000** (le port 8000 doit être libre). Au premier lancement, l'entrypoint :

- génère `.env` depuis `.env.example` et crée `APP_KEY` si besoin ;
- attend que MariaDB 10.11 soit prêt, crée le lien `storage` et lance les migrations ;
- n'insère les données de démonstration **que si la table `clubs` est vide** : les redémarrages ne réinitialisent rien.

Les accès base Docker sont définis dans `docker-compose.yml` (`DB_HOST=db`, base `ensaj_clubs`, utilisateur `ensaj`, mot de passe `secret`) : **aucune modification manuelle de `.env` n'est nécessaire**. `.env.example` reste inchangé pour une utilisation XAMPP locale.

```bash
docker compose logs -f app   # suivre les logs Apache/Laravel
docker compose down          # arrêter en conservant les données (volume db-data)
docker compose down -v       # arrêter ET réinitialiser la base de données
```

### 3.2 Alternative : XAMPP / PHP local

```bash
git clone <url-du-depot> && cd <dossier-du-projet>
composer install
cp .env.example .env          # Windows : copy .env.example .env
php artisan key:generate
# Créer la base ensaj_clubs dans phpMyAdmin ou en ligne de commande :
# mysql -u root -e "CREATE DATABASE IF NOT EXISTS ensaj_clubs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

Ouvrir ensuite **http://127.0.0.1:8000**.

Si `php artisan storage:link` indique que le lien existe déjà, c'est normal. Les fichiers uploadés sont stockés dans `storage/app/public/{logos,posts,media,team}` ; seuls leurs chemins relatifs sont enregistrés en base.

---

## 4. Comptes de démonstration

Mot de passe de **tous** les comptes : `password`.

| Email | Nom | Rôle | Statut | Remarque |
|---|---|---|---|---|
| `bde@ensaj.test` | BDE Admin | `bde` | approved | `club_id` NULL |
| `etudiant@ensaj.test` | Étudiant Test | `student` | approved | filière `gi` |
| `attente@ensaj.test` | En Attente | `student` | pending | montre le refus de connexion |
| `refuse@ensaj.test` | Refusé Test | `student` | rejected | motif « Informations incomplètes. » |
| `membre.ais@ensaj.test` | Membre AIS | `club_member` | approved | club AIS |
| `membre.hackademy@ensaj.test` | Membre Hackademy | `club_member` | approved | club Hackademy |

### Scénario de démonstration

1. Se déconnecter, s'inscrire avec une nouvelle adresse : le message « compte à valider » s'affiche ; la connexion est refusée.
2. Se connecter comme `bde@ensaj.test` → **Administration → Modération → Inscriptions** → valider le nouveau compte.
3. Se connecter avec le nouveau compte → « Contacter le club » sur la page AIS → envoyer une question.
4. Se connecter comme `membre.ais@ensaj.test` → **Mes conversations** → répondre au fil.
5. Dans **Espace membre**, créer un post (brouillon) puis cliquer « Soumettre », et proposer une fiche du bureau.
6. Revenir au BDE → **Moderation → Posts / Fiches** → valider : le membre reçoit une notification.
7. Se déconnecter (visiteur) : le post et la fiche apparaissent sur les pages publiques du club.

---

## 5. Où modifier les données et la charte

- **Descriptions des clubs** : `database/seeders/data/clubs.php` (11 entrées, descriptions actuellement provisoires). Puis relancer `php artisan migrate:fresh --seed`.
- **Logos des clubs** : déposer `database/seeders/assets/logos/{slug}.png` (ex. `ais.png`) avant le seeder ; ils sont copiés dans `storage/app/public/logos/`. Sans logo, le site affiche `public/images/club-default.svg`.
- **Couleurs et typographie** : variables `:root` de `public/css/app.css` (`--color-primary`, `--color-accent`, `--color-bg`, `--font-main`, `--radius`, …). Un seul endroit à modifier pour toute la charte.
- **Comptes de démo et contenus d'exemple** : `database/seeders/UserSeeder.php` et `database/seeders/DemoContentSeeder.php`.

---

## 6. Travail en équipe avec Git

- **Ne jamais commiter** `.env`, `vendor/`, `storage/app/public/` ni les fichiers uploadés.
- **Ne jamais échanger de dump SQL** : la base se recrée toujours avec `php artisan migrate:fresh --seed`.
- Branches courtes (`feature/...`, `fix/...`) et commits fréquents.
- Le `.env.example` est versionné ; le `.env` reste local.

---

## 7. Lancer les tests

```bash
php artisan test
```

Les tests Feature couvrent l'inscription, la connexion, la matrice des rôles, le workflow des posts et fiches, l'accès aux conversations, la validation des uploads et la cohérence `assignRole()`. Ils utilisent SQLite en mémoire si `pdo_sqlite` est disponible.

---

## 8. Choix techniques

- **Docker** : `Dockerfile` (PHP 8.2 + Apache, GD compilé avec WebP), `docker-compose.yml` (MariaDB 10.11 + volume persistant) et `docker/entrypoint.sh` (`.env`, migrations, seed si vide). Les variables d'environnement Compose surchargent celles de `.env.example`.
- **Laravel 12** retenu car la version de PHP disponible est 8.2 (Laravel 12 accepte PHP ≥ 8.2).
- **Enums PHP castés** pour `Role`, `Status`, `FieldOfStudy`, `MessageType` et `MediaType`. Dans les requêtes SQL, la valeur `->value` est utilisée explicitement.
- **Notifications** : table UUID identique au stub Laravel 12 de `make:notifications-table`, canal `database` uniquement (aucun email).
- **Images** : `intervention/image` v3 avec GD. Le projet encode en **WebP** quand GD compile cette option ; sinon il bascule automatiquement en **JPEG** avec la même qualité (~80) et adapte l'extension du fichier. Cela permet de faire tourner le site sur un XAMPP dont GD est compilé sans WebP (cas rencontré en local). Le comportement est centralisé dans `app/Services/ImageService.php`.
- **Tests** : fabriques minimales dans `tests/TestCase.php` (pas de dépendance supplémentaire) et base SQLite en mémoire via `phpunit.xml`.
- **Pas de suppression de compte**, de réinitialisation de mot de passe, de commentaires, de likes ni d'historique : ces fonctions ne sont pas au programme.
- Le bouton « Contacter le club » est visible sur la page publique ; un visiteur est renvoyé vers `/login` puis ramené sur la page voulue après connexion.

---

## 9. Structure des dossiers (version courte)

```text
app/
  Enums/            Role, Status, FieldOfStudy, MessageType, MediaType
  Http/Controllers/ Auth, Profile, Notification, Conversation, Public/, Manage/, Admin/
  Http/Middleware/  EnsureRole (alias `role`)
  Http/Requests/    FormRequest de validation
  Models/           User, Club, Post, PostImage, Media, TeamMember, Conversation, Message
  Notifications/    ModerationResult, NewMessage
  Policies/         Post, TeamMember, Media, Conversation
  Services/         ImageService
database/
  migrations/       schéma complet, dans l'ordre imposé
  seeders/          DatabaseSeeder, ClubSeeder, UserSeeder, DemoContentSeeder, data/, assets/logos/
public/
  css/app.css       toute la charte (variables :root)
  js/               app.js, chat.js, gallery.js, team-year.js, upload-preview.js
  images/           club-default.svg
resources/views/
  layouts/, components/, auth/, profile/, public/, conversations/,
  manage/, admin/, notifications/, errors/
routes/web.php      toutes les routes nommées
tests/Feature/      tests de comportement
Dockerfile          image PHP 8.2 + Apache + extensions
docker-compose.yml  app (port 8000) + MariaDB 10.11
docker/             entrypoint.sh et php.ini (.dockerignore à la racine)
```
