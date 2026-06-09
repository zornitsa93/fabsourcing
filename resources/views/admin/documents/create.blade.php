@extends('layouts.admin')

@section('page-title', 'Nouveau document')

@section('content')
  <div class="a-page-header">
    <h1>Nouveau document</h1>
    <a href="{{ route('documents.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Retour</a>
  </div>

  <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="a-form-card">
      <p class="a-section-title">Contenu</p>

      <div class="a-field-row">
        <div class="a-field">
          <label>Titre (FR) <span style="color:#c62828">*</span></label>
          <input type="text" name="title_fr" value="{{ old('title_fr') }}" placeholder="Titre en français" />
          @error('title_fr')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Titre (EN) <span style="color:#c62828">*</span></label>
          <input type="text" name="title_en" value="{{ old('title_en') }}" placeholder="Title in English" />
          @error('title_en')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="a-field-row">
        <div class="a-field">
          <label>Description (FR) <span style="font-weight:400;font-size:11px;color:#8a96ad">(facultatif)</span></label>
          <textarea name="description_fr" rows="3" placeholder="Description en français">{{ old('description_fr') }}</textarea>
          @error('description_fr')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Description (EN) <span style="font-weight:400;font-size:11px;color:#8a96ad">(optional)</span></label>
          <textarea name="description_en" rows="3" placeholder="Description in English">{{ old('description_en') }}</textarea>
          @error('description_en')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="a-form-card">
      <p class="a-section-title">Fichier PDF</p>
      <div class="a-field">
        <label>Fichier PDF <span style="color:#c62828">*</span> <span style="font-weight:400;font-size:11px;color:#8a96ad">(PDF uniquement · max 20 MB)</span></label>
        <input type="file" name="file" accept="application/pdf" style="margin-top:4px" />
        @error('file')<div class="a-field-error">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="a-form-card">
      <p class="a-section-title">Paramètres</p>
      <div class="a-field-row">
        <div class="a-field">
          <label>Ordre (tri)</label>
          <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0" style="max-width:100px" />
          @error('sort_order')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="a-field">
        <label class="a-toggle">
          <input type="checkbox" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
          <span>Publié</span>
        </label>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" class="a-btn a-btn-primary">Enregistrer</button>
      <a href="{{ route('documents.index') }}" class="a-btn a-btn-ghost" style="margin-left:auto">Annuler</a>
    </div>
  </form>
@endsection
