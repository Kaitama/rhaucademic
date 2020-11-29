@extends('dashboard.template')
@section('pagetitle', 'Data Organisasi')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable grid">
	
	<div class="five wide column">
		
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h4 class="ui header">Tambah Organisasi</h4>
			</div>
			<div class="ui segment">
				<form action="{{route('organization.store')}}" method="post" id="form-create" class="ui form error">
					@csrf
					<div class="required field @error('name') error @enderror">
						<label>Nama Organisasi</label>
						<input type="text" name="name" value="{{old('name')}}">
					</div>
					<div class="required field @error('focus') error @enderror">
						<label>Fokus Organisasi</label>
						<input type="text" name="focus" value="{{old('focus')}}">
					</div>
					<div class="field">
						<label>Deskripsi Organisasi</label>
						<textarea name="description" rows="3">{{old('description')}}</textarea>
					</div>
					<div class="required field @error('starting_at') error @enderror">
						<label>Berdiri Tanggal</label>
						<input type="text" name="starting_at" value="{{old('starting_at') ?? date('d/m/Y')}}">
					</div>
					<div class="field">
						<label>Status</label>
						<select name="active" class="ui fluid dropdown">
							<option value="1">Aktif</option>
							<option value="0">Nonaktif</option>
						</select>
					</div>
					
				</form>
			</div>
			<div class="ui segment">
				<button class="ui button labeled icon green fluid" onclick="document.getElementById('form-create').submit()">
					<i class="ui plus icon"></i>
					Tambah
				</button>
			</div>
		</div>
	</div>
	
	
	<div class="eleven wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h4 class="ui header">Data Organisasi Aktif</h4>
			</div>
			@if ($actives->isEmpty())
			<div class="ui segment">
				<div class="ui message"><p>Data organisasi nonaktif masih kosong.</p></div>
			</div>
			@else
			<div class="ui segment">
				<div class="ui relaxed divided list">
					@foreach ($actives as $act)
					
					<div class="item segment">
						<div class="right floated content">
							<div class="ui icon button mini editing" data-id="{{$act->id}}" data-name="{{$act->name}}" data-focus="{{$act->focus}}" data-starting="{{date('d/m/Y', strtotime($act->starting_at))}}" data-desc="{{$act->description}}" data-position="left center" data-inverted="" data-tooltip="Ubah Organisasi"><i class="ui edit icon"></i></div>
							<div class="ui icon button negative mini toggle-active" data-id="{{$act->id}}" data-name="{{$act->name}}" data-position="left center" data-inverted="" data-tooltip="Nonaktifkan Organisasi"><i class="icon times"></i></div>
						</div>
						<div class="content">
							<a class="header" href="{{route('organization.show', $act->id)}}">{{$act->name}}</a>
							<div class="description">
								{{$act->focus}}<br>
								<span class="italic">Berdiri tanggal {{date('d/m/Y', strtotime($act->starting_at))}}</span>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
			@endif
		</div>
		
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h4 class="ui header">Data Organisasi Nonaktif</h4>
			</div>
			@if ($nonactives->isEmpty())
			<div class="ui segment">
				<div class="ui message"><p>Data organisasi nonaktif masih kosong.</p></div>
			</div>
			@else
			<div class="ui segment">
				<div class="ui relaxed divided list">
					
					@foreach ($nonactives as $non)
					
					<div class="item segment">
						<div class="right floated content">
							<div class="ui icon button positive mini toggle-inactive" data-id="{{$non->id}}" data-name="{{$non->name}}" data-position="left center" data-inverted="" data-tooltip="Aktifkan Organisasi"><i class="icon check"></i></div>
						</div>
						<div class="content">
							<a class="header" href="{{route('organization.show', $non->id)}}">{{$non->name}}</a>
							<div class="description">
								{{$non->focus}}<br>
								<span class="italic">Berdiri tanggal {{date('d/m/Y', strtotime($non->starting_at))}}</span>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
			
			@endif
		</div>
	</div>
	
</div>





