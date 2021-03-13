@extends('dashboard.template')
@section('pagetitle', 'Prestasi')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui basic segment">
	<div class="ui small basic red icon buttons"> 
		@can('c prestasi')
		<div class="ui button imp dropdown">
			<div class="text">
				<i class="upload icon"></i> Import Excel
			</div>
			<div class="menu">
				<a href="{{asset('excels/TEMPLATE_PRESTASI.xlsx')}}" class="item"><i class="file excel icon"></i> Download Template</a>
				<div class="item" id="uploadexcel">
					<i class="cloud upload icon"></i>
					Upload Excel
				</div>
			</div>
		</div>
		@endcan
		
		@can('r prestasi')
		<div id="export-excel" class="ui button"><i class="download icon"></i> Export Excel</div>
		@endcan
	</div>
</div>

<div class="ui stackable two column grid">
	
	<div class="five wide column">
		@can('c prestasi')
		<div class="ui segments">
			<div class="ui green inverted segment">
				<h4 class="ui header">Tambah Prestasi Santri</h4>
			</div>
			<div class="ui segment">
				<form method="post" action="{{route('achievement.store')}}" id="frm-achievement" class="ui form error">
					@csrf
					<div class="field required @error('students') error @enderror">
						<label>Santri Berprestasi</label>
						<div class="ui fluid search multiple selection dropdown selectstudents">
							<input type="hidden" name="students" value="{{old('students')}}">
							<i class="search icon"></i>
							<div class="default text"></div>
						</div>
					</div>
					<div class="field required @error('name') error @enderror">
						<label>Nama Prestasi</label>
						<input type="text" name="name" value="{{old('name')}}">
					</div>
					<div class="two fields">
						<div class="field required @error('date') error @enderror">
							<label>Tanggal</label>
							<input type="text" name="date" value="{{old('date') ?? date('d/m/Y')}}">
						</div>
						<div class="field">
							<label>Peringkat</label>
							<input type="text" name="rank" value="{{old('rank')}}">
						</div>
					</div>
					<div class="field">
						<label>Hadiah / Apresiasi</label>
						<input type="text" name="reward" value="{{old('reward')}}">
					</div>
					<div class="field">
						<label>Keterangan</label>
						<textarea name="description" rows="2">{{old('description')}}</textarea>
					</div>
					<div class="field">
						<div class="ui grey sub header right aligned">Ditulis oleh {{Auth::user()->name}}</div>
					</div>
				</form>
			</div>
			<div class="ui segment">
				<div class="ui positive fluid labeled icon button" onclick="document.getElementById('frm-achievement').submit()">
					Simpan
					<i class="checkmark icon"></i>
				</div>
			</div>
		</div>
		@endcan
	</div>
	
	<div class="eleven wide column">
		<div class="ui segments">
			<div class="ui green inverted segment">
				<h4 class="ui header">Daftar Santri Berprestasi</h4>
			</div>
			@if($lists->isEmpty())
			<div class="ui segment">
				<div class="ui green message">
					<div class="header">Data kosong.</div>
					<p>Tidak ada santri dengan prestasi.</p>
				</div>
			</div>
			@else
			<div class="ui segment accordion">
				<div class="ui divided list">
					@foreach ($lists as $list)
					@php
					if($list->photo){ $mphoto = $list->photo; }
					else { if($list->gender == 'P') $mphoto = 'female.jpg'; else $mphoto = 'male.jpg'; }
					@endphp
					<div class="item title">
						<div class="right floated content">
							<div class="ui basic small icon button">
								<i class="ui chevron down icon"></i>
							</div>
						</div>
						<img class="ui avatar image" src="{{asset('assets/img/student/' . $mphoto)}}">
						<div class="content">
							<a href="{{route('student.profile', $list->stambuk)}}" class="header">{{$list->name}}</a>
							{{$list->achievement->count()}} prestasi
							
						</div>
					</div>
					<div class="content">
						<div class="ui segments">
							@foreach ($list->achievement->sortByDesc('date') as $ach)
							<div class="ui segment">
								<div class="ui sub header">
									{{date('d/m/Y', strtotime($ach->date))}} - {{$ach->name}}
								</div>
								<div class="description">{{$ach->description ?? '-'}}</div>
								<div class="description">
									<i class="trophy icon"></i> Ranking: {{$ach->rank ?? '-'}}
								</div>
								<div class="description">
									<i class="gift icon"></i> Hadiah: {{$ach->reward ?? '-'}}
								</div>
								<div class="description" style="font-size: 0.8em">
									<i>Ditulis oleh {{$ach->user['name']}}</i>
								</div>
								
								
								@can('u prestasi')
								<a 
								class="ui mini button btn-edit" 
								data-id="{{$ach->id}}" 
								data-stdname="{{$list->name}}" 
								data-stambuk="{{$list->stambuk}}" 
								data-photo="{{asset('assets/img/student/' . $mphoto)}}" 
								data-name="{{$ach->name}}" 
								data-date="{{date('d/m/Y', strtotime($ach->date))}}" 
								data-reward="{{$ach->reward}}" 
								data-description="{{$ach->description}}" 
								data-rank="{{$ach->rank}}"
								>Ubah</a> 
								@endcan
								
								@canany(['d prestasi', 'global delete'])
								<a 
								class="ui red mini button btn-delete" 
								data-id="{{$ach->id}}" 
								data-stdname="{{$list->name}}" 
								data-stambuk="{{$list->stambuk}}" 
								data-photo="{{asset('assets/img/student/' . $mphoto)}}" 
								data-name="{{$ach->name}}" 
								data-date="{{date('d/m/Y', strtotime($ach->date))}}" 
								data-reward="{{$ach->reward}}" 
								data-description="{{$ach->description}}" 
								data-rank="{{$ach->rank}}"
								>Hapus</a>
								@endcanany
								
							</div>
							@endforeach
						</div>
						
					</div>
					@endforeach
				</div>
				{{$lists->links()}}
			</div>
			@endif
		</div>
	</div>
