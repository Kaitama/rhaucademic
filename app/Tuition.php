<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class Tuition extends Model
{
		//
		protected $guarded = [];

		// relation with student
		public function student()
		{
			return $this->belongsTo(Student::class);
		}
}