<?php

namespace App\Imports;

use App\Achievement;
use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Auth;

class AchievementsImport implements ToModel, WithStartRow
{
	use Importable;
	
	
	public function model(array $row)
	{
		$student = Student::where('stambuk', $row[1])->first();
		if($student){
			return new Achievement([
				'student_id' 	=> $student->id,
				'user_id'	=> Auth::id(),
				'date'	=> $row[5] . '-' . $row[4] . '-' . $row[3],
				'name'	=> $row[2],
				'rank'	=> $row[6] ?? null,
				'description'	=> $row[8] ?? null,
				'reward'	=> $row[7] ?? null,
				]
			);		
		}
	}
	
	public function startRow(): int
	{
		return 3;
	}
}