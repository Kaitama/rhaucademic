<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermitsTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('permits', function (Blueprint $table) {
			$table->id();
			$table->foreignId('student_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->cascadeOnUpdate();
			$table->dateTime('datefrom');
			$table->dateTime('dateto');
			$table->string('reason');
			$table->text('description')->nullable();
			$table->dateTime('signdate');
			$table->string('signature');
			$table->timestamp('checkout')->nullable();
			$table->bigInteger('checkedout_by')->nullable();
			$table->timestamp('checkin')->nullable();
			$table->bigInteger('checkedin_by')->nullable();
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
		Schema::dropIfExists('permits');
	}
}