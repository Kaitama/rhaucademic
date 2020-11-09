<?php

namespace App\Http\Controllers;

use App\Offense;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffenseController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request)
	{
		//
		$lists = Student::has('offense')->get()->sortByDesc(function($off){
			return $off->offense->count();
		});
		$lists = $lists->paginate(10);
		// $lists=null;
		return view('dashboard.nurture.offense', ['lists' => $lists]);
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
			'name'		=> 'required',
			'students'=> 'required',
			'date'		=> 'required'
		],[
			'students.required' => 'Santri tidak boleh kosong.',
			'name.required'			=> 'Nama pelanggaran tidak boleh kosong.',
			'date.required'			=> 'Tanggal tidak boleh kosong.'
			]
		);
		$students = explode(',', $request->students);
		$date = explode('/', $request->date);
		$date = $date[2] . '-' . $date[1] . '-' . $date[0];
		foreach ($students as $id) {
			Offense::create([
				'student_id' 	=> $id,
				'user_id'			=> Auth::id(),
				'name'				=> $request->name,
				'date'				=> $date,
				'punishment'	=> $request->punishment,
				'notes'				=> $request->notes
				]
			);
		}
		return back()->with('success', 'Pelanggaran santri berhasil disimpan.');
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\Offense  $offense
	* @return \Illuminate\Http\Response
	*/
	public function show(Offense $offense)
	{
		//
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Offense  $offense
	* @return \Illuminate\Http\Response
	*/
	public function edit(Offense $offense)
	{
		//
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Offense  $offense
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Offense $offense)
	{
		//
		$this->validate($request, [
			'ename'		=> 'required',
			'edate'		=> 'required'
		],[
			'ename.required'			=> 'Nama pelanggaran tidak boleh kosong.',
			'edate.required'			=> 'Tanggal tidak boleh kosong.'
			]
		);
		$date = explode('/', $request->edate);
		$date = $date[2] . '-' . $date[1] . '-' . $date[0];
		$offense->find($request->eid)->update([
			'name'				=> $request->ename,
			'date'				=> $date,
			'punishment'	=> $request->epunishment,
			'notes'				=> $request->enotes
			]
		);

		return back()->with('success', 'Data pelanggaran berhasil diubah.');
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\Offense  $offense
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Request $request, Offense $offense)
	{
		//
		$offense->find($request->id)->delete();
		return back()->with('success', 'Data pelanggaran berhasil dihapus.');
	}
}