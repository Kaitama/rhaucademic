@extends('dashboard.template')
@section('pagetitle', 'Detail Organisasi')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable grid">
	
	<div class="five wide column">
		
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Tambah Anggota</h3>
			</div>
			<div class="ui segment">
				@if ($org->active)
						
				<form action="{{route('organization.addstudents')}}" method="post" id="form-add-students" class="ui form error">
					@csrf
					<input type="hidden" name="organization_id" value="{{$org->id}}">
					<div class="field required @error('students') error @enderror">
						<label>Nama Santri</label>
						<div class="ui fluid search multiple selection dropdown selectstudents">
							<input type="hidden" name="students" value="{{old('students')}}">
							<i class="search icon"></i>
							<div class="default text"></div>
						</div>
					</div>
					<div class="field required @error('position') error @enderror">
						<label>Jabatan</label>
						<select name="position" id="position" class="ui fluid dropdown">
							<option value="1">Ketua</option>
							<option value="2">Wakil Ketua</option>
							<option value="3">Sekretaris</option>
							<option value="4">Bendahara</option>
							<option value="5" selected>Anggota</option>
						</select>
					</div>
					<div class="field">
						<label>Keterangan</label>
						<textarea name="description" rows="3">{{old('description')}}</textarea>
					</div>
					<div class="field required @error('joindate') error @enderror">
						<label>Mulai Tanggal</label>
						<input type="text" name="joindate" value="{{old('joindate') ?? date('d/m/Y')}}">
					</div>
					<button type="submit" class="ui button labeled icon green">
						<i class="ui plus icon"></i>
						Tambah
					</button>
				</form>
				@else
						<div class="ui red message">
							Tidak dapat menambah anggota baru karena organisasi sudah tidak aktif.
						</div>
				@endif
			</div>
			
		</div>
	</div>
	
	<div class="eleven wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>{{$org->name}}</h3>
			</div>
			<div class="ui segment">
				<div class="ui list">
					<div class="item">
						<div class="content">
							<div class="description">Fokus Organisasi</div>
							<div class="header">{{$org->focus}}</div>
						</div>
					</div>
					<div class="item">
						<div class="content">
							<div class="description">Berdiri Tanggal</div>
							<div class="header">{{date('d/m/Y', strtotime($org->starting_at))}}</div>
						</div>
					</div>
					<div class="item">
						<div class="content">
							<div class="description">Deskripsi Organisasi</div>
							<div class="header">{{$org->description}}</div>
						</div>
					</div>
					<div class="item">
						<div class="content">
							<div class="description">Jumlah Anggota</div>
							<div class="header">{{$org->student->where('organization_student.isactive', true)->count()}} orang</div>
						</div>
					</div>
				</div>
				
				<div class="ui basic segment">
					<div class="ui horizontal divider">Daftar Anggota</div>
				</div>
				@if($org->student->where('organization_student.isactive', true)->count() > 0)
				<table class="ui celled table">
					<thead>
						<tr>
							<th>#</th>
							<th>Nama</th>
							<th>Kelas</th>
							<th>Jabatan</th>
							<th>Mulai</th>
							<th>Options</th>
						</tr>
					</thead>
					@php $no=1 @endphp
					<tbody>
						@foreach ($org->student->where('organization_student.isactive', true)->sortBy('organization_student.position') as $std)
						@php
						switch ($std->organization_student->position) {
							case 1: $pos = 'Ketua'; break;
							case 2: $pos = 'Wakil Ketua'; break;
							case 3: $pos = 'Sekretaris'; break;
							case 4: $pos = 'Bendahara'; break;
							default: $pos = 'Anggota'; break;
						}
						$joindate = date('d/m/Y', strtotime($std->organization_student->joindate));
						@endphp
						<tr>
							<td>{{$no++}}</td>
							<td><a href="{{route('student.profile', $std->stambuk)}}">{{$std->name}}</a></td>
							<td>
								@if ($std->classroom)
								<a href="{{route('classroom.show', $std->classroom['id'])}}">{{$std->classroom['name']}}</a>
								@else
										-
								@endif
							</td>
							<td>
								<div class="ui sub header">{{$pos}}</div>
								{{$std->organization_student->description ?? '-'}}
							</td>
							<td>{{$joindate}}</td>
							<td>
								<div class="ui icon tiny buttons">
									<button class="ui button edit-data" data-inverted="" data-tooltip="Ubah data" data-position="left center" onclick="editStudent({{$std->id}}, '{{$std->name}}', {{$std->organization_student->position}}, '{{$std->organization_student->description}}')"><i class="edit icon"></i></button>
								</div>
								<div class="ui icon tiny buttons negative right floated">
									{{-- buat modal konfirmasi --}}
									<button class="ui button toggle-isactive" data-id="{{$std->id}}" data-name="{{$std->name}}" data-inverted="" data-tooltip="Nonaktifkan anggota" data-position="left center"><i class="times icon"></i></button>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@else
				<div class="ui message">Tidak ada santri terdaftar pada organisasi ini.</div>
				@endif
				
			</div>
		</div>
	</div>
	
