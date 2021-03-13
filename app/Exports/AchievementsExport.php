<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use App\Achievement;
use App\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;

class AchievementsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithColumnFormatting, WithStyles
{
	protected $sdate = null;
	protected $edate = null;
	protected $no = 0;
	
	public function __construct($s, $e)
	{
		$this->sdate = $this->transformDate($s);
		$this->edate = $this->transformDate($e);
	}
	
	public function collection()
	{
		$achievements = Achievement::where('student_id', '!=', null)->where('date', '>=', $this->sdate)->where('date', '<=', $this->edate)->orderBy('date')->get();
		
		foreach ($achievements as $achievement) {
			$achievement->stambuk = $achievement->student['stambuk'];
			$achievement->studentname = $achievement->student['name'];
			$achievement->class = $achievement->student->classroom['name'];
			$achievement->by = $achievement->user['name'];
		}
		return $achievements;
		
	}

	public function map($achievements): array
	{
		
		return [
			++$this->no,
			$this->transformDateTime($achievements->date)->format('d/m/Y'),
			$achievements->stambuk,
			$achievements->studentname,
			$achievements->class,
			$achievements->name,
			$achievements->rank,
			$achievements->reward,
			$achievements->description,
			$achievements->by,
		];
	}

	public function columnFormats(): array
	{
		return [
			'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
		];
	}
	
	public function headings() :array {
		return ['NO', 'TANGGAL', 'STAMBUK', 'NAMA SANTRI', 'KELAS', 'PRESTASI', 'PERINGKAT', 'HADIAH', 'KETERANGAN', 'DITULIS OLEH'];
	}
	
	public function styles(Worksheet $sheet) { 
		return [1 => ['font' => ['bold' => true, 'size' => 14]], ];
	}

	private function transformDate($dt)
	{
		$d = explode('/', $dt);
		return $d[2] . '-' . $d[1] . '-' . $d[0];
	}

	private function transformDateTime(string $value, string $format = 'Y-m-d')
	{
		try {
			return Carbon::instance(Date::excelToDateTimeObject($value))->format($format);
		} catch (\ErrorException $e) {
			return Carbon::createFromFormat($format, $value);
		}
	}
}