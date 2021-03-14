<?php

namespace App\Http\Controllers;

use App\Student;
use App\Dormroom;
use App\Building;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DormroomImport;
use App\Exports\DormroomExport;
use App\Exports\StudentDormroomExport;

class DormroomController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		//
		$dormrooms = Dormroom::orderBy('name')->get();
		return view('dashboard.dormroom.index', ['dormrooms' => $dormrooms]);
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

	public function import(Request $request)
	{
		$this->validate($request, [
			'excel'	=> 'required',
		]);
		Excel::import(new DormroomImport(), $request->file('excel'));
		
		return back()->with('success', 'Data santri di asrama berhasil diimport.');
	}

	public function export(Request $request)
	{
		return Excel::download(new DormroomExport(), 'DATA-ASRAMA-' . date('d-m-Y') . '.xlsx');
	}

	public function template()
	{
		return Excel::download(new StudentDormroomExport(), 'DATA-ASRAMA-SANTRI-' . date('d-m-Y') . '.xlsx');
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
			'dorm_name'	=> 'required',
			'dorm_capacity' => 'required|numeric'
		],[
			'dorm_name.required' => 'Nama asrama tidak boleh kosong.',
			'dorm_capacity.required' => 'Kapasitas asrama tidak boleh kosong.',
			'dorm_capacity.numeric'	=> 'kapasitas asrama hanya boleh angka.'
			]
		);
		
		Dormroom::create([
			'building'		=> $request->building,
			'name'				=> $request->dorm_name,
			'capacity'		=> $request->dorm_capacity
			]
		);
		
		return back()->with('success', 'Data asrama berhasil ditambahkan.');
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\Dormroom  $dormroom
	* @return \Illuminate\Http\Response
	*/
	public function show($id)
	{
		//
		$dorms = Dormroom::all();
		$dormroom = Dormroom::find($id);
		return view('dashboard.dormroom.show', ['dormroom' => $dormroom, 'dorms' => $dorms]);
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Dormroom  $dormroom
	* @return \Illuminate\Http\Response
	*/
	public function edit(Dormroom $dormroom)
	{
		//
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Dormroom  $dormroom
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Dormroom $dormroom)
	{
		//
		$this->validate($request, [
			'name'	=> 'required',
			'capacity' => 'required|numeric'
		],[
			'name.required' => 'Nama kelas tidak boleh kosong.',
			'capacity.required' => 'Kapasitas ruangan kelas tidak boleh kosong.',
			'capacity.numeric'	=> 'kapasitas kelas hanya boleh angka.'
			]
		);
		
		Dormroom::find($request->id)->update([
			'building' => $request->building,
			'name'				=> $request->name,
			'capacity'		=> $request->capacity
			]
		);
		
		return back()->with('success', 'Data kelas berhasil diubah.');
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\Dormroom  $dormroom
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Request $request)
	{
		//
		Dormroom::find($request->id)->delete();
		return back()->with('success', 'Data asrama berhasil dihapus.');
	}


	public function addstudents(Request $r)
	{
		$this->validate($r, ['students' => 'required'], ['students.required' => 'Santri tidak boleh kosong.']);
		$students = explode(',', $r->students);
		foreach ($students as $std) {
			Student::find($std)->update(['dormroom_id' => $r->id]);
		}
		return back()->with('success', 'Santri berhasil ditambahkan ke dalam asrama.');
	}

	public function removestudent(Request $r)
	{
		Student::find($r->idtoremove)->update(['dormroom_id' => $r->change ? $r->dorm : null]);
		return back()->with('success', 'Santri berhasil dikeluarkan dari asrama.');
	}

}