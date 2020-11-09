<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Student;
use App\User;

class Permit extends Model
{
		//
		protected $guarded = [];

		// relation to student
		public function student()
		{
			return $this->belongsTo(Student::class);
		}

		// relation to user
		public function user()
		{
			return $this->belongsTo(User::class);
		}
}