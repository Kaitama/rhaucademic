<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class Dormroom extends Model
{
	//
	protected $guarded = [];
	
	// relation to students
	public function student()
	{
		return $this->hasMany(Student::class);
	}
	
}