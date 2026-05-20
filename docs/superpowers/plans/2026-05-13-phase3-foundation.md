# Phase 3 Foundation Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the complete technical foundation for the fab-sourcing.fr Laravel 12 multilingual website — environment, packages, DB schema, models, i18n routing, SCSS design system, and base layout — so that the homepage route renders the correct design shell with working nav and footer.

**Architecture:** Laravel 12 on the old Kernel.php stack (not the new bootstrap/app style), Laravel Mix 6 for assets, spatie/laravel-translatable for bilingual JSON columns, URL-prefix locale detection (`/{lang}/...`), SCSS extracted 1:1 from the Claude Design `styles.css` into partials, Blade layout with nav/footer matching the React design prototype.

**Tech Stack:** PHP 8.2, Laravel 12, Laravel Mix 6 (webpack), spatie/laravel-translatable, intervention/image-laravel, MySQL→SQLite (local dev), Inter + JetBrains Mono (Google Fonts), custom SCSS design system.

---

## File Map

### Created
- `database/database.sqlite`
- `database/migrations/XXXX_add_meta_to_pages_table.php`
- `database/migrations/XXXX_create_services_table.php`
- `database/migrations/XXXX_create_product_categories_table.php`
- `database/migrations/XXXX_create_products_table.php`
- `database/migrations/XXXX_create_method_steps_table.php`
- `database/migrations/XXXX_create_site_settings_table.php`
- `app/Models/Service.php`
- `app/Models/ProductCategory.php`
- `app/Models/Product.php`
- `app/Models/MethodStep.php`
- `app/Models/SiteSetting.php`
- `app/Http/Middleware/SetLocale.php`
- `app/Http/Controllers/Web/HomeController.php`
- `app/Http/Controllers/Web/ServicesController.php`
- `app/Http/Controllers/Web/ProductsController.php`
- `app/Http/Controllers/Web/WhyController.php`
- `app/Http/Controllers/Web/MethodController.php`
- `app/Http/Controllers/Web/AboutController.php`
- `app/Http/Controllers/Web/ContactController.php`
- `resources/views/web/home.blade.php`
- `resources/views/web/services.blade.php`
- `resources/views/web/products.blade.php`
- `resources/views/web/why.blade.php`
- `resources/views/web/method.blade.php`
- `resources/views/web/about.blade.php`
- `resources/views/web/contact.blade.php`
- `resources/views/partials/nav.blade.php`
- `resources/views/partials/footer.blade.php`
- `resources/lang/fr/auth.php`
- `resources/lang/fr/pagination.php`
- `resources/lang/fr/passwords.php`
- `resources/lang/fr/validation.php`
- `resources/sass/_variables.scss`
- `resources/sass/_typography.scss`
- `resources/sass/_layout.scss`
- `resources/sass/_nav.scss`
- `resources/sass/_hero.scss`
- `resources/sass/_buttons.scss`
- `resources/sass/_cards.scss`
- `resources/sass/_sections.scss`
- `resources/sass/_forms.scss`
- `resources/sass/_utils.scss`
- `public/images/logo-fab-full.png` (copied from design package)
- `public/images/logo-fab-full-light.png` (copied from design package)

### Modified
- `.env` — SQLite, APP_NAME=Fab Sourcing
- `app/Models/Page.php` — add HasTranslations trait
- `app/Http/Kernel.php` — register SetLocale middleware alias
- `routes/web.php` — full rewrite with 7 public routes + root redirect
- `resources/views/layouts/web.blade.php` — rebuild matching design HTML
- `resources/sass/web.scss` — replace all content with @imports of partials
- `webpack.mix.js` — remove conflicting postCss line

---

## Task 1: Environment Setup

**Files:**
- Modify: `.env`
- Create: `database/database.sqlite`

- [ ] **Step 1.1: Switch to SQLite and rename app**

Edit `.env` — replace the DB block and APP_NAME:

```ini
APP_NAME="Fab Sourcing"
APP_ENV=local
APP_KEY=base64:nLfpZLBOE1UXoYJBXsiEMeD2mKf8by1jUVMag8VNH/w=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=fabsourcing
# DB_USERNAME=root
# DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

- [ ] **Step 1.2: Create the SQLite database file**

```bash
touch database/database.sqlite
```

- [ ] **Step 1.3: Verify artisan can connect**

```bash
php artisan migrate:status 2>&1
```

Expected: table list or "No migrations found" — NOT a PDO connection error.

---

## Task 2: Install Packages

**Files:**
- Modify: `composer.json` (via composer require)

- [ ] **Step 2.1: Install spatie/laravel-translatable**

```bash
composer require spatie/laravel-translatable
```

Expected: Package installed, no errors.

- [ ] **Step 2.2: Install intervention/image**

```bash
composer require intervention/image-laravel
```

Expected: Package installed. (No publish needed for Phase 3 — we just need it available.)

- [ ] **Step 2.3: Verify autoload**

```bash
php artisan about 2>&1 | head -20
```

Expected: Laravel version shown, no missing class errors.

---

## Task 3: French Language Files

**Files:**
- Create: `resources/lang/fr/auth.php`
- Create: `resources/lang/fr/pagination.php`
- Create: `resources/lang/fr/passwords.php`
- Create: `resources/lang/fr/validation.php`

- [ ] **Step 3.1: Create `resources/lang/fr/auth.php`**

```php
<?php

return [
    'failed'   => 'Ces identifiants ne correspondent pas à nos enregistrements.',
    'password' => 'Le mot de passe fourni est incorrect.',
    'throttle' => 'Tentatives de connexion trop nombreuses. Veuillez réessayer dans :seconds secondes.',
];
```

- [ ] **Step 3.2: Create `resources/lang/fr/pagination.php`**

```php
<?php

return [
    'previous' => '&laquo; Précédent',
    'next'     => 'Suivant &raquo;',
];
```

- [ ] **Step 3.3: Create `resources/lang/fr/passwords.php`**

```php
<?php

return [
    'reset'     => 'Votre mot de passe a été réinitialisé.',
    'sent'      => 'Nous vous avons envoyé par e-mail le lien de réinitialisation du mot de passe.',
    'throttled' => 'Veuillez patienter avant de réessayer.',
    'token'     => 'Ce jeton de réinitialisation du mot de passe n\'est pas valide.',
    'user'      => 'Aucun utilisateur n\'est enregistré avec cette adresse e-mail.',
];
```

- [ ] **Step 3.4: Create `resources/lang/fr/validation.php`**

Copy the `en` version and translate the key strings. Minimal working version:

```php
<?php

return [
    'accepted'             => 'Le champ :attribute doit être accepté.',
    'email'                => 'Le champ :attribute doit être une adresse e-mail valide.',
    'max'                  => [
        'string' => 'Le texte de :attribute ne peut pas dépasser :max caractères.',
    ],
    'min'                  => [
        'string' => 'Le texte de :attribute doit contenir au moins :min caractères.',
    ],
    'required'             => 'Le champ :attribute est obligatoire.',
    'string'               => 'Le champ :attribute doit être une chaîne de caractères.',
    'unique'               => 'Cette valeur est déjà utilisée pour :attribute.',
    'attributes'           => [],
];
```

---

## Task 4: Migrations

**Files:**
- Create: 6 migration files (run `php artisan make:migration` for each)

- [ ] **Step 4.1: Generate migration files**

```bash
php artisan make:migration add_meta_to_pages_table --table=pages
php artisan make:migration create_services_table
php artisan make:migration create_product_categories_table
php artisan make:migration create_products_table
php artisan make:migration create_method_steps_table
php artisan make:migration create_site_settings_table
```

- [ ] **Step 4.2: Write `add_meta_to_pages_table` migration**

Open the newly created file in `database/migrations/` ending `_add_meta_to_pages_table.php`:

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
            $table->string('meta_title')->nullable()->after('priority');
            $table->text('meta_description')->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
```

