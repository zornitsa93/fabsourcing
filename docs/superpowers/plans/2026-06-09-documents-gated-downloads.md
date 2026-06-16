# Documents / Téléchargements — Gated Downloads Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Let public visitors register an account, have an admin approve it, then log in to download admin-managed documents (PDFs) from a gated "Documents / Téléchargements" area; notification emails are built but disabled behind a config flag.

**Architecture:** Custom auth on Laravel's existing `web` guard + `User` model (no auth packages), mirroring the existing custom `AdminAuthController`. Documents are spatie-translatable records with files on a private disk, streamed through a gated controller. Admin gets two new resource sections (Accounts, Documents) following the existing `Route::resource` + `resources/views/admin/<res>/` pattern. All public pages/emails are bilingual via the site's `$lang === 'fr' ? … : …` convention.

**Tech Stack:** Laravel 12, PHPUnit 11, spatie/laravel-translatable 6, Blade, MySQL (sqlite :memory: for tests).

**Spec:** `docs/superpowers/specs/2026-06-09-documents-gated-downloads-design.md`

**Conventions discovered (follow these):**
- Public controllers: `app/Http/Controllers/Web/…`; public routes in `routes/web.php` inside the `{lang}` group (`->name('home')`, `route('home', $lang)`).
- Admin controllers: `app/Http/Controllers/…` (no sub-namespace); admin routes in `routes/admin.php` (loaded with `prefix('admin')` + `adminauth`, **bare route names** e.g. `pages.index`); admin views in `resources/views/admin/<res>/`; sidebar `resources/views/admin/partials/sidebar.blade.php`.
- Translatable models: `use Spatie\Translatable\HasTranslations;` + `public array $translatable = [...]`; read with `getTranslation('field', $lang, false)`.
- Public auth route names will be namespaced `member.*` to avoid clashing with the admin `documents.*` resource.

---

## Task 1: Safe test database (do this first)

**Files:**
- Modify: `phpunit.xml`

⚠️ `phpunit.xml` currently has the sqlite `:memory:` lines commented out, so `RefreshDatabase` tests would migrate/refresh the real MySQL `fabsourcing` DB and destroy dev data. Enable an in-memory sqlite test DB first.

- [ ] **Step 1: Enable sqlite for tests**

In `phpunit.xml`, inside `<php>`, replace the two commented lines with:
```xml
<server name="DB_CONNECTION" value="sqlite"/>
<server name="DB_DATABASE" value=":memory:"/>
```

- [ ] **Step 2: Verify the suite runs against sqlite**

Run: `php artisan test --testsuite=Feature`
Expected: existing tests run (SitemapTest may pass/skip); no prompt about the MySQL database.

- [ ] **Step 3: Commit**
```bash
git add phpunit.xml
git commit -m "test: run feature tests against in-memory sqlite"
```

---

## Task 2: Migrations (users columns + documents table)

**Files:**
- Create: `database/migrations/2026_06_09_100000_add_account_fields_to_users_table.php`
- Create: `database/migrations/2026_06_09_100100_create_documents_table.php`

- [ ] **Step 1: Write the users migration**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company')->nullable()->after('email');
            $table->string('phone')->nullable()->after('company');
            $table->timestamp('approved_at')->nullable()->after('password');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            $table->timestamp('gdpr_consent_at')->nullable()->after('approved_by');
            $table->string('locale', 2)->default('fr')->after('gdpr_consent_at');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company', 'phone', 'approved_at', 'approved_by', 'gdpr_consent_at', 'locale']);
        });
    }
};
```

- [ ] **Step 2: Write the documents migration**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->string('file_path');
            $table->string('original_filename');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('published')->default(true);
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('documents'); }
};
```

- [ ] **Step 3: Run the migrations**

Run: `php artisan migrate`
Expected: both migrations run "DONE".

- [ ] **Step 4: Commit**
```bash
git add database/migrations/2026_06_09_100000_add_account_fields_to_users_table.php database/migrations/2026_06_09_100100_create_documents_table.php
git commit -m "feat: migrations for account approval fields and documents table"
```

---

## Task 3: User model (approval helpers)

**Files:**
- Modify: `app/Models/User.php`
- Test: `tests/Unit/UserApprovalTest.php`

- [ ] **Step 1: Write the failing test**
```php
<?php
namespace Tests\Unit;
use App\Models\User;
use Tests\TestCase;

class UserApprovalTest extends TestCase {
    public function test_user_is_pending_by_default(): void {
        $user = new User(['name' => 'A', 'email' => 'a@b.c']);
        $this->assertFalse($user->isApproved());
    }
    public function test_user_with_approved_at_is_approved(): void {
        $user = new User();
        $user->approved_at = now();
        $this->assertTrue($user->isApproved());
    }
}
```

- [ ] **Step 2: Run it — expect fail**

