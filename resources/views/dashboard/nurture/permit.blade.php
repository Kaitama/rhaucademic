@extends('dashboard.template')
@section('pagetitle', 'Perizinan')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable two column grid">
	
	<div class="five wide column">
		@can('c pengasuhan')
		<div class="ui segments">
			<div class="ui violet inverted segment">
				<h4 class="ui header">Buat Surat Izin</h4>
			</div>
			<div class="ui segment">
				<form action="{{route('permit.store')}}" method="post" id="frm-add" class="ui form error">
					@csrf
					<div class="field required @error('signdate') error @enderror">
						<label>Dikeluarkan Tanggal</label>
						<input type="text" name="signdate" value="{{old('signdate') ?? date('d/m/Y')}}">
					</div>
					<div class="field required @error('student') error @enderror">
						<label>Nama Santri</label>
						<div class="ui fluid search selection dropdown selectstudent">
							<input type="hidden" name="student" value="{{old('student')}}">
							<i class="search icon"></i>
							<div class="default text"></div>
						</div>
					</div>
					<div class="field required @error('description') error @enderror">
						<label>Keperluan Izin</label>
						<input type="text" name="description" value="{{old('description')}}">
					</div>
					<div class="field required @error('datefrom') error @enderror">
						<label>Mulai Tanggal</label>
						<input type="text" name="datefrom" value="{{old('datefrom') ?? date('d/m/Y')}}">
					</div>
					<div class="field required @error('dateto') error @enderror">
						<label>Sampai Tanggal</label>
						<input type="text" name="dateto" value="{{old('dateto') ?? date('d/m/Y',strtotime('tomorrow'))}}">
					</div>
					<div class="field">
						<label>Jam</label>
						<input type="text" name="timeto" value="{{old('timeto') ?? '23:59'}}">
					</div>
					<div class="field">
						<div class="ui grey sub header right aligned">Dikeluarkan oleh {{Auth::user()->name}}</div>
					</div>
				</form>
			</div>
			<div class="ui segment">
				<div class="ui fluid violet labeled icon button" onclick="document.getElementById('frm-add').submit()">
					<i class="ui save icon"></i>Simpan
				</div>
			</div>
		</div>
		@endcan
	</div>
	
	<div class="eleven wide column">
		<div class="ui segments">
			<div class="ui violet inverted segment">
				<h4 class="ui header">Daftar Surat Izin Santri</h4>
			</div>
			<div class="ui segment">
				@if ($permits->isEmpty())
				<div class="ui message violet">
					<div class="header">Data kosong.</div>
					<p>Tidak ada santri yang izin keluar kampus.</p>
				</div>
				@else
				{{-- list permit --}}
				<div class="ui segment accordion">
					<div class="ui divided list">
						@foreach ($permits as $permit)
						@php
						if($permit->student['photo']){ $mphoto = $permit->student['photo']; }
						else { if($permit->student['gender'] == 'P') $mphoto = 'female.jpg'; else $mphoto = 'male.jpg'; }
						if(date('Y-m-d H:i:s') <= $permit->dateto) $active = true; else $active = false;
						@endphp
						<div class="item">
							<div class="content right floated">
								<a href="{{route('permit.show', $permit->id)}}" target="_blank" class="ui basic tiny icon button">
									<i class="ui print icon"></i>
								</a>
								@can('d pengasuhan')
								<a class="ui red icon tiny button delete-permit" data-id="{{$permit->id}}" data-photo="{{asset('assets/img/student/' . $mphoto)}}" data-stbk="{{$permit->student['stambuk']}}" data-name="{{$permit->student['name']}}" data-title="{{$permit->description}}" data-active="{{$active}}" data-from="{{$permit->user['name']}}" data-date="{{date('d/m/Y', strtotime($permit->signdate))}}">
									<i class="ui trash icon"></i>
								</a>
								@endcan
							</div>
							<img class="ui avatar image" src="{{asset('assets/img/student/' . $mphoto)}}">
							<div class="content">
								<a href="{{route('student.profile', $permit->student['stambuk'])}}" class="header">{{$permit->student['stambuk']}} - {{$permit->student['name']}}</a>
								<div class="description">
									@if ($active)
									<span class="ui mini green right label">Active</span>
									@else
									<span class="ui mini red right label">Expired</span>
									@endif
									<b>{{$permit->description}}</b> <br> Dari tanggal {{date('d/m/Y', strtotime($permit->datefrom))}} sampai tanggal {{date('d/m/Y', strtotime($permit->dateto))}} pukul {{date('H:i', strtotime($permit->dateto))}} WIB.
								</div>
								<div class="description" style="font-size: 0.8em">
									<i>Dikeluarkan oleh <b>{{$permit->user['name']}}</b> pada tanggal {{date('d/m/Y', strtotime($permit->signdate))}}.</i>
								</div>
							</div>
						</div>
						
						@endforeach
					</div>
					{{$permits->links()}}
				</div>
				
				@endif
			</div>
		</div>
	</div>
	
</div>


@if ($permits)
@can('d pengasuhan')
{{-- modal delete permit --}}
<div id="mdl-delete" class="ui tiny modal">
	<div class="header">
		Hapus Surat Izin
	</div>
	
	<div class="content">
		<div class="description">
			<div class="ui container center aligned">
				<img id="d-photo" class="ui centered small circular image" src="">
				<span id="d-header" class="ui sub header"></span>
			</div>
			<div class="ui horizontal divider">&bull;</div>
			<div class="ui list">
				<div class="item">
					<div class="content">
						<div id="d-title" class="header"></div>
						<div id="d-desc" class="description"></div>
					</div>
				</div>
			</div>
			<div class="ui red message">
				<p id="d-msg"></p>
			</div>
			<form action="{{route('permit.destroy')}}" method="post" id="frm-permit-delete">
				@csrf <input type="hidden" name="id" value="">
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui negative right labeled icon button" onclick="document.getElementById('frm-permit-delete').submit()">
			Hapus
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>
@endcan
@endif
@endsection

@section('pagescript')
<script>
	// 
	$(document).ready(function(){
		$('.selectstudent').dropdown({
			minCharacters: 3,
			apiSettings: {
				cache: true,
				url: '{{url("api/students/list/{query}")}}',
			},
			fields: {
				remoteValues : 'results', 
				name         : 'name',  
				value        : 'value'
			}
		});
	});
	
	// delete permit
	$('.delete-permit').click(function(){
		var id = $(this).data('id');
		var stbk = $(this).data('stbk');
		var photo = $(this).data('photo');
		var name = $(this).data('name');
		var title = $(this).data('title');
		var active = $(this).data('active');
		var date = $(this).data('date');
		var from = $(this).data('from');
		$('#mdl-delete #d-photo').attr('src', photo);
		$('#mdl-delete #d-header').html(stbk + ' - ' + name);
		$('#mdl-delete #d-title').html(title)
		$('#mdl-delete #d-desc').html('Dikeluarkan tanggal ' + date + ' oleh ' + from);
		$('#mdl-delete input[name=id]').val(id);
		if(active == true){
			$('#d-msg').html('Surat izin santri masih berstatus aktif. Menghapusnya akan membuat surat tersebut tidak valid. Anda yakin?');
		} else {
			$('#d-msg').html('Menghapus surat izin santri ikut menghapus riwayat izin santri tersebut. Anda yakin?');
		}
		$('#mdl-delete').modal('show');
	});
</script>
@endsection