</div>

@if($lists)

@can('c prestasi')
{{-- modal upload excel --}}
<div class="ui tiny modal" id="modal-upload">
	<div class="header">
		Upload File Excel Prestasi
	</div>
	<div class="content">
		<div class="description">Pastikan file Excel yang akan di upload sudah sesuai dengan template. Klik disini untuk download template: <a href="{{asset('excels/TEMPLATE_PRESTASI.xlsx')}}">Download Template</a></div>
		<div class="ui divider"></div>
		<form action="{{route('excel.data.achievement')}}" method="POST" id="form-upload-excel" class="ui form" enctype="multipart/form-data">
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

@can('r prestasi')
{{-- modal export excel --}}
<div id="mdl-export" class="ui tiny modal">
	<div class="header">
		Export Data Prestasi
	</div>
	<div class="content">
		<div class="description">
			<form action="{{route('excel.export.achievement')}}" method="post" class="ui form error" id="frm-export">
				@csrf
				<div class="two fields">
					<div class="field required">
						<label>Mulai Tanggal</label>
						<input type="text" name="startdate" value="{{\Carbon\Carbon::now()->firstOfMonth()->format('d/m/Y')}}">
					</div>
					<div class="field required">
						<label>Sampai Tanggal</label>
						<input type="text" name="enddate" value="{{\Carbon\Carbon::now()->format('d/m/Y')}}">
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
@endcan

@can('u prestasi')
{{-- modal edit achievement --}}
<div id="mdl-edit" class="ui tiny modal">
	<div class="header">
		Ubah Prestasi
	</div>
	
	<div class="content">
		<div class="description">
			<div class="ui container center aligned">
				<img id="e-photo" class="ui centered small circular image" src="">
				<span id="e-header" class="ui sub header"></span>
			</div>
			<div class="ui horizontal divider">&bull;</div>
			<form method="post" action="{{route('achievement.update')}}" id="frm-achievement-update" class="ui form">
				@csrf
				<input type="hidden" name="eid" value="">
				<div class="field required">
					<label>Prestasi</label>
					<input type="text" name="ename" value="">
				</div>
				<div class="two fields">
					<div class="field required">
						<label>Tanggal</label>
						<input type="text" name="edate" value="">
					</div>
					<div class="field">
						<label>Peringkat</label>
						<input type="text" name="erank" value="">
					</div>
				</div>
				<div class="field">
					<label>Hadiah / Apresiasi</label>
					<input type="text" name="ereward" value="">
				</div>
				<div class="field">
					<label>Keterangan</label>
					<textarea name="edescription" rows="2"></textarea>
				</div>
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui positive right labeled icon button" onclick="document.getElementById('frm-achievement-update').submit()">
			Simpan
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>
@endcan

