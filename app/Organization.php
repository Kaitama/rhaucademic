<?php

namespace App;

use App\Student;
use App\OrganizationStudent;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Organization extends Model
{
	//
	use LogsActivity;
	protected $guarded = [];
	protected static $logUnguarded = true;
	protected static $logName = 'organisasi';

	public function student()
	{
		return $this->belongsToMany(Student::class)
		->withTimestamps()
		->withPivot(['position', 'description', 'joindate', 'outdate', 'isactive'])
		->as('organization_student');
		// ->using(OrganizationStudent::class);
	}
}