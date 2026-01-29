<!-- Purpose: guidance for AI coding agents working on this Laravel app -->
# Copilot instructions for MegaZ ERS

This file gives focused, actionable conventions for AI coding agents contributing to this repository.

1) Project overview
- Framework: Laravel 11 (PHP 8.1+). Frontend: Vue 3 + Vite + Tailwind.
- App structure: domain-driven folders under `app/` (Repositories, Services, Actions, Utilities, Models, Events, Notifications, Observers).
- Key entry points: `artisan`, `public/index.php`, `bootstrap/app.php`.

2) High-level architecture & patterns agents should follow
- Repository pattern: Most domain logic is behind `app/Repositories/*` (each repository usually has an interface and implementation). Bindings live in `app/Providers/RepositoryServiceProvider.php`. When adding a new repository, create `XxxRepositoryInterface` and `XxxRepository` and register the binding in that provider.
- Service/Action layer: Business workflows often live in `app/Services/` or `app/Actions/`. Prefer placing orchestrating code (multiple repo calls, transactions) there rather than inside controllers.
- Global helpers: `app/Utilities/*.php` are autoloaded via `composer.json` `autoload.files`. Use existing helpers like `Pagination()`, `UserData()` and `UploadFileToServer()` to stay consistent.
- Routes: Routes are split by domain in `routes/*.php` (e.g., `routes/hr.php`, `routes/financials.php`). Add new endpoints to the appropriate domain file.

3) Important integration points to know
- Auth: Laravel Sanctum is used (`laravel/sanctum`); `UserData()` returns `auth('sanctum')->user()`.
- Firebase / FCM: `kreait/laravel-firebase` and `laravel-notification-channels/fcm` integrations are present; search `config/firebase.php` / `config/fcm.php` for settings.
- SMS: `app/Utilities/GeneralUtilities.php` contains `SendSMSVerificationCode()` and `SendApprovalSMS()` which call an external SMS API (smspoh). Keep config keys in `config/services.php` or `config/*.php`.
- Excel import/export: `maatwebsite/excel` is used for spreadsheets.

4) Developer workflows & commands (copyable)
- Install PHP deps: `composer install` (post-create scripts copy `.env.example` to `.env` and generate app key).
- Frontend: `npm install` then `npm run dev` (Vite) or `npm run build` (production).
- DB / migrations: set `.env` DB_* then `php artisan migrate --seed` as needed.
- Tests: `./vendor/bin/phpunit` or `php artisan test` (see `phpunit.xml`).
- Formatter/lint: Laravel Pint is available: `./vendor/bin/pint`.
- Local queue & workers: queued jobs use configured `QUEUE_CONNECTION` (testing uses `sync`).

5) Project-specific conventions (do not invent alternatives)
- Interface-first repositories: add interface in `app/Repositories/<Domain>/<Name>RepositoryInterface.php` and implementation in `app/Repositories/<Domain>/<Name>Repository.php`.
- Bind interfaces in `app/Providers/RepositoryServiceProvider.php` (project relies on these bindings everywhere).
- Use helpers from `app/Utilities` for pagination, file uploads and JSON response patterns to maintain consistent API signatures.
- Routes and controllers: controllers should be thin; move complex logic into Services/Actions and Repositories.
- Response utilities: standard response shaping is implemented in `app/Utilities/ResponseUtilities.php` — use it for API responses.

6) Files to inspect for examples
- Repository binding example: `app/Providers/RepositoryServiceProvider.php`
- Utilities & helpers: `app/Utilities/GeneralUtilities.php`, `app/Utilities/ResponseUtilities.php`
- Example repo & interface: `app/Repositories/Report/ReportRepository.php` and `app/Repositories/Report/ReportInterface.php`
- Routes split by domain: `routes/*.php` (e.g., `routes/hr.php`)
- Frontend entry: `resources/js` and `vite.config.js` for Vite setup

7) Safety & testing notes for agents
- Do not change global bindings across many repositories without explicit review — many files rely on the interface bindings in `RepositoryServiceProvider`.
- Avoid committing sensitive values. `.env` should not be checked in. Use `config/*.php` for configuration keys references.
- When modifying database models or migrations, update/follow existing seeders in `database/seeders/` and keep `phpunit.xml` test environment variables in mind.

8) Typical tasks and how to implement them
- Add API endpoint: add route in appropriate `routes/*.php` → controller method (thin) → call Service/Action → Service uses Repositories (interfaces) → register any new interface binding in `RepositoryServiceProvider`.
- Add helper: add PHP function in `app/Utilities/*` and ensure composer autoload files (already present) include file path if adding a new global file; prefer extending existing utility files when appropriate.

If anything here is unclear or you want more examples (e.g., a new repository scaffold or an example endpoint), tell me which domain and I will draft a small scaffold and tests.
