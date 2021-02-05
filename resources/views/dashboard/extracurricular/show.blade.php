@extends('dashboard.template')
@section('pagetitle', 'Detail Ekstrakurikuler')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable grid">
	<div class="ui five wide column">
		@can('u ekskul')
		<div class="ui segments">
			<div class="ui grey segment">
				<h4 class="ui header">Tambah Anggota</h4>
			</div>
			<div class="ui segment">
				@if ($extra->active)
				
				<form action="{{route('extracurricular.addstudents')}}" method="post" id="form-add-students" class="ui form error">
					@csrf
					<input type="hidden" name="id" value="{{$extra->id}}">
					<div class="field required @error('students') error @enderror">
						<label>Nama Santri</label>
						<div class="ui fluid search multiple selection dropdown selectstudents">
							<input type="hidden" name="students" value="{{old('students')}}">
							<i class="search icon"></i>
							<div class="default text"></div>
						</div>
					</div>
					<div class="field required @error('joindate') error @enderror">
						<label>Mulai Tanggal</label>
						<input type="text" name="joindate" value="{{date('d/m/Y')}}">
					</div>
					<div class="field">
						<label>Status</label>
						<select name="active" class="ui dropdown">
							<option value="1">Aktif</option>
							<option value="0">Nonaktif</option>
						</select>
					</div>
					
				</form>
				@else
				<div class="ui red message">
					Tidak dapat menambah anggota baru karena kegiatan ekstrakurikuler sudah tidak aktif.
				</div>
				@endif
			</div>
			<div class="ui segment">
				<button class="ui button labeled icon green fluid" onclick="document.getElementById('form-add-students').submit()">
					<i class="ui plus icon"></i>
					Tambah
				</button>
			</div>
			
		</div>
		@endcan
	</div>
	
	@php
	switch ($extra->day) {
		case 1: $d = 'Senin'; break;
		case 2: $d = 'Selasa'; break;
		case 3: $d = 'Rabu'; break;
		case 4: $d = 'Kamis'; break;
		case 5: $d = 'Jum\'at'; break;
		case 6: $d = 'Sabtu'; break;
		default: $d = 'Minggu'; break;
	}
	@endphp
	
	<div class="ui eleven wide column">
		<div class="ui segments">
			<div class="ui grey segment">
				<h4 class="ui header">{{$extra->name}}</h4>
			</div>
			<div class="ui segment">
				<div class="ui list">
					<div class="item">
						<div class="content">
							<div class="description">Nama Pembina</div>
							<div class="header">{{$extra->user->name ?? '-'}}</div>
						</div>
					</div>
					<div class="item">
						<div class="content">
							<div class="description">Deskripsi</div>
							<div class="header">{{$extra->description ?? '-'}}</div>
						</div>
					</div>
					<div class="item">
						<div class="content">
							<div class="description">Jadwal Kegiatan</div>
							<div class="header">Hari {{$d}} pukul {{date('H:i', strtotime($extra->time))}} WIB</div>
						</div>
					</div>
					<div class="item">
						<div class="content">
							<div class="description">Jumlah Santri</div>
							<div class="header">{{$extra->student->where('extracurricular_student.isactive', true)->count()}} orang</div>
						</div>
					</div>
				</div>
				
				<div class="ui basic segment">
					<div class="ui horizontal divider">Daftar Anggota</div>
				</div>
				@if($extra->student->where('extracurricular_student.isactive', true)->count() > 0)
				<table class="ui celled table">
					<thead>
						<tr>
							<th>#</th>
							<th>Nama</th>
							<th>Kelas</th>
							<th>Bergabung</th>
							@can('u ekskul')
							<th>Options</th>
							@endcan
						</tr>
					</thead>
					@php $no=1 @endphp
					<tbody>
						@foreach ($extra->student->where('extracurricular_student.isactive', true)->sortByDesc('extracurricular_student.joindate') as $std)
						<tr>
							<td>{{$no++}}</td>
							<td><a href="{{route('student.profile', $std->stambuk)}}">{{$std->name}}</a></td>
							<td>
								@if ($std->classroom)
								<a href="{{route('classroom.show', $std->classroom->id)}}">{{$std->classroom['name']}}</a>
								@else
								{{'-'}}
								@endif
							</td>
							<td>{{date('d/m/Y', strtotime($std->extracurricular_student->joindate))}}</td>
							@can('u ekskul')
							<td>
								{{-- buat modal konfirmasi --}}
								<button class="ui tiny labeled icon button negative toggle-isactive" data-studentid="{{$std->id}}" data-name="{{$std->name}}"><i class="times icon"></i>Nonaktifkan</button>
							</td>
							@endcan
						</tr>
						@endforeach
					</tbody>
				</table>
				@else
				<div class="ui message">Tidak ada santri terdaftar pada ekstrakurikuler ini.</div>
				@endif
			</div>
		</div>
	</div>
</div>

@can('u ekskul')
{{-- toggle isactive --}}
<div id="modal-toggle-isactive" class="ui tiny modal">
	<div class="header">
		Nonaktifkan Anggota
	</div>
	<div class="content">
		<div class="description">
			<div class="ui red message">
				<p>Anda yakin ingin menonaktifkan keanggotaan <span class="toggle-std-name"></span> dari kegiatan ekstrakurikuler {{$extra->name}}?</p>
			</div>
			<form id="form-toggle" action="{{route('extracurricular.toggleisactive')}}" method="post" style="display:none">
				@csrf
				<input type="hidden" name="id" value="{{$extra->id}}">
				<input type="hidden" id="toggle-student-id" name="student_id" value="">
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui negative right labeled icon button" onclick="document.getElementById('form-toggle').submit();">
			Nonaktifkan
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>
@endcan

@endsection

@section('pagescript')
<script>
	$(document).ready(function(){
		$('.selectstudents').dropdown({
			minCharacters: 3,
			apiSettings: {
				cache: true,
				url: '{{url("dashboard/search/students/{query}")}}',
			},
			fields: {
				remoteValues : 'results', 
				name         : 'name',  
				value        : 'value'
			}
		});
	});
</script>
@can('u ekskul')
<script>
	$('.toggle-isactive').click(function(){
		var stdid = $(this).data('studentid');
		var name = $(this).data('name');
		$('.toggle-std-name').html(name);
		$('#toggle-student-id').val(stdid);
		$('#modal-toggle-isactive').modal('show');
	});
</script>
@endcan
@endsection