<nav class="ui top fixed menu">
	<div class="left menu">
		<a href="#" class="sidebar-menu-toggler item" data-target="#sidebar">
			<i class="sidebar icon"></i>
		</a>
		<a href="#" class="header item">
			RAUDHAH
		</a>
	</div>
	
	<div class="right menu">
		{{-- <a href="#" class="item"><i class="bell icon"></i></a> --}}
		<div class="ui dropdown item">
			{{-- <i class="user cirlce icon"></i> --}}
			<img class="ui avatar image" src="{{Auth::user()->photo ? asset('assets/img/user/' . Auth::user()->photo) : asset('assets/img/user/nopic.png')}}">
			<span class="nav-username">{{ucfirst(Auth::user()->name)}}</span>
			<div class="menu">
				<a href="#" class="item">
					<i class="info circle icon"></i> 
					Profile
				</a>
				<a href="{{route('user.settings')}}" class="item">
					<i class="wrench icon"></i>
					Settings
				</a>
				<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="item">
					<i class="sign-out icon"></i>
					Logout
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
					@csrf
			</form>
			</div>
		</div>
	</div>
</nav>