<?php

namespace App\Imports;

use App\Tuition;
use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TuitionsImport implements ToModel, WithStartRow
{
	use Importable;
	
	private $rows = 0;
	private $fail = 0;
	private $sbks = array();
	
	public function model(array $row)
	{
		$student = $this->check($row[2]);
		if($student){
			$duplicate = $this->duplicate($student->id, $row[3], $row[4]);
			if($duplicate){
				++$this->fail;
				array_push($this->sbks, $row[2]);
				return null;
			} else {
				++$this->rows;
				return new Tuition([
					'student_id' 	=> $student->id,
					'paydate'			=> $this->transformDate($row[1]),
					'formonth'		=> $row[3],
					'foryear'			=> $row[4],
					'nominal'			=> $row[5]
					]
				);
			}
		}
		else {
			++$this->fail;
			array_push($this->sbks, $row[2]);
			return null;
		}
		
		// return array($good, $fail);
	}
	
	public function getRowCount(): int
	{
		return $this->rows;
	}
	public function getFailCount(): int
	{
		return $this->fail;
	}
	public function failEntry(): array
	{
		return $this->sbks;
	}
	
	public function transformDate($value, $format = 'Y-m-d')
	{
		try {
			return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
		} catch (\ErrorException $e) {
			return \Carbon\Carbon::createFromFormat($format, $value);
		}
	}
	
	private function check($stambuk)
	{
		return Student::where('stambuk', $stambuk)->first();
	}
	
	private function duplicate($id, $month, $year)
	{
		return Tuition::where('student_id', $id)->where('formonth', $month)->where('foryear', $year)->first();
	}
	
	public function startRow(): int
	{
		return 2;
	}
}