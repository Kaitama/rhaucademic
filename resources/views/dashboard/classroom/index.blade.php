@extends('dashboard.template')
@section('pagetitle', 'Ruang Kelas')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable grid">
	
	<div class="six wide column">
		
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Tambah Kelas</h3>
			</div>
			<div class="ui segment">
				<form action="{{route('classroom.store')}}" method="post" class="ui form">
					@csrf
					<div class="required field @error('building_id') error @enderror">
						<label>Tingkat</label>
						<select name="level" class="ui dropdown">
							<option value="">Pilih tingkatan kelas</option>
							@for ($i = 1; $i <= 8; $i++)
							@if ($i < 7)
							<option value="{{$i}}">Tingkat {{$i}}</option>
							@endif
							@if ($i == 7)
							<option value="{{$i}}">Tingkat 1INT</option>
							@endif
							@if ($i == 8)
							<option value="{{$i}}">Tingkat 3INT</option>
									@endif
							@endfor
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
				@for ($i = 1; $i <= 8; $i++)
				<h3 class="ui top attached segment grey inverted">
					@if ($i <= 6) Tingkat {{$i}} @endif
					@if ($i == 7) Tingkat 1INT @endif
					@if ($i == 8) Tingkat 3INT @endif
				</h3>
				@foreach ($classrooms as $classroom)
				@if ($i == $classroom->level)
				<div class="ui attached segment">
					<div class="ui middle aligned divided list">
						<div class="item">
							<div class="right floated content">
								<div class="ui small basic icon buttons">
									<button class="ui button update-classroom" data-classroomid="{{$classroom->id}}" data-classroomname="{{$classroom->name}}" data-classroomcapacity="{{$classroom->capacity}}" data-classroomlevel="{{$classroom->level}}">
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
					</div>
				</div>
				@endif
				@endforeach
				@endfor
			</div>
		</div>
	</div>
	
</div>


<div id="modal-edit-classroom" class="ui modal tiny">
	<div class="header">
		Ubah Data Kelas
	</div>
	<form class="ui form content" id="form-update-classroom" method="POST" action="{{route('classroom.update')}}">
		@csrf
		<input type="hidden" name="id" id="classroom-id-update" value="">
		<div class="required field">
			<label>Tingkat</label>
			<select id="select-level" name="level" class="ui search dropdown">
				<option value="">Pilih tingkatan kelas</option>
				@for ($i = 1; $i < 7; $i++)
				<option value="{{$i}}">Tingkat {{$i}}</option>
				@endfor
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
		var level = $(this).data('classroomlevel');
		$("#select-level").val(level).find("option[value="+ level +"]").attr('selected', true);
		$('.ui.dropdown').dropdown();
		$("#classroom-id-update").val(id);
		$("#classroom-name-update").val(name);
		$("#classroom-capacity-update").val(capacity);
		$("#modal-edit-classroom").modal('show');
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