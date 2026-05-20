# Admin Panel Foundation + Pages CRUD Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Rebuild the admin panel with a design-consistent layout (Inter + navy + Fab Sourcing branding) and a fully functional Pages CRUD with FR/EN tab switching, rich-text body content, hero image upload, published toggle, search, and pagination — seeded with 6 static pages and one admin user.

**Architecture:** The `admin` guard (`Auth\AdminAuthController`, `AdminAuthenticated` middleware, `routes/admin.php`) already exists and works. We rebuild only the presentation layer (layout, login view, pages views) and extend the domain layer (migration + model fields + controller logic). The RouteServiceProvider already wraps `routes/admin.php` with `['web','adminauth']` + `prefix('admin')`. No auth code changes needed. Admin SCSS is written from scratch using the same `_variables.scss` design tokens as the public site, without Bulma. Quill (already on the CDN) handles rich-text editing.

**Tech Stack:** PHP 8.2, Laravel 12, Blade, spatie/laravel-translatable, Quill 2 (CDN), Laravel Mix 6 (SCSS), SQLite.

---

## Codebase Context (read before implementing)

- **Auth guard**: `admin` in `config/auth.php`, uses `App\Models\Admin` (table: `admins`)
- **Routes**: `routes/admin.php` is loaded by `RouteServiceProvider` under prefix `admin` with `['web','adminauth']`. `Route::resource('pages', PagesController::class)` already exists there.
- **Page model**: `app/Models/Page.php` uses `HasTranslations`. Translatable: `title`, `content`, `meta_title`, `meta_description`. Current table columns: `id, title, content, slug, created_at, updated_at, priority, meta_title, meta_description`.
- **Priority column**: existing `priority` int column — we'll re-label it "Sort order" in the UI rather than adding a redundant `sort_order` column.
- **Design tokens**: `resources/sass/_variables.scss` has `$ink-900`, `$accent`, `$bg`, `$font-sans`, `$font-mono`, breakpoints, etc. Import these in admin SCSS.
- **Public logo**: `public/images/logo-fab-full-light.png` (white variant) — use in dark admin sidebar.

---

## File Map

### Created
- `database/migrations/XXXX_add_published_hero_to_pages_table.php`
- `database/seeders/AdminSeeder.php`
- `database/seeders/PagesSeeder.php`
- `resources/views/admin/partials/sidebar.blade.php`
- `resources/views/admin/dashboard.blade.php` (minimal replacement)

### Modified
- `app/Models/Page.php` — add `published`, `hero_image` to fillable; add casts
- `app/Http/Controllers/PagesController.php` — full rewrite
- `database/seeders/DatabaseSeeder.php` — call AdminSeeder + PagesSeeder
- `resources/views/layouts/admin.blade.php` — full rebuild (no Bulma)
- `resources/views/auth/admin/login.blade.php` — full rebuild
- `resources/views/admin/pages/index.blade.php` — full rebuild
- `resources/views/admin/pages/create.blade.php` — full rebuild
- `resources/views/admin/pages/edit.blade.php` — full rebuild
- `resources/sass/admin.scss` — full rebuild (no Bulma, uses `_variables.scss` tokens)

---

## Task 1: Pages Table Migration

**Files:**
- Create: `database/migrations/XXXX_add_published_hero_to_pages_table.php`

- [ ] **Step 1.1: Generate the migration**

```bash
php artisan make:migration add_published_hero_to_pages_table --table=pages
```

- [ ] **Step 1.2: Write the migration body**

Open the newly created file in `database/migrations/` ending `_add_published_hero_to_pages_table.php` and replace its contents:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('published')->default(false)->after('slug');
            $table->string('hero_image')->nullable()->after('published');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['published', 'hero_image']);
        });
    }
};
```

- [ ] **Step 1.3: Run the migration**

```bash
php artisan migrate
```

Expected output: `... add_published_hero_to_pages_table ... DONE`

- [ ] **Step 1.4: Verify columns**

```bash
php artisan tinker --execute="echo implode(', ', \Illuminate\Support\Facades\Schema::getColumnListing('pages'));"
```

Expected: `id, title, content, slug, published, hero_image, created_at, updated_at, priority, meta_title, meta_description`

---

## Task 2: Page Model Update

**Files:**
- Modify: `app/Models/Page.php`

- [ ] **Step 2.1: Replace the fillable and add casts**

Open `app/Models/Page.php`. Replace the `$fillable` array and add `$casts`:

```php
protected $fillable = [
    'title', 'content', 'slug', 'priority',
    'meta_title', 'meta_description',
    'published', 'hero_image',
];

protected $casts = [
    'published' => 'boolean',
];
```

The full updated `app/Models/Page.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Fluent;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title', 'content', 'slug', 'priority',
        'meta_title', 'meta_description',
        'published', 'hero_image',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public array $translatable = ['title', 'content', 'meta_title', 'meta_description'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class);
    }

    public function pageSettings()
    {
        return $this->hasMany(PageSetting::class);
    }

    public function getSetting(string $code, $default = null)
    {
        if ($this->relationLoaded('pageSettings')) {
            return $this->pageSettings->keyBy('code')[$code]->content ?? $default;
        }

        return $this->pageSettings()
            ->where('code', $code)
            ->value('content') ?? $default;
    }

    public function getSettingsAttribute()
    {
        $map = [];
        foreach ($this->pageSettings as $s) {
            $map[$s->code] = $s->content;
        }
        return new Fluent($map);
    }

    public function getAttributeValByLanguage($attr, $language)
    {
        $obj = json_decode($this->$attr);
        return $obj->$language ?? '';
    }

    public function getValueByLanguage($jsonObj, $language)
    {
        $obj = json_decode($jsonObj, true);
        return $obj[$language] ?? (is_array($obj) ? reset($obj) : '');
    }

    public function getValueByFirstLanguage($jsonObj)
    {
        $obj = json_decode($jsonObj, true);
        if (!is_array($obj)) return '';
        $firstLanguage = Language::active()->first()->slug ?? Language::first()->slug ?? 'fr';
        return $obj[$firstLanguage] ?? reset($obj) ?? '';
    }
}
```

- [ ] **Step 2.2: Verify model loads**

```bash
php artisan tinker --execute="echo App\Models\Page::class;"
```

Expected: `App\Models\Page`

---

## Task 3: Admin User Seeder

**Files:**
- Create: `database/seeders/AdminSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 3.1: Create `database/seeders/AdminSeeder.php`**