Run: `php artisan test --filter=UserApprovalTest`
Expected: FAIL ("Call to undefined method isApproved").

- [ ] **Step 3: Update the model**

In `app/Models/User.php`, set `$fillable` to include the new public fields and add casts + helper:
```php
protected $fillable = ['name', 'email', 'company', 'phone', 'password', 'locale'];

protected $casts = [
    'email_verified_at' => 'datetime',
    'approved_at'       => 'datetime',
    'gdpr_consent_at'   => 'datetime',
];

public function isApproved(): bool
{
    return $this->approved_at !== null;
}
```

- [ ] **Step 4: Run it — expect pass**

Run: `php artisan test --filter=UserApprovalTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add app/Models/User.php tests/Unit/UserApprovalTest.php
git commit -m "feat: User approval fields and isApproved() helper"
```

---

## Task 4: Document model

**Files:**
- Create: `app/Models/Document.php`
- Test: `tests/Unit/DocumentModelTest.php`

- [ ] **Step 1: Write the failing test**
```php
<?php
namespace Tests\Unit;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentModelTest extends TestCase {
    use RefreshDatabase;
    public function test_translatable_title_and_published_scope(): void {
        $doc = new Document();
        $doc->setTranslation('title', 'fr', 'Catalogue');
        $doc->setTranslation('title', 'en', 'Catalogue EN');
        $doc->file_path = 'documents/x.pdf';
        $doc->original_filename = 'x.pdf';
        $doc->published = false;
        $doc->save();
        Document::create([
            'title' => ['fr' => 'B', 'en' => 'B'],
            'file_path' => 'documents/y.pdf', 'original_filename' => 'y.pdf', 'published' => true,
        ]);
        $this->assertSame('Catalogue', $doc->fresh()->getTranslation('title', 'fr'));
        $this->assertCount(1, Document::published()->get());
    }
}
```

- [ ] **Step 2: Run it — expect fail**

Run: `php artisan test --filter=DocumentModelTest`
Expected: FAIL ("Class Document not found").

- [ ] **Step 3: Create the model**
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Document extends Model {
    use HasTranslations;

    public array $translatable = ['title', 'description'];

    protected $fillable = [
        'title', 'description', 'file_path', 'original_filename',
        'file_size', 'mime_type', 'sort_order', 'published',
    ];

    protected $casts = ['published' => 'boolean'];

    public function scopePublished(Builder $q): Builder { return $q->where('published', true); }
    public function scopeOrdered(Builder $q): Builder { return $q->orderBy('sort_order')->orderByDesc('id'); }
}
```

- [ ] **Step 4: Run it — expect pass**

Run: `php artisan test --filter=DocumentModelTest`
Expected: PASS.

- [ ] **Step 5: Commit**
```bash
git add app/Models/Document.php tests/Unit/DocumentModelTest.php
git commit -m "feat: Document model with translatable title and scopes"
```

---

## Task 5: Private disk + notifications config flag

**Files:**
- Modify: `config/filesystems.php`
- Create: `config/documents.php`
- Modify: `.env.example`

- [ ] **Step 1: Add the private `documents` disk**

In `config/filesystems.php`, inside `'disks' => [ ... ]`, add:
```php
'documents' => [
    'driver'     => 'local',
    'root'       => storage_path('app/documents'),
    'visibility' => 'private',
    'throw'      => false,
],
```

- [ ] **Step 2: Create the documents config**
```php
<?php
return [
    // When false, registration/approval emails are NOT sent (code is ready; flip after SMTP is set up).
    'notifications_enabled' => env('DOCUMENTS_MAIL_ENABLED', false),
    // Admin recipient for "new pending account" notifications.
    'admin_email' => env('DOCUMENTS_ADMIN_EMAIL', 'thierry.sudol@fab-sourcing.fr'),
    'max_upload_kb' => 20480, // 20 MB
];
```

- [ ] **Step 3: Document the env flag**

Append to `.env.example`:
```
DOCUMENTS_MAIL_ENABLED=false
DOCUMENTS_ADMIN_EMAIL=thierry.sudol@fab-sourcing.fr
```

- [ ] **Step 4: Verify config loads**

Run: `php artisan config:clear && php artisan tinker --execute="echo (int) config('documents.notifications_enabled');"`
Expected: prints `0`.

- [ ] **Step 5: Commit**
```bash
git add config/filesystems.php config/documents.php .env.example
git commit -m "feat: private documents disk + notifications feature flag"
```

---

## Task 6: Public registration (pending account)

**Files:**
- Create: `app/Http/Controllers/Web/Auth/RegisterController.php`
- Create: `app/Http/Requests/RegisterRequest.php`
- Create: `resources/views/web/auth/register.blade.php`
- Modify: `routes/web.php` (inside the `{lang}` group)
- Test: `tests/Feature/RegistrationTest.php`

- [ ] **Step 1: Write the failing feature test**
```php
<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase {
    use RefreshDatabase;
    public function test_registration_creates_a_pending_user(): void {
        $res = $this->post('/fr/inscription', [
            'name' => 'Jean Dupont', 'email' => 'jean@acme.fr', 'company' => 'Acme',
            'phone' => '0102', 'password' => 'secret123', 'password_confirmation' => 'secret123',
            'gdpr' => '1',
        ]);
        $res->assertRedirect();
        $user = User::where('email', 'jean@acme.fr')->first();
        $this->assertNotNull($user);
        $this->assertNull($user->approved_at);          // pending
        $this->assertNotNull($user->gdpr_consent_at);
        $this->assertGuest();                            // not logged in until approved
    }
    public function test_registration_requires_gdpr_and_unique_email(): void {
        User::create(['name'=>'x','email'=>'dupe@acme.fr','password'=>bcrypt('x')]);
        $this->post('/fr/inscription', [
            'name'=>'Y','email'=>'dupe@acme.fr','password'=>'secret123','password_confirmation'=>'secret123',
        ])->assertSessionHasErrors(['email','gdpr']);
    }
}
```

- [ ] **Step 2: Run it — expect fail**

Run: `php artisan test --filter=RegistrationTest`
Expected: FAIL (404 / route not defined).

- [ ] **Step 3: Create the FormRequest**
```php
<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest {
    public function authorize(): bool { return true; }
    public function rules(): array {
        return [
            'name'     => ['required','string','max:120'],
            'email'    => ['required','email','max:190','unique:users,email'],
            'company'  => ['nullable','string','max:160'],
            'phone'    => ['nullable','string','max:40'],
            'password' => ['required','string','min:8','confirmed'],
            'gdpr'     => ['accepted'],
        ];
    }
}
```

- [ ] **Step 4: Create the controller** (email dispatch added in Task 12; leave the `// notify` hooks)
```php
<?php
namespace App\Http\Controllers\Web\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller {
    public function show(string $lang): View {
        return view('web.auth.register', ['lang' => $lang]);
    }
    public function store(RegisterRequest $request, string $lang): RedirectResponse {
        $user = User::create([
            'name'     => $request->string('name'),
            'email'    => $request->string('email'),
            'company'  => $request->input('company'),
            'phone'    => $request->input('phone'),
            'password' => Hash::make($request->string('password')),
            'locale'   => $lang,
        ]);
        $user->forceFill(['gdpr_consent_at' => now()])->save();

        // Task 12 wires notifications here (flag-gated):
        // Notifications::accountPending($user);

        return redirect()->route('login', $lang)->with('registered', true);
    }
}
```

