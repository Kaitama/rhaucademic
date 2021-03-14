<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Dormroom;

class StudentDormroomExport implements WithMultipleSheets
{
	use Exportable;
	
	public function sheets(): array
	{
		$sheets = [];
		$sheets[] = new StudentDormTemplate();
		$sheets[] = new DormroomData();
		return $sheets;
	}
}

class StudentDormTemplate implements ShouldAutoSize, WithStyles, WithHeadings, WithCustomStartCell, WithTitle, WithEvents, WithColumnFormatting
{
	use RegistersEventListeners;

	public function styles(Worksheet $sheet) { return [1 => ['font' => ['bold' => true, 'size' => 16]]]; }
	
	public function collection(){ return collect(); }

	public function startCell(): string { return 'A1'; }
	
	public function title(): string { return 'DATA ASRAMA SANTRI'; } 

	public function headings(): array
	{
		return [
			'NO.',
			'ID ASRAMA',
			'STAMBUK SANTRI',
			'NAMA SANTRI'
		];
	}

	public static function afterSheet(AfterSheet $event)
	{
		$sheet = $event->sheet->getDelegate();
		$sheet->getComment('B1')->getText()->createTextRun('Hanya isi ID ASRAMA dari sheet DATA ASRAMA.');
		$sheet->getComment('C1')->getText()->createTextRun('Hanya diisi stambuk santri.');
		$sheet->getComment('D1')->getText()->createTextRun('Nama santri boleh dikosongkan.');
		
		$sheet->getStyle('B1:C1')->getFont()->getColor()->setRGB('ff0000');
		$sheet->getStyle('A1:D1')->getBorders()->getAllBorders()
		->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
		$sheet->getRowDimension('1')->setRowHeight(36);
	}

	public function columnFormats(): array
    {
        return [
            // 'J' => NumberFormat::FORMAT_TEXT,
            // 'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        ];
    }
	
}

class DormroomData implements FromCollection, WithTitle, WithStyles, WithHeadings, ShouldAutoSize, WithCustomStartCell
{
	
	public function collection()
	{
		//
		$dormrooms = Dormroom::all();
		foreach ($dormrooms as $dormroom) {
			unset($dormroom['created_at']);
			unset($dormroom['updated_at']);
		}
		return $dormrooms->sortBy('id');
	}
	
	public function startCell(): string { return 'A1'; }
	
	public function headings(): array { return ['ID ASRAMA', 'NAMA GEDUNG', 'NAMA ASRAMA', 'KAPASITAS'];	}
	
	public function title(): string { return 'DATA ASRAMA'; }
	
	public function styles(Worksheet $sheet) { return [1 => ['font' => ['bold' => true, 'size' => 16]]]; }
	
}