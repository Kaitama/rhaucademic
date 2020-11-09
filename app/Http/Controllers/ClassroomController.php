<?php

namespace App\Http\Controllers;

use App\Building;
use App\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		//
		// $buildings = Building::where('group', 'classroom')->get();
		$classrooms = Classroom::orderBy('level')->get();
		return view('dashboard.classroom.index', ['classrooms' => $classrooms]);
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
			'level' => 'required',
			'class_name'	=> 'required',
			'class_capacity' => 'required|numeric'
		],[
			'level.required' => 'Tingkatan kelas harus dipilih.',
			'class_name.required' => 'Nama kelas tidak boleh kosong.',
			'class_capacity.required' => 'Kapasitas kelas tidak boleh kosong.',
			'class_capacity.numeric'	=> 'kapasitas kelas hanya boleh angka.'
		]);

			Classroom::create([
				'level'		=> $request->level,
				'name'		=> $request->class_name,
				'capacity'=> $request->class_capacity
			]);

			return back()->with('success', 'Data kelas berhasil ditambahkan.');
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\Classroom  $classroom
	* @return \Illuminate\Http\Response
	*/
	public function show(Classroom $classroom)
	{
		//
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Classroom  $classroom
	* @return \Illuminate\Http\Response
	*/
	public function edit(Classroom $classroom)
	{
		//
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Classroom  $classroom
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Classroom $classroom)
	{
		//
		$this->validate($request, [
			'level' => 'required',
			'name'	=> 'required',
			'capacity' => 'required|numeric'
		],[
			'level.required' => 'Tingkatan kelas harus dipilih.',
			'name.required' => 'Nama kelas tidak boleh kosong.',
			'capacity.required' => 'Kapasitas ruangan kelas tidak boleh kosong.',
			'capacity.numeric'	=> 'kapasitas kelas hanya boleh angka.'
		]);

			Classroom::find($request->id)->update([
				'level' 			=> $request->level,
				'name'				=> $request->name,
				'capacity'		=> $request->capacity
			]);

			return back()->with('success', 'Data kelas berhasil diubah.');
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\Classroom  $classroom
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Request $request)
	{
		//
		Classroom::find($request->id)->delete();
		return back()->with('success', 'Data kelas berhasil dihapus.');
		
	}
}