- [ ] **Step 5: Add routes** — inside the `{lang}` group in `routes/web.php`, add (and `use App\Http\Controllers\Web\Auth\RegisterController;` at top):
```php
Route::get('/inscription',  [RegisterController::class, 'show'])->name('register');
Route::post('/inscription', [RegisterController::class, 'store'])->name('register.store');
```

- [ ] **Step 6: Create the bilingual view** `resources/views/web/auth/register.blade.php`

Extend `layouts.web`; a `<form method="POST" action="{{ route('register.store', $lang) }}">` with `@csrf`, fields name/email/company/phone/password/password_confirmation, a required `gdpr` checkbox, `@error` blocks, and FR/EN labels via `{{ $lang === 'fr' ? '…' : '…' }}`. Mirror the markup/classes of `resources/views/web/contact.blade.php` form. Submit button text: FR "Créer mon compte" / EN "Create my account".

- [ ] **Step 7: Run it — expect pass**

Run: `php artisan test --filter=RegistrationTest`
Expected: PASS.

- [ ] **Step 8: Commit**
```bash
git add app/Http/Controllers/Web/Auth/RegisterController.php app/Http/Requests/RegisterRequest.php resources/views/web/auth/register.blade.php routes/web.php tests/Feature/RegistrationTest.php
git commit -m "feat: public registration creating pending accounts"
```

---

## Task 7: Login / logout with approval gate

**Files:**
- Create: `app/Http/Controllers/Web/Auth/LoginController.php`
- Create: `resources/views/web/auth/login.blade.php`
- Modify: `routes/web.php` (`{lang}` group)
- Test: `tests/Feature/LoginApprovalTest.php`

- [ ] **Step 1: Write the failing test**
```php
<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginApprovalTest extends TestCase {
    use RefreshDatabase;
    private function user(bool $approved): User {
        return User::create([
            'name'=>'U','email'=>'u@acme.fr','password'=>Hash::make('secret123'),
            'approved_at'=> $approved ? now() : null,
        ]);
    }
    public function test_pending_user_cannot_log_in(): void {
        $this->user(false);
        $this->post('/fr/connexion', ['email'=>'u@acme.fr','password'=>'secret123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }
    public function test_approved_user_can_log_in(): void {
        $this->user(true);
        $this->post('/fr/connexion', ['email'=>'u@acme.fr','password'=>'secret123'])
            ->assertRedirect();
        $this->assertAuthenticated();
    }
}
```

