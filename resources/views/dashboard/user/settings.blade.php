@extends('dashboard.template')
@section('pagetitle', 'User Settings')
@section('content')

@include('dashboard.components.flashmessage')

<div class="ui stackable two column grid">
	<div class="five wide column">
		{{-- profile picture --}}
		<div class="ui segments">
			<div class="ui segment inverted">
				<h4 class="ui header">Profile Picture</h4>
			</div>
			<div class="ui segment">
				<div class="ui fluid card" id="propic">
					<div class="blurring dimmable image">
						<div class="ui dimmer">
							<div class="content">
								<div class="center">
									<form action="{{route('user.updatepict')}}" id="frm-pict" method="post" enctype="multipart/form-data" style="margin: 0; padding: 0;">
										@csrf
										<div class="ui action input">
											<input type="file" name="photo">
										</div>
									</form>
									<div class="ui fluid inverted labeled icon button" id="attach"><i class="ui upload icon"></i> Pilih Photo</div>
								</div>
							</div>
						</div>
						<img src="{{Auth::user()->photo ? asset('assets/img/user/' . Auth::user()->photo) : asset('assets/img/user/nopic.png')}}" id="authphoto">
					</div>
				</div>
			</div>
			<div id="save-pict" class="ui segment" style="display: none">
				<div class="ui fluid black labeled icon button" onclick="document.getElementById('frm-pict').submit()">
					<i class="save icon"></i>Simpan
				</div>
			</div>
		</div>
	</div>
	
	<div class="eleven wide column">
		<div class="ui stackable two column grid">
			{{-- login info --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui segment black inverted">
						<h4 class="ui header">Login Information</h4>
					</div>
					<div class="ui segment">
						<form action="{{route('user.updatelogin')}}" id="frm-info" class="ui form" method="post">
							@csrf
							<div class="field @error('name') error @enderror">
								<label>Nama Lengkap</label>
								<input type="text" name="name" value="{{old('name') ?? Auth::user()->name}}">
							</div>
							<div class="field @error('email') error @enderror">
								<label>Email</label>
								<input type="text" name="email" value="{{old('email') ?? Auth::user()->email}}">
							</div>
							<div class="field @error('username') error @enderror">
								<label>Username</label>
								<input type="text" name="username" value="{{old('username') ?? Auth::user()->username}}">
							</div>
						</form>
					</div>
					<div class="ui segment">
						<div class="ui labeled icon button black fluid" onclick="document.getElementById('frm-info').submit()">
							<i class="save icon"></i>Simpan
						</div>
					</div>
				</div>
			</div>
			{{-- password --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui segment black inverted">
						<h4 class="ui header">Change Password</h4>
					</div>
					<div class="ui segment">
						<form action="{{route('user.updatepassword')}}" id="frm-pass" class="ui form" method="post">
							@csrf
							<div class="field @error('old_password') error @enderror">
								<label>Password Lama</label>
								<input type="password" name="old_password">
							</div>
							<div class="field @error('password') error @enderror">
								<label>Password Baru</label>
								<input type="password" name="password">
							</div>
							<div class="field">
								<label>Konfirmasi Password</label>
								<input type="password" name="password_confirmation">
							</div>
						</form>
					</div>
					<div class="ui segment">
						<div class="ui labeled icon button black fluid" onclick="document.getElementById('frm-pass').submit()">
							<i class="save icon"></i>Simpan
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="ui horizontal divider">&bull;</div>
		{{-- biodata --}}
		<div class="ui segments">
			<div class="ui black inverted segment">
				<h4 class="ui header">Profile Information</h4>
			</div>
			<div class="ui segment">
				<div class="ui message">
					<p>Pending content ...</p>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('pagescript')
<script>
	$('#propic .image').dimmer({
		on: 'hover'
	});
	$("#attach").click(function(){
		$("input[name=photo]").click();
	});
	$('input:file', '.ui.action.input')
	.on('change', function(e) {
		var name = e.target.files[0].name;
		readURL(this);
		$('#save-pict').transition('fade in');
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function(e) {
				$('#authphoto').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]); // convert to base64 string
		}
	}
</script>
@endsection