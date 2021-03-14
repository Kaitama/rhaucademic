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

use App\Classroom;

class StudentClassroomExport implements WithMultipleSheets
{
	use Exportable;
	
	public function sheets(): array
	{
		$sheets = [];
		$sheets[] = new StudentClassTemplate();
		$sheets[] = new ClassroomData();
		return $sheets;
	}
}

class StudentClassTemplate implements ShouldAutoSize, WithStyles, WithHeadings, WithCustomStartCell, WithTitle, WithEvents, WithColumnFormatting
{
	use RegistersEventListeners;

	public function styles(Worksheet $sheet) { return [1 => ['font' => ['bold' => true, 'size' => 16]]]; }
	
	public function collection(){ return collect(); }

	public function startCell(): string { return 'A1'; }
	
	public function title(): string { return 'DATA KELAS SANTRI'; } 

	public function headings(): array
	{
		return [
			'NO.',
			'ID KELAS',
			'STAMBUK SANTRI',
			'NAMA SANTRI'
		];
	}

	public static function afterSheet(AfterSheet $event)
	{
		$sheet = $event->sheet->getDelegate();
		$sheet->getComment('B1')->getText()->createTextRun('Hanya isi ID KELAS dari sheet DATA KELAS.');
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

class ClassroomData implements FromCollection, WithTitle, WithStyles, WithHeadings, ShouldAutoSize, WithCustomStartCell
{
	
	public function collection()
	{
		//
		$classrooms = Classroom::all();
		foreach ($classrooms as $classroom) {
			unset($classroom['created_at']);
			unset($classroom['updated_at']);
			if($classroom['level'] == 7) $classroom['level'] = 'TINGKAT 1INT';
			elseif($classroom['level'] == 8) $classroom['level'] = 'TINGKAT 3INT';
			else $classroom['level'] = 'TINGKAT ' . $classroom['level'];
		}
		return $classrooms->sortBy('id');
	}
	
	public function startCell(): string { return 'A1'; }
	
	public function headings(): array { return ['ID KELAS', 'TINGKAT', 'NAMA KELAS', 'KAPASITAS'];	}
	
	public function title(): string { return 'DATA KELAS'; }
	
	public function styles(Worksheet $sheet) { return [1 => ['font' => ['bold' => true, 'size' => 16]]]; }
	
}