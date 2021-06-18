@extends('dashboard.template')
@section('pagetitle', 'Detail Ruang Kelas')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui segments">
	<div class="ui black segment">
		<h4 class="ui header">Data Kelas {{$classroom->name}}</h4>
	</div>
	<div class="ui segment">
		<div class="ui list">
			<div class="item">
				<div class="content">
					<div class="description">Tingkat</div>
					<div class="header">{{$classroom->level}}</div>
				</div>
			</div>
			<div class="item">
				<div class="content">
					<div class="description">Kelas</div>
					<div class="header">{{$classroom->name}}</div>
				</div>
			</div>
			<div class="item">
				<div class="content">
					<div class="description">Jumlah santri</div>
					<div class="header">{{$classroom->student->count()}} orang</div>
				</div>
			</div>
		</div>
		
		<div class="ui horizontal divider">&bull;</div>
		
		@if ($classroom->student->count() == 0)
		<div class="ui message">Belum ada santri terdaftar di kelas ini.</div>
		@else
		
		@can('m basdat')
		<div id="btn-deactivate" class="ui labeled icon red button">
			<i class="ui exclamation icon"></i>
			NONAKTIFKAN SEMUA SANTRI
		</div>
		
		{{-- modal deactivate --}}
		<div class="ui tiny modal" id="modal-deactivate">
			<div class="header">
				Nonaktifkan Semua Santri
			</div>
			<div class="content">
				<div class="ui message red">Anda yakin ingin menonaktifkan semua santri di kelas ini?</div>
				<form action="{{route('classroom.deactivatestudents')}}" method="post" id="form-deactivate" class="ui form">
					@csrf
					<input type="hidden" name="classid" value="{{ $classroom->id }}">
					<div class="field">
						<label>Alasan</label>
						<select name="status" class="ui dropdown">
							<option value="2">ALUMNI</option>
							<option value="3">SKORSING</option>
							<option value="4">CUTI</option>
							<option value="5">SAKIT</option>
							<option value="6">AKADEMIK</option>
							<option value="7">EKONOMI</option>
							<option value="8">LAINNYA</option>
						</select>
					</div>
					<div class="field">
						<label>Keterangan</label>
						<textarea name="description" rows="3">{{old('description')}}</textarea>
					</div>
					<div class="field">
						<div class="ui checkbox">
							<input type="checkbox" name="permanent" tabindex="0" class="hidden" checked>
							<label>Nonaktif permanen</label>
						</div>
					</div>	
				</form>
				
			</div>
			<div class="actions">
				<div class="ui black deny button">
					Batal
				</div>
				<div class="ui negative right labeled icon button" onclick="document.getElementById('form-deactivate').submit()">
					Nonaktifkan
					<i class="checkmark icon"></i>
				</div>
			</div>
		</div>
		
		@endcan
		
		<table class="ui black table">
			<thead>
				<tr>
					<th>#</th>
					<th>Stambuk</th>
					<th>Nama Santri</th>
					<th>Status</th>
					<th>Asrama</th>
					@can('m basdat')
					<th>Options</th>
					@endcan
				</tr>
			</thead>
			<tbody>
				@php $no = 1 @endphp
				@foreach($classroom->student as $std)
				<tr>
					<td>{{$no++}}</td>
					<td>{{$std->stambuk}}</td>
					<td><a href="{{route('student.profile', $std->stambuk)}}">{{$std->name}}</a></td>
					<td>
						@if ($std->status == 1)
						<div class="ui green tiny label"><i class="ui check icon"></i>Aktif</div>
						@else
						<div class="ui red tiny label"><i class="ui times icon"></i>Nonaktif</div>
						@endif
					</td>
					<td>
						@if ($std->dormroom)
						<a href="{{route('dormroom.show', $std->dormroom['id'])}}">{{$std->dormroom['name']}}</a>
						@else - @endif
					</td>
					@can('m basdat')
					<td>
						<button class="ui icon mini negative button" onclick="removeStudent({{$std->id}})">
							<i class="ui minus icon"></i>
						</button>
					</td>
					@endcan
				</tr>
				@endforeach
			</tbody>
		</table>
		
		@endif
		
	</div>
</div>

@can('m basdat')
<form id="form-remove-student" action="{{route('classroom.removestudent')}}" method="post" style="display: none">
	@csrf
	<input type="hidden" name="idtoremove" value="">
</form>
@endcan

@endsection

@section('pagescript')
@can('m basdat')
<script>
	function removeStudent(id)
	{
		$('input[name=idtoremove]').val(id);
		$('#form-remove-student').submit();
	}
	$('#btn-deactivate').click(function(){
		$('#modal-deactivate').modal('show');
		$('.opt.dropdown').dropdown({action: 'select'});
	})
</script>
@endcan
@endsection