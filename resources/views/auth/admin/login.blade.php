<!DOCTYPE html>
<html lang="bg">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Вход — 3omara</title>
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>

<div class="a-login-shell">
  <div class="a-login-card">

    <img class="a-login-logo" src="{{ asset('images/logo-omara.svg') }}" alt="3omara" style="display:block; margin: 0 auto 28px;" />

    <h1>3omara</h1>
    <p class="a-login-sub">Влезте в административния панел.</p>

    @if(session('error'))
      <div class="a-alert a-alert-error">{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="a-alert a-alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('adminLoginPost') }}" method="POST">
      @csrf

      <div class="a-field">
        <label for="email">Имейл</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}"
               required autocomplete="email" autofocus>
        @error('email')
          <div class="a-field-error">{{ $message }}</div>
        @enderror
      </div>

      <div class="a-field">
        <label for="password">Парола</label>
        <input id="password" type="password" name="password"
               required autocomplete="current-password">
        @error('password')
          <div class="a-field-error">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="a-btn a-btn-primary" style="width:100%; justify-content:center; margin-top:8px;">
        Вход
      </button>
    </form>

    <p class="a-login-footer">3omara Admin</p>
  </div>
</div>

</body>
</html>
