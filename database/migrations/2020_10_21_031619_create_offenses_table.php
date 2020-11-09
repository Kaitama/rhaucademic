<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffensesTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('offenses', function (Blueprint $table) {
			$table->id();
			$table->foreignId('student_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->date('date');
			$table->string('name');
			$table->text('punishment')->nullable();
			$table->text('notes')->nullable();
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
		Schema::dropIfExists('offenses');
	}
}