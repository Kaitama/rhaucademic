<form action="{{route('student.secondary')}}" method="post" id="fdata5" class="ui form error" style="display: none">
	@csrf
	<input type="hidden" name="command" value="5">
	<input type="hidden" name="id" value="{{$student->id}}">
	
	<div class="ui two column very relaxed grid">
		<div class="column">
			
			<div class="field">
				<div class="field">
					<div id="transfer" class="ui checkbox">
						<input type="checkbox" name="transfer" tabindex="0" class="hidden" {{$student->studentprofile['transfer'] ? 'checked' : ''}}>
						<label>Pindahan dari sekolah lain.</label>
					</div>
				</div>
			</div>
			
			<div class="field">
				<label>Dari Sekolah</label>
				<select name="slevel" class="ui dropdown">
					<option value="NEGERI">NEGERI</option>
					<option value="SWASTA"{{$student->studentprofile['slevel'] == 'SWASTA' ? ' selected' : ''}}>SWASTA</option>
				</select>
			</div>
			<div class="field">
				<label>Tingkat</label>
				<select name="sfrom" class="ui dropdown">
					<option value="SD">SD</option>
					<option value="MTS"{{$student->studentprofile['sfrom'] == 'MTS' ? ' selected' : ''}}>MTS</option>
					<option value="SMP"{{$student->studentprofile['sfrom'] == 'SMP' ? ' selected' : ''}}>SMP</option>
					<option value="MA"{{$student->studentprofile['sfrom'] == 'MA' ? ' selected' : ''}}>MA</option>
				</select>
			</div>
			
			<div class="field">
				<label>Nama Sekolah</label>
				<input type="text" name="sname" value="{{$student->studentprofile['transfer'] ? $student->studentprofile['pfrom'] : $student->studentprofile['sname']}}">
			</div>
			
			<div class="field">
				<label>Alamat</label>
				<textarea name="sadd" rows="3">{{$student->studentprofile['transfer'] ? $student->studentprofile['padd'] : $student->studentprofile['sadd']}}</textarea>
			</div>
			
		</div>
		<div class="column">
			
			<div class="field">
				<label>Nomor Pokok Sekolah Nasional</label>
				<input type="text" name="snpsn" value="{{old('snpsn') ?? $student->studentprofile['snpsn']}}">
			</div>
			
			<div class="field">
				<label>Nomor Ujian Nasional</label>
				<input type="text" name="sun" value="{{old('sun') ?? $student->studentprofile['sun']}}">
			</div>
			
			<div class="field">
				<label>Nomor Ijazah</label>
				<input type="text" name="sijazah" value="{{old('sijazah') ?? $student->studentprofile['sijazah']}}">
			</div>
			
			<div class="field">
				<label>Nomor Surat Keterangan Hasil Ujian Nasional</label>
				<input type="text" name="sskhun" value="{{old('sskhun') ?? $student->studentprofile['sskhun']}}">
			</div>
			
			<div class="field tfield" style="display: {{$student->studentprofile['transfer'] ? 'block' : 'none'}}">
				<label>Alasan Pindah</label>
				<select name="preason" class="ui dropdown">
					@foreach ($reasons as $reason)
					<option value="{{$reason}}"{{$student->studentprofile['preason'] == $reason ? ' selected' : ''}}>{{$reason}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="field tfield" style="display: {{$student->studentprofile['transfer'] ? 'block' : 'none'}}">
				<label>Keterangan</label>
				<textarea name="pdescription" rows="3">{{old('pdescription') ?? $student->studentprofile['pdescription']}}</textarea>
			</div>
			
			
			<div class="field">
				<button type="submit" class="ui positive labeled icon button fluid"><i class="ui save icon"></i>Simpan</button>
			</div>
		</div>
	</div>
</form>