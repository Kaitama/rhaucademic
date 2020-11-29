<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationStudentTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('organization_student', function (Blueprint $table) {
			$table->id();
			$table->foreignId('organization_id')->constrained();
			$table->foreignId('student_id')->constrained();
			$table->integer('position')->default(5);
			$table->text('description')->nullable();
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
		Schema::dropIfExists('organization_student');
	}
}