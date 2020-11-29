@extends('dashboard.template')
@section('pagetitle', $student->name)
@section('content')

@include('dashboard.components.flashmessage')

@if ($student->photo)
@php $photo = asset('assets/img/student/' . $student->photo) @endphp
@else
@if($student->gender == 'L')
@php $photo = asset('assets/img/student/male.jpg') @endphp
@else
@php $photo = asset('assets/img/student/female.jpg') @endphp
@endif
@endif

<div class="ui stackable grid">
	<div class="five wide column">
		<div id="data1">
			
			{{-- picture --}}
			<div class="ui fluid card" id="propic">
				<div class="blurring dimmable image">
					<div class="ui dimmer">
						<div class="content">
							<div class="center">
								<form action="{{route('student.update.photo')}}" method="post" id="formphoto" class="ui form" enctype="multipart/form-data">
									@csrf
									<input type="hidden" name="id" value="{{$student->id}}">
									<div class="ui action input">
										<input type="file" name="photo">
									</div>
								</form>
								
								@can('m basdat')
								<div class="ui fluid inverted labeled icon button" id="attach"><i class="ui upload icon"></i> Ubah Photo</div>
								<div class="ui divider"></div>
								@endcan
								<a href="{{url('assets/img/originals', $student->photo)}}" target="_blank" class="ui fluid inverted labeled icon button"><i class="ui download icon"></i> Download</a>
							</div>
						</div>
					</div>
					<img src="{{$photo}}" id="studentphoto">
				</div>
				<div class="content">
					@can('m basdat')
					<a id="edata1" class="ui bottom right attached red label" onclick="toggleForm('ddata1','edata1', 'fdata1', 'cdata1')">
						<i class="ui edit icon"></i> Edit
					</a>
					<a id="cdata1" class="ui bottom right attached grey label" onclick="toggleForm('ddata1','edata1', 'fdata1', 'cdata1')" style="display: none">
						<i class="ui times icon"></i> Cancel
					</a>
					@endcan
					<div id="filelabel" style="display: none">
						<label id="filename" class="meta"></label>
						<button id="submit-formphoto" class="fluid ui button green">Upload foto</button>
					</div>
					{{-- barcode --}}
					<div class="ui basic segment center aligned">
						@php echo '<img class="ui fluid image" src="data:image/png;base64,' . DNS1D::getBarcodePNG($student->stambuk, "C128", 3, 64) . '" alt="barcode"   />'; @endphp
					</div>
					{{-- /barcode --}}
					@php
					switch ($student->status) {
						case 1: $status = 'AKTIF'; break;
						case 2: $status = 'ALUMNI'; break;
						case 3: $status = 'SKORSING'; break;
						case 4: $status = 'CUTI'; break;
						case 5: $status = 'SAKIT'; break;
						case 6: $status = 'AKADEMIK'; break;
						case 7: $status = 'EKONOMI'; break;
						case 8: $status = 'LAINNYA'; break;
						default: $status = ''; break;
					}
					@endphp
					<div id="ddata1" class="ui fluid list">
						<div class="item">
							<div class="content">
								<div class="description">Status</div>
								<div class="header">{{$student->status == 1 ? 'AKTIF' : 'NONAKTIF'}}</div>
							</div>
						</div>
						@if ($student->status != 1)
						<div class="item">
							<div class="content">
								<div class="description">Keterangan</div>
								<div class="header">{{$status}}{{$student->description ? ' - ' . $student->description : ''}}</div>
							</div>
						</div>
						@endif
						<div class="item">
							<div class="content">
								<div class="description">Stambuk</div>
								<div class="header">{{$student->stambuk}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nama Lengkap</div>
								<div class="header">{{$student->name}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Kelas</div>
								<div class="header">{{$student->classroom_id ? $student->classroom->name : '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Asrama</div>
								<div class="header">{{$student->dormroom_id ? $student->dormroom->name : '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Tempat, Tanggal Lahir</div>
								<div class="header">{{$student->birthplace}}, {{date('d/m/Y', strtotime($student->birthdate))}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Jenis Kelamin</div>
								<div class="header">{{$student->gender == 'P' ? 'PEREMPUAN' : 'LAKI-LAKI'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Kartu Keluarga</div>
								<div class="header">{{$student->nokk}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Induk Kependudukan</div>
								<div class="header">{{$student->nik}}</div>
							</div>
						</div>
					</div>
					
					@can('m basdat')
					@include('dashboard.student.forms.form1')
					@endcan
					
				</div>
			</div>
			
		</div>
	</div>
	{{-- right column --}}
	<div class="eleven wide column">
		
		{{-- tab menu --}}
		<div class="ui top attached tabular menu">
			<a class="item active" data-tab="biodata">Biodata</a>
			<a class="item" data-tab="wali">Wali</a>
			@if($student->studentprofile['donatur'])
			<a class="item" data-tab="donatur">Donatur</a>
			@endif
			<a class="item" data-tab="school">Asal Sekolah</a>
		</div>
		{{-- /tab --}}
		
		{{-- bio data --}}
		<div id="data2" class="ui bottom attached tab segment active" data-tab="biodata">
			@can('m basdat')
			<a id="edata2" class="ui right ribbon red label" onclick="toggleForm('ddata2','edata2', 'fdata2', 'cdata2')"><i class="ui edit icon"></i> Edit</a>
			<a id="cdata2" class="ui right ribbon grey label" onclick="toggleForm('ddata2','edata2', 'fdata2', 'cdata2')" style="display: none"><i class="ui times icon"></i> Cancel</a>
			@endcan
			<div id="ddata2" class="ui stackable two column very relaxed grid">
				<div class="column">
					<div class="ui list">
						<div class="item">
							<div class="content">
								<div class="description">Nama Panggilan</div>
								<div class="header">{{$student->studentprofile['nickname'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Induk Siswa Nasional</div>
								<div class="header">{{$student->studentprofile['nisn'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Golongan Darah</div>
								<div class="header">{{$student->studentprofile['blood'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Tinggi Badan</div>
								<div class="header">{{$student->studentprofile['height'] ?? '00 '}} CM</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Berat Badan</div>
								<div class="header">{{$student->studentprofile['weight'] ?? '00 '}} KG</div>
							</div>
						</div>
					</div>
				</div>
				<div class="column">
					<div class="ui list">
						<div class="item">
							<div class="content">
								<div class="description">Hobby</div>
								<div class="header">{{$student->studentprofile['hobby'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Cita-cita</div>
								<div class="header">{{$student->studentprofile['wishes'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Prestasi</div>
								<div class="header">{{$student->studentprofile['achievement'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Pada</div>
								<div class="header">{{$student->studentprofile['competition'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							@php $siblings = $student->studentprofile['siblings'] + 1 @endphp
							<div class="content">
								<div class="description">Anak Ke</div>
								<div class="header">{{$student->studentprofile['numposition'] ? $student->studentprofile['numposition'] . ' DARI ' . $siblings . ' BERSAUDARA' : '-'}}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@can('m basdat')
			@include('dashboard.student.forms.form2')
			@endcan
			<div class="ui vertical divider">&bull;</div>
		</div>
		{{-- /bio data --}}
		
		{{-- wali --}}
		<div id="data3" class="ui bottom attached tab segment" data-tab="wali">
			@can('m basdat')
			<a id="edata3" class="ui right ribbon red label" onclick="toggleForm('ddata3','edata3', 'fdata3', 'cdata3')"><i class="ui edit icon"></i> Edit</a>
			<a id="cdata3" class="ui right ribbon grey label" onclick="toggleForm('ddata3','edata3', 'fdata3', 'cdata3')" style="display: none"><i class="ui times icon"></i> Cancel</a>
			@endcan
			<div id="ddata3" class="ui stackable two column very relaxed grid">
				<div class="column">
					<div class="ui list">
						<div class="item">
							<div class="content">
								<div class="description">Nama Ayah {{$student->studentprofile['flive'] ? '' : '(Almarhum)'}}</div>
								<div class="header">{{$student->studentprofile['fname'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Induk Kependudukan</div>
								<div class="header">{{$student->studentprofile['fktp'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Telepon</div>
								<div class="header">{{$student->studentprofile['fphone'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor WhatsApp</div>
								<div class="header">{{$student->studentprofile['fwa'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Alamat</div>
								<div class="header">{{$student->studentprofile['fadd'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Pendidikan Terakhir</div>
								<div class="header">{{$student->studentprofile['fedu'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Agama</div>
								<div class="header">{{$student->studentprofile['freligion'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Pekerjaan</div>
								<div class="header">{{$student->studentprofile['fwork'] ?? '-'}}</div>
							</div>
						</div>
						@php
						if($student->studentprofile['fsalary']){
							$fsalary = 'Rp. ' . number_format($student->studentprofile['fsalary'] , 0, ',', '.');
						}
						if($student->studentprofile['faddsalary']){
							$faddsalary = 'Rp. ' . number_format($student->studentprofile['faddsalary'] , 0, ',', '.');
						}
						@endphp
						<div class="item">
							<div class="content">
								<div class="description">Penghasilan / Bulan</div>
								<div class="header">{{$student->studentprofile['fsalary'] ? $fsalary : '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Penghasilan Tambahan / Bulan</div>
								<div class="header">{{$student->studentprofile['faddsalary'] ? $faddsalary : '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Status Pernikahan</div>
								<div class="header">{{$student->studentprofile['mariage'] == 1 ? 'MENIKAH' : 'BERCERAI'}}</div>
							</div>
						</div>
					</div>
				</div>
				<div class="column">
					<div class="ui list">
						<div class="item">
							<div class="content">
								<div class="description">Nama Ibu {{$student->studentprofile['mlive'] ? '' : '(Almarhumah)'}}</div>
								<div class="header">{{$student->studentprofile['mname'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Induk Kependudukan</div>
								<div class="header">{{$student->studentprofile['mktp'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Telepon</div>
								<div class="header">{{$student->studentprofile['mphone'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor WhatsApp</div>
								<div class="header">{{$student->studentprofile['mwa'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Alamat</div>
								<div class="header">{{$student->studentprofile['madd'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Pendidikan Terakhir</div>
								<div class="header">{{$student->studentprofile['medu'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Agama</div>
								<div class="header">{{$student->studentprofile['mreligion'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Pekerjaan</div>
								<div class="header">{{$student->studentprofile['mwork'] ?? '-'}}</div>
							</div>
						</div>
						@php
						if($student->studentprofile['msalary']){
							$msalary = 'Rp. ' . number_format($student->studentprofile['msalary'] , 0, ',', '.');
						}
						if($student->studentprofile['maddsalary']){
							$maddsalary = 'Rp. ' . number_format($student->studentprofile['maddsalary'] , 0, ',', '.');
						}
						@endphp
						<div class="item">
							<div class="content">
								<div class="description">Penghasilan / Bulan</div>
								<div class="header">{{$student->studentprofile['msalary'] ? $msalary : '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Penghasilan Tambahan / Bulan</div>
								<div class="header">{{$student->studentprofile['maddsalary'] ? $maddsalary : '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Pembiayaan</div>
								<div class="header">{{$student->studentprofile['donatur'] ? 'SANTRI DIBIAYAI DONATUR' : 'SANTRI DIBIAYAI ORANG TUA'}}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@can('m basdat')
			@include('dashboard.student.forms.form3')
			@endcan
			<div class="ui vertical divider">&bull;</div>
		</div>
		{{-- /wali --}}
		
		@if($student->studentprofile['donatur'])
		{{-- donatur --}}
		<div id="data4" class="ui bottom attached tab segment" data-tab="donatur">
			@can('m basdat')
			<a id="edata4" class="ui right ribbon red label" onclick="toggleForm('ddata4','edata4', 'fdata4', 'cdata4')"><i class="ui edit icon"></i> Edit</a>
			<a id="cdata4" class="ui right ribbon grey label" onclick="toggleForm('ddata4','edata4', 'fdata4', 'cdata4')" style="display: none"><i class="ui times icon"></i> Cancel</a>
			@endcan
			<div id="ddata4" class="ui stackable two column very relaxed grid">
				<div class="column">
					<div class="ui list">
						<div class="item">
							<div class="content">
								<div class="description">Nama Donatur</div>
								<div class="header">{{$student->studentprofile['dname'] ?? '-'}}</div>
							</div>
						</div>
						
						<div class="item">
							<div class="content">
								<div class="description">Hubungan Dengan Santri</div>
								<div class="header">{{$student->studentprofile['drelation'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Telepon</div>
								<div class="header">{{$student->studentprofile['dphone'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Alamat</div>
								<div class="header">{{$student->studentprofile['dadd'] ?? '-'}}</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			@can('m basdat')
			@include('dashboard.student.forms.form4')
			@endcan
			<div class="ui vertical divider">&bull;</div>
		</div>
		{{-- /donatur --}}
		@endif
		
		{{-- school --}}
		<div id="data5" class="ui bottom attached tab segment" data-tab="school">
			@can('m basdat')
			<a id="edata5" class="ui right ribbon red label" onclick="toggleForm('ddata5','edata5', 'fdata5', 'cdata5')"><i class="ui edit icon"></i> Edit</a>
			<a id="cdata5" class="ui right ribbon grey label" onclick="toggleForm('ddata5','edata5', 'fdata5', 'cdata5')" style="display: none"><i class="ui times icon"></i> Cancel</a>
			@endcan
			<div id="ddata5" class="ui stackable two column very relaxed grid">
				<div class="column">
					<div class="ui list">
						@if($student->studentprofile['transfer'])
						<div class="item">
							<div class="content">
								<div class="description">Status</div>
								<div class="header">PINDAHAN</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Alasan Pindah</div>
								<div class="header">{{$student->studentprofile['preason'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Keterangan</div>
								<div class="header">{{$student->studentprofile['pdescription'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Asal Sekolah</div>
								<div class="header">{{$student->studentprofile['sfrom'] ? $student->studentprofile['sfrom'] . ' ' . $student->studentprofile['slevel'] : '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nama Sekolah</div>
								<div class="header">{{$student->studentprofile['pfrom'] ?? '-'}}</div>
							</div>
						</div>
						@else
						<div class="item">
							<div class="content">
								<div class="description">Asal Sekolah</div>
								<div class="header">{{$student->studentprofile['sfrom'] ? $student->studentprofile['sfrom'] . ' ' . $student->studentprofile['slevel'] : '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nama Sekolah</div>
								<div class="header">{{$student->studentprofile['sname'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Alamat</div>
								<div class="header">{{$student->studentprofile['sadd'] ?? '-'}}</div>
							</div>
						</div>
						@endif
					</div>
				</div>
				
				<div class="column">
					<div class="ui list">
						@if($student->studentprofile['transfer'])
						<div class="item">
							<div class="content">
								<div class="description">Alamat</div>
								<div class="header">{{$student->studentprofile['padd'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Pokok Sekolah Nasional</div>
								<div class="header">{{$student->studentprofile['snpsn'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Ujian Nasional</div>
								<div class="header">{{$student->studentprofile['sun'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Ijazah</div>
								<div class="header">{{$student->studentprofile['sijazah'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Surat Keterangan Hasil Ujian Nasional</div>
								<div class="header">{{$student->studentprofile['sskhun'] ?? '-'}}</div>
							</div>
						</div>
						@else
						<div class="item">
							<div class="content">
								<div class="description">Nomor Pokok Sekolah Nasional</div>
								<div class="header">{{$student->studentprofile['snpsn'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Ujian Nasional</div>
								<div class="header">{{$student->studentprofile['sun'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Ijazah</div>
								<div class="header">{{$student->studentprofile['sijazah'] ?? '-'}}</div>
							</div>
						</div>
						<div class="item">
							<div class="content">
								<div class="description">Nomor Surat Keterangan Hasil Ujian Nasional</div>
								<div class="header">{{$student->studentprofile['sskhun'] ?? '-'}}</div>
							</div>
						</div>
						@endif
					</div>
				</div>
				
			</div>
			@can('m basdat')
			@include('dashboard.student.forms.form5')
			@endcan
			<div class="ui vertical divider">&bull;</div>
		</div>
		{{-- /school --}}
		
		
		@if (Auth::user()->level == 9)
		<div class="ui basic segment"></div>
		@include('dashboard.components.slider')
		@endif
		
		@include('dashboard.student.activity')
		
	</div>
	{{-- /right column --}}
</div>


@endsection

@section('pagescript')

@can('m basdat')
<script>
	function toggleForm(d, e, f, c){
		$("#" + d).toggle();
		$("#" + f).toggle();
		$("#" + e).toggle();
		$("#" + c).toggle();
	}
</script>
@endcan
<script>
	$(document).ready(function(){
		$('.slider').slick({
			autoplay: true,
			autoplaySpeed: 2000,
			dots: true,
			arrows: false,
			// prevArrow: '<button class="slick-prev ui mini icon button"><i class="ui angle left"></i></button>',
		});
		$('.special.cards .image').dimmer({
			on: 'hover'
		});
	});
	
	$('#transfer').click(function(){
		state = $(this).hasClass('checked');
		if(state){
			$('.tfield').show();
		} else {
			$('.tfield').hide();
		}
		
	})
	
	$('#propic .image').dimmer({
		on: 'hover'
	});
	$("#attach").click(function(){
		$("input[name=photo]").click();
	});
	$('input:file', '.ui.action.input')
	.on('change', function(e) {
		var name = e.target.files[0].name;
		// $('input:text', $(e.target).parent()).val(name);
		$("#filelabel").show();
		$('#filename', $(e.target).parent()).html('File: ' + name);
		readURL(this);
	});
	$("#submit-formphoto").click(function(){
		$("#formphoto").submit();
	});
	$('.menu .item').tab();
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function(e) {
				$('#studentphoto').attr('src', e.target.result);
			}
			
			reader.readAsDataURL(input.files[0]); // convert to base64 string
		}
	}
</script>
@endsection