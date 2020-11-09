<form action="{{route('student.update')}}" method="post" id="fdata1" class="ui form error" style="display: none">
	@csrf
	<input type="hidden" name="id" value="{{$student->id}}">
	<div class="field required @error('stambuk') error @enderror">
		<label>Stambuk</label>
		<input type="text" name="stambuk" value="{{old('stambuk') ?? $student->stambuk}}">
	</div>
	<div class="field required @error('name') error @enderror">
		<label>Nama Lengkap</label>
		<input type="text" name="name" value="{{old('name') ?? $student->name}}">
	</div>
	<div class="field">
		<label>Kelas</label>
		<select name="classroom_id" class="ui search dropdown">
			<option value="">Pilih kelas</option>
			@foreach ($classrooms as $classroom)
			<option value="{{$classroom->id}}"{{$classroom->id == $student->classroom_id ? ' selected' : ''}}>{{$classroom->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="field">
		<label>Asrama</label>
		<select name="dormroom_id" class="ui search dropdown">
			<option value="">Pilih asrama</option>
			@foreach ($dormrooms as $dormroom)
			<option value="{{$dormroom->id}}"{{$dormroom->id == $student->dormroom_id ? ' selected' : ''}}>{{$dormroom->name}}</option>
			@endforeach
		</select>
	</div>
	<div class="field required">
		<label>Tempat Lahir</label>
		<input type="text" name="birthplace" value="{{old('birthplace') ?? $student->birthplace}}">
	</div>
	<div class="field required @error('birthdate') error @enderror">
		<label>Tanggal Lahir</label>
		<input type="text" name="birthdate" value="{{old('birthdate') ?? date('d/m/Y', strtotime($student->birthdate))}}" autocomplete="off">
	</div>
	<div class="field required">
		<label>Jenis Kelamin</label>
		<select name="gender" class="ui fluid dropdown">
			<option value="L"{{$student->gender == 'L' ? ' selected' : ''}}>LAKI-LAKI</option>
			<option value="P"{{$student->gender == 'P' ? ' selected' : ''}}>PEREMPUAN</option>
		</select>
	</div>
	<div class="field required @error('nokk') error @enderror">
		<label>Nomor Kartu Keluarga</label>
		<input type="text" name="nokk" value="{{old('nokk') ?? $student->nokk}}">
	</div>
	<div class="field required @error('nik') error @enderror">
		<label>Nomor Induk Kependudukan</label>
		<input type="text" name="nik" value="{{old('nik') ?? $student->nik}}">
	</div>
	
	
	<div class="field">
		<button type="submit" class="ui positive fluid labeled icon button"><i class="ui save icon"></i>Simpan</button>
	</div>
</form>