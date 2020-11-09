<form action="{{route('student.secondary')}}" method="post" id="fdata3" class="ui form error" style="display: none">
	@csrf
	<input type="hidden" name="command" value="3">
	<input type="hidden" name="id" value="{{$student->id}}">
	<div class="ui two column very relaxed grid">
		<div class="column">
			<div class="field">
				<label>Nama Ayah</label>
				<input type="text" name="fname" value="{{old('fname') ?? $student->studentprofile['fname']}}">
			</div>
			<div class="field">
				<label>Status</label>
				<select name="flive" class="ui dropdown">
					<option value="1">HIDUP</option>
					<option value="0"{{$student->studentprofile['flive'] == 0 ? ' selected' : '' }}>MENINGGAL</option>
				</select>
			</div>
			<div class="field">
				<label>Nomor KTP</label>
				<input type="text" name="fktp" value="{{old('fktp') ?? $student->studentprofile['fktp']}}">
			</div>
			<div class="field">
				<label>Nomor Telepon</label>
				<input type="text" name="fphone" value="{{old('fphone') ?? $student->studentprofile['fphone']}}">
			</div>
			<div class="field">
				<label>Nomor WhatsApp</label>
				<input type="text" name="fwa" value="{{old('fwa') ?? $student->studentprofile['fwa']}}">
			</div>
			<div class="field">
				<label>Alamat</label>
				<textarea name="fadd" rows="3">{{old('fadd') ?? $student->studentprofile['fadd']}}</textarea>
			</div>
			<div class="field">
				<label>Pendidikan Terakhir</label>
				<select name="fedu" class="ui dropdown">
					<option value="">Jenjang pendidikan</option>
					@foreach ($educations as $edu)
					<option value="{{$edu}}"{{$student->studentprofile['fedu'] == $edu ? ' selected' : ''}}>{{$edu}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="field">
				<label>Agama</label>
				<select name="freligion" class="ui dropdown">
					@foreach ($religions as $religi)
					<option value="{{$religi}}"{{$student->studentprofile['freligion'] == $religi ? ' selected' : ''}}>{{$religi}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="field">
				<label>Pekerjaan</label>
				<select name="fwork" class="ui search dropdown">
					<option value="">Pilih pekerjaan</option>
					@foreach ($wishes as $work)
					<option value="{{$work}}"{{$student->studentprofile['fwork'] == $work ? ' selected' : ''}}>{{$work}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="field">
				<label>Penghasilan / Bulan</label>
				<input type="text" name="fsalary" value="{{old('fsalary') ?? $student->studentprofile['fsalary']}}">
			</div>
			
			<div class="field">
				<label>Penghasilan Tambahan / Bulan</label>
				<input type="text" name="faddsalary" value="{{old('faddsalary') ?? $student->studentprofile['faddsalary']}}">
			</div>
			
			<div class="field">
				<label>Status Pernikahan</label>
				<select name="mariage" class="ui dropdown">
					<option value="1">MENIKAH</option>
					<option value="0"{{$student->studentprofile['mariage'] == 0 ? ' selected' : ''}}>BERCERAI</option>
				</select>
			</div>
		</div>
		
		<div class="column">
			
			<div class="field">
				<label>Nama Ibu</label>
				<input type="text" name="mname" value="{{old('mname') ?? $student->studentprofile['mname']}}">
			</div>
			<div class="four field">
				<label>Status</label>
				<select name="mlive" class="ui dropdown">
					<option value="1">HIDUP</option>
					<option value="0"{{$student->studentprofile['mlive'] == 0 ? ' selected' : '' }}>MENINGGAL</option>
				</select>
			</div>
			<div class="field">
				<label>Nomor KTP</label>
				<input type="text" name="mktp" value="{{old('mktp') ?? $student->studentprofile['mktp']}}">
			</div>
			<div class="field">
				<label>Nomor Telepon</label>
				<input type="text" name="mphone" value="{{old('mphone') ?? $student->studentprofile['mphone']}}">
			</div>
			<div class="field">
				<label>Nomor WhatsApp</label>
				<input type="text" name="mwa" value="{{old('mwa') ?? $student->studentprofile['mwa']}}">
			</div>
			<div class="field">
				<label>Alamat</label>
				<textarea name="madd" rows="3">{{old('madd') ?? $student->studentprofile['madd']}}</textarea>
			</div>
			<div class="field">
				<label>Pendidikan Terakhir</label>
				<select name="medu" class="ui dropdown">
					<option value="">Jenjang pendidikan</option>
					@foreach ($educations as $edu)
					<option value="{{$edu}}"{{$student->studentprofile['medu'] == $edu ? ' selected' : ''}}>{{$edu}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="field">
				<label>Agama</label>
				<select name="mreligion" class="ui dropdown">
					@foreach ($religions as $religi)
					<option value="{{$religi}}"{{$student->studentprofile['mreligion'] == $religi ? ' selected' : ''}}>{{$religi}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="field">
				<label>Pekerjaan</label>
				<select name="mwork" class="ui search dropdown">
					<option value="">Pilih pekerjaan</option>
					@foreach ($wishes as $work)
					<option value="{{$work}}"{{$student->studentprofile['mwork'] == $work ? ' selected' : ''}}>{{$work}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="field">
				<label>Penghasilan / Bulan</label>
				<input type="text" name="msalary" value="{{old('msalary') ?? $student->studentprofile['msalary']}}">
			</div>
			
			<div class="field">
				<label>Penghasilan Tambahan / Bulan</label>
				<input type="text" name="maddsalary" value="{{old('maddsalary') ?? $student->studentprofile['maddsalary']}}">
			</div>
			
			<div class="field">
				<div class="ui checkbox">
					<input type="checkbox" name="donatur" tabindex="0" class="hidden"{{$student->studentprofile['donatur'] ? '' : ' checked'}}>
					<label>Santri dibiayai oleh orang tua.</label>
				</div>
			</div>
			
			<div class="field">
				<button type="submit" class="ui positive labeled icon button fluid"><i class="ui save icon"></i>Simpan</button>
			</div>
		</div>
	</div>
</form>