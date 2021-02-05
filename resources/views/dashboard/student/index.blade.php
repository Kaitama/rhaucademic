@extends('dashboard.template')
@section('pagetitle', 'Data Santri')

@section('content')

@include('dashboard.components.flashmessage')
<div class="ui segments">
	<div class="ui grey segment menu">
		<h3>Data Santri</h3>
	</div>
	
	<div class="ui segment">
		@can('m basdat')
		<div class="ui small basic icon buttons"> 
			<div class="ui button imp dropdown">
				<div class="text">
					<i class="upload icon"></i> Import Excel
				</div>
				<div class="menu">
					<a href="{{route('excel.template.student')}}" class="item"><i class="file excel icon"></i> Download Template</a>
					<div class="item" id="uploadexcel">
						<i class="cloud upload icon"></i>
						Upload Excel
					</div>
				</div>
			</div>
			
			<div id="exportstudents" class="ui button"><i class="download icon"></i> Export Excel</div>
			
			<a href="{{route('student.download.barcode')}}" class="ui button"><i class="file archive icon"></i> Download Barcode</a>
		</div>
		<a href="{{route('student.create')}}" class="ui labeled icon button green right floated"><i class="plus icon"></i> Tambah Santri</a>
		<div class="ui divider"></div>
		@endcan
		
		{{-- LIST SANTRI --}}
		<div class="ui two column stackable grid">
			<div class="column">
				<form action="{{route('student.filter')}}" method="get" id="form-filter" class="ui form">
					@csrf
					<div class="inline field">
						
						<select name="statfilter" id="selectstatus" class="ui dropdown">
							<option value="">Filter santri</option>
							<option value="1"{{app('request')->input('statfilter') == 1 ? ' selected' : ''}}>AKTIF</option>
							<option value="2"{{app('request')->input('statfilter') == 2 ? ' selected' : ''}}>ALUMNI</option>
							<option value="3"{{app('request')->input('statfilter') == 3 ? ' selected' : ''}}>SKORSING</option>
							<option value="4"{{app('request')->input('statfilter') == 4 ? ' selected' : ''}}>CUTI</option>
							<option value="5"{{app('request')->input('statfilter') == 5 ? ' selected' : ''}}>SAKIT</option>
							<option value="6"{{app('request')->input('statfilter') == 6 ? ' selected' : ''}}>AKADEMIK</option>
							<option value="7"{{app('request')->input('statfilter') == 7 ? ' selected' : ''}}>EKONOMI</option>
							<option value="8"{{app('request')->input('statfilter') == 8 ? ' selected' : ''}}>LAINNYA</option>
						</select>
					</div>
				</form>
			</div>
			@php
			switch (app('request')->input('statfilter')) {
				case 1: $ket = ' aktif '; break;
				case 2: $ket = ' alumni '; break;
				case 3: $ket = ' nonaktif karena skorsing '; break;
				case 4: $ket = ' nonaktif karena cuti '; break;
				case 5: $ket = ' nonaktif karena sakit '; break;
				case 6: $ket = ' nonaktif karena kendala akademik '; break;
				case 7: $ket = ' nonaktif karena kendala ekonomi '; break;
				case 8: $ket = ' nonaktif dengan alasan lainnya '; break;
				default: $ket = ''; break;
			}
			@endphp
			<div class="column right aligned">
				<form action="{{route('student.search')}}" method="get" id="form-search" class="ui form">
					@csrf
					<div class="ui large icon input">
						<input type="text" name="s" placeholder="Cari santri.." value="{{ app('request')->input('s') }}" data-content="Cari stambuk atau nama santri." data-position="left center" data-variation="inverted">
						<i class="inverted circular search link icon" id="btn-search"></i>
					</div>
				</form>
			</div>
		</div>
		<div class="ui divider"></div>
		<div class="ui positive message">Menampilkan {{$students->count()}} dari total keseluruhan {{$students->total()}} santri{{$ket}}.</div>
		<table class="ui celled table">
			<thead>
				<tr>
					<th class="collapsing">#</th>
					<th class="collapsing">Stambuk</th>
					<th>Nama Lengkap</th>
					<th>Asrama</th>
					<th>Kelas</th>
					<th>Status</th>
					<th class="collapsing">App. Wali</th>
					@can('m basdat')
					<th class="collapsing">Options</th>
					@endcan
				</tr>
			</thead>
			<tbody>
				@if($students->isEmpty())
				<tr>
					<td colspan="7" class="center aligned">Tidak ada santri ditemukan.</td>
				</tr>
				@endif
				@foreach ($students as $key => $student)
				<tr>
					<td>{{$key + $students->firstItem()}}</td>
					<td>
						<h4 class="ui header">
							<div class="content">{{$student->stambuk}}</div>
						</h4>
					</td>
					<td>
						@if ($student->photo)
						@php $photo = asset('assets/img/student/' . $student->photo) @endphp
						@else
						@if($student->gender == 'L')
						@php $photo = asset('assets/img/student/male.jpg') @endphp
						@else
						@php $photo = asset('assets/img/student/female.jpg') @endphp
						@endif
						@endif
						<h4 class="ui image header">
							<img src="{{$photo}}" class="ui mini rounded image">
							<div class="content">
								<a href="{{route('student.profile', $student->stambuk)}}">{{$student->name}}</a>
								<div class="sub header">
									{{$student->birthplace . ', ' . date('d-m-Y', strtotime($student->birthdate))}}
								</div>
							</div>
						</h4>
					</td>
					<td>
						@if ($student->dormroom)
						<a href="{{route('dormroom.show',$student->dormroom['id'])}}">{{$student->dormroom['name']}}</a>
						@else - @endif
					</td>
					<td>
						@if ($student->classroom)
						<a href="{{route('classroom.show',$student->classroom['id'])}}">{{$student->classroom['name']}}</a>
						@else - @endif
					</td>
					<td class="collapsing">
						@php $st = ['2' => 'Alumni', '3' => 'Skorsing', '4' => 'Cuti', '5' => 'Sakit', '6' => 'Akademik', '7' => 'Ekonomi', '8' => 'Lainnya'] @endphp
						@if ($student->status == 1)
						<div class="ui green tiny label"><i class="ui check icon"></i>Aktif</div>
						@else
						<div class="ui tiny label" @if($student->description) data-tooltip="{{$student->description}}" @endif><i class="ui times icon"></i>{{$st[$student->status]}}</div>
						@endif
					</td>
					<td>
						@if ($student->user)
						<div class="ui blue labeled icon mini button" onclick="mobileOptions({{$student->user->id}}, `{{$student->name}}`)">
							<i class="cog icon"></i>Android
						</div>
						@else
						<div class="ui labeled icon mini button disabled">
							<i class="times icon"></i>Empty
						</div>
						@endif
					</td>
					@can('m basdat')
					<td class="middle center aligned">
						<div class="ui opt dropdown labeled icon mini basic button">
							<i class="ui dropdown icon"></i>
							<span class="text">Options</span>
							<div class="menu">
								@if ($student->status == 1)
								<div class="item btn-deactivate" data-id="{{$student->id}}" data-name="{{$student->name}}">
									<i class="times icon"></i>
									Nonaktifkan
								</div>
								@else
								<div class="item btn-activate" data-id="{{$student->id}}" data-name="{{$student->name}}">
									<i class="check icon"></i>
									Aktifkan
								</div>
								@endif
								<div class="divider"></div>
								<a class="item btn-delete" data-id="{{$student->id}}" data-name="{{$student->name}}">
									<i class="trash icon"></i>
									Hapus
								</a>
							</div>
						</div>
					</td>
					@endcan
				</tr>
				@endforeach
			</tbody>
		</table>
		
		<div class="ui divider"></div>
		{{$students->appends(['_token' => app('request')->input('_token'), 'statfilter' => app('request')->input('statfilter')])->links()}}
	</div>
