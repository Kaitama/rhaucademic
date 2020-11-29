<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Student;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



// Route::get('students/suggest/', function(Request $request){
// 	$s = $request->get('search');
// 	$data = Student::where('stambuk','like','%'.$s.'%')->orWhere('name','like','%'.$s.'%')->get();
// 	$data->map(function ($dt) {
// 		$url = asset('assets/img/student');
// 		if($dt->photo) { $img = $url . '/' . $dt->photo; }
// 		else {if($dt->gender == 'P') $img = $url . '/female.jpg'; else $img = $url . '/male.jpg';}
// 		$dt['image'] = $img;
// 		return $dt;
// 	});
// 	return response()->json(["data" => $data]);
// });

// search students
// Route::get('students/list/{query}', function($s){
// 	$results = Student::where('stambuk','like','%'.$s.'%')->orWhere('name','like','%'.$s.'%')->where('status', true)->get();
// 	// $results = Student::all();
// 	$data = array();
// 	foreach ($results as $res) {
// 		$data[] = ['name' => $res->stambuk . ' - ' . $res->name, 'value' => $res->id, 'text' => $res->name, 'stambuk' => $res->stambuk, 'url' => route('student.profile', $res->stambuk)];
// 	}
// 	return response()->json(['success' => true, 'results' => $data]);
// });



Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});


// CLIENT MOBILE ROUTE
Route::group(['prefix' => 'client'], function () {
	Route::post('/login', [UserController::class, 'mobileLogin']);
	Route::post('/checkstudent', [ApiController::class, 'checkifexists']);
	Route::post('/register', [UserController::class, 'mobileRegister']);
	// Route::get('/logout', 'UsersController@logout')->middleware('auth:api');
});
Route::group(['prefix' => 'client', 'middleware' => 'auth:api'], function(){
	
	
	Route::get('/checkauth', function(){
		return response()->json(['success' => true, 'message' => 'ini pesan terproteksi']);
	});
	Route::get('/userdata', [UserController::class, 'userData']);
	Route::get('/profile', [ApiController::class, 'getProfile']);
	Route::get('/payment/{id}', [ApiController::class, 'getTuition']);
	Route::get('/achievement/{id}', [ApiController::class, 'getAchievement']);
	Route::get('/offense/{id}', [ApiController::class, 'getOffense']);
	Route::get('/permit/{id}', [ApiController::class, 'getPermit']);
	Route::get('/organization/{id}', [ApiController::class, 'getOrganization']);
	Route::get('/extracurricular/{id}', [ApiController::class, 'getExtracurricular']);
	Route::post('/changeemail', [ApiController::class, 'changeEmail']);
	Route::post('/changename', [ApiController::class, 'changeName']);
	Route::post('/changeusername', [ApiController::class, 'changeUsername']);
	Route::post('/changepassword', [ApiController::class, 'changePassword']);
	Route::get('/logout', [UserController::class, 'mobileLogout']);
	Route::get('/carrousels', [ApiController::class, 'carrousels']);
});