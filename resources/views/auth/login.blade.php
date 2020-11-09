@extends('layouts.form')
@section('pagetitle', 'Login User')

@section('form')
<div class="ui middle aligned center aligned grid">
	<div class="column">
		<h2 class="ui teal image header">
			<img src="{{asset('assets/img/app/logo.png')}}" class="image">
			<div class="content">
				Log-in User
			</div>
		</h2>
		<form class="ui large form error" method="POST" action="{{ route('login') }}">
			@csrf
			<div class="ui stacked segment">
				<div class="field">
					<div class="ui left icon input">
						<i class="user icon"></i>
						<input type="text" name="username" placeholder="Username" class="@error('username') error @enderror">
					</div>
				</div>
				<div class="field">
					<div class="ui left icon input">
						<i class="lock icon"></i>
						<input type="password" name="password" placeholder="Password" class="@error('password') error @enderror">
					</div>
				</div>
				<div class="field" style="text-align: left">
					<div class="ui checkbox">
						<input type="checkbox" tabindex="0" class="hidden" name="remember">
						<label>Remember login</label>
					</div>
				</div>
				<button type="submit" class="ui fluid large teal submit button">Login</button>
			</div>
		</form>
		@if ($errors->any())
		<div class="ui error message">
			<ul class="list">
				@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		
		{{-- <div class="ui message">
			New to us? <a href="#">Sign Up</a>
		</div> --}}
	</div>
</div>
@endsection