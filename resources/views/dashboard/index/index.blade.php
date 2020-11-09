@extends('dashboard.template')
@section('pagetitle', 'Dashboard')
@section('content')

@if ($passnotify)
<div class="ui red icon message">
  <i class="exclamation icon"></i>
  <div class="content">
    <div class="header">
      Security Alert!
    </div>
    <p>Anda masih menggunakan password default. Segera ubah password anda pada menu <b>Settings</b> atau <b><a href="{{route('user.settings')}}">klik disini</a></b>.</p>
  </div>
</div>
@endif

@include('dashboard.index.parts.welcome')

@endsection