@extends('dashboard.template')
@section('pagetitle', 'Detail Asrama')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui segments">
	<div class="ui black segment">
		<h4 class="ui header">Data Asrama {{$dormroom->name}}</h4>
	</div>
	<div class="ui segment">
		<div class="ui list">
			<div class="item">
				<div class="content">
					<div class="description">Gedung</div>
					<div class="header">{{$dormroom->building}}</div>
				</div>
			</div>
			<div class="item">
				<div class="content">
					<div class="description">Asrama</div>
					<div class="header">{{$dormroom->name}}</div>
				</div>
			</div>
			<div class="item">
				<div class="content">
					<div class="description">Jumlah santri</div>
					<div class="header">{{$dormroom->student->count()}} orang</div>
				</div>
			</div>
		</div>
		
		<div class="ui horizontal divider">&bull;</div>
		@if ($dormroom->student->count() == 0)
		<div class="ui message">Belum ada santri terdaftar di asrama ini.</div>
		@else
		<table class="ui black table">
			<thead>
				<tr>
					<th>#</th>
					<th>Stambuk</th>
					<th>Nama Santri</th>
					<th>Status</th>
					<th>Kelas</th>
					@can('m basdat')
					<th class="collapsing">Options</th>
					@endcan
				</tr>
			</thead>
			<tbody>
				@php $no = 1 @endphp
				@foreach($dormroom->student as $std)
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
						@if ($std->classroom)
						<a href="{{route('classroom.show', $std->classroom['id'])}}">{{$std->classroom['name']}}</a>
						@else - @endif
					</td>
					@can('m basdat')
					<td>
						<button class="ui icon small right floated negative button" onclick="removeStudent({{$std->id}}, '{{$std->name}}')" data-tooltip="Keluarkan dari asrama" data-inverted="">
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

<form id="form-remove-student" action="{{route('dormroom.removestudent')}}" method="post" style="display: none">
	@csrf
	<input type="hidden" name="idtoremove" value="">
</form>


{{--  --}}

{{-- edit data --}}
<div id="modal-edit-data" class="ui mini modal">
	<div class="header">
		Keluarkan dari asrama
	</div>
	<div class="content">
		<div class="description">
			<div class="ui message">Anda akan mengeluarkan <span id="std-name"></span> dari asrama {{$dormroom->name}}, lanjutkan?</div>
			<div class="ui divider"></div>
			<form action="{{route('dormroom.removestudent')}}" method="post" id="form-edit-students" class="ui form">
				@csrf
				<input type="hidden" id="std-id" name="idtoremove" value="">
				<div class="field">
					<div id="toggle-select" class="ui checkbox">
						<input type="checkbox" name="change" id="chk-change" tabindex="0" class="hidden">
						<label>Pindahkan ke asrama lain?</label>
					</div>
				</div>
				<div class="field">
					<select name="dorm" id="dorm-id" class="ui search dropdown disabled">
						<option value="">Ubah Asrama</option>
						@foreach ($dorms as $dorm)
						<option value="{{$dorm->id}}">{{$dorm->name}}</option>
						@endforeach
					</select>
				</div>
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui positive right labeled icon button" onclick="document.getElementById('form-edit-students').submit();">
			Proses
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>

@endsection

@section('pagescript')
<script>
	
	function removeStudent(id, name)
	{
		$('#std-id').val(id);
		$('#std-name').html(name);
		$('#modal-edit-data').modal('show');
		// $('#form-remove-student').submit();
	}
	$('#toggle-select').change(function(){
		if($('.ui.dropdown').hasClass('disabled')){
			$('.ui.dropdown').removeClass('disabled');
		}else{
			$('.ui.dropdown').addClass('disabled');
			$('.ui.dropdown').dropdown('restore defaults');
		}
	});
</script>
@endsection