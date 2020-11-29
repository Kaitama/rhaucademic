{{-- slider --}}
@include('dashboard.components.slider')

<div class="ui basic segment">
	

	<div class="ui two columns grid stackable">
		<div class="five wide column">
			<div class="ui fluid card">
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
			{{-- today statistics --}}
			<div class="ui basic segment">
				<div class="ui horizontal divider">Statistik Santri Hari Ini</div>
				<div class="ui four column stackable grid">
					
					{{-- checkout --}}
					<div class="ui column">
						<div class="ui segment center aligned black inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$checkouts}}
								</div>
								<div class="label">
									Checkout
								</div>
							</div>
						</div>
					</div>
					{{-- checkin --}}
					<div class="ui column">
						<div class="ui segment center aligned black inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$checkins}}
								</div>
								<div class="label">
									Checkin
								</div>
							</div>
						</div>
					</div>
					{{-- payments  --}}
					<div class="ui column">
						<div class="ui segment center aligned black inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$tuitions}}
								</div>
								<div class="label">
									Pembayaran
								</div>
							</div>
						</div>
					</div>
					{{-- offenses --}}
					<div class="ui column">
						<div class="ui segment center aligned black inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$offenses}}
								</div>
								<div class="label">
									Pelanggaran
								</div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
			
			{{-- all time statistics --}}
			<div class="ui basic segment">
				<div class="ui horizontal divider">Statistik Raudhah</div>
				<div class="ui four column stackable grid">
					
					{{-- user --}}
					<div class="ui column">
						<div class="ui segment center aligned teal inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$users}}
								</div>
								<div class="label">
									User Akun
								</div>
							</div>
						</div>
					</div>
					{{-- student --}}
					<div class="ui column">
						<div class="ui segment center aligned green inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$stdactive}}
								</div>
								<div class="label">
									Santri Aktif
								</div>
							</div>
						</div>
					</div>
					{{-- inactive --}}
					<div class="ui column">
						<div class="ui segment center aligned red inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$stdinactiv}}
								</div>
								<div class="label">
									Santri Nonaktif
								</div>
							</div>
						</div>
					</div>
					{{-- alumni --}}
					<div class="ui column">
						<div class="ui segment center aligned grey inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$alumni > 9999 ? '9999+' : $alumni}}
								</div>
								<div class="label">
									Alumni
								</div>
							</div>
						</div>
					</div>
					{{-- classroom --}}
					<div class="ui column">
						<div class="ui segment center aligned brown inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$classrooms->count()}}
								</div>
								<div class="label">
									Ruang Kelas
								</div>
							</div>
						</div>
					</div>
					{{-- dormroom --}}
					<div class="ui column">
						<div class="ui segment center aligned violet inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$dormrooms->count()}}
								</div>
								<div class="label">
									Ruang Asrama
								</div>
							</div>
						</div>
					</div>
					{{-- organization --}}
					<div class="ui column">
						<div class="ui segment center aligned orange inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$organizations}}
								</div>
								<div class="label">
									Organisasi
								</div>
							</div>
						</div>
					</div>
					{{-- extracurricular --}}
					<div class="ui column">
						<div class="ui segment center aligned olive inverted">
							<div class="ui statistic inverted">
								<div class="value">
									{{$extracurriculars}}
								</div>
								<div class="label">
									Ekstrakurikuler
								</div>
							</div>
						</div>
					</div>
					
					
				</div>
			</div>
			
			
		</div>
	</div>
	
	<div class="ui two columns grid stackable">
		<div class="column">
			<div class="ui segments accordion">
				<div class="ui blue inverted segment active title">
					<h4><i class="dropdown icon"></i>Kelas</h4>
				</div>
				<div class="ui segment active content">
					<div class="ui three columns stackable grid">
						@foreach ($classrooms as $classroom)
						<div class="column">
							<div class="ui fluid card">
								<div class="content"><div class="ui grey sub header">Tingkat {{$classroom->level}}</div></div>
								<div class="content">
									<a href="{{route('classroom.show', $classroom->id)}}" class="header">{{$classroom->name}}</a>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
		<div class="column">
			<div class="ui segments accordion">
				<div class="ui teal inverted segment active title">
					<h4><i class="dropdown icon"></i>Asrama</h4>
				</div>
				<div class="ui segment active content">
					<div class="ui two columns stackable grid">
						@foreach ($dormrooms as $dormroom)
						<div class="column">
							<div class="ui fluid card">
								<div class="content"><div class="ui grey sub header">Gedung {{$dormroom->building}}</div></div>
								<div class="content">
									<a href="{{route('dormroom.show', $dormroom->id)}}" class="header">{{$dormroom->name}}</a>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>