- [ ] **Step 2: Run it — expect fail**

Run: `php artisan test --filter=LoginApprovalTest`
Expected: FAIL (404).

- [ ] **Step 3: Create the controller**
```php
<?php
namespace App\Http\Controllers\Web\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller {
    public function show(string $lang): View {
        return view('web.auth.login', ['lang' => $lang]);
    }
    public function login(Request $request, string $lang): RedirectResponse {
        $data = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);
        $user = User::where('email', $data['email'])->first();
        $pending = $lang === 'fr'
            ? "Votre compte est en attente de validation par notre équipe."
            : 'Your account is awaiting approval by our team.';
        $invalid = $lang === 'fr' ? 'Identifiants invalides.' : 'Invalid credentials.';

        if ($user && ! $user->isApproved()) {
            throw ValidationException::withMessages(['email' => $pending]);
        }
        if (! Auth::attempt($data, $request->boolean('remember'))) {
            throw ValidationException::withMessages(['email' => $invalid]);
        }
        $request->session()->regenerate();
        return redirect()->intended(route('member.documents', $lang));
    }
    public function logout(Request $request, string $lang): RedirectResponse {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home', $lang);
    }
}
```

- [ ] **Step 4: Add routes** (`{lang}` group; `use App\Http\Controllers\Web\Auth\LoginController;`):
```php
Route::get('/connexion',     [LoginController::class, 'show'])->name('login');
Route::post('/connexion',    [LoginController::class, 'login'])->name('login.post');
Route::post('/deconnexion',  [LoginController::class, 'logout'])->name('logout');
```

- [ ] **Step 5: Create the bilingual login view** `resources/views/web/auth/login.blade.php`

Extend `layouts.web`; form POST to `route('login.post', $lang)` with `@csrf`, email + password, remember checkbox, `@error('email')`, and a link to `route('register', $lang)` ("Pas encore de compte ? Inscrivez-vous" / "No account yet? Register"). Show `@if(session('registered'))` a green notice: FR "Compte créé — en attente de validation." / EN "Account created — pending approval." Mirror contact form styling.

- [ ] **Step 6: Run it — expect pass**

Run: `php artisan test --filter=LoginApprovalTest`
Expected: PASS.

- [ ] **Step 7: Commit**
```bash
git add app/Http/Controllers/Web/Auth/LoginController.php resources/views/web/auth/login.blade.php routes/web.php tests/Feature/LoginApprovalTest.php
git commit -m "feat: login/logout with pending-approval gate"
```

---

## Task 8: EnsureUserApproved middleware

**Files:**
- Create: `app/Http/Middleware/EnsureUserApproved.php`
- Modify: `app/Http/Kernel.php` (route middleware alias)

- [ ] **Step 1: Create the middleware**
```php
<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserApproved {
    public function handle(Request $request, Closure $next) {
        $lang = $request->route('lang') ?? 'fr';
        if (! Auth::check() || ! Auth::user()->isApproved()) {
            return redirect()->route('login', $lang);
        }
        return $next($request);
    }
}
```

- [ ] **Step 2: Register the alias** — in `app/Http/Kernel.php` `$routeMiddleware`/`$middlewareAliases`, add:
```php
'approved' => \App\Http\Middleware\EnsureUserApproved::class,
```

- [ ] **Step 3: Verify it resolves**

Run: `php artisan route:list --columns=uri,middleware | head` (no error) — full gating is asserted in Task 9's test.

- [ ] **Step 4: Commit**
```bash
git add app/Http/Middleware/EnsureUserApproved.php app/Http/Kernel.php
git commit -m "feat: EnsureUserApproved route middleware"
```

---

## Task 9: Documents area (gated list + streamed download)

**Files:**
- Create: `app/Http/Controllers/Web/DocumentsController.php`
- Create: `resources/views/web/documents/index.blade.php`
- Modify: `routes/web.php` (`{lang}` group)
- Modify: `resources/views/partials/footer.blade.php`
- Test: `tests/Feature/DocumentsAccessTest.php`

