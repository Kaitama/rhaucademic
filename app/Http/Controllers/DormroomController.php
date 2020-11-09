<?php

namespace App\Http\Controllers;

use App\Dormroom;
use App\Building;
use Illuminate\Http\Request;

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
				]);
		
					Dormroom::create([
						'building'		=> $request->building,
						'name'				=> $request->dorm_name,
						'capacity'		=> $request->dorm_capacity
					]);
		
					return back()->with('success', 'Data asrama berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dormroom  $dormroom
     * @return \Illuminate\Http\Response
     */
    public function show(Dormroom $dormroom)
    {
        //
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
				]);
		
					Dormroom::find($request->id)->update([
						'building' => $request->building,
						'name'				=> $request->name,
						'capacity'		=> $request->capacity
					]);
		
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
}