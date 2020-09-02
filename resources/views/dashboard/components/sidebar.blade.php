@php
		$s = Request::segment(2);
@endphp
<div class="ui sidebar vertical menu sidebar-menu" id="sidebar">
	<div class="item">
		<img src="{{asset('assets/img/app/logo.png')}}" class="ui small image centered" alt="Raudhah">
	</div>
	<a href="{{route('dashboard-index')}}" class="item{{$s == null ? ' active' : ''}}">
		<div>
			<i class="icon home grey"></i>
			Dashboard
		</div>
	</a>
	<div class="item">
		<div class="sidebar-header">
			Keuangan
		</div>
	</div>
	<a href="#" class="item">
		<div>
			<i class="icon chart line grey"></i>
			Charts
		</div>
	</a>

	<div class="item">
		<div class="sidebar-header">
			Basis Data
		</div>
	</div>
	<a href="#" class="item">
		<div>
			<i class="icon address book grey"></i>
			Santri
		</div>
	</a>
	<a href="{{route('pegawai-index')}}" class="item{{$s == 'pegawai' ? ' active' : ''}}">
		<div>
			<i class="icon id badge grey"></i>
			Pegawai
		</div>
	</a>
	<a href="#" class="item">
		<div>
			<i class="icon university grey"></i>
			Kelas
		</div>
	</a>
	
	
	<div class="item">
		<form action="#">
			<div class="ui mini action input">
				<input type="text" placeholder="Search..." />
				<button class="ui mini icon button">
					<i class=" search icon"></i>
				</button>
			</div>
		</form>
	</div>
	<div class="ui basic segment">
		<strong>&copy;{{date('Y')}} <a href="https://www.raudhah.ac.id">Raudhah</a></strong><br> 
		<small><br>Crafted by</small><br> <span class="jariyah-text">JARIYAH</span> Digital Solution
	</div>
</div>