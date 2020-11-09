<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

use App\Classroom;
USE App\Building;
use App\Dormroom;
use App\Student;

class StudentsSampleExport implements WithMultipleSheets
{
	use Exportable;
	public function sheets(): array
	{
		$sheets = [];
		
		$sheets[] = new TemplateSantri();
		
		for ($i = 1; $i <= 2; $i++) {
			$sheets[] = new BuildData($i);
		}
		
		return $sheets;
		
	}
	
}

class TemplateSantri implements WithTitle, WithCustomStartCell, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
	use RegistersEventListeners;
	
	public function collection(){ return collect(); }
	
	public function startCell(): string { return 'A1'; }
	
	public function headings(): array
	{
		return [['NO.', 'DATA PRIMER SANTRI', '', '', '', '', '', '', '', '', 'BIODATA SANTRI', '', '', '', '', '', '', '', '', '', '', '', 'DATA AYAH', '', '', '', '', '', '', '', '', '', '', 'BERCERAI (Y/T)', 'DATA IBU', '', '', '', '', '', '', '', '', '', '', 'DATA DONATUR', '', '', '', 'ASAL SEKOLAH'],
		
		['', 'STAMBUK', 'NAMA SANTRI', 'NO. KK', 'NIK', 'TGL. LAHIR', 'TEMPAT LAHIR', 'JENIS KELAMIN (L/P)', 'ID KELAS', 'ID ASRAMA',
		
		'NAMA PANGGILAN', 'NISN', 'GOL. DARAH', 'BERAT BADAN', 'TINGGI BADAN', 'HOBBY', 'CITA-CITA', 'PRESTASI', 'PADA LOMBA', 'ANAK KE', 'JLH. SAUDARA KANDUNG', 'JLH. SAUDARA TIRI',
		
		'NAMA AYAH', 'NO. KTP', 'MENINGGAL (Y/T)', 'TELEPON', 'WHATSAPP', 'ALAMAT', 'PENDIDIKAN TERAKHIR', 'AGAMA', 'PEKERJAAN', 'PENGHASILAN POKOK', 'PENGHASILAN TAMBAHAN', '', 'NAMA IBU', 'NO. KTP', 'MENINGGAL (Y/T)', 'TELEPON', 'WHATSAPP', 'ALAMAT', 'PENDIDIKAN TERAKHIR', 'AGAMA', 'PEKERJAAN', 'PENGHASILAN POKOK', 'PENGHASILAN TAMBAHAN',  'NAMA DONATUR', 'HUBUNGAN DGN. SANTRI', 'TELEPON', 'ALAMAT', 'SD/SMP', 'NEGERI (Y/T)', 'NAMA SEKOLAH', 'ALAMAT', 'NPSN', 'NO. UJIAN NASIONAL', 'NO. IJAZAH', 'NO. SKHUN', 'PINDAHAN (Y/T)', 'PINDAHAN DARI', 'ALAMAT', 'ALASAN PINDAH', 'KETERANGAN']];
	}
	
	public function title(): string { return 'Data Santri'; } 
	
	public function styles(Worksheet $sheet) { return [
		1 => ['font' => ['bold' => true, 'size' => 16]], 
		2 => ['font' => ['bold' => true, 'size' => 14]],
		'A1:BJ1' => ['alignment' => [
			'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
		],]
	]; }
	
	public static function afterSheet(AfterSheet $event)
	{
		$sheet = $event->sheet->getDelegate();
		$sheet->getComment('I2')->getText()->createTextRun('Hanya diisi ID Kelas dari Sheet Data Kelas.');
		$sheet->getComment('J2')->getText()->createTextRun('Hanya diisi ID Asrama dari Sheet Data Asrama.');
		$sheet->getComment('L2')->getText()->createTextRun('Nomor Induk Siswa Nasional.');
		$sheet->getComment('N2')->getText()->createTextRun('Hanya ketikkan angka dalam kilogram.');
		$sheet->getComment('O2')->getText()->createTextRun('Hanya ketikkan angka dalam centimeter.');
		$sheet->getComment('P2')->getText()->createTextRun('Pisahkan tiap hobi dengan koma.');
		$sheet->getComment('Q2')->getText()->createTextRun('Pisahkan tiap cita-cita dengan koma.');

		$sheet->getComment('T2')->getText()->createTextRun('Hanya ketikkan angka.');
		$sheet->getComment('U2')->getText()->createTextRun('Hanya ketikkan angka.');
		$sheet->getComment('V2')->getText()->createTextRun('Hanya ketikkan angka.');
		$sheet->getComment('Y2')->getText()->createTextRun('Y jika ayah santri sudah meninggal, T jika masih hidup.');
		$sheet->getComment('AC2')->getText()->createTextRun('SD, MI, SMP, MTS, MA, SMA, PESANTREN, D1, D2, D3, S1, S2, S3.');
		$sheet->getComment('AD2')->getText()->createTextRun('ISLAM, KRISTEN, BUDDHA, HINDU.');
		$sheet->getComment('AF2')->getText()->createTextRun('Hanya isi dengan nominal angka, tanpa simbol Rp atau titik dan koma.');
		$sheet->getComment('AG2')->getText()->createTextRun('Hanya isi dengan nominal angka, tanpa simbol Rp atau titik dan koma.');
		$sheet->getComment('AH1')->getText()->createTextRun('Y jika sudah bercerai, T jika masih menikah.');
		$sheet->getComment('AK2')->getText()->createTextRun('Y jika ibu santri sudah meninggal, T jika masih hidup.');
		$sheet->getComment('AO2')->getText()->createTextRun('SD, MI, SMP, MTS, MA, SMA, PESANTREN, D1, D2, D3, S1, S2, S3.');
		$sheet->getComment('AP2')->getText()->createTextRun('ISLAM, KRISTEN, BUDDHA, HINDU.');
		$sheet->getComment('AR2')->getText()->createTextRun('Hanya isi dengan nominal angka, tanpa simbol Rp atau titik dan koma.');
		$sheet->getComment('AS2')->getText()->createTextRun('Hanya isi dengan nominal angka, tanpa simbol Rp atau titik dan koma.');
		$sheet->getComment('AT2')->getText()->createTextRun('Hanya isi jika biaya bukan ditanggung orang tua santri.');
		$sheet->getComment('AX2')->getText()->createTextRun('Lulus dari SD atau SMP');
		$sheet->getComment('AY2')->getText()->createTextRun('Y jika asal sekolah Negeri, T jika Swasta.');
		$sheet->getComment('BB2')->getText()->createTextRun('Nomor Pokok Sekolah Nasional.');
		$sheet->getComment('BF2')->getText()->createTextRun('Y jika santri pindah dari pesantren/sekolah lain, T jika tidak.');
		
		// merging
		$event->sheet->mergeCells('A1:A2');
		$event->sheet->mergeCells('B1:J1');
		$event->sheet->mergeCells('K1:V1');
		$event->sheet->mergeCells('W1:AG1');
		$event->sheet->mergeCells('AH1:AH2');
		$event->sheet->mergeCells('AI1:AS1');
		$event->sheet->mergeCells('AT1:AW1');
		$event->sheet->mergeCells('AX1:BJ1');

		// styling
		$sheet->getStyle('B2:H2')->getFont()->getColor()->setRGB('ff0000');
		$sheet->getStyle('A1:BJ2')->getBorders()->getAllBorders()
		->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
		$sheet->getRowDimension('1')->setRowHeight(36);
	}
	
}


