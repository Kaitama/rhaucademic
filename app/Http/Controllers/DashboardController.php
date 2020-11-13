<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Tuition;
use App\Offense;
use App\Permit;

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
		$passnotify = false;
		if (Hash::check('password', $user->password)) {
			$passnotify = true;
		}
		// 
		$today = date('Y-m-d H:i:s');
		// $tomorrow = date('Y-m-d H:i:s',strtotime($date1 . "+1 days"));
		$offenses 	= Offense::where('date', date('Y-m-d'))->count();
		$tuitions 	= Tuition::where('paydate', date('Y-m-d'))->count();
		$checkouts	= Permit::where('checkout', '>=', date('Y-m-d'))->where('checkout', '<=', date('Y-m-d H:i:s'))->count();
		$checkins		= Permit::where('checkin', '>=', date('Y-m-d'))->where('checkin', '<=', date('Y-m-d H:i:s'))->count();
		
		
		return view('dashboard.index.index', ['passnotify' => $passnotify, 'offenses' => $offenses, 'tuitions' => $tuitions, 'checkouts' => $checkouts, 'checkins' => $checkins]);
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