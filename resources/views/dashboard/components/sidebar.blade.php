@php
$s = Request::segment(2);
@endphp
<div class="ui sidebar vertical menu sidebar-menu" id="sidebar">
	<div class="item">
		<img src="{{asset('assets/img/app/logo.png')}}" class="ui small image centered" alt="Raudhah">
	</div>
	<a href="{{route('dashboard.index')}}" class="item{{$s == null ? ' active' : ''}}">
		<div>
			<i class="icon home grey"></i>
			Dashboard
		</div>
	</a>
	
	@can('m pengasuhan')
	<div class="item">
		<div class="sidebar-header">
			Pengasuhan
		</div>
	</div>
	<a href="{{route('achievement.index')}}" class="item{{$s == 'achievement' ? ' active' : ''}}">
		<div>
			<i class="trophy icon grey"></i>
			Prestasi
		</div>
	</a>
	<a href="{{route('offense.index')}}" class="item{{$s == 'offense' ? ' active' : ''}}">
		<div>
			<i class="icon hand paper grey"></i>
			Pelanggaran
		</div>
	</a>
	<a href="{{route('permit.index')}}" class="item{{$s == 'permit' ? ' active' : ''}}">
		<div>
			<i class="newspaper icon grey"></i>
			Perizinan
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
	
	@can('m basdat')
	<div class="item">
		<div class="sidebar-header">
			Basis Data
		</div>
	</div>
	<a href="{{route('pegawai.index')}}" class="item{{$s == 'staff' ? ' active' : ''}}">
		<div>
			<i class="icon user grey"></i>
			Pegawai
		</div>
	</a>
	<a href="{{route('classroom.index')}}" class="item{{$s == 'classroom' ? ' active' : ''}}">
		<div>
			<i class="icon university grey"></i>
			Kelas
		</div>
	</a>
	<a href="{{route('dormroom.index')}}" class="item{{$s == 'dormroom' ? ' active' : ''}}">
		<div>
			<i class="icon building grey"></i>
			Asrama
		</div>
	</a>
	<a href="{{route('student.index')}}" class="item{{$s == 'student' ? ' active' : ''}}">
		<div>
			<i class="icon users grey"></i>
			Santri
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
	
	{{-- <div class="item">
		<form action="#">
			<div class="ui mini action input">
				<input type="text" placeholder="Search..." />
				<button class="ui mini icon button">
					<i class=" search icon"></i>
				</button>
			</div>
		</form>
	</div> --}}
	<div class="ui horizontal divider">&bull;</div>
	<div class="ui basic segment">
		<strong>&copy;{{date('Y')}} <a href="{{env('APP_URL')}}">{{env('APP_NAME')}}</a></strong><br> 
		<small><br>Crafted by</small><br> <span class="jariyah-text">JARIYAH</span> Digital Solution
	</div>
	<div class="ui basic segment"></div>
</div>