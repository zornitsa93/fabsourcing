@extends('layouts.admin')

@section('page-title', 'Нов продукт')

@section('content')
  <div class="a-page-header">
    <h1>Нов продукт</h1>
    <a href="{{ route('products.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Назад</a>
  </div>

  <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Top settings bar --}}
    <div class="a-form-card">
      <p class="a-section-title">Основна информация</p>
      <div class="a-field-row">
        <div class="a-field">
          <label>Категория <span style="color:#c62828">*</span></label>
          <select name="product_category_id">
            <option value="">— изберете —</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ old('product_category_id') == $cat->id ? 'selected' : '' }}>
                {{ $cat->getTranslation('name','fr',false) }}
              </option>
            @endforeach
          </select>
          @error('product_category_id')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Slug <span style="color:#c62828">*</span></label>
          <input type="text" name="slug" value="{{ old('slug') }}" placeholder="напр. escalier-acier-z1" />
          @error('slug')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Приоритет</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" />
        </div>
        <div class="a-field" style="display:flex; align-items:flex-end; padding-bottom:2px">
          <label class="a-toggle">
            <input type="checkbox" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
            <span>Публикуван</span>
          </label>
        </div>
      </div>
    </div>

    {{-- Content tabs --}}
    <div class="a-prod-tabs">
      <div class="a-prod-tab-bar">
        <button type="button" class="a-prod-tab active" data-tab="tab-fr">FR Съдържание</button>
        <button type="button" class="a-prod-tab" data-tab="tab-en">EN Content</button>
        <button type="button" class="a-prod-tab" data-tab="tab-images">Изображения</button>
        <button type="button" class="a-prod-tab" data-tab="tab-seo">SEO</button>
      </div>

      {{-- FR --}}
      <div id="tab-fr" class="a-prod-tab-panel active">
        <div class="a-form-card">
          <p class="a-section-title">Съдържание (FR)</p>

          <div class="a-field">
            <label>Наименование (FR) <span style="color:#c62828">*</span></label>
            <input type="text" name="name[fr]" value="{{ old('name.fr') }}" placeholder="Nom du produit en français" />
            @error('name.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>

          <div class="a-field">
            <label>Кратко описание (FR)</label>
            <textarea name="short_description[fr]" rows="3" placeholder="Description courte…">{{ old('short_description.fr') }}</textarea>
          </div>

          <div class="a-field">
            <label>Пълно описание (FR)</label>
            <div class="a-quill-wrap"><div id="quill-full-fr"></div></div>
            <textarea name="full_description[fr]" id="full-fr-input" style="display:none">{{ old('full_description.fr') }}</textarea>
          </div>

          <div class="a-field">
            <label>Характеристики (FR) <span style="font-weight:400;font-size:11px;color:#8a96ad">— един ред = едно предимство</span></label>
            <div id="features-fr-list" class="a-bullets-list">
              @foreach(old('features_fr', []) as $bullet)
                <div class="a-bullet-row">
                  <input type="text" name="features_fr[]" value="{{ $bullet }}" placeholder="Caractéristique…" />
                  <button type="button" class="a-bullet-remove a-btn a-btn-danger a-btn-sm">✕</button>
                </div>
              @endforeach
            </div>
            <button type="button" class="a-btn a-btn-ghost a-btn-sm" style="margin-top:8px" onclick="addBullet('features-fr-list','features_fr[]','Caractéristique…')">+ Добави ред</button>
          </div>

          <div class="a-field">
            <label>Материали (FR)</label>
            <textarea name="materials[fr]" rows="3" placeholder="Matériaux utilisés…">{{ old('materials.fr') }}</textarea>
          </div>

          <div class="a-field">
            <label>Спецификации (FR)</label>
            <div class="a-quill-wrap"><div id="quill-spec-fr"></div></div>
            <textarea name="specifications[fr]" id="spec-fr-input" style="display:none">{{ old('specifications.fr') }}</textarea>
          </div>
        </div>
      </div>

      {{-- EN --}}
      <div id="tab-en" class="a-prod-tab-panel">
        <div class="a-form-card">
          <p class="a-section-title">Content (EN)</p>

          <div class="a-field">
            <label>Name (EN)</label>
            <input type="text" name="name[en]" value="{{ old('name.en') }}" placeholder="Product name in English" />
          </div>

          <div class="a-field">
            <label>Short description (EN)</label>
            <textarea name="short_description[en]" rows="3" placeholder="Short description…">{{ old('short_description.en') }}</textarea>
          </div>

          <div class="a-field">
            <label>Full description (EN)</label>
            <div class="a-quill-wrap"><div id="quill-full-en"></div></div>
            <textarea name="full_description[en]" id="full-en-input" style="display:none">{{ old('full_description.en') }}</textarea>
          </div>

          <div class="a-field">
            <label>Features (EN)</label>
            <div id="features-en-list" class="a-bullets-list">
              @foreach(old('features_en', []) as $bullet)
                <div class="a-bullet-row">
                  <input type="text" name="features_en[]" value="{{ $bullet }}" placeholder="Feature…" />
                  <button type="button" class="a-bullet-remove a-btn a-btn-danger a-btn-sm">✕</button>
                </div>
              @endforeach
            </div>
            <button type="button" class="a-btn a-btn-ghost a-btn-sm" style="margin-top:8px" onclick="addBullet('features-en-list','features_en[]','Feature…')">+ Add row</button>
          </div>

          <div class="a-field">
            <label>Materials (EN)</label>
            <textarea name="materials[en]" rows="3" placeholder="Materials used…">{{ old('materials.en') }}</textarea>
          </div>

          <div class="a-field">
            <label>Specifications (EN)</label>
            <div class="a-quill-wrap"><div id="quill-spec-en"></div></div>
            <textarea name="specifications[en]" id="spec-en-input" style="display:none">{{ old('specifications.en') }}</textarea>
          </div>
        </div>
      </div>

      {{-- Images --}}
      <div id="tab-images" class="a-prod-tab-panel">
        <div class="a-form-card">
          <p class="a-section-title">Основно изображение</p>
          <div class="a-field">
            <input type="file" name="main_image" accept="image/*" />
            <p style="margin-top:6px; font-size:12px; color:#8a96ad">Препоръка: минимум 800×600px. Генерират се автоматично thumb (300×300) и medium (800×600).</p>
            @error('main_image')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="a-form-card">
          <p class="a-section-title">Галерия</p>
          <div class="a-field">
            <input type="file" name="gallery_images[]" accept="image/*" multiple />
            <p style="margin-top:6px; font-size:12px; color:#8a96ad">Може да изберете няколко файла наведнъж.</p>
          </div>
        </div>
      </div>

      {{-- SEO --}}
      <div id="tab-seo" class="a-prod-tab-panel">
        <div class="a-form-card">
          <p class="a-section-title">SEO / Мета</p>

          <div class="a-lang-group">
            <div class="a-lang-bar">
              <button type="button" class="a-lang-tab active" data-lang="fr">FR</button>
              <button type="button" class="a-lang-tab" data-lang="en">EN</button>
            </div>

            <div class="a-field">
              <label>Мета заглавие</label>
              <div class="a-lang-field active" data-lang="fr">
                <input type="text" name="meta_title[fr]" value="{{ old('meta_title.fr') }}" placeholder="Meta title (Français)" />
              </div>
              <div class="a-lang-field" data-lang="en">
                <input type="text" name="meta_title[en]" value="{{ old('meta_title.en') }}" placeholder="Meta title (English)" />
              </div>
            </div>

            <div class="a-field">
              <label>Мета описание</label>
              <div class="a-lang-field active" data-lang="fr">
                <textarea name="meta_description[fr]" rows="3" placeholder="Meta description (Français)">{{ old('meta_description.fr') }}</textarea>
              </div>
              <div class="a-lang-field" data-lang="en">
                <textarea name="meta_description[en]" rows="3" placeholder="Meta description (English)">{{ old('meta_description.en') }}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" name="action" value="save" class="a-btn a-btn-primary">Запази и затвори</button>
      <button type="submit" name="action" value="continue" class="a-btn a-btn-ghost">Запази и продължи</button>
      <a href="{{ route('products.index') }}" class="a-btn a-btn-ghost" style="margin-left:auto">Отказ</a>
    </div>
  </form>
@endsection

@push('scripts')
<script>
// ── Product tab switcher ───────────────────────────────────────────────────
(function () {
  document.querySelectorAll('.a-prod-tab').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var target = btn.dataset.tab;
      document.querySelectorAll('.a-prod-tab').forEach(function (b) { b.classList.remove('active'); });
      document.querySelectorAll('.a-prod-tab-panel').forEach(function (p) { p.classList.remove('active'); });
      btn.classList.add('active');
      document.getElementById(target).classList.add('active');
    });
  });
})();

