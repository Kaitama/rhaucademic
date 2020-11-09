<?php

namespace App\Imports;

use App\Student;
use App\Studentprofile;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class StudentsImport implements WithMultipleSheets
{
	public function sheets(): array
	{
		return [
			0 => new FirstSheetImport(),
		];
	}
	
}

class FirstSheetImport implements ToModel, WithStartRow
{
	public function model(array $row)
	{
		if($this->check($row[1])) {
			$this->check($row[1]);
		} else {
			$student = Student::create([
				// data primer
				'stambuk' => $row[1],
				'name' => $row[2],
				'nokk' => $row[3],
				'nik' => $row[4],
				'birthdate' => $this->transformDate($row[5]),
				'birthplace' => $row[6],
				'gender' => $row[7],
				'classroom_id' => $row[8],
				'dormroom_id' => $row[9]
				]
			);
			// dd($student);
			return new Studentprofile([
				'student_id' => $student->id,
				'nickname'	=> $row[10],
				'nisn' => $row[11],
				'blood' => $row[12],
				'weight' => $row[13],
				'height' => $row[14],
				'hobby' => $row[15],
				'wishes' => $row[16],
				'achievement' => $row[17],
				'competition' => $row[18],
				'numposition' => $row[19] ? $row[19] : 1,
				'siblings' => $row[20] ? $row[20] : 0,
				'stepsiblings' => $row[21] ? $row[21] : 0,
				'fname' => $row[22],
				'fktp' => $row[23],
				'flive' => $row[24] == 'Y' ? false : true,
				'fphone' => $row[25],
				'fwa' => $row[26],
				'fadd' => $row[27],
				'fedu' => $row[28],
				'freligion' => $row[29],
				'fwork' => $row[30],
				'fsalary' => $row[31],
				'faddsalary' => $row[32],
				'mariage' => $row[33] == 'Y' ? false : true,
				'mname' => $row[34],
				'mktp' => $row[35],
				'mlive' => $row[36] == 'Y' ? false : true,
				'mphone' => $row[37],
				'mwa' => $row[38],
				'madd' => $row[39],
				'medu' => $row[40],
				'mreligion' => $row[41],
				'mwork' => $row[42],
				'msalary' => $row[43],
				'maddsalary' => $row[44],
				'donatur' => $row[45] == null ? false : true,
				'dname' => $row[45],
				'drelation' => $row[46],
				'dphone' => $row[47],
				'dadd' => $row[48],
				'sfrom' => $row[49],
				'slevel' => $row[50] == 'T' ? 'SWASTA' : 'NEGERI',
				'sname' => $row[51],
				'sadd' => $row[52],
				'snpsn' => $row[53],
				'sun' => $row[54],
				'sijazah' => $row[55],
				'sskhun' => $row[56],
				'transfer' => $row[57] == 'Y' ? true : false,
				'pfrom' => $row[58],
				'padd' => $row[59],
				'preason' => $row[60],
				'pdescription' => $row[61],
				]
			);
		}
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
	
	public function startRow(): int
	{
		return 3;
	}
}