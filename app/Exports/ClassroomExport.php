<?php

namespace App\Exports;

use App\Classroom;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;

class ClassroomExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithStyles
{
	private $no = 0;
	public function collection()
	{
		return Classroom::orderBy('level')->get();
		
	}

	public function map($classrooms): array
	{
		
		return [
			++$this->no,
			$classrooms->level,
			$classrooms->name,
			$classrooms->capacity,
			$classrooms->student->count(),
			$classrooms->capacity - $classrooms->student->count(),
		];
	}

	
	public function headings() :array {
		return ['NO', 'TINGKAT', 'NAMA KELAS', 'KAPASITAS SANTRI', 'TERISI', 'KOSONG'];
	}
	
	public function styles(Worksheet $sheet) { 
		return [1 => ['font' => ['bold' => true, 'size' => 14]], ];
	}

}