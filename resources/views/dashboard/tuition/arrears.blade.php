@extends('dashboard.template')
@section('pagetitle', 'Tunggakan Uang Sekolah')

@section('content')

@include('dashboard.components.flashmessage')

@php
$months = ['1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April', '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus', '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
@endphp

<div class="ui segments">
	<div class="ui grey segment menu">
		<h3>Tunggakan Uang Sekolah</h3>
	</div>
	<div class="ui segment">
		{{-- LIST TUNGGAKAN --}}
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
							<div class="field required @error('month') error @enderror">
								<select name="month" class="ui dropdown">
									<option value="">Pilih bulan</option>
									@php $mo = app('request')->input('month') ?? date('m'); @endphp
									@foreach ($months as $key => $val)
									<option value="{{$key}}"{{$mo == $key ? ' selected' : ''}}>{{$val}}</option>
									@endforeach
								</select>
							</div>
							<div class="field required @error('year') error @enderror">
								<input type="text" name="year" value="{{app('request')->input('year') ?? date('Y')}}" placeholder="Tahun">
							</div>
							<div class="field">
								<button type="submit" id="btn-filter" class="ui icon button grey"><i class="ui search icon"></i></button>
							</div>
							@if($arrears)
							<div class="field">
								<a href="{{route('arrears.index')}}">Clear</a>
							</div>
							@endif
						</div>
					</form>
				</div>
			</div>
			
		</div>
		@if ($arrears == null)
		<div class="ui icon message">
			<i class="exclamation circle icon"></i>
			<div class="content">
				<div class="header">
					Lakukan filter!
				</div>
				<p>Belum ada santri yang menunggak pembayaran pada bulan ini.</p>
				
			</div>
		</div>
		@else
		<div class="ui teal message">
			Menampilkan total {{$arrears->count()}} santri yang menunggak pembayaran uang sekolah bulan {{$months[$arrears->month]}} tahun {{$arrears->year}}.
		</div>
		<table class="ui celled table">
			<thead>
				<tr>
					<th>#</th>
					<th>Stambuk</th>
					<th>Nama</th>
					<th>Kelas</th>
					<th>Asrama</th>
					<th>Telp</th>
					<th>WhatsApp</th>
				</tr>
			</thead>
			<tbody>
				@php $no = 1 @endphp
				@foreach ($arrears as $ar)
				<tr>
					<td>{{$no++}}</td>
					<td>{{$ar->stambuk}}</td>
					<td><a href="{{route('student.profile', $ar->stambuk)}}">{{$ar->name}}</a></td>
					<td>{{$ar->classroom['name']}}</td>
					<td>{{$ar->dormroom['name']}}</td>
					<td>{{$ar->studentprofile->fphone ?? $ar->studentprofile->mphone}}</td>
					<td>{{$ar->studentprofile->fwa ?? $ar->studentprofile->mwa}}</td>
				</tr>
				
				@endforeach
			</tbody>
		</table>		
		@endif
		
	</div>
</div>

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
	});
	
	
</script>	
@endsection