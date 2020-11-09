@extends('dashboard.template')
@section('pagetitle', 'Tambah Pegawai')
@section('content')

@include('dashboard.components.flashmessage')

<div class="ui segments">
	<div class="ui grey segment menu">
		<h3>Tambah Pegawai</h3>
	</div>
	<div class="ui segment">
		<form action="{{route('pegawai.store')}}" method="post" class="ui form error" enctype="multipart/form-data">
			@csrf
			
			<div class="fields">
				<div class="required ten wide field @error('name') error @enderror">
					<label>Nama Lengkap</label>
					<input type="text" name="name" value="{{old('name')}}">
				</div>
				
				<div class="six wide field">
					<label>Photo</label>
					<div class="ui action input">
						<input type="text" placeholder="Pilih foto" readonly>
						<input type="file" name="photo">
						<div id="attach" class="ui icon button">
							<i class="attach icon"></i>
						</div>
					</div>
				</div>
			</div>
			
			<div class="two fields">
				
				<div class="field">
					<label>Role</label>
					<select multiple="" name="role[]" class="ui search dropdown">
						<option value="">Pilih role</option>
						@foreach ($roles as $role)
						<option value="{{$role->name}}">{{ucwords($role->name)}}</option>
						@endforeach
					</select>
				</div>

				<div class="required field @error('email') error @enderror">
					<label>Email</label>
					<input type="email" name="email" value="{{old('email')}}">
				</div>
			</div>
			
			<div class="three fields">
				<div class="required field @error('username') error @enderror">
					<label>Username</label>
					<input type="text" name="username" value="{{old('username')}}">
				</div>

				<div class="required field @error('password') error @enderror">
					<label>Password</label>
					<input type="password" name="password">
				</div>
				
				<div class="required field">
					<label>Konfirmasi Password</label>
					<input type="password" name="password_confirmation">
				</div>
			</div>
			
			<button type="submit" class="ui labeled icon button green">
				<i class="save icon"></i>
				Simpan
			</button>
			
		</form>
	</div>
</div>

@endsection

@section('pagescript')
<script>
	$("input:text, #attach").click(function() {
		$(this).parent().find("input:file").click();
	});
	
	$('input:file', '.ui.action.input')
	.on('change', function(e) {
		var name = e.target.files[0].name;
		$('input:text', $(e.target).parent()).val(name);
	});
</script>
@endsection