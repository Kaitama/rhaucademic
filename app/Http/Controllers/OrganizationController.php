<?php

namespace App\Http\Controllers;

use App\Organization;
use App\Student;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		//
		$actives = Organization::where('active', true)->get();
		$nonactives = Organization::where('active', false)->get();
		return view('dashboard.organization.index', ['actives' => $actives, 'nonactives' => $nonactives]);
	}
	
	public function activate(Request $req)
	{
		
		Organization::find($req->activate_id)->update(['active' => true]);
		
		return back()->with('success', 'Organisasi berhasil diaktifkan.');
		
	}
	public function deactivate(Request $req)
	{
		$org = Organization::find($req->deactivate_id);
		// $stds = $org->student()->id;
		$stds = $org->student()->wherePivot('isactive', true)->get();
		foreach ($stds as $std) {
			$org->student()->updateExistingPivot($std, ['isactive' => false, 'outdate' => date('Y-m-d')]);
		}
		$org->update(['active' => false]);
		
		return back()->with('success', 'Organisasi berhasil dinonaktifkan.');
		
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
			'name'				=> 'required',
			'focus'				=> 'required',
			'starting_at'	=> 'required',
		],[
			'name.required'				=> 'Nama organisasi tidak boleh kosong.',
			'focus.required'			=> 'Fokus organisasi tidak boleh kosong.',
			'starting_at.required'=> 'Tanggal berdiri organisasi tidak boleh kosong.'
			]
		);
		$date = explode('/', $request->starting_at);
		$starting_at = $date[2] . '-' . $date[1] . '-' . $date[0];
		Organization::create([
			'name' 				=> $request->name,
			'focus'				=> $request->focus,
			'description'	=> $request->description,
			'starting_at'	=> $starting_at,
			'active'			=> $request->active,
			]);
			return back()->with('success', 'Data organisasi berhasil ditambahkan.');
		}
		
		/**
		* Display the specified resource.
		*
		* @param  \App\Organization  $organization
		* @return \Illuminate\Http\Response
		*/
		public function show(Organization $organization, $id)
		{
			//
			$org = Organization::find($id);
			// $org = $org->pivot->id();
			// dd($org->student()->wherePivot('isactive', true)->get());
			return view('dashboard.organization.show', ['org' => $org]);
		}
		
		/**
		* add students
		*/
		public function addstudents(Request $req)
		{
			
			$this->validate($req, [
				'students' => 'required',
				'joindate' => 'required',
			], [
				'students.required' => 'Nama santri tidak boleh kosong.',
				'joindate.required' => 'Tanggal bergabung tidak boleh kosong.',
				]
			);
			
			$org = Organization::find($req->organization_id);
			$students = explode(',',$req->students);
			
			$joindate = $this->convertDate($req->joindate);
			foreach ($students as $std) {
				$student = Student::find($std);
				if($student->organization()->wherePivot('organization_id', $req->organization_id)->wherePivot('isactive', true)->exists()){
					return back()->with('error', $student->name . ' sudah terdaftar sebagai sebagai anggota.');
				}
				$org->student()->attach($std, ['position' => $req->position, 'description' => $req->description, 'joindate' => $joindate]);
			}
			return back()->with('success', 'Anggota organisasi berhasil ditambahkan.');
		}
		
		/**
		* edit students
		*/
		public function editstudents(Request $req, $id)
		{
			
			$org = Organization::find($id);
			$joindate = $this->convertDate($req->joindate);
			// $std = $org->student->where('organization_student.student_id', $req->student_id)->where('organization_student.organization_id', $id)->sortByDesc('organization_student.joindate')->first();
			
			// if($req->position != $std->organization_student->position) {
				$org->student()->wherePivot('isactive', true)->updateExistingPivot($req->student_id, ['outdate'=> $joindate, 'isactive' => false]);
				$org->student()->attach($req->student_id, ['position'=> $req->position, 'description' => $req->description, 'joindate' => $joindate]);
			// } else {
			// 	$org->student()->wherePivot('created_at', $std->organization_student->created_at)->updateExistingPivot($req->student_id, ['description' => $req->description, 'joindate' => $joindate]);
			// }
			return back()->with('success', 'Anggota organisasi berhasil diubah.');
		}
		
		/**
		* activate - deactivate student from organization
		*/
		public function toggleisactive(Request $req, $id)
		{
			$org = Organization::find($id);
			$org->student()->wherePivot('isactive', true)->updateExistingPivot($req->student_id, ['outdate' => date('Y-m-d'),'isactive' => false]);
			return back()->with('success', 'Anggota organisasi berhasil dinonaktifkan.');
		}
		
		/**
		* Show the form for editing the specified resource.
		*
		* @param  \App\Organization  $organization
		* @return \Illuminate\Http\Response
		*/
		public function edit(Organization $organization)
		{
			//
		}
		
		/**
		* Update the specified resource in storage.
		*
		* @param  \Illuminate\Http\Request  $request
		* @param  \App\Organization  $organization
		* @return \Illuminate\Http\Response
		*/
		public function update(Request $request)
		{
			//
			$this->validate($request, [
				'uname'				=> 'required',
				'ufocus'				=> 'required',
				'ustarting_at'	=> 'required',
			],[
				'uname.required'				=> 'Nama organisasi tidak boleh kosong.',
				'ufocus.required'			=> 'Fokus organisasi tidak boleh kosong.',
				'ustarting_at.required'=> 'Tanggal berdiri organisasi tidak boleh kosong.'
				]
			);
			$date = explode('/', $request->ustarting_at);
			$starting_at = $date[2] . '-' . $date[1] . '-' . $date[0];
			Organization::find($request->upid)->update([
				'name'				=> $request->uname,
				'focus'				=> $request->ufocus,
				'description'	=> $request->udescription,
				'starting_at'	=> $starting_at
				]
			);
			return back()->with('success', 'Data organisasi berhasil diubah.');
		}
		
		/**
		* Remove the specified resource from storage.
		*
		* @param  \App\Organization  $organization
		* @return \Illuminate\Http\Response
		*/
		public function destroy(Organization $organization)
		{
			//
		}
		
		private function convertDate($date)
		{
			$d = explode('/', $date);
			return $d[2] . '-' . $d[1] . '-' . $d[0];
		}
	}