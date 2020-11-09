<form action="{{route('student.secondary')}}" method="post" id="fdata4" class="ui form error" style="display: none">
	@csrf
	<input type="hidden" name="command" value="4">
	<input type="hidden" name="id" value="{{$student->id}}">
	<div class="ui two column very relaxed grid">
		<div class="column">
			<div class="field">
				<label>Nama Donatur</label>
				<input type="text" name="dname" value="{{old('dname') ?? $student->studentprofile['dname']}}">
			</div>
			
			<div class="field">
				<label>Hubungan Dengan Santri</label>
				<select name="drelation" class="ui dropdown">
					@foreach ($donaters as $donatur)
					<option value="{{$donatur}}"{{$student->studentprofile['drelation'] == $donatur ? ' selected' : ''}}>{{$donatur}}</option>
					@endforeach
				</select>
			</div>
			
			<div class="field">
				<label>Nomor Telepon</label>
				<input type="text" name="dphone" value="{{old('dphone') ?? $student->studentprofile['dphone']}}">
			</div>
		</div>
		<div class="column">
			<div class="field">
				<label>Alamat</label>
				<textarea name="dadd" rows="3">{{old('dadd') ?? $student->studentprofile['dadd']}}</textarea>
			</div>
			
			<div class="field">
				<button type="submit" class="ui positive labeled icon button fluid"><i class="ui save icon"></i>Simpan</button>
			</div>
		</div>
	</div>
	
</form>