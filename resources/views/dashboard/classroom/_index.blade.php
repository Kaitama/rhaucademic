@extends('dashboard.template')
@section('pagetitle', 'Ruang Kelas')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable grid">
	
	<div class="six wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Tambah Gedung</h3>
			</div>
			<div class="ui segment">
				<form action="{{route('classroom.building.store')}}" method="post" class="ui form">
					@csrf
					<input type="hidden" name="building_group" value="classroom">
					<div class="required field @error('building_name') error @enderror">
						<label>Nama Gedung</label>
						<input type="text" name="building_name">
					</div>
					<button type="submit" class="ui button labeled icon green">
						<i class="ui plus icon"></i>
						Tambah
					</button>
				</form>
			</div>
		</div>
		
		
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Tambah Kelas</h3>
			</div>
			<div class="ui segment">
				<form action="{{route('classroom.store')}}" method="post" class="ui form">
					@csrf
					<div class="required field @error('building_id') error @enderror">
						<label>Nama Gedung</label>
						<select name="building_id" class="ui search dropdown">
							<option value="">Pilih gedung</option>
							@foreach ($buildings as $building)
							<option value="{{$building->id}}"{{old('building_id') == $building->id ? ' selected' : ''}}>{{$building->name}}</option>
							@endforeach
						</select>
					</div>
					<div class="required field @error('class_name') error @enderror">
						<label>Nama Kelas</label>
						<input type="text" name="class_name" value="{{old('class_name')}}">
					</div>
					<div class="required field @error('class_capacity') error @enderror">
						<label>Kapasitas Santri</label>
						<input type="text" name="class_capacity" value="{{old('class_capacity')}}">
					</div>
					<button type="submit" class="ui button labeled icon green">
						<i class="ui plus icon"></i>
						Tambah
					</button>
				</form>
			</div>
		</div>
	</div>
	
	<div class="ten wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Data Ruang Kelas</h3>
			</div>
			<div class="ui segment">
				@foreach ($buildings as $building)
				<h3 class="ui top attached segment">
					{{$building->name}}
					<div class="ui top right attached label">
						<a class="update-building" data-buildingid="{{$building->id}}" data-buildingname="{{$building->name}}"><i class="ui edit icon"></i> Edit</a>
						
						<a class="delete-building" data-buildingid="{{$building->id}}" data-buildingname="{{$building->name}}" style="margin-left:24px"><i class="ui trash icon"></i> Delete</a>
						
					</div>
				</h3>
				<div class="ui attached segment">
					
					<div class="ui middle aligned divided list">
						@if($building->classroom->count() == 0)
						<div class="meta">Tidak ada kelas terdaftar.</div>
						@else
						@foreach ($building->classroom->sortBy('name') as $classroom)
						
						<div class="item">
							<div class="right floated content">
								<div class="ui small basic icon buttons">
									<button class="ui button update-classroom" data-classroomid="{{$classroom->id}}" data-classroomname="{{$classroom->name}}" data-classroomcapacity="{{$classroom->capacity}}" data-buildingid="{{$building->id}}">
										<i class="edit icon"></i>
									</button>
									<button class="ui button delete-classroom" data-classroomid="{{$classroom->id}}" data-classroomname="{{$classroom->name}}">
										<i class="trash icon"></i>
									</button>
								</div>
							</div>
							
							<div class="content">
								<div class="header">{{$classroom->name}}</div>
								<p>Kapasitas {{$classroom->capacity}} santri.</p>
								
							</div>
						</div>
						@endforeach
						@endif
					</div>
					
				</div>
				@endforeach
			</div>
		</div>
	</div>
	
</div>

{{-- modal edit building --}}
<div id="modal-edit-building" class="ui modal tiny">
	<div class="header">
		Ubah Nama Gedung
	</div>
	<form class="actions ui form" method="POST" action="{{route('classroom.building.update')}}">
		@csrf
		<input type="hidden" name="id" id="building-id-update" value="">
		<div class="field required">
			<input type="text" name="name" id="building-name-update" value="">
		</div>
		<div class="ui black deny button">
			Batal
		</div>
		<button type="submit" class="ui positive right labeled icon button">
			Ubah
			<i class="save icon"></i>
		</button>
	</form>
</div>
{{-- modal edit classroom --}}
<div id="modal-edit-classroom" class="ui modal tiny">
	<div class="header">
		Ubah Data Kelas
	</div>
	<form class="ui form content" id="form-update-classroom" method="POST" action="{{route('classroom.update')}}">
		@csrf
		<input type="hidden" name="id" id="classroom-id-update" value="">
		<div class="required field">
			<label>Nama Gedung</label>
			<select id="select-building" name="building_id" class="ui search dropdown">
				<option value="">Pilih gedung</option>
				@foreach ($buildings as $building)
				<option value="{{$building->id}}">{{$building->name}}</option>
				@endforeach
			</select>
		</div>
		<div class="field required">
			<label>Nama Kelas</label>
			<input type="text" name="name" id="classroom-name-update" value="">
		</div>
		<div class="field required">
			<label>Kapasitas</label>
			<input type="text" name="capacity" id="classroom-capacity-update" value="">
		</div>
	</form>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<button onclick="event.preventDefault(); document.getElementById('form-update-classroom').submit();" class="ui positive right labeled icon button">
			Ubah
			<i class="save icon"></i>
		</button>
	</div>
</div>

@include('dashboard.components.modaldelete')

@endsection

@section('pagescript')
<script>
	
	$(".update-classroom").click(function(){
		var id = $(this).data('classroomid');
		var name = $(this).data('classroomname');
		var capacity = $(this).data('classroomcapacity');
		var buildingid = $(this).data('buildingid');
		$("#select-building").val(buildingid).find("option[value="+ buildingid +"]").attr('selected', true);
		$('.ui.dropdown').dropdown();
		$("#classroom-id-update").val(id);
		$("#classroom-name-update").val(name);
		$("#classroom-capacity-update").val(capacity);
		$("#modal-edit-classroom").modal('show');
	});
	$(".update-building").click(function(){
		var id = $(this).data('buildingid');
		var name = $(this).data('buildingname');
		$("#building-id-update").val(id);
		$("#building-name-update").val(name);
		$("#modal-edit-building").modal('show');
	});
	$(".delete-building").click(function(){
		var id = $(this).data('buildingid');
		var name = $(this).data('buildingname');
		$("#message").html("Menghapus data Gedung " + name + " berarti ikut menghapus semua data kelas yang terkait dengannya.");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('classroom.building.destroy')}}");
		$("#modal-delete").modal('show');
	});
	$(".delete-classroom").click(function(){
		var id = $(this).data('classroomid');
		var name = $(this).data('classroomname');
		$("#message").html("Menghapus data Kelas " + name + " memungkinkan sebagian santri tidak memiliki kelas.");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('classroom.destroy')}}");
		$("#modal-delete").modal('show');
	});
</script>
@endsection