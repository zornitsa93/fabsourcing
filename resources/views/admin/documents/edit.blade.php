@extends('layouts.admin')

@section('page-title', 'Modifier le document')

@section('content')
  <div class="a-page-header">
    <h1>Modifier : <span style="color:#2b62d9">{{ $document->getTranslation('title','fr',false) ?: $document->original_filename }}</span></h1>
    <a href="{{ route('documents.index') }}" class="a-btn a-btn-ghost a-btn-sm">← Retour</a>
  </div>

  @if(session('status'))
    <div class="a-alert a-alert-success" style="margin-bottom:16px">Document mis à jour.</div>
  @endif

  <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="a-form-card">
      <p class="a-section-title">Contenu</p>

      <div class="a-field-row">
        <div class="a-field">
          <label>Titre (FR) <span style="color:#c62828">*</span></label>
          <input type="text" name="title_fr"
                 value="{{ old('title_fr', $document->getTranslation('title','fr',false)) }}"
                 placeholder="Titre en français" />
          @error('title_fr')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Titre (EN) <span style="color:#c62828">*</span></label>
          <input type="text" name="title_en"
                 value="{{ old('title_en', $document->getTranslation('title','en',false)) }}"
                 placeholder="Title in English" />
          @error('title_en')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="a-field-row">
        <div class="a-field">
          <label>Description (FR) <span style="font-weight:400;font-size:11px;color:#8a96ad">(facultatif)</span></label>
          <textarea name="description_fr" rows="3" placeholder="Description en français">{{ old('description_fr', $document->getTranslation('description','fr',false)) }}</textarea>
          @error('description_fr')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
        <div class="a-field">
          <label>Description (EN) <span style="font-weight:400;font-size:11px;color:#8a96ad">(optional)</span></label>
          <textarea name="description_en" rows="3" placeholder="Description in English">{{ old('description_en', $document->getTranslation('description','en',false)) }}</textarea>
          @error('description_en')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>
    </div>

    <div class="a-form-card">
      <p class="a-section-title">Fichier PDF</p>
      <div class="a-field">
        <label>Fichier actuel</label>
        <div style="font-size:13px; color:#6b7891; margin-top:4px">
          {{ $document->original_filename }}
          @if($document->file_size)
            <span style="color:#8a96ad; margin-left:8px">({{ number_format($document->file_size / 1024, 1) }} KB)</span>
          @endif
        </div>
      </div>
      <div class="a-field">
        <label>Remplacer le fichier <span style="font-weight:400;font-size:11px;color:#8a96ad">(PDF uniquement · max 20 MB · Laisser vide pour conserver le fichier actuel / Leave empty to keep the current file)</span></label>
        <input type="file" name="file" accept="application/pdf" style="margin-top:4px" />
        @error('file')<div class="a-field-error">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="a-form-card">
      <p class="a-section-title">Paramètres</p>
      <div class="a-field-row">
        <div class="a-field">
          <label>Ordre (tri)</label>
          <input type="number" name="sort_order"
                 value="{{ old('sort_order', $document->sort_order) }}" min="0" style="max-width:100px" />
          @error('sort_order')<div class="a-field-error">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="a-field">
        <label class="a-toggle">
          <input type="checkbox" name="published" value="1" {{ old('published', $document->published) ? 'checked' : '' }}>
          <span>Publié</span>
        </label>
      </div>
    </div>

    <div class="a-form-footer">
      <button type="submit" class="a-btn a-btn-primary">Enregistrer</button>
    </div>
  </form>

  <div class="a-form-card" style="margin-top:24px; border-color:#fde8e8">
    <p class="a-section-title" style="color:#c62828">Zone de danger</p>
    <p style="font-size:13px; color:#6b7891; margin-bottom:16px">
      La suppression est irréversible et supprime le fichier du serveur.
    </p>
    <form action="{{ route('documents.destroy', $document) }}" method="POST"
          onsubmit="return confirm('Supprimer « {{ addslashes($document->getTranslation('title','fr',false)) }} » ?');">
      @csrf
      @method('DELETE')
      <button type="submit" class="a-btn a-btn-danger">Supprimer le document</button>
    </form>
  </div>
@endsection