- [ ] **Step 4.3: Write `create_services_table` migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description');
            $table->string('icon')->nullable();
            $table->string('number', 2);
            $table->tinyInteger('col_span')->default(4);
            $table->boolean('featured')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
```

- [ ] **Step 4.4: Write `create_product_categories_table` migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
```

- [ ] **Step 4.5: Write `create_products_table` migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')
                  ->constrained()
                  ->onDelete('restrict');
            $table->json('title');
            $table->json('description');
            $table->string('image')->nullable();
            $table->string('tag_number', 4)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

- [ ] **Step 4.6: Write `create_method_steps_table` migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('method_steps', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description');
            $table->string('number', 2);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('method_steps');
    }
};
```

- [ ] **Step 4.7: Write `create_site_settings_table` migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text | textarea | image
            $table->boolean('translatable')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
```

- [ ] **Step 4.8: Run all migrations**

```bash
php artisan migrate
```

Expected output includes lines like:
```
Running migrations...
... create_services_table ........... 12ms DONE
... create_product_categories_table . 8ms DONE
... create_products_table ........... 10ms DONE
... create_method_steps_table ....... 8ms DONE
... create_site_settings_table ...... 8ms DONE
... add_meta_to_pages_table ......... 6ms DONE
```

No errors. If `pages` table already exists with data, the alter migration runs cleanly; if DB is fresh it all runs in order.

---

## Task 5: Models

**Files:**
- Modify: `app/Models/Page.php`
- Create: `app/Models/Service.php`
- Create: `app/Models/ProductCategory.php`
- Create: `app/Models/Product.php`
- Create: `app/Models/MethodStep.php`
- Create: `app/Models/SiteSetting.php`

- [ ] **Step 5.1: Update `app/Models/Page.php` — add HasTranslations**

Replace the full file:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Page extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'content', 'slug', 'priority', 'meta_title', 'meta_description'];

    // Fields managed by spatie/laravel-translatable (JSON columns, per-locale values)
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

    // $page->settings returns a Fluent with ->code as properties
    public function getSettingsAttribute()
    {
        $map = [];
        foreach ($this->pageSettings as $s) {
            $map[$s->code] = $s->content;
        }
        return new \Illuminate\Support\Fluent($map);
    }
}
```

- [ ] **Step 5.2: Create `app/Models/Service.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title', 'description', 'icon', 'number', 'col_span', 'featured', 'sort_order',
    ];

    public array $translatable = ['title', 'description'];
}
```

- [ ] **Step 5.3: Create `app/Models/ProductCategory.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'sort_order'];

    public array $translatable = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('sort_order');
    }
}
```

- [ ] **Step 5.4: Create `app/Models/Product.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'product_category_id', 'title', 'description', 'image', 'tag_number', 'sort_order',
    ];

    public array $translatable = ['title', 'description'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? \Illuminate\Support\Facades\Storage::url($this->image) : null;
    }
}
```

- [ ] **Step 5.5: Create `app/Models/MethodStep.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MethodStep extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title', 'description', 'number', 'sort_order'];

    public array $translatable = ['title', 'description'];
}
```

- [ ] **Step 5.6: Create `app/Models/SiteSetting.php`**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type', 'translatable'];

    protected $casts = ['translatable' => 'boolean'];

    // Retrieve a setting value by key. If translatable, decode JSON and return current locale.
    public static function get(string $key, string $locale = null, $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        if ($setting->translatable) {
            $locale = $locale ?? app()->getLocale();
            $decoded = json_decode($setting->value, true);
            return $decoded[$locale] ?? $decoded[array_key_first($decoded)] ?? $default;
        }

        return $setting->value ?? $default;
    }
}
```

- [ ] **Step 5.7: Verify models load without errors**

```bash
php artisan tinker --execute="echo App\Models\Service::class;"
```

Expected: `App\Models\Service`

---

## Task 6: SetLocale Middleware & Route Registration

**Files:**
- Create: `app/Http/Middleware/SetLocale.php`
- Modify: `app/Http/Kernel.php`
- Modify: `routes/web.php`
- Create: `app/Http/Controllers/Web/HomeController.php` (and 6 siblings)

- [ ] **Step 6.1: Create `app/Http/Middleware/SetLocale.php`**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\Language;

class SetLocale
{
    // Validated locale slugs cached after first DB hit
    private static ?array $allowedLocales = null;

    public function handle(Request $request, Closure $next): mixed
    {
        $lang = $request->route('lang');
        $allowed = $this->allowedLocales();

        // Fall back to 'fr' if the URL param is missing or unrecognised
        $locale = in_array($lang, $allowed, true) ? $lang : 'fr';

        App::setLocale($locale);

        return $next($request);
    }

    private function allowedLocales(): array
    {
        if (static::$allowedLocales === null) {
            static::$allowedLocales = cache()->remember(
                'active_locale_slugs',
                now()->addHours(1),
                fn () => Language::where('status', 1)->pluck('slug')->toArray()
            );
        }
        return static::$allowedLocales;
    }
}
```

- [ ] **Step 6.2: Register the middleware alias in `app/Http/Kernel.php`**

In `$routeMiddleware` array, add one line (after the existing entries):

```php
'setlocale' => \App\Http\Middleware\SetLocale::class,
```

- [ ] **Step 6.3: Create the Web controllers directory and all 7 controllers**

Create `app/Http/Controllers/Web/` directory, then add each controller.

**`app/Http/Controllers/Web/HomeController.php`**
```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;

class HomeController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        return view('web.home', $this->commonForWebPages($lang));
    }
}
```

**`app/Http/Controllers/Web/ServicesController.php`**
```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\Service;

class ServicesController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        $services = Service::orderBy('sort_order')->get();
        return view('web.services', $this->commonForWebPages($lang) + compact('services'));
    }
}
```

**`app/Http/Controllers/Web/ProductsController.php`**
```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\Product;
use App\Models\ProductCategory;

class ProductsController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        $categories  = ProductCategory::orderBy('sort_order')->get();
        $products    = Product::with('category')->orderBy('sort_order')->get();
        return view('web.products', $this->commonForWebPages($lang) + compact('categories', 'products'));
    }
}
```

**`app/Http/Controllers/Web/WhyController.php`**
```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;

class WhyController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        return view('web.why', $this->commonForWebPages($lang));
    }
}
```

**`app/Http/Controllers/Web/MethodController.php`**
```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\MethodStep;

class MethodController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        $steps = MethodStep::orderBy('sort_order')->get();
        return view('web.method', $this->commonForWebPages($lang) + compact('steps'));
    }
}
```

**`app/Http/Controllers/Web/AboutController.php`**
```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use App\Models\SiteSetting;

class AboutController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        return view('web.about', $this->commonForWebPages($lang));
    }
}
```

**`app/Http/Controllers/Web/ContactController.php`**
```php
<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\WebPagesController;
use Illuminate\Http\Request;

class ContactController extends WebPagesController
{
    public function index(string $lang = 'fr')
    {
        return view('web.contact', $this->commonForWebPages($lang));
    }

