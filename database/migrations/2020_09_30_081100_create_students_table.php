<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('students', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
			$table->foreignId('classroom_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->foreignId('dormroom_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->string('stambuk')->unique();
			$table->string('nik')->unique();
			$table->string('nokk');
			$table->string('name');
			$table->date('birthdate');
			$table->string('birthplace');
			$table->string('gender');
			$table->integer('status')->default(1);
			$table->string('photo')->nullable();
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
		Schema::dropIfExists('students');
	}
}