@extends('dashboard.template')
@section('pagetitle', 'Ruang Kelas')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui stackable grid">
	
	<div class="five wide column">
		<div class="ui segments">
			<div class="ui grey segment">
				<h4 class="ui header">Tambah Banner Baru</h4>
			</div>
			<div class="ui segment">
				<form action="{{route('carrousel.store')}}" method="post" id="form-banner" class="ui form error" enctype="multipart/form-data">
					@csrf
					<div class="field required @error('title') error @enderror">
						<label>Judul</label>
						<input type="text" name="title">
					</div>
					<div class="field required @error('image') error @enderror">
						<label>Banner</label>
						<div class="blurring dimmable image">
							<div class="ui dimmer">
								<div class="content">
									<div class="ui action input">
										<input type="file" name="img">
									</div>
									<div class="ui fluid inverted labeled icon button" id="attach"><i class="ui upload icon"></i>Upload</div>
								</div>
							</div>
							<img class="ui fluid image" id="banner-image" src="{{asset('assets/img/carrousel/noimage.png')}}">
						</div>
					</div>
					<div class="field">
						<label>Link Tujuan</label>
						<input type="text" name="link" placeholder="{{config('app.url')}}">
					</div>
				</form>
			</div>
			<div class="ui segment">
				<div class="ui labeled icon button positive fluid" onclick="document.getElementById('form-banner').submit()">
					<i class="ui save icon"></i>Simpan
				</div>
			</div>
		</div>
	</div>
	
	<div class="eleven wide column">
		
		<div class="ui segments">
			<div class="ui grey segment">
				<h4 class="ui header">List Banner Informasi</h4>
			</div>
			@if($carrousels->isEmpty())
			<div class="ui segment">
				<div class="ui message">
					<div class="header">Kosong!</div>
					<p>Data banner informasi masih kosong.</p>
				</div>
			</div>
			@else
			<div class="ui segment">
				<div class="ui divided items">
					@foreach ($carrousels->sortByDesc('created_at') as $car)
					<div class="item">
						<div class="image">
							<img src="{{asset('assets/img/carrousel/' . $car->image)}}">
						</div>
						<div class="content">
							<a href="{{$car->link}}" class="header">{{$car->title}}</a>
							<div class="meta">
								<span>Dipublish pada {{date('d/m/Y', strtotime($car->created_at))}}</span>
							</div>
							<div class="extra">
								<div class="ui negative mini labeled icon button" onclick="confirmDelete({{$car->id}}, '{{$car->title}}')">
									<i class="right trash icon"></i>
									Delete
								</div>
							</div>
						</div>
					</div>
					@endforeach
				</div>
				<div class="ui message">
					<p>Hanya 5 banner informasi terbaru yang akan tampil pada aplikasi Mobile orang tua santri.</p>
				</div>
			</div>
			
			@endif
		</div>
		
	</div>
	
</div>


{{-- modal delete --}}
@include('dashboard.components.modaldelete')

@endsection

@section('pagescript')
<script>
	
	function confirmDelete(id, title){
		$("#message").html("Banner informasi " + title + " akan dihapus!");
		$("#data-id").val(id);
		$('#form-delete').attr("action", "{{route('carrousel.destroy')}}");
		$("#modal-delete").modal('show');
	}
	
	$('.blurring.image').dimmer({
		on: 'hover'
	});
	$("#attach").click(function(){
		$("input[name=img]").click();
	});
	$('input:file', '.ui.action.input')
	.on('change', function(e) {
		readURL(this);
	});
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function(e) {
				$('#banner-image').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]); // convert to base64 string
		}
	}
</script>
@endsection