@extends('layouts.admin')

@section('title', 'Контакт заявки')

@section('content')
<div class="a-page-header">
  <h1>Контакт заявки
    @if($unreadCount > 0)
      <span style="display:inline-flex;align-items:center;justify-content:center;background:#2b62d9;color:#fff;border-radius:999px;font-size:12px;font-weight:600;padding:2px 10px;margin-left:10px;vertical-align:middle">{{ $unreadCount }}</span>
    @endif
  </h1>
</div>

@if(session('success'))
  <div class="a-alert a-alert-success">{{ session('success') }}</div>
@endif

<div class="a-card">
  @if($submissions->isEmpty())
    <p style="color:#6b7891; padding:24px 0">Няма заявки.</p>
  @else
    <table class="a-table">
      <thead>
        <tr>
          <th></th>
          <th>Дата</th>
          <th>Имe</th>
          <th>Фирма</th>
          <th>Имейл</th>
          <th>Телефон</th>
          <th>Статус</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($submissions as $sub)
          <tr style="{{ !$sub->is_read ? 'font-weight:600;' : '' }}">
            <td>
              @if(!$sub->is_read)
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#2b62d9;"></span>
              @endif
            </td>
            <td style="white-space:nowrap; color:#6b7891; font-size:13px">{{ $sub->created_at->format('d.m.Y H:i') }}</td>
            <td>{{ $sub->name }}</td>
            <td>{{ $sub->company ?: '—' }}</td>
            <td><a href="mailto:{{ $sub->email }}" style="color:#2b62d9">{{ $sub->email }}</a></td>
            <td>{{ $sub->phone ?: '—' }}</td>
            <td>
              @if($sub->is_responded)
                <span style="color:#22c55e; font-size:13px">Отговорено</span>
              @elseif($sub->is_read)
                <span style="color:#6b7891; font-size:13px">Прочетено</span>
              @else
                <span style="color:#2b62d9; font-size:13px">Ново</span>
              @endif
            </td>
            <td>
              <a href="{{ route('contact-submissions.show', $sub) }}" class="a-btn a-btn-sm">Преглед</a>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div style="margin-top:24px">
      {{ $submissions->links() }}
    </div>
  @endif
</div>
@endsection
