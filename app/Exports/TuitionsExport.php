<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use App\Tuition;
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

class TuitionsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithColumnFormatting, WithStyles
{
	protected $month = null; 
	protected $year = null;
	protected $no = 0;
	
	public function __construct($m, $y)
	{
		$this->month = $m;
		$this->year = $y;
	}
	/**
	* @return \Illuminate\Support\Collection
	*/
	public function collection()
	{
		$tuitions = null;
		
		if($this->month == 'all'){
			if($this->year){
				$tuitions = Tuition::where('foryear', $this->year)->get();
			} else {
				$tuitions = Tuition::get();
			}
		} else {
			if($this->year){
				$tuitions = Tuition::where('formonth', $this->month)->where('foryear', $this->year)->get();
			} else {
				$tuitions = Tuition::where('formonth', $this->month)->get();
			}
		}
		
		foreach ($tuitions as $tuition) {
			$tuition->stambuk = $tuition->student['stambuk'];
			$tuition->name = $tuition->student['name'];
			$tuition->class = $tuition->student->classroom['name'];
		}
		return $tuitions;
		
	}
	
	public function map($tuitions): array
	{
		$fordate = $tuitions->foryear . '-' . $tuitions->formonth . '-20';
		$fordate = $this->transformDateTime($fordate)->format('m/Y');
		
		return [
			++$this->no,
			$this->transformDateTime($tuitions->paydate)->format('d/m/Y'),
			$tuitions->stambuk,
			$tuitions->name,
			$tuitions->class,
			// $tuitions->formonth . '/' . $tuitions->foryear,
			$fordate,
			$tuitions->nominal
		];
	}
	
	public function columnFormats(): array
	{
		return [
			'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
			'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
			'G' => NumberFormat::FORMAT_ACCOUNTING_IDR,
		];
	}
	
	public function headings() :array {
		return ['No.', 'Tanggal Bayar', 'Stambuk', 'Nama Santri', 'Kelas', 'Pembayaran Bulan', 'Nominal'];
	}
	
	public function styles(Worksheet $sheet) { 
		return [1 => ['font' => ['bold' => true, 'size' => 14]], ];
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