<?php

namespace App;
use App\User;
use App\Student;


use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
	//
	protected $guarded = [];
	
	// relation to user
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	// relation to student
	public function student()
	{
		return $this->belongsToMany(Student::class)
		->withTimestamps()
		->withPivot(['joindate', 'outdate', 'isactive'])
		->as('extracurricular_student');
	}
}