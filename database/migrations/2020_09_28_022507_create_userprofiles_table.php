<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserprofilesTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('userprofiles', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
			$table->string('phone')->nullable();
			$table->string('ftitle')->nullable();
			$table->string('ltitle')->nullable();
			$table->boolean('gender')->default(0);
			$table->date('birthdate')->nullable();
			$table->string('birthplace')->nullable();
			$table->string('position')->nullable();
			$table->text('address')->nullable();
			$table->year('since')->nullable();
			$table->integer('education')->nullable();
			$table->string('school')->nullable();
			$table->string('major')->nullable();
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
		Schema::dropIfExists('userprofiles');
	}
}