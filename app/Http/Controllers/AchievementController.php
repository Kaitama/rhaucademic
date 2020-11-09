<?php

namespace App\Http\Controllers;

use App\Achievement;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request)
	{
		
		$lists = Student::has('achievement')->get()->sortByDesc(function($ach){
			return $ach->achievement->count();
		});
		$lists = $lists->paginate(10);
		// $lists=null;
		return view('dashboard.nurture.achievement', ['lists' => $lists]);
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
		$this->validate($request, [
			'students'=> 'required',
			'name'		=> 'required',
			'date'		=> 'required'
		], [
			'students.required' => 'Santri tidak boleh kosong.',
			'name.required'	=> 'Nama prestasi tidak boleh kosong.',
			'date.required'	=> 'Tanggal kegiatan tidak boleh kosong.'
			]
		);
		
		$date = explode('/', $request->date);
		$date = $date[2] . '-' . $date[1] . '-' . $date[0];
		$students = explode(',', $request->students);
		foreach ($students as  $id) {
			
			Achievement::create([
				'user_id'			=> Auth::id(),
				'student_id'	=> $id,
				'name'				=> $request->name,
				'date'				=> $date,
				'rank'				=> $request->rank,
				'reward'			=> $request->reward,
				'description'	=> $request->description
				]
			);	
		}
		return back()->with('success', 'Prestasi santri berhasil ditambahkan.');
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\Achievement  $achievement
	* @return \Illuminate\Http\Response
	*/
	public function show(Achievement $achievement)
	{
		//
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Achievement  $achievement
	* @return \Illuminate\Http\Response
	*/
	public function edit(Achievement $achievement)
	{
		//
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Achievement  $achievement
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Achievement $achievement)
	{
		//
		$this->validate($request, [
			'ename'		=> 'required',
			'edate'		=> 'required'
		], [
			'ename.required'	=> 'Nama prestasi tidak boleh kosong.',
			'edate.required'	=> 'Tanggal kegiatan tidak boleh kosong.'
			]);
			$date = explode('/', $request->edate);
			$date = $date[2] . '-' . $date[1] . '-' . $date[0];
			$achievement->find($request->eid)->update([
				'name'				=> $request->ename,
				'date'				=> $date,
				'rank'				=> $request->erank,
				'reward'			=> $request->ereward,
				'description'	=> $request->edescription
				]);
				return back()->with('success', 'Data prestasi santri berhasil diubah.');
			}
			
			/**
			* Remove the specified resource from storage.
			*
			* @param  \App\Achievement  $achievement
			* @return \Illuminate\Http\Response
			*/
			public function destroy(Request $request, Achievement $achievement)
			{
				//
				$achievement->find($request->id)->delete();
				return back()->with('success', 'Data prestasi santri berhasil dihapus.');
			}
		}