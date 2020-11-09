<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		/**
		* LEVEL
		* 1 Developer
		* 2 Administrator
		* 3 Staff Admin
		* 4 Teacher
		* 5 Unsigned User
		* --
		* 9 Unsigned User
		*/
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->integer('level')->default(5);
			$table->string('name');
			$table->string('username')->unique();
			$table->string('email')->unique();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->string('photo')->nullable();
			$table->rememberToken();
			$table->timestamps();
		});
	}
	
	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down()
	{
		Schema::dropIfExists('users');
	}
}