<div class="ui basic segment">
	
	<div class="ui two columns grid stackable">
		<div class="five wide column">
			<div class="ui card">
				<div class="image">
					<img src="{{asset('assets/img/user')}}/{{Auth::user()->photo ?? 'nopic.png'}}">
				</div>
				<div class="content">
					<a class="header">{{Auth::user()->name}}</a>
					<div class="meta">
						<span class="date">{{ucwords(Auth::user()->getRoleNames()->implode(', '))}}</span>
					</div>
					<div class="description">
						<div class="ui list">
							<div class="content">
								<div class="item">
									<i class="ui mail icon"></i>{{Auth::user()->email}}
								</div>
								<div class="item">
									<i class="ui user icon"></i>{{Auth::user()->username}}
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="extra content">
					<a href="{{route('user.settings')}}">
						<i class="cog icon"></i>
						Edit profile
					</a>
				</div>
			</div>
		</div>
		<div class="eleven wide column">
			{{--  --}}
		</div>
	</div>
	
</div>