<div id="modal-toggle-active" class="ui modal tiny">
	<div class="header">
		Nonaktifkan Organisasi
	</div>
	<div class="ui basic segment">
		<div class="ui red message">
			<p>Anda yakin ingin menonaktifkan organisasi <span class="org-name"></span>? Semua anggota yang terdaftar akan ikut dinonaktifkan!</p>
		</div>
	</div>
	<form id="form-toggle-active" method="POST" action="{{route('organization.deactivate')}}">
		@csrf
		<input type="hidden" name="deactivate_id" id="deactivate_id" value="">
	</form>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<button onclick="event.preventDefault(); document.getElementById('form-toggle-active').submit();" class="ui negative right labeled icon button">
			Nonaktifkan
			<i class="check icon"></i>
		</button>
	</div>
</div>

<div id="modal-toggle-inactive" class="ui modal tiny">
	<div class="header">
		Aktifkan Organisasi
	</div>
	<div class="ui basic segment">
		<div class="ui green message">
			<p>Anda yakin ingin mengaktifkan organisasi <span class="org-name"></span>?</p>
		</div>
	</div>
	<form id="form-toggle-inactive" method="POST" action="{{route('organization.activate')}}">
		@csrf
		<input type="hidden" name="activate_id" id="activate_id" value="">
	</form>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<button onclick="event.preventDefault(); document.getElementById('form-toggle-inactive').submit();" class="ui positive right labeled icon button">
			Aktifkan
			<i class="check icon"></i>
		</button>
	</div>
</div>

{{-- modal edit --}}
<div id="modal-update" class="ui modal tiny">
	<div class="header">Ubah Data Organisasi</div>
	<div class="ui basic segment">
		<form id="form-update" class="ui form error" method="POST" action="{{route('organization.update')}}">
			@csrf
			<input type="hidden" name="upid" value="">
			<div class="required field @error('uname') error @enderror">
				<label>Nama Organisasi</label>
				<input type="text" name="uname" value="{{old('uname')}}">
			</div>
			<div class="required field @error('ufocus') error @enderror">
				<label>Fokus Organisasi</label>
				<input type="text" name="ufocus" value="{{old('ufocus')}}">
			</div>
			<div class="field">
				<label>Deskripsi Organisasi</label>
				<textarea name="udescription" rows="3">{{old('udescription')}}</textarea>
			</div>
			<div class="required field @error('ustarting_at') error @enderror">
				<label>Berdiri Tanggal</label>
				<input type="text" name="ustarting_at" value="{{old('ustarting_at') ?? date('d/m/Y')}}">
			</div>
			
		</form>
	</div>
	<div class="actions">
		<div class="ui black deny button">
			Batal
		</div>
		<button onclick="document.getElementById('form-update').submit();" class="ui right labeled positive icon button">
			<i class="save icon"></i> Ubah
		</button>
	</div>
</div>


@include('dashboard.components.modaldelete')

@endsection

@section('pagescript')
<script>
	
	$(".toggle-inactive").click(function(){
		var org_id = $(this).data('id');
		var org_name = $(this).data('name');
		$('#activate_id').val(org_id);
		$('.org-name').html(org_name);
		$('#modal-toggle-inactive').modal('show');
	})
	
	$(".toggle-active").click(function(){
		var org_id = $(this).data('id');
		var org_name = $(this).data('name');
		$('#deactivate_id').val(org_id);
		$('.org-name').html(org_name);
		$('#modal-toggle-active').modal('show');
	})
	
	$(".addstudents").click(function(){
		var id = $(this).data('id');
		$('input[name=organization_id]').val(id);
		$('#modal-add-students').modal('show');
	})
	
	$(".editing").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		var focus = $(this).data('focus');
		var desc = $(this).data('desc');
		var start = $(this).data('starting');

		$('#form-update input[name=upid]').val(id);
		$('#form-update input[name=uname]').val(name);
		$('#form-update textarea[name=udescription]').html(desc);
		$('#form-update input[name=ufocus]').val(focus);
		$('#form-update input[name=ustarting_at]').val(start);
		
		$("#modal-update").modal('show');
	});
	$(".delete-organization").click(function(){
		var id = $(this).data('organizationid');
		var name = $(this).data('organizationname');
		$("#message").html("Menghapus data Kelas " + name + " memungkinkan sebagian santri tidak memiliki kelas.");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('organization.destroy')}}");
		$("#modal-delete").modal('show');
	});
</script>
@endsection