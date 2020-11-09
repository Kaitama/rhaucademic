<?php

namespace App\Http\Controllers;

use Image;
use Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Student;


class UserController extends Controller
{
	public function  settings(User $user)
	{
		return view('dashboard.user.settings');
	}
	
	public function updatepict(Request $request)
	{
		$this->validate($request, [
			'photo'			=> 'image|mimes:jpg,jpeg,png',
		], [
			'photo.image' => 'Photo harus berupa file image.',
			'photo.mimes'	=> 'Format photo tidak valid.'
			]
		);
		
		$user = User::find(Auth::id());
		$photo = null;
		$oldphoto = $user->photo;
		
		if($request->hasFile('photo'))
		{
			$file = $request->file('photo');
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
		
		$user->update([
			'photo' => $photo
			]
		);
		return back()->with('success', 'Foto profil berhasil diubah.');
	}
	
	public function updatelogin(Request $request, User $user)
	{
		$this->validate($request, [
			'name' 			=> 'required',
			'username' 	=> 'required|alpha_num|min:3|unique:users,username,' . Auth::id(),
			'email'			=> 'required|email|unique:users,email,' . Auth::id(),
		], [
			'name.required' => 'Nama tidak boleh kosong.',
			'username.required' => 'Username tidak boleh kosong.',
			'username.alpha_num' => 'Username hanya boleh huruf & angka.',
			'username.min' => 'Username minimal 3 karakter.',
			'username.unique' => 'Username sudah terdaftar.',
			'email.required'	=> 'Email tidak boleh kosong.',
			'email.email'	=> 'Email tidak valid.',
			'email.unique' => 'Email sudah terdaftar',
			]
		);
		
		$user->find(Auth::id())->update([
			'name'		=> $request->name,
			'username'=> $request->username,
			'email'		=> $request->email,
			]
		);
		return back()->with('success', 'Login information berhasil diubah.');
	}
	
	public function updatepassword(Request $request)
	{
		$this->validate($request, [
			'old_password'	=> 'required',
			'password'			=> 'required|min:8|confirmed',
		], [
			'old_password.required' => 'Password lama tidak boleh kosong.',
			'password.required' => 'Password tidak boleh kosong.',
			'password.min' => 'Password minimal 8 karakter.',
			'password.confirmed' => 'Konfirmasi password salah.',
			]
		);
		
		$usr = User::find(Auth::id());
		
		if (Hash::check($request->old_password, $usr->password)) {
			$usr->update([
				'password' => Hash::make($request->password),
				]
			);
			return back()->with('success', 'Password berhasil diubah.');
		} else {
			return back()->with('error', 'Password lama anda salah.');
		}
	}
	
	// client login
	public function mobileLogin(Request $request)
	{
		if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) 
		{
			$user = Auth::user();
			if($user->student == null){
				return response()->json([
					'success' => false,
					'message' => 'Tidak ada santri yang terdaftar pada akun ini.',
				], 400);
			}
			
			$success['token'] = $user->createToken('appToken')->accessToken;
			$user->student;
			
			return response()->json([
				'success' => true,
				'token' => $success,
				'message' => 'Login berhasil.',
			], 200);
		} else {
			return response()->json([
				'success' => false,
				'message' => 'Username atau password salah.',
			],401);
		}
	}
	
	// client register
	public function mobileRegister(Request $request)
	{
		// 
		$id = $request->sId;
		$checkemail = User::where('email', $request->email)->first();
		if($checkemail){
			return response()->json([
				'success' => false,
				'message' => 'Email sudah terdaftar.',
			],401);
		}
		$checkusername = User::where('username', $request->username)->first();
		if($checkusername){
			return response()->json([
				'success' => false,
				'message' => 'Username sudah terdaftar.',
			],401);
		}
		$student = Student::find($id);
		if($student){
			$user = User::create([
				'name' => $request->name,
				'username' => $request->username,
				'email'	=> $request->email,
				'level'	=> 9,
				'password'	=> Hash::make($request->password),
				]
			);
			$student->update(['user_id' => $user->id,]);
			$success['token'] = $user->createToken('appToken')->accessToken;
			
			return response()->json([
				'success' => true, 
				'token' => $success
			], 200);
		} 
			return response()->json([
				'success' => false,
				'message' => 'Registrasi gagal.',
			],401);
		
	}
	
	// client logout
	public function mobileLogout(Request $request)
	{
		
		return response()->json([
			'success' => true,
			'message' => 'User logged out successfully'
			]
		);
	}

// client userdata
public function userData()
{
	$id = request()->user()->id;
	$student = Student::where('user_id', $id)->with('classroom')->with('dormroom')->first();
	// $student['kelas'] = $student->classroom['name'];
	if($student->photo == null ){
		if($student->gender == 'P') $student->photo = 'female.jpg';
		else $student->photo = 'male.jpg';
	}
	$student->user = request()->user()->name;
	$student->username = request()->user()->username;
	$student->email = request()->user()->email;

	return response()->json(['success' => true, 'data' => $student], 200);
}

}