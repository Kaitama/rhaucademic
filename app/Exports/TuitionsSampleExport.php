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

class TuitionsSampleExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
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
			'No.',
			'Tanggal Bayar',
			'Stambuk',
			'Pembayaran Bulan',
			'Tahun',
			'Nominal'
		];
	}

	public function styles(Worksheet $sheet)
	{
		return [
			1    => ['font' => ['bold' => true, 'size' => 14]],
			'A1:F1' => ['alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
			],]
		];
	}

	public static function afterSheet(AfterSheet $event)
	{
		$sheet = $event->sheet->getDelegate();
		$sheet->getComment('D1')->getText()->createTextRun('Hanya diisi angka bulan 1 s/d 12.');
		$sheet->getComment('E1')->getText()->createTextRun('Hanya diisi 4 digit angka tahun.');
		$sheet->getComment('F1')->getText()->createTextRun('Hanya diisi angka tanpa simbol lainnya.');
		$sheet->getRowDimension('1')->setRowHeight(36);
	}
}