    public function send(Request $request, string $lang = 'fr')
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:120',
            'email'   => 'required|email|max:180',
            'message' => 'required|string|max:2000',
        ]);

        // Phase 3: just flash success — real mail sending comes in a later phase
        session()->flash('contact_sent', true);

        return redirect()->route('contact', $lang);
    }
}
```

- [ ] **Step 6.4: Rewrite `routes/web.php`**

```php
<?php

use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ServicesController;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\WhyController;
use App\Http\Controllers\Web\MethodController;
use App\Http\Controllers\Web\AboutController;
use App\Http\Controllers\Web\ContactController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Auth (no prefix, no adminauth — login page must be accessible)
|--------------------------------------------------------------------------
*/
Route::get('admin/login',  'Auth\AdminAuthController@getLogin')->name('adminLogin');
Route::post('admin/login', 'Auth\AdminAuthController@postLogin')->name('adminLoginPost');
Route::get('admin/logout', 'Auth\AdminAuthController@logout')->name('adminLogout');

/*
|--------------------------------------------------------------------------
| Public site — all routes prefixed with /{lang}
|--------------------------------------------------------------------------
*/

// Root redirect: / → /fr
Route::get('/', function () {
    return redirect()->route('home', 'fr');
})->name('root');

Route::group(['prefix' => '{lang}', 'middleware' => 'setlocale'], function () {
    Route::get('/',               [HomeController::class,     'index'])->name('home');
    Route::get('/services',       [ServicesController::class,  'index'])->name('services');
    Route::get('/produits',       [ProductsController::class,  'index'])->name('products');
    Route::get('/pourquoi-lest',  [WhyController::class,       'index'])->name('why');
    Route::get('/methode',        [MethodController::class,    'index'])->name('method');
    Route::get('/a-propos',       [AboutController::class,     'index'])->name('about');
    Route::get('/contact',        [ContactController::class,   'index'])->name('contact');
    Route::post('/contact',       [ContactController::class,   'send'])->name('contact.send');
});
```

- [ ] **Step 6.5: Verify routes are registered**

```bash
php artisan route:list --path="fr" 2>&1 | head -20
```

Expected: rows for `GET /fr`, `GET /fr/services`, `GET /fr/produits`, etc.

---

## Task 7: SCSS Design System

**Files:** 10 SCSS partials + updated `web.scss`

All values come directly from `/tmp/fabdesign/fab-sourcing/project/styles.css`.

- [ ] **Step 7.1: Create `resources/sass/_variables.scss`**

```scss
// ============================================================
// Design tokens — converted from CSS custom properties
// ============================================================

// Palette
$bg:            #ffffff;
$bg-alt:        #f4f6f9;
$bg-deep:       #0d1a33;
$ink-900:       #0f1e3d;
$ink-800:       #1a2c50;
$ink-700:       #36486b;
$ink-500:       #6b7891;
$ink-400:       #8a96ad;
$ink-300:       #b8c0d0;
$line:          rgba(15, 30, 61, 0.08);
$line-strong:   rgba(15, 30, 61, 0.18);
$accent:        #2b62d9;
$accent-hover:  #1d4eb8;
$accent-soft:   rgba(43, 98, 217, 0.08);
$steel-1:       #c9d1de;
$steel-2:       #8896ae;

// Typography
$font-sans:     "Inter", -apple-system, BlinkMacSystemFont, "Helvetica Neue", Arial, sans-serif;
$font-mono:     "JetBrains Mono", ui-monospace, "SF Mono", Menlo, monospace;

// Layout
$container:     1280px;
$container-wide: 1440px;
$gutter:        32px;
$radius-sm:     4px;
$radius:        8px;
$radius-lg:     16px;

// Breakpoints
$bp-mobile:     640px;
$bp-tablet:     800px;
$bp-nav:        900px;
$bp-desktop:    1024px;
```

- [ ] **Step 7.2: Create `resources/sass/_typography.scss`**

```scss
// ============================================================
// Typography system
// ============================================================

.serif         { font-family: $font-sans; font-weight: 500; letter-spacing: -0.03em; }
.serif-italic  { font-family: $font-sans; font-style: normal; font-weight: 600; letter-spacing: -0.03em; }

.h-display em, .h-1 em, .h-2 em, .h-3 em {
  font-style: normal;
  font-weight: 600;
  color: $accent;
}

.mono {
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  font-weight: 500;
}

.eyebrow {
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: $ink-500;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  gap: 10px;

  &::before {
    content: "";
    width: 24px;
    height: 1px;
    background: $ink-500;
  }

  &.no-line::before { display: none; }
}

.h-display {
  font-family: $font-sans;
  font-weight: 600;
  font-size: clamp(56px, 8vw, 112px);
  line-height: 1.0;
  letter-spacing: -0.045em;
}

.h-1 {
  font-family: $font-sans;
  font-weight: 600;
  font-size: clamp(40px, 5.2vw, 68px);
  line-height: 1.05;
  letter-spacing: -0.04em;
}

.h-2 {
  font-family: $font-sans;
  font-weight: 600;
  font-size: clamp(30px, 3.6vw, 44px);
  line-height: 1.1;
  letter-spacing: -0.035em;
}

.h-3 {
  font-family: $font-sans;
  font-weight: 600;
  font-size: clamp(22px, 2.2vw, 26px);
  line-height: 1.25;
  letter-spacing: -0.025em;
}

.h-4 {
  font-family: $font-sans;
  font-weight: 600;
  font-size: 17px;
  letter-spacing: -0.015em;
}

.lede {
  font-size: clamp(17px, 1.4vw, 20px);
  line-height: 1.55;
  color: $ink-700;
  max-width: 56ch;
  text-wrap: pretty;
}

.body     { font-size: 16px; line-height: 1.65; color: $ink-700; text-wrap: pretty; }
.body-sm  { font-size: 14px; line-height: 1.55; color: $ink-500; }
```

- [ ] **Step 7.3: Create `resources/sass/_layout.scss`**

```scss
// ============================================================
// Layout helpers
// ============================================================

.container {
  max-width: $container;
  margin: 0 auto;
  padding: 0 $gutter;
}

.container-wide {
  max-width: $container-wide;
  margin: 0 auto;
  padding: 0 $gutter;
}

.section       { padding: clamp(72px, 9vw, 128px) 0; }
.section-tight { padding: clamp(48px, 6vw, 80px) 0; }

.divider { height: 1px; background: $line; width: 100%; }

// Section head — eyebrow + title left, lede right
.section-head {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 48px;
  align-items: end;
  margin-bottom: 56px;

  .section-head-right { color: $ink-700; }

  @media (max-width: $bp-tablet) {
    grid-template-columns: 1fr;
    gap: 16px;
  }
}
```

- [ ] **Step 7.4: Create `resources/sass/_nav.scss`**

```scss
// ============================================================
// Navigation
// ============================================================

.nav {
  position: sticky;
  top: 0;
  z-index: 50;
  background: rgba(255, 255, 255, 0.82);
  backdrop-filter: saturate(180%) blur(14px);
  -webkit-backdrop-filter: saturate(180%) blur(14px);
  border-bottom: 1px solid $line;
}

.nav-inner {
  max-width: $container-wide;
  margin: 0 auto;
  padding: 18px $gutter;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 32px;
}

.brand {
  display: flex;
  align-items: center;
  gap: 12px;
  cursor: pointer;
  flex-shrink: 0;
  text-decoration: none;
}

.brand-logo {
  height: 38px;
  width: auto;
  display: block;
  flex-shrink: 0;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 4px;

  @media (max-width: $bp-nav) { display: none; }
}

