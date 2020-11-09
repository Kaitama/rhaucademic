<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentprofilesTable extends Migration
{
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up()
	{
		Schema::create('studentprofiles', function (Blueprint $table) {
			$table->id();
			// profile
			$table->foreignId('student_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
			$table->string('nickname')->nullable();
			$table->string('nisn')->nullable();
			$table->string('blood')->nullable();
			$table->string('weight')->nullable();
			$table->string('height')->nullable();
			$table->string('hobby')->nullable();
			$table->string('wishes')->nullable();
			$table->string('achievement')->nullable();
			$table->text('competition')->nullable();
			$table->integer('numposition')->default(1);
			$table->integer('siblings')->default(0)->nullable();
			$table->integer('stepsiblings')->default(0)->nullable();
			// father
			$table->string('fktp')->nullable();
			$table->string('fname')->nullable();
			$table->boolean('flive')->default(true);
			$table->string('fphone')->nullable();
			$table->string('fwa')->nullable();
			$table->text('fadd')->nullable();
			$table->string('fedu')->nullable();
			$table->string('freligion')->nullable();
			$table->string('fwork')->nullable();
			$table->string('fsalary')->nullable();
			$table->string('faddsalary')->nullable();
			$table->boolean('mariage')->default(true);
			// mother
			$table->string('mktp')->nullable();
			$table->string('mname')->nullable();;
			$table->boolean('mlive')->default(true);
			$table->string('mphone')->nullable();
			$table->string('mwa')->nullable();
			$table->text('madd')->nullable();
			$table->string('medu')->nullable();
			$table->string('mreligion')->nullable();
			$table->string('mwork')->nullable();
			$table->string('msalary')->nullable();
			$table->string('maddsalary')->nullable();
			// donatur
			$table->boolean('donatur')->default(false);
			$table->string('dname')->nullable();
			$table->string('drelation')->nullable();
			$table->string('dphone')->nullable();
			$table->text('dadd')->nullable();
			// school
			$table->string('sfrom')->nullable();
			$table->string('slevel')->nullable();
			$table->string('sname')->nullable();
			$table->text('sadd')->nullable();
			$table->string('snpsn')->nullable();
			$table->string('sun')->nullable();
			$table->string('sijazah')->nullable();
			$table->string('sskhun')->nullable();
			$table->boolean('transfer')->default(false);
			$table->string('pfrom')->nullable();
			$table->text('padd')->nullable();
			$table->string('preason')->nullable();
			$table->text('pdescription')->nullable();
			
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
		Schema::dropIfExists('studentprofiles');
	}
}