@extends('layouts.admin')

@section('page-title', 'Documents')

@section('content')
  <div class="a-page-header">
    <h1>Documents</h1>
    <a href="{{ route('documents.create') }}" class="a-btn a-btn-ghost a-btn-sm">+ Ajouter un document</a>
  </div>

  @if(session('status'))
    <div class="a-alert a-alert-success" style="margin-bottom:16px">
      @if(session('status') === 'created') Document créé avec succès.
      @elseif(session('status') === 'updated') Document mis à jour.
      @elseif(session('status') === 'deleted') Document supprimé.
      @endif
    </div>
  @endif

  <div class="a-card">
    <table class="a-table">
      <thead>
        <tr>
          <th style="width:40px; text-align:center">Ordre</th>
          <th>Titre (FR)</th>
          <th style="width:90px; text-align:center">Statut</th>
          <th style="width:100px; text-align:right">Taille</th>
          <th style="width:150px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($documents as $document)
          <tr>
            <td style="color:#8a96ad; font-size:13px; text-align:center">{{ $document->sort_order }}</td>
            <td>
              <div style="font-weight:500; color:#1a2235">{{ $document->getTranslation('title','fr',false) ?: '—' }}</div>
              @if($document->getTranslation('title','en',false))
                <div style="font-size:12px; color:#8a96ad">{{ $document->getTranslation('title','en',false) }}</div>
              @endif
              <div style="font-size:11px; color:#b0bac9; margin-top:2px">{{ $document->original_filename }}</div>
            </td>
            <td style="text-align:center">
              @if($document->published)
                <span class="a-badge a-badge-published">Publié</span>
              @else
                <span class="a-badge a-badge-draft">Brouillon</span>
              @endif
            </td>
            <td style="text-align:right; font-size:13px; color:#8a96ad">
              {{ $document->file_size ? number_format($document->file_size / 1024, 1) . ' KB' : '—' }}
            </td>
            <td>
              <div class="a-table-actions">
                <a href="{{ route('documents.edit', $document) }}" class="a-btn a-btn-ghost a-btn-sm">Modifier</a>
                <form action="{{ route('documents.destroy', $document) }}" method="POST"
                      onsubmit="return confirm('Supprimer « {{ addslashes($document->getTranslation('title','fr',false)) }} » ?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="a-btn a-btn-danger a-btn-sm">Supprimer</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center; padding:40px; color:#8a96ad; font-size:14px;">
              Aucun document trouvé.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
