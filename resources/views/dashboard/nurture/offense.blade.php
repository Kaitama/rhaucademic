@extends('dashboard.template')
@section('pagetitle', 'Pelanggaran')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable two column grid">
	
	<div class="five wide column">
		@can('c pelanggaran')
		<div class="ui segments">
			<div class="ui red inverted segment">
				<h4 class="ui header">Tambahkan Pelanggaran</h4>
			</div>
			<div class="ui segment">
				<form method="post" action="{{route('offense.store')}}" id="frm-add" class="ui form error">
					@csrf
					<div class="field required @error('date') error @enderror">
						<label>Tanggal</label>
						<input type="text" name="date" value="{{old('date') ?? date('d/m/Y')}}">
					</div>
					<div class="field required @error('name') error @enderror">
						<label>Nama Pelanggaran</label>
						<input type="text" name="name" value="{{old('name')}}">
					</div>
					{{--  --}}
					<div class="field required @error('students') error @enderror">
						<label>Nama Santri</label>
						<div class="ui fluid search multiple selection dropdown selectstudents">
							<input type="hidden" name="students" value="{{old('students')}}">
							<i class="search icon"></i>
							<div class="default text"></div>
						</div>
					</div>
					<div class="field">
						<label>Hukuman</label>
						<textarea name="punishment" rows="2">{{old('punishment')}}</textarea>
					</div>
					<div class="field">
						<label>Catatan</label>
						<textarea name="notes" rows="2">{{old('notes')}}</textarea>
					</div>
					<div class="field">
						<div class="ui grey sub header right aligned">Ditulis oleh {{Auth::user()->name}}</div>
					</div>
				</form>
			</div>
			<div class="ui segment">
				<div class="ui fluid red labeled icon button" onclick="document.getElementById('frm-add').submit()">
					<i class="ui save icon"></i>Simpan
				</div>
			</div>
		</div>
		@endcan
		
	</div>
	<div class="eleven wide column">
		<div class="ui segments">
			<div class="ui red inverted segment">
				<h4 class="ui header">Daftar Santri Dengan Pelanggaran</h4>
			</div>
			@if($lists->isEmpty())
			<div class="ui segment">
				<div class="ui red message">
					<div class="header">Data kosong.</div>
					<p>Tidak ada santri dengan pelanggaran.</p>
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
							{{$list->offense->count()}} pelanggaran
							
						</div>
					</div>
					<div class="content">
						<div class="ui segments">
							@foreach ($list->offense->sortByDesc('date') as $off)
							<div class="ui segment">
								<div class="ui sub header">
									{{date('d/m/Y', strtotime($off->date))}} - {{$off->name}}
								</div>
								
								<div class="description">
									<i class="ui exclamation triangle icon"></i>	Hukuman: {{$off->punishment ?? '-'}}
								</div>
								
								<div class="description"><i class="ui sticky note icon"></i> Catatan: {{$off->notes ?? '-'}}</div>
								<div class="description" style="font-size: 0.8em">
									<i>Ditulis oleh {{$off->user['name']}}</i>
								</div>
								
								@can('u pelanggaran')
								<a 
								class="ui mini button btn-edit" 
								data-id="{{$off->id}}" 
								data-stdname="{{$list->name}}" 
								data-stambuk="{{$list->stambuk}}" 
								data-photo="{{asset('assets/img/student/' . $mphoto)}}" 
								data-name="{{$off->name}}" 
								data-date="{{date('d/m/Y', strtotime($off->date))}}" 
								data-punishment="{{$off->punishment}}" 
								data-notes="{{$off->notes}}" 
								>Ubah</a> 
								@endcan
								
								@canany(['d pelanggaran', 'global delete'])
								<a class="ui red mini button btn-delete" 
								data-id="{{$off->id}}" 
								data-stdname="{{$list->name}}" 
								data-stambuk="{{$list->stambuk}}" 
								data-photo="{{asset('assets/img/student/' . $mphoto)}}" 
								data-name="{{$off->name}}" 
								data-date="{{date('d/m/Y', strtotime($off->date))}}" 
								data-punishment="{{$off->punishment}}" 
								data-notes="{{$off->notes}}" 
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
@can('u pelanggaran')
{{-- modal edit offense --}}
<div id="mdl-edit" class="ui tiny modal">
	<div class="header">
		Ubah Pelanggaran
	</div>
	
	<div class="content">
		<div class="description">
			<div class="ui container center aligned">
				<img id="e-photo" class="ui centered small circular image" src="">
				<span id="e-header" class="ui sub header"></span>
			</div>
			<div class="ui horizontal divider">&bull;</div>
			<form method="post" action="{{route('offense.update')}}" id="frm-offense-update" class="ui form">
				@csrf
				<input type="hidden" name="eid" value="">
				<div class="field required @error('edate') error @enderror">
					<label>Tanggal</label>
					<input type="text" name="edate" value="">
				</div>
				<div class="field required @error('ename') error @enderror">
					<label>Nama Pelanggaran</label>
					<input type="text" name="ename" value="">
				</div>
				<div class="field">
					<label>Hukuman</label>
					<input type="text" name="epunishment" value="">
				</div>
				<div class="field">
					<label>Catatan</label>
					<textarea name="enotes" rows="2"></textarea>
				</div>
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui positive right labeled icon button" onclick="document.getElementById('frm-offense-update').submit()">
			Simpan
			<i class="checkmark icon"></i>
		</div>
	</div>
