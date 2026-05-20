@extends('layouts.admin')

@section('page-title', 'Нова страница')

@section('content')
  <div class="a-page-header">
    <h1>Нова страница</h1>
    <a href="{{ route('pages.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Назад</a>
  </div>

  <form action="{{ route('pages.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

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
            <input type="text" name="title[fr]" value="{{ old('title.fr') }}" placeholder="Titre (Français)" />
            @error('title.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="title[en]" value="{{ old('title.en') }}" placeholder="Title (English)" />
          </div>
        </div>

        <div class="a-field">
          <label>Текст</label>
          <div class="a-lang-field active" data-lang="fr">
            <div class="a-quill-wrap"><div id="quill-content-fr"></div></div>
            <textarea name="content[fr]" id="content-fr-input" style="display:none">{{ old('content.fr') }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <div class="a-quill-wrap"><div id="quill-content-en"></div></div>
            <textarea name="content[en]" id="content-en-input" style="display:none">{{ old('content.en') }}</textarea>
          </div>
        </div>
      </div>

      <div class="a-form-card">
        <p class="a-section-title">SEO / Мета</p>

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

    <div class="a-form-card">
      <p class="a-section-title">Настройки</p>

      <div class="a-field-row">
        <div class="a-field">
          <label>Slug <span style="color:#c62828">*</span></label>
          <input type="text" name="slug" value="{{ old('slug') }}" placeholder="напр. home" />
          @error('slug')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Приоритет</label>
          <input type="number" name="priority" value="{{ old('priority', 0) }}" min="0" />
        </div>
      </div>

      <div class="a-field">
        <label>Основно изображение</label>
        <input type="file" name="hero_image" accept="image/*" />
        @error('hero_image')<div class="a-field-error">{{ $message }}</div>@enderror
      </div>

      <div class="a-field">
        <label class="a-toggle">
          <input type="checkbox" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
          <span>Публикувана</span>
        </label>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" name="action" value="save" class="a-btn a-btn-primary">Запази и затвори</button>
      <button type="submit" name="action" value="continue" class="a-btn a-btn-ghost">Запази и продължи</button>
      <a href="{{ route('pages.index') }}" class="a-btn a-btn-ghost" style="margin-left:auto">Отказ</a>
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

  document.querySelector('form').addEventListener('submit', function () {
    document.getElementById('content-fr-input').value = quillFr.getSemanticHTML();
    document.getElementById('content-en-input').value = quillEn.getSemanticHTML();
  });
})();
</script>
@endpush
