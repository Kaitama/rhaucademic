@extends('dashboard.template')
@section('pagetitle', 'Data Ekstrakurikuler')

@section('content')
@include('dashboard.components.flashmessage')
@php
$days = ['1' => 'Senin', '2' => 'Selasa', '3' => 'Rabu', '4' => 'Kamis', '5' => 'Jum\'at', '6' => 'Sabtu', '7' => 'Minggu'];
@endphp
<div class="ui stackable two column grid">
	
	<div class="five wide column">
		<div class="ui segments">
			<div class="ui grey segment">
				<h4 class="ui header">Tambah Ekstrakurikuler Baru</h4>
			</div>
			<div class="ui segment">
				<form action="{{route('extracurricular.store')}}" method="post" id="frm-store" class="ui form error">
					@csrf
					<div class="field required @error('name') error @enderror">
						<label>Nama Ekstrakurikuler</label>
						<input type="text" name="name" value="{{old('name')}}">
					</div>
					<div class="field">
						<label>Deskripsi</label>
						<textarea name="description" rows="3">{{old('description')}}</textarea>
					</div>
					<div class="field required @error('mentor') error @enderror">
						<label>Pembina / Mentor</label>
						<div class="ui fluid search selection dropdown mentor">
							<input type="hidden" name="mentor" value="{{old('mentor')}}">
							<div class="default text"></div>
							<i class="dropdown icon"></i>
						</div>
					</div>
					<div class="field required">
						<label>Hari</label>
						<select name="day" id="day" class="ui dropdown dayselect">
							@foreach ($days as $key => $val)
							<option value="{{$key}}">{{$val}}</option>
							@endforeach
						</select>
					</div>
					<div class="field required @error('time') errror @enderror">
						<label>Jam</label>
						<input type="text" name="time" value="{{old('time') ?? '15:30'}}">
					</div>
				</form>
			</div>
			<div class="ui segment">
				<button class="ui fluid button positive labeled icon" onclick="document.getElementById('frm-store').submit()">
					<i class="ui save icon"></i><span id="btn-form-text">Simpan</span>
				</button>
			</div>
		</div>
	</div>
	<div class="eleven wide column">
		<div class="ui segments">
			<div class="ui grey segment">
				<h4 class="ui header">Data Ekstrakurikuler Aktif</h4>
			</div>
			<div class="ui segment">
				@if ($actives->isEmpty())
				<div class="ui message">Data ekstrakurikuler aktif masih kosong.</div>
				@else
				<div class="ui divided list">
					@foreach ($actives as $active)
					@php 
					switch ($active->day) {
						case 1: $d = 'Senin'; break;
						case 2: $d = 'Selasa'; break;
						case 3: $d = 'Rabu'; break;
						case 4: $d = 'Kamis'; break;
						case 5: $d = 'Jum\'at'; break;
						case 6: $d = 'Sabtu'; break;
						default: $d = 'Minggu'; break;
					}
					@endphp
					@if ($active->active)
					
					<div class="item">
						<div class="right floated content">
							<div class="ui mini labeled icon button editing" data-id="{{$active->id}}" data-name="{{$active->name}}" data-desc="{{$active->description}}" data-day="{{$active->day}}" data-time="{{date('H:i', strtotime($active->time))}}" data-mentor="{{$active->user->id}}" data-mentorname="{{$active->user->name}}"><i class="ui edit icon"></i>Edit</div>
							<div class="ui mini labeled icon button negative toggling" data-id="{{$active->id}}" data-name="{{$active->name}}" data-cmd="d">
								<i class="ui times icon"></i>Nonaktifkan
							</div>
						</div>
						<div class="content">
							<div class="header"><a href="{{route('extracurricular.show', $active->id)}}">{{$active->name}}</a></div>
							<div class="description">Dibimbing oleh {{$active->user->name}}</div>
							<div class="description">Hari {{$d}} pukul {{date('H:i', strtotime($active->time))}} WIB</div>
						</div>
					</div>
					
					@endif
					@endforeach
				</div>
				@endif
			</div>
		</div>
		
		
		{{-- inactive extracurricular --}}
		
		<div class="ui segments">
			<div class="ui segment">
				<h4 class="ui header">Data Ekstrakurikuler Nonaktif</h4>
			</div>
			<div class="ui segment">
				@if ($inactives->isEmpty())
				<div class="ui message">Data ekstrakurikuler nonaktif masih kosong.</div>
				@else
				<div class="ui divided list">
					@foreach ($inactives as $inactive)
					@php 
					switch ($inactive->day) {
						case 1: $d = 'Senin'; break;
						case 2: $d = 'Selasa'; break;
						case 3: $d = 'Rabu'; break;
						case 4: $d = 'Kamis'; break;
						case 5: $d = 'Jum\'at'; break;
						case 6: $d = 'Sabtu'; break;
						default: $d = 'Minggu'; break;
					}
					@endphp
					
					
					<div class="item">
						<div class="right floated content">
							<div class="ui mini labeled icon button positive toggling" data-id="{{$inactive->id}}" data-name="{{$inactive->name}}" data-cmd="a">
								<i class="ui check icon"></i>Aktifkan
							</div>
						</div>
						<div class="content">
							<div class="header"><a href="{{route('extracurricular.show', $inactive->id)}}">{{$inactive->name}}</a></div>
							<div class="description">Dibimbing oleh {{$inactive->user->name}}</div>
							<div class="description">Hari {{$d}} pukul {{date('H:i', strtotime($inactive->time))}} WIB</div>
						</div>
					</div>
					
					
					@endforeach
				</div>
				@endif
			</div>
		</div>
		
	</div>
	
