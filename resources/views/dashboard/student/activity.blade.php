{{-- <div class="row"> --}}
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
								<div class="description">Riwayat pembayaran uang sekolah</div>
							</div>
						</div>
					</div>
					<div class="ui segment">
						@if($tuitions->isEmpty())
						<div class="ui green message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
							@php $i = 0 @endphp
							@foreach ($tuitions as $tuition)
							<div class="item">
								<div class="ui sub header">Pembayaran bulan {{$tuition->formonth}}/{{$tuition->foryear}}</div>
								<div class="description">Dibayar tanggal {{date('d/m/Y', strtotime($tuition->paydate))}}</div>
							</div>
							@endforeach
						</div>	
						@endif
					</div>
					@if($tuitions->hasPages())
					<div class="ui segment">
						<div class="extra content">
							{{ $tuitions->appends(['tuitions' => $tuitions->currentPage()])->links() }}
						</div>
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
						@if($achievements->isEmpty())
						<div class="ui orange message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
							@foreach ($achievements->sortByDesc('date') as $ach)
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
							@endforeach
						</div>
						@endif
					</div>
					@if($achievements->hasPages())
					<div class="ui segment">
						<div class="extra content">
							{{ $achievements->appends(['achievements' => $achievements->currentPage()])->links() }}
						</div>
					</div>
					@endif
				</div>
			</div>
			
			{{-- permit --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui violet inverted segment">
						<div class="ui inverted list">
							<div class="item">
								<h4 class="ui header">Perizinan</h4>
								<div class="description">Riwayat santri izin keluar pesantren</div>
							</div>
						</div>
					</div>
					<div class="ui segment">
						@if ($permits->isEmpty())
						<div class="ui violet message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
							@foreach ($permits->sortByDesc('signdate') as $perm)
							<div class="item">
								<div class="ui sub header">{{date('d/m/Y', strtotime($perm->signdate))}} - {{$perm->reason}}</div>
								<div class="description">{{$perm->description ? $perm->description . '.' : ''}} Dari {{date('d/m/Y', strtotime($perm->datefrom))}} sampai {{date('d/m/Y', strtotime($perm->dateto))}}.</div>
								@if(date('Y-m-d H:i:s') <= $perm->dateto)
								<div class="ui mini green right label">Active</div>
								@else
								<span class="ui mini red right label">Expired</span>
								@endif
							</div>
							@endforeach
						</div>
						@endif
					</div>
					@if($permits->hasPages())
					<div class="ui segment">
						<div class="extra content">
							{{ $permits->appends(['permits' => $permits->currentPage()])->links() }}
						</div>
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
						@if ($offenses->isEmpty())
						<div class="ui red message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
							@foreach ($offenses->sortByDesc('date') as $off)
							<div class="item">
								<div class="ui sub header">{{date('d/m/Y', strtotime($off->date))}} - {{$off->name}}</div>
								<div class="description">Hukuman: {{$off->punishment ?? '-'}}</div>
								<div class="description">Catatan: {{$off->notes ?? '-'}}</div>
							</div>
							@endforeach
						</div>
						@endif
					</div>
					@if($offenses->hasPages())
					<div class="ui segment">
						<div class="extra content">
							{{ $offenses->appends(['offenses' => $offenses->currentPage()])->links() }}
						</div>
					</div>
					@endif
				</div>
			</div>
			
			{{-- organization --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui brown inverted segment">
						<div class="ui inverted list">
							<div class="item">
								<h4 class="ui header">Organisasi</h4>
								<div class="description">Riwayat organisasi santri</div>
							</div>
						</div>
					</div>
					<div class="ui segment">
						@if ($organizations->isEmpty())
						<div class="ui brown message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
						
							@foreach ($organizations->sortByDesc('organization_student.joindate') as $org)
							@php
							switch ($org->organization_student->position) {
								case 1: $pos = 'Ketua'; break;
								case 2: $pos = 'Wakil Ketua'; break;
								case 3: $pos = 'Sekretaris'; break;
								case 4: $pos = 'Bendahara'; break;
								default: $pos = 'Anggota'; break;
							}
							@endphp
							<div class="item">
								<div class="ui sub header">{{$pos}} {{$org->name ?? ''}}</div>
								{{$org->organization_student->description ?? ''}}
								<div class="description">Dari: {{date('d/m/Y', strtotime($org->organization_student->joindate))}} sampai {{$org->organization_student->outdate ? date('d/m/Y', strtotime($org->organization_student->outdate)) : 'sekarang'}}</div>
							</div>
							
							@endforeach
						</div>
						@endif
					</div>
					@if($organizations->hasPages())
					<div class="ui center aligned segment">
						<div class="extra content">
							{{ $organizations->appends(['organizations' => $organizations->currentPage()])->links() }}
						</div>
					</div>
					@endif
				</div>
			</div>
			
			{{-- extracurricular --}}
			<div class="column">
				<div class="ui segments">
					<div class="ui purple inverted segment">
						<div class="ui inverted list">
							<div class="item">
								<h4 class="ui header">Ekstrakurikuler</h4>
								<div class="description">Riwayat kegiatan santri diluar kelas</div>
							</div>
						</div>
					</div>
					<div class="ui segment">
						@if ($extracurriculars->isEmpty())
						<div class="ui purple message">Data masih kosong.</div>
						@else
						<div class="ui divided list">
							@foreach ($extracurriculars->sortByDesc('extracurricular_student.joindate') as $ext)
							@php
							switch ($ext->day) {
								case 1: $d = 'Senin'; break;
								case 2: $d = 'Selasa'; break;
								case 3: $d = 'Rabu'; break;
								case 4: $d = 'Kamis'; break;
								case 5: $d = 'Jum\'at'; break;
								case 6: $d = 'Sabtu'; break;
								default: $d = 'Minggu'; break;
							}
							@endphp
							<div class="item">
								<div class="ui sub header">{{$ext->name ?? ''}}</div>
								<div class="description">Dari: {{date('d/m/Y', strtotime($ext->extracurricular_student->joindate))}} sampai {{$ext->extracurricular_student->outdate ? date('d/m/Y', strtotime($ext->extracurricular_student->outdate)) : 'sekarang'}}</div>
								<div class="description">Hari {{$d}} pukul {{date('H:i', strtotime($ext->time))}} WIB</div>
								<div class="description">Dibina oleh {{$ext->user->name ?? '-'}}</div>
							</div>
							
							
							@endforeach
						</div>
						@endif
					</div>
					@if($extracurriculars->hasPages())
					<div class="ui center aligned segment">
						<div class="extra content">
							{{ $extracurriculars->appends(['extracurriculars' => $extracurriculars->currentPage()])->links() }}
						</div>
					</div>
					@endif
				</div>
			</div>
			
			{{-- library --}}
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
			
		</div>
	</div>
{{-- </div> --}}
