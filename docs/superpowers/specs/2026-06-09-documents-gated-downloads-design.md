# Documents / Téléchargements — Gated Downloads with Account Approval

- **Date:** 2026-06-09
- **Status:** Approved design — pending spec review
- **Site:** Fab Sourcing (bilingual FR/EN Laravel B2B marketing site)

## 1. Purpose

Add a "Documents / Téléchargements" area to the public site where visitors download
documents (PDF catalogues, technical sheets) **only after** creating an account that an
administrator has approved. Goals: capture qualified B2B leads and control access to
commercial/technical documents.

## 2. Key decisions (locked)

- **Access model:** Full user accounts (email + password). Self-registration creates a
  *pending* account; an admin must approve it before the user can log in or download.
  (Not a lead-gate, not magic-link.)
- **Documents:** Managed by admins via the admin panel (upload PDF, FR/EN title +
  description, publish/unpublish, ordering).
- **Email notifications:** Implemented but **disabled by default** — the client is not yet
  ready to configure SMTP. Sending is gated by a config flag; when enabled later, three
  emails fire (see §7). Until then the flow works on-site only.
- **Email verification:** Not used — admin approval is the human gate.
- **Auth implementation:** Custom controllers on the existing `web` guard + `User` model,
  mirroring the existing `AdminAuthController` pattern. No new auth packages
  (no Breeze/Fortify/UI).
- **Registration fields:** name, email, company, phone (optional), password (+ confirm),
  GDPR consent checkbox.
- **Bilingual:** All new pages, validation messages and email templates in FR/EN using the
  site's `$lang === 'fr' ? … : …` convention.

## 3. Data model

### 3.1 `users` (alter existing table)
Add columns:
- `company` — string, nullable
- `phone` — string, nullable
- `approved_at` — timestamp, nullable (null = pending)
- `approved_by` — unsignedBigInteger, nullable (admin id, set on approval)
- `gdpr_consent_at` — timestamp, nullable (consent recorded at registration)
- `locale` — string(2), nullable (registration language, for email locale; default `fr`)

`User` model: add `company`, `phone` to `$fillable`; `approved_*`, `gdpr_consent_at`,
`locale` set programmatically. Add helper `isApproved(): bool` (`approved_at !== null`).

### 3.2 `documents` (new table)
- `id`
- `title` — JSON translatable (FR/EN), same mechanism as Page/Product
- `description` — JSON translatable (FR/EN), nullable
- `file_path` — string (path on the private `documents` disk)
- `original_filename` — string
- `file_size` — unsignedBigInteger (bytes)
- `mime_type` — string
- `sort_order` — int, default 0
- `published` — boolean, default true
- `download_count` — unsignedInteger, default 0
- timestamps

New `Document` model: translatable title/description; `published` scope; default order by
`sort_order`.

## 4. File storage

- New **private** disk `documents` (driver `local`, root `storage/app/documents`, NOT
  symlinked to public).
- Uploaded files live there; never directly web-accessible.
- Downloads are streamed by a controller only after auth + approval checks.

## 5. Public routes & flow (web guard)

Bilingual routes (FR primary, EN variant), names indicative:
- `GET  /{lang}/inscription`  → register form
- `POST /{lang}/inscription`  → store: create pending user, set `gdpr_consent_at`,
  fire "received" + admin emails (if enabled), show confirmation screen
- `GET  /{lang}/connexion`    → login form
- `POST /{lang}/connexion`    → authenticate; **reject if not approved** with FR/EN
  "account pending approval" message; reject invalid credentials; throttled
- `POST /{lang}/deconnexion`  → logout
- `GET  /{lang}/documents`    → **gated** (auth + approved) list of published documents
- `GET  /{lang}/documents/{document}/telecharger` → **gated** stream download;
  increments `download_count`

Middleware: `auth` (web guard) + new `EnsureUserApproved` (pending users are redirected to
a "pending approval" notice; never reach the documents area or a download URL).

Footer: add "Documents / Téléchargements" link. When not authenticated the link points to
the login page, with a small note "Inscrivez-vous pour accéder aux documents" /
"Register to access the documents".

## 6. Admin panel additions

Mirror the existing admin CRUD controllers/views and `admin` guard.

### 6.1 Accounts — `/admin/comptes`
- List users with status (pending / approved), name, email, company, phone, registered date.
- Pending shown first; a badge/count of pending accounts appears in the admin nav.
- Actions: **Approve** (set `approved_at` + `approved_by`, fire "approved" email if enabled),
  **Reject/Delete** (remove a pending account), **Revoke** (set `approved_at = null`).

### 6.2 Documents — `/admin/documents`
- List with published status, size, order.
- Create/Edit: upload PDF (validate mime = pdf, max size 20 MB), FR/EN title + description,
  `sort_order`, `published` toggle; replacing the file on edit deletes the old file.
- Delete: removes the DB row and the file from the private disk.

## 7. Emails (prepared, sending disabled by default)

Config flag `config('documents.notifications_enabled')` ← `env('DOCUMENTS_MAIL_ENABLED', false)`.
Every send is wrapped in `if (config('documents.notifications_enabled'))`.

Three bilingual mailables + Blade email templates:
1. **Admin — new pending account** → to the admin address from site settings:
   "New account awaiting approval."
2. **User — registration received** → "Thanks, your account is pending approval."
3. **User — account approved** → "Your account is approved; you can now log in."

When the client later sets `MAIL_*` env and flips `DOCUMENTS_MAIL_ENABLED=true`, emails
start sending with no code change. Until then: admin sees the pending list; users see
on-site status messages.

## 8. Security / GDPR

- Passwords hashed (bcrypt, Laravel default).
- GDPR consent checkbox **required** at registration; timestamp stored in `gdpr_consent_at`.
- Documents on a private disk; downloads only via the gated authenticated route.
- Login and registration **throttled** (Laravel rate limiting).
- CSRF on all forms; standard validation (unique email, confirmed password, min length).

## 9. Bilingual handling

- New Blade views follow the `$lang === 'fr' ? … : …` convention used site-wide.
- Validation messages localized FR/EN.
- Email templates FR/EN; locale chosen from the user's stored `locale` (default `fr`).
- Footer link label FR/EN.

## 10. Migrations / deploy

- Migrations: alter `users`; create `documents`.
- Config: add `config/documents.php` (or a key) for `notifications_enabled`; register the
  `documents` private disk in `config/filesystems.php`.
- Optional seeder: register a "Documents" page record / footer wiring if needed (idempotent,
  no truncate/delete).
- Deploy: `git pull` → `php artisan migrate --force` → ensure `storage/app/documents` exists
  → `php artisan config:clear && php artisan view:clear`.

## 11. Out of scope (YAGNI / future)

- Email verification (replaced by admin approval).
- Password-strength meters, 2FA, social login.
- Per-document access rules / user groups (all approved users see all published documents).
- Self-service profile editing or account self-deletion UI (admin handles accounts).
- Actual SMTP configuration (client will do later; code is ready behind the flag).