</div>


{{-- modal edit --}}
<div id="modal-update" class="ui modal tiny">
	<div class="header">Ubah Data Ekstrakurikuler</div>
	<div class="ui basic segment">
	<form id="form-update" class="ui form error" method="POST" action="{{route('extracurricular.update')}}">
		@csrf
		<input type="hidden" name="upid" value="">
		<div class="field required @error('uname') error @enderror">
			<label>Nama Ekstrakurikuler</label>
			<input type="text" name="uname" value="{{old('uname')}}">
		</div>
		<div class="field">
			<label>Deskripsi</label>
			<textarea name="udescription" rows="3">{{old('udescription')}}</textarea>
		</div>
		<div class="field required @error('umentor') error @enderror">
			<label>Pembina / Mentor</label>
			<div class="ui fluid search selection dropdown mentor">
				<input type="hidden" name="umentor" value="{{old('umentor')}}">
				<div class="default text"></div>
				<i class="dropdown icon"></i>
			</div>
		</div>
		<div class="field required">
			<label>Hari</label>
			<select name="uday" id="day" class="ui dropdown dayselect">
				@foreach ($days as $key => $val)
				<option value="{{$key}}">{{$val}}</option>
				@endforeach
			</select>
		</div>
		<div class="field required @error('utime') errror @enderror">
			<label>Jam</label>
			<input type="text" name="utime" value="{{old('utime') ?? '15:30'}}">
		</div>
		
	</form>
</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<button onclick="document.getElementById('form-update').submit();" class="ui right labeled positive icon button">
			<i class="save icon"></i> Ubah
		</button>
	</div>
</div>

{{-- modal toggling --}}
<div id="modal-toggle" class="ui modal tiny">
	<div class="header ex-header"></div>
	<div class="ui basic segment">
		<div id="ex-message" class="ui message">
			<p>Anda yakin ingin <span class="ex-status"></span> ekstrakurikuler <span class="ex-name"></span>? <span class="ex-extra"></span></p>
		</div>
	</div>
	<form id="form-toggle" method="POST" action="{{route('extracurricular.toggle')}}">
		@csrf
		<input type="hidden" name="toggle_id" id="toggle-id" value="">
		<input type="hidden" name="toggle_cmd" id="toggle-cmd" value="">
	</form>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<button onclick="document.getElementById('form-toggle').submit();" id="ex-btn" class="ui right labeled icon button">
			<span class="ex-btntext"></span>
			<i class="check icon"></i>
		</button>
	</div>
</div>


@endsection

@section('pagescript')
<script>
	$(document).ready(function(){
		$('.mentor').dropdown({
			minCharacters: 3,
			apiSettings: {
				cache: true,
				url: '{{url("dashboard/search/staffs/{query}")}}',
			},
			fields: {
				remoteValues : 'results', 
				name         : 'name',  
				value        : 'value',
			},
			clearable: true,
		});
	});
	
	
	$(".toggling").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		var cmd = $(this).data('cmd');
		if(cmd == 'd') {
			$('.ex-header').html('Nonaktifkan Ekstrakurikuler');
			$('#ex-message').addClass('negative');
			$('.ex-status').html('menonaktifkan');
			$('.ex-name').html(name);
			$('.ex-extra').html('Semua anggota yang terdaftar akan ikut dinonaktifkan!');
			$('#toggle-id').val(id);
			$('#toggle-cmd').val('d');
			$('#ex-btn').addClass('negative');
			$('.ex-btntext').html('Nonaktifkan');
		} else {
			$('.ex-header').html('Aktifkan Ekstrakurikuler');
			$('#ex-message').addClass('positive');
			$('.ex-status').html('mengaktifkan');
			$('.ex-name').html(name);
			$('.ex-extra').html('Semua anggota yang terdaftar harus registrasi ulang!');
			$('#toggle-id').val(id);
			$('#toggle-cmd').val('a');
			$('#ex-btn').addClass('positive');
			$('.ex-btntext').html('Aktifkan');
		}
		$('#modal-toggle').modal('show');
	})
	
	$(".editing").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		var desc = $(this).data('desc');
		var day = $(this).data('day');
		var time = $(this).data('time');
		var mentor = $(this).data('mentor');
		var mentorname = $(this).data('mentorname');
		
		$('#form-update input[name=upid]').val(id);
		$('#form-update input[name=uname]').val(name);
		$('#form-update textarea[name=udescription]').html(desc);
		$('#form-update input[name=umentor]').val(mentor);
		$('#form-update .default.text').removeClass('default');
		$('#form-update .text').html(mentorname);
		$('#form-update .mentor i').addClass('clear');
		$('#form-update .mentor .menu').addClass('transition hidden');
		$('#form-update .mentor .menu').html('<div class="item active selected" data-value="'+ mentor +'">'+ mentorname +'</div>');
		$('#form-update select[name=uday]').val(day).find("option[value="+ day +"]").attr('selected', true);
		$('#form-update .dayselect').dropdown();
		$('#form-update input[name=utime]').val(time);
		$('#form-update').attr('action', '{{route("extracurricular.update")}}');

		$('#modal-update').modal('show');
	});
	
	function reloadForm()
	{ 
		window.location.href = "{{route('extracurricular.index')}}";
	}
</script>
@endsection