- [ ] **Step 1: Write the failing test**
```php
<?php
namespace Tests\Feature;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentsAccessTest extends TestCase {
    use RefreshDatabase;
    public function test_guest_is_redirected_to_login(): void {
        $this->get('/fr/documents')->assertRedirect('/fr/connexion');
    }
    public function test_pending_user_redirected(): void {
        $u = User::create(['name'=>'U','email'=>'u@a.fr','password'=>bcrypt('x'),'approved_at'=>null]);
        $this->actingAs($u)->get('/fr/documents')->assertRedirect('/fr/connexion');
    }
    public function test_approved_user_downloads_file(): void {
        Storage::fake('documents');
        Storage::disk('documents')->put('documents/cat.pdf', '%PDF-1.4 test');
        $doc = Document::create([
            'title'=>['fr'=>'Catalogue','en'=>'Catalogue'], 'file_path'=>'documents/cat.pdf',
            'original_filename'=>'catalogue.pdf','published'=>true,'mime_type'=>'application/pdf',
        ]);
        $u = User::create(['name'=>'U','email'=>'ok@a.fr','password'=>bcrypt('x'),'approved_at'=>now()]);
        $this->actingAs($u)->get("/fr/documents/{$doc->id}/telecharger")
            ->assertOk()->assertHeader('content-disposition', 'attachment; filename=catalogue.pdf');
        $this->assertSame(1, $doc->fresh()->download_count);
    }
}
```

- [ ] **Step 2: Run it — expect fail**

Run: `php artisan test --filter=DocumentsAccessTest`
Expected: FAIL (404).

- [ ] **Step 3: Create the controller**
```php
<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentsController extends Controller {
    public function index(string $lang): View {
        $documents = Document::published()->ordered()->get();
        return view('web.documents.index', compact('documents', 'lang'));
    }
    public function download(string $lang, Document $document): StreamedResponse {
        abort_unless($document->published, 404);
        abort_unless(Storage::disk('documents')->exists($document->file_path), 404);
        $document->increment('download_count');
        return Storage::disk('documents')->download($document->file_path, $document->original_filename);
    }
}
```

- [ ] **Step 4: Add gated routes** (`{lang}` group; `use App\Http\Controllers\Web\DocumentsController;`):
```php
Route::middleware(['auth','approved'])->group(function () {
    Route::get('/documents',                       [DocumentsController::class, 'index'])->name('member.documents');
    Route::get('/documents/{document}/telecharger',[DocumentsController::class, 'download'])->name('member.documents.download');
});
```

- [ ] **Step 5: Create the view** `resources/views/web/documents/index.blade.php`

Extend `layouts.web`; loop `@foreach($documents as $doc)` showing `getTranslation('title',$lang,false)` + description + a `btn btn-accent` link to `route('member.documents.download', ['lang'=>$lang,'document'=>$doc->id])`; FR/EN heading "Documents / Téléchargements". Show a logout form (`route('logout',$lang)`) + a greeting with the user name.

- [ ] **Step 6: Add the footer link** — in `resources/views/partials/footer.blade.php` sitemap `<ul>`, add (auth-aware):
```blade
@auth
  <li><a href="{{ route('member.documents', $lang) }}">{{ $lang === 'fr' ? 'Documents' : 'Documents' }}</a></li>
@else
  <li><a href="{{ route('login', $lang) }}">{{ $lang === 'fr' ? 'Documents / Téléchargements' : 'Documents / Downloads' }}</a></li>
@endauth
```

- [ ] **Step 7: Run it — expect pass**

Run: `php artisan test --filter=DocumentsAccessTest`
Expected: PASS.

- [ ] **Step 8: Commit**
```bash
git add app/Http/Controllers/Web/DocumentsController.php resources/views/web/documents/index.blade.php resources/views/partials/footer.blade.php routes/web.php tests/Feature/DocumentsAccessTest.php
git commit -m "feat: gated documents area with streamed downloads + footer link"
```

---

## Task 10: Admin — Accounts approval

**Files:**
- Create: `app/Http/Controllers/AccountsController.php`
- Create: `resources/views/admin/accounts/index.blade.php`
- Modify: `routes/admin.php`
- Modify: `resources/views/admin/partials/sidebar.blade.php`
- Test: `tests/Feature/AdminAccountsTest.php`

- [ ] **Step 1: Write the failing test**
```php
<?php
namespace Tests\Feature;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAccountsTest extends TestCase {
    use RefreshDatabase;
    private function admin(): Admin {
        return Admin::create(['name'=>'Adm','email'=>'adm@a.fr','password'=>bcrypt('secret123')]);
    }
    public function test_admin_can_approve_a_pending_user(): void {
        $u = User::create(['name'=>'U','email'=>'u@a.fr','password'=>bcrypt('x'),'approved_at'=>null]);
        $this->actingAs($this->admin(), 'admin')
            ->post("/admin/accounts/{$u->id}/approve")->assertRedirect();
        $this->assertNotNull($u->fresh()->approved_at);
        $this->assertSame($this->admin()->id ?? $u->fresh()->approved_by, $u->fresh()->approved_by ?: $u->fresh()->approved_by);
    }
}
```
(Note: the `approved_by` assertion just checks it is set; keep the simple `assertNotNull($u->fresh()->approved_by)`.)

- [ ] **Step 2: Run it — expect fail**

Run: `php artisan test --filter=AdminAccountsTest`
Expected: FAIL (404).

