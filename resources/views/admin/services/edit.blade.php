@extends('layouts.admin')

@section('page-title', 'Редактирай услуга')

@section('content')
  <div class="a-page-header">
    <h1>Редактирай: <span style="color:#2b62d9">{{ $service->getTranslation('title','fr',false) ?: '—' }}</span></h1>
    <a href="{{ route('services-admin.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Назад</a>
  </div>

  <form action="{{ route('services-admin.update', $service) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="a-lang-group">
      <div class="a-lang-bar">
        <button type="button" class="a-lang-tab active" data-lang="fr">FR</button>
        <button type="button" class="a-lang-tab" data-lang="en">EN</button>
      </div>

      <div class="a-form-card">
        <p class="a-section-title">Съдържание</p>

        <div class="a-field">
          <label>Заглавие <span style="color:#c62828">*</span> <span style="font-weight:400;text-transform:none;font-size:11px;color:#8a96ad">(FR задължително · EN незадължително)</span></label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="title[fr]"
                   value="{{ old('title.fr', $service->getTranslation('title','fr',false)) }}"
                   placeholder="Заглавие (Френски)" />
            @error('title.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="title[en]"
                   value="{{ old('title.en', $service->getTranslation('title','en',false)) }}"
                   placeholder="Заглавие (Английски)" />
          </div>
        </div>

        <div class="a-field">
          <label>Кратко описание <span style="color:#c62828">*</span> <span style="font-weight:400;text-transform:none;font-size:11px;color:#8a96ad">(max 300 знака)</span></label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="description[fr]" rows="2"
                      placeholder="Кратко описание (Френски)">{{ old('description.fr', $service->getTranslation('description','fr',false)) }}</textarea>
            @error('description.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="description[en]" rows="2"
                      placeholder="Кратко описание (Английски)">{{ old('description.en', $service->getTranslation('description','en',false)) }}</textarea>
          </div>
        </div>

        <div class="a-field">
          <label>Пълно описание <span style="font-weight:400;text-transform:none;font-size:11px;color:#8a96ad">(незадължително)</span></label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="long_description[fr]" rows="5"
                      placeholder="Подробно описание на услугата (Френски)">{{ old('long_description.fr', $service->getTranslation('long_description','fr',false)) }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="long_description[en]" rows="5"
                      placeholder="Подробно описание на услугата (Английски)">{{ old('long_description.en', $service->getTranslation('long_description','en',false)) }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="a-form-card">
      <p class="a-section-title">Снимка</p>

      @if($service->image)
        <div class="a-field">
          <label>Текуща снимка</label>
          <div style="margin-top:4px">
            <img src="{{ $service->thumb_url }}" alt=""
                 style="max-width:240px; max-height:160px; border-radius:6px; border:1px solid rgba(15,30,61,0.12); object-fit:cover">
          </div>
          <label class="a-toggle" style="margin-top:12px">
            <input type="checkbox" name="remove_image" value="1" {{ old('remove_image') ? 'checked' : '' }}>
            <span>Премахни изображението</span>
          </label>
        </div>
      @endif

      <div class="a-field">
        <label>{{ $service->image ? 'Ново изображение' : 'Изображение' }} <span style="font-weight:400;font-size:11px;color:#8a96ad">(JPG / PNG / WEBP · max 5 MB)</span></label>
        <input type="file" name="image" id="image-input" accept="image/jpeg,image/png,image/webp"
               style="margin-top:4px" />
        <div id="image-preview" style="display:none; margin-top:12px">
          <img id="image-preview-img" src="" alt="Preview"
               style="max-width:240px; max-height:160px; border-radius:6px; border:1px solid rgba(15,30,61,0.12); object-fit:cover">
        </div>
        @error('image')<div class="a-field-error">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="a-form-card">
      <p class="a-section-title">Настройки</p>

      <div class="a-field">
        <label>Slug <span style="color:#c62828">*</span></label>
        <input type="text" name="slug" value="{{ old('slug', $service->slug) }}"
               placeholder="sourcing-industriel" />
        @error('slug')<div class="a-field-error">{{ $message }}</div>@enderror
      </div>

      <div class="a-field-row">
        <div class="a-field">
          <label>Номер <span style="font-weight:400;font-size:11px;color:#8a96ad">(напр. 01)</span></label>
          <input type="text" name="number" value="{{ old('number', $service->number) }}" placeholder="01" style="max-width:100px" />
        </div>
        <div class="a-field">
          <label>Ширина в решетката <span style="font-weight:400;font-size:11px;color:#8a96ad">(1–12)</span></label>
          <input type="number" name="col_span" value="{{ old('col_span', $service->col_span) }}" min="1" max="12" style="max-width:100px" />
          @error('col_span')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Ред (сортиране)</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}" min="0" style="max-width:100px" />
        </div>
      </div>

      <div class="a-field" style="display:flex; gap:24px">
        <label class="a-toggle">
          <input type="checkbox" name="featured" value="1" {{ old('featured', $service->featured) ? 'checked' : '' }}>
          <span>Акцентирана</span>
        </label>
        <label class="a-toggle">
          <input type="checkbox" name="published" value="1" {{ old('published', $service->published) ? 'checked' : '' }}>
          <span>Публикувана</span>
        </label>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" class="a-btn a-btn-primary">Запази промените</button>
      <a href="{{ route('services-admin.index') }}" class="a-btn a-btn-ghost">Отказ</a>
    </div>
  </form>

  <div class="a-form-card" style="margin-top:24px; border-color:#fde8e8">
    <p class="a-section-title" style="color:#c62828">Опасна зона</p>
    <p style="font-size:13px; color:#6b7891; margin-bottom:16px">Изтриването е необратимо. Снимката също ще бъде изтрита.</p>
    <form action="{{ route('services-admin.destroy', $service) }}" method="POST"
          onsubmit="return confirm('Изтрий услугата „{{ addslashes($service->getTranslation('title', 'fr', false)) }}"?');">
      @csrf
      @method('DELETE')
      <button type="submit" class="a-btn a-btn-danger">Изтрий услугата</button>
    </form>
  </div>
@endsection

@push('scripts')
<script>
(function () {
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
})();
</script>
@endpush
