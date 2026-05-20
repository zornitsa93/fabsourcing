@extends('layouts.admin')

@section('page-title', 'Нова статия')

@section('content')
  <div class="a-page-header">
    <h1>Нова блог статия</h1>
    <a href="{{ route('blog-posts.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Назад</a>
  </div>

  <form action="{{ route('blog-posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Settings bar --}}
    <div class="a-form-card">
      <p class="a-section-title">Основна информация</p>
      <div class="a-field-row">
        <div class="a-field">
          <label>Slug <span style="font-size:11px;font-weight:400;color:#8a96ad">(автоматично от FR заглавие)</span></label>
          <input type="text" name="slug" id="slug-field" value="{{ old('slug') }}" placeholder="напр. pourquoi-externaliser" />
          @error('slug')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Автор</label>
          <input type="text" name="author_name" value="{{ old('author_name', 'Thierry Sudol') }}" />
        </div>
      </div>

      <div class="a-field">
        <label>Публикуване</label>
        <div class="a-publish-row">
          <label class="a-publish-opt">
            <input type="radio" name="publish_mode" value="draft" {{ old('publish_mode', 'draft') === 'draft' ? 'checked' : '' }}>
            <span>Чернова</span>
          </label>
          <label class="a-publish-opt">
            <input type="radio" name="publish_mode" value="now" {{ old('publish_mode') === 'now' ? 'checked' : '' }}>
            <span>Публикувай сега</span>
          </label>
          <label class="a-publish-opt">
            <input type="radio" name="publish_mode" value="schedule" {{ old('publish_mode') === 'schedule' ? 'checked' : '' }}>
            <span>Насрочи за по-късно</span>
          </label>
        </div>
        <div id="schedule-date-field" style="display:none; margin-top:10px">
          <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" style="max-width:280px" />
        </div>
      </div>
    </div>

    {{-- Content tabs --}}
    <div class="a-prod-tabs">
      <div class="a-prod-tab-bar">
        <button type="button" class="a-prod-tab active" data-tab="btab-fr">FR Съдържание</button>
        <button type="button" class="a-prod-tab" data-tab="btab-en">EN Content</button>
        <button type="button" class="a-prod-tab" data-tab="btab-image">Изображение</button>
        <button type="button" class="a-prod-tab" data-tab="btab-seo">SEO</button>
      </div>

      {{-- FR --}}
      <div id="btab-fr" class="a-prod-tab-panel active">
        <div class="a-form-card">
          <p class="a-section-title">Съдържание (FR)</p>

          <div class="a-field">
            <label>Заглавие (FR) <span style="color:#c62828">*</span></label>
            <input type="text" name="title[fr]" id="title-fr" value="{{ old('title.fr') }}"
                   placeholder="Titre de l'article" />
            @error('title.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>

          <div class="a-field">
            <label>Резюме (FR)</label>
            <textarea name="excerpt[fr]" rows="3" placeholder="Courte introduction…">{{ old('excerpt.fr') }}</textarea>
          </div>

          <div class="a-field">
            <label>Текст (FR)</label>
            <div class="a-quill-wrap"><div id="quill-body-fr"></div></div>
            <textarea name="body[fr]" id="body-fr-input" style="display:none">{{ old('body.fr') }}</textarea>
          </div>

          <div class="a-field">
            <label>Тагове (FR)</label>
            <div id="tags-fr-list" class="a-bullets-list">
              @foreach(old('tags_fr', []) as $tag)
                <div class="a-bullet-row">
                  <input type="text" name="tags_fr[]" value="{{ $tag }}" placeholder="tag…" />
                  <button type="button" class="a-bullet-remove a-btn a-btn-danger a-btn-sm">✕</button>
                </div>
              @endforeach
            </div>
            <button type="button" class="a-btn a-btn-ghost a-btn-sm" style="margin-top:8px"
                    onclick="addBullet('tags-fr-list','tags_fr[]','tag…')">+ Добави таг</button>
          </div>
        </div>
      </div>

      {{-- EN --}}
      <div id="btab-en" class="a-prod-tab-panel">
        <div class="a-form-card">
          <p class="a-section-title">Content (EN)</p>

          <div class="a-field">
            <label>Title (EN)</label>
            <input type="text" name="title[en]" value="{{ old('title.en') }}" placeholder="Article title" />
          </div>

          <div class="a-field">
            <label>Excerpt (EN)</label>
            <textarea name="excerpt[en]" rows="3" placeholder="Short introduction…">{{ old('excerpt.en') }}</textarea>
          </div>

          <div class="a-field">
            <label>Body (EN)</label>
            <div class="a-quill-wrap"><div id="quill-body-en"></div></div>
            <textarea name="body[en]" id="body-en-input" style="display:none">{{ old('body.en') }}</textarea>
          </div>

          <div class="a-field">
            <label>Tags (EN)</label>
            <div id="tags-en-list" class="a-bullets-list">
              @foreach(old('tags_en', []) as $tag)
                <div class="a-bullet-row">
                  <input type="text" name="tags_en[]" value="{{ $tag }}" placeholder="tag…" />
                  <button type="button" class="a-bullet-remove a-btn a-btn-danger a-btn-sm">✕</button>
                </div>
              @endforeach
            </div>
            <button type="button" class="a-btn a-btn-ghost a-btn-sm" style="margin-top:8px"
                    onclick="addBullet('tags-en-list','tags_en[]','tag…')">+ Add tag</button>
          </div>
        </div>
      </div>

      {{-- Image --}}
      <div id="btab-image" class="a-prod-tab-panel">
        <div class="a-form-card">
          <p class="a-section-title">Главно изображение</p>
          <input type="hidden" name="featured_image_path" id="featured-image-path" value="">

          <div id="featured-image-preview" style="display:none; margin-bottom:14px">
            <img id="featured-image-thumb" src="" alt="" style="max-height:200px; border-radius:5px; border:1px solid rgba(15,30,61,0.1)">
            <div style="margin-top:8px">
              <button type="button" class="a-btn a-btn-danger a-btn-sm" onclick="clearFeaturedImage()">Премахни</button>
            </div>
          </div>

          <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap">
            <button type="button" class="a-btn a-btn-ghost a-btn-sm" onclick="openMediaPicker(setFeaturedFromLibrary)">
              Избери от библиотека
            </button>
            <span style="color:#8a96ad; font-size:12px">— или —</span>
            <input type="file" name="featured_image_file" accept="image/*" />
          </div>
          @error('featured_image_file')<div class="a-field-error" style="margin-top:6px">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- SEO --}}
      <div id="btab-seo" class="a-prod-tab-panel">
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
                <input type="text" name="meta_title[fr]" value="{{ old('meta_title.fr') }}" placeholder="Meta title (FR)" />
              </div>
              <div class="a-lang-field" data-lang="en">
                <input type="text" name="meta_title[en]" value="{{ old('meta_title.en') }}" placeholder="Meta title (EN)" />
              </div>
            </div>
            <div class="a-field">
              <label>Мета описание</label>
              <div class="a-lang-field active" data-lang="fr">
                <textarea name="meta_description[fr]" rows="3" placeholder="Meta description (FR)">{{ old('meta_description.fr') }}</textarea>
              </div>
              <div class="a-lang-field" data-lang="en">
                <textarea name="meta_description[en]" rows="3" placeholder="Meta description (EN)">{{ old('meta_description.en') }}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" name="action" value="save" class="a-btn a-btn-primary">Запази и затвори</button>
      <button type="submit" name="action" value="continue" class="a-btn a-btn-ghost">Запази и продължи</button>
      <a href="{{ route('blog-posts.index') }}" class="a-btn a-btn-ghost" style="margin-left:auto">Отказ</a>
    </div>
  </form>
