<?php

namespace App;
use App\User;
use App\Student;


use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Extracurricular extends Model
{
	//
	use LogsActivity;
	protected $guarded = [];
	protected static $logUnguarded = true;
	protected static $logName = 'ekstrakurikuler';

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