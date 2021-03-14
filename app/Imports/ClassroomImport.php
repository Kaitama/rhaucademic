<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Classroom;
use App\Student;

class ClassroomImport implements ToModel, WithStartRow
{
	use Importable;
	
	
	public function model(array $row)
	{
		$student = Student::where('stambuk', $row[2])->first();
		if($student){
		$student->update([
			'classroom_id'	=> $row[1],
		]);
	}
		return $student;
		// $level = $row[1];
		// if($level == '1INT') $level = 7;
		// if($level == '3INT') $level = 8;
		// return new Classroom([
		// 	'level'	=> $level,
		// 	'name'	=> $row[2],
		// 	'capacity'	=> $row[3],
		// 	]
		// );		
	}
	
	public function startRow(): int
	{
		return 2;
	}
}