<?php

namespace App;

use App\Student;
use App\OrganizationStudent;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
	//
	protected $guarded = [];
	
	public function student()
	{
		return $this->belongsToMany(Student::class)
		->withTimestamps()
		->withPivot(['position', 'description', 'joindate', 'outdate', 'isactive'])
		->as('organization_student');
		// ->using(OrganizationStudent::class);
	}
}