// ── Bullet list helper ─────────────────────────────────────────────────────
function addBullet(listId, inputName, placeholder) {
  var list = document.getElementById(listId);
  var row  = document.createElement('div');
  row.className = 'a-bullet-row';
  row.innerHTML = '<input type="text" name="' + inputName + '" placeholder="' + placeholder + '" />'
    + '<button type="button" class="a-bullet-remove a-btn a-btn-danger a-btn-sm">✕</button>';
  list.appendChild(row);
  row.querySelector('input').focus();
}
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('a-bullet-remove')) {
    e.target.closest('.a-bullet-row').remove();
  }
});

// ── Quill editors ─────────────────────────────────────────────────────────
(function () {
  var toolbar = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link', 'clean']
  ];
  var editors = [
    { quill: new Quill('#quill-full-fr', { theme:'snow', modules:{ toolbar } }), input: 'full-fr-input' },
    { quill: new Quill('#quill-full-en', { theme:'snow', modules:{ toolbar } }), input: 'full-en-input' },
    { quill: new Quill('#quill-spec-fr', { theme:'snow', modules:{ toolbar } }), input: 'spec-fr-input' },
    { quill: new Quill('#quill-spec-en', { theme:'snow', modules:{ toolbar } }), input: 'spec-en-input' },
  ];
  editors.forEach(function (e) {
    var val = document.getElementById(e.input).value;
    if (val) e.quill.clipboard.dangerouslyPasteHTML(val);
  });
  document.querySelector('form').addEventListener('submit', function () {
    editors.forEach(function (e) {
      document.getElementById(e.input).value = e.quill.getSemanticHTML();
    });
  });
})();
</script>
@endpush
