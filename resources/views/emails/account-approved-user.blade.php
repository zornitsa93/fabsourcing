@php($fr = ($user->locale ?? 'fr') === 'fr')
<!DOCTYPE html>
<html lang="{{ $fr ? 'fr' : 'en' }}">
<head>
  <meta charset="utf-8">
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue", Arial, sans-serif; background:#f4f5f7; margin:0; padding:40px 20px; color:#0f1e3d; }
    .card { background:#fff; max-width:600px; margin:0 auto; border-radius:8px; overflow:hidden; }
    .header { background:#0f1e3d; padding:28px 32px; }
    .header h1 { color:#fff; font-size:20px; margin:0; font-weight:600; letter-spacing:-0.02em; }
    .header p { color:rgba(255,255,255,0.6); margin:6px 0 0; font-size:13px; }
    .body { padding:32px; font-size:15px; line-height:1.7; }
    .cta { display:inline-block; margin-top:16px; background:#0f1e3d; color:#fff; padding:12px 24px; border-radius:6px; text-decoration:none; font-size:15px; }
    .footer { text-align:center; padding:24px; color:#6b7891; font-size:12px; }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <h1>{{ $fr ? 'Fab Sourcing' : 'Fab Sourcing' }}</h1>
      <p>{{ $fr ? 'Activation de compte' : 'Account activation' }}</p>
    </div>
    <div class="body">
      @if($fr)
        <p>Bonjour {{ $user->name }},</p>
        <p>Bonne nouvelle — votre compte est validé. Vous pouvez maintenant vous connecter :</p>
        <a href="{{ url(($user->locale ?? 'fr').'/connexion') }}" class="cta">Se connecter</a>
        <p style="margin-top:24px;">Cordialement,<br>L'équipe Fab Sourcing</p>
      @else
        <p>Hello {{ $user->name }},</p>
        <p>Great news — your account has been approved. You can now log in:</p>
        <a href="{{ url(($user->locale ?? 'fr').'/connexion') }}" class="cta">Log in</a>
        <p style="margin-top:24px;">Best regards,<br>The Fab Sourcing team</p>
      @endif
    </div>
    <div class="footer">
      Fab Sourcing · fab-sourcing.fr
    </div>
  </div>
</body>
</html>