.nav-link {
  padding: 8px 14px;
  font-size: 14px;
  color: $ink-700;
  border-radius: 999px;
  transition: all 0.15s ease;
  position: relative;
  white-space: nowrap;
  text-decoration: none;

  &:hover { color: $ink-900; }

  &.active {
    color: $ink-900;

    &::after {
      content: "";
      position: absolute;
      bottom: 2px;
      left: 50%;
      transform: translateX(-50%);
      width: 4px;
      height: 4px;
      border-radius: 50%;
      background: $accent;
    }
  }
}

.nav-right {
  display: flex;
  align-items: center;
  gap: 14px;
}

.lang-toggle {
  display: inline-flex;
  border: 1px solid $line-strong;
  border-radius: 999px;
  padding: 3px;
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.08em;

  a {
    padding: 4px 10px;
    border-radius: 999px;
    color: $ink-500;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s ease;

    &.active {
      background: $ink-900;
      color: $bg;
    }
  }
}

.mobile-menu-btn {
  display: none;
  @media (max-width: $bp-nav) { display: inline-flex; }
}
```

- [ ] **Step 7.5: Create `resources/sass/_hero.scss`**

```scss
// ============================================================
// Hero — Variant A (editorial split) & Variant B (full-bleed)
// ============================================================

// Shared image block
.hero-img {
  position: relative;
  background: $bg-alt;
  border-radius: $radius;
  overflow: hidden;

  img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: grayscale(0.15) contrast(1.05);
  }
}

.hero-img-tag {
  position: absolute;
  top: 20px;
  left: 20px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 6px 12px;
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(8px);
  border-radius: 999px;
  font-family: $font-mono;
  font-size: 10px;
  letter-spacing: 0.14em;
  text-transform: uppercase;

  .dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: $accent;
    animation: pulse 2s ease-in-out infinite;
  }
}

@keyframes pulse {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.5; transform: scale(1.4); }
}

// -- Hero A
.hero-a {
  padding: clamp(40px, 6vw, 80px) 0 clamp(72px, 8vw, 112px);

  .hero-a-grid {
    display: grid;
    grid-template-columns: 1fr 0.85fr;
    gap: clamp(32px, 5vw, 72px);
    align-items: end;

    @media (max-width: $bp-nav) {
      grid-template-columns: 1fr;
    }
  }

  .hero-a-headline {
    font-family: $font-sans;
    font-weight: 600;
    font-size: clamp(56px, 6.8vw, 92px);
    line-height: 1.0;
    letter-spacing: -0.045em;
    max-width: 14ch;

    em { font-style: normal; color: $accent; font-weight: 600; }
    .line { display: block; }
  }

  .hero-a-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 28px;
    margin-top: 40px;
    padding-top: 32px;
    border-top: 1px solid $line;
    max-width: 52ch;
  }

  .hero-a-image {
    @extend .hero-img;
    aspect-ratio: 4/5;

    @media (max-width: $bp-nav) { aspect-ratio: 4/3; }
  }
}

// -- Hero B (full-bleed)
.hero-b {
  position: relative;
  min-height: clamp(560px, 80vh, 820px);
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  overflow: hidden;
  background: $bg-deep;

  .hero-b-bg {
    position: absolute;
    inset: 0;
    z-index: 0;

    img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.5) contrast(1.1) saturate(0.7) hue-rotate(190deg);
    }

    &::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(13,26,51,0.4) 0%, rgba(13,26,51,0.2) 35%, rgba(13,26,51,0.92) 100%);
    }
  }

  .hero-b-content {
    position: relative;
    z-index: 1;
    padding: clamp(72px, 10vw, 140px) 0 clamp(80px, 8vw, 100px);
    color: $bg;
  }

  .hero-b-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 48px;
    align-items: end;

    @media (max-width: $bp-nav) { grid-template-columns: 1fr; gap: 28px; }
  }

  .hero-b-headline {
    font-family: $font-sans;
    font-weight: 600;
    font-size: clamp(48px, 6.4vw, 88px);
    line-height: 1.02;
    letter-spacing: -0.04em;
    color: #fff;

    em { font-style: normal; color: $steel-1; font-weight: 600; }
  }

  .hero-b-sub {
    font-size: 17px;
    line-height: 1.55;
    color: rgba(255, 255, 255, 0.78);
    max-width: 46ch;
    margin-top: 24px;
  }

  .hero-b-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 24px;

    @media (max-width: $bp-nav) { align-items: flex-start; }
  }

  .hero-b-marquee {
    position: relative;
    z-index: 1;
    border-top: 1px solid rgba(255, 255, 255, 0.12);
    border-bottom: 1px solid rgba(255, 255, 255, 0.12);
    overflow: hidden;
    white-space: nowrap;
    padding: 18px 0;
    background: rgba(0, 0, 0, 0.25);
  }

  .hero-b-marquee-track {
    display: inline-flex;
    gap: 56px;
    animation: marquee 38s linear infinite;
    font-family: $font-mono;
    font-size: 12px;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7);

    span {
      display: inline-flex;
      align-items: center;
      gap: 14px;

      &::after {
        content: "";
        display: inline-block;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: $accent;
      }
    }
  }

  .btn-ghost {
    border-color: rgba(255, 255, 255, 0.3);
    color: #fff;

    &:hover { background: #fff; color: $ink-900; border-color: #fff; }
  }
}

@keyframes marquee {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

// -- Page hero (inner pages)
.page-hero {
  padding: clamp(72px, 8vw, 120px) 0 clamp(48px, 5vw, 72px);
  border-bottom: 1px solid $line;

  .page-hero-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 64px;
    align-items: end;

    @media (max-width: $bp-nav) { grid-template-columns: 1fr; gap: 24px; }
  }
}

.breadcrumb {
  display: flex;
  gap: 8px;
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: $ink-500;
  margin-bottom: 24px;

  span { color: $ink-300; }
  a { color: $ink-500; text-decoration: none; &:hover { color: $ink-900; } }
}
```

- [ ] **Step 7.6: Create `resources/sass/_buttons.scss`**

```scss
// ============================================================
// Buttons
// ============================================================

.btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  padding: 14px 22px;
  border-radius: 999px;
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 0.01em;
  transition: all 0.2s ease;
  white-space: nowrap;
  cursor: pointer;
  border: 1px solid transparent;
  text-decoration: none;
  font-family: $font-sans;
}

.btn-primary {
  background: $ink-900;
  color: #fff;

  &:hover { background: $accent; transform: translateY(-1px); }
}

.btn-accent {
  background: $accent;
  color: #fff;

  &:hover { background: $accent-hover; transform: translateY(-1px); }
}

.btn-ghost {
  border-color: $line-strong;
  color: $ink-900;

  &:hover { background: $ink-900; color: $bg; border-color: $ink-900; }
}

.btn-link {
  padding: 0;
  border-radius: 0;
  background: transparent;
  color: $ink-900;
  border-bottom: 1px solid $line-strong;
  padding-bottom: 4px;
  font-size: 14px;
  font-weight: 500;

  &:hover { border-color: $accent; color: $accent; }
}

.btn .arrow,
.btn-link .arrow {
  display: inline-block;
  transition: transform 0.2s ease;
}

.btn:hover .arrow,
.btn-link:hover .arrow { transform: translateX(3px); }
```

- [ ] **Step 7.7: Create `resources/sass/_cards.scss`**

```scss
// ============================================================
// Cards — services, products, values
// ============================================================

// Service cards
.services-grid {
  display: grid;
  grid-template-columns: repeat(12, 1fr);
  gap: 24px;

  @media (max-width: $bp-nav) { grid-template-columns: 1fr; }
}

