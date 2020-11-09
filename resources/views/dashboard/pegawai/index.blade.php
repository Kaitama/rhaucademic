@extends('dashboard.template')
@section('pagetitle', 'Data Pegawai')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable two column grid">
	
	<div class="five wide column" id="segment-create">
		<div class="ui segments">
			<div class="ui grey inverted segment">
				<h4 class="ui header">Tambahkan Pegawai Baru</h4>
			</div>
			<div class="ui segment">
				
				<form action="{{route('pegawai.store')}}" method="post" id="frm-create" class="ui form error" enctype="multipart/form-data">
					@csrf	
					{{-- photo --}}
					<div class="ui fluid card" id="createpic">
						<div class="blurring dimmable image">
							<div class="ui dimmer">
								<div class="content">
									<div class="center">
										<div class="ui action input cpic">
											<input type="file" name="photo">
										</div>
										<div class="ui fluid inverted labeled icon button" id="createattach"><i class="ui upload icon"></i> Pilih Photo</div>
									</div>
								</div>
							</div>
							<img src="{{asset('assets/img/user/nopic.png')}}" id="cstaffphoto">
						</div>
					</div>
					{{-- // --}}

					<div class="required field @error('name') error @enderror">
						<label>Nama Lengkap</label>
						<input type="text" name="name" value="{{old('name')}}">
					</div>
					
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
					<div class="required field @error('username') error @enderror">
						<label>Username</label>
						<input type="text" name="username" value="{{old('username')}}">
					</div>
				</form>
				<div class="ui message">
					<p>Password untuk user baru: <b>password</b></p>
				</div>
			</div>
			<div class="ui segment">
				<div class="ui labeled icon button fluid green" onclick="document.getElementById('frm-create').submit()">
					<i class="save icon"></i>
					Simpan
				</div>
			</div>
		</div>
		
	</div>
	<div class="five wide column" id="segment-edit" style="display: none">
		<div class="ui segments">
			<div class="ui grey inverted segment segment">
				<h4 class="ui header">Ubah Data Pegawai</h4>
			</div>
			<div class="ui segment">
				
				<form action="{{route('pegawai.update')}}" method="post" id="frm-edit" class="ui form error" enctype="multipart/form-data">
					@csrf	
					<input type="hidden" name="id" value="">
					{{--  --}}
					<div class="ui fluid card" id="editpic">
						<div class="blurring dimmable image">
							<div class="ui dimmer">
								<div class="content">
									<div class="center">
										<div class="ui action input epic">
											<input type="file" name="ephoto">
										</div>
										<div class="ui fluid inverted labeled icon button" id="editattach"><i class="ui upload icon"></i> Pilih Photo</div>
									</div>
								</div>
							</div>
							<img src="" id="estaffphoto">
						</div>
					</div>
					{{--  --}}
					<div class="required field @error('name') error @enderror">
						<label>Nama Lengkap</label>
						<input type="text" name="name" value="">
					</div>
					<div class="required field @error('username') error @enderror">
						<label>Username</label>
						<input type="text" name="username" value="">
					</div>
					<div class="required field @error('email') error @enderror">
						<label>Email</label>
						<input type="email" name="email" value="">
					</div>
					<div id="roles" class="field">
						<label>Role</label>
						<select multiple="" name="role[]" class="ui search dropdown">
							<option value="">Kosongkan jika tidak diubah</option>
							<option value="del">Hapus role</option>
							@foreach ($roles as $role)
							<option value="{{$role->name}}">{{ucwords($role->name)}}</option>
							@endforeach
						</select>
					</div>
				</form>
				
			</div>
			<div class="ui segment">
				<div id="btn-cancel" class="ui labeled icon button">
					<i class="times icon"></i>Batal
				</div>
				<div class="ui green labeled icon button right floated" onclick="document.getElementById('frm-edit').submit()">
					<i class="save icon"></i>Simpan
				</div>
				<div class="ui horizontal divider">&bull;</div>
				<div id="btn-reset" class="ui basic fluid button">
					Reset password
				</div>
			</div>
			
			{{--  --}}
		</div>
	</div>
	
	<div class="eleven wide column">
		<div class="ui segments">
			<div class="ui grey inverted segment">
				<h4 class="ui header">Daftar Pegawai</h4>
			</div>
			<div class="ui segment">
				<div class="ui divided list">
					@foreach ($users as $user)
					<div class="item">
						<div class="content right floated">
							<div class="ui mini icon button btn-edit"
							data-id="{{$user->id}}"
							data-name="{{$user->name}}"
							data-username="{{$user->username}}"
							data-email="{{$user->email}}"
							data-roles="{{$user->getRoleNames()->implode(',')}}"
							data-photo="{{$user->photo ? asset('assets/img/user/' . $user->photo) : asset('assets/img/user/nopic.png')}}"
							>
							<i class="edit icon"></i>
						</div>
						<div class="ui mini red icon button btn-delete" data-id="{{$user->id}}" data-name="{{$user->name}}">
							<i class="trash icon"></i>
						</div>
					</div>
					<img class="ui avatar image" src="{{$user->photo ? asset('assets/img/user/' . $user->photo) : asset('assets/img/user/nopic.png')}}">
					<div class="content">
						<a class="header">{{$user->name}}</a>
						<div class="description"><i class="ui user circle icon"></i>{{$user->username}}</div>
						<div class="description"><i class="ui mail icon"></i>{{$user->email}}</div>
						<div class="description"><i class="universal access icon"></i>{{ucwords($user->getRoleNames()->implode(', '))}}</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		<div class="ui center aligned segment">
			{{$users->links()}}
		</div>
	</div>
</div>

</div>

{{-- modal reset password --}}
<div id="mdl-reset-password" class="ui basic modal">
  <div class="ui icon header">
    <i class="exclamation triangle icon"></i>
    WARNING
  </div>
  <div class="content">
		<p>Anda yakin ingin mereset password <span id="staffnamereset"></span>?</p>
		<p>User ini dapat melakukan login kembali dengan password default: <b>password</b></p>
		<form action="{{route('pegawai.resetpassword')}}" method="post" id="frm-reset-password">
			@csrf
			<input type="hidden" name="idtoreset">
		</form>
  </div>
  <div class="actions">
    <div class="ui red basic cancel inverted button">
      <i class="remove icon"></i>
      No
    </div>
    <div class="ui green ok inverted button" onclick="document.getElementById('frm-reset-password').submit()">
      <i class="checkmark icon"></i>
      Yes
    </div>
  </div>
</div>


@include('dashboard.components.modaldelete')
@endsection

@section('pagescript')
<script>
	$(".btn-delete").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		$("#message").html("Menghapus data " + name + " berarti ikut menghapus semua data yang terkait dengannya.");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('pegawai.destroy')}}");
		$("#modal-delete").modal('show');
	});
	
	
	$('.btn-edit').click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		var username = $(this).data('username');
		var email = $(this).data('email');
		var roles = $(this).data('roles');
		var role = roles.split(',');
		var photo = $(this).data('photo');
		
		$('#frm-edit input[name=name]').val(name);
		$('#frm-edit input[name=username]').val(username);
		$('#frm-edit input[name=email]').val(email);
		$('#frm-edit input[name=role]').val(roles);
		$('#frm-edit input[name=id]').val(id);
		$('#estaffphoto').attr('src', photo);
		$('.ui.search').dropdown('set exactly', role);
		$('#segment-create').hide();
		$('#segment-edit').fadeIn();
		
		$('#btn-reset').attr('data-idtoreset', id);
		$('#btn-reset').attr('data-nametoreset', name);
	});
	
	$('#btn-cancel').click(function(){
		$('#segment-edit').hide();
		$('#segment-create').fadeIn();
		$('.ui.search').dropdown('clear');
	});

	// modal reset password
	$('#btn-reset').click(function(){
		var id = $(this).data('idtoreset');
		var name = $(this).data('nametoreset');
		$('#staffnamereset').html(name);
		$('input[name=idtoreset]').val(id);
		$('#mdl-reset-password').modal('show');
	});

// file createattach dimmer
$('#createpic .image').dimmer({
		on: 'hover'
	});
	$("#createattach").click(function(){
		$("input[name=photo]").click();
	});
	$('input:file', '.ui.action.input.cpic').on('change', function(e) {
		var name = e.target.files[0].name;
		readURL(this, '#cstaffphoto');
	});

	// file editattach dimmer
	$('#editpic .image').dimmer({
		on: 'hover'
	});
	$("#editattach").click(function(){
		$("input[name=ephoto]").click();
	});
	$('input:file', '.ui.action.input.epic').on('change', function(e) {
		var name = e.target.files[0].name;
		readURL(this, '#estaffphoto');
	});

	function readURL(input, idinp) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function(e) {
				$(idinp).attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]); // convert to base64 string
		}
	}
</script>
@endsection