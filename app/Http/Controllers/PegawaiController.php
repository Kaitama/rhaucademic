<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\User;
use App\Userprofile;
use Image;
use Storage;

class PegawaiController extends Controller
{
	
	public function index()
	{
		//
		$roles = Role::where('name', '<>', 'developer')->get();
		$users = User::where('level', '>' , 1)->where('level', '<' , 9)->latest()->paginate(10);
		return view('dashboard.pegawai.index', ['users' => $users, 'roles' => $roles]);
	}
	
	public function create()
	{
		//
		$roles = Role::where('name', '<>', 'developer')->get();
		return view('dashboard.pegawai.create', ['roles' => $roles]);
	}
	
	public function store(Request $request)
	{
		
		$this->validate($request, [
			'name' 			=> 'required',
			'username' 	=> 'required|alpha_num|min:3|unique:users',
			'email'			=> 'required|email|unique:users',
			'photo'			=> 'image|mimes:jpg,jpeg,png',
		], [
			'name.required' => 'Nama tidak boleh kosong.',
			'username.required' => 'Username tidak boleh kosong.',
			'username.alpha_num' => 'Username hanya boleh huruf & angka.',
			'username.min' => 'Username minimal 3 karakter.',
			'username.unique' => 'Username sudah terdaftar.',
			'email.required'	=> 'Email tidak boleh kosong.',
			'email.email'	=> 'Email tidak valid.',
			'email.unique' => 'Email sudah terdaftar',
			'photo.image' => 'Photo harus berupa file image.',
			'photo.mimes'	=> 'Format photo tidak valid.'
			]
		);
		
		$photo = null;
		
		if($request->hasFile('photo'))
		{
			$file = $request->file('photo');
			$photo = $file->store('/');
			$thumb = Image::make($file)->fit(400, 400, function ($constraint) 
			{
				$constraint->aspectRatio();$constraint->upsize();
			})->crop(400, 400);
			$thumb->save(public_path('assets/img/user/') . $photo);
		} 
		
		$user = User::create([
			'level' => 5,
			'name' => $request->name,
			'username' => $request->username,
			'email'	=> $request->email,
			'password' => Hash::make('password'),
			'photo' => $photo,
			]
		);
		Userprofile::create(['user_id' => $user->id]);
		
		if($request->role){
			$user->syncRoles($request->role);
		}
		
		return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil ditambahkan.');
	}
	
	
	public function show($id)
	{
		//
	}
	
	
	public function update(Request $request)
	{
		//
		$this->validate($request, [
			'name' 			=> 'required',
			'username' 	=> 'required|alpha_num|min:3|unique:users,username,' . $request->id,
			'email'			=> 'required|email|unique:users,email,' . $request->id,
			'photo'			=> 'image|mimes:jpg,jpeg,png',
		], [
			'name.required' => 'Nama tidak boleh kosong.',
			'username.required' => 'Username tidak boleh kosong.',
			'username.alpha_num' => 'Username hanya boleh huruf & angka.',
			'username.min' => 'Username minimal 3 karakter.',
			'username.unique' => 'Username sudah terdaftar.',
			'email.required'	=> 'Email tidak boleh kosong.',
			'email.email'	=> 'Email tidak valid.',
			'email.unique' => 'Email sudah terdaftar',
			'photo.image' => 'Photo harus berupa file image.',
			'photo.mimes'	=> 'Format photo tidak valid.'
			]
		);
		
		$user = User::where('id', $request->id)->first();
		$oldphoto = $user->photo;
		$photo = $oldphoto;
		
		if($request->hasFile('ephoto'))
		{
			$file = $request->file('ephoto');
			$photo = $file->store('/');
			$thumb = Image::make($file)->fit(400, 400, function ($constraint) 
			{
				$constraint->aspectRatio();$constraint->upsize();
			})->crop(400, 400);
			$thumb->save(public_path('assets/img/user/') . $photo);
			if($oldphoto){
				$ori = public_path('assets/img/originals').'/'.$oldphoto;
				$thb = public_path('assets/img/user').'/'.$oldphoto;
				if(file_exists($ori)) unlink($ori);
				if(file_exists($thb)) unlink($thb);
			}
		} 
		
		$usr = User::find($request->id);
		$usr->update([
			'name'		=> $request->name,
			'username'=> $request->username,
			'email'		=> $request->email,
			'photo'		=> $photo,
			]
		);
		
		if($request->role){
			if($request->role[0] == 'del'){
				$user->syncRoles([]);
			} else {
				$user->syncRoles($request->role);
				activity()->performedOn($usr)->withProperties(['attributes' => ['name' => $usr->name, 'role' => $request->role]])->log('edited');
			}
		}
		
		return back()->with('success', 'Data pegawai berhasil diubah.'); 
	}
	
	public function destroy(Request $request)
	{
		//
		$user = User::find($request->id); 
		if($user->photo){
			$ori = public_path('assets/img/user').'/'.$user->photo;
			$thumb = public_path('assets/img/user/thumbnail').'/'.$user->photo;
			if(file_exists($ori)) unlink($ori);
			if(file_exists($thumb)) unlink($thumb);
		}
		$user->delete();
		return redirect()->route('pegawai.index')->with('success', 'Pegawai berhasil dihapus.');
	}
	
	public function resetpassword(Request $request)
	{
		
		User::find($request->idtoreset)->update([
			'password' => Hash::make('password')
			]
		);
		return back()->with('success', 'Password user berhasil direset.');
	}
	
}