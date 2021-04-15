<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Classroom;
use App\Dormroom;
use App\Tuition;
use App\Offense;
use App\Permit;
use App\Carrousel;
use App\Student;
use App\Organization;
use App\Extracurricular;
use Carbon\Carbon;

class DashboardController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		//
		$user = User::find(Auth::id());
		if($user->level == 9) return redirect()->route('student.profile', $user->student->stambuk);
		$passnotify = false;
		if (Hash::check('password', $user->password)) {
			$passnotify = true;
		}
		// 
		$today = date('Y-m-d H:i:s');
		// $tomorrow = date('Y-m-d H:i:s',strtotime($date1 . "+1 days"));
		$offenses 	= Offense::whereDate('created_at', Carbon::today())->count();
		$tuitions 	= Tuition::whereDate('created_at', Carbon::today())->count();
		$checkouts	= Permit::whereDate('checkout', Carbon::today())->count();
		$checkins		= Permit::whereDate('checkin', Carbon::today())->count();
		// $checkouts	= Permit::where('checkout', '>=', date('Y-m-d'))->where('checkout', '<=', date('Y-m-d H:i:s'))->count();
		// $checkins		= Permit::where('checkin', '>=', date('Y-m-d'))->where('checkin', '<=', date('Y-m-d H:i:s'))->count();
		$classrooms = Classroom::orderBy('level')->orderBy('name')->get();
		$dormrooms	= Dormroom::orderBy('building')->orderBy('name')->get();
		$carousels	= Carrousel::limit(5)->get();
		$users			= User::where('level', '>', 1)->where('level', '<', 9)->count();
		$stdactive	= Student::where('status', 1)->count();
		$alumni			= Student::where('status', 2)->count();
		$stdinactiv	= Student::where('status', '>', 2)->count();
		$organiz		= Organization::where('active', true)->count();
		$extracs		= Extracurricular::where('active', true)->count();
		
		return view('dashboard.index.index', ['passnotify' => $passnotify, 'offenses' => $offenses, 'tuitions' => $tuitions, 'checkouts' => $checkouts, 'checkins' => $checkins, 'classrooms' => $classrooms, 'dormrooms' => $dormrooms, 'carousels' => $carousels, 'users' => $users, 'stdactive' => $stdactive, 'alumni' => $alumni, 'stdinactiv' => $stdinactiv, 'organizations' => $organiz, 'extracurriculars' => $extracs]);
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
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function show($id)
	{
		//
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function edit($id)
	{
		//
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, $id)
	{
		//
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id)
	{
		//
	}
}