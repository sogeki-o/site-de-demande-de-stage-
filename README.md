# Site de demande de stage

Application web Laravel pour la gestion des demandes de stage entre trois profils:

- Demandeur
- Service
- RH

## Stack technique

- PHP 8.2+
- Composer
- Node.js 20+ et npm
- Laravel 12
- SQLite (par defaut) ou MySQL

## Installation rapide (Windows PowerShell)

Depuis la racine du projet:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\setup-dev.ps1
```

Ce script:

- installe les dependances PHP et JS
- cree `.env` depuis `.env.example` si absent
- genere `APP_KEY` si absent
- cree le fichier `database/database.sqlite` si necessaire
- execute les migrations et les seeders

## Installation manuelle

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
```

Pour Linux/macOS, remplacer `copy` par:

```bash
cp .env.example .env
```

## Lancement en local

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

## Comptes et donnees de test

Les utilisateurs de test sont injectes par les seeders. Relancer:

```bash
php artisan migrate:fresh --seed
```

## Commandes utiles

```bash
php artisan test
php artisan route:list
php artisan config:clear
php artisan cache:clear
```

## Securite avant publication

- Ne jamais versionner `.env`
- Ne jamais commiter des cles API ou mots de passe reels
- Verifier la presence de `.env` dans `.gitignore`
- Utiliser seulement `.env.example` comme modele partage

## Structure principale

- `app/Http/Controllers`: logique applicative (Admin, RH, Service, Demandeur)
- `app/Models`: modeles metier
- `resources/views`: vues Blade
- `database/migrations`: structure de la base
- `database/seeders`: donnees initiales

## Depannage

Si une erreur de cache apparait:

```bash
php artisan optimize:clear
```

Si les assets front ne se compilent pas:

```bash
npm run build
```
