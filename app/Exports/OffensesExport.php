<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use App\Offense;
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
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class OffensesExport extends DefaultValueBinder implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithColumnFormatting, WithStyles, WithCustomValueBinder
{
	use RegistersEventListeners;

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
		$offenses = Offense::where('student_id', '!=', null)->where('date', '>=', $this->sdate)->where('date', '<=', $this->edate)->orderBy('date')->get();
		
		foreach ($offenses as $offense) {
			$offense->class = null;
			$offense->stambuk = $offense->student['stambuk'];
			$offense->studentname = $offense->student['name'];
			if($offense->student) $offense->class = $offense->student->classroom['name'];
			$offense->by = $offense->user['name'];
		}
		return $offenses;
		
	}

	public function map($offenses): array
	{
		
		return [
			++$this->no,
			$this->transformDateTime($offenses->date)->format('d/m/Y'),
			$offenses->stambuk,
			$offenses->studentname,
			$offenses->class,
			$offenses->name,
			$offenses->punishment,
			$offenses->notes,
			$offenses->by,
		];
	}

	public function columnFormats(): array
	{
		return [
			'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
		];
	}

	public function bindValue(Cell $cell, $value)
	{
		
		if(strlen((string)$value) > 14){ // if (is_numeric($value)) {
			$cell->setValueExplicit($value, DataType::TYPE_STRING);
			return true;
		}

		if (is_numeric($value)) {
			$cell->setValueExplicit($value, DataType::TYPE_STRING);
			return true;
		}
		
		// else return default behavior
		return parent::bindValue($cell, $value);
	}
	
	public function headings() :array {
		return ['NO', 'TANGGAL', 'STAMBUK', 'NAMA SANTRI', 'KELAS', 'PELANGGARAN', 'HUKUMAN', 'CATATAN', 'DITULIS OLEH'];
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