```php
<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@fab-sourcing.fr'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@fab-sourcing.fr',
                'password' => Hash::make('changeme'),
                'isMaster' => true,
            ]
        );
    }
}
```

- [ ] **Step 3.2: Update `database/seeders/DatabaseSeeder.php`**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
        ]);
    }
}
```

- [ ] **Step 3.3: Run the admin seeder**

```bash
php artisan db:seed --class=AdminSeeder
```

Expected: no errors, runs cleanly.

- [ ] **Step 3.4: Verify admin user exists**

```bash
php artisan tinker --execute="echo App\Models\Admin::where('email','admin@fab-sourcing.fr')->value('name');"
```

Expected: `Admin`

---

## Task 4: Admin SCSS Rebuild

**Files:**
- Modify: `resources/sass/admin.scss` (full rewrite)

- [ ] **Step 4.1: Rewrite `resources/sass/admin.scss`**

Replace the entire file content:

```scss
// ============================================================
// Fab Sourcing Admin — stylesheet
// Imports the same design tokens as the public site.
// No Bulma. No Bootstrap.
// ============================================================

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

@import 'variables';   // shared design tokens

// ─── Reset ───────────────────────────────────────────────────
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
  font-family: $font-sans;
  font-size: 15px;
  line-height: 1.5;
  color: $ink-900;
  background: $bg-alt;
  -webkit-font-smoothing: antialiased;
}

a     { color: inherit; text-decoration: none; }
button { font-family: inherit; cursor: pointer; border: none; background: none; }
input, textarea, select { font-family: inherit; font-size: inherit; color: inherit; }
img   { display: block; max-width: 100%; }

// ─── Shell ───────────────────────────────────────────────────
.a-shell {
  display: flex;
  min-height: 100vh;
}

// ─── Sidebar ─────────────────────────────────────────────────
.a-sidebar {
  width: 240px;
  flex-shrink: 0;
  background: $ink-900;
  display: flex;
  flex-direction: column;
  position: sticky;
  top: 0;
  height: 100vh;
  overflow-y: auto;
}

.a-sidebar-logo {
  padding: 24px 20px 20px;
  border-bottom: 1px solid rgba(255,255,255,0.08);

  img {
    height: 36px;
    width: auto;
  }
}

.a-sidebar-label {
  font-family: $font-mono;
  font-size: 10px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  color: rgba(255,255,255,0.35);
  padding: 24px 20px 8px;
}

.a-nav {
  padding: 0 8px;
  flex: 1;

  ul { list-style: none; padding: 0; }

  a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    border-radius: $radius-sm;
    font-size: 14px;
    font-weight: 500;
    color: rgba(255,255,255,0.72);
    transition: all 0.15s ease;

    &:hover          { color: #fff; background: rgba(255,255,255,0.06); }
    &.a-nav-active   { color: #fff; background: $accent; }

    svg { width: 16px; height: 16px; flex-shrink: 0; opacity: 0.8; }
  }
}

.a-sidebar-user {
  padding: 16px 20px;
  border-top: 1px solid rgba(255,255,255,0.08);
  display: flex;
  align-items: center;
  gap: 12px;

  .a-sidebar-user-name {
    font-size: 13px;
    color: rgba(255,255,255,0.85);
    font-weight: 500;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  a {
    font-size: 12px;
    color: rgba(255,255,255,0.45);
    white-space: nowrap;
    &:hover { color: #fff; }
  }
}

// ─── Main area ───────────────────────────────────────────────
.a-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
}

.a-topbar {
  background: $bg;
  border-bottom: 1px solid $line;
  padding: 16px 32px;
  display: flex;
  align-items: center;
  gap: 16px;
  position: sticky;
  top: 0;
  z-index: 10;

  .a-topbar-title {
    font-size: 16px;
    font-weight: 600;
    letter-spacing: -0.02em;
    flex: 1;
  }
}

.a-content {
  flex: 1;
  padding: 32px;
}

// ─── Page header ─────────────────────────────────────────────
.a-page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
  gap: 16px;

  h1 {
    font-size: 22px;
    font-weight: 600;
    letter-spacing: -0.03em;
  }
}

// ─── Buttons ─────────────────────────────────────────────────
.a-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  border-radius: $radius-sm;
  font-size: 13px;
  font-weight: 500;
  transition: all 0.15s ease;
  border: 1px solid transparent;
  cursor: pointer;
  white-space: nowrap;
}

.a-btn-primary {
  background: $ink-900;
  color: #fff;
  &:hover { background: $accent; }
}

.a-btn-accent {
  background: $accent;
  color: #fff;
  &:hover { background: $accent-hover; }
}

.a-btn-ghost {
  border-color: $line-strong;
  color: $ink-700;
  background: $bg;
  &:hover { border-color: $ink-900; color: $ink-900; }
}

.a-btn-danger {
  border-color: rgba(217, 48, 37, 0.3);
  color: #c62828;
  background: rgba(217, 48, 37, 0.05);
  &:hover { background: #c62828; color: #fff; border-color: #c62828; }
}

.a-btn-sm {
  padding: 6px 12px;
  font-size: 12px;
}

// ─── Table ───────────────────────────────────────────────────
.a-card {
  background: $bg;
  border: 1px solid $line;
  border-radius: $radius;
  overflow: hidden;
}

.a-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;

  thead {
    background: $bg-alt;

    th {
      padding: 12px 16px;
      text-align: left;
      font-weight: 600;
      font-size: 12px;
      letter-spacing: 0.04em;
      color: $ink-500;
      border-bottom: 1px solid $line;
      white-space: nowrap;
    }
  }

  tbody {
    tr {
      border-bottom: 1px solid $line;
      transition: background 0.1s ease;
      &:last-child { border-bottom: none; }
      &:hover { background: $bg-alt; }
    }

    td {
      padding: 14px 16px;
      vertical-align: middle;
    }
  }
}

