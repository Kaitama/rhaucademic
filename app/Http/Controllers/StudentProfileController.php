<?php

namespace App\Http\Controllers;

use App\Student;
use App\Studentprofile;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		//
	}
	
	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
		//
	}
	
	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request)
	{
		//
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\StudentProfile  $studentProfile
	* @return \Illuminate\Http\Response
	*/
	public function show(StudentProfile $studentProfile)
	{
		//
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\StudentProfile  $studentProfile
	* @return \Illuminate\Http\Response
	*/
	public function edit(StudentProfile $studentProfile)
	{
		//
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\StudentProfile  $studentProfile
	* @return \Illuminate\Http\Response
	*/


	private function form2($r)
	{		
	
		return Studentprofile::where('student_id', $r->id)->update([
			'nickname' => $r->nickname,
			'nisn' => $r->nisn,
			'blood'	=> $r->blood,
			'height'	=> $r->height,
			'weight'	=> $r->weight,
			'hobby'	=> $r->hobby ? implode(', ', $r->hobby) : null,
			'wishes'	=> $r->wishes ? implode(', ', $r->wishes) : null,
			'achievement'	=> $r->achievement,
			'competition'	=> $r->competition,
			'numposition'	=> $r->numposition ?? 1,
			'siblings'	=> $r->siblings,
			'stepsiblings'	=> $r->stepsiblings
		]);
	}

	private function form3($r)
	{
		return Studentprofile::where('student_id', $r->id)->update([
			'fktp'	=> $r->fktp,
			'fname'	=> $r->fname,
			'flive'	=> $r->flive,
			'fphone'	=> $r->fphone,
			'fwa'	=> $r->fwa,
			'fadd'	=> $r->fadd,
			'fedu'	=> $r->fedu,
			'freligion'	=> $r->freligion,
			'fwork'	=> $r->fwork,
			'fsalary'	=> $r->fsalary,
			'faddsalary'	=> $r->faddsalary,
			'mariage'	=> $r->mariage,
			'mktp'	=> $r->mktp,
			'mname'	=> $r->mname,
			'mlive'	=> $r->mlive,
			'mphone'	=> $r->mphone,
			'mwa'	=> $r->mwa,
			'madd'	=> $r->madd,
			'medu'	=> $r->medu,
			'mreligion'	=> $r->mreligion,
			'mwork'	=> $r->mwork,
			'msalary'	=> $r->msalary,
			'maddsalary'	=> $r->maddsalary,
			'donatur'	=> $r->donatur == 'on' ? false : true,
		]);
	}

	private function form4($r)
	{
		return Studentprofile::where('student_id', $r->id)->update([
			'dname'	=> $r->dname,
			'drelation'	=> $r->drelation,
			'dphone'	=> $r->dphone,
			'dadd'	=> $r->dadd,
		]);
	}

	private function form5($r)
	{
		return Studentprofile::where('student_id', $r->id)->update([
			'transfer' => $r->transfer == 'on' ? true : false,
			'sfrom' => $r->sfrom,
			'slevel'	=> $r->slevel,
			'sname'	=> $r->transfer == 'on' ? null : $r->sname,
			'sadd'	=> $r->transfer == 'on' ? null : $r->sadd,
			'snpsn'	=> $r->snpsn,
			'sun'	=> $r->sun,
			'sijazah'	=> $r->sijazah,
			'sskhun'	=> $r->sskhun,
			'pfrom'	=> $r->transfer == 'on' ? $r->sname : null,
			'padd'	=> $r->transfer == 'on' ? $r->sadd : null,
			'preason'	=> $r->transfer == 'on' ? $r->preason : null,
			'pdescription'	=> $r->transfer == 'on' ? $r->pdescription : null,
		]);
	}

	public function update(Request $r)
	{
		//
		
		if($r->command == 2)
		{
			$this->form2($r);
		} 
		elseif($r->command == 3)
		{
			$this->form3($r);
		} 
		elseif($r->command == 4)
		{
			$this->form4($r);
		}
		elseif($r->command == 5) 
		{
			$this->form5($r);
		} else {
			return back();
		}
		return back()->with('success', 'Profil santri berhasil diubah.');
		
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\StudentProfile  $studentProfile
	* @return \Illuminate\Http\Response
	*/
	public function destroy(StudentProfile $studentProfile)
	{
		//
	}
}