- [ ] **Step 3: Create the controller**
```php
<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountsController extends Controller {
    public function index(): View {
        $pending  = User::whereNull('approved_at')->latest()->get();
        $approved = User::whereNotNull('approved_at')->latest('approved_at')->get();
        return view('admin.accounts.index', compact('pending', 'approved'));
    }
    public function approve(User $account): RedirectResponse {
        $account->forceFill(['approved_at' => now(), 'approved_by' => Auth::guard('admin')->id()])->save();
        // Task 12 wires the "approved" email here (flag-gated).
        return back()->with('status', 'approved');
    }
    public function revoke(User $account): RedirectResponse {
        $account->forceFill(['approved_at' => null, 'approved_by' => null])->save();
        return back()->with('status', 'revoked');
    }
    public function destroy(User $account): RedirectResponse {
        $account->delete();
        return back()->with('status', 'deleted');
    }
}
```

- [ ] **Step 4: Add routes** — in `routes/admin.php` (`use App\Http\Controllers\AccountsController;`):
```php
Route::get('/accounts',                 [AccountsController::class, 'index'])->name('accounts.index');
Route::post('/accounts/{account}/approve',[AccountsController::class, 'approve'])->name('accounts.approve');
Route::post('/accounts/{account}/revoke', [AccountsController::class, 'revoke'])->name('accounts.revoke');
Route::delete('/accounts/{account}',      [AccountsController::class, 'destroy'])->name('accounts.destroy');
```

- [ ] **Step 5: Create the view** `resources/views/admin/accounts/index.blade.php`

Mirror `resources/views/admin/contact-submissions/index.blade.php`. Two tables: **Pending** (name, email, company, phone, registered; buttons Approve `POST accounts.approve`, Delete `DELETE accounts.destroy`) and **Approved** (+ approved_at; button Revoke). Each action a small `@csrf` form.

- [ ] **Step 6: Add sidebar links + pending badge** — in `resources/views/admin/partials/sidebar.blade.php`, add a link to `route('accounts.index')` labelled "Comptes", with a pending count badge:
```blade
@php($pendingCount = \App\Models\User::whereNull('approved_at')->count())
<a href="{{ route('accounts.index') }}">Comptes @if($pendingCount) <span class="badge">{{ $pendingCount }}</span> @endif</a>
```

- [ ] **Step 7: Run it — expect pass**

Run: `php artisan test --filter=AdminAccountsTest`
Expected: PASS.

- [ ] **Step 8: Commit**
```bash
git add app/Http/Controllers/AccountsController.php resources/views/admin/accounts/index.blade.php routes/admin.php resources/views/admin/partials/sidebar.blade.php tests/Feature/AdminAccountsTest.php
git commit -m "feat: admin account approval (approve/revoke/delete + pending badge)"
```

---

## Task 11: Admin — Documents CRUD

**Files:**
- Create: `app/Http/Controllers/DocumentsAdminController.php`
- Create: `resources/views/admin/documents/{index,create,edit}.blade.php`
- Modify: `routes/admin.php`
- Modify: `resources/views/admin/partials/sidebar.blade.php`
- Test: `tests/Feature/AdminDocumentsTest.php`

- [ ] **Step 1: Write the failing test**
```php
<?php
namespace Tests\Feature;
use App\Models\Admin;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDocumentsTest extends TestCase {
    use RefreshDatabase;
    public function test_admin_uploads_a_document_to_private_disk(): void {
        Storage::fake('documents');
        $admin = Admin::create(['name'=>'A','email'=>'a@a.fr','password'=>bcrypt('secret123')]);
        $res = $this->actingAs($admin, 'admin')->post('/admin/documents', [
            'title_fr'=>'Catalogue','title_en'=>'Catalogue','description_fr'=>'','description_en'=>'',
            'sort_order'=>1,'published'=>1,
            'file'=> UploadedFile::fake()->create('cat.pdf', 100, 'application/pdf'),
        ]);
        $res->assertRedirect();
        $doc = Document::first();
        $this->assertNotNull($doc);
        $this->assertSame('Catalogue', $doc->getTranslation('title','fr'));
        Storage::disk('documents')->assertExists($doc->file_path);
    }
}
```

- [ ] **Step 2: Run it — expect fail**

Run: `php artisan test --filter=AdminDocumentsTest`
Expected: FAIL (404).