</div>


{{-- modal deactivate --}}
<div class="ui tiny modal" id="modal-deactivate">
	<div class="header">
		Nonaktifkan Santri
	</div>
	<div class="content">
		<div class="ui message red">Anda yakin ingin menonaktifkan status santri atas nama <span id="name-deactivate"></span>?</div>
		<form action="{{route('student.deactivate')}}" method="post" id="form-deactivate" class="ui form">
			@csrf
			<input type="hidden" name="idtodeactivate" value="">
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
					<input type="checkbox" name="permanent" tabindex="0" class="hidden">
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
{{-- modal activate --}}
<div class="ui tiny modal" id="modal-activate">
	<div class="header">
		Aktifkan Santri
	</div>
	<div class="content">
		<div class="ui message green">Anda yakin ingin mengaktifkan status santri atas nama <span id="name-activate"></span>? Kelas atau asrama harus didaftarkan secara manual.</div>
		<form action="{{route('student.activate')}}" method="post" style="display: none" id="form-activate">
			@csrf
			<input type="hidden" name="idtoactivate" value="">
		</form>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui positive right labeled icon button" onclick="document.getElementById('form-activate').submit()">
			Aktifkan
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>
{{-- modal export students --}}
<div class="ui tiny modal" id="modal-export">
	<div class="header">
		Pilih Kelas
	</div>
	<div class="content">
		
		<form id="form-export-students" action="{{route('excel.export.students')}}" method="post" class="ui form">
			@csrf
			<div class="field">
				<select name="c" class="ui search dropdown">
					<option value="">Pilih kelas</option>
					@foreach ($classrooms as $class)
					<option value="{{$class->id}}">{{$class->name}}</option>
					@endforeach
				</select>
			</div>
		</form>
		
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui positive right labeled icon button" onclick="document.getElementById('form-export-students').submit()">
			Export
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>

