@extends('dashboard.template')
@section('pagetitle', 'Ruang Asrama')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable grid">
	
	<div class="six wide column">
		@can('c asrama')
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Tambah Asrama</h3>
			</div>
			<div class="ui segment">
				<form action="{{route('dormroom.store')}}" method="post" class="ui form">
					@csrf
					<div class="field">
						<label>Nama Gedung</label>
						<input type="text" name="building">
					</div>
					<div class="required field @error('dorm_name') error @enderror">
						<label>Nama Asrama</label>
						<input type="text" name="dorm_name" value="{{old('dorm_name')}}">
					</div>
					<div class="required field @error('dorm_capacity') error @enderror">
						<label>Kapasitas Santri</label>
						<input type="text" name="dorm_capacity" value="{{old('dorm_capacity')}}">
					</div>
					<button type="submit" class="ui button labeled icon green">
						<i class="ui plus icon"></i>
						Tambah
					</button>
				</form>
			</div>
		</div>
		@endcan
	</div>
	
	<div class="ten wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Data Ruang Asrama</h3>
			</div>
			<div class="ui segment">
				
				@if($dormrooms->isEmpty())
				<div class="ui message">
					<div class="header">Data kosong.</div>
					<p>Belum ada asrama yang terdaftar.</p>
				</div>
				@endif
				
				@foreach ($dormrooms as $dormroom)
				<div class="ui attached segment">
					<div class="ui middle aligned divided list">
						<div class="item">
							<div class="right floated content">
								<div class="ui small basic icon buttons">
									@can('c asrama')
									<button class="ui button" data-tooltip="Tambah santri" data-inverted="" onclick="addStudents({{$dormroom->id}}, '{{$dormroom->name}}')">
										<i class="plus icon"></i>
									</button>
									@endcan
									@can('u asrama')
									<button class="ui button" onclick="editDormroom({{$dormroom->id}}, '{{$dormroom->name}}', {{$dormroom->capacity}}, '{{$dormroom->building}}')" data-tooltip="Ubah asrama" data-inverted="">
										<i class="edit icon"></i>
									</button>
									@endcan
									@canany(['d asrama', 'global delete'])
									<button class="ui button" onclick="deleteDormroom({{$dormroom->id}}, '{{$dormroom->name}}')" data-tooltip="Hapus asrama" data-inverted="">
										<i class="trash icon"></i>
									</button>
									@endcanany
								</div>
							</div>
							
							<div class="content">
								<a href="{{route('dormroom.show', $dormroom->id)}}" class="header">{{$dormroom->name}}</a>
								<p>
									@if($dormroom->building)
									Gedung {{$dormroom->building}}, &nbsp;
									@endif
									Jumlah santri {{$dormroom->student->count()}}/{{$dormroom->capacity}}.
								</p>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	
</div>

@can('u asrama')
<div id="modal-edit-dormroom" class="ui modal tiny">
	<div class="header">
		Ubah Data Asrama
	</div>
	<form class="ui form content" id="form-update-dormroom" method="POST" action="{{route('dormroom.update')}}">
		@csrf
		<input type="hidden" name="id" id="dormroom-id-update" value="">
		<div class="field">
			<label>Nama Gedung</label>
			<input type="text" name="building" id="dormroom-building-update">
		</div>
		<div class="field required">
			<label>Nama Asrama</label>
			<input type="text" name="name" id="dormroom-name-update" value="">
		</div>
		<div class="field required">
			<label>Kapasitas</label>
			<input type="text" name="capacity" id="dormroom-capacity-update" value="">
		</div>
	</form>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<button onclick="event.preventDefault(); document.getElementById('form-update-dormroom').submit();" class="ui positive right labeled icon button">
			Ubah
			<i class="save icon"></i>
		</button>
	</div>
</div>

{{-- modal addStudents --}}
<div id="modal-add-students" class="ui modal tiny">
	<div class="header">
		Tambah Santri Asrama <span id="namaasrama"></span>
	</div>
	<form class="ui form content" id="form-add-students" method="POST" action="{{route('dormroom.addstudents')}}">
		@csrf
		<input type="hidden" name="id" id="dormroom-id-add-students" value="">
		{{-- form field --}}
		<div class="field required @error('students') error @enderror">
			<label>Nama Santri</label>
			<div class="ui fluid search multiple selection dropdown selectstudents">
				<input type="hidden" name="students" value="{{old('students')}}">
				<i class="search icon"></i>
				<div class="default text"></div>
			</div>
		</div>
	</form>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<button onclick="document.getElementById('form-add-students').submit();" class="ui positive right labeled icon button">
			Simpan
			<i class="save icon"></i>
		</button>
	</div>
</div>
@endcan

@canany(['d asrama', 'global delete'])
@include('dashboard.components.modaldelete')
@endcanany

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

@can('u asrama')
<script>
	function addStudents(id, name)
	{
		$('#namaasrama').html(name);
		$('#dormroom-id-add-students').val(id);
		$('#modal-add-students').modal('show');
	}
	
	function editDormroom(id, name, capacity, building)
	{
		$('.ui.dropdown').dropdown();
		$("#dormroom-id-update").val(id);
		$("#dormroom-name-update").val(name);
		$("#dormroom-capacity-update").val(capacity);
		$("#dormroom-building-update").val(building);
		$("#modal-edit-dormroom").modal('show');
	}
</script>
@endcan

@canany(['d asrama', 'global delete'])
<script>
	function deleteDormroom(id, name)
	{
		$("#message").html("Menghapus data Kelas " + name + " memungkinkan sebagian santri tidak memiliki kelas.");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('dormroom.destroy')}}");
		$("#modal-delete").modal('show');
	}
</script>
@endcanany
@endsection