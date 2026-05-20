@extends('layouts.admin')

@section('title', 'Заявка от ' . $contactSubmission->name)

@section('content')
<div class="a-page-header">
  <h1>Заявка от {{ $contactSubmission->name }}</h1>
  <a href="{{ route('contact-submissions.index') }}" class="a-btn a-btn-ghost">← Назад</a>
</div>

@if(session('success'))
  <div class="a-alert a-alert-success">{{ session('success') }}</div>
@endif

<div class="a-form-card" style="display:grid; grid-template-columns:1.4fr 1fr; gap:24px; align-items:start">

  {{-- Main info --}}
  <div>
    <div style="margin-bottom:24px; display:flex; gap:12px; flex-wrap:wrap">
      @if(!$contactSubmission->is_responded)
        <form action="{{ route('contact-submissions.mark-responded', $contactSubmission) }}" method="POST">
          @csrf @method('POST')
          <button type="submit" class="a-btn">Отбележи като отговорено</button>
        </form>
      @else
        <span style="color:#22c55e; font-weight:500">✓ Отговорено на {{ $contactSubmission->responded_at?->format('d.m.Y') }}</span>
      @endif

      <form action="{{ route('contact-submissions.destroy', $contactSubmission) }}" method="POST"
            onsubmit="return confirm('Изтрий тази заявка?')">
        @csrf @method('DELETE')
        <button type="submit" class="a-btn a-btn-danger">Изтрий</button>
      </form>
    </div>

    <table style="width:100%; border-collapse:collapse">
      <tr style="border-bottom:1px solid rgba(15,30,61,0.08)">
        <td style="padding:12px 0; color:#6b7891; font-size:13px; width:140px">Дата</td>
        <td style="padding:12px 0">{{ $contactSubmission->created_at->format('d.m.Y H:i') }}</td>
      </tr>
      <tr style="border-bottom:1px solid rgba(15,30,61,0.08)">
        <td style="padding:12px 0; color:#6b7891; font-size:13px">Имe</td>
        <td style="padding:12px 0; font-weight:600">{{ $contactSubmission->name }}</td>
      </tr>
      @if($contactSubmission->company)
      <tr style="border-bottom:1px solid rgba(15,30,61,0.08)">
        <td style="padding:12px 0; color:#6b7891; font-size:13px">Фирма</td>
        <td style="padding:12px 0">{{ $contactSubmission->company }}</td>
      </tr>
      @endif
      <tr style="border-bottom:1px solid rgba(15,30,61,0.08)">
        <td style="padding:12px 0; color:#6b7891; font-size:13px">Имейл</td>
        <td style="padding:12px 0"><a href="mailto:{{ $contactSubmission->email }}" style="color:#2b62d9">{{ $contactSubmission->email }}</a></td>
      </tr>
      @if($contactSubmission->phone)
      <tr style="border-bottom:1px solid rgba(15,30,61,0.08)">
        <td style="padding:12px 0; color:#6b7891; font-size:13px">Телефон</td>
        <td style="padding:12px 0"><a href="tel:{{ $contactSubmission->phone }}">{{ $contactSubmission->phone }}</a></td>
      </tr>
      @endif
      @if($contactSubmission->attachment)
      <tr style="border-bottom:1px solid rgba(15,30,61,0.08)">
        <td style="padding:12px 0; color:#6b7891; font-size:13px">Приложение</td>
        <td style="padding:12px 0">
          <a href="{{ $contactSubmission->attachment_url }}" target="_blank" class="a-btn a-btn-sm">Изтегли</a>
        </td>
      </tr>
      @endif
    </table>

    <div style="margin-top:28px">
      <div style="font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:#6b7891; margin-bottom:12px">Съобщение</div>
      <div style="background:#f4f5f7; border-radius:8px; padding:20px 24px; line-height:1.7; white-space:pre-wrap; font-size:15px">{{ $contactSubmission->message }}</div>
    </div>
  </div>

  {{-- Side meta --}}
  <div class="a-card" style="padding:20px">
    <div style="font-size:12px; letter-spacing:0.1em; text-transform:uppercase; color:#6b7891; margin-bottom:16px">Статус</div>
    <div style="display:flex; flex-direction:column; gap:10px; font-size:14px">
      <div style="display:flex; align-items:center; gap:8px">
        <span style="width:8px;height:8px;border-radius:50%;background:{{ $contactSubmission->is_read ? '#22c55e' : '#2b62d9' }};flex-shrink:0"></span>
        {{ $contactSubmission->is_read ? 'Прочетено' : 'Непрочетено' }}
      </div>
      <div style="display:flex; align-items:center; gap:8px">
        <span style="width:8px;height:8px;border-radius:50%;background:{{ $contactSubmission->is_responded ? '#22c55e' : '#d93025' }};flex-shrink:0"></span>
        {{ $contactSubmission->is_responded ? 'Отговорено' : 'Без отговор' }}
      </div>
    </div>

    <div style="margin-top:24px; padding-top:24px; border-top:1px solid rgba(15,30,61,0.08)">
      <a href="mailto:{{ $contactSubmission->email }}?subject=Re: votre demande Fab Sourcing"
         class="a-btn" style="width:100%; justify-content:center; display:flex">
        Отговори по имейл
      </a>
    </div>
  </div>

</div>
@endsection
