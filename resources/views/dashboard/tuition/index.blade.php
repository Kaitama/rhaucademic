@extends('dashboard.template')
@section('pagetitle', 'Uang Sekolah')

@section('content')

@include('dashboard.components.flashmessage')

@php
$months = ['1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April', '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus', '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
@endphp

<div class="ui segments">
	<div class="ui grey segment menu">
		<h3>Uang Sekolah</h3>
	</div>
	<div class="ui segment">
		<div class="ui small basic icon buttons"> 
			<div class="ui button dropdown" id="uploadexcel">
				<div class="text">
					<i class="upload icon"></i> Import Excel
				</div>
				<div class="menu">
					<a href="{{route('excel.template.tuition')}}" class="item">
						<i class="file excel icon"></i>
						Download Template
					</a>
					@can('c keuangan')
					<div id="upload-excel" class="item">
						<i class="cloud upload icon"></i>
						Upload Excel
					</div>
					@endcan
				</div>
				
			</div>
			<div id="export-excel" class="ui button"><i class="download icon"></i> Export Excel</div>
		</div>
		@can('c keuangan')
		<div id="btn-create" class="ui labeled icon button green right floated"><i class="plus icon"></i> Buat Pembayaran</div>
		@endcan
		{{-- LIST PEMBAYARAN --}}
		<div class="ui divider"></div>
		<div class="ui stackable two column grid">
			
			<div class="column">
				<div class="ui basic segment">
					<div class="ui search">
						<div class="ui left icon input">
							<input class="prompt" type="text" placeholder="Cari santri">
							<i class="search icon"></i>
						</div>
					</div>
				</div>
			</div>

			<div class="column">
				<div class="ui basic segment">
					{{-- form filter --}}
					<form action="" method="get" id="frm-filter" class="ui form">
						@csrf
						<div class="inline fields">
							<div class="field">
								<select name="month" class="ui dropdown">
									<option value="">Pilih bulan</option>
									@foreach ($months as $key => $val)
									<option value="{{$key}}"{{app('request')->input('month') == $key ? ' selected' : ''}}>{{$val}}</option>
									@endforeach
								</select>
							</div>
							<div class="field">
								<input type="text" name="year" value="{{app('request')->input('year')}}" placeholder="Tahun">
							</div>
							<div class="field">
								<button type="submit" id="btn-filter" class="ui icon button grey"><i class="ui search icon"></i></button>
							</div>
							@if($tuitions)
							<div class="field">
								<a href="{{route('tuition.index')}}">Clear</a>
							</div>
							@endif
						</div>
					</form>
				</div>
			</div>

		</div>
		@if ($tuitions == null)
		<div class="ui icon message">
			<i class="exclamation circle icon"></i>
			<div class="content">
				<div class="header">
					Lakukan filter!
				</div>
				<p>Mohon memilih untuk menampilkan data pembayaran berdasarkan bulan dan/atau tahun.</p>
			</div>
		</div>
		@else
		<table class="ui celled table">
			<thead>
				<tr>
					<th>#</th>
					<th>Tanggal Bayar</th>
					<th>Stambuk</th>
					<th>Nama Santri</th>
					<th>Kelas</th>
					<th>Pembayaran</th>
					<th>Nominal</th>
					@can('u keuangan', 'd keuangan')
					<th>Opsi</th>
					@endcan
				</tr>
			</thead>
			<tbody>
				@foreach ($tuitions as $key => $tuition)
				<tr>
					<td>{{$key + $tuitions->firstItem()}}</td>
					<td>{{date('d/m/Y', strtotime($tuition->paydate))}}</td>
					<td>{{$tuition->student['stambuk'] ?? '-'}}</td>
					@php
					if($tuition->student['photo']){ $mphoto = $tuition->student['photo']; }
					else { if($tuition->student['gender'] == 'P') $mphoto = 'female.jpg'; else $mphoto = 'male.jpg'; }
					@endphp
					<td>
						@if($tuition->student)
						<img src="{{asset('assets/img/student/' . $mphoto)}}" class="ui avatar image">
						<a href="{{route('student.profile', $tuition->student['stambuk'])}}">{{$tuition->student['name']}}</a>
						@else
						<i>Santri telah dihapus</i>
						@endif
					</td>
					<td>
						@if($tuition->student['classroom_id'])
						<a href="{{route('classroom.show', $tuition->student['classroom_id'])}}">
						{{$tuition->student->classroom['name']}}
					</a>
						@else
						{{'-'}}
						@endif
					</td>
					<td>{{$tuition->formonth . '/' . $tuition->foryear}}</td>
					<td>Rp. {{number_format($tuition->nominal, 0, ',', '.')}}</td>
					@can('u keuangan', 'd keuangan')
					<td>
						<div class="ui icon yellow mini button btn-edit" data-id="{{$tuition->id}}" data-month="{{$tuition->formonth}}" data-foryear="{{$tuition->foryear}}" data-stambuk="{{$tuition->student['stambuk']}}" data-name="{{$tuition->student['name']}}" data-nominal="{{$tuition->nominal}}" data-paydate="{{date('d/m/Y', strtotime($tuition->paydate))}}"><i class="ui edit icon"></i></div>
						<div class="ui icon negative mini button btn-delete" data-id="{{$tuition->id}}" data-formonth="{{$tuition->formonth}}" data-foryear="{{$tuition->foryear}}" data-name="{{$tuition->student['name']}}"><i class="ui trash icon"></i></div>
					</td>
					@endcan
				</tr>
				@endforeach
			</tbody>
		</table>		
		<div class="ui divider"></div>
		{{$tuitions->links()}}
		@endif
		
	</div>
</div>

{{-- modal export excel --}}
<div id="mdl-export" class="ui tiny modal">
	<div class="header">
		Export Data Pembayaran Uang Sekolah
	</div>
	<div class="content">
		<div class="description">
			<form action="{{route('excel.export.tuition')}}" method="post" class="ui form error" id="frm-export">
				@csrf
				<div class="two fields">
					<div class="field required">
						<label>Pilih Bulan</label>
						<select name="formonth" class="ui dropdown">
							<option value="all" selected>Semua bulan</option>
							@foreach ($months as $key => $month)
							<option value="{{$key}}">{{$month}}</option>
							@endforeach
						</select>
					</div>
					<div class="field required">
						<label>Tahun</label>
						<input type="text" name="foryear" value="{{date('Y')}}">
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui positive right labeled icon button" onclick="document.getElementById('frm-export').submit()">
			Export
			<i class="download icon"></i>
		</div>
	</div>
</div>

@can('c keuangan')
{{-- modal create --}}
<div id="mdl-create" class="ui tiny modal">
	<div class="header">
		Buat Pembayaran Uang Sekolah
	</div>
	<div class="content">
		<div class="description">
			<form action="{{route('tuition.store')}}" id="frm-create" class="ui form error" method="post">
				@csrf
				<div class="field required @error('paydate') error @enderror">
					<label>Tanggal Bayar</label>
					<input type="text" name="paydate" value="{{old('paydate') ?? date('d/m/Y')}}">
				</div>
				<div class="field required @error('student_id') error @enderror">
					<label>Santri</label>
					<div class="ui fluid search multiple selection dropdown selectstudents">
						<input type="hidden" name="student_id" value="{{old('student_id')}}">
						<i class="search icon"></i>
						<div class="default text"></div>
					</div>
				</div>
				<div class="two fields">
					<div class="ten wide field required @error('formonth') error @enderror">
						<label>Pembayaran Bulan</label>
						<select name="formonth" class="ui dropdown">
							<option value="">Pilih bulan</option>
							@foreach ($months as $key => $month)
							<option value="{{$key}}"{{old('formonth') == $key ? ' selected' : ''}}>{{$month}}</option>
							@endforeach
						</select>
					</div>
					<div class="six wide field required @error('foryear') error @enderror">
						<label>Tahun</label>
						<input type="text" name="foryear" value="{{old('foryear') ?? date('Y')}}">
					</div>
				</div>
				<div class="field required @error('nominal') error @enderror">
					<label>Nominal</label>
					<input type="text" name="nominal" value="{{old('nominal')}}">
				</div>
			</form>
			
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div id="btn-submit-create" class="ui positive right labeled icon button">
			Simpan
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>
{{-- modal upload excel --}}
<div class="ui tiny modal" id="mdl-upload-excel">
	<div class="header">
		Upload File Excel Uang Sekolah
	</div>
	<div class="content">
		<div class="description">Pastikan file Excel yang akan di upload sudah sesuai dengan template. Klik disini untuk download template: <a href="{{route('excel.template.tuition')}}" target="_blank">Download Template</a></div>
		<div class="ui divider"></div>
		<form action="{{route('excel.data.tuition')}}" method="POST" id="form-upload-excel" class="ui form" enctype="multipart/form-data">
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
@endcan

@can('u keuangan')
{{-- modal edit --}}
<div id="mdl-edit" class="ui tiny modal">
	<div class="header">
		Ubah Pembayaran Uang Sekolah
	</div>
	<div class="content">
		<div class="ui message">
			<div class="content">
				<p>Data pembayaran <span id="student-name"></span> - <span id="student-stambuk"></span></p>
			</div>
		</div>
		<div class="description">
			<form action="{{route('tuition.update')}}" id="frm-update" class="ui form error" method="post">
				@csrf
				<input type="hidden" name="id">
				<div class="field required @error('paydate') error @enderror">
					<label>Tanggal Bayar</label>
					<input type="text" name="paydate" value="{{old('paydate')}}">
				</div>
				<div class="two fields">
					<div class="ten wide field required @error('formonth') error @enderror">
						<label>Pembayaran Bulan</label>
						<select name="formonth" class="ui dropdown">
							<option value="">Pilih bulan</option>
							@foreach ($months as $key => $month)
							<option value="{{$key}}"{{old('formonth') == $key ? ' selected' : ''}}>{{$month}}</option>
							@endforeach
						</select>
					</div>
					<div class="six wide field required @error('foryear') error @enderror">
						<label>Tahun</label>
						<input type="text" name="foryear" value="{{old('foryear')}}">
					</div>
				</div>
				<div class="field required @error('nominal') error @enderror">
					<label>Nominal</label>
					<input type="text" name="nominal" value="{{old('nominal')}}">
				</div>
			</form>
			
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div id="btn-submit-update" class="ui positive right labeled icon button">
			Ubah
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>
@endcan

@can('d keuangan')
@include('dashboard.components.modaldelete')
@endcan
@endsection

@section('pagescript')
<script>
	$(document).ready(function(){
		$('.ui.search').search({
			minCharacters: 3,
			apiSettings: {
				cache: true,
				url: '{{url("dashboard/search/students/{query}")}}',
			},
			fields: {
				results : 'results', 
				title		: 'name',  
				url		 	: 'url'
			},
			
		});

		$('.selectstudents').dropdown({
			minCharacters: 3,
			apiSettings: {
				cache: false,
				url: '{{url("dashboard/search/students/{query}")}}',
			},
			fields: {
				remoteValues : 'results', 
				name         : 'name',  
				value        : 'value',
				text				 : 'stambuk',
			}
		});
	});
	
	$("#export-excel").click(function(){
		$("#mdl-export").modal('show');
	})
	$("#btn-create").click(function(){
		$("#mdl-create").modal('show');
	});
	$("#btn-submit-create").click(function(){
		$("#frm-create").submit();
	});
	$("#btn-submit-update").click(function(){
		$("#frm-update").submit();
	});
	$(".btn-delete").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		var month = $(this).data('month');
		var year = $(this).data('year');
		$("#message").html("Anda akan menghapus data pembayaran uang sekolah pada bulan " + month + " tahun " + year + " atas nama " + name + " ?");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('tuition.destroy')}}");
		$("#modal-delete").modal('show');
	});
	$(".btn-edit").click(function(){
		var id = $(this).data('id');
		var stambuk = $(this).data('stambuk');
		var name = $(this).data('name');
		var paydate = $(this).data('paydate');
		var formonth = $(this).data('formonth');
		var foryear = $(this).data('foryear');
		var nominal = $(this).data('nominal');
		$("input[name=stambuk]").val(stambuk);
		$("input[name=name]").val(name);
		$("input[name=paydate]").val(paydate);
		$("input[name=foryear]").val(foryear);
		$("input[name=nominal]").val(nominal);
		$("input[name=id]").val(id);
		$("#student-name").html(name);
		$("#student-stambuk").html(stambuk);
		$("#mdl-edit").modal('show');
	});
	
	$("#upload-excel").click(function(){
		$("#mdl-upload-excel").modal('show');
	});
	$("#processupload").click(function(e){
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