<?php

namespace App\Imports;

use App\Offense;
use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Auth;


class OffensesImport implements ToModel, WithStartRow
{
	use Importable;
	
	
	public function model(array $row)
	{
		$student = Student::where('stambuk', $row[4])->first();
		if($student){
			return new Offense([
				'student_id' 	=> $student->id,
				'user_id'	=> Auth::id(),
				'date'	=> $row[3] . '-' . $row[2] . '-' . $row[1],
				'name'	=> $row[5],
				'punishment'	=> $row[6],
				'notes'	=> $row[7] ?? null,
				]
			);		
		}
	}
	
	public function startRow(): int
	{
		return 3;
	}
}