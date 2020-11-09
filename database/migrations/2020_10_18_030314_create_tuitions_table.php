<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTuitionsTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('tuitions', function (Blueprint $table) {
			$table->id();
			$table->foreignId('student_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->date('paydate');
			$table->integer('formonth');
			$table->integer('foryear');
			$table->bigInteger('nominal');
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
		Schema::dropIfExists('tuitions');
	}
}