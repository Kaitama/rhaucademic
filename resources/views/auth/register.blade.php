@extends('layouts.form')
@section('pagetitle', 'Daftar Akun')

@section('form')
<div class="ui middle aligned center aligned grid">
	<div class="column">
		<h2 class="ui teal image header">
			<img src="{{asset('assets/img/app/logo.png')}}" class="image">
			<div class="content">
				Register User
			</div>
		</h2>
		<form class="ui large form error" method="POST" action="{{ route('register') }}">
			@csrf
			<div class="ui stacked segment">
				<div class="ui horizontal divider">Data Santri</div>
				<div class="field">
					<div class="ui left icon input @error('nokk') error @enderror">
						<i class="file alternate icon"></i>
						<input type="text" value="{{old('nokk')}}" name="nokk" placeholder="Nomor Kartu Keluarga">
					</div>
				</div>
				<div class="field">
					<div class="ui left icon input @error('nik') error @enderror">
						<i class="file icon"></i>
						<input type="text" value="{{old('nik')}}" name="nik" placeholder="NIK Santri">
					</div>
				</div>
				<div class="ui horizontal divider">Data Wali Santri</div>
				<div class="field">
					<div class="ui left icon input @error('name') error @enderror">
						<i class="address card icon"></i>
						<input type="text" value="{{old('name')}}" name="name" placeholder="Nama Lengkap">
					</div>
				</div>
				<div class="field">
					<div class="ui left icon input @error('email') error @enderror">
						<i class="mail icon"></i>
						<input type="text" value="{{old('email')}}" name="email" placeholder="Email">
					</div>
				</div>
				<div class="field">
					<div class="ui left icon input @error('username') error @enderror">
						<i class="user icon"></i>
						<input type="text" value="{{old('username')}}" name="username" placeholder="Username">
					</div>
				</div>
				<div class="field">
					<div class="ui left icon input @error('password') error @enderror">
						<i class="lock icon"></i>
						<input type="password" name="password" placeholder="Password">
					</div>
				</div>
				<div class="field">
					<div class="ui left icon input">
						<i class="lock icon"></i>
						<input type="password" name="password_confirmation" placeholder="Konfirmasi Password">
					</div>
				</div>
				<button type="submit" class="ui fluid large teal submit button">Register</button>
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
		
		@if (session('error'))
		<div class="ui error message">
			<p>{{session('error')}}</p>
		</div>
		@endif
		
		<div class="ui message">
			Sudah punya akun? <a href="{{route('login')}}">Login disini!</a>
		</div>
	</div>
</div>
@endsection