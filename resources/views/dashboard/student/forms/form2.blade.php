<form action="{{route('student.secondary')}}" method="post" id="fdata2" class="ui form error" style="display: none">
	@csrf
	<input type="hidden" name="command" value="2">
	<input type="hidden" name="id" value="{{$student->id}}">
	<div class="ui two column very relaxed grid">
		<div class="column">
			<div class="field">
				<label>Nama Panggilan</label>
				<input type="text" name="nickname" value="{{old('nickname') ?? $student->studentprofile['nickname']}}">
			</div>
			<div class="field">
				<label>Nomor Induk Siswa Nasional</label>
				<input type="text" name="nisn" value="{{old('nisn') ?? $student->studentprofile['nisn']}}">
			</div>
			<div class="field">
				<label>Golongan Darah</label>
				<input type="text" name="blood" value="{{old('blood') ?? $student->studentprofile['blood']}}">
			</div>
			<div class="field">
				<label>Tinggi Badan</label>
				<input type="text" name="height" value="{{old('height') ?? $student->studentprofile['height']}}">
			</div>
			<div class="field">
				<label>Berat Badan</label>
				<input type="text" name="weight" value="{{old('weight') ?? $student->studentprofile['weight']}}">
			</div>
			<div class="field">
				<label>Hobby</label>
				@php $hb = explode(', ', $student->studentprofile['hobby']); @endphp
				<select multiple name="hobby[]" class="ui multiple dropdown">
					@foreach ($hobbies as $hobby)
					<option value="{{$hobby}}" @foreach($hb as $h) {{$h == $hobby ? 'selected' : ''}} @endforeach>{{$hobby}}</option>
					@endforeach
				</select>
			</div>
			<div class="field">
				<label>Cita - Cita</label>
				@php $ws = explode(', ', $student->studentprofile['wishes']); @endphp
				<select multiple name="wishes[]" class="ui multiple dropdown">
					@foreach ($hobbies as $wishes)
					<option value="{{$wishes}}" @foreach($ws as $w) {{$w == $wishes ? 'selected' : ''}} @endforeach>{{$wishes}}</option>
					@endforeach
				</select>
			</div>
		</div>
		
		<div class="column">			
			<div class="field">
				<label>Prestasi</label>
				<input type="text" name="achievement" value="{{old('achievement') ?? $student->studentprofile['achievement']}}">
			</div>
			<div class="field">
				<label>Pada Kompetisi</label>
				<textarea name="competition" rows="3">{{old('competition') ?? $student->studentprofile['competition']}}</textarea>
			</div>
			<div class="field">
				<label>Anak Ke</label>
				<input type="text" name="numposition" value="{{old('numposition') ?? $student->studentprofile['numposition']}}">
			</div>
			<div class="field">
				<label>Jumlah Saudara Kandung</label>
				<input type="text" name="siblings" value="{{old('siblings') ?? $student->studentprofile['siblings']}}">
			</div>
			<div class="field">
				<label>Jumlah Saudara Tiri</label>
				<input type="text" name="stepsiblings" value="{{old('stepsiblings') ?? $student->studentprofile['stepsiblings']}}">
			</div>
			<div class="field">
				<button type="submit" class="ui positive labeled icon fluid button"><i class="ui save icon"></i>Simpan</button>
			</div>
		</div>
	</div>
	
	
</form>