@php($fr = ($user->locale ?? 'fr') === 'fr')
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue", Arial, sans-serif; background:#f4f5f7; margin:0; padding:40px 20px; color:#0f1e3d; }
    .card { background:#fff; max-width:600px; margin:0 auto; border-radius:8px; overflow:hidden; }
    .header { background:#0f1e3d; padding:28px 32px; }
    .header h1 { color:#fff; font-size:20px; margin:0; font-weight:600; letter-spacing:-0.02em; }
    .header p { color:rgba(255,255,255,0.6); margin:6px 0 0; font-size:13px; }
    .body { padding:32px; font-size:15px; line-height:1.7; }
    .row { padding:10px 0; border-bottom:1px solid rgba(15,30,61,0.08); display:flex; gap:16px; }
    .row:last-child { border-bottom:none; }
    .label { font-size:12px; text-transform:uppercase; letter-spacing:0.1em; color:#6b7891; min-width:100px; padding-top:2px; }
    .value { font-size:15px; color:#0f1e3d; }
    .cta { display:inline-block; margin-top:20px; background:#0f1e3d; color:#fff; padding:12px 24px; border-radius:6px; text-decoration:none; font-size:15px; }
    .footer { text-align:center; padding:24px; color:#6b7891; font-size:12px; }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <h1>{{ $fr ? 'Nouveau compte en attente' : 'New account awaiting approval' }}</h1>
      <p>Fab Sourcing Admin · {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <div class="body">
      <p>{{ $fr ? 'Un nouveau compte est en attente de validation :' : 'A new account is awaiting approval:' }}</p>
      <div class="row">
        <span class="label">{{ $fr ? 'Nom' : 'Name' }}</span>
        <span class="value">{{ $user->name }}</span>
      </div>
      <div class="row">
        <span class="label">Email</span>
        <span class="value"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span>
      </div>
      @if($user->company)
      <div class="row">
        <span class="label">{{ $fr ? 'Entreprise' : 'Company' }}</span>
        <span class="value">{{ $user->company }}</span>
      </div>
      @endif
      <a href="{{ url('admin/accounts') }}" class="cta">
        {{ $fr ? 'Valider le compte' : 'Review account' }}
      </a>
    </div>
    <div class="footer">
      Fab Sourcing Admin · fab-sourcing.fr
    </div>
  </div>
</body>
</html>
