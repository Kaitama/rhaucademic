@extends('dashboard.template')
@section('pagetitle', 'Laporan Perizinan')

@section('content')
@include('dashboard.components.flashmessage')
<div class="ui stackable grid">
	
	<div class="four wide column">
		<div class="ui segments">
			<div class="ui grey segment menu">
				<h3>Tentukan Query</h3>
			</div>
			<div class="ui segment">
				<form action="" method="get" id="rep-permit" class="ui form error">
					@csrf
					<div class="required field @error('datefrom') error @enderror">
						<label>Mulai Tanggal</label>
						<input type="text" name="datefrom" value="{{old('datefrom') ?? date('d/m/Y')}}">
					</div>
					@php
					$next = date('d/m/Y', strtotime(date('d-m-Y') . '+2 days'));
					@endphp
					<div class="required field @error('dateto') error @enderror">
						<label>Sampai Tanggal</label>
						<input type="text" name="dateto" value="{{old('dateto') ?? $next}}">
					</div>
					<div class="field">
						<label>Opsi</label>
						<select name="option" class="ui dropdown">
							<option value="1">Semua Data</option>
							<option value="2">Checkout</option>
							<option value="3">Checkin</option>
						</select>
					</div>
				</form>
			</div>
			<div class="ui segment">
				<button class="ui labeled icon button fluid green large" onclick="document.getElementById('rep-permit').submit()">
					<i class="ui print icon"></i>Proses
				</button>
			</div>
			
		</div>
		
	</div>
	
	<div class="twelve wide column">
		<div class="ui segments">
			<div class="ui segment">
				<h3>Laporan Perizinan</h3>
			</div>
			<div class="ui segment">
				@if (!$data)
				<div class="ui message">Tentukan query untuk menampilkan laporan.</div>
				@else
				<div class="ui message">Menampilkan total {{$data->count()}} data.</div>
				<table class="ui celled table">
					<thead>
						<tr>
							<th>#</th>
							<th>Santri</th>
							<th>Tenggat</th>
							<th>Checkout / Checkin</th>
							<th>Dikeluarkan</th>
						</tr>
					</thead>
					<tbody>
						@php $no = 1 @endphp
						@foreach ($data as $d)
						<tr>
							<td>{{$no++}}</td>
							<td>
								<div class="ui list">
									<div class="item">
										<div class="content">
											<div class="header"><a href="{{route('student.profile', $d->student->stambuk)}}">{{$d->student->name}}</a></div>
											<div class="description">Kelas {{$d->student->classroom->name ?? '-'}}, Asrama {{$d->student->dormroom->name ?? '-'}}</div>
										</div>
									</div>
									<div class="item">
										<div class="content">
											<div class="description">Alasan</div>
											<div class="header">{{$d->reason}}</div>
										</div>
									</div>
									<div class="item">
										<div class="content">
											<div class="description">Keterangan</div>
											<div class="header">{{$d->description ?? '-'}}</div>
										</div>
									</div>
								</div>
							</td>
							<td>
								<div class="ui list">
									<div class="item">
										<div class="content">
										<div class="description">Dari</div>
										<div class="header">{{date('d/m/Y', strtotime($d->datefrom))}}</div>
									</div>
									</div>
									<div class="item">
										<div class="content">
										<div class="description">Sampai</div>
										<div class="header">{{date('d/m/Y', strtotime($d->dateto))}}</div>
									</div>
									</div>
									<div class="item">
										<div class="content">
										<div class="description">Pukul</div>
										<div class="header">{{date('H:i', strtotime($d->dateto))}} WIB</div>
									</div>
									</div>
								</div>
							</td>
							<td>
								<div class="ui list">
									<div class="item">
										<div class="content">
											<div class="description">Checkout</div>
											<div class="header">{{$d->checkout ? date('d/m/Y H:i', strtotime($d->checkout)) . ' WIB' : '-'}}</div>
											<div class="description">Oleh {{$d->outby}}</div>
										</div>
									</div>
									<div class="item">
										<div class="content">
											<div class="description">Checkin</div>
											<div class="header">{{$d->checkin ? date('d/m/Y H:i', strtotime($d->checkin)) . ' WIB' : '-'}}</div>
											<div class="description">Oleh {{$d->inby}}</div>
										</div>
									</div>
								</div>
							</td>
							<td>
								<div class="ui list">
									<div class="item">
										<div class="content">
											<div class="description">Tanggal</div>
											<div class="header">{{date('d/m/Y', strtotime($d->signdate))}}</div>
										</div>
									</div>
									<div class="item">
										<div class="content">
											<div class="description">Oleh</div>
											<div class="header">{{$d->user->name}}</div>
										</div>
									</div>
									<div class="item">
										<div class="content">
											<a href="{{route('permit.validating', $d->signature)}}" class="ui labeled mini icon button"><i class="ui eye icon"></i>Tampilkan</a>
										</div>
									</div>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection