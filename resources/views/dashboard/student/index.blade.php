@extends('dashboard.template')
@section('pagetitle', 'Data Santri')

@section('content')

@include('dashboard.components.flashmessage')
<div class="ui segments">
	<div class="ui grey segment menu">
		<h3>Data Santri</h3>
	</div>
	<div class="ui segment">
		<div class="ui small basic icon buttons"> 
			<div class="ui button" id="uploadexcel">
				<span class="text"><i class="upload icon"></i> Import Excel</span>
			</div>
			<a href="{{route('excel.template.student')}}" class="ui button"><i class="download icon"></i> Export Excel</a>
			<a href="{{route('student.download.barcode')}}" class="ui button"><i class="file archive icon"></i> Download Barcode</a>
		</div>
		<a href="{{route('student.create')}}" class="ui labeled icon button green right floated"><i class="plus icon"></i> Tambah Santri</a>
		{{-- LIST SANTRI --}}
		<div class="ui divider"></div>
		<div class="ui basic right aligned segment">
			<form action="{{route('student.search')}}" method="get" id="form-search">
				@csrf
				
				<div class="ui large icon input">
					<input type="text" name="s" placeholder="Cari santri.." value="{{ app('request')->input('s') }}" data-content="Cari stambuk atau nama santri." data-position="left center" data-variation="inverted">
					<i class="inverted circular search link icon" id="btn-search"></i>
				</div>

				
			</form>
		</div>
		<table class="ui celled table">
			<thead>
				<tr>
					<th>#</th>
					<th>Stambuk</th>
					<th>Nama Lengkap</th>
					<th>Asrama</th>
					<th>Kelas</th>
					<th>Status</th>
					<th>Options</th>
				</tr>
			</thead>
			<tbody>
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
						{{$student->dormroom['name'] ?? '-'}}
					</td>
					<td>{{$student->classroom['name'] ?? '-'}}</td>
					<td>
						@if ($student->status)
						<div class="ui green tiny tag label">Aktif</div>
						@endif
					</td>
					<td class="middle center aligned">
						<div class="ui opt dropdown labeled icon mini basic button">
							<i class="ui dropdown icon"></i>
							<span class="text">Options</span>
							<div class="menu">
								<a href="{{route('student.profile', $student->stambuk)}}" class="item">
									<i class="user icon"></i>
									Profile
								</a>
								<div class="divider"></div>
								<a class="item btn-delete" data-id="{{$student->id}}" data-name="{{$student->name}}">
									<i class="trash icon"></i>
									Delete
								</a>
							</div>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		
		<div class="ui divider"></div>
		{{$students->links()}}
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

@include('dashboard.components.modaldelete')

@endsection

@section('pagescript')
<script>
	
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


	

</script>
@endsection