.service {
  position: relative;
  padding: 32px;
  background: $bg-alt;
  border-radius: $radius;
  display: flex;
  flex-direction: column;
  min-height: 280px;
  transition: all 0.25s ease;
  overflow: hidden;

  &:hover {
    background: $ink-900;
    color: #fff;

    .service-num, .service-desc { color: rgba(255, 255, 255, 0.65); }
    .service-icon { color: #fff; }
  }

  &.col-7  { grid-column: span 7; }
  &.col-5  { grid-column: span 5; }
  &.col-4  { grid-column: span 4; }
  &.col-6  { grid-column: span 6; }
  &.col-12 { grid-column: span 12; }

  @media (max-width: $bp-nav) {
    &.col-7, &.col-5, &.col-4, &.col-6, &.col-12 { grid-column: span 1; }
  }

  &.featured {
    background: $ink-900;
    color: #fff;
    min-height: 360px;

    .service-num  { color: rgba(255, 255, 255, 0.5); }
    .service-desc { color: rgba(255, 255, 255, 0.72); }
    .service-icon { color: $accent; }

    &:hover { background: $accent; .service-icon { color: #fff; } }
  }
}

.service-num {
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.14em;
  color: $ink-500;
  margin-bottom: auto;
}

.service-title {
  font-family: $font-sans;
  font-weight: 600;
  font-size: 26px;
  line-height: 1.15;
  letter-spacing: -0.035em;
  margin: 24px 0 12px;
}

.service-desc {
  font-size: 14px;
  line-height: 1.55;
  color: $ink-500;
}

.service-icon {
  position: absolute;
  top: 32px;
  right: 32px;
  width: 28px;
  height: 28px;
  color: $ink-700;
  transition: color 0.25s ease;
}

// Product cards
.products-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;

  @media (max-width: $bp-nav)    { grid-template-columns: 1fr 1fr; }
  @media (max-width: $bp-mobile) { grid-template-columns: 1fr; }
}

.product-card {
  background: $bg-alt;
  border-radius: $radius;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  cursor: pointer;
  transition: transform 0.25s ease;
  text-decoration: none;
  color: inherit;

  &:hover { transform: translateY(-3px); }

  &:hover .product-card-img img { transform: scale(1.04); }
}

.product-card-img {
  aspect-ratio: 4/3;
  background: $bg;
  position: relative;
  overflow: hidden;

  img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
    filter: grayscale(0.1) contrast(1.05);
  }
}

.product-card-tag {
  position: absolute;
  top: 16px;
  left: 16px;
  background: rgba(255, 255, 255, 0.92);
  backdrop-filter: blur(8px);
  padding: 5px 10px;
  border-radius: 999px;
  font-family: $font-mono;
  font-size: 10px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  white-space: nowrap;
}

.product-card-body { padding: 24px; }

.product-card-title {
  font-family: $font-sans;
  font-weight: 600;
  font-size: 22px;
  line-height: 1.25;
  letter-spacing: -0.03em;
  margin-bottom: 8px;
}

.product-card-desc {
  font-size: 14px;
  color: $ink-500;
  line-height: 1.55;
}

// Filter chips (products page)
.filters {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-bottom: 40px;
  padding-bottom: 32px;
  border-bottom: 1px solid $line;
}

.filter-chip {
  padding: 8px 16px;
  border: 1px solid $line-strong;
  border-radius: 999px;
  font-size: 13px;
  color: $ink-700;
  transition: all 0.15s ease;
  text-decoration: none;
  background: transparent;
  cursor: pointer;
  font-family: $font-sans;

  &:hover { border-color: $ink-900; color: $ink-900; }

  &.active {
    background: $ink-900;
    color: $bg;
    border-color: $ink-900;
  }
}

// Value/mission grid (about, method pages)
.values-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0;
  border-top: 1px solid $line;

  @media (max-width: $bp-tablet) { grid-template-columns: 1fr; }
}

.value {
  padding: 36px 28px;
  border-right: 1px solid $line;
  border-bottom: 1px solid $line;

  &:nth-child(3n) { border-right: none; }

  @media (max-width: $bp-tablet) { border-right: none; }
}

.value-num   { font-family: $font-mono; font-size: 11px; letter-spacing: 0.12em; color: $ink-500; }
.value-title { font-family: $font-sans; font-weight: 600; font-size: 22px; line-height: 1.25; margin: 16px 0 12px; letter-spacing: -0.03em; }
.value-desc  { font-size: 14px; color: $ink-500; line-height: 1.6; }

// Image placeholder fallback
.img-placeholder {
  position: absolute;
  inset: 0;
  background:
    repeating-linear-gradient(45deg, rgba(24,21,19,0.05) 0, rgba(24,21,19,0.05) 1px, transparent 1px, transparent 8px),
    $bg-alt;
  display: flex;
  align-items: center;
  justify-content: center;
  color: $ink-500;
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.14em;
  text-transform: uppercase;
  text-align: center;
  padding: 16px;
}
```

- [ ] **Step 7.8: Create `resources/sass/_sections.scss`**

```scss
// ============================================================
// Sections — stat ribbon, why, method, CTA, footer
// ============================================================

// Stat ribbon
.stat-ribbon {
  border-top: 1px solid $line;
  border-bottom: 1px solid $line;
  background: $bg;
}

.stat-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 0;

  @media (max-width: $bp-nav) {
    grid-template-columns: repeat(2, 1fr);

    .stat:nth-child(2) { border-right: none; }
    .stat:nth-child(1), .stat:nth-child(2) { border-bottom: 1px solid $line; }
  }
}

.stat {
  padding: 36px 28px;
  border-right: 1px solid $line;

  &:last-child { border-right: none; }
}

.stat-value {
  font-family: $font-sans;
  font-weight: 600;
  font-size: clamp(40px, 4vw, 56px);
  line-height: 1;
  letter-spacing: -0.04em;
  color: $ink-900;

  sup {
    font-size: 0.5em;
    vertical-align: top;
    font-family: $font-mono;
    font-weight: 500;
    letter-spacing: 0.05em;
    margin-left: 4px;
    position: relative;
    top: 4px;
  }
}

.stat-label {
  font-family: $font-mono;
  font-size: 11px;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: $ink-500;
  margin-top: 14px;
  display: block;
}

// Why East EU (dark section)
.why-section {
  background: $ink-900;
  color: #fff;

  .eyebrow { color: rgba(255, 255, 255, 0.55); &::before { background: rgba(255, 255, 255, 0.5); } }
  .h-1 { color: #fff; em { color: $steel-1; font-style: normal; font-weight: 600; } }
}

.why-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: clamp(40px, 6vw, 96px);
  align-items: start;

  @media (max-width: $bp-nav) { grid-template-columns: 1fr; }
}

.why-list { display: flex; flex-direction: column; }

.why-item {
  display: grid;
  grid-template-columns: 80px 1fr;
  gap: 24px;
  padding: 28px 0;
  border-top: 1px solid rgba(255, 255, 255, 0.12);
  align-items: start;

  &:last-child { border-bottom: 1px solid rgba(255, 255, 255, 0.12); }

  @media (max-width: $bp-nav) { grid-template-columns: 50px 1fr; gap: 16px; }
}