.a-table-actions {
  display: flex;
  align-items: center;
  gap: 6px;
}

// ─── Status badge ────────────────────────────────────────────
.a-badge {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 3px 10px;
  border-radius: 999px;
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  font-weight: 500;

  &.a-badge-published   { background: rgba(43,142,67,0.1);  color: #2b8e43; }
  &.a-badge-draft       { background: rgba(107,120,145,0.1); color: $ink-500; }
}

// ─── Search bar ──────────────────────────────────────────────
.a-search-bar {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;

  input {
    flex: 1;
    max-width: 340px;
    border: 1px solid $line-strong;
    border-radius: $radius-sm;
    padding: 9px 14px;
    font-size: 14px;
    background: $bg;
    transition: border-color 0.15s ease;
    &:focus { outline: none; border-color: $accent; }
  }
}

// ─── Form ────────────────────────────────────────────────────
.a-form-card {
  background: $bg;
  border: 1px solid $line;
  border-radius: $radius;
  padding: 28px;
  margin-bottom: 24px;
}

.a-section-title {
  font-size: 13px;
  font-weight: 600;
  letter-spacing: 0.04em;
  text-transform: uppercase;
  color: $ink-500;
  margin-bottom: 20px;
  padding-bottom: 12px;
  border-bottom: 1px solid $line;
}

// Lang tabs
.a-lang-bar {
  display: inline-flex;
  border: 1px solid $line-strong;
  border-radius: $radius-sm;
  padding: 2px;
  margin-bottom: 24px;
  background: $bg-alt;
  gap: 2px;
}

.a-lang-tab {
  padding: 6px 20px;
  border-radius: 3px;
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.1em;
  font-weight: 500;
  color: $ink-500;
  cursor: pointer;
  transition: all 0.15s ease;
  border: none;
  background: transparent;

  &.active {
    background: $ink-900;
    color: $bg;
  }
}

.a-lang-field { display: none; &.active { display: block; } }

// Field wrapper
.a-field {
  margin-bottom: 20px;

  label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    color: $ink-500;
    margin-bottom: 8px;
  }

  input[type="text"],
  input[type="email"],
  input[type="password"],
  input[type="number"],
  input[type="file"],
  textarea,
  select {
    width: 100%;
    border: 1px solid $line-strong;
    border-radius: $radius-sm;
    padding: 10px 14px;
    font-size: 14px;
    background: $bg;
    transition: border-color 0.15s ease;
    &:focus { outline: none; border-color: $accent; }
  }

  textarea { resize: vertical; min-height: 100px; }

  .a-field-error {
    font-size: 12px;
    color: #c62828;
    margin-top: 4px;
  }
}

.a-field-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;

  @media (max-width: $bp-tablet) { grid-template-columns: 1fr; }
}

// Toggle / checkbox
.a-toggle {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;

  input[type="checkbox"] {
    appearance: none;
    width: 40px;
    height: 22px;
    border-radius: 999px;
    background: $line-strong;
    position: relative;
    cursor: pointer;
    transition: background 0.2s ease;
    flex-shrink: 0;
    border: none;
    padding: 0;

    &::after {
      content: "";
      position: absolute;
      top: 3px;
      left: 3px;
      width: 16px;
      height: 16px;
      border-radius: 50%;
      background: #fff;
      transition: transform 0.2s ease;
    }

    &:checked {
      background: $accent;
      &::after { transform: translateX(18px); }
    }
  }

  span { font-size: 14px; color: $ink-700; }
}

// Quill editor container
.a-quill-wrap {
  border: 1px solid $line-strong;
  border-radius: $radius-sm;
  overflow: hidden;

  .ql-toolbar { border: none; border-bottom: 1px solid $line; background: $bg-alt; }
  .ql-container { border: none; font-family: $font-sans; font-size: 15px; min-height: 220px; }
}

