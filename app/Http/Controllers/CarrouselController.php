<?php

namespace App\Http\Controllers;

use App\Carrousel;
use Illuminate\Http\Request;
use Storage;

class CarrouselController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		//
		$carrousels = Carrousel::all();
		return view('dashboard.carrousel.index', ['carrousels' => $carrousels]);
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
	public function store(Request $r)
	{
		//
		$this->validate($r, [
			'title'			=> 'required',
			'img'			=> 'image|mimes:jpg,jpeg,png',
		], [
			'title.required'	=> 'Judul informasi tidak boleh kosong.',
			'img.image'			=> 'File banner harus berupa gambar.',
			'img.mimes'			=> 'Format file banner hanya .jpg, .jpeg, atau .png',
			]
		);
		$img = 'noimage.png';
		if($r->hasFile('img')) {
			$file = $r->file('img');
			$img = $file->store('/', 'carrousel');
		}
		Carrousel::create([
			'title'		=> $r->title,
			'image'		=> $img,
			'link'		=> $r->link
			]
		);
		
		return back()->with('success', 'Banner informasi berhasil ditambahkan.');
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\Carrousel  $carrousel
	* @return \Illuminate\Http\Response
	*/
	public function show(Carrousel $carrousel)
	{
		//
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Carrousel  $carrousel
	* @return \Illuminate\Http\Response
	*/
	public function edit(Carrousel $carrousel)
	{
		//
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Carrousel  $carrousel
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Carrousel $carrousel)
	{
		//
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\Carrousel  $carrousel
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Request $r)
	{
		//
		$car = Carrousel::find($r->id);
		if($car->image != 'noimage.png'){
			$img = public_path('assets/img/carrousel').'/'.$car->image;
				if(file_exists($img)) unlink($img);
		}
		$car->delete();
		return back()->with('success', 'Data banner informasi berhasil dihapus.');
	}
}