<div class="ui basic segment">
				
	<div class="ui special cards slider">
		@foreach ($carousels as $car)
		<div class="ui fluid card">
			<div class="blurring dimmable image">
				<div class="ui dimmer">
					<div class="content">
						<div class="center">
							<h2 class="ui inverted header">{{$car->title}}</h2>
							@if($car->link)
							<a href="{{$car->link}}" target="_blank" class="ui button inverted">Kunjungi Link</a>
							@endif
						</div>
					</div>
				</div>
				<img src="{{asset('assets/img/carrousel/' . $car->image)}}" class="img-slider">
			</div>
		</div>
		@endforeach
	</div>
	
</div>