@endsection

@push('scripts')
<script>
// Product tab switcher (reused for blog)
(function () {
  document.querySelectorAll('.a-prod-tab').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var t = btn.dataset.tab;
      document.querySelectorAll('.a-prod-tab').forEach(function (b) { b.classList.remove('active'); });
      document.querySelectorAll('.a-prod-tab-panel').forEach(function (p) { p.classList.remove('active'); });
      btn.classList.add('active');
      document.getElementById(t).classList.add('active');
    });
  });
})();

// Bullet helper
function addBullet(listId, name, ph) {
  var list = document.getElementById(listId);
  var row  = document.createElement('div');
  row.className = 'a-bullet-row';
  row.innerHTML = '<input type="text" name="' + name + '" placeholder="' + ph + '" />'
    + '<button type="button" class="a-bullet-remove a-btn a-btn-danger a-btn-sm">✕</button>';
  list.appendChild(row);
  row.querySelector('input').focus();
}
document.addEventListener('click', function (e) {
  if (e.target.classList.contains('a-bullet-remove')) e.target.closest('.a-bullet-row').remove();
});

// Slug auto-generate from FR title
document.getElementById('title-fr').addEventListener('input', function () {
  var slug = document.getElementById('slug-field');
  if (!slug._touched) {
    slug.value = this.value.toLowerCase()
      .normalize('NFD').replace(/[̀-ͯ]/g, '')
      .replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
  }
});
document.getElementById('slug-field').addEventListener('input', function () { this._touched = true; });

// Publish mode toggle
(function () {
  var radios = document.querySelectorAll('input[name="publish_mode"]');
  var field  = document.getElementById('schedule-date-field');
  function update() {
    var val = document.querySelector('input[name="publish_mode"]:checked')?.value;
    field.style.display = val === 'schedule' ? 'block' : 'none';
  }
  radios.forEach(function (r) { r.addEventListener('change', update); });
  update();
})();

// Featured image from library
function setFeaturedFromLibrary(img) {
  document.getElementById('featured-image-path').value  = img.path;
  document.getElementById('featured-image-thumb').src   = img.url;
  document.getElementById('featured-image-preview').style.display = 'block';
}
function clearFeaturedImage() {
  document.getElementById('featured-image-path').value  = '';
  document.getElementById('featured-image-thumb').src   = '';
  document.getElementById('featured-image-preview').style.display = 'none';
}

// Quill
(function () {
  var toolbar = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link', 'image', 'clean']
  ];
  var qFr = new Quill('#quill-body-fr', { theme:'snow', modules:{ toolbar: toolbar }});
  var qEn = new Quill('#quill-body-en', { theme:'snow', modules:{ toolbar: toolbar }});

  // Override image button to open media picker
  function overrideImageHandler(quill) {
    quill.getModule('toolbar').addHandler('image', function () {
      openMediaPicker(function (img) {
        var range = quill.getSelection(true);
        quill.insertEmbed(range.index, 'image', img.url);
      });
    });
  }
  overrideImageHandler(qFr);
  overrideImageHandler(qEn);

  var initFr = document.getElementById('body-fr-input').value;
  var initEn = document.getElementById('body-en-input').value;
  if (initFr) qFr.clipboard.dangerouslyPasteHTML(initFr);
  if (initEn) qEn.clipboard.dangerouslyPasteHTML(initEn);

  document.querySelector('form').addEventListener('submit', function () {
    document.getElementById('body-fr-input').value = qFr.getSemanticHTML();
    document.getElementById('body-en-input').value = qEn.getSemanticHTML();
  });
})();
</script>
@endpush