- [ ] **Step 3: Create the controller**
```php
<?php
namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentsAdminController extends Controller {
    public function index(): View {
        return view('admin.documents.index', ['documents' => Document::ordered()->get()]);
    }
    public function create(): View { return view('admin.documents.create'); }

    public function store(Request $request): RedirectResponse {
        $data = $this->validateData($request, true);
        $doc = new Document();
        $this->fill($doc, $data);
        $this->attachFile($doc, $request);
        $doc->save();
        return redirect()->route('documents.index')->with('status', 'created');
    }
    public function edit(Document $document): View {
        return view('admin.documents.edit', ['document' => $document]);
    }
    public function update(Request $request, Document $document): RedirectResponse {
        $data = $this->validateData($request, false);
        $this->fill($document, $data);
        if ($request->hasFile('file')) {
            Storage::disk('documents')->delete($document->file_path);
            $this->attachFile($document, $request);
        }
        $document->save();
        return redirect()->route('documents.index')->with('status', 'updated');
    }
    public function destroy(Document $document): RedirectResponse {
        Storage::disk('documents')->delete($document->file_path);
        $document->delete();
        return redirect()->route('documents.index')->with('status', 'deleted');
    }

    private function validateData(Request $request, bool $fileRequired): array {
        return $request->validate([
            'title_fr'      => ['required','string','max:190'],
            'title_en'      => ['required','string','max:190'],
            'description_fr'=> ['nullable','string'],
            'description_en'=> ['nullable','string'],
            'sort_order'    => ['nullable','integer','min:0'],
            'published'     => ['nullable','boolean'],
            'file'          => [$fileRequired ? 'required' : 'nullable','file','mimes:pdf','max:'.config('documents.max_upload_kb')],
        ]);
    }
    private function fill(Document $doc, array $data): void {
        $doc->setTranslation('title','fr',$data['title_fr']);
        $doc->setTranslation('title','en',$data['title_en']);
        $doc->setTranslation('description','fr',$data['description_fr'] ?? '');
        $doc->setTranslation('description','en',$data['description_en'] ?? '');
        $doc->sort_order = $data['sort_order'] ?? 0;
        $doc->published  = (bool)($data['published'] ?? false);
    }
    private function attachFile(Document $doc, Request $request): void {
        $file = $request->file('file');
        $path = $file->store('documents', 'documents');
        $doc->file_path = $path;
        $doc->original_filename = $file->getClientOriginalName();
        $doc->file_size = $file->getSize();
        $doc->mime_type = $file->getMimeType();
    }
}
```

- [ ] **Step 4: Add routes** — in `routes/admin.php` (`use App\Http\Controllers\DocumentsAdminController;`):
```php
Route::resource('documents', DocumentsAdminController::class)->except(['show']);
```

- [ ] **Step 5: Create the three views** under `resources/views/admin/documents/`

Mirror `resources/views/admin/product-categories/{index,create,edit}.blade.php`:
- `index`: table (title FR, published, size, order, edit/delete).
- `create` / `edit`: `<form enctype="multipart/form-data">` with `@csrf` (edit adds `@method('PUT')`), inputs `title_fr/title_en/description_fr/description_en/sort_order/published` + `<input type="file" name="file">` (edit: "leave empty to keep current file"), validation `@error` blocks.

- [ ] **Step 6: Add sidebar link** — in `resources/views/admin/partials/sidebar.blade.php` add `<a href="{{ route('documents.index') }}">Documents</a>`.

- [ ] **Step 7: Run it — expect pass**

Run: `php artisan test --filter=AdminDocumentsTest`
Expected: PASS.

- [ ] **Step 8: Commit**
```bash
git add app/Http/Controllers/DocumentsAdminController.php resources/views/admin/documents routes/admin.php resources/views/admin/partials/sidebar.blade.php tests/Feature/AdminDocumentsTest.php
git commit -m "feat: admin documents CRUD with private-disk uploads"
```

---

## Task 12: Notification emails (built, flag-gated, dispatched)

**Files:**
- Create: `app/Mail/AccountPendingAdmin.php`, `app/Mail/AccountReceivedUser.php`, `app/Mail/AccountApprovedUser.php`
- Create: `resources/views/emails/{account-pending-admin,account-received-user,account-approved-user}.blade.php`
- Create: `app/Support/AccountNotifications.php`
- Modify: `app/Http/Controllers/Web/Auth/RegisterController.php` (call hook)
- Modify: `app/Http/Controllers/AccountsController.php` (call hook)
- Test: `tests/Feature/AccountEmailsTest.php`

- [ ] **Step 1: Write the failing test (flag off = nothing sent; flag on = sent)**
```php
<?php
namespace Tests\Feature;
use App\Mail\AccountApprovedUser;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AccountEmailsTest extends TestCase {
    use RefreshDatabase;
    public function test_no_email_when_flag_disabled(): void {
        config(['documents.notifications_enabled' => false]);
        Mail::fake();
        $u = User::create(['name'=>'U','email'=>'u@a.fr','password'=>bcrypt('x')]);
        $admin = Admin::create(['name'=>'A','email'=>'a@a.fr','password'=>bcrypt('secret123')]);
        $this->actingAs($admin,'admin')->post("/admin/accounts/{$u->id}/approve");
        Mail::assertNothingSent();
    }
    public function test_email_sent_when_flag_enabled(): void {
        config(['documents.notifications_enabled' => true]);
        Mail::fake();
        $u = User::create(['name'=>'U','email'=>'u@a.fr','password'=>bcrypt('x')]);
        $admin = Admin::create(['name'=>'A','email'=>'a@a.fr','password'=>bcrypt('secret123')]);
        $this->actingAs($admin,'admin')->post("/admin/accounts/{$u->id}/approve");
        Mail::assertSent(AccountApprovedUser::class);
    }
}
```

