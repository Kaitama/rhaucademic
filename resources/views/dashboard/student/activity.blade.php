<div class="row">
	<div class="ui basic segment">
		<h4 class="ui horizontal divider header">Riwayat Aktifitas</h4>
	</div>
	
	<div class="ui container">
		<div class="ui two column doubling stackable masonry grid">
			{{-- tuition --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui green inverted segment">
						<div class="ui inverted list">
							<div class="item">
								<h4 class="ui header">Uang Sekolah</h4>
								<div class="description">Pembayaran 12 bulan terakhir</div>
							</div>
						</div>
					</div>
					<div class="ui segment">
						@if($student->tuition->isEmpty())
						<div class="ui green message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
							@php $i = 0 @endphp
							@foreach ($student->tuition as $tuition)
							<div class="item">
								<div class="ui sub header">Pembayaran bulan {{$tuition->formonth}}/{{$tuition->foryear}}</div>
								<div class="description">Dibayar tanggal {{date('d/m/Y', strtotime($tuition->paydate))}}</div>
							</div>
							@php $i++; @endphp
							@if($i == 12) @break @endif
							@endforeach
						</div>	
						@endif
					</div>
					@if($student->tuition->count() > 12)
					<div class="ui segment">
						<a href="#" class="extra content">
							<i class="ui share icon"></i> Selengkapnya
						</a>
					</div>
					@endif
				</div>
			</div>
			
			{{-- achievement --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui orange inverted segment">
						<div class="ui inverted list">
							<div class="item">
								<h4 class="ui header">Prestasi</h4>
								<div class="description">Pencapaian santri di pesantren</div>
							</div>
						</div>
					</div>
					<div class="ui segment">
						@if($student->achievement->isEmpty())
						<div class="ui orange message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
							@php $i = 0 @endphp
							@foreach ($student->achievement->sortByDesc('date') as $ach)
							<div class="item">
								<div class="ui sub header">{{date('d/m/Y', strtotime($ach->date))}} - {{$ach->name}}</div>
								<div class="description">{{$ach->description}}</div>
								<div class="description">
									@if($ach->rank) 
									<div class="ui mini orange label">
										<i class="trophy icon"></i> Ranking {{$ach->rank}}
									</div>
									@endif
									@if($ach->reward)
									<div class="ui mini blue label">
										<i class="gift icon"></i> {{$ach->reward}}
									</div>
									@endif
								</div>
							</div>
							@php $i++; @endphp
							@if($i == 10) @break @endif
							@endforeach
						</div>
						@endif
					</div>
					@if($student->achievement->count() > 10)
					<div class="ui segment">
						<a href="#" class="extra content">
							<i class="ui share icon"></i> Selengkapnya
						</a>
					</div>
					@endif
				</div>
			</div>
			{{-- health --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui yellow inverted segment">
						<h4 class="ui header">Kesehatan</h4>
					</div>
					<div class="ui segment">
						Pending content...
					</div>
				</div>
			</div>
			{{-- permit --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui violet inverted segment">
						<h4 class="ui header">Perizinan</h4>
					</div>
					<div class="ui segment">
						@if ($student->permit->isEmpty())
						<div class="ui violet message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
							@php $i = 0 @endphp
							@foreach ($student->permit->sortByDesc('signdate') as $perm)
							<div class="item">
								<div class="ui sub header">{{date('d/m/Y', strtotime($perm->signdate))}} - {{$perm->description}}</div>
								<div class="description">Dari {{date('d/m/Y', strtotime($perm->datefrom))}} sampai {{date('d/m/Y', strtotime($perm->dateto))}}.</div>
								@if(date('Y-m-d H:i:s') <= $perm->dateto)
								<div class="ui mini green right label">Active</div>
								@else
								<span class="ui mini red right label">Expired</span>
								@endif
							</div>
							@php $i++; @endphp
							@if($i == 10) @break @endif
							@endforeach
						</div>
						@endif
					</div>
					@if($student->permit->count() > 10)
					<div class="ui segment">
						<a href="#" class="extra content">
							<i class="ui share icon"></i> Selengkapnya
						</a>
					</div>
					@endif
				</div>
			</div>
			{{-- offense --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui red inverted segment">
						<div class="ui inverted list">
							<div class="item">
								<h4 class="ui header">Pelanggaran</h4>
								<div class="description">Pelanggaran santri di pesantren</div>
							</div>
						</div>
					</div>
					<div class="ui segment">
						@if ($student->offense->isEmpty())
						<div class="ui red message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
							@php $i = 0 @endphp
							@foreach ($student->offense->sortByDesc('date') as $off)
							<div class="item">
								<div class="ui sub header">{{date('d/m/Y', strtotime($off->date))}} - {{$off->name}}</div>
								<div class="description">Hukuman: {{$off->punishment ?? '-'}}</div>
								<div class="description">Catatan: {{$off->notes ?? '-'}}</div>
							</div>
							
							@php $i++; @endphp
							@if($i == 10) @break @endif
							@endforeach
						</div>
						@endif
					</div>
					@if($student->offense->count() > 10)
					<div class="ui segment">
						<a href="#" class="extra content">
							<i class="ui share icon"></i> Selengkapnya
						</a>
					</div>
					@endif
				</div>
			</div>
			<div class="column">
				<div class="ui segments">
					<div class="ui blue inverted segment">
						<h4 class="ui header">Peminjaman Buku</h4>
					</div>
					<div class="ui segment">
						Pending content...
					</div>
				</div>
			</div>
			
		</div>
	</div>
	
</div>