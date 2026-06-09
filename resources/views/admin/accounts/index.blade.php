@extends('layouts.admin')

@section('title', 'Comptes utilisateurs')

@section('content')
<div class="a-page-header">
  <h1>Comptes utilisateurs</h1>
</div>

@if(session('status'))
  <div class="a-alert a-alert-success">
    @if(session('status') === 'approved') Compte approuvé.
    @elseif(session('status') === 'revoked') Approbation révoquée.
    @elseif(session('status') === 'deleted') Compte supprimé.
    @endif
  </div>
@endif

<div class="a-card" style="margin-bottom:32px">
  <h2 style="font-size:16px; font-weight:600; margin-bottom:16px; color:#1e293b">
    En attente
    @if($pending->isNotEmpty())
      <span style="display:inline-flex;align-items:center;justify-content:center;background:#2b62d9;color:#fff;border-radius:999px;font-size:12px;font-weight:600;padding:2px 10px;margin-left:10px;vertical-align:middle">{{ $pending->count() }}</span>
    @endif
  </h2>
  @if($pending->isEmpty())
    <p style="color:#6b7891; padding:8px 0">Aucun compte en attente.</p>
  @else
    <table class="a-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Société</th>
          <th>Téléphone</th>
          <th>Inscrit le</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($pending as $u)
          <tr>
            <td>{{ $u->name }}</td>
            <td><a href="mailto:{{ $u->email }}" style="color:#2b62d9">{{ $u->email }}</a></td>
            <td>{{ $u->company ?: '—' }}</td>
            <td>{{ $u->phone ?: '—' }}</td>
            <td style="white-space:nowrap; color:#6b7891; font-size:13px">{{ $u->created_at->format('d.m.Y H:i') }}</td>
            <td style="white-space:nowrap">
              <form method="POST" action="{{ route('accounts.approve', $u) }}" style="display:inline">
                @csrf
                <button type="submit" class="a-btn a-btn-sm">Approuver</button>
              </form>
              <form method="POST" action="{{ route('accounts.destroy', $u) }}" style="display:inline; margin-left:8px" onsubmit="return confirm('Supprimer ce compte ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="a-btn a-btn-sm" style="background:#ef4444; border-color:#ef4444; color:#fff">Supprimer</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>

<div class="a-card">
  <h2 style="font-size:16px; font-weight:600; margin-bottom:16px; color:#1e293b">Approuvés</h2>
  @if($approved->isEmpty())
    <p style="color:#6b7891; padding:8px 0">Aucun compte approuvé.</p>
  @else
    <table class="a-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Société</th>
          <th>Approuvé le</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($approved as $u)
          <tr>
            <td>{{ $u->name }}</td>
            <td><a href="mailto:{{ $u->email }}" style="color:#2b62d9">{{ $u->email }}</a></td>
            <td>{{ $u->company ?: '—' }}</td>
            <td style="white-space:nowrap; color:#6b7891; font-size:13px">{{ $u->approved_at->format('d.m.Y H:i') }}</td>
            <td>
              <form method="POST" action="{{ route('accounts.revoke', $u) }}" style="display:inline" onsubmit="return confirm('Révoquer l\'approbation ?')">
                @csrf
                <button type="submit" class="a-btn a-btn-sm" style="background:#f59e0b; border-color:#f59e0b; color:#fff">Révoquer</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