@canany(['d prestasi', 'global delete'])
{{-- modal delete achievement --}}
<div id="mdl-delete" class="ui tiny modal">
	<div class="header">
		Hapus Prestasi
	</div>
	
	<div class="content">
		<div class="description">
			<div class="ui container center aligned">
				<img id="d-photo" class="ui centered small circular image" src="">
				<span id="d-header" class="ui sub header"></span>
			</div>
			<div class="ui horizontal divider">&bull;</div>
			<h5 class="ui red header">Anda yakin ingin menghapus prestasi berikut?</h5>
			<div class="ui segment">
				<div id="d-title" class="ui sub header"></div>
				<div id="d-description" class="meta"></div>
				<p>
					<span id="d-reward"></span>
					<span id="d-rank"></span>
				</p>
			</div>
			<form action="{{route('achievement.destroy')}}" method="post" id="frm-achievement-delete">
				@csrf <input type="hidden" name="id" value="">
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui negative right labeled icon button" onclick="document.getElementById('frm-achievement-delete').submit()">
			Hapus
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>
@endcanany

@endif


@endsection

@section('pagescript')
<script>
	// 
	
	//
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
	$("#export-excel").click(function(){
		$("#mdl-export").modal('show');
	});
</script>

@can('u prestasi')
<script>
	// edit achievement
	$('.btn-edit').click(function(){
		var stambuk = $(this).data('stambuk');
		var stdname = $(this).data('stdname');
		var photo = $(this).data('photo');
		var id = $(this).data('id');
		var name = $(this).data('name');
		var date = $(this).data('date');
		var rank = $(this).data('rank');
		var reward = $(this).data('reward');
		var desc = $(this).data('description');
		$('#mdl-edit #e-photo').attr('src', photo);
		$('#mdl-edit #e-header').html(stambuk + ' - ' + stdname);
		$('#mdl-edit input[name=eid]').val(id);
		$('#mdl-edit input[name=ename]').val(name);
		$('#mdl-edit input[name=edate]').val(date);
		$('#mdl-edit input[name=ereward]').val(reward);
		$('#mdl-edit input[name=erank]').val(rank);
		$('#mdl-edit textarea[name=edescription]').html(desc);
		$('#mdl-edit').modal('show');
	});
</script>
@endcan

@canany(['d prestasi', 'global delete'])
<script>
	// delete achievement
	$('.btn-delete').click(function(){
		var stambuk = $(this).data('stambuk');
		var stdname = $(this).data('stdname');
		var photo = $(this).data('photo');
		var id = $(this).data('id');
		var name = $(this).data('name');
		var date = $(this).data('date');
		var rank = $(this).data('rank');
		var reward = $(this).data('reward');
		var desc = $(this).data('description');
		$('#mdl-delete #d-photo').attr('src', photo);
		$('#mdl-delete #d-header').html(stambuk + ' - ' + stdname);
		$('#mdl-delete #d-title').html(date + ' - ' + name);
		$('#mdl-delete #d-reward').html('<div class="ui tiny blue label"><i class="gift icon"></i> '+ reward +'</div>');
		$('#mdl-delete #d-rank').html('<div class="ui tiny orange label"><i class="trophy icon"></i> Ranking '+ rank +'</div>');
		$('#mdl-delete #d-description').html(desc);
		$('#mdl-delete input[name=id]').val(id);
		$('#mdl-delete').modal('show');
	});
</script>
@endcanany

@can('c prestasi')
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
</script>
@endcan

@endsection