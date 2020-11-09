<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Studentprofile;
use App\Classroom;
use App\Dormroom;
use App\Tuition;
use App\Achievement;
use App\Offense;
use App\Permit;
use App\Client;
use App\User;

class Student extends Model
{
	//
	protected $guarded = [];
	
	// relation to studentprofile
	public function studentprofile()
	{
		return $this->hasOne(StudentProfile::class);
	}

	// relation to classroom
	public function classroom()
	{
		return $this->belongsTo(Classroom::class);
	} 

	// relation to dormroom
	public function dormroom()
	{
		return $this->belongsTo(Dormroom::class);
	}

	// relation to tuition
	public function tuition()
	{
		return $this->hasMany(Tuition::class);
	}

	// relation to achievement
	public function achievement()
	{
		return $this->hasMany(Achievement::class);
	}

	// relation to offense
	public function offense()
	{
		return $this->hasMany(Offense::class);
	}

	// relation to permit
	public function permit()
	{
		return $this->hasMany(Permit::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}