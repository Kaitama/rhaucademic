<?php

namespace App\Exports;

use App\Dormroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;

class DormroomExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithStyles
{
	private $no = 0;
	public function collection()
	{
		return Dormroom::orderBy('building')->get();
		
	}

	public function map($dormrooms): array
	{
		
		return [
			++$this->no,
			$dormrooms->building,
			$dormrooms->name,
			$dormrooms->capacity,
			$dormrooms->student->count(),
			$dormrooms->capacity - $dormrooms->student->count(),
		];
	}

	
	public function headings() :array {
		return ['NO', 'NAMA GEDUNG', 'NAMA ASRAMA', 'KAPASITAS SANTRI', 'TERISI', 'KOSONG'];
	}
	
	public function styles(Worksheet $sheet) { 
		return [1 => ['font' => ['bold' => true, 'size' => 14]], ];
	}

}