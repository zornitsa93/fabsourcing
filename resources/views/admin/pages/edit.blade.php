@extends('layouts.admin')

@section('page-title', 'Редактирай страница')

@section('content')
  <div class="a-page-header">
    <h1>Редактирай: <span style="color:#2b62d9">{{ $page->getTranslation('title','fr',false) ?: $page->slug }}</span></h1>
    <a href="{{ route('pages.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Назад</a>
  </div>

  <form action="{{ route('pages.update', $page) }}" method="POST" enctype="multipart/form-data">
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
                   value="{{ old('title.fr', $page->getTranslation('title','fr',false)) }}"
                   placeholder="Заглавие (Френски)" />
            @error('title.fr')<div class="a-field-error">{{ $message }}</div>@enderror
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="title[en]"
                   value="{{ old('title.en', $page->getTranslation('title','en',false)) }}"
                   placeholder="Заглавие (Английски)" />
          </div>
        </div>
      </div>

      @if($page->slug === 'home')
      <div class="a-form-card">
        <p class="a-section-title">Херо — заглавие и подзаглавие</p>
        <p style="font-size:13px;color:#8a96ad;margin-top:-8px;margin-bottom:20px">
          Съдържание на главната секция на началната страница.
        </p>

        <div class="a-field">
          <label>Главно заглавие (H1)</label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="hero_heading[fr]" rows="4"
              placeholder="Fabrication&#10;métallique&#10;européenne,&#10;coûts réduits">{{ old('hero_heading.fr', $page->getTranslation('hero_heading','fr',false)) }}</textarea>
            <p style="font-size:12px;color:#8a96ad;margin-top:4px">Всеки ред = един ред от заглавието. Нов ред = видимо разделяне.</p>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="hero_heading[en]" rows="4"
              placeholder="European&#10;metalwork,&#10;reduced&#10;costs">{{ old('hero_heading.en', $page->getTranslation('hero_heading','en',false)) }}</textarea>
            <p style="font-size:12px;color:#8a96ad;margin-top:4px">Всеки ред = един ред от заглавието. Нов ред = видимо разделяне.</p>
          </div>
        </div>

        <div class="a-field" style="margin-top:20px">
          <label>Уводен текст</label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="hero_lede[fr]" rows="3"
              placeholder="Fab Sourcing vous connecte à des ateliers certifiés en Bulgarie…">{{ old('hero_lede.fr', $page->getTranslation('hero_lede','fr',false)) }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="hero_lede[en]" rows="3"
              placeholder="Fab Sourcing connects you with certified workshops in Bulgaria…">{{ old('hero_lede.en', $page->getTranslation('hero_lede','en',false)) }}</textarea>
          </div>
        </div>

        <div class="a-field" style="margin-top:20px">
          <label>Херо изображение (дясна страна)</label>
          @if($page->hero_image)
            <div class="a-img-preview">
              <img src="{{ Storage::url($page->hero_image) }}" alt="Херо изображение" />
            </div>
            <label class="a-toggle" style="margin-top:12px">
              <input type="checkbox" name="remove_hero_image" value="1">
              <span>Премахни изображението</span>
            </label>
            <p style="margin-top:8px; font-size:13px; color:#8a96ad">Качи ново изображение за замяна.</p>
          @endif
          <input type="file" name="hero_image" accept="image/*" style="margin-top:8px" />
          @error('hero_image')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>
      @endif

      @if($page->slug === 'services')
      <div class="a-form-card">
        <p class="a-section-title">Херо — подзаглавие и изображение</p>
        <p style="font-size:13px;color:#8a96ad;margin-top:-8px;margin-bottom:20px">
          Текстът под H1 и снимката вдясно на страница Services.
        </p>

        <div class="a-field">
          <label>Подзаглавие (lede)</label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="hero_lede[fr]" rows="3"
              placeholder="De la pièce unitaire à la grande série, Fab Sourcing coordonne…">{{ old('hero_lede.fr', $page->getTranslation('hero_lede','fr',false)) }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="hero_lede[en]" rows="3"
              placeholder="From unit production to large series, Fab Sourcing coordinates…">{{ old('hero_lede.en', $page->getTranslation('hero_lede','en',false)) }}</textarea>
          </div>
        </div>

        <div class="a-field" style="margin-top:20px">
          <label>Херо изображение (16:10, мин. 1600px широко)</label>
          @if($page->hero_image)
            <div class="a-img-preview">
              <img src="{{ Storage::url($page->hero_image) }}" alt="Services херо изображение" />
            </div>
            <label class="a-toggle" style="margin-top:12px">
              <input type="checkbox" name="remove_hero_image" value="1">
              <span>Премахни изображението</span>
            </label>
            <p style="margin-top:8px; font-size:13px; color:#8a96ad">Качи ново изображение за замяна.</p>
          @endif
          <input type="file" name="hero_image" accept="image/*" style="margin-top:8px" />
          @error('hero_image')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>
      @endif

      @if($page->slug === 'home')
      <div class="a-form-card">
        <p class="a-section-title">Секция Услуги — описание</p>

        <div class="a-field">
          <label>Уводен текст под заглавието "Нашите услуги"</label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="services_lede[fr]" rows="3"
              placeholder="De la pièce unitaire à la série…">{{ old('services_lede.fr', $page->getTranslation('services_lede','fr',false)) }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="services_lede[en]" rows="3"
              placeholder="From one-off parts to series production…">{{ old('services_lede.en', $page->getTranslation('services_lede','en',false)) }}</textarea>
          </div>
        </div>
      </div>
      @endif

      @if($page->slug === 'home')
      <div class="a-form-card">
        <p class="a-section-title">Секция "Защо Източна Европа"</p>

        <div class="a-field">
          <label>Малък надпис (eyebrow)</label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="why_eyebrow[fr]"
                   value="{{ old('why_eyebrow.fr', $page->getTranslation('why_eyebrow','fr',false)) }}"
                   placeholder="Pourquoi l'Europe de l'Est" />
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="why_eyebrow[en]"
                   value="{{ old('why_eyebrow.en', $page->getTranslation('why_eyebrow','en',false)) }}"
                   placeholder="Why Eastern Europe" />
          </div>
        </div>

        <div class="a-field" style="margin-top:20px">
          <label>Заглавие (H2)</label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="why_heading[fr]"
                   value="{{ old('why_heading.fr', $page->getTranslation('why_heading','fr',false)) }}"
                   placeholder="Qualité européenne, coûts maîtrisés" />
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="why_heading[en]"
                   value="{{ old('why_heading.en', $page->getTranslation('why_heading','en',false)) }}"
                   placeholder="European quality, controlled costs" />
          </div>
        </div>

        @for($n = 1; $n <= 4; $n++)
        <p class="a-section-title" style="margin-top:24px; font-size:12px">Точка {{ $n }}</p>
        <div class="a-field">
          <label>Заглавие</label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="why_item{{ $n }}_title[fr]"
                   value="{{ old('why_item'.$n.'_title.fr', $page->getTranslation('why_item'.$n.'_title','fr',false)) }}" />
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="why_item{{ $n }}_title[en]"
                   value="{{ old('why_item'.$n.'_title.en', $page->getTranslation('why_item'.$n.'_title','en',false)) }}" />
          </div>
        </div>
        <div class="a-field" style="margin-top:12px">
          <label>Описание</label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="why_item{{ $n }}_desc[fr]" rows="2">{{ old('why_item'.$n.'_desc.fr', $page->getTranslation('why_item'.$n.'_desc','fr',false)) }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="why_item{{ $n }}_desc[en]" rows="2">{{ old('why_item'.$n.'_desc.en', $page->getTranslation('why_item'.$n.'_desc','en',false)) }}</textarea>
          </div>
        </div>
        @endfor

        <p class="a-section-title" style="margin-top:24px; font-size:12px">Изображение и метрики</p>

        <div class="a-field">
          <label>Изображение на секцията (4:3)</label>
          @if($page->why_image)
            <div class="a-img-preview">
              <img src="{{ Storage::url($page->why_image) }}" alt="Why изображение" />
            </div>
            <label class="a-toggle" style="margin-top:12px">
              <input type="checkbox" name="remove_why_image" value="1">
              <span>Премахни изображението</span>
            </label>
            <p style="margin-top:8px; font-size:13px; color:#8a96ad">Качи ново изображение за замяна.</p>
          @endif
          <input type="file" name="why_image" accept="image/*" style="margin-top:8px" />
          @error('why_image')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>

        <div class="a-field" style="margin-top:20px">
          <label>Надпис под изображението</label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="why_caption[fr]"
                   value="{{ old('why_caption.fr', $page->getTranslation('why_caption','fr',false)) }}"
                   placeholder="Atelier partenaire certifié EN 1090" />
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="why_caption[en]"
                   value="{{ old('why_caption.en', $page->getTranslation('why_caption','en',false)) }}"
                   placeholder="EN 1090 certified partner workshop" />
          </div>
        </div>

        <div class="a-field-row" style="margin-top:20px">
          <div class="a-field">
            <label>Метрика 1 — стойност</label>
            <input type="text" name="why_metric1_value"
                   value="{{ old('why_metric1_value', $page->why_metric1_value) }}"
                   placeholder="15" maxlength="20" />
          </div>
          <div class="a-field">
            <label>Метрика 1 — етикет</label>
            <div class="a-lang-field active" data-lang="fr">
              <input type="text" name="why_metric1_label[fr]"
                     value="{{ old('why_metric1_label.fr', $page->getTranslation('why_metric1_label','fr',false)) }}"
                     placeholder="ans terrain" />
            </div>
            <div class="a-lang-field" data-lang="en">
              <input type="text" name="why_metric1_label[en]"
                     value="{{ old('why_metric1_label.en', $page->getTranslation('why_metric1_label','en',false)) }}"
                     placeholder="yrs exp." />
            </div>
          </div>
        </div>

        <div class="a-field-row" style="margin-top:20px">
          <div class="a-field">
            <label>Метрика 2 — стойност</label>
            <input type="text" name="why_metric2_value"
                   value="{{ old('why_metric2_value', $page->why_metric2_value) }}"
                   placeholder="−50%" maxlength="20" />
          </div>
          <div class="a-field">
            <label>Метрика 2 — етикет</label>
            <div class="a-lang-field active" data-lang="fr">
              <input type="text" name="why_metric2_label[fr]"
                     value="{{ old('why_metric2_label.fr', $page->getTranslation('why_metric2_label','fr',false)) }}"
                     placeholder="sur vos coûts" />
            </div>
            <div class="a-lang-field" data-lang="en">
              <input type="text" name="why_metric2_label[en]"
                     value="{{ old('why_metric2_label.en', $page->getTranslation('why_metric2_label','en',false)) }}"
                     placeholder="on costs" />
            </div>
          </div>
        </div>

      </div>
      @endif

      <div class="a-form-card">
        <p class="a-section-title">SEO / Мета</p>

        <div class="a-field">
          <label>Мета заглавие</label>
          <div class="a-lang-field active" data-lang="fr">
            <input type="text" name="meta_title[fr]"
                   value="{{ old('meta_title.fr', $page->getTranslation('meta_title','fr',false)) }}"
                   placeholder="Мета заглавие (Френски)" />
          </div>
          <div class="a-lang-field" data-lang="en">
            <input type="text" name="meta_title[en]"
                   value="{{ old('meta_title.en', $page->getTranslation('meta_title','en',false)) }}"
                   placeholder="Мета заглавие (Английски)" />
          </div>
        </div>

        <div class="a-field">
          <label>Мета описание</label>
          <div class="a-lang-field active" data-lang="fr">
            <textarea name="meta_description[fr]" rows="3"
                      placeholder="Мета описание (Френски)">{{ old('meta_description.fr', $page->getTranslation('meta_description','fr',false)) }}</textarea>
          </div>
          <div class="a-lang-field" data-lang="en">
            <textarea name="meta_description[en]" rows="3"
                      placeholder="Мета описание (Английски)">{{ old('meta_description.en', $page->getTranslation('meta_description','en',false)) }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="a-form-card">
      <p class="a-section-title">Настройки</p>

      <div class="a-field-row">
        <div class="a-field">
          <label>Slug <span style="color:#c62828">*</span></label>
          <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" placeholder="напр. home" />
          @error('slug')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Приоритет</label>
          <input type="number" name="priority" value="{{ old('priority', $page->priority) }}" min="0" />
        </div>
      </div>

      @if($page->slug !== 'home' && $page->slug !== 'services')
      <div class="a-field">
        <label>Основно изображение</label>
        @if($page->hero_image)
          <div class="a-img-preview">
            <img src="{{ Storage::url($page->hero_image) }}" alt="Изображение" />
          </div>
          <label class="a-toggle" style="margin-top:12px">
            <input type="checkbox" name="remove_hero_image" value="1">
            <span>Премахни изображението</span>
          </label>
          <p style="margin-top:8px; font-size:13px; color:#8a96ad">Качи ново изображение за замяна.</p>
        @endif
        <input type="file" name="hero_image" accept="image/*" style="margin-top:8px" />
        @error('hero_image')<div class="a-field-error">{{ $message }}</div>@enderror
      </div>
      @endif

      <div class="a-field">
        <label class="a-toggle">
          <input type="checkbox" name="published" value="1" {{ $page->published ? 'checked' : '' }}>
          <span>Публикувана</span>
        </label>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" name="action" value="save" class="a-btn a-btn-primary">Запази и затвори</button>
      <button type="submit" name="action" value="continue" class="a-btn a-btn-ghost">Запази и продължи</button>
    </div>
  </form>

  {{-- Delete is a separate form to prevent accidental submission alongside the edit form --}}
  <form action="{{ route('pages.destroy', $page) }}" method="POST"
        style="display:flex; justify-content:flex-end; margin-top:16px; padding-top:16px; border-top:1px solid rgba(15,30,61,0.08)"
        onsubmit="return confirm('Изтрий тази страница завинаги? Това действие е необратимо.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="a-btn a-btn-danger">Изтрий страницата</button>
  </form>
@endsection