class BuildData implements FromCollection, WithTitle, WithCustomStartCell, WithHeadings, ShouldAutoSize, WithStyles
{
	
	private $i;
	
	public function __construct($i){
		$this->i = $i;
	}
	
	public function collection()
	{
		// return User::all();
		if($this->i == 1){
			$classrooms = Classroom::all();
			foreach ($classrooms as $classroom) {
			// 	$classroom->building =  $classroom->building->name;
			// 	unset($classroom['building_id']);
				unset($classroom['created_at']);
				unset($classroom['updated_at']);
			}
			return $classrooms->sortBy('level');
		} else {
			$dormrooms = Dormroom::all();
			foreach ($dormrooms as $dormroom) {
				// $dormroom->building = $dormroom->building->name;
				// unset($dormroom['building_id']);
				unset($dormroom['created_at']);
				unset($dormroom['updated_at']);
			}
			return $dormrooms->sortBy('building');
		}
	}
	
	public function startCell(): string
	{
		return 'A1';
	}
	
	public function headings(): array
	{
		if($this->i == 1){
			return ['ID KELAS', 'TINGKAT', 'NAMA KELAS', 'KAPASITAS'];
		}else {
			return ['ID ASRAMA', 'GEDUNG', 'NAMA ASRAMA', 'KAPASITAS'];
		}
	}
	
	public function title(): string
	{
		if($this->i == 1) {
			return 'Data Kelas';
		} else {
			return 'Data Asrama';
		}
	}
	
	public function styles(Worksheet $sheet)
	{
		return [
			1    => ['font' => ['bold' => true, 'size' => 14]],
		];
	}
	
}