.why-item-num   { font-family: $font-mono; font-size: 11px; letter-spacing: 0.14em; color: rgba(255,255,255,0.4); padding-top: 6px; }
.why-item-title { font-family: $font-sans; font-weight: 600; font-size: 22px; line-height: 1.25; letter-spacing: -0.03em; margin-bottom: 8px; em { font-style: normal; color: $steel-1; font-weight: 600; } }
.why-item-desc  { font-size: 14px; line-height: 1.6; color: rgba(255,255,255,0.65); max-width: 52ch; }

.why-feature {
  margin-top: 36px;
  position: relative;
  aspect-ratio: 4/3;
  border-radius: $radius;
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.10);

  img { width: 100%; height: 100%; object-fit: cover; filter: brightness(0.45) contrast(1.1) saturate(0.7) hue-rotate(190deg); }
}

.why-feature-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  padding: 32px;
  background: linear-gradient(180deg, rgba(13,26,51,0.15) 0%, rgba(13,26,51,0.7) 100%);
}

.why-feature-row { display: flex; align-items: center; gap: 14px; color: rgba(255,255,255,0.75); margin-bottom: 24px; }
.why-arrow { font-family: $font-mono; font-size: 14px; color: $steel-1; }

.why-feature-metrics { display: flex; align-items: flex-end; gap: 32px; color: #fff; }
.why-feature-metrics > div { display: flex; align-items: baseline; gap: 8px; }
.why-feature-sep { width: 1px; height: 36px; background: rgba(255,255,255,0.18); align-self: center; }
.why-metric-val { font-family: $font-sans; font-weight: 600; font-size: clamp(40px, 4.5vw, 56px); line-height: 1; letter-spacing: -0.04em; }
.why-metric-lbl { font-family: $font-mono; font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase; color: rgba(255,255,255,0.65); }

// Method timeline
.method-list { display: flex; flex-direction: column; }

.method-step {
  display: grid;
  grid-template-columns: 120px 1fr 1fr;
  gap: 48px;
  padding: 36px 0;
  border-top: 1px solid $line;
  align-items: start;

  &:last-child { border-bottom: 1px solid $line; }

  @media (max-width: $bp-tablet) { grid-template-columns: 1fr; gap: 12px; padding: 28px 0; }
}

.method-step-num {
  font-family: $font-sans;
  font-weight: 600;
  font-size: 56px;
  line-height: 1;
  letter-spacing: -0.05em;
  color: $ink-900;

  em { color: $accent; font-style: normal; font-weight: 600; }

  @media (max-width: $bp-tablet) { font-size: 40px; }
}

.method-step-title { font-family: $font-sans; font-weight: 600; font-size: 24px; line-height: 1.2; letter-spacing: -0.03em; padding-top: 8px; @media (max-width: $bp-tablet) { padding-top: 0; } }
.method-step-desc  { font-size: 15px; line-height: 1.6; color: $ink-700; padding-top: 12px; max-width: 48ch; }

// CTA strip
.cta-section { background: $bg-alt; }

.cta-inner {
  display: grid;
  grid-template-columns: 1.3fr 1fr;
  gap: 48px;
  align-items: end;
  padding: clamp(60px, 8vw, 120px) 0;

  @media (max-width: $bp-tablet) { grid-template-columns: 1fr; }
}

// Footer
.footer {
  background: $ink-900;
  color: #fff;
  padding: 80px 0 36px;
}

.footer-grid {
  display: grid;
  grid-template-columns: 1.5fr 1fr 1fr 1fr;
  gap: 48px;
  padding-bottom: 64px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.12);

  @media (max-width: $bp-tablet) { grid-template-columns: 1fr 1fr; }
}

.footer-logo  { height: 56px; width: auto; display: block; }
.footer-tag   { color: rgba(255, 255, 255, 0.6); font-size: 14px; margin-top: 24px; max-width: 38ch; line-height: 1.6; }

.footer-col {
  h5 {
    font-family: $font-mono;
    font-size: 11px;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.5);
    font-weight: 500;
    margin-bottom: 20px;
  }

  ul { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; }

  a {
    color: rgba(255, 255, 255, 0.85);
    font-size: 14px;
    transition: color 0.15s ease;
    text-decoration: none;

    &:hover { color: $steel-1; }
  }

  span { color: rgba(255, 255, 255, 0.85); font-size: 14px; }
}

.footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 28px;
  font-size: 12px;
  color: rgba(255, 255, 255, 0.5);
  font-family: $font-mono;
  letter-spacing: 0.08em;

  @media (max-width: $bp-tablet) { flex-direction: column; gap: 12px; }
}
```

- [ ] **Step 7.9: Create `resources/sass/_forms.scss`**

```scss
// ============================================================
// Forms
// ============================================================

.form { display: flex; flex-direction: column; gap: 20px; }

.field {
  display: flex;
  flex-direction: column;
  gap: 8px;

  label {
    font-family: $font-mono;
    font-size: 11px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: $ink-500;
    font-weight: 500;
  }

  input, textarea, select {
    background: transparent;
    border: none;
    border-bottom: 1px solid $line-strong;
    padding: 12px 0;
    font-size: 17px;
    color: $ink-900;
    font-family: $font-sans;
    font-weight: 500;
    letter-spacing: -0.015em;
    transition: border-color 0.2s ease;
    width: 100%;

    &:focus { outline: none; border-color: $accent; }
  }

  textarea { resize: vertical; min-height: 120px; font-weight: 400; font-size: 16px; }

  .field-error { font-size: 12px; color: #d93025; margin-top: 4px; }
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;

  @media (max-width: 700px) { grid-template-columns: 1fr; }
}

.form-success {
  padding: 24px;
  background: $bg-alt;
  border-radius: $radius;
  border-left: 3px solid $accent;
}
```

- [ ] **Step 7.10: Create `resources/sass/_utils.scss`**

```scss
// ============================================================
// Utilities & animations
// ============================================================

.text-accent { color: $accent; }
.text-muted  { color: $ink-500; }
.text-bg     { color: $bg; }
.bg-deep     { background: $ink-900; color: $bg; }
.bg-alt      { background: $bg-alt; }

.fade-in {
  animation: fadeIn 0.6s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(12px); }
  to   { opacity: 1; transform: translateY(0); }
}

.reveal {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.8s ease, transform 0.8s ease;

  &.in { opacity: 1; transform: translateY(0); }
}

@media (prefers-reduced-motion: reduce) {
  .reveal, .fade-in { animation: none; transition: none; opacity: 1; transform: none; }
  .hero-a-tag .dot  { animation: none; }
  .hero-b-marquee-track { animation-play-state: paused; }
}
```

- [ ] **Step 7.11: Rewrite `resources/sass/web.scss`**

Replace the entire file content:

```scss
// ============================================================
// Fab Sourcing — Web stylesheet
// ============================================================

// Google Fonts
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap');

// Design system partials (order matters: variables first)
@import 'variables';
@import 'typography';
@import 'layout';
@import 'nav';
@import 'hero';
@import 'buttons';
@import 'cards';
@import 'sections';
@import 'forms';
@import 'utils';

// Base reset & global styles
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html, body {
  background: $bg;
  color: $ink-900;
  font-family: $font-sans;
  font-size: 16px;
  line-height: 1.55;
  font-weight: 400;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-rendering: optimizeLegibility;
  scroll-behavior: smooth;
  overflow-x: hidden;
}

img   { display: block; max-width: 100%; }
a     { color: inherit; text-decoration: none; }
button { font-family: inherit; cursor: pointer; border: none; background: none; color: inherit; }
input, textarea, select { font-family: inherit; font-size: inherit; color: inherit; }

