# Smarter Dog / EasyAppointments — App Overview

## What the App Is

Smarter Dog (EasyAppointments) is a self-hosted appointment scheduling platform. It supports:

- Public booking pages for customers.
- Back-office management for staff (calendar, customers, services, providers, settings).
- API access for programmatic integrations.
- Optional integrations (Google Calendar sync, LDAP login, webhooks, email notifications).

The app is PHP-based and built on top of CodeIgniter with custom `EA_*` core extensions.

---

## High-Level Architecture

The project follows a classic MVC structure with server-rendered pages and page-specific JavaScript:

- **Entry point**: `index.php`
  - Loads root `config.php`
  - Loads Composer autoload
  - Sets environment (`development`, `testing`, `production`)
  - Boots CodeIgniter system/application folders
- **Application code**: `application/`
  - `controllers/` request handlers and page/API endpoints
  - `models/` domain and persistence logic
  - `views/` server-rendered templates/components/layouts
  - `libraries/` reusable services (auth, sync, notifications, availability, etc.)
  - `helpers/` utility functions (`setting`, `permission`, `routes`, `session`, etc.)
  - `migrations/` schema/data evolution over app versions
- **Framework core**: `system/` (CodeIgniter base framework)
- **Frontend assets**: `assets/` (JS, CSS, images)
- **Tests**: `tests/` (PHPUnit)

---

## Request & Runtime Flow

1. Browser/API request enters `index.php`.
2. Framework initializes autoloaded helpers/libraries.
3. Router maps URL to a controller action.
4. Controller checks permissions and loads needed models/libraries.
5. Business logic runs in models/libraries.
6. Response is either:
   - HTML view (`application/views/...`) + JS assets, or
   - JSON response (API/backend AJAX controllers).

The custom base controller `EA_Controller` performs common work for most requests, including language setup, common page/script variables, user validity checks, and rate limiting.

---

## Main Functional Areas

### 1) Public Booking Experience

- Default route points to booking.
- Booking workflow allows service/provider selection, timeslot selection, customer details, and final confirmation.
- Supports reschedule mode via appointment hash.
- Behavior is highly settings-driven (required fields, terms/privacy display, booking lock windows, themes).

### 2) Backoffice Calendar & Operations

- Calendar is the central staff workspace for appointments.
- Role-aware behavior for admins, providers, and secretaries.
- Supports appointment editing/rescheduling and links to related entities.

### 3) Customer Management

- Customer CRUD/search, role-based access control.
- Optional enrichment with related appointments/pets.
- Visibility and permissions can be constrained via settings and role permissions.

### 4) Business/System Settings

- Business settings pages manage operational defaults and global behavior.
- API settings pages manage API-related keys/configuration.
- Integrations pages expose integration setup surfaces.

### 5) REST API (v1)

- Resource-oriented routes under `/api/v1/...`.
- Standard patterns for list/show/create/update/delete resources.
- API controllers centralize auth, filtering, pagination, field mapping, and error handling.

### 6) Integrations & Automation

- **Google Calendar sync** support.
- **LDAP auth** fallback in login flow.
- **Webhooks** for outbound event notifications (appointments/customers/services/providers/etc.).
- **Email notifications** and iCalendar/CalDAV related capabilities.

---

## Permissions and Security Model

- Permissions are action/resource-based (`view`, `add`, `edit`, `delete` against resource constants).
- Role slugs (`admin`, `provider`, `secretary`, `customer`) drive capability checks.
- Controllers frequently gate access early using `can(...)` / `cannot(...)` helper functions.
- Session-based user context is validated in common controller logic.

---

## Data & Domain Notes

- Database is MySQL/MariaDB-oriented (default `mysqli`) with table prefix `ea_`.
- Migrations are enabled and are the canonical source for schema evolution.
- Models enforce domain-level validation (for example appointment datetime validity, ownership checks, seat constraints, and required fields).

---

## Frontend Organization

- Server-rendered pages are in `application/views/pages`.
- Reusable UI fragments are in `application/views/components` and layouts in `application/views/layouts`.
- Page scripts are in `assets/js/pages` and helpers/utilities in adjacent JS folders.
- A page typically injects server variables and then loads a matching page script.

---

## Installation, Deployment, and Updates

- Copy `config-sample.php` to `config.php` and fill base URL + DB credentials.
- Install Composer dependencies.
- Ensure `storage/` is writable.
- Run installation flow through the web UI if not installed.
- For upgrades, the update process applies pending migrations to sync schema/data.

---

## Testing and Quality Tooling

- PHPUnit configured via `phpunit.xml.dist`.
- Test bootstrap in `tests/bootstrap.php`.
- Composer script available: `composer test` (runs PHPUnit in `APP_ENV=testing`).
- Static analysis configuration present (`phpstan.neon`).

---

## Recommended Learning Path for New Contributors

1. Read `index.php` to understand bootstrap and environment behavior.
2. Follow one end-to-end web flow (Booking controller → models/libraries → view → JS page script).
3. Read one API controller (for example appointments API v1) to learn API conventions.
4. Review permissions constants and helper functions (`constants.php`, `permission_helper.php`).
5. Study recent migrations to understand current data model assumptions.
6. Run tests and inspect a few model-level tests to see validation expectations.

---

## Quick Directory Cheat Sheet

- `index.php` — app bootstrap/front controller.
- `config-sample.php` — environment template.
- `application/controllers/` — web + AJAX + API endpoints.
- `application/models/` — data/domain logic.
- `application/views/` — templates, layouts, components.
- `application/libraries/` — integration and business services.
- `application/helpers/` — global utility helpers.
- `application/migrations/` — DB/settings evolution.
- `assets/` — frontend JS/CSS/images.
- `tests/` — PHPUnit tests.
- `storage/` — writable runtime data (logs/cache/uploads/backups).
