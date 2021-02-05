@php
$s = Request::segment(2);
@endphp
<div class="ui sidebar vertical menu sidebar-menu" id="sidebar">
	<div class="item">
		<img src="{{asset('assets/img/app/logo.png')}}" class="ui small image centered" alt="Raudhah">
	</div>
	
	@if(Auth::user()->level < 9)
	<a href="{{route('dashboard.index')}}" class="item{{$s == null ? ' active' : ''}}">
		<div>
			<i class="icon home grey"></i>
			Dashboard
		</div>
	</a>
	
	@canany(['r prestasi', 'r perizinan', 'r pelanggaran', 'r asrama', 'r organisasi', 'r ekskul'])
	<div class="item">
		<div class="sidebar-header">
			Pengasuhan
		</div>
	</div>
	@endcanany

	@can('r prestasi')
	<a href="{{route('achievement.index')}}" class="item{{$s == 'achievement' ? ' active' : ''}}">
		<div>
			<i class="trophy icon grey"></i>
			Prestasi
		</div>
	</a>
	@endcan

	@can('r pelanggaran')
	<a href="{{route('offense.index')}}" class="item{{$s == 'offense' ? ' active' : ''}}">
		<div>
			<i class="icon hand paper grey"></i>
			Pelanggaran
		</div>
	</a>
	@endcan

	@can('r perizinan')
	<a href="{{route('permit.index')}}" class="item{{$s == 'permit' ? ' active' : ''}}">
		<div>
			<i class="newspaper icon grey"></i>
			Perizinan
		</div>
	</a>
	@endcan

	@can('r asrama')
	<a href="{{route('dormroom.index')}}" class="item{{$s == 'dormroom' ? ' active' : ''}}">
		<div>
			<i class="icon building grey"></i>
			Asrama
		</div>
	</a>
	@endcan

	@can('r organisasi')
	<a href="{{route('organization.index')}}" class="item{{$s == 'organization' ? ' active' : ''}}">
		<div>
			<i class="icon sitemap grey"></i>
			Organisasi
		</div>
	</a>
	@endcan

	@can('r ekskul')
	<a href="{{route('extracurricular.index')}}" class="item{{$s == 'extracurricular' ? ' active' : ''}}">
		<div>
			<i class="icon futbol grey"></i>
			Ekstrakurikuler
		</div>
	</a>
	@endcan
	
	@can('m keuangan')
	<div class="item">
		<div class="sidebar-header">
			Keuangan
		</div>
	</div>
	<a href="{{route('tuition.index')}}" class="item{{$s == 'tuition' ? ' active' : ''}}">
		<div>
			<i class="icon calendar check grey"></i>
			Uang Sekolah
		</div>
	</a>
	@endcan

	@canany(['r perizinan', 'm keuangan'])
	<div class="item">
		<div class="sidebar-header">
			Laporan
		</div>
	</div>
	@endcanany
	
	@can('r perizinan')
	<a href="{{route('report.permit')}}" class="item{{$s == 'report-permit' ? ' active' : ''}}">
		<div>
			<i class="icon file alternate grey"></i>
			Laporan Perizinan
		</div>
	</a>
	@endcan

	@can('m keuangan')
	<a href="{{route('arrears.index')}}" class="item{{$s == 'arrears' ? ' active' : ''}}">
		<div>
			<i class="icon calendar times grey"></i>
			Laporan Tunggakan
		</div>
	</a>
	@endcan

	@if (Auth::user()->level == 1 || Auth::user()->level == 2)
	<a href="{{route('logs.index')}}" class="item{{$s == 'logs' ? ' active' : ''}}">
		<div>
			<i class="icon retweet grey"></i>
			Log Aktivitas
		</div>
	</a>
	@endif
	
	
	<div class="item">
		<div class="sidebar-header">
			Basis Data
		</div>
	</div>
	@can('m basdat')
	<a href="{{route('pegawai.index')}}" class="item{{$s == 'staff' ? ' active' : ''}}">
		<div>
			<i class="icon user grey"></i>
			Pegawai
		</div>
	</a>
	@endcan

	@can('m basdat')
	<a href="{{route('classroom.index')}}" class="item{{$s == 'classroom' ? ' active' : ''}}">
		<div>
			<i class="icon university grey"></i>
			Kelas
		</div>
	</a>
	@endcan

	@can('r santri')
	<a href="{{route('student.index')}}" class="item{{$s == 'student' ? ' active' : ''}}">
		<div>
			<i class="icon users grey"></i>
			Santri
		</div>
	</a>
	@endcan

	@can('m basdat')
	<a href="{{route('carrousel.index')}}" class="item{{$s == 'carrousel' ? ' active' : ''}}">
		<div>
			<i class="icon images outline grey"></i>
			Banner Informasi
		</div>
	</a>
	@endcan
	
	@role('developer')
	<div class="item">
		<div class="sidebar-header">
			Developer Menu
		</div>
	</div>
	<a href="{{route('role.index')}}" class="item{{$s == 'role' ? ' active' : ''}}">
		<div>
			<i class="icon user times grey"></i>
			Role
		</div>
	</a>
	<a href="{{route('permission.index')}}" class="item{{$s == 'permission' ? ' active' : ''}}">
		<div>
			<i class="icon sitemap grey"></i>
			Permission
		</div>
	</a>
	@endrole
	<div class="ui horizontal divider">&bull;</div>
	@endif
	<div class="ui basic segment">
		<strong>&copy;{{date('Y')}} <a href="{{env('APP_URL')}}">{{env('APP_NAME')}}</a></strong><br> 
		<small><br>Crafted by</small><br> <span class="jariyah-text">JARIYAH</span> Digital Solution
	</div>
	<div class="ui basic segment"></div>
</div>