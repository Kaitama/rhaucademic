@extends('dashboard.template')
@section('pagetitle', 'Ruang Asrama')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable grid">
	
	<div class="six wide column">
		
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
	</div>
	
	<div class="ten wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Data Ruang Asrama</h3>
			</div>
			<div class="ui segment">
				
				@foreach ($dormrooms as $dormroom)
				<div class="ui attached segment">
					<div class="ui middle aligned divided list">
						<div class="item">
							<div class="right floated content">
								<div class="ui small basic icon buttons">
									<button class="ui button update-dormroom" data-dormroomid="{{$dormroom->id}}" data-dormroomname="{{$dormroom->name}}" data-dormroomcapacity="{{$dormroom->capacity}}" data-dormroombuilding="{{$dormroom->building}}">
										<i class="edit icon"></i>
									</button>
									<button class="ui button delete-dormroom" data-dormroomid="{{$dormroom->id}}" data-dormroomname="{{$dormroom->name}}">
										<i class="trash icon"></i>
									</button>
								</div>
							</div>
							
							<div class="content">
								<div class="header">{{$dormroom->name}}</div>
								<p>
									@if($dormroom->building)
									Gedung {{$dormroom->building}}, &nbsp;
									@endif
									Kapasitas {{$dormroom->capacity}} santri.</p>
							</div>
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
	</div>
	
</div>

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

@include('dashboard.components.modaldelete')

@endsection

@section('pagescript')
<script>
	$(".update-dormroom").click(function(){
		var id = $(this).data('dormroomid');
		var name = $(this).data('dormroomname');
		var capacity = $(this).data('dormroomcapacity');
		var building = $(this).data('dormroombuilding');
		
		$('.ui.dropdown').dropdown();
		$("#dormroom-id-update").val(id);
		$("#dormroom-name-update").val(name);
		$("#dormroom-capacity-update").val(capacity);
		$("#dormroom-building-update").val(building);
		$("#modal-edit-dormroom").modal('show');
	});
	$(".delete-dormroom").click(function(){
		var id = $(this).data('dormroomid');
		var name = $(this).data('dormroomname');
		$("#message").html("Menghapus data Kelas " + name + " memungkinkan sebagian santri tidak memiliki kelas.");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('dormroom.destroy')}}");
		$("#modal-delete").modal('show');
	});
	
</script>
@endsection