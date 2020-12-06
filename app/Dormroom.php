<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Student;

class Dormroom extends Model
{
	//
	use LogsActivity;
	protected $guarded = [];
	protected static $logUnguarded = true;
	
	// relation to students
	public function student()
	{
		return $this->hasMany(Student::class);
	}
	
}