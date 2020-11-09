@extends('dashboard.template')
@section('pagetitle', 'Ubah Pegawai')
@section('content')

@include('dashboard.components.flashmessage')

<div class="ui segments">
	<div class="ui grey segment menu">
		<h3>Ubah Data Pegawai</h3>
	</div>
	<div class="ui segment">
		<form action="{{route('pegawai.update')}}" method="post" class="ui form error" enctype="multipart/form-data">
			@csrf
			
			<input type="hidden" name="id" value="{{$user->id}}">
			
			<div class="fields">
				<div class="required ten wide field @error('name') error @enderror">
					<label>Nama Lengkap</label>
					<input type="text" name="name" value="{{old('name') ? old('name') : $user->name}}">
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
			
			<div class="three fields">
				<div class="field">
					<label>Role</label>
					<select multiple="" name="role[]" class="ui search dropdown">
						<option value="">Kosongkan jika tidak diubah</option>
						<option value="del">Hapus role user</option>
						@foreach ($roles as $role)
						<option value="{{$role->name}}">{{ucwords($role->name)}}</option>
						@endforeach
					</select>
				</div>
				
				<div class="required field @error('username') error @enderror">
					<label>Username</label>
					<input type="text" name="username" value="{{old('username') ? old('username') : $user->username}}">
				</div>
				
				<div class="required field @error('email') error @enderror">
					<label>Email</label>
					<input type="email" name="email" value="{{old('email') ? old('email') : $user->email}}">
				</div>
			</div>
			


			<button type="submit" class="ui labeled icon button green">
				<i class="save icon"></i>
				Simpan
			</button>
			<button type="button" id="btn-update-pass" class="ui labeled icon button basic right floated">
				<i class="lock icon"></i>
				Ubah password
			</button>
			
		</form>
	</div>
</div>

	
	<div id="modal-update-password" class="ui modal tiny">
		<div class="header">
			Ubah Password {{$user->name}}
		</div>
		<div class="content">
			<div class="description">
				<form action="{{route('pegawai.updatepassword')}}" method="post" class="ui form" id="frmuppass">
					@csrf
					<input type="hidden" name="id" value="{{$user->id}}">
					<div class="required field @error('old_password') error @enderror">
						<label>Password Lama</label>
						<input type="password" name="old_password">
					</div>
					
					<div class="required field @error('password') error @enderror">
						<label>Password Baru</label>
						<input type="password" name="password">
					</div>
					
					<div class="required field">
						<label>Konfirmasi Password Baru</label>
						<input type="password" name="password_confirmation">
					</div>
				</form>
			</div>
		</div>
		<div class="actions">
			<div class="ui black deny button">
				Batal
			</div>
			<div class="ui positive right labeled icon button" onclick="event.preventDefault(); document.getElementById('frmuppass').submit();">
				Ubah password
				<i class="lock icon"></i>
			</div>
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
		
		$('#btn-update-pass').click(function(){
			$('#modal-update-password').modal('show');
		});
		
		
		// $('.ui.toggle').click(function(){
			// // 	chk = $('#chkpass');
			// 	state = $(this).hasClass('checked');
			// 	if(state){
				// 		$('#update-pass').show();
				// 	} else {
					// 		$('#update-pass').hide();
					// 	}
					
					// });
				</script>
				@endsection