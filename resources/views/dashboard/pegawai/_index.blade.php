@extends('dashboard.template')
@section('pagetitle', 'Data Pegawai')

@section('content')

@include('dashboard.components.flashmessage')
<div class="ui segments">
	<div class="ui grey segment menu">
		<h3>Data Pegawai</h3>
	</div>
	<div class="ui segment">
		<div class="ui small basic icon buttons"> 
			<button class="ui button"><i class="file icon"></i> Download PDF</button>
			<button class="ui button"><i class="upload icon"></i> Import Excel</button>
			<button class="ui button"><i class="download icon"></i> Export Excel</button>
		</div>
		<a href="{{route('pegawai.create')}}" class="ui labeled icon button green right floated"><i class="plus icon"></i> Tambah Pegawai</a>
		{{-- LIST PEGAWAI --}}
		<div class="ui divider"></div>
		<div class="ui three column grid">
			@foreach ($users as $user)
			<div class="column">
				<div class="ui fluid card">
					<div class="content">
						<img class="right floated mini ui image" src="{{$user->photo ? asset('assets/img/user/' . $user->photo) : asset('assets/img/user/nopic.png')}}">
						<div class="header">
							{{$user->name}}
						</div>
						<div class="meta">
							{{ucwords($user->getRoleNames()->implode(', '))}}
						</div>
					</div>
					<div class="content">
						<div class="description">
							<p>
								<span class="meta">Username</span>
								<span class="ui right floated">{{$user->username}}</span>
							</p>
							<p>
								<span class="meta">Email</span>
								<span class="ui right floated">{{$user->email}}</span>
							</p>
						</div>
					</div>
					<div class="extra content">
						<div class="ui two buttons">
							<a href="{{route('pegawai.edit', $user->id)}}" class="ui basic grey button">Ubah</a>
							<button class="ui basic red button btn-delete" data-id="{{$user->id}}" data-name="{{$user->name}}">Hapus</button>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<div class="ui divider"></div>
			{{$users->links()}}
	</div>
</div>

@include('dashboard.components.modaldelete')
@endsection

@section('pagescript')
<script>
	
	$(".btn-delete").click(function(){
		var id = $(this).data('id');
		var name = $(this).data('name');
		$("#message").html("Menghapus data " + name + " berarti ikut menghapus semua data yang terkait dengannya.");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('pegawai.destroy')}}");
		$("#modal-delete").modal('show');
	});
	
</script>
@endsection