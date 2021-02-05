@extends('dashboard.template')
@section('pagetitle', 'User Log')

@section('content')
@include('dashboard.components.flashmessage')

<div class="ui segments">
	<div class="ui segment">
		<h4 class="ui header">Log Aktifitas Pegawai</h4>
	</div>
	<div class="ui segment">
		<table class="ui celled table">
			<thead>
				<tr>
					<th>#</th>
					<th>Waktu</th>
					<th>Keterangan</th>
					<th>Opsi</th>
				</tr>
			</thead>
			<tbody>
				@php $no = 1 @endphp
				@foreach ($logs as $log)
				@php
					if ($log->description == 'created') {
						$t = 'menambahkan';
					} 
					if ($log->description == 'updated') {
						$t = 'mengubah';
					}
					if ($log->description == 'deleted') {
						$t = 'menghapus';
					}
				@endphp
						<tr>
							<td>{{$no++}}</td>
							<td>{{date('d/m/Y H:i', strtotime($log->created_at))}}</td>
							<td>{{$log->causer->name}} {{$t}} data {{$log->log_name}}.</td>
							<td>
								<div class="ui flat button" onclick="showDetails({{$log->changes}})">Details</div>
							</td>
						</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>

<div id="md-details" class="ui modal">
  <div class="header">
    Detail Log
  </div>
  <div class="content">
    <div id="desc" class="description">
      
    </div>
  </div>
  <div class="actions">
    <div class="ui black deny right labeled icon button">
      Close
      <i class="times icon"></i>
    </div>
  </div>
</div>

@endsection

@section('pagescript')
<script>
	// 
	function showDetails(obj){
		console.log(obj);
		$('#md-details').modal('show');
	}
</script>
@endsection