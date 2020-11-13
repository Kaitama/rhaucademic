<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtracurricularsTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('extracurriculars', function (Blueprint $table) {
			$table->id();
			// mentor
			$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->string('name');
			$table->text('description')->nullable();
			$table->integer('day');
			$table->time('time');
			$table->boolean('active')->default(true);
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
		Schema::dropIfExists('extracurriculars');
	}
}