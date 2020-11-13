<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<title>{{env('APP_NAME')}} - Surat Izin Keluar</title>
	
	<link rel="stylesheet" href="{{asset('assets/semantic/semantic.min.css')}}" />
	<link
	rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css"
	integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ="
	crossorigin="anonymous"
	/>
	<style>
		body {
			background-color: #DADADA;
		}
		body > .grid {
			height: 100%;
		}
		.sub.header {
			/* text-align: center!important; */
			margin-top: 0!important;
			margin-bottom: 0!important;
		}
		.student.name {
			margin-top: 10px!important;
			margin-bottom: 0px!important;
		}
		.paragraph {
			text-align: center!important;
			font-size: 120%!important;
		}
		.signature {
			margin-top: 32px!important;
			text-align: right!important;
		}
	</style>
</head>

<body>
	
	@php
	if ($data->student['photo']) {
		$src = asset('assets/img/student/' . $data->student['photo']);
	} else {
		if($data->student['gender'] == 'P') $src = asset('assets/img/student/female.jpg');
		else $src = asset('assets/img/student/male.jpg');
	}
	@endphp
	
	<div class="ui two column stackable middle aligned centered grid">
		<div class="column">
			
			<div class="ui segment">
				@if ($data->active)
				<div class="ui top right ribbon green label">Valid</div>
				@else
				<div class="ui top right ribbon red label">Expired</div>
				@endif
				
				<img class="ui small centered circular image" src="{{$src}}">
				<div class="ui header center aligned student name">{{$data->student['name']}}</div>
				<div class="ui sub header center aligned">{{$data->student['stambuk']}} <br> {{$data->student->classroom['name']}}</div>
				<div class="ui horizontal divider">&bull;</div>
				
				<p class="paragraph">Surat izin keluar dengan keperluan <b>{{$data->reason}}</b>{{$data->description ? ' (' . $data->description .')' : ''}}. Surat ini berlaku mulai hari <b>{{$data->dayfrom}}</b> tanggal <b>{{$data->textfrom}}</b> sampai dengan <b>{{$data->dayto}}</b> tanggal <b>{{$data->textto}}</b> pukul <b>{{date('H:i', strtotime($data->dateto))}} WIB</b>.</p>
				
				<p class="signature"><b>{{$data->user['name']}}</b> <br> {{date('d/m/Y', strtotime($data->signdate))}}</p>
			</div>
			<div class="ui basic segment center aligned">
				@cannot('m validasi perizinan')
					<div class="ui orange message">Surat ini hanya dapat divalidasi oleh user yang berwenang.</div>
				@endcannot
				@if (!$data->checkout)
				@can('m validasi perizinan')
				<form id="checkout" action="{{route('permit.checkout')}}" method="post" style="display: none">
					@csrf
					<input type="hidden" name="id" value="{{$data->id}}">
				</form>
				<div class="ui negative button fluid large" onclick="document.getElementById('checkout').submit()">Checkout</div>
				@endcan
				@endif
				@if ($data->checkout && !$data->checkin)
				<div class="ui red message">
					Santri checkout pada tanggal <b>{{date('d/m/Y', strtotime($data->checkout))}}</b> pukul <b>{{date('H:i', strtotime($data->checkout))}}</b> WIB oleh <b>{{$data->outby}}</b>.
				</div>
				@can('m validasi perizinan')
				<form id="checkin" action="{{route('permit.checkin')}}" method="post" style="display: none">
					@csrf
					<input type="hidden" name="id" value="{{$data->id}}">
				</form>
				<div class="ui positive button fluid large" onclick="document.getElementById('checkin').submit()">Checkin</div>
				@endcan
				@endif
				@if ($data->checkout && $data->checkin)
				<div class="ui red message">
					Santri checkout pada tanggal <b>{{date('d/m/Y', strtotime($data->checkout))}}</b> pukul <b>{{date('H:i', strtotime($data->checkout))}}</b> WIB oleh <b>{{$data->outby}}</b>.
				</div>
				<div class="ui green message">
					Santri checkin pada tanggal <b>{{date('d/m/Y', strtotime($data->checkin))}}</b> pukul <b>{{date('H:i', strtotime($data->checkin))}}</b> WIB oleh <b>{{$data->inby}}</b>.
				</div>
				@endif
			</div>
		</div>
		
	</div>
</div>


<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/semantic/semantic.min.js')}}"></script>
<script src="{{asset('assets/js/script.js')}}"></script>
</body>
</html>