- [ ] **Step 2: Run it — expect fail**

Run: `php artisan test --filter=AccountEmailsTest`
Expected: FAIL (class AccountApprovedUser not found).

- [ ] **Step 3: Create the three Mailables** — each a standard `Illuminate\Mail\Mailable` taking the `User` in its constructor, with `envelope()` subject FR/EN by `$user->locale`, and `content(view: 'emails.<name>')`. Example `app/Mail/AccountApprovedUser.php`:
```php
<?php
namespace App\Mail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountApprovedUser extends Mailable {
    use Queueable, SerializesModels;
    public function __construct(public User $user) {}
    public function envelope(): Envelope {
        $fr = ($this->user->locale ?? 'fr') === 'fr';
        return new Envelope(subject: $fr ? 'Votre compte est validé' : 'Your account is approved');
    }
    public function content(): Content {
        return new Content(view: 'emails.account-approved-user', with: ['user' => $this->user]);
    }
}
```
(`AccountReceivedUser` and `AccountPendingAdmin` follow the same shape; the admin one takes `User` and is addressed in the dispatcher to `config('documents.admin_email')`.)

- [ ] **Step 4: Create the three Blade email templates** under `resources/views/emails/` — simple bilingual HTML keyed on `$user->locale` (greeting, message, and for approved a link to `route('login','fr')`).

- [ ] **Step 5: Create the flag-gated dispatcher** `app/Support/AccountNotifications.php`
```php
<?php
namespace App\Support;
use App\Mail\AccountApprovedUser;
use App\Mail\AccountPendingAdmin;
use App\Mail\AccountReceivedUser;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AccountNotifications {
    public static function pending(User $user): void {
        if (! config('documents.notifications_enabled')) return;
        Mail::to(config('documents.admin_email'))->send(new AccountPendingAdmin($user));
        Mail::to($user->email)->send(new AccountReceivedUser($user));
    }
    public static function approved(User $user): void {
        if (! config('documents.notifications_enabled')) return;
        Mail::to($user->email)->send(new AccountApprovedUser($user));
    }
}
```

- [ ] **Step 6: Wire the hooks**
  - In `RegisterController::store`, after saving consent: `\App\Support\AccountNotifications::pending($user);`
  - In `AccountsController::approve`, after saving: `\App\Support\AccountNotifications::approved($account);`

- [ ] **Step 7: Run it — expect pass**

Run: `php artisan test --filter=AccountEmailsTest`
Expected: PASS.

- [ ] **Step 8: Commit**
```bash
git add app/Mail resources/views/emails app/Support/AccountNotifications.php app/Http/Controllers/Web/Auth/RegisterController.php app/Http/Controllers/AccountsController.php tests/Feature/AccountEmailsTest.php
git commit -m "feat: account notification emails (flag-gated, off by default)"
```

---

## Task 13: Full-suite green + deploy notes

- [ ] **Step 1: Run the whole suite**

Run: `php artisan test`
Expected: all green.

- [ ] **Step 2: Manual smoke (local)**

Register at `/fr/inscription` → see pending notice at `/fr/connexion` → approve in `/admin/accounts` → log in → `/fr/documents` lists docs uploaded via `/admin/documents` → download streams the PDF.

- [ ] **Step 3: Record deploy steps** (for the server)
```
git pull origin main
php artisan migrate --force
mkdir -p storage/app/documents
php artisan config:clear && php artisan view:clear
# Later, to enable emails: set MAIL_* + DOCUMENTS_MAIL_ENABLED=true in .env, then php artisan config:clear
```

- [ ] **Step 4: Commit any docs**
```bash
git add docs/superpowers/plans/2026-06-09-documents-gated-downloads.md
git commit -m "docs: documents gated-downloads implementation plan"
```

---

## Self-review notes (done)

- **Spec coverage:** §3 data model → Tasks 2–4; §4 storage → Task 5/9; §5 public flow → Tasks 6,7,9; §6 admin → Tasks 10,11; §7 emails (flag) → Tasks 5,12; §8 security/GDPR → Tasks 6 (gdpr/throttle note),7,9; §9 bilingual → views in Tasks 6,7,9,12; §10 deploy → Task 13.
- **Throttle:** add `->middleware('throttle:6,1')` to the `login.post` and `register.store` routes when wiring Task 6/7 routes (noted here so it isn't missed).
- **Types consistent:** `isApproved()`, `Document::published()/ordered()`, `member.documents`, `member.documents.download`, `accounts.*`, `documents.*` (admin), `AccountNotifications::pending/approved` are used consistently across tasks.
</content>
