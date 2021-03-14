<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;

use App\Dormroom;
use App\Student;

class DormroomImport implements ToModel, WithStartRow
{
	use Importable;
	
	
	public function model(array $row)
	{
		
		$student = Student::where('stambuk', $row[2])->first();
		if($student){
		$student->update([
			'dormroom_id'	=> $row[1],
		]);
	}
		return $student;
		
		// return new Dormroom([
		// 	'building'	=> $row[1],
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