</div>



{{-- toggle isactive --}}
<div id="modal-toggle-isactive" class="ui tiny modal">
	<div class="header">
		Nonaktifkan Keanggotaan <span class="toggle-std-name"></span>
	</div>
	<div class="content">
		<div class="description">
			<div class="ui red message">
				<p>Anda yakin ingin menonaktifkan keanggotaan <span class="toggle-std-name"></span> dari organisasi {{$org->name}}?</p>
			</div>
			<form id="form-toggle" action="{{route('organization.toggleisactive', $org->id)}}" method="post" style="display:none">
				@csrf
				<input type="hidden" id="toggle-student-id" name="student_id" value="">
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui negative right labeled icon button" onclick="event.preventDefault(); document.getElementById('form-toggle').submit();">
			Nonaktifkan
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>

{{-- edit data --}}
<div id="modal-edit-data" class="ui modal">
	<div class="header">
		Ubah Keanggotaan <span id="std-name"></span>
	</div>
	<div class="content">
		<div class="description">
			<form action="{{route('organization.editstudents', $org->id)}}" method="post" id="form-edit-students" class="ui form">
				@csrf
				<input type="hidden" name="student_id" value="">
				
				<div class="field required">
					<label>Jabatan</label>
					<select name="position" id="position-edit" class="ui fluid dropdown">
						<option value="">Pilih Jabatan</option>
						<option value="1">Ketua</option>
						<option value="2">Wakil Ketua</option>
						<option value="3">Sekretaris</option>
						<option value="4">Bendahara</option>
						<option value="5">Anggota</option>
					</select>
				</div>
				<div class="field">
					<label>Keterangan</label>
					<textarea name="description" id="std-desc" rows="3"></textarea>
				</div>
				<div class="field">
					<label>Mulai Tanggal</label>
					<input type="text" name="joindate" value="{{date('d/m/Y')}}">
				</div>
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui positive right labeled icon button" onclick="event.preventDefault(); document.getElementById('form-edit-students').submit();">
			Tambahkan
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>


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
	
	$('.toggle-isactive').click(function(){
		var student_id = $(this).data('id');
		var student_name = $(this).data('name');
		$('#toggle-student-id').val(student_id);
		$('.toggle-std-name').html(student_name);
		$('#modal-toggle-isactive').modal('show');
		// $('#form-toggle').submit();
	})
	
	function editStudent(id, name, pos, desc){
		$('input[name=student_id]').val(id);
		$('#std-name').html(name);
		$("#position-edit").val(pos).find("option[value="+ pos +"]").attr('selected', true); $('.ui.dropdown').dropdown();
		$('#std-desc').html(desc);
		
		$('#modal-edit-data').modal('show');
	}
</script>
@endsection