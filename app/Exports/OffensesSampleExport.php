<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class OffensesSampleExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
	use RegistersEventListeners;
	
	/**
	* @return \Illuminate\Support\Collection
	*/
	public function collection()
	{
		//
		return collect();
	}

	public function headings(): array
	{
		return [
			[
			'NO.',
			'WAKTU PELANGGARAN',
			'', '',
			'STAMBUK',
			'NAMA PELANGGARAN',
			'HUKUMAN',
			'KETERANGAN'
			],
			[
				'', 'TGL', 'BLN', 'THN', '', '', '', ''
			]
		];
	}

	public function styles(Worksheet $sheet)
	{
		return [
			1    => ['font' => ['bold' => true, 'size' => 14]],
			2    => ['font' => ['bold' => true, 'size' => 14]],
			'A1:H1' => ['alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],]
		];
	}

	public static function afterSheet(AfterSheet $event)
	{
		$sheet = $event->sheet->getDelegate();
		$sheet->getComment('B2')->getText()->createTextRun('Hanya diisi angka tanggal.');
		$sheet->getComment('C2')->getText()->createTextRun('Hanya diisi angka bulan 1 s/d 12.');
		$sheet->getComment('D2')->getText()->createTextRun('Hanya diisi 4 digit angka tahun.');
		$sheet->getComment('H1')->getText()->createTextRun('Kolom ini boleh dikosongkan.');
		$sheet->getRowDimension('1')->setRowHeight(36);
		$sheet->getStyle('B1')->getAlignment()->setWrapText(true);
		$sheet->getStyle('B1')->getFont()->getColor()->setRGB('ff0000');
		$sheet->getStyle('B2:D2')->getFont()->getColor()->setRGB('ff0000');
		$sheet->getStyle('E1')->getFont()->getColor()->setRGB('ff0000');
		$sheet->getStyle('F1')->getFont()->getColor()->setRGB('ff0000');
		
		// merging
		$event->sheet->mergeCells('A1:A2');
		$event->sheet->mergeCells('B1:D1');
		$event->sheet->mergeCells('E1:E2');
		$event->sheet->mergeCells('F1:F2');
		$event->sheet->mergeCells('G1:G2');
		$event->sheet->mergeCells('H1:H2');
	}
}