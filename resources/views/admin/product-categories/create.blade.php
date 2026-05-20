@extends('layouts.admin')

@section('page-title', 'Нова категория')

@section('content')
  <div class="a-page-header">
    <h1>Нова продуктова категория</h1>
    <a href="{{ route('product-categories.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Назад</a>
  </div>

  <form action="{{ route('product-categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="a-lang-group">
      <div class="a-lang-bar">
        <button type="button" class="a-lang-tab active" data-lang="fr">FR</button>
        <button type="button" class="a-lang-tab" data-lang="en">EN</button>
      </div>

      <div class="a-form-card">
        <p class="a-section-title">Съдържание</p>

        <div class="a-field">
          <label>Наименование <span style="color:#c62828">*</span>
            <span style="font-weight:400;text-transform:none;font-size:11px;color:#8a96ad">(FR задължително · EN незадължително)</span>
          </label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="name[fr]" id="name-fr" value="{{ old('name.fr') }}" placeholder="Наименование (Френски)" />
            @error('name.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="name[en]" value="{{ old('name.en') }}" placeholder="Наименование (Английски)" />
          </div>
        </div>

        <div class="a-field">
          <label>Кратко описание <span style="font-weight:400;text-transform:none;font-size:11px;color:#8a96ad">(max 500 знака)</span></label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="description[fr]" rows="2" placeholder="Кратко описание (Френски)">{{ old('description.fr') }}</textarea>
            @error('description.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="description[en]" rows="2" placeholder="Кратко описание (Английски)">{{ old('description.en') }}</textarea>
          </div>
        </div>

        <div class="a-field">
          <label>Пълно описание <span style="font-weight:400;text-transform:none;font-size:11px;color:#8a96ad">(незадължително)</span></label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="long_description[fr]" rows="5" placeholder="Подробно описание на категорията (Френски)">{{ old('long_description.fr') }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="long_description[en]" rows="5" placeholder="Подробно описание на категорията (Английски)">{{ old('long_description.en') }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="a-form-card">
      <p class="a-section-title">Снимка</p>
      <div class="a-field">
        <label>Изображение <span style="font-weight:400;font-size:11px;color:#8a96ad">(JPG / PNG / WEBP · max 5 MB · препоръчано 4:3)</span></label>
        <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/webp" style="margin-top:4px" />
        <div id="image-preview" style="display:none; margin-top:12px">
          <img id="image-preview-img" src="" alt="Preview"
               style="max-width:240px; max-height:180px; border-radius:6px; border:1px solid rgba(15,30,61,0.12); object-fit:cover">
        </div>
        @error('image')<div class="a-field-error">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="a-form-card">
      <p class="a-section-title">Настройки</p>

      <div class="a-field-row">
        <div class="a-field">
          <label>Slug <span style="color:#c62828">*</span> <span style="font-weight:400;font-size:11px;color:#8a96ad">(генерира се автоматично)</span></label>
          <input type="text" name="slug" id="slug" value="{{ old('slug') }}" placeholder="structures-metalliques" />
          @error('slug')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Икона <span style="font-weight:400;font-size:11px;color:#8a96ad">(незадължително)</span></label>
          <input type="text" name="icon" value="{{ old('icon') }}" placeholder="напр. wrench" />
        </div>
        <div class="a-field">
          <label>Ред (сортиране)</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="max-width:100px" />
        </div>
      </div>

      <div class="a-field" style="display:flex; gap:24px; flex-wrap:wrap; align-items:flex-start">
        <label class="a-toggle">
          <input type="checkbox" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
          <span>Публикувана</span>
        </label>
        <label class="a-toggle">
          <input type="checkbox" name="featured" id="featured-check" value="1" {{ old('featured') ? 'checked' : '' }}>
          <span>Акцентирана на началната страница</span>
        </label>
        <div id="featured-order-wrap" style="{{ old('featured') ? '' : 'display:none' }}">
          <label style="font-size:13px; font-weight:500; display:block; margin-bottom:4px">
            Ред на началната страница <span style="font-weight:400;font-size:11px;color:#8a96ad">(1–5)</span>
          </label>
          <input type="number" name="featured_order" value="{{ old('featured_order') }}" min="1" max="99" style="max-width:80px" />
        </div>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" name="action" value="save" class="a-btn a-btn-primary">Запази и затвори</button>
      <button type="submit" name="action" value="continue" class="a-btn a-btn-ghost">Запази и продължи</button>
      <a href="{{ route('product-categories.index') }}" class="a-btn a-btn-ghost" style="margin-left:auto">Отказ</a>
    </div>
  </form>
@endsection

@push('scripts')
<script>
(function () {
  // Slug auto-generation from FR name
  var nameFr   = document.getElementById('name-fr');
  var slugField = document.getElementById('slug');
  var slugEdited = false;

  slugField.addEventListener('input', function () { slugEdited = true; });

  nameFr.addEventListener('input', function () {
    if (slugEdited) return;
    slugField.value = nameFr.value
      .toLowerCase()
      .normalize('NFD').replace(/[̀-ͯ]/g, '')
      .replace(/[^a-z0-9\s-]/g, '')
      .trim()
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-');
  });

  // Image preview
  document.getElementById('image-input').addEventListener('change', function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('image-preview-img').src = e.target.result;
      document.getElementById('image-preview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  });

  // Featured order toggle
  var featuredCheck = document.getElementById('featured-check');
  var featuredWrap  = document.getElementById('featured-order-wrap');
  featuredCheck.addEventListener('change', function () {
    featuredWrap.style.display = this.checked ? '' : 'none';
  });
})();
</script>
@endpush
