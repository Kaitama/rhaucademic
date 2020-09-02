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
			<img class="ui avatar image" src="{{asset('assets/img/user/nopic.png')}}">
			<span class="nav-username">Username</span>
			<div class="menu">
				<a href="#" class="item">
					<i class="info circle icon"></i> 
					Profile
				</a>
				<a href="#" class="item">
					<i class="wrench icon"></i>
					Settings
				</a>
				<a href="#" class="item">
					<i class="sign-out icon"></i>
					Logout
				</a>
			</div>
		</div>
	</div>
</nav>