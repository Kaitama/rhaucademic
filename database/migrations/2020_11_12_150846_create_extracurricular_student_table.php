<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtracurricularStudentTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('extracurricular_student', function (Blueprint $table) {
			$table->id();
			$table->foreignId('extracurricular_id')->constrained();
			$table->foreignId('student_id')->constrained();
			$table->date('joindate');
			$table->date('outdate')->nullable();
			$table->boolean('isactive')->default(true);
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
		Schema::dropIfExists('extracurricular_student');
	}
}