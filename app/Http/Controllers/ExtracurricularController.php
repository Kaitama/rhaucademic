<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Extracurricular;
use App\Student;
use App\User;

class ExtracurricularController extends Controller
{
	//
	public function index()
	{
		$extras = Extracurricular::all();
		
		list($actives, $inactives) = $extras->partition(function ($i) {
			return $i->active;
		});
		
		return view('dashboard.extracurricular.index', ['actives' => $actives, 'inactives' => $inactives]);
	}
	
	// 
	public function store(Request $r)
	{
		$this->validate($r, [
			'name'		=> 'required',
			'mentor'	=> 'required',
			'time'		=> 'required|size:5'
		], [
			'name.required'		=> 'Nama ekstrakurikuler tidak boleh kosong.',
			'mentor.required'	=> 'Mentor / Pembina ekstrakurikuler tidak boleh kosong.',
			'time.required'		=> 'Jam pelaksanaan tidak boleh kosong.',
			'time.size'				=> 'Format jam salah.',
			]
		);
		
		Extracurricular::create([
			'name'				=> $r->name,
			'description'	=> $r->description,
			'user_id'			=> $r->mentor,
			'day'					=> $r->day,
			'time'				=> $r->time,
			]
		);
		return back()->with('success', 'Data ekstrakurikuler berhasil disimpan.');
	}
	
	// 
	public function toggle(Request $r)
	{
		$id = $r->toggle_id;
		if($r->toggle_cmd == 'd'){
			Extracurricular::find($id)->update(['active' => false]);
			return back()->with('success', 'Data ekstrakurikuler berhasil dinonaktifkan.');
		}else {
			Extracurricular::find($id)->update(['active' => true]);
			return back()->with('success', 'Data ekstrakurikuler berhasil diaktifkan.');
		}
	}
	
	// 
	public function show($id)
	{
		$extra = Extracurricular::find($id);
		
		return view('dashboard.extracurricular.show', ['extra' => $extra]);
	}
	
	// 
	public function addstudents(Request $r)
	{
		$this->validate($r, [
			'students'		=> 'required',
			'joindate'		=> 'required',
		], [
			'students.required'	=> 'Nama santri tidak boleh kosong.',
			'joindate.required'	=> 'Tanggal tidak boleh kosong.',
			]
		);
		$ext = Extracurricular::find($r->id);
		$students = explode(',',$r->students);
		$joindate = $this->convertDate($r->joindate);
		foreach ($students as $std) {
			$student = Student::find($std);
			if($student->extracurricular()->wherePivot('extracurricular_id', $r->id)->wherePivot('isactive', true)->exists()){
				return back()->with('error', $student->name . ' sudah terdaftar sebagai sebagai anggota.');
			}
			$ext->student()->attach($std, ['joindate' => $joindate, 'outdate' => $r->active ? null : date('Y-m-d'), 'isactive' => $r->active]);
		}
		return back()->with('success', 'Anggota ekstrakurikuler berhasil ditambahkan.');
	}
	
	// 
	public function toggleisactive(Request $r)
	{
		$ext = Extracurricular::find($r->id);
		$ext->student()->wherePivot('isactive', true)->updateExistingPivot($r->student_id, ['outdate' => date('Y-m-d'), 'isactive' => false]);
		return back()->with('success', 'Anggota organisasi berhasil dinonaktifkan.');
	}

	// 
	public function update(Request $r)
	{
		$this->validate($r, [
			'uname'		=> 'required',
			'umentor'	=> 'required',
			'utime'		=> 'required|size:5'
		], [
			'uname.required'		=> 'Nama ekstrakurikuler tidak boleh kosong.',
			'umentor.required'	=> 'Mentor / Pembina ekstrakurikuler tidak boleh kosong.',
			'utime.required'		=> 'Jam pelaksanaan tidak boleh kosong.',
			'utime.size'				=> 'Format jam salah.',
			]
		);

		Extracurricular::find($r->upid)->update([
			'name'				=> $r->uname,
			'description'	=> $r->udescription,
			'user_id'			=> $r->umentor,
			'day'					=> $r->uday,
			'time'				=> $r->utime
		]
		);
		return back()->with('success', 'Data ekstrakurikuler berhasil diubah.');
	}
	
	// 
	private function convertDate($date)
	{
		$d = explode('/', $date);
		return $d[2] . '-' . $d[1] . '-' . $d[0];
	}
	
}