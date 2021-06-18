<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;


class DebugController extends Controller
{
	//
	public function stambuk(){
		$students = Student::all();
		$num = 0;
		foreach ($students as $s) {
			$ns = str_replace('.', '', $s->stambuk);
			$s->update([
				'stambuk'	=> $ns,
			]);
			$num++;
		}
		echo $num;
	}
}