// Hero image preview
.a-img-preview {
  margin-top: 12px;
  position: relative;
  display: inline-block;

  img {
    height: 140px;
    border-radius: $radius-sm;
    object-fit: cover;
    border: 1px solid $line;
  }

  .a-img-remove {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(0,0,0,0.6);
    color: #fff;
    font-size: 14px;
    line-height: 24px;
    text-align: center;
    cursor: pointer;
    &:hover { background: #c62828; }
  }
}

// Form footer (save buttons)
.a-form-footer {
  display: flex;
  align-items: center;
  gap: 10px;
  padding-top: 24px;
  border-top: 1px solid $line;
  margin-top: 8px;
}

// ─── Alerts ──────────────────────────────────────────────────
.a-alert {
  padding: 14px 18px;
  border-radius: $radius-sm;
  font-size: 14px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;

  &.a-alert-success { background: rgba(43,142,67,0.08); color: #2b8e43; border: 1px solid rgba(43,142,67,0.2); }
  &.a-alert-error   { background: rgba(217,48,37,0.08); color: #c62828; border: 1px solid rgba(217,48,37,0.2); }
}

// ─── Login ───────────────────────────────────────────────────
.a-login-shell {
  min-height: 100vh;
  background: $ink-900;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 32px;
}

.a-login-card {
  width: 100%;
  max-width: 400px;
  background: $bg;
  border-radius: $radius-lg;
  padding: 40px 36px;
  box-shadow: 0 24px 64px rgba(0,0,0,0.3);

  .a-login-logo {
    height: 44px;
    margin: 0 auto 32px;
  }

  h1 {
    font-size: 20px;
    font-weight: 600;
    letter-spacing: -0.03em;
    margin-bottom: 6px;
    text-align: center;
  }

  .a-login-sub {
    font-size: 13px;
    color: $ink-500;
    text-align: center;
    margin-bottom: 28px;
  }
}

.a-login-footer {
  margin-top: 20px;
  text-align: center;
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.06em;
  color: $ink-400;
}

// ─── Pagination ──────────────────────────────────────────────
.a-pagination {
  display: flex;
  align-items: center;
  gap: 4px;
  padding-top: 16px;
  justify-content: flex-end;
  font-size: 13px;

  a, span {
    padding: 6px 12px;
    border-radius: $radius-sm;
    border: 1px solid $line;
    color: $ink-700;
    transition: all 0.15s ease;
  }

  a:hover { border-color: $accent; color: $accent; }

  .a-page-active {
    background: $ink-900;
    color: #fff;
    border-color: $ink-900;
  }

  .a-page-disabled { opacity: 0.4; pointer-events: none; }
}
```

- [ ] **Step 4.2: Build assets**

```bash
npm run dev 2>&1 | tail -5
```

Expected: `webpack compiled successfully`

---

## Task 5: Admin Layout Rebuild

**Files:**
- Modify: `resources/views/layouts/admin.blade.php`
- Create: `resources/views/admin/partials/sidebar.blade.php`
- Modify: `resources/views/admin/dashboard.blade.php`

- [ ] **Step 5.1: Create `resources/views/admin/partials/sidebar.blade.php`**

```blade
{{-- Admin sidebar navigation --}}
@php
  $current = Route::currentRouteName();
  function aNavActive(string $current, string ...$names): string {
    foreach ($names as $name) {
      if (str_starts_with($current, $name)) return 'a-nav-active';
    }
    return '';
  }
@endphp

<aside class="a-sidebar">
  <div class="a-sidebar-logo">
    <img src="{{ asset('images/logo-fab-full-light.png') }}" alt="Fab Sourcing Admin" />
  </div>

  <p class="a-sidebar-label">Navigation</p>

  <nav class="a-nav">
    <ul>
      <li>
        <a href="{{ route('dashboard') }}" class="{{ aNavActive($current, 'dashboard') }}">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Dashboard
        </a>
      </li>
      <li>
        <a href="{{ route('pages.index') }}" class="{{ aNavActive($current, 'pages.') }}">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Pages
        </a>
      </li>
      <li>
        <a href="#" class="">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          Products
        </a>
      </li>
      <li>
        <a href="#" class="">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          Blog
        </a>
      </li>
      <li>
        <a href="#" class="">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          Services
        </a>
      </li>
      <li>
        <a href="#" class="">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Settings
        </a>
      </li>
      <li>
        <a href="#" class="">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Media
        </a>
      </li>
    </ul>
  </nav>

  <div class="a-sidebar-user">
    <span class="a-sidebar-user-name">{{ auth()->guard('admin')->user()->name }}</span>
    <a href="{{ route('adminLogout') }}">Logout</a>
  </div>
</aside>
```

- [ ] **Step 5.2: Rewrite `resources/views/layouts/admin.blade.php`**

```blade
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') — Fab Sourcing</title>

  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
  <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('images/admin-favicon.svg') }}">
  @stack('head')
</head>
<body>
<div class="a-shell">

  @include('admin.partials.sidebar')

  <div class="a-main">
    <header class="a-topbar">
      <span class="a-topbar-title">@yield('page-title', 'Dashboard')</span>
      @yield('topbar-actions')
    </header>

    <main class="a-content">
      @if(session('success'))
        <div class="a-alert a-alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="a-alert a-alert-error">{{ session('error') }}</div>
      @endif
      @if($errors->any())
        <div class="a-alert a-alert-error">
          Please fix the highlighted fields below.
        </div>
      @endif

      @yield('content')
    </main>
  </div>

</div>

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>

{{-- Lang-tab switcher --}}
<script>
(function () {
  document.querySelectorAll('.a-lang-tab').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var lang = btn.dataset.lang;
      var group = btn.closest('.a-lang-group') || document;
      group.querySelectorAll('.a-lang-tab').forEach(function (b) { b.classList.remove('active'); });
      btn.classList.add('active');
      group.querySelectorAll('.a-lang-field').forEach(function (f) {
        f.classList.toggle('active', f.dataset.lang === lang);
      });
    });
  });
})();
</script>

@stack('scripts')
</body>
</html>
```

- [ ] **Step 5.3: Rewrite `resources/views/admin/dashboard.blade.php`**

```blade
@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
  <div class="a-page-header">
    <h1>Dashboard</h1>
  </div>
  <div class="a-card" style="padding: 32px; text-align:center; color: var(--ink-500, #6b7891);">
    <p style="font-size:15px; color:#6b7891">Welcome to Fab Sourcing Admin.</p>
    <p style="margin-top:8px; font-size:13px; color:#8a96ad">Use the sidebar to manage your content.</p>
  </div>
@endsection
```

---

## Task 6: Admin Login View Rebuild

**Files:**
- Modify: `resources/views/auth/admin/login.blade.php`

- [ ] **Step 6.1: Rewrite `resources/views/auth/admin/login.blade.php`**

```blade
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login — Fab Sourcing</title>
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>

