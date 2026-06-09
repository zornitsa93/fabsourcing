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
    .footer { text-align:center; padding:24px; color:#6b7891; font-size:12px; }
  </style>
</head>
<body>
  <div class="card">
    <div class="header">
      <h1>{{ $fr ? 'Fab Sourcing' : 'Fab Sourcing' }}</h1>
      <p>{{ $fr ? 'Demande de compte' : 'Account request' }}</p>
    </div>
    <div class="body">
      @if($fr)
        <p>Bonjour {{ $user->name }},</p>
        <p>Merci, votre demande de compte a bien été reçue. Elle sera validée par notre équipe sous peu.</p>
        <p>Vous recevrez un e-mail dès que votre compte sera activé.</p>
        <p>Cordialement,<br>L'équipe Fab Sourcing</p>
      @else
        <p>Hello {{ $user->name }},</p>
        <p>Thank you — your account request has been received. It will be reviewed by our team shortly.</p>
        <p>You will receive an email once your account is activated.</p>
        <p>Best regards,<br>The Fab Sourcing team</p>
      @endif
    </div>
    <div class="footer">
      Fab Sourcing · fab-sourcing.fr
    </div>
  </div>
</body>
</html>
