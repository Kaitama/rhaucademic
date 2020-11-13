<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use App\Userprofile;
use App\Offense;
use App\Achievement;
use App\Permit;
use App\Student;
use App\Extracurricular;

class User extends Authenticatable
{
	use HasApiTokens, Notifiable, HasRoles;
	
	/**
	* The attributes that are mass assignable.
	*
	* @var array
	*/
	protected $fillable = [
		'level', 'name', 'username', 'email', 'password', 'photo'
	];
	
	/**
	* The attributes that should be hidden for arrays.
	*
	* @var array
	*/
	protected $hidden = [
		'password', 'remember_token',
	];
	
	/**
	* The attributes that should be cast to native types.
	*
	* @var array
	*/
	protected $casts = [
		'email_verified_at' => 'datetime',
	];
	
	/**
	* Relationships
	*/
	public function userprofile()
	{
		return $this->hasOne(Userprofile::class);
	}
	
	public function achievement()
	{
		return $this->hasMany(Achievement::class);
	}
	public function offense()
	{
		return $this->hasMany(Offense::class);
	}
	
	// relation to permit
	public function permit()
	{
		return $this->hasMany(Permit::class);
	}

	// relation to student
	public function student()
	{
		return $this->hasOne(Student::class);
	}

	// relation to extracurricular
	public function extracurricular()
	{
		return $this->hasMany(Extracurricular::class);
	}
}