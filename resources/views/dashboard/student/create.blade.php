@extends('dashboard.template')
@section('pagetitle', 'Tambah Santri')

@section('content')

@include('dashboard.components.flashmessage')

<form action="{{route('student.store')}}" method="post" class="ui form error" enctype="multipart/form-data">
	@csrf
	
	<div class="ui stackable grid">
		<div class="five wide column">
			<div class="ui segments">
				<div class="ui grey segment menu">
					<h3>Foto Santri</h3>
				</div>
				<div class="ui segment">
					<div class="ui fluid card" id="propic">
						<div class="blurring dimmable image">
							<div class="ui dimmer">
								<div class="content">
									<div class="center">
										<div class="ui action input">
											<input type="file" name="photo">
										</div>
										<div class="ui fluid inverted labeled icon button" id="attach"><i class="ui upload icon"></i> Pilih Photo</div>
									</div>
								</div>
							</div>
							<img src="{{asset('assets/img/user/nopic.png')}}" id="studentphoto">
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="eleven wide column">
			<div class="ui segments">
				<div class="ui grey segment menu">
					<h3>Data Primer Santri</h3>
				</div>
				<div class="ui segment">
					<div class="field required @error('stambuk') error @enderror">
						<label>Stambuk</label>
						<input type="text" name="stambuk" value="{{old('stambuk')}}">
					</div>
					<div class="field required @error('name') error @enderror">
						<label>Nama Lengkap</label>
						<input type="text" name="name" value="{{old('name')}}">
					</div>
					<div class="field">
						<label>Kelas</label>
						<select name="classroom_id" class="ui search dropdown">
							<option value="">Pilih kelas</option>
							@foreach ($classrooms as $classroom)
							<option value="{{$classroom->id}}">{{$classroom->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="field">
						<label>Asrama</label>
						<select name="dormroom_id" class="ui search dropdown">
							<option value="">Pilih asrama</option>
							@foreach ($dormrooms as $dormroom)
							<option value="{{$dormroom->id}}">{{$dormroom->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="field required @error('birthplace') error @enderror">
						<label>Tempat Lahir</label>
						<input type="text" name="birthplace" value="{{old('birthplace')}}">
					</div>
					<div class="field required @error('birthdate') error @enderror">
						<label>Tanggal Lahir</label>
						<input type="text" name="birthdate" value="{{old('birthdate')}}" autocomplete="off">
					</div>
					<div class="field required">
						<label>Jenis Kelamin</label>
						<select name="gender" class="ui fluid dropdown">
							<option value="L">LAKI-LAKI</option>
							<option value="P">PEREMPUAN</option>
						</select>
					</div>
					<div class="field required @error('nokk') error @enderror">
						<label>Nomor Kartu Keluarga</label>
						<input type="text" name="nokk" value="{{old('nokk')}}">
					</div>
					<div class="field required @error('nik') error @enderror">
						<label>Nomor Induk Kependudukan</label>
						<input type="text" name="nik" value="{{old('nik')}}">
					</div>

					<div class="ui divider"></div>
					
					<div class="field">
						<button type="submit" class="ui positive labeled icon button"><i class="ui save icon"></i>Simpan</button>
					</div>

				</div>
			</div>
		</div>
	</div>
</form>

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
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function(e) {
				$('#studentphoto').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]); // convert to base64 string
		}
	}
</script>
@endsection