<div class="a-login-shell">
  <div class="a-login-card">

    <img class="a-login-logo" src="{{ asset('images/logo-fab-full.png') }}" alt="Fab Sourcing" />

    <h1>Admin</h1>
    <p class="a-login-sub">Sign in to manage your content.</p>

    @if(session('error'))
      <div class="a-alert a-alert-error">{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="a-alert a-alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('adminLoginPost') }}" method="POST">
      @csrf

      <div class="a-field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               required autocomplete="email" autofocus>
        @error('email')
          <div class="a-field-error">{{ $message }}</div>
        @enderror
      </div>

      <div class="a-field">
        <label for="password">Password</label>
        <input id="password" type="password" name="password"
               required autocomplete="current-password">
        @error('password')
          <div class="a-field-error">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="a-btn a-btn-primary" style="width:100%; justify-content:center; margin-top:8px;">
        Sign in
      </button>
    </form>

    <p class="a-login-footer">fab-sourcing.fr</p>
  </div>
</div>

</body>
</html>
```

---

## Task 7: PagesController Rebuild

**Files:**
- Modify: `app/Http/Controllers/PagesController.php`

- [ ] **Step 7.1: Rewrite `app/Http/Controllers/PagesController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PagesController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search', '');

        $pages = Page::when($search, function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            })
            ->orderBy('priority')
            ->paginate(20)
            ->withQueryString();

        return view('admin.pages.index', compact('pages', 'search'));
    }

    public function create(): View
    {
        $languages = Language::active()->get();
        return view('admin.pages.create', compact('languages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'slug'           => 'required|string|max:120|unique:pages,slug',
            'title.fr'       => 'required|string|max:255',
            'title.en'       => 'nullable|string|max:255',
            'meta_title.fr'  => 'nullable|string|max:255',
            'meta_title.en'  => 'nullable|string|max:255',
            'meta_description.fr' => 'nullable|string|max:500',
            'meta_description.en' => 'nullable|string|max:500',
            'content.fr'     => 'nullable|string',
            'content.en'     => 'nullable|string',
            'priority'       => 'nullable|integer|min:0|max:9999',
            'hero_image'     => 'nullable|image|max:2048',
        ]);

        $data = [
            'slug'     => $request->input('slug'),
            'priority' => $request->input('priority', 0),
            'published' => $request->boolean('published'),
        ];

        // Store hero image
        if ($request->hasFile('hero_image')) {
            $data['hero_image'] = $request->file('hero_image')
                ->store('pages', 'public');
        }

        $page = Page::create($data);

        // Translatable fields via spatie
        foreach (['title', 'meta_title', 'meta_description', 'content'] as $field) {
            foreach (['fr', 'en'] as $locale) {
                $value = $request->input("{$field}.{$locale}");
                if ($value !== null) {
                    $page->setTranslation($field, $locale, $value);
                }
            }
        }
        $page->save();

        if ($request->input('action') === 'continue') {
            return redirect()->route('pages.edit', $page)
                ->with('success', 'Page created. Continue editing below.');
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page "' . $page->getTranslation('title', 'fr', false) . '" created.');
    }

    public function show(Page $page): RedirectResponse
    {
        return redirect()->route('pages.edit', $page);
    }

    public function edit(Page $page): View
    {
        $languages = Language::active()->get();
        return view('admin.pages.edit', compact('page', 'languages'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $request->validate([
            'slug'           => 'required|string|max:120|unique:pages,slug,' . $page->id,
            'title.fr'       => 'required|string|max:255',
            'title.en'       => 'nullable|string|max:255',
            'meta_title.fr'  => 'nullable|string|max:255',
            'meta_title.en'  => 'nullable|string|max:255',
            'meta_description.fr' => 'nullable|string|max:500',
            'meta_description.en' => 'nullable|string|max:500',
            'content.fr'     => 'nullable|string',
            'content.en'     => 'nullable|string',
            'priority'       => 'nullable|integer|min:0|max:9999',
            'hero_image'     => 'nullable|image|max:2048',
        ]);

        $data = [
            'slug'      => $request->input('slug'),
            'priority'  => $request->input('priority', 0),
            'published' => $request->boolean('published'),
        ];

        // Handle hero image
        if ($request->boolean('remove_hero_image') && $page->hero_image) {
            Storage::disk('public')->delete($page->hero_image);
            $data['hero_image'] = null;
        } elseif ($request->hasFile('hero_image')) {
            if ($page->hero_image) {
                Storage::disk('public')->delete($page->hero_image);
            }
            $data['hero_image'] = $request->file('hero_image')
                ->store('pages', 'public');
        }

        $page->update($data);

        foreach (['title', 'meta_title', 'meta_description', 'content'] as $field) {
            foreach (['fr', 'en'] as $locale) {
                $value = $request->input("{$field}.{$locale}");
                if ($value !== null) {
                    $page->setTranslation($field, $locale, $value);
                }
            }
        }
        $page->save();

        if ($request->input('action') === 'continue') {
            return redirect()->route('pages.edit', $page)
                ->with('success', 'Saved.');
        }

        return redirect()->route('pages.index')
            ->with('success', 'Page updated.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        if ($page->hero_image) {
            Storage::disk('public')->delete($page->hero_image);
        }

        $title = $page->getTranslation('title', 'fr', false);
        $page->delete();

        return redirect()->route('pages.index')
            ->with('success', 'Page "' . $title . '" deleted.');
    }
}
```

- [ ] **Step 7.2: Verify the class loads**

```bash
php artisan tinker --execute="echo App\Http\Controllers\PagesController::class;"
```

Expected: `App\Http\Controllers\PagesController`

---

## Task 8: Pages Views Rebuild

**Files:**
- Modify: `resources/views/admin/pages/index.blade.php`
- Modify: `resources/views/admin/pages/create.blade.php`
- Modify: `resources/views/admin/pages/edit.blade.php`

- [ ] **Step 8.1: Rewrite `resources/views/admin/pages/index.blade.php`**

```blade
@extends('layouts.admin')

@section('page-title', 'Pages')

@section('topbar-actions')
  <a href="{{ route('pages.create') }}" class="a-btn a-btn-primary a-btn-sm">+ New Page</a>
@endsection

@section('content')
  <div class="a-page-header">
    <h1>Pages</h1>
  </div>

  {{-- Search --}}
  <form method="GET" action="{{ route('pages.index') }}" class="a-search-bar">
    <input type="text" name="search" value="{{ $search }}"
           placeholder="Search by slug or title…">
    <button type="submit" class="a-btn a-btn-ghost a-btn-sm">Search</button>
    @if($search)
      <a href="{{ route('pages.index') }}" class="a-btn a-btn-ghost a-btn-sm">Clear</a>
    @endif
  </form>

  <div class="a-card">
    <table class="a-table">
      <thead>
        <tr>
          <th>Slug</th>
          <th>Title (FR)</th>
          <th>Status</th>
          <th>Sort</th>
          <th>Updated</th>
          <th style="width:120px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pages as $page)
          <tr>
            <td>
              <code style="font-family: var(--font-mono, monospace); font-size:12px; color:#6b7891">
                {{ $page->slug }}
              </code>
            </td>
            <td>{{ $page->getTranslation('title', 'fr', false) ?: '—' }}</td>
            <td>
              @if($page->published)
                <span class="a-badge a-badge-published">Published</span>
              @else
                <span class="a-badge a-badge-draft">Draft</span>
              @endif
            </td>
            <td>{{ $page->priority }}</td>
            <td style="font-size:12px; color:#8a96ad">
              {{ $page->updated_at->format('d M Y') }}
            </td>
            <td>
              <div class="a-table-actions">
                <a href="{{ route('pages.edit', $page) }}" class="a-btn a-btn-ghost a-btn-sm">Edit</a>
                <form action="{{ route('pages.destroy', $page) }}" method="POST"
                      onsubmit="return confirm('Delete this page?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="a-btn a-btn-danger a-btn-sm">Delete</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center; padding:40px; color:#8a96ad; font-size:14px;">
              No pages found.
              @if($search) <a href="{{ route('pages.index') }}" style="color:#2b62d9">Clear search</a>@endif
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    @if($pages->hasPages())
      <div style="padding: 16px; border-top: 1px solid rgba(15,30,61,0.08);">
        <div class="a-pagination">
          @if($pages->onFirstPage())
            <span class="a-page-disabled">← Prev</span>
          @else
            <a href="{{ $pages->previousPageUrl() }}">← Prev</a>
          @endif

          @foreach($pages->getUrlRange(max(1, $pages->currentPage()-2), min($pages->lastPage(), $pages->currentPage()+2)) as $page => $url)
            @if($page == $pages->currentPage())
              <span class="a-page-active">{{ $page }}</span>
            @else
              <a href="{{ $url }}">{{ $page }}</a>
            @endif
          @endforeach

          @if($pages->hasMorePages())
            <a href="{{ $pages->nextPageUrl() }}">Next →</a>
          @else
            <span class="a-page-disabled">Next →</span>
          @endif
        </div>
      </div>
    @endif
  </div>
@endsection
```

- [ ] **Step 8.2: Rewrite `resources/views/admin/pages/create.blade.php`**

```blade
@extends('layouts.admin')

@section('page-title', 'New Page')

@section('content')
  <div class="a-page-header">
    <h1>New Page</h1>
    <a href="{{ route('pages.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Back</a>
  </div>

  <form action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Lang switcher --}}
    <div class="a-lang-group">
      <div class="a-lang-bar">
        <button type="button" class="a-lang-tab active" data-lang="fr">FR</button>
        <button type="button" class="a-lang-tab" data-lang="en">EN</button>
      </div>

      {{-- Core fields --}}
      <div class="a-form-card">
        <p class="a-section-title">Content</p>

        {{-- Title --}}
        <div class="a-field">
          <label>Title <span style="color:#c62828">*</span> <span style="font-weight:400;text-transform:none;font-size:11px;color:#8a96ad">(FR required · EN optional, falls back to FR)</span></label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="title[fr]" value="{{ old('title.fr') }}" placeholder="Titre (Français)" />
            @error('title.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="title[en]" value="{{ old('title.en') }}" placeholder="Title (English)" />
          </div>
        </div>

        {{-- Body content --}}
        <div class="a-field">
          <label>Body Content</label>
          <div class="a-lang-field active" data-lang="fr">
            <div class="a-quill-wrap">
              <div id="quill-content-fr"></div>
            </div>
            <textarea name="content[fr]" id="content-fr-input" style="display:none">{{ old('content.fr') }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <div class="a-quill-wrap">
              <div id="quill-content-en"></div>
            </div>
            <textarea name="content[en]" id="content-en-input" style="display:none">{{ old('content.en') }}</textarea>
          </div>
        </div>
      </div>

      {{-- Meta fields --}}
      <div class="a-form-card">
        <p class="a-section-title">SEO / Meta</p>

        <div class="a-field">
          <label>Meta Title</label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="meta_title[fr]" value="{{ old('meta_title.fr') }}" placeholder="Meta title (Français)" />
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="meta_title[en]" value="{{ old('meta_title.en') }}" placeholder="Meta title (English)" />
          </div>
        </div>

        <div class="a-field">
          <label>Meta Description</label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="meta_description[fr]" rows="3" placeholder="Meta description (Français)">{{ old('meta_description.fr') }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="meta_description[en]" rows="3" placeholder="Meta description (English)">{{ old('meta_description.en') }}</textarea>
          </div>
        </div>
      </div>
    </div>{{-- end .a-lang-group --}}

    {{-- Settings (not translatable) --}}
    <div class="a-form-card">
      <p class="a-section-title">Settings</p>

      <div class="a-field-row">
        <div class="a-field">
          <label>Slug <span style="color:#c62828">*</span></label>
          <input type="text" name="slug" value="{{ old('slug') }}" placeholder="e.g. home" />
          @error('slug')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Sort Order</label>
          <input type="number" name="priority" value="{{ old('priority', 0) }}" min="0" />
        </div>
      </div>

      <div class="a-field">
        <label>Hero Image</label>
        <input type="file" name="hero_image" accept="image/*" />
        @error('hero_image')<div class="a-field-error">{{ $message }}</div>@enderror
      </div>

      <div class="a-field">
        <label class="a-toggle">
          <input type="checkbox" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
          <span>Published</span>
        </label>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" name="action" value="save" class="a-btn a-btn-primary">Save &amp; Close</button>
      <button type="submit" name="action" value="continue" class="a-btn a-btn-ghost">Save &amp; Continue Editing</button>
      <a href="{{ route('pages.index') }}" class="a-btn a-btn-ghost" style="margin-left:auto">Cancel</a>
    </div>
  </form>
@endsection

@push('scripts')
<script>
(function () {
  var quillFr = new Quill('#quill-content-fr', { theme: 'snow', modules: { toolbar: [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link', 'clean']
  ]}});
  var quillEn = new Quill('#quill-content-en', { theme: 'snow', modules: { toolbar: [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link', 'clean']
  ]}});

  // Pre-fill from old() values if present
  var initFr = document.getElementById('content-fr-input').value;
  var initEn = document.getElementById('content-en-input').value;
  if (initFr) quillFr.clipboard.dangerouslyPasteHTML(initFr);
  if (initEn) quillEn.clipboard.dangerouslyPasteHTML(initEn);

  document.querySelector('form').addEventListener('submit', function () {
    document.getElementById('content-fr-input').value = quillFr.getSemanticHTML();
    document.getElementById('content-en-input').value = quillEn.getSemanticHTML();
  });
})();
</script>
@endpush
```

- [ ] **Step 8.3: Rewrite `resources/views/admin/pages/edit.blade.php`**

```blade
@extends('layouts.admin')

@section('page-title', 'Edit Page')

@section('content')
  <div class="a-page-header">
    <h1>Edit: <span style="color:#2b62d9">{{ $page->getTranslation('title','fr',false) ?: $page->slug }}</span></h1>
    <a href="{{ route('pages.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Back</a>
  </div>

  <form action="{{ route('pages.update', $page) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Lang switcher --}}
    <div class="a-lang-group">
      <div class="a-lang-bar">
        <button type="button" class="a-lang-tab active" data-lang="fr">FR</button>
        <button type="button" class="a-lang-tab" data-lang="en">EN</button>
      </div>

      <div class="a-form-card">
        <p class="a-section-title">Content</p>

        <div class="a-field">
          <label>Title <span style="color:#c62828">*</span> <span style="font-weight:400;text-transform:none;font-size:11px;color:#8a96ad">(FR required · EN optional)</span></label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="title[fr]"
                   value="{{ old('title.fr', $page->getTranslation('title','fr',false)) }}"
                   placeholder="Titre (Français)" />
            @error('title.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="title[en]"
                   value="{{ old('title.en', $page->getTranslation('title','en',false)) }}"
                   placeholder="Title (English)" />
          </div>
        </div>

        <div class="a-field">
          <label>Body Content</label>
          <div class="a-lang-field active" data-lang="fr">
            <div class="a-quill-wrap">
              <div id="quill-content-fr"></div>
            </div>
            <textarea name="content[fr]" id="content-fr-input" style="display:none">{{ old('content.fr', $page->getTranslation('content','fr',false)) }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <div class="a-quill-wrap">
              <div id="quill-content-en"></div>
            </div>
            <textarea name="content[en]" id="content-en-input" style="display:none">{{ old('content.en', $page->getTranslation('content','en',false)) }}</textarea>
          </div>
        </div>
      </div>

      <div class="a-form-card">
        <p class="a-section-title">SEO / Meta</p>

        <div class="a-field">
          <label>Meta Title</label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="meta_title[fr]"
                   value="{{ old('meta_title.fr', $page->getTranslation('meta_title','fr',false)) }}"
                   placeholder="Meta title (Français)" />
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="meta_title[en]"
                   value="{{ old('meta_title.en', $page->getTranslation('meta_title','en',false)) }}"
                   placeholder="Meta title (English)" />
          </div>
        </div>

        <div class="a-field">
          <label>Meta Description</label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="meta_description[fr]" rows="3"
                      placeholder="Meta description (Français)">{{ old('meta_description.fr', $page->getTranslation('meta_description','fr',false)) }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="meta_description[en]" rows="3"
                      placeholder="Meta description (English)">{{ old('meta_description.en', $page->getTranslation('meta_description','en',false)) }}</textarea>
          </div>
        </div>
      </div>
    </div>{{-- end .a-lang-group --}}

    <div class="a-form-card">
      <p class="a-section-title">Settings</p>

      <div class="a-field-row">
        <div class="a-field">
          <label>Slug <span style="color:#c62828">*</span></label>
          <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="e.g. home" />
          @error('slug')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Sort Order</label>
          <input type="number" name="priority" value="{{ old('priority', $page->priority) }}" min="0" />
        </div>
      </div>

      {{-- Hero image --}}
      <div class="a-field">
        <label>Hero Image</label>
        @if($page->hero_image)
          <div class="a-img-preview">
            <img src="{{ Storage::url($page->hero_image) }}" alt="Hero" />
          </div>
          <label class="a-toggle" style="margin-top:12px">
            <input type="checkbox" name="remove_hero_image" value="1">
            <span>Remove current image</span>
          </label>
          <p style="margin-top:8px; font-size:13px; color:#8a96ad">Upload a new image to replace the current one.</p>
        @endif
        <input type="file" name="hero_image" accept="image/*" style="margin-top:8px" />
        @error('hero_image')<div class="a-field-error">{{ $message }}</div>@enderror
      </div>

      <div class="a-field">
        <label class="a-toggle">
          <input type="checkbox" name="published" value="1" {{ $page->published ? 'checked' : '' }}>
          <span>Published</span>
        </label>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" name="action" value="save" class="a-btn a-btn-primary">Save &amp; Close</button>
      <button type="submit" name="action" value="continue" class="a-btn a-btn-ghost">Save &amp; Continue Editing</button>
      <form action="{{ route('pages.destroy', $page) }}" method="POST"
            style="margin-left:auto"
            onsubmit="return confirm('Permanently delete this page?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="a-btn a-btn-danger">Delete Page</button>
      </form>
    </div>
  </form>
@endsection

@push('scripts')
<script>
(function () {
  var toolbar = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link', 'clean']
  ];
  var quillFr = new Quill('#quill-content-fr', { theme: 'snow', modules: { toolbar: toolbar }});
  var quillEn = new Quill('#quill-content-en', { theme: 'snow', modules: { toolbar: toolbar }});

  var initFr = document.getElementById('content-fr-input').value;
  var initEn = document.getElementById('content-en-input').value;
  if (initFr) quillFr.clipboard.dangerouslyPasteHTML(initFr);
  if (initEn) quillEn.clipboard.dangerouslyPasteHTML(initEn);

  document.querySelector('form:not([onsubmit])').addEventListener('submit', function () {
    document.getElementById('content-fr-input').value = quillFr.getSemanticHTML();
    document.getElementById('content-en-input').value = quillEn.getSemanticHTML();
  });
})();
</script>
@endpush
```

---

## Task 9: 6 Static Pages Seeder

**Files:**
- Create: `database/seeders/PagesSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 9.1: Create `database/seeders/PagesSeeder.php`**

```php
<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'slug'     => 'home',
                'priority' => 1,
                'title'    => ['fr' => 'Accueil',             'en' => 'Home'],
            ],
            [
                'slug'     => 'services',
                'priority' => 2,
                'title'    => ['fr' => 'Services',             'en' => 'Services'],
            ],
            [
                'slug'     => 'why-eastern-europe',
                'priority' => 3,
                'title'    => ['fr' => "Pourquoi l'Est de l'Europe", 'en' => 'Why Eastern Europe'],
            ],
            [
                'slug'     => 'methodology',
                'priority' => 4,
                'title'    => ['fr' => 'Méthodologie',         'en' => 'Methodology'],
            ],
            [
                'slug'     => 'about',
                'priority' => 5,
                'title'    => ['fr' => 'À propos',             'en' => 'About'],
            ],
            [
                'slug'     => 'contact',
                'priority' => 6,
                'title'    => ['fr' => 'Contact',              'en' => 'Contact'],
            ],
        ];

        foreach ($pages as $data) {
            $page = Page::firstOrCreate(
                ['slug' => $data['slug']],
                ['priority' => $data['priority'], 'published' => false]
            );

            foreach (['fr', 'en'] as $locale) {
                if (!$page->getTranslation('title', $locale, false)) {
                    $page->setTranslation('title', $locale, $data['title'][$locale]);
                }
            }
            $page->save();
        }
    }
}
```

- [ ] **Step 9.2: Update `database/seeders/DatabaseSeeder.php` to also call PagesSeeder**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            PagesSeeder::class,
        ]);
    }
}
```

- [ ] **Step 9.3: Run the pages seeder**

```bash
php artisan db:seed --class=PagesSeeder
```

Expected: no errors, runs cleanly.

- [ ] **Step 9.4: Verify pages were seeded**

```bash
php artisan tinker --execute="
App\Models\Page::orderBy('priority')->get(['slug','priority'])->each(function(\$p){ echo \$p->priority . ' ' . \$p->slug . PHP_EOL; });
"
```

Expected output:
```
1 home
2 services
3 why-eastern-europe
4 methodology
5 about
6 contact
```

---

## Task 10: Storage Link + Build + End-to-End Verify

**Files:**
- No code changes — verification only

- [ ] **Step 10.1: Create storage symlink**

```bash
php artisan storage:link 2>&1
```

Expected: `The [public/storage] link has been connected to [storage/app/public].` (or "already exists")

- [ ] **Step 10.2: Build assets**

```bash
npm run dev 2>&1 | tail -5
```

Expected: `webpack compiled successfully`

- [ ] **Step 10.3: Start dev server**

```bash
php artisan serve --port=8000 &
sleep 2
```

- [ ] **Step 10.4: Test login redirects to admin**

```bash
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/admin/login
```

Expected: `200`

```bash
curl -s http://localhost:8000/admin/login | grep -o 'Sign in' | head -1
```

Expected: `Sign in`

- [ ] **Step 10.5: Simulate login and verify pages route is protected**

```bash
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/admin/pages
```

Expected: `302` (redirect to login — not authenticated)

- [ ] **Step 10.6: Verify pages seeded correctly via artisan**

```bash
php artisan tinker --execute="echo App\Models\Page::count() . ' pages, ' . App\Models\Admin::count() . ' admins';"
```

Expected: `6 pages, 1 admins`

- [ ] **Step 10.7: Test create/edit/delete manually (browser)**

Instructions for manual verification:
1. Open `http://localhost:8000/admin/login`
2. Sign in with `admin@fab-sourcing.fr` / `changeme`
3. You should land on the pages list — confirm 6 pages listed
4. Click **Edit** on "Accueil (home)"
5. Add a FR title text, set published=true, click Save & Close
6. Confirm redirect to index with success flash
7. Click **+ New Page**, fill slug `test-delete`, FR title `Test`, save
8. Find the new row in the list and click **Delete**, confirm
9. Confirm the row is gone

- [ ] **Step 10.8: Stop dev server**

```bash
kill $(lsof -ti:8000) 2>/dev/null || true
```

---

## Self-Review

**Spec coverage:**
- ✅ Admin auth: uses existing `admin` guard, `AdminAuthenticated` middleware, login at `/admin/login`
- ✅ Admin user seedable: `AdminSeeder` creates admin@fab-sourcing.fr / changeme, isMaster=true
- ✅ Admin layout: sidebar with Dashboard, Pages, Products, Blog, Services, Settings, Media, Logout
- ✅ Top bar: shows admin name (in sidebar user section) + Logout
- ✅ Design consistency: same `_variables.scss` tokens, Inter font, navy sidebar
- ✅ Pages CRUD: index, create, edit, destroy — routes via existing resource route
- ✅ Page fields: slug, title (FR+EN), meta_title (FR+EN), meta_description (FR+EN), content (FR+EN via Quill), hero_image (upload), published toggle, sort order (via `priority` column)
- ✅ FR/EN editing UX: single tab switcher at top of form toggles all translatable field groups
- ✅ FR required, EN optional (validation rule `title.fr: required`, `title.en: nullable`)
- ✅ Page listing: table with slug, title FR, published badge, sort, updated date, edit/delete actions
- ✅ Search: by slug or title (Laravel `like` query)
- ✅ Pagination: `paginate(20)` with custom HTML links
- ✅ Routes: `/admin/pages`, `/admin/pages/create`, `/admin/pages/{id}/edit` — via existing resource route
- ✅ 6 static pages seeded: home, services, why-eastern-europe, methodology, about, contact

**Not in scope (deferred):**
- Products, Blog, Services, Settings, Media admin modules (sidebar links exist but point to `#`)
- Email-based password reset for admin
- Admin user management (the old `/admin/admins` routes still exist but aren't linked from new sidebar)
