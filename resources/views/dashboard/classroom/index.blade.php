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

			{{-- <div class="ui segment">
				<div class="ui fluid basic buttons">
					<div class="ui button dropdown">
						<i class="upload icon"></i>
						Import Excel
						<div class="menu">
							<a href="{{asset('excels/TEMPLATE_KELAS.xlsx')}}" class="item">Download template</a>
							<div id="uploadexcel" class="item">Upload Excel</div>
						</div>
					</div>
					<a href="{{route('excel.export.classroom')}}" class="ui button">
						<i class="download icon"></i>
						Export Excel
					</a>
				</div>
			</div> --}}
		</div>
	</div>
	
	<div class="ten wide column">
		<div class="ui segments">
			<div class="ui yellow segment menu">
				<h3>Upload Massal</h3>
			</div>

			<div class="ui segment">
				<p>Anda dapat menentukan kelas santri secara massal dengan mengupload file Excel pada form ini.</p>
				<form action="{{route('excel.data.classroom')}}" method="POST" id="form-upload-excel" class="ui form error" enctype="multipart/form-data">
					@csrf
					<div class="field required">
						<label>File Excel</label>
						<div class="ui action input @error('excel') error @enderror">
							<input type="text" placeholder="Pilih file" readonly>
							<input type="file" name="excel">
							<div id="attach" class="ui icon button">
								<i class="attach icon"></i>
							</div>
						</div>
					</div>
					
						<button type="submit" class="ui green labeled icon right floated button">
							<i class="file excel icon"></i>
							Upload
						</button>
						<a href="{{route('excel.template.classroom')}}" class="ui basic button">Download Template</a>
				</form>
			</div>
			
		</div>

		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Data Ruang Kelas</h3>
			</div>
			<div class="ui segment right aligned">
				<a href="{{route('excel.export.classroom')}}" class="ui button labeled icon basic">
					<i class="download icon"></i>
					Export Excel
				</a>
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
									<button class="ui button" data-tooltip="Tambah santri" data-inverted="" onclick="addStudents({{$classroom->id}}, '{{$classroom->name}}')">
										<i class="plus icon"></i>
									</button>
									<button class="ui button" data-tooltip="Edit kelas" data-inverted="" onclick="editClassroom({{$classroom->id}}, '{{$classroom->name}}', {{$classroom->capacity}}, '{{$classroom->level}}')">
										<i class="edit icon"></i>
									</button>
									<button class="ui button" data-tooltip="Hapus kelas" data-inverted="" onclick="deleteClassroom({{$classroom->id}}, '{{$classroom->name}}')">
										<i class="trash icon"></i>
									</button>
								</div>
							</div>
							
							<div class="content">
								<a href="{{route('classroom.show', $classroom->id)}}" class="header">{{$classroom->name}}</a>
								<p>Jumlah santri {{$classroom->student->count()}}/{{$classroom->capacity}}.</p>
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

{{-- modal addStudents --}}
<div id="modal-add-students" class="ui modal tiny">
	<div class="header">
		Tambah Santri Kelas <span id="namakelas"></span>
	</div>
	<form class="ui form content" id="form-add-students" method="POST" action="{{route('classroom.addstudents')}}">
		@csrf
		<input type="hidden" name="id" id="classroom-id-add-students" value="">
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

{{-- modal upload excel --}}
<div class="ui tiny modal" id="modal-upload">
	<div class="header">
		Upload File Excel Kelas
	</div>
	<div class="content">
		<div class="description">Pastikan file Excel yang akan di upload sudah sesuai dengan template. Klik disini untuk download template: <a href="{{asset('excels/TEMPLATE_KELAS.xlsx')}}">Download Template</a></div>
		<div class="ui divider"></div>
		<form action="{{route('excel.data.classroom')}}" method="POST" id="form-upload-excel" class="ui form" enctype="multipart/form-data">
			@csrf
			<div class="field">
				<label>File Excel</label>
				<div class="ui action input">
					<input type="text" placeholder="Pilih file" readonly>
					<input type="file" name="excel">
					<div id="attach" class="ui icon button">
						<i class="attach icon"></i>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui positive right labeled icon button" id="processupload">
			Upload
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>


@include('dashboard.components.modaldelete')

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
	
	function editClassroom(id,name,capacity,level)
	{
		$("#select-level").val(level).find("option[value="+ level +"]").attr('selected', true);
		$('.ui.dropdown').dropdown();
		$("#classroom-id-update").val(id);
		$("#classroom-name-update").val(name);
		$("#classroom-capacity-update").val(capacity);
		$("#modal-edit-classroom").modal('show');
	}
	
	function deleteClassroom(id, name)
	{
		$("#message").html("Menghapus data Kelas " + name + " memungkinkan sebagian santri tidak memiliki kelas.");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('classroom.destroy')}}");
		$("#modal-delete").modal('show');
	}
	
	function addStudents(id, name)
	{
		$('#namakelas').html(name);
		$('#classroom-id-add-students').val(id);
		$('#modal-add-students').modal('show');
	}

	$("#uploadexcel").click(function(){
		$("#modal-upload").modal('show');
	});
	$("#processupload").click(function(e){
		// e.preventDefault();
		$("#form-upload-excel").submit();
	});
	$("input:text, #attach").click(function() {
		$(this).parent().find("input:file").click();
	});
	$('input:file', '.ui.action.input')
	.on('change', function(e) {
		var name = e.target.files[0].name;
		$('input:text', $(e.target).parent()).val(name);
	});
</script>
@endsection