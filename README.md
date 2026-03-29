# Church Management System (Core PHP Full)

A full Core PHP + MySQL Church Management System tailored for Tanzanian churches.

## Stack
- Backend: Core PHP 8+ (no framework)
- Database: MySQL 8+
- Frontend: Modern mobile-first CSS + vanilla JS
- Server: Apache (WAMP)

## Implemented Modules
- Authentication (session login/logout)
- Dashboard with live stats
- Membership and Attendance foundation
- Event management foundation with event report API
- Finance management (income/expense posting)
- Procurement workflow page and linked architecture
- Asset management page and linked architecture
- Communication module page (SMS integration plan)
- Reports center page
- Audit trail logging for key actions

## Project Structure
- public/index.php: Front controller and route dispatcher
- app/config.example.php: Safe config template for repo
- app/config.php: Local app and DB settings (not committed)
- app/core/*: Database, Auth, Audit, Response helpers
- app/controllers/*: PageController and ApiController
- app/views/*: Layout and module pages
- database/schema.sql: Full relational schema
- database/sample_data.sql: Demo data
- docs/api-endpoints.md: Full REST endpoint design
- docs/core-php-api.md: Implemented core API routes

## Setup on WAMP
1. Create database:
   - CREATE DATABASE church_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
2. Import SQL:
   - source database/schema.sql
   - source database/sample_data.sql
   - source database/migrations/2026_03_29_001_create_theme_verses.sql
3. Create local config from template and update DB credentials if needed:
   - copy app/config.example.php to app/config.php
   - then edit app/config.php
4. Enable Apache rewrite module and AllowOverride for .htaccess.
5. Open in browser:
   - http://localhost/kanisa/church-cms/public/

## Demo Login
Sample users are inserted from sample_data.sql.
Use one of these phone values:
- +255700000001 (Admin)
- +255700000002 (Finance Officer)
- +255700000003 (Secretary)
- +255700000004 (Standard User)

Demo password for all sample users: 12345678

## Core API Available Now
- POST /api/v1/auth/login
- GET /api/v1/dashboard/stats
- GET /api/v1/members
- POST /api/v1/members
- POST /api/v1/finance/entries
- GET /api/v1/events/{id}/report

## Security and Scalability Notes
- Use password_hash/password_verify for credentials.
- Add strict role checks per route based on Auth::hasRole.
- Keep audit_logs append-only.
- Add pagination and query filters for large data sets.
- Add background worker/cron for SMS retries and report exports.

## Next Enhancements
- Complete all REST endpoints from docs/api-endpoints.md in public/index.php + ApiController.
- Add PDF/Excel exports for financial reports.
- Add provider integration for SMS sending and delivery callbacks.
- Add CSRF tokens for form POST endpoints.
