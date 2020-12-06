<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;

class Role extends Model
{
	// use LogsActivity;
	//
	public $table = 'roles';
	protected $guarded = [];

	// protected static $logUnguarded = true;
}