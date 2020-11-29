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

<form id="form-remove-student" action="{{route('classroom.removestudent')}}" method="post" style="display: none">
	@csrf
	<input type="hidden" name="idtoremove" value="">
</form>

@endsection

@section('pagescript')
<script>
	function removeStudent(id)
	{
		$('input[name=idtoremove]').val(id);
		$('#form-remove-student').submit();
	}
</script>
@endsection