{{-- modal upload excel --}}
<div class="ui tiny modal" id="modal-upload">
	<div class="header">
		Upload File Excel Santri
	</div>
	<div class="content">
		<div class="description">Pastikan file Excel yang akan di upload sudah sesuai dengan template. Klik disini untuk download template: <a href="{{route('excel.template.student')}}" target="_blank">Download Template</a></div>
		<div class="ui divider"></div>
		<form action="{{route('excel.data.student')}}" method="POST" id="form-upload-excel" class="ui form" enctype="multipart/form-data">
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

{{-- modal mobile options --}}
<div class="ui tiny modal" id="modal-mobile">
	<div class="header">
		Pengaturan Akun Wali Santri
	</div>
	<div class="content">
		<div class="description">
			<p>Anda akan mengatur ulang akun wali santri <span id="mobile-student-name"></span>!</p>
			<form method="POST" action="{{route('user.mobiledestroy')}}" class="ui form">
				@csrf
				<input type="hidden" id="mobile-user-id" name="mobileuserid">
				<div class="field">
					<button type="submit" class="ui negative labeled icon button">
						<i class="trash icon"></i>Delete Account
					</button>
				</div>
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
	</div>
</div>

@include('dashboard.components.modaldelete')

@endsection

@section('pagescript')
<script>
	
	function mobileOptions(id, name) {
		$('#mobile-user-id').val(id);
		$('#mobile-student-name').html(name);
		$("#modal-mobile").modal('show');
	}

	$('#selectstatus').on('change', function(){
		$('#form-filter').submit();
	})
	
	$("#uploadexcel").click(function(){
		$("#modal-upload").modal('show');
	});
	$("#processupload").click(function(e){
		// e.preventDefault();
		$("#form-upload-excel").submit();
	});
	
	$('.btn-deactivate').click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		$('input[name=idtodeactivate]').val(id);
		$('#name-deactivate').html(name);
		$('#modal-deactivate').modal('show');
		$('.opt.dropdown').dropdown({action: 'select'});
	})
	
	$('.btn-activate').click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		$('input[name=idtoactivate]').val(id);
		$('#name-activate').html(name);
		$('#modal-activate').modal('show');
		$('.opt.dropdown').dropdown({action: 'select'});
	})
	
	
	$("input:text, #attach").click(function() {
		$(this).parent().find("input:file").click();
	});
	
	
	$('input:file', '.ui.action.input')
	.on('change', function(e) {
		var name = e.target.files[0].name;
		$('input:text', $(e.target).parent()).val(name);
	});
	
	$(".btn-delete").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		$("#message").html("Menghapus data " + name + " berarti ikut menghapus semua data yang terkait dengannya.");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('student.destroy')}}");
		$("#modal-delete").modal('show');
		$('.opt.dropdown').dropdown({action: 'select'});
	});
	
	// search input
	$("input[name=s]").popup();
	
	var sval = $("input[name=s]").val();
	if(sval){
		$("#btn-search").removeClass("search");
		$("#btn-search").addClass("times");
	}
	$("input[name=s]").keyup(function(){
		$("#btn-search").removeClass("times");
		$("#btn-search").addClass("search");
	});
	$("#btn-search").click(function(){
		if($("#btn-search").hasClass("times")){
			$("input[name=s]").removeAttr('value');
			window.location.href="{{route('student.index')}}";
			return false;
		}
		$("#form-search").submit();
	});
	
	$('#exportstudents').click(function(){
		$('#modal-export').modal('show');
	});
	
	$( document ).ready(function() {
		// console.log( "ready!" );
		$('.imp.dropdown').dropdown({action: 'select'});
	});
	
</script>
@endsection