</div>
@endcan
@canany(['d pelanggaran', 'global delete'])
{{-- modal delete offense --}}
<div id="mdl-delete" class="ui tiny modal">
	<div class="header">
		Hapus Pelanggaran
	</div>
	
	<div class="content">
		<div class="description">
			<div class="ui container center aligned">
				<img id="d-photo" class="ui centered small circular image" src="">
				<span id="d-header" class="ui sub header"></span>
			</div>
			<div class="ui horizontal divider">&bull;</div>
			<h5 class="ui red header">Anda yakin ingin menghapus pelanggaran berikut?</h5>
			<div class="ui segment">
				<div id="d-title" class="ui sub header"></div>
				<div id="d-punishment" class="meta"></div>
				<div id="d-notes" class="meta"></div>
			</div>
			<form action="{{route('offense.destroy')}}" method="post" id="frm-offense-delete">
				@csrf <input type="hidden" name="id" value="">
			</form>
		</div>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<div class="ui negative right labeled icon button" onclick="document.getElementById('frm-offense-delete').submit()">
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
</script>

@can('u pelanggaran')
<script>
	$('.btn-edit').click(function(){
		var stambuk = $(this).data('stambuk');
		var stdname = $(this).data('stdname');
		var photo = $(this).data('photo');
		var id = $(this).data('id');
		var name = $(this).data('name');
		var date = $(this).data('date');
		var punishment = $(this).data('punishment');
		var notes = $(this).data('notes');
		$('#mdl-edit #e-photo').attr('src', photo);
		$('#mdl-edit #e-header').html(stambuk + ' - ' + stdname);
		$('#mdl-edit input[name=eid]').val(id);
		$('#mdl-edit input[name=ename]').val(name);
		$('#mdl-edit input[name=edate]').val(date);
		$('#mdl-edit input[name=epunishment]').val(punishment);
		$('#mdl-edit textarea[name=enotes]').html(notes);
		$('#mdl-edit').modal('show');
	});
</script>
@endcan

@canany(['d pelanggaran', 'global delete'])
<script>
	$('.btn-delete').click(function(){
		var stambuk = $(this).data('stambuk');
		var stdname = $(this).data('stdname');
		var photo = $(this).data('photo');
		var id = $(this).data('id');
		var name = $(this).data('name');
		var date = $(this).data('date');
		var punishment = $(this).data('punishment');
		var notes = $(this).data('notes');
		$('#mdl-delete #d-photo').attr('src', photo);
		$('#mdl-delete #d-header').html(stambuk + ' - ' + stdname);
		$('#mdl-delete #d-title').html(date + ' - ' + name);
		$('#mdl-delete #d-punishment').html('Hukuman: ' + punishment);
		$('#mdl-delete #d-notes').html('<i>' + notes + '</i>');
		$('#mdl-delete input[name=id]').val(id);
		$('#mdl-delete').modal('show');
	});
</script>
@endcanany

@endsection