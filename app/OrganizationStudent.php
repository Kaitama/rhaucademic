<?php

namespace App;


use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Student;

class OrganizationStudent extends Pivot
{
		//
		public function member()
		{
			return $this->belongsTo(Student::class);
		}
}