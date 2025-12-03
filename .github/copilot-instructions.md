# Copilot / AI Agent Instructions for AgroGISTech

These instructions are tailored to this repository (a Laravel application). Focus on actionable, discoverable patterns and commands so an AI coding agent can be immediately productive.

**Big Picture**
- **App type:** Laravel PHP web application (framework files present under `vendor/laravel` and typical Laravel layout).
- **Major components:** `app/Http/Controllers` (HTTP controllers), `app/Models` (Eloquent models, e.g. `app/Models/User.php`), `routes/web.php` (HTTP routes), `resources/views` (Blade views), `database/migrations` and `database/seeders` (schema + seeders).
- **Why structure exists:** Standard Laravel MVC keeps controllers thin, models in `app/Models`, and configuration under `config/`. Providers in `app/Providers` are used for app-wide bindings.

**Common developer workflows & commands (PowerShell on Windows)**
- **Install PHP deps:** `composer install`
- **Install JS deps & start dev assets:** `npm install` then `npm run dev` (Vite is configured via `vite.config.js`).
- **Environment setup:** Copy or inspect `.env` (root). Generate app key: `php artisan key:generate`.
- **Run migrations & seed:** `php artisan migrate --seed` (check `database/seeders/DatabaseSeeder.php`).
- **Run tests:** `php artisan test` or `./vendor/bin/phpunit` (use the packaged `vendor\\bin\\phpunit.bat` on Windows).
- **Local server (dev):** `php artisan serve` (or use Laragon, Docker, etc.).

**Project-specific conventions & patterns**
- **Models folder:** All Eloquent models live in `app/Models` (example: `User.php`). Use `Model::query()` patterns for DB work.
- **Controllers:** HTTP-facing logic goes in `app/Http/Controllers` — prefer returning views via `return view('...')` or JSON for APIs.
- **Routes:** Add HTTP routes in `routes/web.php`. If adding API routes, mirror Laravel default structure (`routes/api.php`) if applicable.
- **Service providers:** Global bindings, event listeners, or macros belong in `app/Providers/AppServiceProvider.php`.
- **Migrations/seeds:** Database schema changes use files in `database/migrations`; seeds live in `database/seeders` and are referenced from `DatabaseSeeder.php`.

**Integration points & external deps**
- **Composer:** Check `composer.json` for PHP dependencies; add packages via `composer require` and update service providers if needed.
- **Node/Vite:** Frontend assets are handled via `package.json` and `vite.config.js`. Use `npm run build` for production bundles.
- **Config & secrets:** Configuration in `config/*.php`; runtime secrets in `.env`. Do not commit secrets.
- **Logging:** Runtime logs are in `storage/logs/laravel.log` — useful for debugging exceptions.

**Files to inspect for context when making changes**
- `app/Models/User.php` — model conventions, fillable attributes, relationships.
- `app/Http/Controllers/*` — typical controller patterns and response shapes.
- `routes/web.php` — where HTTP endpoints are defined.
- `database/migrations/*` and `database/seeders/*` — DB shape and initial data.
- `config/database.php` — DB connection defaults used in migrations/tests.
- `composer.json` and `package.json` — dependency sources and available scripts.

**Editing & testing guidance for agents**
- When adding a new feature, update: model (in `app/Models`), migration (if DB change), controller (`app/Http/Controllers`), route (`routes/web.php`), and a small feature test under `tests/Feature`.
- Prefer small, focused changes that include an accompanying test. Use `php artisan test` locally to verify.
- For frontend changes, run `npm run dev` to validate Vite dev server output; run `npm run build` to test production build.

**Examples (copyable snippets)**
- Create migration and model:
  - `php artisan make:migration create_things_table --create=things`
  - `php artisan make:model Thing -m` (creates model + migration)
- Quick test run (PowerShell):
  - `composer install; php artisan key:generate; php artisan migrate --seed; php artisan test`

If anything here looks incomplete or you want me to add examples for a specific area (APIs, events, queues, or containerization), tell me which area and I will extend this file.
