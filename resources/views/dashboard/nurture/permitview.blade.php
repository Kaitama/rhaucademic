<html>
<head>
	<title>Surat Izin {{$data->student['name']}}</title>
	<style>
		body{
			margin: 0 2cm!important;
			font-size: 12pt;
		}
		.kop { text-align: center; }
		.h1 {font-size: 14pt; font-weight: bold;}
		.h2 {font-size: 12pt; font-weight: bold;}
		.line	{ line-height: 1.5pt; }
		.left-col { width: 3.5cm; padding-left: 0.4cm;}
		table.signature { width: 100%}
		.signature tr td { vertical-align: bottom;}
		.signname, .bardesc {line-height: 0;}
		.maklumat {margin-top: 2cm;}
	</style>
</head>
@php 
$sd = strtotime($data->signdate);
$fd = strtotime($data->datefrom);
$td = strtotime($data->dateto);
@endphp
<body>
	<p class="kop">
		<span class="h1">PENGASUHAN</span><br>
		<span class="h2">PESANTREN AR-RAUDLATUL HASANAH</span><br>
		<span class="h3">Jl. Letjend Jamin Ginting Km.11 Paya Bundung Medan 20135 Telp. (061) 8360135</span>
	</p>
	<hr class="line">
	<p>Memberikan izin kepada:</p>
	<table>
		<tr>
			<td class="left-col">Stambuk</td>
			<td>:</td>
			<td>{{$data->student['stambuk']}}</td>
		</tr>
		<tr>
			<td class="left-col">Nama Santri</td>
			<td>:</td>
			<td>{{ucwords(strtolower($data->student['name']))}}</td>
		</tr>
		<tr>
			<td class="left-col">Kelas</td>
			<td>:</td>
			<td>{{$data->student->classroom['name']}}</td>
		</tr>
		<tr>
			<td class="left-col">Alasan</td>
			<td>:</td>
			<td>{{$data->reason}}</td>
		</tr>
		<tr>
			<td class="left-col">Keterangan</td>
			<td>:</td>
			<td>{{$data->description ?? '-'}}</td>
		</tr>
	</table>
	<p>Surat ini berlaku:</p>
	<table>
		<tr>
			<td class="left-col">Mulai Dari</td>
			<td>:</td>
			<td>Hari {{$data->dayfrom}}, {{date('d', $fd)}} {{$data->monthfrom}} {{date('Y', $fd)}}</td>
		</tr>
		<tr>
			<td class="left-col">Sampai Dengan</td>
			<td>:</td>
			<td>Hari {{$data->dayto}}, {{date('d', $td)}} {{$data->monthto}} {{date('Y', $td)}}</td>
		</tr>
		<tr>
			<td class="left-col">Pukul</td>
			<td>:</td>
			<td>{{date('H:i', strtotime($data->dateto))}} WIB</td>
		</tr>
	</table>
	<br>
	<br>
	@php $url = url('/dashboard/permit/validate/' . $data->signature); @endphp
	
	<table class="signature">
		<tr>
			{{-- barcode --}}
			<td width="60%">
				{!! DNS2D::getBarcodeHTML($url, 'QRCODE', 3.5, 3.5) !!}
				<p class="bardesc">&nbsp;(Scan untuk validasi)</p>
			</td>
			{{-- signature --}}
			<td>
				<p>
					Raudhah, {{date('d', $sd)}} {{$data->monthsign}} {{date('Y', $sd)}} <br>
					Pemberi izin <br><br><br><br><br>
				</p>
				<p class="signname">
					<b>{{$data->user['name']}}</b>
				</p>
			</td>
		</tr>
	</table>
	<h4 class="maklumat">MAKLUMAT</h4>
	<ol>
		<li>Surat ini hanya sebagai keterangan yang bersangkutan telah izin keluar kampus Pesantren Ar-Raudlatul Hasanah.</li>
		<li>Pengasuhan tidak menerima alasan apapun untuk memperpanjang izin melalui telp/fax.</li>
		<li>Jika tanpa ada pemberitahuan perpanjangan izin, yang bersangkutan akan mendapat sanksi sebagai berikut:</li>
		<ol type="a">
			<li>Terlambat hadir mendapat sanksi menjadi petugas kebersihan.</li>
			<li>Terlambat 1 hari mendapat sanksi potong rambut ABRI / memakai jilbab merah selama 3 hari.</li>
			<li>Terlambat 2 hari mendapat sanksi potong rambut botak /  memakai jilbab merah selama 1 minggu.</li>
			<li>Terlambat 3 hari diserahkan kepada orang tua.</li>
		</ol>
	</ol>
</body>
</html>