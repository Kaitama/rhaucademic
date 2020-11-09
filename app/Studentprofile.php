<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;

class Studentprofile extends Model
{
		//
		protected $guarded = [];

		// relation to student
		public function student()
		{
			return $this->belongsTo(Student::class);
		}
}