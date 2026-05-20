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
    .body { padding:32px; }
    .row { padding:12px 0; border-bottom:1px solid rgba(15,30,61,0.08); display:flex; gap:16px; }
    .row:last-child { border-bottom:none; }
    .label { font-size:12px; text-transform:uppercase; letter-spacing:0.1em; color:#6b7891; min-width:100px; padding-top:2px; }
    .value { font-size:15px; color:#0f1e3d; }
    .message-box { background:#f4f5f7; border-radius:6px; padding:16px 20px; margin-top:24px; font-size:15px; line-height:1.7; white-space:pre-wrap; }
    .footer { text-align:center; padding:24px; color:#6b7891; font-size:12px; }
    a { color:#2b62d9; }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <h1>Nouvelle demande de contact</h1>
      <p>Fab Sourcing — {{ now()->format('d/m/Y H:i') }}</p>
    </div>
    <div class="body">
      <div class="row">
        <span class="label">Nom</span>
        <span class="value">{{ $submission->name }}</span>
      </div>
      @if($submission->company)
      <div class="row">
        <span class="label">Entreprise</span>
        <span class="value">{{ $submission->company }}</span>
      </div>
      @endif
      <div class="row">
        <span class="label">Email</span>
        <span class="value"><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></span>
      </div>
      @if($submission->phone)
      <div class="row">
        <span class="label">Téléphone</span>
        <span class="value"><a href="tel:{{ $submission->phone }}">{{ $submission->phone }}</a></span>
      </div>
      @endif
      <div class="message-box">{{ $submission->message }}</div>
      <p style="margin-top:24px; font-size:14px; color:#6b7891">
        <a href="{{ url('/admin/contact-submissions/' . $submission->id) }}">
          → Voir dans l'admin Fab Sourcing
        </a>
      </p>
    </div>
    <div class="footer">
      Fab Sourcing Admin · fab-sourcing.fr
    </div>
  </div>
</body>
</html>
