<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAchievementsTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('achievements', function (Blueprint $table) {
			$table->id();
			$table->foreignId('student_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->date('date');
			$table->string('name');
			$table->string('rank')->nullable();
			$table->text('description')->nullable();
			$table->string('reward')->nullable();
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
		Schema::dropIfExists('achievements');
	}
}