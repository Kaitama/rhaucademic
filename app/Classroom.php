<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class Classroom extends Model
{
	//
	protected $guarded = [];
	
	// relation to students
	public function student()
	{
		return $this->hasMany(Student::class);
	}
	
}