<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Student;
use App\Studentprofile;
use App\Achievement;
use App\Offense;
use App\Tuition;
use App\Permit;
use App\Organization;
use App\Carrousel;

class ApiController extends Controller
{
	public function checkifexists(Request $request)
	{
		$student = Student::where('nokk', $request->nokk)->where('nik', $request->nik)->first();

		if($student == null){
			return response()->json(['success' => false, 'message' => 'Tidak ada santri dengan data tersebut.'], 401);
		} else {
			if($student->user_id) {
				return response()->json(['success' => false, 'message' => 'Akun sudah terdaftar, silahkan login.'], 401);
			}

			return response()->json(['success' => true, 'sId' => $student->id, 'message' => 'Santri terdaftar.'], 200);
		}
	}

	public function getProfile()
	{
		$id = request()->user()->id;
		$student = Student::where('user_id', $id)->with('studentprofile')->with('classroom')->with('dormroom')->first();
		if($student->photo == null ){
			if($student->gender == 'P') $student->photo = 'female.jpg';
			else $student->photo = 'male.jpg';
		}
		return response()->json(['success' => true, 'data' => $student], 200);
	}

	public function getTuition($id)
	{
		$tuitions = Tuition::where('student_id', $id)->orderBy('foryear', 'desc')->orderBy('formonth', 'desc')->limit(18)->get();
		if($tuitions)
			return response()->json(['success' => true, 'data' => $tuitions], 200);
		return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 401);
	}

	public function getAchievement($id)
	{
		$achievements = Achievement::where('student_id', $id)->orderBy('date', 'desc')->get();
		if($achievements)
			return response()->json(['success' => true, 'data' => $achievements], 200);
		return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 401);
	}

	public function getOffense($id)
	{
		$offenses = Offense::where('student_id', $id)->orderBy('date', 'desc')->get();
		if($offenses)
			return response()->json(['success' => true, 'data' => $offenses], 200);
		return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 401);
	}

	public function getPermit($id)
	{
		$permits = Permit::where('student_id', $id)->orderBy('datefrom', 'desc')->get();
		if($permits){
			foreach ($permits as $permit) {
				$permit->signer = $permit->user['name'];
			}
			return response()->json(['success' => true, 'data' => $permits], 200);
		}
		return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 401);
	}

	public function getOrganization($id)
	{
		$student = Student::find($id);
		$orgs = $student->organization()->wherePivot('student_id', $id)->orderBy('organization_student.joindate', 'desc')->get();
		if($orgs) return response()->json(['success' => true, 'data' => $orgs], 200);
		return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 401);
	}

	public function getExtracurricular($id)
	{
		$student = Student::find($id);
		$extras = $student->extracurricular()->wherePivot('student_id', $id)->orderBy('extracurricular_student.joindate', 'desc')->get();
		if($extras) return response()->json(['success' => true, 'data' => $extras], 200);
		return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 401);
	}

	public function changeEmail(Request $request)
	{
		$user = request()->user();
		if(!$user) return response()->json(['success' => true, 'message' => 'Autentikasi gagal, silahkan login kembali.'], 200);
		$email = $request->email;
		$pass = $request->password;
		if(Hash::check($pass, $user->password))
		{
			if(User::where('email', $email)->first()) return response()->json(['success' => false, 'message' => 'Gagal, email sudah terdaftar!'], 401);
			$user->update([
				'email' => $email,
			]);
			return response()->json(['success' => true, 'message' => 'Sukses, silahkan login kembali.'], 200);
		}
		return response()->json(['success' => false, 'message' => 'Password yang anda masukkan salah!'], 401);
	}

	public function changePassword(Request $request)
	{
		$user = request()->user();
		if(!$user) return response()->json(['success' => true, 'message' => 'Autentikasi gagal, silahkan login kembali.'], 200);
		$password = $request->password;
		$newPassword = $request->newpassword;
		if(Hash::check($password, $user->password))
		{
			
			$user->update(['password' => Hash::make($newPassword)]);
			return response()->json(['success' => true, 'message' => 'Sukses, silahkan login kembali.'], 200);
		}
		return response()->json(['success' => false, 'message' => 'Password yang anda masukkan salah!'], 401);
	}

	public function changeName(Request $request)
	{
		$user = request()->user();
		if(!$user) return response()->json(['success' => true, 'message' => 'Autentikasi gagal, silahkan login kembali.'], 200);
		$password = $request->password;
		$name = $request->name;
		if(Hash::check($password, $user->password))
		{
			$user->update([
				'name' => $name,
			]);
			return response()->json(['success' => true, 'message' => 'Sukses, silahkan login kembali.'], 200);
		}
		return response()->json(['success' => false, 'message' => 'Password yang anda masukkan salah!'], 401);

	}

	public function changeUsername(Request $request)
	{
		$user = request()->user();
		if(!$user) return response()->json(['success' => true, 'message' => 'Autentikasi gagal, silahkan login kembali.'], 200);
		$password = $request->password;
		$username = $request->username;
		if(Hash::check($password, $user->password))
		{
			if(User::where('username', $username)->first()) return response()->json(['success' => false, 'message' => 'Gagal, username sudah terdaftar!'], 401);
			$user->update([
				'username' => $username,
			]);
			return response()->json(['success' => true, 'message' => 'Sukses, silahkan login kembali.'], 200);
		}
		return response()->json(['success' => false, 'message' => 'Password yang anda masukkan salah!'], 401);
	}

	public function carrousels()
	{
		$cars = Carrousel::orderBy('created_at', 'desc')->limit(5)->get();
		return response()->json(['success' => true, 'data' => $cars], 200);

	}

}