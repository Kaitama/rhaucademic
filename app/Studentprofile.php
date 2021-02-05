<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Student;

class Studentprofile extends Model
{
		//
		use LogsActivity;
		protected $guarded = [];
		protected static $logUnguarded = false;

		// relation to student
		public function student()
		{
			return $this->belongsTo(Student::class);
		}
}