::selection { background: $accent; color: #fff; }
```

---

## Task 8: Logo Assets

**Files:**
- Create: `public/images/logo-fab-full.png`
- Create: `public/images/logo-fab-full-light.png`

- [ ] **Step 8.1: Copy logo files from design package**

```bash
cp /tmp/fabdesign/fab-sourcing/project/assets/logo-full.png public/images/logo-fab-full.png
cp /tmp/fabdesign/fab-sourcing/project/assets/logo-full-light.png public/images/logo-fab-full-light.png
```

Expected: files present in `public/images/`.

```bash
ls -lh public/images/logo-fab-full*.png
```

---

## Task 9: Base Layout Rebuild

**Files:**
- Modify: `resources/views/layouts/web.blade.php`
- Create: `resources/views/partials/nav.blade.php`
- Create: `resources/views/partials/footer.blade.php`

- [ ] **Step 9.1: Create `resources/views/partials/nav.blade.php`**

```blade
{{-- Navigation — sticky glassmorphism nav matching design --}}
<nav class="nav">
  <div class="nav-inner">

    {{-- Brand logo --}}
    <a href="{{ route('home', $lang) }}" class="brand">
      <img class="brand-logo" src="{{ asset('images/logo-fab-full.png') }}" alt="Fab Sourcing" />
    </a>

    {{-- Desktop nav links --}}
    <div class="nav-links">
      @php
        $navLinks = [
          ['route' => 'home',     'label' => $lang === 'fr' ? 'Accueil'        : 'Home'],
          ['route' => 'services', 'label' => $lang === 'fr' ? 'Services'       : 'Services'],
          ['route' => 'products', 'label' => $lang === 'fr' ? 'Produits'       : 'Products'],
          ['route' => 'why',      'label' => $lang === 'fr' ? "Pourquoi l'Est" : 'Why East EU'],
          ['route' => 'method',   'label' => $lang === 'fr' ? 'Méthode'        : 'Method'],
          ['route' => 'about',    'label' => $lang === 'fr' ? 'À propos'       : 'About'],
        ];
        $currentRoute = Route::currentRouteName();
      @endphp

      @foreach($navLinks as $link)
        <a href="{{ route($link['route'], $lang) }}"
           class="nav-link {{ $currentRoute === $link['route'] ? 'active' : '' }}">
          {{ $link['label'] }}
        </a>
      @endforeach
    </div>

    {{-- Right: language toggle + CTA --}}
    <div class="nav-right">
      <div class="lang-toggle">
        @foreach($languages as $language)
          <a href="{{ route(\Illuminate\Support\Facades\Route::current()->getName(), $language->slug) }}"
             class="{{ $language->slug === $lang ? 'active' : '' }}">
            {{ strtoupper($language->slug) }}
          </a>
        @endforeach
      </div>

      <a href="{{ route('contact', $lang) }}" class="btn btn-primary">
        {{ $lang === 'fr' ? 'Devis gratuit' : 'Free quote' }}
        <span class="arrow">→</span>
      </a>
    </div>

  </div>
</nav>
```

- [ ] **Step 9.2: Create `resources/views/partials/footer.blade.php`**

```blade
{{-- Footer — dark navy, 4-col grid --}}
<footer class="footer">
  <div class="container">
    <div class="footer-grid">

      {{-- Brand column --}}
      <div>
        <img class="footer-logo" src="{{ asset('images/logo-fab-full-light.png') }}" alt="Fab Sourcing" />
        <p class="footer-tag">
          {{ $lang === 'fr'
            ? 'Outsourcing industriel en Bulgarie &amp; Roumanie. Métallerie, structures acier, fabrication sur mesure.'
            : 'Industrial outsourcing in Bulgaria &amp; Romania. Metalwork, steel structures, custom fabrication.' }}
        </p>
      </div>

      {{-- Sitemap --}}
      <div class="footer-col">
        <h5>{{ $lang === 'fr' ? 'Plan du site' : 'Sitemap' }}</h5>
        <ul>
          <li><a href="{{ route('home',     $lang) }}">{{ $lang === 'fr' ? 'Accueil'        : 'Home' }}</a></li>
          <li><a href="{{ route('services', $lang) }}">{{ $lang === 'fr' ? 'Services'       : 'Services' }}</a></li>
          <li><a href="{{ route('products', $lang) }}">{{ $lang === 'fr' ? 'Produits'       : 'Products' }}</a></li>
          <li><a href="{{ route('why',      $lang) }}">{{ $lang === 'fr' ? "Pourquoi l'Est" : 'Why East EU' }}</a></li>
          <li><a href="{{ route('method',   $lang) }}">{{ $lang === 'fr' ? 'Méthode'        : 'Method' }}</a></li>
          <li><a href="{{ route('about',    $lang) }}">{{ $lang === 'fr' ? 'À propos'       : 'About' }}</a></li>
          <li><a href="{{ route('contact',  $lang) }}">Contact</a></li>
        </ul>
      </div>

      {{-- Contact --}}
      <div class="footer-col">
        <h5>Contact</h5>
        <ul>
          <li><span>Thierry Sudol</span></li>
          <li><a href="tel:+33782085117">+33 (0)7 82 08 51 17</a></li>
          <li><a href="mailto:tsudol.fabtec@yahoo.com">tsudol.fabtec@yahoo.com</a></li>
        </ul>
      </div>

      {{-- Office --}}
      <div class="footer-col">
        <h5>{{ $lang === 'fr' ? 'Bureau' : 'Office' }}</h5>
        <ul>
          <li><span>1, route Neuve</span></li>
          <li><span>24150 St-Capraise-de-Lalinde</span></li>
          <li><span>France</span></li>
        </ul>
      </div>

    </div>

    <div class="footer-bottom">
      <span>© {{ now()->year }} Fab Sourcing — {{ $lang === 'fr' ? 'Tous droits réservés' : 'All rights reserved' }}</span>
      <span>fab-sourcing.fr</span>
    </div>
  </div>
</footer>
```

- [ ] **Step 9.3: Rewrite `resources/views/layouts/web.blade.php`**

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title', 'Fab Sourcing — Outsourcing industriel en Bulgarie &amp; Roumanie')</title>
  <meta name="description" content="@yield('description', 'Externalisez votre production industrielle en Bulgarie et Roumanie. Qualité européenne, coûts réduits, délais courts.')">

  {{-- Preconnect for Google Fonts (performance) --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  {{-- Main stylesheet (compiled from resources/sass/web.scss) --}}
  <link href="{{ asset('css/web.css') }}" rel="stylesheet">

  @stack('head')
</head>
<body>

  @include('partials.nav')

  <main>
    @yield('content')
  </main>

  @include('partials.footer')

  {{-- Web JS bundle --}}
  <script src="{{ asset('js/web.js') }}"></script>

  {{-- Scroll reveal (tiny inline script — no jQuery needed) --}}
  <script>
    (function () {
      var obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
        });
      }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
      document.querySelectorAll('.reveal').forEach(function (el) { obs.observe(el); });
    })();
  </script>

  @stack('scripts')
</body>
</html>
```

---

## Task 10: Stub Page Views

**Files:** 7 minimal view stubs (Phase 3 only — just verify layout renders)

- [ ] **Step 10.1: Create stub views for all 7 pages**

**`resources/views/web/home.blade.php`**
```blade
@extends('layouts.web')

@section('title', $lang === 'fr' ? 'Accueil — Fab Sourcing' : 'Home — Fab Sourcing')

@section('content')
  <section class="section">
    <div class="container">
      <div class="eyebrow" style="margin-bottom: 20px">Phase 3 · Scaffold</div>
      <h1 class="h-1">Accueil <em>en construction.</em></h1>
      <p class="lede" style="margin-top: 24px">
        La page d'accueil sera construite à la phase suivante.
        Langue active&nbsp;: <strong>{{ $lang }}</strong>
      </p>
      <div style="margin-top: 32px; display: flex; gap: 12px;">
        <a href="{{ route('services', $lang) }}" class="btn btn-primary">Services <span class="arrow">→</span></a>
        <a href="{{ route('contact',  $lang) }}" class="btn btn-ghost">Contact</a>
      </div>
    </div>
  </section>
@endsection
```

Create matching stubs for the remaining 6 pages — replace the title and section label for each:

**`resources/views/web/services.blade.php`**
```blade
@extends('layouts.web')
@section('title', $lang === 'fr' ? 'Services — Fab Sourcing' : 'Services — Fab Sourcing')
@section('content')
  <section class="section"><div class="container">
    <div class="eyebrow" style="margin-bottom:20px">Phase 3 · Scaffold</div>
    <h1 class="h-1">Services</h1>
    <p class="lede" style="margin-top:24px">Langue : <strong>{{ $lang }}</strong></p>
  </div></section>
@endsection
```

**`resources/views/web/products.blade.php`**
```blade
@extends('layouts.web')
@section('title', $lang === 'fr' ? 'Produits — Fab Sourcing' : 'Products — Fab Sourcing')
@section('content')
  <section class="section"><div class="container">
    <div class="eyebrow" style="margin-bottom:20px">Phase 3 · Scaffold</div>
    <h1 class="h-1">{{ $lang === 'fr' ? 'Produits' : 'Products' }}</h1>
    <p class="lede" style="margin-top:24px">Langue : <strong>{{ $lang }}</strong></p>
  </div></section>
@endsection
```

**`resources/views/web/why.blade.php`**
```blade
@extends('layouts.web')
@section('title', $lang === 'fr' ? "Pourquoi l'Est — Fab Sourcing" : 'Why East EU — Fab Sourcing')
@section('content')
  <section class="section"><div class="container">
    <div class="eyebrow" style="margin-bottom:20px">Phase 3 · Scaffold</div>
    <h1 class="h-1">{{ $lang === 'fr' ? "Pourquoi l'Est" : 'Why East EU' }}</h1>
    <p class="lede" style="margin-top:24px">Langue : <strong>{{ $lang }}</strong></p>
  </div></section>
@endsection
```

**`resources/views/web/method.blade.php`**
```blade
@extends('layouts.web')
@section('title', $lang === 'fr' ? 'Méthode — Fab Sourcing' : 'Method — Fab Sourcing')
@section('content')
  <section class="section"><div class="container">
    <div class="eyebrow" style="margin-bottom:20px">Phase 3 · Scaffold</div>
    <h1 class="h-1">{{ $lang === 'fr' ? 'Méthode' : 'Method' }}</h1>
    <p class="lede" style="margin-top:24px">Langue : <strong>{{ $lang }}</strong></p>
  </div></section>
@endsection
```

**`resources/views/web/about.blade.php`**
```blade
@extends('layouts.web')
@section('title', $lang === 'fr' ? 'À propos — Fab Sourcing' : 'About — Fab Sourcing')
@section('content')
  <section class="section"><div class="container">
    <div class="eyebrow" style="margin-bottom:20px">Phase 3 · Scaffold</div>
    <h1 class="h-1">{{ $lang === 'fr' ? 'À propos' : 'About' }}</h1>
    <p class="lede" style="margin-top:24px">Langue : <strong>{{ $lang }}</strong></p>
  </div></section>
@endsection
```

**`resources/views/web/contact.blade.php`**
```blade
@extends('layouts.web')
@section('title', 'Contact — Fab Sourcing')
@section('content')
  <section class="section"><div class="container">
    <div class="eyebrow" style="margin-bottom:20px">Phase 3 · Scaffold</div>
    <h1 class="h-1">Contact</h1>
    <p class="lede" style="margin-top:24px">Langue : <strong>{{ $lang }}</strong></p>
  </div></section>
@endsection
```

---

## Task 11: Build & Verify

**Files:**
- Modify: `webpack.mix.js` (remove conflicting postCss line)

- [ ] **Step 11.1: Fix webpack.mix.js**

The current file has both `postCss('resources/css/app.css', ...)` and `sass('resources/sass/app.scss', ...)` outputting to `public/css/app.css` — they overwrite each other. Remove the postCss line:

```js
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/web.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/admin.scss', 'public/css')
   .sass('resources/sass/web.scss', 'public/css');
```

- [ ] **Step 11.2: Install npm deps (already present, verify)**

```bash
npm install 2>&1 | tail -5
```

Expected: packages installed or "up to date".

- [ ] **Step 11.3: Build assets**

```bash
npm run dev 2>&1 | tail -20
```

Expected: `webpack compiled successfully` — no errors. Three CSS files: `public/css/app.css`, `public/css/admin.css`, `public/css/web.css`.

If there are SCSS errors, they will appear as "ERROR in …" — fix the specific file mentioned.

- [ ] **Step 11.4: Run migrations**

```bash
php artisan migrate 2>&1
```

Expected: All migrations run cleanly. No errors.

- [ ] **Step 11.5: Seed languages table with fr + en**

```bash
php artisan tinker --execute="
App\Models\Language::truncate();
App\Models\Language::create(['name' => 'Français', 'slug' => 'fr', 'status' => 1]);
App\Models\Language::create(['name' => 'English',  'slug' => 'en', 'status' => 1]);
echo 'Done: ' . App\Models\Language::count() . ' languages';
"
```

Expected: `Done: 2 languages`

- [ ] **Step 11.6: Start dev server and verify homepage**

```bash
php artisan serve --port=8000 &
sleep 2
curl -s -o /dev/null -w "%{http_code} %{redirect_url}" http://localhost:8000/
```

Expected: `301 http://localhost:8000/fr` (root redirects to /fr)

```bash
curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/fr
```

Expected: `200`

```bash
curl -s http://localhost:8000/fr | grep -o '<nav class="nav">' | head -1
```

Expected: `<nav class="nav">` (layout is rendering)

```bash
curl -s http://localhost:8000/en/services | grep -o '<html lang="en">' | head -1
```

Expected: `<html lang="en">` (locale switching works)

- [ ] **Step 11.7: Stop dev server**

```bash
kill $(lsof -ti:8000) 2>/dev/null || true
```

---

## Self-Review

**Spec coverage:**
- ✅ SQLite + .env update (Task 1)
- ✅ spatie/laravel-translatable + intervention/image (Task 2)
- ✅ FR language files (Task 3)
- ✅ All 6 migrations (Task 4)
- ✅ All 6 models + Page updated (Task 5)
- ✅ SetLocale middleware + Kernel registration (Task 6)
- ✅ routes/web.php rewrite with 7 routes + root redirect (Task 6)
- ✅ 7 Web controllers (Task 6)
- ✅ 10 SCSS partials + web.scss entry (Task 7)
- ✅ Logo assets copied (Task 8)
- ✅ web.blade.php layout rebuilt (Task 9)
- ✅ nav + footer partials (Task 9)
- ✅ 7 stub views (Task 10)
- ✅ webpack.mix.js fix + build + migrate + server verify (Task 11)

**Not in Phase 3 (deferred to later phases):**
- Real page content (hero, stats, services grid on homepage)
- Admin CRUD for new entities
- Contact form email sending
- Media upload for products
- Database seeders with content
